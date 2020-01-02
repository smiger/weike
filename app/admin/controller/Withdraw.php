<?php
namespace app\admin\controller;

use app\home\model\CreditRecord;
use app\home\model\Member;
use app\admin\model\Config;
use think\Db;
use think\Log;

class Withdraw extends  Base
{
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的提现','','error');
            }
            $status = \app\admin\model\Withdraw::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['uid|realname|mobile'] = ['like', "%{$params['keyword']}%"];
            }
            if(!empty($params['status'])){
                $where['status'] = ['in',$params['status']];
            }
        }

        $total = \app\admin\model\Withdraw::getCount($where);
        $list = \app\admin\model\Withdraw::getPagination($where, 15, $total, "id DESC");
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total,
            'credit2Total' => \app\admin\model\Withdraw::getCredit2Total($where)
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        $parent_money = 0;
        $money = 0;
        if(check_id($id)){
            $item = \app\admin\model\Withdraw::getInfoById($id);

            $money = $item['credit2'] - $item['fee'];

            $setting = ['invitation_withdraw_rebate' => 0];
            $config = Config::getInfo();
            if(check_array($config['setting'])){
                $setting = $config['setting'];
                if(!empty($setting['invitation_withdraw_rebate'])){
                    $setting['invitation_withdraw_rebate'] = round(floatval($setting['invitation_withdraw_rebate']/100),2);
                }
            }

            //有推荐人，提现时，系统将自动扣除的红利给到你的推荐人
            $memberInfo = Member::getUserInfoById($item['uid']);
            if ($memberInfo && $memberInfo['parent_uid'] > 0 && $setting['invitation_withdraw_rebate'] > 0) {
                $parent_money = round($item['credit2'] * $setting['invitation_withdraw_rebate'], 2);
                $money -= $parent_money;
            }
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'origin' => $item->getData(),
            'parent_money' => $parent_money,
            'money' => $money
        ]);
    }

    public function save(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Withdraw::getInfoById($id);
        }

        if (empty($item) || is_null($item)) {
            message("审核失败",'','error');
        }

        $origin = $item->getData();
        if ($origin['update_time'] > 0) {
            message("审核失败",'','error');
        }

        $params = array_trim(request()->post());

        Db::startTrans();

        $update = [];
        $update['status'] = intval($params['status']);
        $update['note'] = $params['note'];
        $update['update_time'] = TIMESTAMP;
        $status = \app\admin\model\Withdraw::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message("审核失败",'','error');
        }

        //审核通过时
        if ($origin['status'] != 1 && $update['status'] == 1) {
            $setting = ['invitation_withdraw_rebate' => 0];
            $config = Config::getInfo();
            if(check_array($config['setting'])){
                $setting = $config['setting'];
                if(!empty($setting['invitation_withdraw_rebate'])){
                    $setting['invitation_withdraw_rebate'] = round(floatval($setting['invitation_withdraw_rebate']/100),2);
                }
            }

            //有推荐人，提现时，系统将自动扣除的红利给到你的推荐人
            $memberInfo = Member::getUserInfoById($item['uid']);
            if ($memberInfo && $memberInfo['parent_uid'] > 0 && $setting['invitation_withdraw_rebate'] > 0) {
                $parent_money = round($item['credit2'] * $setting['invitation_withdraw_rebate'], 2);

                $status1 = Member::updateCreditById($memberInfo['parent_uid'], 0, $parent_money);
                if(!$status1){
                    Db::rollback();
                    message("审核失败",'','error');
                }

                $status3 = CreditRecord::addInfo([
                    'uid' => $memberInfo['parent_uid'],
                    'type' => 'credit2',
                    'num' => $parent_money,
                    'title' => '提现审核',
                    'remark' => "徒弟[" . $memberInfo['username'] . "]提现[" . $item['credit2'] . "]，获得推荐收入{$parent_money}元。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message("审核失败",'','error');
                }
            }
        }

        //审核未通过时需要退回金额给用户
        if ($update['status'] == -1) {
            $credit2 = $item['credit2'] + $item['fee'];
            $status1 = Member::updateCreditById($item['uid'], 0, $credit2);
            if(!$status1){
                Db::rollback();
                message('审核失败：-1','','error');
            }
            $status2 = CreditRecord::addInfo([
                'uid' => $item['uid'],
                'type' => 'credit2',
                'num' => $credit2,
                'title' => '提现审核',
                'remark' => "申请提现到账号：" . $item['account'] . "审核未通过，退回{$credit2}余额。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status2){
                Db::rollback();
                message('审核失败：-2','','error');
            }
        }

        Db::commit();

        message("审核成功",'reload','success');
    }
}
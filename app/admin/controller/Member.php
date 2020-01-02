<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/2/1
 * Time: 21:23
 */

namespace app\admin\controller;

use app\admin\model\CreditRecord;
use think\Db;

class Member extends Base
{
    //会员列表
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的记录','','error');
            }
            $status = \app\admin\model\Member::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }
        
        $params = request()->get();
        $list = \app\admin\model\Member::getList($params);
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => \app\admin\model\Member::getTotal(),
            'credit2Total' => \app\admin\model\Member::getCredit2Total()
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Member::getInfoById($id);
            if(request()->isAjax()){
                $params = array_trim(request()->post());
                if (!empty($params['password'])) {
                    $params['salt'] = random(8);
                    $params['password'] = md5_password($params['password'],$params['salt']);
                } else {
                    unset($params['password']);
                }
                param_is_or_no(['is_check'],$params);
                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                $status = \app\admin\model\Member::updateInfoById($id,$params);
                if(!$status){
                    message("{$op}失败",'','error');
                }
                message("{$op}成功",'reload','success');
            }
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item
        ]);
    }

    //会员充值列表
    public function charge(){
        if(request()->isAjax()){
            $this->charge_save();
        }

        $params = request()->get();
        $list = \app\admin\model\Charge::getList($params);
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => \app\admin\model\Charge::getTotal()
        ]);
    }

    //会员充值保存
    private function charge_save(){
        $uid = trim(params('uid'));
        if(!check_id($uid)){
            message('会员编号输入错误','','error');
        }
        $member = \app\admin\model\Member::getInfoById($uid);
        if(empty($member)){
            message('会员信息不存在','','error');
        }
        $num = floatval(trim(params('num')));
        if(empty($num)){
            message('请输入充值金额','','error');
        }
        Db::startTrans();
        $status1 = \app\admin\model\Member::updateCreditById($uid, 0, $num);
        if(!$status1){
            Db::rollback();
            message('充值失败：-1','','error');
        }
        $status2 = CreditRecord::addInfo([
            'uid' => $member['uid'],
            'type' => 'credit2',
            'num' => $num,
            'title' => '后台会员充值',
            'remark' => "管理员后台操作，".($num>0?'充值':'扣除').abs($num)."余额。"
        ]);
        if(!$status2){
            Db::rollback();
            message('充值失败：-2','','error');
        }
        $status3 = \app\admin\model\Charge::addInfo([
            'admin_id' => $this->administrator['id'],
            'uid' => $member['uid'],
            'type' => 'credit2',
            'num' => $num,
            'remark' => "管理员后台操作，".($num>0?'充值':'扣除').abs($num)."余额。"
        ]);
        if(!$status3){
            Db::rollback();
            message('充值失败：-3','','error');
        }
        Db::commit();
        message('充值成功','reload','success');
    }

}
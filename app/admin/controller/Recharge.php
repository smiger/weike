<?php
namespace app\admin\controller;

use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

class Recharge extends Base
{
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的记录','','error');
            }
            $status = \app\admin\model\Recharge::deleteByIds($ids);
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

        $total = \app\admin\model\Recharge::getCount($where);
        $list = \app\admin\model\Recharge::getPagination($where, 15, $total, "id DESC");
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Recharge::getInfoById($id);
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'origin' => $item->getData()
        ]);
    }

    public function save(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Recharge::getInfoById($id);
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
        $status = \app\admin\model\Recharge::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message("审核失败",'','error');
        }

        //审核未通过时需要退回金额给用户
        if ($update['status'] == 1) {
            $credit2 = $item['credit2'];
            $status1 = Member::updateCreditById($item['uid'], 0, $credit2);
            if(!$status1){
                Db::rollback();
                message('审核失败：-1','','error');
            }
            $status2 = CreditRecord::addInfo([
                'uid' => $item['uid'],
                'type' => 'credit2',
                'num' => $credit2,
                'title' => '会员充值',
                'remark' => "充值申请审核通过，充值{$credit2}余额。",
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
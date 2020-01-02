<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/23
 * Time: 21:33
 */

namespace app\admin\controller;

use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;
use think\Loader;

class Task extends Base{

    //任务列表
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的任务','','error');
            }
            $status = \app\admin\model\Task::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['id|title'] = ['like', "%{$params['keyword']}%"];
            }
            if(!empty($params['is_display'])){
                $where['is_display'] = ['in',$params['is_display']];
            }
        }

        $total = \app\admin\model\Task::getCount($where);
        $list = \app\admin\model\Task::getPagination($where, 15, $total, "update_time DESC");

        $categorys = [];
        $categories = \app\admin\model\TaskCategory::getList();
        foreach ($categories as $key => $value) {
            $categorys[$value['id']] = $value['title'];
        }

        $GLOBALS['categorys'] = $categorys;

        $itemsCallback = function ($item, $key) {
            $item['category_type'] = "";

            if ($item['end_time'] < TIMESTAMP) {
                $item['category_type'] = "past";
            }  else if ($item['is_complete'] == 1) {
                $item['category_type'] = "pass";
            }   else if ($item['is_display'] == 0) {
                $item['category_type'] = "wait";
            } else if ($item['start_time'] < TIMESTAMP && $item['end_time'] > TIMESTAMP) {
                $item['category_type'] = "ing";
            }

            $item['start_time'] = date('Y-m-d H:i:s', $item['start_time']);
            $item['end_time'] = date('Y-m-d H:i:s', $item['end_time']);
            $item['complete_time'] = date('Y-m-d H:i:s', $item['complete_time']);
            $item['category'] = isset($GLOBALS['categorys'][$item['category_id']]) ? $GLOBALS['categorys'][$item['category_id']] : '';
            return $item;
        };

        $list->each($itemsCallback);

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
            $categorys = [];
            $categories = \app\admin\model\TaskCategory::getList();
            foreach ($categories as $key => $value) {
                $categorys[$value['id']] = $value['title'];
            }

            $item = \app\admin\model\Task::getInfoById($id);
            if(!empty($item['thumbs'])){
                $item['thumbs'] = unserialize($item['thumbs']);
            }

            $item['fee_money'] = $item['give_credit2'] * $item['fee'];

            $operate_steps = \app\home\model\Task::getOperateStepsById($id);

            $member = \app\admin\model\Member::getInfoById($item['uid']);
            $item['username'] = $member['username'];

            $item['category_type'] = "";

            if ($item['end_time'] < TIMESTAMP) {
                $item['category_type'] = "past";
            } else if ($item['is_complete'] == 1) {
                $item['category_type'] = "complete";
            } else if ($item['is_display'] == 0) {
                $item['category_type'] = "wait";
            } else if ($item['is_display'] == -1) {
                $item['category_type'] = "nopass";
            } else if ($item['start_time'] < TIMESTAMP && $item['end_time'] > TIMESTAMP) {
                $item['category_type'] = "ing";
            }

            $item['start_time'] = date('Y-m-d H:i:s', $item['start_time']);
            $item['end_time'] = date('Y-m-d H:i:s', $item['end_time']);
            $item['complete_time'] = date('Y-m-d H:i:s', $item['complete_time']);
            $item['category'] = isset($categorys[$item['category_id']]) ? $categorys[$item['category_id']] : '';
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'operate_steps' => $operate_steps,
            'origin' => $item->getData()
        ]);
    }

    public function save(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Task::getInfoById($id);
        }

        if (empty($item) || is_null($item)) {
            message("审核失败",'','error');
        }

        $origin = $item->getData();
        if ($origin['admin_id'] > 0) {
            message("审核失败",'','error');
        }

        $params = array_trim(request()->post());

        Db::startTrans();

        $update = [];
        $update['title'] = $params['title'];
        $update['is_display'] = intval($params['is_display']);
        $update['audit_remarks'] = $params['audit_remarks'];
        $update['admin_id'] = $this->administrator['id'];
        $update['update_time'] = TIMESTAMP;
        $status = \app\admin\model\Task::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message("审核失败",'','error');
        }

        //审核未通过时需要退回金额给用户
        if ($update['is_display'] == -1) {
            $credit2 = $item['amount'];
            $status1 = Member::updateCreditById($item['uid'], 0, $credit2);
            if(!$status1){
                Db::rollback();
                message('审核失败：-1','','error');
            }
            $status2 = CreditRecord::addInfo([
                'uid' => $item['uid'],
                'type' => 'credit2',
                'num' => $credit2,
                'title' => '任务发布审核',
                'remark' => "任务[" . $item['id'] . "]-" . $item['title'] . "发布审核未通过，退回{$credit2}余额。",
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
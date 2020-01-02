<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;


use app\admin\model\Config;
use app\admin\model\TaskCategory;
use app\admin\model\Uploads;
use app\home\model\Area;
use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

class Mytaskjoin extends Base {

    public function index(){
        $member = $this->checkLogin();

        $params = array();
        $params['uid'] = $member['uid'];
        $data = $this->_get_data($params);

        $data['category_type'] = "all";

        return $this->fetch('index', $data);
    }

    public function category() {
        $member = $this->checkLogin();

        $params = array();
        $params['uid'] = $member['uid'];
        $params['category_type'] = trim(params('t'));
        $data = $this->_get_data($params);

        $data['category_type'] = $params['category_type'];

        return $this->fetch('index', $data);
    }

    private function _get_data($params = array()) {
        $categories = TaskCategory::getListByKey();

        $pszie = 15;
        $tasks = \app\home\model\MyTaskJoin::getListByParams($params, $pszie);
        if(!empty($tasks)){
            foreach ($tasks as &$v){
                $v['category_icon'] = isset($categories[$v['category_id']]) ? to_media($categories[$v['category_id']]['thumb']) : "";
            }
        }
        if(request()->isAjax()){
            if(empty($tasks)){
                message('没有更多信息','','error');
            }
            $html = $this->fetch('_list', [
                'tasks' => $tasks
            ]);
            message($html,'','success');
        }
        $count = \app\home\model\MyTaskJoin::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        return [
            'tasks' => $tasks,
            'pageCount' => $pageCount
        ];
    }

    public function del(){
        $member = $this->checkLogin();

        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('任务ID错误','','error');
        }
        $joinInfo = \app\home\model\TaskJoin::getInfoById($id);
        if(empty($joinInfo)){
            message('任务不存在','','error');
        }

        if(empty($this->member['uid']) || $joinInfo['uid'] != $this->member['uid']){
            message('任务不存在','','error');
        }

        $member_task_join_info = \app\home\model\TaskJoin::getInfoByTaskIdAndUid($joinInfo['task_id'], $this->member['uid']);
        if(is_null($member_task_join_info)){
            message('此任务您还未抢单','','error');
        }

        if (!in_array($member_task_join_info['status'], array(1, 4))) {
            message('当前状态错误，无法上传验证','','error');
        }

        Db::startTrans();

        $params = [
            'delflag' => 2
        ];
        $status = \app\home\model\TaskJoin::updateInfoById($id, $params);
        if(!$status){
            Db::rollback();
            message('放弃接单失败：-1','','error');
        }

        $status = \app\home\model\Task::incJoinNum($joinInfo['task_id'], -1);
        if(!$status){
            Db::rollback();
            message('放弃接单失败：-2','','error');
        }

        $status = \app\home\model\Task::updateInfoById($joinInfo['task_id'], ['is_complete' => 0]);
        if(!$status){
            Db::rollback();
            message('放弃接单失败：-3','','error');
        }

        Db::commit();

        message('放弃接单成功','reload','success');
    }
    
}
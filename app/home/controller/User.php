<?php
namespace app\home\controller;

use app\home\model\Member;
use app\home\model\Follow;
use app\home\model\Task;
use app\home\model\TaskJoin;

class User extends Base {

    public function view($id = 0) {
        if(!check_id($id)){
            message('用户信息不存在','/home/index.html','error');
        }

        $member = Member::getUserInfoById($id);
        if(empty($member)){
            message('用户信息不存在','/home/index.html','error');
        }

        $params = array();
        $params['uid'] = $member['uid'];
        $params['category_type'] = "all";
        $data = $this->_get_data($params);

        $data['member'] = $member;
        $data['isFollow'] = false;

        if ($this->member['uid']) {
        	$data['isFollow'] = Follow::getIsFollow($this->member['uid'], $member['uid']);	
        }

        //发布次数
        $data['task_num'] = Task::getTotalCountByMemberId($member['uid']);
        //参与次数
        $data['join_num'] = TaskJoin::getTotalCountByMemberId($member['uid']);

        return $this->fetch('index', $data);
    }

    private function _get_data($params = array()) {
        $pszie = 15;
        $tasks = \app\home\model\MyTask::getListByParams($params, $pszie);
        if(request()->isAjax()){
            if(empty($tasks)){
                message('没有更多信息','','error');
            }
            $html = $this->fetch('_task_list', [
                'tasks' => $tasks
            ]);
            message($html,'','success');
        }
        $count = \app\home\model\MyTask::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        return [
            'tasks' => $tasks,
            'pageCount' => $pageCount
        ];
    }

}
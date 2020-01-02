<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\admin\model\TaskCategory;
use app\home\model\Member;
use app\admin\model\InvitationRebateRecord;
use think\Db;
use think\Log;

class Mytaskaudit extends Base {

    public function index($id = 0){
        $params = array_trim(request()->get());
        $data = $this->_get_data($id, $params);

        return $this->fetch('index', $data);
    }

    public function category($id = 0) {
        $params = array_trim(request()->get());
        $params['category_type'] = trim(params('t'));
        $data = $this->_get_data($id, $params);

        return $this->fetch('index', $data);
    }

    private function _get_data($id, $params = array()) {
        $member = $this->checkLogin();

        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('任务ID错误','','error');
        }
        $item = \app\home\model\Task::getInfoById($id);
        if(empty($item)){
            message('任务不存在','','error');
        }

        if(empty($this->member['uid']) || $item['uid'] != $this->member['uid']){
            message('任务不存在','','error');
        }

        $params['task_id'] = $item['id'];
        $params['uid'] = $member['uid'];

        $categories = TaskCategory::getListByKey();

        $pszie = 15;
        $tasks = \app\home\model\MyTaskAudit::getListByParams($params, $pszie);
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
        $count = \app\home\model\MyTaskAudit::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        return [
            'item' => $item,
            'category_type' => isset($params['category_type']) ? $params['category_type'] : 'all',
            'tasks' => $tasks,
            'count' => $count,
            'pageCount' => $pageCount
        ];
    }

    public function view() {
        return $this->detail(1);
    }

    public function detail($isview = 0) {
        $member = $this->checkLogin();

        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('审核ID错误','','error');
        }
        $joinInfo = \app\home\model\TaskJoin::getInfoById($id);
        if(empty($joinInfo)){
            message('审核不存在','','error');
        }

        if(!empty($joinInfo['thumbs'])){
            $joinInfo['thumbs'] = unserialize($joinInfo['thumbs']);
        }

        $item = \app\home\model\Task::getInfoById($joinInfo['task_id']);
        if(empty($item)){
            message('任务不存在','','error');
        }

        if(empty($this->member['uid']) || $item['uid'] != $this->member['uid']){
            message('任务不存在','','error');
        }

        $joinMember = Member::getUserInfoById($joinInfo['uid']);
        if(empty($member)){
            message('会员信息不存在','/home/auth/login.html','error');
        }

        $item['audit_num'] = \app\home\model\TaskJoin::getAuditNumById($item['id']);

        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'joinInfo' => $joinInfo,
            'joinMember' => $joinMember,
            'isview' => $isview,
        ]);
    }

    public function audit() {
        $member = $this->checkLogin();
        $setting = ['invitation_rebate' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['invitation_rebate'])){
                $setting['invitation_rebate'] = round(floatval($setting['invitation_rebate']/100),2);
            }
        }

        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('审核ID错误','','error');
        }
        $joinInfo = \app\home\model\TaskJoin::getInfoById($id);
        if(empty($joinInfo)){
            message('审核不存在','','error');
        }

        if(!empty($joinInfo['thumbs'])){
            $joinInfo['thumbs'] = unserialize($joinInfo['thumbs']);
        }

        $params = \app\home\model\Task::getInfoById($joinInfo['task_id']);
        if(empty($params)){
            message('任务不存在','','error');
        }

        if(empty($this->member['uid']) || $params['uid'] != $this->member['uid']){
            message('任务不存在','','error');
        }

        $joinMemberInfo = Member::getUserInfoById($joinInfo['uid']);
        if (!$joinMemberInfo){
            message('任务会员不存在','','error');
        }

        $insert_task_id = $joinInfo['task_id'];

        Db::startTrans();

        $update = array(
            'status' => 3,
            'audit_time' => TIMESTAMP,
            'update_time' => TIMESTAMP
        );
        $status = \app\home\model\TaskJoin::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message('审核失败:-1','','error');
        }

        if($params['give_credit1']>0 || $params['unit_price']>0){
            $give_credit1 = $params['give_credit1'];
            $unit_price = $params['unit_price'];

            //有推荐人，做完任务审核通过需要按后台设置比例分成给推荐人
            if ($joinMemberInfo['parent_uid'] > 0 && $setting['invitation_rebate'] > 0) {
                $parent_money = round($unit_price * $setting['invitation_rebate'], 2);
                $unit_price -= $parent_money;

                $status1 = Member::updateCreditById($joinMemberInfo['parent_uid'], 0, $parent_money);
                if(!$status1){
                    Db::rollback();
                    message('审核失败:-2','','error');
                }

                $status3 = CreditRecord::addInfo([
                    'uid' => $joinMemberInfo['parent_uid'],
                    'type' => 'credit2',
                    'num' => $parent_money,
                    'title' => '审核任务',
                    'remark' => "任务[" . $params['id'] . "]-" . $params['title'] . "审核成功，获得推荐收入{$parent_money}元。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message('审核失败:-4','','error');
                }

                $status3 = InvitationRebateRecord::addInfo([
                    'uid' => $joinMemberInfo['parent_uid'],
                    'num' => $parent_money,
                    'task_id' => $insert_task_id,
                    'remark' => "徒弟[" . $joinMemberInfo['username'] . "]完成任务[" . $params['id'] . "]-" . $params['title'] . "，获得推荐收入{$parent_money}元。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message('审核失败:-4','','error');
                }

                $status4 = Member::updateInviteInfo($joinMemberInfo['parent_uid'], 0, $parent_money);
                if(!$status4){
                    Db::rollback();
                    message('审核失败:-4','','error');
                }
            }

            $taskService = \think\Loader::model("Task", 'service');
            $taskService->invitationFirstTaskAward($joinMemberInfo, $params);

            $status1 = Member::updateCreditById($joinInfo['uid'], $give_credit1, $unit_price);
            if(!$status1){
                Db::rollback();
                message('审核失败:-2','','error');
            }
            //分别记录积分和余额记录
            if($give_credit1>0){
                $status2 = CreditRecord::addInfo([
                    'uid' => $joinInfo['uid'],
                    'type' => 'credit1',
                    'num' => $give_credit1,
                    'title' => '审核任务',
                    'remark' => "任务[" . $params['id'] . "]-" . $params['title'] . "审核成功，奖励{$give_credit1}积分。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status2){
                    Db::rollback();
                    message('审核失败:-3','','error');
                }
            }
            if($unit_price>0){
                $status3 = CreditRecord::addInfo([
                    'uid' => $joinInfo['uid'],
                    'type' => 'credit2',
                    'num' => $unit_price,
                    'title' => '审核任务',
                    'remark' => "任务[" . $params['id'] . "]-" . $params['title'] . "审核成功，奖励{$unit_price}元。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message('审核失败:-4','','error');
                }
            }
        }

        Db::commit();

        message('审核成功','javascript:history.back()','success');
    }

    public function nopass() {
        $member = $this->checkLogin();

        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('审核ID错误','','error');
        }
        $joinInfo = \app\home\model\TaskJoin::getInfoById($id);
        if(empty($joinInfo)){
            message('审核不存在','','error');
        }

        if(!empty($joinInfo['thumbs'])){
            $joinInfo['thumbs'] = unserialize($joinInfo['thumbs']);
        }

        $params = \app\home\model\Task::getInfoById($joinInfo['task_id']);
        if(empty($params)){
            message('任务不存在','','error');
        }

        if(empty($this->member['uid']) || $params['uid'] != $this->member['uid']){
            message('任务不存在','','error');
        }

        $insert_task_id = $joinInfo['task_id'];

        Db::startTrans();

        $update = array(
            'status' => 4,
            'audit_time' => TIMESTAMP,
            'update_time' => TIMESTAMP
        );
        $status = \app\home\model\TaskJoin::updateInfoById($id, $update);
        if(!$status){
            Db::rollback();
            message('审核失败:-1','','error');
        }

        Db::commit();

        message('审核成功','javascript:history.back()','success');
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/23
 * Time: 21:33
 */

namespace app\admin\controller;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;
use think\Loader;

class Taskjoin extends Base{

    //任务列表
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的任务','','error');
            }
            $status = \app\admin\model\TaskJoin::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['uid'])){
                $where['uid'] = $params['uid'];
            }
            if(!empty($params['title'])){
                $where['task_id'] = ['like', "%{$params['title']}%"];
            }
            if(!empty($params['start_time']) && !empty($params['end_time'])){
                $where['create_time'] = [['egt', strtotime($params['start_time'] . " 00:00:00")], ['elt', strtotime($params['end_time'] . " 23:59:59")]];
            }
            if(!empty($params['status'])){
                $where['status'] = ['in',$params['status']];
            }
        }

        $total = \app\admin\model\TaskJoin::getCount($where);
        $list = \app\admin\model\TaskJoin::getPagination($where, 15, $total, "update_time DESC");

        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total,
            'params' => $params
        ]);
    }

    public function post(){
        $id = params('id');
        $row = ['is_display' => 0, 'admin_id' => -1];
        $origin = ['is_display' => 0, 'admin_id' => -1];
        if(check_id($id)){
            $row = \app\admin\model\TaskJoin::getInfoById($id);
            if(!empty($row['thumbs'])){
                $row['thumbs'] = unserialize($row['thumbs']);
            }

            $member = \app\admin\model\Member::getInfoById($row['uid']);
            $row['username'] = $member['username'];

            $origin = $row->getData();
        }

        return $this->fetch(__FUNCTION__,[
            'row' => $row,
            'origin' => $origin
        ]);
    }

    public function check(){
        $id = params('id');
        $row = [];
        if(check_id($id)){
            $row = \app\admin\model\TaskJoin::getInfoById($id);
        }

        if (empty($row) || is_null($row)) {
            message("审核失败",'','error');
        }

        if ($row['status'] == 3 || $row['status'] == 4) {
            message("审核失败",'','error');
        }

        $params = array_trim(request()->post());
        if ($params['status'] == 3) {
            $this->pass($row);
        } else {
            $this->nopass($row);
        }
    }

    private function pass($joinInfo) {
        $setting = ['invitation_rebate' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['invitation_rebate'])){
                $setting['invitation_rebate'] = round(floatval($setting['invitation_rebate']/100),2);
            }
        }

        $params = \app\home\model\Task::getInfoById($joinInfo['task_id']);
        if(empty($params)){
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
        $status = \app\home\model\TaskJoin::updateInfoById($joinInfo['id'], $update);
        if(!$status){
            Db::rollback();
            message('审核失败:-1','','error');
        }

        if($params['unit_price']>0){
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

            $status1 = Member::updateCreditById($joinInfo['uid'], 0, $unit_price);
            if(!$status1){
                Db::rollback();
                message('审核失败:-2','','error');
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

    private function nopass($joinInfo) {
        $params = \app\home\model\Task::getInfoById($joinInfo['task_id']);
        if(empty($params)){
            message('任务不存在','','error');
        }

        $insert_task_id = $joinInfo['task_id'];

        Db::startTrans();

        $update = array(
            'status' => 4,
            'audit_time' => TIMESTAMP,
            'update_time' => TIMESTAMP
        );
        $status = \app\home\model\TaskJoin::updateInfoById($joinInfo['id'], $update);
        if(!$status){
            Db::rollback();
            message('审核失败:-1','','error');
        }

        Db::commit();

        message('审核成功','javascript:history.back()','success');
    }
}
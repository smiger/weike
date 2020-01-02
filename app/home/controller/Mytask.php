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

class Mytask extends Base {

    public function index(){
        $member = $this->checkLogin();

        $params = array();
        $params['uid'] = $member['uid'];
        $params['category_type'] = "all";
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
        $pszie = 15;
        $tasks = \app\home\model\MyTask::getListByParams($params, $pszie);
        if(request()->isAjax()){
            if(empty($tasks)){
                message('没有更多信息','','error');
            }
            $view = $params['category_type'] == "audit" ? '_audit_list' : '_list';
            $html = $this->fetch($view, [
                'tasks' => $tasks
            ]);
            message($html,'','success');
        }
        $count = \app\home\model\MyTask::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        $setting = [];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['top_fee'])){
                $setting['top_fee'] = intval($setting['top_fee']);
            }
        }

        return [
            'tasks' => $tasks,
            'setting' => $setting,
            'pageCount' => $pageCount
        ];
    }

    public function del() {
        $member = $this->checkLogin();

        $id = intval(params('id'));
        $tasks = \app\home\model\MyTask::getInfoById($id);
        if (is_null($tasks)) {
            message('删除失败:-1','','error');    
        }
        if ($tasks['uid'] != $member['uid']) {
            message('删除失败:-2','','error');       
        }
        if($tasks['join_num'] > 0){
            message('删除失败:-3','','error');
        }

        Db::startTrans();

        $result = \app\home\model\MyTask::delById($id);
        if (!$result) {
            message('删除失败:-3','','error');
        }

        $insert_task_id = $tasks['id'];

        //删除任务时需要退还金额
        if($tasks['give_credit1']>0 || $tasks['amount']>0){
            $status1 = Member::updateCreditById($tasks['uid'], $tasks['give_credit1'], $tasks['amount']);
            if(!$status1){
                Db::rollback();
                message('删除失败:-4','','error');
            }
            //分别记录积分和余额记录
            if($tasks['give_credit1']>0){
                $status2 = CreditRecord::addInfo([
                    'uid' => $tasks['uid'],
                    'type' => 'credit1',
                    'num' => $tasks['give_credit1'],
                    'title' => '删除任务',
                    'remark' => "任务[$insert_task_id]-" . $tasks['title'] . "删除成功，退还{$tasks['give_credit1']}积分。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status2){
                    Db::rollback();
                    message('删除失败:-5','','error');
                }
            }
            if($tasks['amount']>0){
                $status3 = CreditRecord::addInfo([
                    'uid' => $tasks['uid'],
                    'type' => 'credit2',
                    'num' => $tasks['amount'],
                    'title' => '删除任务',
                    'remark' => "任务[$insert_task_id]-" . $tasks['title'] . "删除成功，退还{$tasks['amount']}余额。",
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message('删除失败:-6','','error');
                }
            }
        }

        Db::commit();

        message('删除成功','reload','success');
    }

    public function check() {
        to_json(0, '');
    }

    public function audit() {
        $member = $this->checkLogin();

        $params = array();
        $params['uid'] = $member['uid'];
        $params['category_type'] = "audit";
        $data = $this->_get_data($params);

        $data['category_type'] = $params['category_type'];

        return $this->fetch(__FUNCTION__, $data);
    }

    public function setTop() {
        $member = $this->checkLogin();

        $id = intval(params('id'));
        $tasks = \app\home\model\MyTask::getInfoById($id);
        if (is_null($tasks)) {
            message('置顶失败:-1','','error');    
        }
        if ($tasks['uid'] != $member['uid']) {
            message('置顶失败:-2','','error');       
        }

        $setting = [];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['top_fee'])){
                $setting['top_fee'] = intval($setting['top_fee']);
            }
        }

        if(!isset($setting['top_fee'])){
            $setting['top_fee'] = 0;
        }

        if(!isset($setting['top_max_hour'])){
            $setting['top_max_hour'] = 1;
        }

        $params = array_trim(request()->post());
        $params['hour'] = intval($params['hour']);
        $top_fee = $params['hour'] * $setting['top_fee'];

        if ($params['hour'] > $setting['top_max_hour']) {
            message('置顶时间最多' . $setting['top_max_hour'] . '小时','','error');
        }

        //判断余额或者积分是足够
        if($top_fee > $member['credit2']){
            message('账户余额不足','','error');
        }

        Db::startTrans();
        $update = [
            'top_time' => TIMESTAMP,
            'top_hour' => $params['hour'],
            'top_fee' => $top_fee,
            'top_end_time' => TIMESTAMP + ($params['hour'] * 60 * 60)
        ];
        $status0 = \app\home\model\Task::updateInfoById($id, $update);
        if(!$status0){
            Db::rollback();
            message('置顶失败:-3','','error');
        }

        if($top_fee>0){
            $status1 = Member::updateCreditById($member['uid'], 0, -$top_fee);
            if(!$status1){
                Db::rollback();
                message('置顶失败:-4','','error');
            }
            $status3 = CreditRecord::addInfo([
                'uid' => $member['uid'],
                'type' => 'credit2',
                'num' => -$top_fee,
                'title' => '置顶任务',
                'remark' => "任务[" . $tasks['id'] . "]-" . $tasks['title'] . "置顶成功，扣除{$top_fee}余额。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status3){
                Db::rollback();
                message('置顶失败:-5','','error');
            }
        }

        Db::commit();

        message('置顶成功','reload','success');
    }

    public function outstock() {
        $member = $this->checkLogin();

        $id = intval(params('id'));
        $tasks = \app\home\model\MyTask::getInfoById($id);
        if (is_null($tasks)) {
            message('下架失败:-1','','error');
        }
        if ($tasks['uid'] != $member['uid']) {
            message('下架失败:-2','','error');
        }

        $taskService = \think\Loader::model("Task", 'service');
        $taskService->outStockTask($tasks);

        message('下架成功','reload','success');
    }
}
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

class Task extends Base{

    public function index(){
        $category_id = floor(trim(params('category_id')));
        //任务列表
        $params = request()->request();
        if(check_array($params)){
            array_trim($params);
        }
        $params['category_id'] = $category_id;
        $pszie = 15;
        $tasks = \app\home\model\Task::getListByParams($params,$pszie);
        if(request()->isAjax()){
            if(empty($tasks)){
                message('没有更多任务','','error');
            }
            message($tasks,'','success');
        }
        $count = \app\home\model\Task::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        return $this->fetch(__FUNCTION__,[
            'tasks' => $tasks,
            'pageCount' => $pageCount
        ]);
    }

    public function detail(){
        $is_can_op = 0;
        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('任务ID错误','','error');
        }
        $item = \app\home\model\Task::getInfoById($id);
        if(empty($item)){
            message('任务不存在','','error');
        }
        if(!empty($this->member['uid']) && $item['uid'] == $this->member['uid']){
            $is_can_op = 1;
        }

        $item['audit_num'] = \app\home\model\TaskJoin::getAuditNumById($id);

        $operate_steps = \app\home\model\Task::getOperateStepsById($id);

        $member_task_join_info = NULL;
        if (isset($this->member['uid']) && $this->member['uid'] > 0) {
            $member_task_join_info = \app\home\model\TaskJoin::getInfoByTaskIdAndUid($id, $this->member['uid']);
        }

        $allow_accept = true;
        if ($item['start_time'] > TIMESTAMP || $item['origin_end_time'] < TIMESTAMP) {
            $allow_accept = false;
        }

        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'is_can_op' => $is_can_op,
            'operate_steps' => $operate_steps,
            'member_task_join_info' => $member_task_join_info,
            'allow_accept' => $allow_accept
        ]);
    }

    public function task_join_ajax() {
        $task_id = intval(trim(params('id')));
        $where = ['task_join.task_id' => $task_id];
        $list = \app\home\model\TaskJoin::getTaskPassJoin($where, 15);

        $itemsCallback = function ($item, $key) {
            $username = $item['username'];
            if (strlen($username) >= 11) {
                $item['username'] = substr($username, 0, 3) . "*****" . substr($username, -3);
            }
            return $item;
        };

        $list->each($itemsCallback);

        to_json(0, '', $list);
    }

    //抢单任务
    public function accept(){
        $member = $this->checkLogin();
        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('任务ID错误','','error');
        }
        $item = \app\home\model\Task::getInfoById($id);
        if(empty($item)){
            message('任务不存在','','error');
        }
        if ($item['start_time'] > TIMESTAMP || $item['origin_end_time'] < TIMESTAMP) {
            message('任务不存在','','error');
        }

        if(!empty($this->member['uid']) && $item['uid'] == $this->member['uid']){
            message('无法抢单自己发布的任务','','error');
        }

        $member_task_join_info = \app\home\model\TaskJoin::getInfoByTaskIdAndUid($id, $this->member['uid']);
        if ($member_task_join_info && $member_task_join_info['status'] != 4) {
            message('不能重复抢单','','error');
        }

        $lastTask = \app\home\model\MyTaskJoin::getLastTaskJoin($member['uid']);
        if ($lastTask['status'] < 2 && $lastTask['status']) {
            message('请先完成已接任务，在接新任务','','error');
        }

        $item['join_num'] == \app\home\model\TaskJoin::getJoinNumByTaskId($id);
        if ($item['join_num'] >= $item['ticket_num']) {
            message('任务抢单人数已满，无法抢单','','error');
        }

        Db::startTrans();

        $params = array(
            'task_id' => $id,
            'uid' => $member['uid'],
            'status' => 1,
            'update_time' => TIMESTAMP
        );
        $insert_join_id = \app\home\model\TaskJoin::addInfo($params);
        if(!$insert_join_id){
            Db::rollback();
            message('抢单失败:-1','','error');
        }

        $status = \app\home\model\Task::incJoinNum($id);
        if(!$status){
            Db::rollback();
            message('抢单失败:-2','','error');
        }

        if ($item['join_num'] + 1 >= $item['ticket_num']) {
            $status = \app\home\model\Task::updateInfoById($id, ['is_complete' => 1]);
            if(!$status){
                Db::rollback();
                message('抢单失败:-3','','error');
            }
        }

        Db::commit();
        message('抢单成功','reload','success');
    }

    //添加任务
    public function add(){
        $member = $this->checkLogin();
        $setting = ['push_check' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['period'])){
                $setting['period'] = explode('#',$setting['period']);
            }
            if(!empty($setting['fee'])){
                $setting['fee'] = round(floatval($setting['fee']*0.01),2);
            }
            if(!empty($setting['push_check'])){
                $setting['push_check'] = intval($setting['push_check']);
            }
        }

        //如果是ajax请求，处理发布
        if(request()->isAjax()){
            if(!check_array($setting)){
                message('平台未进行相关设置','','error');
            }
            $params = array_trim(request()->post());
            $result = $this->validate($params,'Task');
            if($result !== true){
                message($result,'','error');
            }

            $params['fee'] = !empty($setting['fee'])?$setting['fee']:0;

            //处理是否的值
            param_is_or_no(['is_screenshot','is_ip_restriction','is_limit_speed'],$params);
            //处理两位小数的值
            params_round(['unit_price','give_credit2'],$params,2);
            //处理是整数的
            params_floor(['check_period','rate','interval_hour','limit_ticket_num'],$params);

            $params['give_credit1'] = intval($params['give_credit1']);
            $params['ticket_num'] = intval($params['ticket_num']);
            $params['give_credit2'] = $params['unit_price'] * $params['ticket_num'];
            $params['amount'] = $params['give_credit2'] * (1 + $params['fee']);

            //判断余额或者积分是足够
            if($params['amount'] > $member['credit2']){
                message('账户余额不足','','error');
            }
            if($params['give_credit1'] > $member['credit1']){
                message('积分不足','','error');
            }

            $task_operate_steps_contents = $params['process_sm'];
            unset($params['process_sm']);
            unset($params['processFile']);

            if (empty($task_operate_steps_contents)) {
                message('您未写操作说明!','','error');
            }
            foreach ($task_operate_steps_contents as $key => $value) {
                if (empty(trim($value))) {
                    message('您未写操作说明!','','error');
                }
            }

            // 获取表单上传文件
            $thumbs = [];
            $files = request()->file('thumbs');
            if(check_array($files)){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if(!$info){
                        message($file->getError(),'','error');
                    }

                    $data = array(
                        'state' => 'SUCCESS',
                        'url' => 'public' . DS . 'uploads' . DS . $info->getSaveName(),
                        'title' => $info->getFilename(),
                        'original' => $info->getFilename(),
                        'type' => '.' . $info->getExtension(),
                        'size' => $info->getSize(),
                    );

                    $imgresource = ROOT_PATH . $data['url'];

                    $image = \think\Image::open($imgresource);

                    $water = [
                        'is_mark' => 1,
                        'mark_type' => 'text',
                        'mark_txt' => '样图请勿上传',
                        'mark_img' => '',
                        'mark_width' => 0,
                        'mark_height' => 0,
                        'mark_degree' => ''
                    ];
                    if($water['is_mark']==1 && $image->width() > $water['mark_width'] && $image->height() > $water['mark_height']) {
                        if($water['mark_type'] == 'text'){
                            $image->text($water['mark_txt'], './hgzb.ttf', 50, '#ff0000', 9)->save($imgresource);
                        }else{
                            $image->water(".".$water['mark_img'], 9, $water['mark_degree'])->save($imgresource);
                        }
                    }

                    $record = [
                        'uid' => $this->member['uid'],
                        'extension' => $info->getExtension(),
                        'save_name' => str_replace('\\','/',$info->getSaveName()),
                        'filename' => $info->getFilename(),
                        'md5' => $info->hash('md5'),
                        'sha1' => $info->hash('sha1'),
                        'size' => $info->getSize(),
                        'create_time' => TIMESTAMP
                    ];
                    //记录文件信息
                    array_push($thumbs,$record['save_name']);
                    //数据库存入失败记录日志
                    if(!Uploads::addInfo($record)){
                        Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                    }
                }
            }
            $params['thumbs'] = check_array($thumbs)?serialize($thumbs):'';
            $params['start_time'] = strtotime($params['start_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $params['check_period_time'] = intval($params['check_period']) * 3600 + TIMESTAMP;
            $params['uid'] = $this->member['uid'];
            $params['update_time'] = TIMESTAMP;

            // 上传操作说明配图
            $task_operate_steps_images = [];
            $task_operate_steps_files = request()->file('processFile');
            if(check_array($task_operate_steps_files)){
                foreach($task_operate_steps_files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if(!$info){
                        message($file->getError(),'','error');
                    }
                    $record = [
                        'uid' => $this->member['uid'],
                        'extension' => $info->getExtension(),
                        'save_name' => str_replace('\\','/',$info->getSaveName()),
                        'filename' => $info->getFilename(),
                        'md5' => $info->hash('md5'),
                        'sha1' => $info->hash('sha1'),
                        'size' => $info->getSize(),
                        'create_time' => TIMESTAMP
                    ];
                    //记录文件信息
                    array_push($task_operate_steps_images, $record['save_name']);
                    //数据库存入失败记录日志
                    if(!Uploads::addInfo($record)){
                        Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                    }
                }
            }

            //读取后台任务是否需要审核配置
            $params['is_display'] = $setting['push_check'] ? 0 : 1;

            Db::startTrans();
            $insert_task_id = \app\home\model\Task::addInfo($params);
            if(!$insert_task_id){
                Db::rollback();
                message('发布失败:-1','','error');
            }

            foreach ($task_operate_steps_contents as $key => $value) {
                $task_operate_steps_params = array(
                    'task_id' => $insert_task_id,
                    'uid' => $this->member['uid'],
                    'content' => $value,
                    'image' => isset($task_operate_steps_images[$key]) ? $task_operate_steps_images[$key] : '',
                    'sort' => $key,
                );

                $insert_task_operate_step_id = \app\home\model\TaskOperateSteps::addInfo($task_operate_steps_params);
                if(!$insert_task_operate_step_id){
                    Db::rollback();
                    message('发布失败:-2','','error');
                }
            }

            if($params['give_credit1']>0 || $params['amount']>0){
                $status1 = Member::updateCreditById($member['uid'], -$params['give_credit1'], -$params['amount']);
                if(!$status1){
                    Db::rollback();
                    message('发布失败:-3','','error');
                }
                //分别记录积分和余额记录
                if($params['give_credit1']>0){
                    $status2 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit1',
                        'num' => -$params['give_credit1'],
                        'title' => '发布任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "发布成功，扣除{$params['give_credit1']}积分。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status2){
                        Db::rollback();
                        message('发布失败:-4','','error');
                    }
                }
                if($params['amount']>0){
                    $status3 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit2',
                        'num' => -$params['amount'],
                        'title' => '发布任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "发布成功，扣除{$params['amount']}余额。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        message('发布失败:-5','','error');
                    }
                }
            }
            Db::commit();
            message('发布成功','/home/mytask.html','success');
        }
        $categories = TaskCategory::getList();
        return $this->fetch(__FUNCTION__,[
            'item' => ['category_id' => 0],
            'member' => $member,
            'categories' => $categories,
            'setting' => $setting,
            'provinces' => Area::$provinces,
            'operate_steps' => null
        ]);
    }

    //编辑任务
    public function edit($id = 0) {
        $member = $this->checkLogin();
        $setting = [];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['period'])){
                $setting['period'] = explode('#',$setting['period']);
            }
            if(!empty($setting['fee'])){
                $setting['fee'] = round(floatval($setting['fee']*0.01),2);
            }
        }

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
        if($item['join_num'] > 0){
            message('任务状态不允许编辑','','error');
        }

        $operate_steps = \app\home\model\Task::getOperateStepsById($id);
        if ($operate_steps) {
            foreach ($operate_steps as $key => $value) {
                $operate_steps[$key]['content'] = str_replace(array("\r", "\n"), array("", "\\n"), $value['content']);
            }
        }

        $categories = TaskCategory::getList();

        return $this->fetch('add', [
            'member' => $member,
            'categories' => $categories,
            'setting' => $setting,
            'provinces' => Area::$provinces,
            'item' => $item,
            'operate_steps' => $operate_steps
        ]);
    }

    //编辑任务
    public function save() {
        $member = $this->checkLogin();
        $setting = [];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['period'])){
                $setting['period'] = explode('#',$setting['period']);
            }
            if(!empty($setting['fee'])){
                $setting['fee'] = round(floatval($setting['fee']*0.01),2);
            }
        }

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
        if($item['join_num'] > 0){
            message('任务状态不允许编辑','','error');
        }

        $params = array_trim(request()->post());
        $result = $this->validate($params,'Task');
        if($result !== true){
            message($result,'','error');
        }

        $params['fee'] = !empty($setting['fee'])?$setting['fee']:0;

        //处理是否的值
        param_is_or_no(['is_screenshot','is_ip_restriction','is_limit_speed'],$params);
        //处理两位小数的值
        params_round(['unit_price','give_credit2'],$params,2);
        //处理是整数的
        params_floor(['check_period','rate','interval_hour','limit_ticket_num'],$params);

        $params['give_credit1'] = intval($params['give_credit1']);
        $params['ticket_num'] = intval($params['ticket_num']);
        $params['give_credit2'] = floatval($params['unit_price'] * $params['ticket_num']);
        $params['amount'] = floatval($params['give_credit2'] * (1 + $params['fee']));

        //判断余额或者积分是足够
        if($params['amount'] > $member['credit2']){
            message('账户余额不足','','error');
        }
        if($params['give_credit1'] > $member['credit1']){
            message('积分不足','','error');
        }

        $task_operate_steps_contents = $params['process_sm'];
        unset($params['process_sm']);
        unset($params['processFile']);

        if (empty($task_operate_steps_contents)) {
            message('您未写操作说明!','','error');
        }
        foreach ($task_operate_steps_contents as $key => $value) {
            if (empty(trim($value))) {
                message('您未写操作说明!','','error');
            }
        }

        $thumbsi = isset($_POST["thumbsi"]) && is_array($_POST["thumbsi"]) ? $_POST["thumbsi"] : [];
        $thumbs = [];

        if (is_array($item['thumbs'])) {
            foreach ($item['thumbs'] as $key => $value) {
                if (in_array($key, $thumbsi)) {
                    $thumbs[] = $value;
                }
            }
        }

        // 获取表单上传文件
        $files = request()->file('thumbs');
        if(check_array($files)){
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if(!$info){
                    message($file->getError(),'','error');
                }

                $data = array(
                    'state' => 'SUCCESS',
                    'url' => 'public' . DS . 'uploads' . DS . $info->getSaveName(),
                    'title' => $info->getFilename(),
                    'original' => $info->getFilename(),
                    'type' => '.' . $info->getExtension(),
                    'size' => $info->getSize(),
                );

                $imgresource = ROOT_PATH . $data['url'];

                $image = \think\Image::open($imgresource);

                $water = [
                    'is_mark' => 1,
                    'mark_type' => 'text',
                    'mark_txt' => '样图请勿上传',
                    'mark_img' => '',
                    'mark_width' => 0,
                    'mark_height' => 0,
                    'mark_degree' => ''
                ];
                if($water['is_mark']==1 && $image->width() > $water['mark_width'] && $image->height() > $water['mark_height']) {
                    if($water['mark_type'] == 'text'){
                        $image->text($water['mark_txt'], './hgzb.ttf', 50, '#ff0000', 9)->save($imgresource);
                    }else{
                        $image->water(".".$water['mark_img'], 9, $water['mark_degree'])->save($imgresource);
                    }
                }
                
                $record = [
                    'uid' => $this->member['uid'],
                    'extension' => $info->getExtension(),
                    'save_name' => str_replace('\\','/',$info->getSaveName()),
                    'filename' => $info->getFilename(),
                    'md5' => $info->hash('md5'),
                    'sha1' => $info->hash('sha1'),
                    'size' => $info->getSize(),
                    'create_time' => TIMESTAMP
                ];
                //记录文件信息
                array_push($thumbs,$record['save_name']);
                //数据库存入失败记录日志
                if(!Uploads::addInfo($record)){
                    Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                }
            }
        }
        $params['thumbs'] = check_array($thumbs)?serialize($thumbs):'';
        $params['start_time'] = strtotime($params['start_time']);
        $params['end_time'] = strtotime($params['end_time']);
        $params['check_period_time'] = intval($params['check_period']) * 3600 + TIMESTAMP;
        $params['is_display'] = 0;
        $params['admin_id'] = 0;
        $params['update_time'] = TIMESTAMP;

        //读取旧的操作说明步骤
        $operate_steps = \app\home\model\Task::getOperateStepsById($id);
        $operate_step_ids = isset($_POST["operate_step_id"]) && is_array($_POST["operate_step_id"]) ? $_POST["operate_step_id"] : [];

        $operate_step_del_ids = [];
        $operate_steps_last_sort = 0;

        //先把旧的操作步骤已经删除的unset
        foreach ($operate_steps as $key => $value) {
            if (!in_array($value['id'], $operate_step_ids)) {
                $operate_step_del_ids[] = $value['id'];
                unset($operate_steps[$key]);
            }

            $operate_steps_last_sort = $value['sort'];
        }

        Db::startTrans();

        //处理未删除的操作说明是否有文字和图片更新
        foreach ($operate_steps as $key => $value) {
            $task_operate_steps_update = [];
            if (isset($task_operate_steps_contents['org' . $value['id']])) {
                $task_operate_steps_update['content'] = trim($task_operate_steps_contents['org' . $value['id']]);
            }

            $image = $this->taskProcessFile('.org' . $value['id']);
            if (!empty($image)) {
                $task_operate_steps_update['image'] = $image;
            }

            if ($task_operate_steps_update) {
                $task_operate_steps_update_result = \app\home\model\TaskOperateSteps::updateInfoById($value['id'], $task_operate_steps_update);
                if(!$task_operate_steps_update_result){
                    Db::rollback();
                    message('保存失败:-1','','error');
                }
            }

            unset($task_operate_steps_contents['org' . $value['id']]);
            unset($_FILES['processFile']['org' . $value['id']]);
        }

        //删除操作说明
        if (count($operate_step_del_ids)) {
            $status0 = \app\home\model\TaskOperateSteps::deleteByIds($operate_step_del_ids);
            if(!$status0){
                Db::rollback();
                message('保存失败:-2','','error');
            }
        }

        // 上传操作说明配图
        $task_operate_steps_images = [];
        $task_operate_steps_files = request()->file('processFile');
        if(check_array($task_operate_steps_files)){
            foreach($task_operate_steps_files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if(!$info){
                    Db::rollback();
                    message($file->getError(),'','error');
                }
                $record = [
                    'uid' => $this->member['uid'],
                    'extension' => $info->getExtension(),
                    'save_name' => str_replace('\\','/',$info->getSaveName()),
                    'filename' => $info->getFilename(),
                    'md5' => $info->hash('md5'),
                    'sha1' => $info->hash('sha1'),
                    'size' => $info->getSize(),
                    'create_time' => TIMESTAMP
                ];
                //记录文件信息
                array_push($task_operate_steps_images, $record['save_name']);
                //数据库存入失败记录日志
                if(!Uploads::addInfo($record)){
                    Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                }
            }
        }

        unset($params['thumbsi']);
        unset($params['operate_step_id']);
        $insert_task_id = $id;
        $status0 = \app\home\model\Task::updateInfoById($id, $params);
        if(!$status0){
            Db::rollback();
            message('保存失败:-3','','error');
        }

        foreach ($task_operate_steps_images as $key => $value) {
            $task_operate_steps_params = array(
                'task_id' => $insert_task_id,
                'uid' => $this->member['uid'],
                'content' => isset($task_operate_steps_contents[$key]) ? $task_operate_steps_contents[$key] : '',
                'image' => isset($task_operate_steps_images[$key]) ? $task_operate_steps_images[$key] : '',
                'sort' => $operate_steps_last_sort + 1,
            );

            $insert_task_operate_step_id = \app\home\model\TaskOperateSteps::addInfo($task_operate_steps_params);
            if(!$insert_task_operate_step_id){
                Db::rollback();
                message('保存失败:-4','','error');
            }
        }

        if($item['give_credit1'] != $params['give_credit1'] || bccomp($item['amount'], $params['amount'], 2) != 0){
            if($item['give_credit1']>0 || $item['amount']>0){
                $status1 = Member::updateCreditById($member['uid'], $item['give_credit1'], $item['amount']);
                if(!$status1){
                    Db::rollback();
                    message('保存失败:-5','','error');
                }
                //分别记录积分和余额记录
                if($item['give_credit1']>0){
                    $status2 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit1',
                        'num' => $item['give_credit1'],
                        'title' => '编辑任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "保存成功，退回原{$item['give_credit1']}积分。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status2){
                        Db::rollback();
                        message('保存失败:-6','','error');
                    }
                }
                if($item['amount']>0){
                    $status3 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit2',
                        'num' => $item['amount'],
                        'title' => '编辑任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "保存成功，退回原{$item['amount']}余额。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        message('保存失败:-7','','error');
                    }
                }
            }

            if($params['give_credit1']>0 || $params['amount']>0){
                $status1 = Member::updateCreditById($member['uid'], -$params['give_credit1'], -$params['amount']);
                if(!$status1){
                    Db::rollback();
                    message('保存失败:-8','','error');
                }
                //分别记录积分和余额记录
                if($params['give_credit1']>0){
                    $status2 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit1',
                        'num' => -$params['give_credit1'],
                        'title' => '编辑任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "保存成功，扣除新{$params['give_credit1']}积分。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status2){
                        Db::rollback();
                        message('保存失败:-9','','error');
                    }
                }
                if($params['amount']>0){
                    $status3 = CreditRecord::addInfo([
                        'uid' => $member['uid'],
                        'type' => 'credit2',
                        'num' => -$params['amount'],
                        'title' => '编辑任务',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "保存成功，扣除新{$params['amount']}余额。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        message('保存失败:-10','','error');
                    }
                }
            }
        }

        Db::commit();

        message('保存成功','/home/mytask.html','success');
    }

}
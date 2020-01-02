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
use app\admin\model\InvitationRebateRecord;
use think\Db;
use think\Log;

class Taskcheck extends Base{

    public function index(){
        $member = $this->checkLogin();
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

        $member_task_join_info = \app\home\model\TaskJoin::getInfoByTaskIdAndUid($id, $this->member['uid']);
        if(is_null($member_task_join_info)){
            message('此任务您还未抢单','','error');
        }

        if (!in_array($member_task_join_info['status'], array(1, 4))) {
            message('当前状态错误，无法上传验证','','error');
        }

        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'is_can_op' => $is_can_op,
            'operate_steps' => $operate_steps,
            'member_task_join_info' => $member_task_join_info,
            'check_text_content' => !empty($item['check_text_content'])?$item['check_text_content']:'',
            'check_text_content_js' => !empty($item['check_text_content'])?str_replace(array("\r", "\n"), array("", "\\n"), $item['check_text_content']):'',
            'check_text_flag' => !empty($item['check_text_content'])?'1':'0'
        ]);
    }

    public function post() {
        $member = $this->checkLogin();
        $params = array_trim(request()->post());
        $id = isset($params['id']) ? intval($params['id']) : 0;
        if(!check_id($id)){
            message('任务不存在','','error');
        }
        $item = \app\home\model\Task::getInfoById($id);
        if(empty($item)){
            message('任务不存在','','error');
        }

        $member_task_join_info = \app\home\model\TaskJoin::getInfoByTaskIdAndUid($id, $this->member['uid']);
        if(is_null($member_task_join_info)){
            message('此任务您还未抢单','','error');
        }

        if (!in_array($member_task_join_info['status'], array(1, 4))) {
            message('当前状态错误，无法上传验证','','error');
        }

        // 获取表单上传文件
        $thumbs = [];
        $files = request()->file('checkFile');
        if(check_array($files)){
            foreach($files as $file){
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
                array_push($thumbs,$record['save_name']);
                //数据库存入失败记录日志
                if(!Uploads::addInfo($record)){
                    Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
                }
            }
        }
        $params['thumbs'] = check_array($thumbs)?serialize($thumbs):'';
        $params['status'] = 2;

        Db::startTrans();
        $status = \app\home\model\TaskJoin::updateInfoById($member_task_join_info['id'], $params);
        if(!$status){
            Db::rollback();
            message('上传验证失败：-1','','error');
        }
        Db::commit();
        message("上传验证成功",'/home/mytaskjoin.html','success');
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;

use app\admin\model\Config;
use app\admin\model\Uploads;
use app\home\model\CreditRecord;
use app\home\model\Member;
use app\home\model\Sign;
use app\home\model\Task;
use app\home\model\TaskJoin;
use app\home\model\Withdraw;
use app\home\model\Recharge;
use think\Db;
use think\Log;

class Account extends Base{

    public function index(){
        $member = $this->checkLogin();
        $is_sign = Sign::getTodayInfoByUid($member['uid']);
        if(request()->isAjax()){
            if(!empty($is_sign)){
                message('今日已签到','','error');
            }
            $config = Config::getInfo();
            $sign_credit1 = 0;
            if(!empty($config['setting']['sign_give_credit1'])){
                $sign_credit1 = floatval(trim($config['setting']['sign_give_credit1']));
            }
            if($sign_credit1 <= 0){
                message('平台未开启签到送积分','','error');
            }
            Db::startTrans();
            $status1= Member::updateInfoById($member['uid'],[
                'credit1' => $member['credit1']+$sign_credit1
            ]);
            if(!$status1){
                Db::rollback();
                message('签到失败：-1','','error');
            }
            $status2 = Sign::addInfo([
                'uid' => $member['uid'],
                'credit1' => $sign_credit1
            ]);
            if(!$status2){
                Db::rollback();
                message('签到失败：-2','','error');
            }
            $status3 = CreditRecord::addInfo([
                'uid' => $member['uid'],
                'type' => 'credit1',
                'num' => $sign_credit1,
                'title' => '签到',
                'remark' => "签到成功，获得{$sign_credit1}积分。"
            ]);
            if(!$status3){
                Db::rollback();
                message('签到失败：-3','','error');
            }
            Db::commit();
            message("签到成功，+{$sign_credit1}积分",'reload','success');
        }
        //发布次数
        $task_num = Task::getTotalCountByMemberId($member['uid']);
        //参与次数
        $join_num = TaskJoin::getTotalCountByMemberId($member['uid']);
        //审核次数
        $params = array();
        $params['uid'] = $member['uid'];
        $params['category_type'] = "audit";
        $audit_num = \app\home\model\MyTask::getCountByParams($params);

        return $this->fetch(__FUNCTION__,[
            'member' => $member,
            'task_num' => $task_num,
            'join_num' => $join_num,
            'audit_num' => $audit_num,
            'is_sign' => !empty($is_sign)?1:0
        ]);
    }

    public function info(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            $file = request()->file('avatar');
             $config = [
                'size' => 2097152,
                'ext'  => 'jpg,gif,png,bmp'
            ];
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate($config)->move(ROOT_PATH . 'public' . DS . 'uploads');
            if(!$info){
                message($file->getError(),'','error');
            }
            $record = [
                'uid' => $member['uid'],
                'extension' => $info->getExtension(),
                'save_name' => str_replace('\\','/',$info->getSaveName()),
                'filename' => $info->getFilename(),
                'md5' => $info->hash('md5'),
                'sha1' => $info->hash('sha1'),
                'size' => $info->getSize(),
                'create_time' => TIMESTAMP
            ];
            //数据库存入失败记录日志
            if(!Uploads::addInfo($record)){
                Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
            }
            $status = Member::updateInfoById($member['uid'],['avatar'=>$record['save_name']]);
            if(!$status){
                message('头像修改失败','','error');
            }
            message('头像修改成功','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member
        ]);
    }

    public function withdraw(){
        $member = $this->checkLogin();
        $setting = ['withdraw_min' => 1, 'withdraw_fee' => 10];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['withdraw_fee'])){
                $setting['withdraw_fee'] = intval($setting['withdraw_fee']);
            }
            if(!empty($setting['withdraw_min'])){
                $setting['withdraw_min'] = intval($setting['withdraw_min']);
            }
        }

        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,'Withdraw');
            if($result !== true){
                message($result,'','error');
            }
            if($params['credit2'] < $setting['withdraw_min']){
                message('提现金额不能少于'.$setting['withdraw_min'].'元','','error');
            }

            //处理是整数的
            params_floor(['credit2'], $params);
            $params['fee'] = $params['credit2'] * ($setting['withdraw_fee'] / 100);
            $credit2 = ($params['credit2'] + $params['fee']);
            if($credit2 > $member['credit2']){
                message('余额不足','','error');
            }

            Db::startTrans();
            $status1 = Member::updateCreditById($member['uid'], 0, -$credit2);
            if(!$status1){
                Db::rollback();
                message('提现失败：-1','','error');
            }
            $status2 = CreditRecord::addInfo([
                'uid' => $member['uid'],
                'type' => 'credit2',
                'num' => -$credit2,
                'title' => '提现',
                'remark' => "申请提现到账号：" . $params['account'] . "，扣除{$credit2}余额。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status2){
                Db::rollback();
                message('提现失败：-2','','error');
            }
            $status3 = Withdraw::addInfo([
                'uid' => $member['uid'],
                'username' => $member['username'],
                'credit2' => $params['credit2'],
                'fee' => $params['fee'],
                'pay_method' => $params['pay_method'],
                'account' => $params['account'],
                'realname' => $params['realname'],
                'mobile' => $params['mobile'],
                'create_time' => TIMESTAMP
            ]);
            if(!$status3){
                Db::rollback();
                message('提现失败：-3','','error');
            }
            Db::commit();

            message('提现成功，请耐心等待平台审核，预计24小时内到帐','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member,
            'setting' => $setting
        ]);
    }

    public function withdrawal(){
        return $this->fetch(__FUNCTION__);
    }

    public function wechatwithdrawal(){
        return $this->fetch(__FUNCTION__);
    }

    public function withdrawlog(){
        $member = $this->checkLogin();

        return $this->fetch(__FUNCTION__);
    }

    public function withdrawlog_ajax() {
        $member = $this->checkLogin();

        $where = ['uid' => $member['uid']];
        $list = \app\admin\model\Withdraw::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }

    public function credit(){
        $member = $this->checkLogin();

        return $this->fetch(__FUNCTION__);
    }

    public function credit_ajax() {
        $member = $this->checkLogin();

        $where = ['uid' => $member['uid'], 'type' => 'credit1'];
        $result = $this->getPagination('Charge', 10, $where, "id DESC", "*");

        to_json(0, '', $result['lists']);
    }

    public function task(){
        return $this->fetch(__FUNCTION__);
    }

    public function recharge(){
        $member = $this->checkLogin();

        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,'Recharge');
            if($result !== true){
                message($result,'','error');
            }
            if($params['credit2'] < 0){
                message('充值金额不能少于0元','','error');
            }

            //处理是整数的
            params_floor(['credit2'], $params);

            // 获取表单上传文件
            $thumbs = [];
            $files = request()->file('thumbs');
            $config = [
                'size' => 2097152,
                'ext'  => 'jpg,gif,png,bmp'
            ];
            if(check_array($files)){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->validate($config)->move(ROOT_PATH . 'public' . DS . 'uploads');
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
            $params['thumbs'] = isset($thumbs[0])?$thumbs[0]:'';

            Db::startTrans();
            $status3 = Recharge::addInfo([
                'uid' => $member['uid'],
                'username' => $member['username'],
                'credit2' => $params['credit2'],
                'realname' => $params['realname'],
                'account' => $params['account'],
                'pay_time' => $params['pay_time'],
                'thumbs' => $params['thumbs'],
                'create_time' => TIMESTAMP
            ]);
            if(!$status3){
                Db::rollback();
                message('提现失败：-3','','error');
            }
            Db::commit();

            message('提交成功，请耐心等待平台审核，预计24小时内到帐','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member
        ]);
    }

    public function rechargelog(){
        $member = $this->checkLogin();

        return $this->fetch(__FUNCTION__);
    }

    public function rechargelog_ajax() {
        $member = $this->checkLogin();

        $where = ['uid' => $member['uid']];
        $list = \app\admin\model\Recharge::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;


use app\admin\model\Channel;
use app\home\model\Member;

class Profile extends Base{

    public function email(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            if($member['is_bind_email'] == 1){
                message('邮箱已经绑定不能修改','','error');
            }
            $email = params('email');
            if(!check_email($email)){
                message('邮箱格式不正确','','error');
            }
            $status = Member::updateInfoById($member['uid'],['email' => $email,'is_bind_email' => 1]);
            if(!$status){
                message('绑定失败','','error');
            }
            message('绑定成功','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member
        ]);
    }

    public function alipay(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            $params = request()->post();
            if($member['is_bind_alipay'] == 1){
                message('支付宝已经绑定不能修改','','error');
            }
            if(empty($params['alipay_account']) || empty($params['alipay_realname'])){
                message('请输入账号和姓名','','error');
            }
            $status = Member::updateInfoById($member['uid'],[
                'alipay_account' => $params['alipay_account'],
                'alipay_realname' => $params['alipay_realname'],
                'is_bind_alipay' => 1
            ]);
            if(!$status){
                message('绑定失败','','error');
            }
            message('绑定成功','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member
        ]);
    }

    public function phone(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            $params = request()->post();
            if($member['is_bind_mobile'] == 1){
                message('手机号已经绑定不能修改','','error');
            }
            if(!check_mobile($params['mobile'])){
                message('手机号格式错误','','error');
            }
            $status = Member::updateInfoById($member['uid'],[
                'mobile' => $params['mobile'],
                'is_bind_mobile' => 1
            ]);
            if(!$status){
                message('绑定失败','','error');
            }
            message('绑定成功','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'member' => $member
        ]);
    }


    public function password(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,[
                'old_password|密码' => 'require|min:6',
                'password|密码' => 'require|min:6|confirm',
                'password_confirm|确认密码' => 'require',
            ]);
            if($result !== true){
                message($result,'','error');
            }
            if(!md5_password_check($params['old_password'],$member['password'],$member['salt'])){
                message('原密码输入错误','','error');
            }
            $status = Member::updateInfoById($member['uid'],[
                'password' => md5_password($params['password'],$member['salt'])
            ]);
            if(!$status){
                message('修改失败','','error');
            }
            message('修改成功，请重新登录','/home/auth/login.html','success');
        }
        return $this->fetch(__FUNCTION__);
    }

    public function channel(){
        $member = $this->checkLogin();
        if(request()->isAjax()){
            if($member['is_bind_channel'] == 1){
                message('您已登记，请勿重复登记','','error');
            }
            $params = request()->post();
            if(!check_id($params['channel_id'])){
                message('请选择合作渠道','','error');
            }
            $result = $this->validate($params,[
                'channel_id|渠道' => 'require',
                'desc|描述信息' => 'require'
            ]);
            if($result !== true){
                message($result,'','error');
            }
            $channel = Channel::getInfoById($params['channel_id']);
            if(empty($channel)){
                message('所选渠道不存在','','error');
            }
            $status = Member::updateInfoById($member['uid'],[
                'channel_id' => $params['channel_id'],
                'channel_name' => $channel['title'],
                'channel_desc' => $params['desc'],
                'is_bind_channel' => 1
            ]);
            if(!$status){
                message('登记失败','','error');
            }
            message('登记成功','reload','success');
        }
        $channels = Channel::getList();
        return $this->fetch(__FUNCTION__,[
            'channels' => $channels,
            'member' => $member
        ]);
    }
}
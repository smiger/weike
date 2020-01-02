<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:56
 */

namespace app\home\controller;

use app\admin\model\Invitation;
use app\home\model\Member;
use think\Cookie;
use think\Loader;
use think\Db;
use think\Log;

class Auth extends  Base{

    //登录
    public function login(){
        if($this->request->isAjax()){
            $params = array_trim(request()->post());
            $validate = $this->validate($params,'Member.login');
            if($validate !== true){
                message($validate,'','error');
            }
            $member = Member::getUserInfoByUsername($params['username']);
            if(empty($member) || $member['is_del'] == 1){
                message('会员信息不存在','','error');
            }
            if(!md5_password_check($params['password'],$member['password'],$member['salt'])){
                message('密码输入错误','','error');
            }
            if($member['is_check'] == 0){
                message('已被管理员禁止登录','','error');
            }
            Cookie::set('member',base64_encode($member));
            message('登录成功','/home.html','success');
        }
        Cookie::delete('member');
        return $this->fetch(__FUNCTION__);
    }

    //注册
    public function register(){
        $parent_uid = intval(trim(params('i')));

        if(request()->isAjax()){
            $params = array_trim(request()->post());
            $validate = Loader::validate('Member');
            if(!$validate->check($params)){
                message($validate->getError(),'','error');
            }

            $parent_member = [];
            if ($parent_uid > 0) {
                $parent_member = Member::getUserInfoById($parent_uid);
                if (!$parent_member) {
                    message('邀请失败','','error');
                }
            }

            Db::startTrans();
            $params['parent_uid'] = $parent_uid;
            $params['salt'] = random(8);
            $params['password'] = md5_password($params['password'],$params['salt']);
            $params['create_time'] = TIMESTAMP;
            unset($params['password_confirm'],$params['invitation_code'],$params['captcha']);
            $insert_member_id = Member::addInfo($params);
            if(!$insert_member_id){
                message('注册失败','','error');
            }

            if ($parent_uid > 0) {
                $status3 = Invitation::addInfo([
                    'uid' => $parent_member['uid'],
                    'username' => $parent_member['username'],
                    'invite_uid' => $insert_member_id,
                    'invite_username' => $params['username'],
                    'create_time' => TIMESTAMP
                ]);
                if(!$status3){
                    Db::rollback();
                    message('注册失败','','error');
                }

                $status4 = Member::updateInviteInfo($parent_uid);
                if(!$status4){
                    Db::rollback();
                    message('注册失败','','error');
                }
            }

            Db::commit();

            message('注册成功','/home/auth/login.html','success');
        }
        return $this->fetch(__FUNCTION__, [
            'parent_uid' => $parent_uid
        ]);
    }

    public function findpwd(){
        return $this->fetch(__FUNCTION__);
    }
}
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

class Register extends  Base{
    //注册
    public function index(){
        $parent_uid = intval(trim(params('i')));

        if(request()->isAjax()){
            $params = array_trim(request()->post());
            $validate = Loader::validate('Member');
            if(!$validate->check($params)){
                message($validate->getError(),'','error');
            }

            $smscode = session("smscode");
          /*   if (empty($smscode) || $params['verification'] != $smscode) {
                message('短信验证码错误','','error');
            } */

            session("smscode", random(6, true));

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
            unset($params['password_confirm'],$params['invitation_code'],$params['captcha'],$params['verification']);
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
    public function sendsmscode() {
        $params = array_trim(request()->post());
        if (!captcha_check($params['validate'])) {
            message('验证码错误','','error');
        }

        //获取对象，如果上面没有引入命名空间，可以这样实例化：$sms = new \alisms\SendSms()
        $sms = new \alisms\SendSms();
        //设置关键的四个配置参数，其实配置参数应该写在公共或者模块下的config配置文件中，然后在获取使用，这里我就直接使用了。
        $sms->accessKeyId = 'LTAIkwsVVLtXc7z7';
        $sms->accessKeySecret = '4hzB3evBtOPJ6iOMmDUmVrkdeKUuXb';
        $sms->signName = '记录生活';
        $sms->templateCode = 'SMS_144451162';

        //$mobile为手机号
        $mobile = $params['mobile'];
        //模板参数，自定义了随机数，你可以在这里保存在缓存或者cookie等设置有效期以便逻辑发送后用户使用后的逻辑处理
        $code = random(6, true);
        $templateParam = array("code" => $code);
        $m = $sms->send($mobile, $templateParam);
        // var_dump($m);exit;
        //类中有说明，默认返回的数组格式，如果需要json，在自行修改类，或者在这里将$m转换后在输出
        //$m = ["Code" => "OK"];
        if ($m["Code"] != "OK") {
            message('发送失败，请稍候尝试','','error');
        }

        session("smscode", $code);

        message('发送成功', '', 'success');
    }
   
}
<?php
namespace app\home\controller;

use app\home\model\Member;

class Forgetpass extends Base {

    public function index() {
        $params = array_trim(request()->get());

        if(!isset($params['uid']) || empty($params['uid'])){
            message('参数错误','','error');
        }

        if(!isset($params['sign']) || empty($params['sign'])){
            message('参数错误','','error');
        }

        $member = Member::getUserInfoById($params['uid']);
        if(empty($member) || $member['is_del'] == 1){
            message('会员信息不存在','','error');
        }
        if($member['is_check'] == 0){
            message('已被管理员禁止登录','','error');
        }
        if(empty($member['sign']) || $member['sign'] != $params['sign']){
            message('参数错误','','error');
        }

        return $this->fetch(__FUNCTION__, [
            'member' => $member
        ]);
    }

    public function changepass() {
        $params = array_trim(request()->post());

        if(!isset($params['captcha']) || empty($params['captcha'])){
            message('验证码不能为空',-2,'error');
        }

        if (!captcha_check($params['captcha'])) {
            message('验证码错误!',-2,'error');
        }

        $member = Member::getUserInfoById($params['uid']);
        if(empty($member) || $member['is_del'] == 1){
            message('会员信息不存在',-1,'error');
        }
        if($member['is_check'] == 0){
            message('已被管理员禁止登录',-1,'error');
        }
        if($member['sign'] != $params['sign']){
            message('参数错误',-1,'error');
        }

        $update = [];
        $update['salt'] = random(8);
        $update['password'] = md5_password($params['password'],$update['salt']);
        $update['sign'] = '';
        $status = Member::updateInfoById($member['uid'], $update);
        if (!$status) {
            message('修改密码失败','','error');
        }

        message('',0,'success');
    }
    
    public function sendmail() {

        $params = array_trim(request()->post());

        if(!isset($params['captcha']) || empty($params['captcha'])){
            message('验证码不能为空',-2,'error');
        }

        if (!captcha_check($params['captcha'])) {
            message('验证码错误!',-2,'error');
        }

        $member = $this->_checkemail($params);

        $sign = random(16);

        $status = Member::updateInfoById($member['uid'], [
            'sign' => $sign
        ]);

        if (!$status) {
            message('更新信息失败',-3,'error');
        }

        $uid = $member['uid'];
        $user_name = $member['username'];
        $email = $member['email'];

        /* 设置重置邮件模板所需要的内容信息 */
        $template    = '{$user_name}您好！<br>
        <br>
        您已经进行了密码重置的操作，请点击以下链接(或者复制到您的浏览器):<br>
        <br>
        <a href="{$reset_email}" target="_blank">{$reset_email}</a><br>
        <br>
        以确认您的新密码重置操作！<br>
        <br>
        {$send_date}';
        $reset_email = $this->GetDomain() . '/home/forgetpass.html?uid=' . $uid . '&sign=' . $sign;

        $content = str_replace(array('{$user_name}', '{$reset_email}', '{$send_date}'), array($user_name, $reset_email,   date('Y-m-d')), $template);

        $result = send_email($email, '密码找回', $content);
        if($result !== true){
            message($result, -3, 'error');
        }

        message('',0,'success');
    }

    public function checkemail() {
        $params = array_trim(request()->post());

        $this->_checkemail($params);

        message('',0,'success');
    }

    private function _checkemail($params) {
        if(!isset($params['username']) || empty($params['username'])){
            message('用户名不能为空','','error');
        }

        if(!isset($params['username']) || empty($params['username'])){
            message('邮箱不能为空','','error');
        }

        if(!check_email($params['email'])){
            message('邮箱格式不正确','','error');
        }

        $member = Member::getUserInfoByUsername($params['username']);
        if(empty($member) || $member['is_del'] == 1){
            message('会员信息不存在',-1,'error');
        }
        if($member['is_check'] == 0){
            message('已被管理员禁止登录',-1,'error');
        }

        if($member['email'] != $params['email']){
            message('用户名和邮箱不匹配',-1,'error');
        }

        return $member;
    }

    /**
     * 取得当前的域名
     *
     * @access  public
     *
     * @return  string      当前的域名
     */
    private function GetDomain()
    {
        /* 协议 */
        $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        else
        {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT']))
            {
                $port = ':' . $_SERVER['SERVER_PORT'];

                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
                {
                    $port = '';
                }
            }
            else
            {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME']))
            {
                $host = $_SERVER['SERVER_NAME'] . $port;
            }
            elseif (isset($_SERVER['SERVER_ADDR']))
            {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }

        return $protocol . $host;
    }

}
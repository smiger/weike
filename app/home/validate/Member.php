<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\home\validate;

class Member extends Base {

    //验证规则
    protected $rule = [
        'username|手机号' => 'require|max:25|min:4|unique:member|checkMobile:',
        //'email|邮箱'=>'require|checkEmail:',
        'password|密码' => 'require|min:6|confirm',
        'password_confirm|确认密码' => 'require',
        'captcha|验证码'=>'require|captcha'
    ];


    //错误提示信息
    protected $message = [
        'password.confirm' => '两次密码输入不一致'
    ];

    //定义场景
    protected $scene = [
        'login'   =>  [
            'name'=>'require|max:25|min:4',
            'password|密码' => 'require|min:6',
            'captcha'
        ]
    ];

    //检测开始时间
    protected function checkEmail($email){
        if (!check_email($email)) {
            return '邮箱格式不正确';
        }
        return true;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\admin\validate;

class Administrator extends Base {

    //验证规则
    protected $rule = [
        'username|用户名' => 'require|max:25|min:4|unique:administrator',
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
            'username' => 'require|max:25|min:4',
            'password' => 'require|min:6',
            'captcha'
        ],
        'add'   =>  [
            'username' => 'require|max:25|min:4|unique:administrator',
            'password' => 'require|min:6|confirm',
            'password_confirm' => 'require|min:6'
        ],
        'edit'   =>  [
            'username' => 'max:25|min:4',
            'password' => 'min:6|confirm',
            'password_confirm' => 'min:6'
        ],
        'change'   =>  [
            'password' => 'min:6|confirm',
            'password_confirm' => 'min:6'
        ]
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\home\validate;

class Withdraw extends Base {

    //验证规则
    protected $rule = [
        'pay_method|提现方式' => 'require|checkPayMethod:',
        'account|提现账号' => 'require',
        'realname|真实姓名' => 'require',
        'mobile|手机号'=>'require|checkMobile:',
        'credit2|提现金额' => 'require'
    ];

    //检测提现方式
    protected function checkPayMethod($pay_method){
       if(!in_array($pay_method,[0,1,2])){
           return '提现方式选择错误';
       }
       return true;
    }




}
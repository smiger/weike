<?php
namespace app\home\validate;

class Recharge extends Base {

    //验证规则
    protected $rule = [
        'realname|真实姓名' => 'require',
        'credit2|提现金额' => 'require'
    ];

}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\admin\validate;

class Channel extends Base{

    //验证规则
    protected $rule = [
        'title|名称' => 'require'
    ];
}
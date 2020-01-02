<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\admin\validate;

class Lock extends Base{

    //验证规则
    protected $rule = [
        'name|网站名称' => 'require',
        'domain|域名' => 'require|unique:lock'
    ];
}
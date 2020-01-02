<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/3/30
 * Time: 下午2:26
 */

namespace app\api\controller;



class Lock extends Base
{

    //获取锁文件
    public function get(){
        to_json(0,'验证成功');
    }
}
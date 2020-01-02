<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 21:38
 */

namespace app\home\controller;


class Help extends Base
{
    public function index(){
        return $this->fetch(__FUNCTION__);
    }
}
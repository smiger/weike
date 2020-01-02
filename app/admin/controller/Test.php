<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/2/1
 * Time: 14:36
 */

namespace app\admin\controller;
use think\Exception;

/**
 * Class Test
 * @package app\admin\controller
 * 测试控制器
 */
class Test extends Base
{

    //测试发送邮件
    public function email(){
        $email = trim(params('email'));
        if(!check_email($email)){
            message('邮箱格式填写错误','','error');
        }
        $result = send_email($email,'测试邮件','郑州慕马树网络科技有限公司，专注于微信第三方开发，手机app定制开发，网站建设，服务器维护等，全心全意服务，客户至上，售后无忧');
        if($result !== true){
            message($result,'','error');
        }
        message('发送成功','','success');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 12:22
 */

namespace app\admin\controller;
use app\admin\model\Administrator;
use think\Cookie;

class Auth extends Base
{

    /**
     * @return mixed
     * 登录操作
     */
    public function login(){
        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,'Administrator.login');
            if($result !== true){
                message($result,'','error');
            }
            $administrator = Administrator::getInfoByUsername($params['username']);
            if(empty($administrator)){
                message('管理员信息不存在','','error');
            }
            if(!md5_password_check($params['password'],$administrator['password'],$administrator['salt'])){
                message('密码输入错误','','error');
            }
            Cookie::set('administrator',base64_encode($administrator));
            cache('DB_TREE_MENU_' . $administrator['id'], NULL);
            message('登录成功', U('index/index'), 'success');
        }
        Cookie::delete('administrator');
        return $this->fetch(__FUNCTION__);
    }
}
<?php
namespace app\admin\controller;

use app\admin\model\Administrator;
use app\admin\model\Config;
use app\admin\model\Uploads;
use think\Log;

class Index extends Base
{

    //网站设置
    public function index(){
        $config = Config::getInfo();
        if(request()->isAjax()){
            $params = ['setting' => params_array('setting')];
            if(!check_array($params['setting'])){
                message('参数填写错误','','error');
            }
            $params['setting'] = serialize(array_trim($params['setting']));
            if(!empty($config)){
                $params['update_time'] = TIMESTAMP;
                $status = Config::updateInfoById($config['id'],$params);
            }else{
                $params['create_time'] = TIMESTAMP;
                $status = Config::addInfo($params);
            }
            if(!$status){
                message('设置失败','','error');
            }
            message('设置成功','reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'config' => $config
        ]);
    }

    //上传文件管理
    public function uploads(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的文件','','error');
            }
            $files = Uploads::getList(['id' => ['in',$ids]]);
            $error = 0;
            $success = 0;
            if(!empty($files)){
                foreach ($files as $k => $info){
                    $file = full_uploads_file($info['save_name']);
                    if(!is_file($file) || unlink($file)){
                        //如果文件不存在或者文件删除成功，执行
                        $status = Uploads::deleteInfoById($info['id']);
                        if(!$status){
                            $error++;
                            Log::error(__FILE__.':'.__LINE__.' 错误：ID:'.$info['id'].'#'.$info['save_name'].'数据库记录删除失败');
                            continue;
                        }
                        $success ++;
                    }
                }
            }
            message("删除成功{$success}个，失败{$error}个",'reload','info');
        }
        $where = [];
        $params = request()->param();
        if(!empty($params['keyword'])){
            $where['filename'] = ['like',"%{$params['keyword']}%"];
        }
        $list = Uploads::getList($where);
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager
        ]);
    }


    //资料设置
    public function profile(){
        if(request()->isAjax()){
            $params = request()->post();
            $result = $this->validate($params,'Administrator.change');
            if($result !== true){
                message($result,'','error');
            }
            if(!empty($params['password'])){
                $params['password'] = md5_password($params['password'],$this->administrator['salt']);
            }else{
                //不修改密码
                unset($params['password']);
            }
            unset_array('password_confirm',$params);
            $params['update_time'] = TIMESTAMP;
            $status = Administrator::updateInfoById($this->administrator['id'],$params);
            if(!$status){
                message('修改失败','','error');
            }
            if(!empty($params['password'])){
                message('修改成功，请重新登录', U('auth/login'), 'success');
            }
            message('修改成功','reload','success');
        }
        return $this->fetch(__FUNCTION__);
    }

}

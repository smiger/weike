<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/23
 * Time: 21:33
 */

namespace app\admin\controller;

class Channel extends Base{


    //分类管理
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的渠道','','error');
            }
            $status = \app\admin\model\Channel::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message("删除成功",'reload','success');
        }
        $list = \app\admin\model\Channel::getList();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $list->render()
        ]);
    }


    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Channel::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            $validate = $this->validate($params,'Channel');
            if($validate !== true){
                message($validate,'','error');
            }
            if(empty($item)){
                $op = "添加";
                $status = \app\admin\model\Channel::addInfo($params);
            }else{
                $op = "修改";
                $status = \app\admin\model\Channel::updateInfoById($id,$params);
            }
            if(!$status){
                message("{$op}失败",'','error');
            }
            message("{$op}成功",'reload','success');
        }
        return $this->fetch(__FUNCTION__,[
            'item' => $item
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/23
 * Time: 21:33
 */

namespace app\admin\controller;

use app\admin\model\TaskConfig;
use think\Loader;

class Taskcategory extends Base{

    //分类管理
    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的分类','','error');
            }
            $status = \app\admin\model\TaskCategory::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message('删除成功','reload','success');
        }
        $list = \app\admin\model\TaskCategory::getList();
        return $this->fetch(__FUNCTION__,[
            'list' => $list
        ]);
    }


    public function post_category(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\TaskCategory::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            $validate = $this->validate($params,'TaskCategory');
            if($validate !== true){
                message($validate,'','error');
            }
            params_floor(['order_by'],$params);
            param_is_or_no(['is_display'],$params);
            params_round(['min_give_credit1','min_give_credit2'],$params);
            if(empty($item)){
                $params['create_time'] = TIMESTAMP;
                $op = "添加";
                $status = \app\admin\model\TaskCategory::addInfo($params);
            }else{
                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                $status = \app\admin\model\TaskCategory::updateInfoById($id,$params);
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
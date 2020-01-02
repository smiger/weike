<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/29
 * Time: 13:02
 */

namespace app\admin\controller;


class Notice extends Base{

    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的公告','','error');
            }
            $status = \app\admin\model\Notice::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message("删除成功",'reload','success');
        }
        $where = [];
        $params = request()->param();
        if(!empty($params['keyword'])){
            $where['name'] = ['like',"%{$params['keyword']}%"];
        }
        if(!empty($params['is_display'])){
            $where['is_display'] = ['in',$params['is_display']];
        }
        $list = \app\admin\model\Notice::getList(15,$where);
        $pager = $list->render();
        return $this->fetch(__FUNCTION__, [
            'list' => $list,
            'pager' => $pager
        ]);
    }


    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Notice::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            $validate = $this->validate($params,'Notice');
            if($validate !== true){
                message($validate,'','error');
            }
            params_floor(['order_by'],$params);
            param_is_or_no(['is_display'],$params);
            if(empty($item)){
                $params['create_time'] = TIMESTAMP;
                $op = "添加";
                $status = \app\admin\model\Notice::addInfo($params);
            }else{
                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                $status = \app\admin\model\Notice::updateInfoById($id,$params);
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
<?php
/**
 * Created by PhpStorm.
 * User: 俊俊de小嘉琪
 * Date: 2018/2/14
 * Time: 11:35
 */

namespace app\admin\controller;


class Charge extends  Base
{

    public function index(){
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的反馈','','error');
            }
            $status = \app\admin\model\Feedback::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message("删除成功",'reload','success');
        }
        $where = ['parent_id' => 0];
        $params = request()->param();
        if(!empty($params['keyword'])){
            $where['username|content'] = ['like',"%{$params['keyword']}%"];
        }
        if(!empty($params['is_reply'])){
            $where['is_reply'] = ['in',$params['is_reply']];
        }
        $total = \app\admin\model\Feedback::getCount($where);
        $list = \app\admin\model\Feedback::getPagination($where, 15, $total, "update_time DESC");
        $pager = $list->render();
        return $this->fetch(__FUNCTION__, [
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }

}
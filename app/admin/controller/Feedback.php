<?php
namespace app\admin\controller;

use think\Db;
use think\Log;

class Feedback extends Base{

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

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = \app\admin\model\Feedback::getInfoById($id);
        }

        $where = ['parent_id' => $id];
        $list = \app\admin\model\Feedback::getPagination($where, 15, NULL, "id");

        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'list' => $list,
            'pager' => $list->render()
        ]);
    }

    public function save() {
        $params = request()->post();
        if(empty($params['content'])){
            message('请输入您需要反馈的内容','','error');
        }

        $params['parent_id'] = isset($params['parent_id']) ? intval($params['parent_id']) : 0;

        $request = request();

        Db::startTrans();
        $status3 = \app\admin\model\Feedback::addInfo([
            'uid' => 0,
            'username' => '',
            'content' => $params['content'],
            'parent_id' => $params['parent_id'],
            'ip' => $request->ip(),
            'create_time' => TIMESTAMP,
            'update_time' => TIMESTAMP
        ]);
        if(!$status3){
            Db::rollback();
            message('反馈失败：-1','','error');
        }

        if ($params['parent_id'] > 0) {
            \app\admin\model\Feedback::updateInfoById($params['parent_id'], [
                'is_reply' => 1,
                'update_time' => TIMESTAMP
            ]);
        }

        Db::commit();

        message('反馈成功','reload','success');
    }
}
<?php
namespace app\home\controller;

use think\Db;
use think\Log;

class Feedback extends Base {

    public function index() {
        $member = $this->checkLogin();

        return $this->fetch(__FUNCTION__);
    }

    public function ajax() {
        $member = $this->checkLogin();

        $where = ['uid' => $member['uid'], 'parent_id' => 0];
        $list = \app\admin\model\Feedback::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }

    public function add() {
        $member = $this->checkLogin();

        return $this->fetch(__FUNCTION__);
    }

    public function save() {
        $member = $this->checkLogin();

        $params = request()->post();
        if(empty($params['content'])){
            message('请输入您需要反馈的内容','','error');
        }

        $params['parent_id'] = isset($params['parent_id']) ? intval($params['parent_id']) : 0;
        $redirect = $params['parent_id'] > 0 ? 'reload' : 'javascript:history.back()';

        $request = request();

        Db::startTrans();
        $status3 = \app\admin\model\Feedback::addInfo([
            'uid' => $member['uid'],
            'username' => $member['username'],
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
            Db::table('tb_feedback')
            ->where('id', $params['parent_id'])
            ->inc('son', 1)
            ->exp('is_reply', 0)
            ->exp('update_time', TIMESTAMP)
            ->update();
        }

        Db::commit();

        message('反馈成功',$redirect,'success');
    }

    public function detail($id = 0) {
        $member = $this->checkLogin();

        $id = intval($id);
        if(!check_id($id)){
            message('反馈ID错误','','error');
        }
        $item = \app\admin\model\Feedback::getInfoById($id);
        if (empty($item)) {
            message('反馈ID错误','','error');
        }

        if ($item['uid'] != $member['uid']) {
            message('反馈ID错误','','error');
        }

        $where = ['parent_id' => $id];
        $list = \app\admin\model\Feedback::getPagination($where, 15, NULL, "id");

        return $this->fetch(__FUNCTION__,[
            'item' => $item,
            'list' => $list,
            'pager' => $list->render()
        ]);
    }

}
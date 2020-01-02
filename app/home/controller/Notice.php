<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:27
 */

namespace app\home\controller;


class Notice extends Base{

    public function index(){
        $list = \app\admin\model\Notice::getList(15);
        if(request()->isAjax()){
            if(empty($list)){
                message('没有更多信息','','error');
            }
        }
        return $this->fetch(__FUNCTION__,[
            'list' => $list
        ]);
    }

    public function detail(){
        $id = floor(trim(params('id')));
        if(!check_id($id)){
            message('公告ID错误','','error');
        }
        $item = \app\admin\model\Notice::getInfoById($id);
        return $this->fetch(__FUNCTION__,[
            'item' => $item
        ]);
    }
}
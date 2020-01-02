<?php
namespace app\admin\controller;

use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

class Invitation extends Base
{
    public function index(){
        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['uid|username|invite_uid|invite_username'] = ['like', "%{$params['keyword']}%"];
            }
        }

        $total = \app\admin\model\Invitation::getCount($where);
        $list = \app\admin\model\Invitation::getPagination($where, 15, $total, "id DESC");
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }
}
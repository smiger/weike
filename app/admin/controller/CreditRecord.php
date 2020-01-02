<?php
namespace app\admin\controller;

use think\Db;
use think\Log;

class CreditRecord extends Base{

    public function index(){
        $where = [];
        $params = request()->param();
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['uid'] = $params['keyword'];
            }
        }

        $total = \app\admin\model\CreditRecord::getCount($where);
        $list = \app\admin\model\CreditRecord::getPagination($where, 15, $total, "id DESC");

        $ids = array();
        foreach ($list as $key => $value) {
            $ids[] = $value['uid'];
        }
        $members = \app\admin\model\Member::getInfoByIds($ids);
        $member_names = [];
        foreach ($members as $member) {
            $member_names[$member['uid']] = $member['username'];
        }
        $GLOBALS['member_names'] = $member_names;

        $itemsCallback = function ($item, $key) {
            global $member_names;
            $item['username'] = isset($GLOBALS['member_names'][$item['uid']]) ? $GLOBALS['member_names'][$item['uid']] : '-';
            return $item;
        };

        $list->each($itemsCallback);


        $pager = $list->render();
        return $this->fetch(__FUNCTION__, [
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }

}
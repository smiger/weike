<?php
namespace app\admin\model;

class AuthRule extends Base
{
    public function getStatusTurnAttr($value, $data)
    {
        $turnArr = [0=>lang('status0'), 1=>lang('status1')];
        return $turnArr[$data['status']];
    }
    public function getLevelTurnAttr($value, $data)
    {
        $turnArr = [1=>lang('auth_level_1'), 2=>lang('auth_level_2'), 3=>lang('auth_level_3')];
        return $turnArr[$data['level']];
    }
    public function getIsmenuTurnAttr($value, $data)
    {
        $turnArr = [0=>lang('ismenu0'), 1=>lang('ismenu1')];
        return $turnArr[$data['ismenu']];
    }
    
    public function treeList($module = '', $status = '')
    {
        $where = [];
        if ($module != ''){
            $where = [
                'module' => $module
            ];
        }
        if ($status != ''){
            $where['status'] = $status;
        }
        $list = $this->where($where)->order('sorts ASC,id ASC')->select();
        $treeClass = new \expand\Tree();
        $list = $treeClass->create($list);
        return $list;
    }
    
    public function treeRules($module = '', $status = '')
    {
        $where = [];
        if ($module != ''){
            $where = [
                'module' => $module
            ];
        }
        if ($status != ''){
            $where['status'] = $status;
        }
        $list = $this->where($where)->order('sorts ASC,id ASC')->select();
        $list = $this->mergeNodes($list);
        return $list;
    }

    public function mergeNodes($rules, $pid = 0){
        $arr = array();
        foreach ($rules as $v) {
            if ($v["pid"] == $pid) {
                $v["children"] = $this->mergeNodes($rules, $v["id"]);
                $v["childnum"] = count($v["children"]);
                $arr[] = $v;
            }
        }
        return $arr;
    }
}
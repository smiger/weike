<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AuthGroup as AuthGroups;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroupAccess;
use app\admin\model\User;

class AuthGroup extends Base
{
    private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new AuthGroups;   //别名：避免与控制名冲突
    }
    
    public function index()
    {
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的数据','','error');
            }
            $status = AuthRules::deleteByIds($ids);
            if(!$status){
                message('删除失败','','error');
            }
            message("删除成功",'reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['title'] = ['like', "%{$params['keyword']}%"];
            }
            if(!empty($params['status'])){
                $where['status'] = ['in',$params['status']];
            }
        }

        $total = AuthGroups::getCount($where);
        $list = AuthGroups::getPagination($where, 15, $total, "id");
        $pager = $list->render();
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'pager' => $pager,
            'total' => $total
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = AuthGroups::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            /*$validate = $this->validate($params,'AuthGroup');
            if($validate !== true){
                message($validate,'','error');
            }*/
            param_is_or_no(['status'],$params);
            if(empty($item)){
                $params['create_time'] = TIMESTAMP;
                $op = "添加";
                $status = AuthGroups::addInfo($params);
            }else{
                $params['rules'] = $params['rules'] ? implode(',', $params['rules']) : '';
                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                $status = AuthGroups::updateInfoById($id,$params);
            }
            if(!$status){
                message("{$op}失败",'','error');
            }
            message("{$op}成功",'reload','success');
        }

        $arModel = new AuthRule();

        if ($item) {
            $authRuleTree = $arModel->treeList('', 1);   //树形权限节点列表
            
            $rulesArr = explode(',', $item['rules']);   //以前就拥有的权限节点
            foreach ($authRuleTree as $k => $val){
                if(in_array($val['id'], $rulesArr)){
                    $authRuleTree[$k]['ischeck'] = 'y';
                }else {
                    $authRuleTree[$k]['ischeck'] = 'n';
                }
            }
        } else {
            $authRuleTree = $arModel->treeList();
        }

        $this->assign('authRuleTree', $authRuleTree);   //树形权限节点列表

        return $this->fetch(__FUNCTION__,[
            'item' => $item
        ]);
    }
     
    public function authUser($id)
    {
        $agaModel = new AuthGroupAccess();
        if (request()->isPost()){
            $data = input('post.');
            $group_id = $data['id'];   //当前角色ID
            $uid = $data['uid'];   //新提交授权用户数组:[1,2,3,4....]
            
            $oldData = $agaModel->where(['group_id' => $group_id])->select();
            $oldUser = array();   //以前授权用户
            $mixArr = array();   //交集授权用户
            $addArr = array();   //新增授权用户
            $delArr = array();   //删除授权用户
            foreach ($oldData as $k =>$v){
                $oldUser[] = $v['uid'];
            }
            $mixArr = array_intersect($uid, $oldUser);
            if (empty($mixArr)){
                $addArr = $uid;
                $delArr = $oldUser;
            }else{
                $addArr = array_diff($uid, $mixArr);
                $delArr = array_diff($oldUser, $mixArr);
            }
            if (!empty($delArr)){
                $where = [
                    'group_id' => $group_id,
                    'uid' => ['in', $delArr],
                ];
                $agaModel->where($where)->delete();
            }
            if (!empty($addArr)){
                $addList = array();
                foreach ($addArr as $k => $v){
                    $addList[] = ['group_id' => $group_id, 'uid' => $v];
                }
                $agaModel->saveAll($addList, false);
            }
            return ajaxReturn(lang('action_success'), url('index'));
        }else{
            $authList = $agaModel->alias('a')->join('user u','a.uid = u.id')
                ->field('u.id,u.username,u.name')
                ->where(['group_id' => $id])->select();   //已经拥有权限用户
            
            $uModel = new User();
            $userList = $uModel->field('id,username,name')->select();   //全部用户
            
            foreach ($userList as $k => $v){   //删除全部用户中已授权用户
                foreach ($authList as $k2 => $v2){
                    if ($v['id'] == $v2['id']){
                        unset($userList[$k]);
                        break;
                    }
                }
            }
            $this->assign('id', $id);
            $this->assign('userList', $userList);
            $this->assign('authList', $authList);
            return $this->fetch();
        }
    }
}
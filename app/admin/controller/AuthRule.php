<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AuthRule as AuthRules;

class AuthRule extends Base
{
    private $cModel;   //当前控制器关联模型
    private $module = 'admin';
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new AuthRules;   //别名：避免与控制名冲突
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

        $list = $this->cModel->treeList($this->module);
        $total = count($list);
        return $this->fetch(__FUNCTION__,[
            'list' => $list,
            'total' => $total
        ]);
    }

    public function post(){
        $id = params('id');
        $item = [];
        if(check_id($id)){
            $item = AuthRules::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            /*$validate = $this->validate($params,'AuthRule');
            if($validate !== true){
                message($validate,'','error');
            }*/
            params_floor(['level', 'sorts'], $params);
            param_is_or_no(['status', 'ismenu'], $params);
            $params['ismenu'] = $params['level'] == 3 ? 0 : 1;
            if(empty($item)){
                $params['create_time'] = TIMESTAMP;
                $op = "添加";
                $status = AuthRules::addInfo($params);
            }else{
                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                $status = AuthRules::updateInfoById($id,$params);
            }
            if(!$status){
                message("{$op}失败",'','error');
            }
            message("{$op}成功",'reload','success');
        }

        $treeList = $this->cModel->treeList($this->module);
        $this->assign('module', $this->module);
        $this->assign('treeList', $treeList);
        
        return $this->fetch(__FUNCTION__,[
            'item' => $item
        ]);
    }
}
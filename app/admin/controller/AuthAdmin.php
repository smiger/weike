<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Administrator as Administrators;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use think\Db;

class AuthAdmin extends Base
{
    private $cModel;   //当前控制器关联模型
    
    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Administrators;   //别名：避免与控制名冲突
    }

    public function index()
    {
        if(request()->isAjax()){
            $ids = params_array('ids');
            if(!check_array($ids)){
                message('请选择要删除的数据','','error');
            }

            Db::startTrans();
            try{
                $result = Administrators::deleteByIds($ids);

                $where1 = [ 'uid' => ['in', $ids] ];
                $agaModel = new AuthGroupAccess();
                $agaModel->where($where1)->delete();   //删除用户分配角色
                // 提交事务
                if ($result){
                    Db::commit();
                }else{
                    message($this->cModel->getError(),'','error');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                message('删除失败','','error');
            }

            message("删除成功",'reload','success');
        }

        $params = request()->get();
        $where = [];
        if(check_array($params)){
            if(!empty($params['keyword'])){
                $where['username|nickname'] = ['like', "%{$params['keyword']}%"];
            }
            if(!empty($params['status'])){
                $where['status'] = ['in',$params['status']];
            }
        }

        $total = Administrators::getCount($where);
        $list = Administrators::getPagination($where, 15, $total, "id DESC");
        $pager = $list->render();

        $agMolde = new AuthGroup();
        $agList = $agMolde->select();
        $agListArr = [];
        $agListArrPic = [];
        foreach ($agList as $k => $v){
            $agListArr[$v['id']] = $v['title'];
            $agListArrPic[$v['id']] = $v['pic'];
        }
        foreach ($list as $k => $v){
            if (!empty($v['userGroup'])){
                foreach ($v['userGroup'] as $k2 => $v2){
                    $v['userGroup'][$k2]['title'] = $agListArr[$v2['group_id']];
                    $v['userGroup'][$k2]['pic'] = $agListArrPic[$v2['group_id']];
                }
            }
        }

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
            $item = Administrators::getInfoById($id);
        }
        if(request()->isAjax()){
            $params = array_trim(request()->post());
            param_is_or_no(['is_check'], $params);
            if(empty($item)){
                $result = $this->validate($params, 'Administrator.add');
                if($result !== true){
                    message($result,'','error');
                }

                $params['create_time'] = TIMESTAMP;
                $op = "添加";

                $params['salt'] = random(8);
                $params['password'] = md5_password($params['password'], $params['salt']);

                unset($params['password_confirm']);

                $status = Administrators::addInfo($params);
            }else{
                $result = $this->validate($params, 'Administrator.edit');
                if($result !== true){
                    message($result,'','error');
                }

                $params['update_time'] = TIMESTAMP;
                $op = "修改";
                unset($params['username']);

                if(!empty($params['password'])){
                    $params['salt'] = random(8);
                    $params['password'] = md5_password($params['password'], $params['salt']);
                }else{
                    //不修改密码
                    unset($params['password']);
                }

                unset($params['password_confirm']);

                $status = Administrators::updateInfoById($id,$params);
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
    
    public function authGroup($id)
    {
        $agaModel = new AuthGroupAccess;
        if (request()->isPost()){
            $data = input('post.');
            $uid = $data['id'];
            $group_id = $data['group_id'];
            $where = ['uid' => $uid];
            $agaModel->where($where)->delete();
            if (!empty($group_id)){
                $addList = array();
                foreach ($group_id as $k =>$v){
                    $addList[] = ['uid' => $uid, 'group_id' => $v];
                }
                $agaModel->saveAll($addList, false);
            }
            message("授权角色成功",'reload','success');
        }else{
            if ($id > 0){
                $agModel = new AuthGroup();
                $groupList = $agModel->where(['status' => 1])->order('id ASC')->select();   //所有正常角色
                $userGroup = $agaModel->where(['uid' => $id])->select();   //当前用户已拥有角色
                foreach ($groupList as $k => $v){
                    foreach ($userGroup as $k2 => $v2){
                        if ($v2['group_id'] == $v['id']){
                            $groupList[$k]['ischeck'] = 'y';
                            break;
                        }
                    }
                }
                $item = $this->cModel->get($id);
                $this->assign('item', $item);
                $this->assign('groupList', $groupList);
                return $this->fetch();
            }
        }
    }
}
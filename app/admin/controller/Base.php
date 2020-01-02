<?php
namespace app\admin\controller;
use think\Config;
use think\Controller;
use think\Cookie;
use app\admin\model\AuthRule as AuthRules;
use expand\Auth;

/**
 * Class Base
 * @package app\home\controller
 * 控制器基类
 */
class Base extends Controller {

    //是否开启wap端模板，设置true时，手机端自动切换到mobile下的视图
    const AUTO_MOBILE = false;

    //登录后的管理员信息
    protected $administrator = [];

    /**
     * Base constructor.
     */
    public function __construct(){
        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('CONTROLLER_PARSENAME', \think\Loader::parseName(request()->controller()));
        define('ACTION_NAME', request()->action());
        define('ACTION_PARSENAME', \think\Loader::parseName(request()->action()));
        //初始化视图
        $this->_initViewPath();
        //最后初始化父类
        parent::__construct();

        //检查是否登录
        $this->_checkLogin();

        //向视图传递变量
        $this->assign([
            'controller' => strtolower(request()->controller()),
            'action' => strtolower(request()->action()),
            'admin' => $this->administrator
        ]);
        $treeMenu = $this->treeMenu();
        $this->assign('treeMenu', $treeMenu);
        $this->filterAccess();
    }

    /**
     * 初始化视图路径
     */
    private function _initViewPath(){
        $viewPath = "./views/admin/pc/";
        if(self::AUTO_MOBILE && request()->isMobile()){
            $viewPath = "./views/admin/mobile/";
        }
        Config::set('template.view_path',$viewPath);
    }


    /**
     * 检查是否需要登录
     */
    private function _checkLogin(){
        if(Cookie::has('administrator')){
            $this->administrator = (array)json_decode(base64_decode(Cookie::get('administrator')));
        }
        //未登录，当前控制器不属于免登录
        if(!in_array(request()->controller(),['Auth']) && !check_array($this->administrator)){
            if(request()->isAjax()){
                message('请先登录', U('auth/login'), 'error');
            }
            $this->redirect(U('auth/login'));
        }
    }

    public function treeMenu()
    {
        if (!isset($this->administrator['id']) || $this->administrator['id'] <= 0) {
            return;
        }

        $uid = $this->administrator['id'];

        $treeMenu = cache('DB_TREE_MENU_'.$uid);
        if(!$treeMenu){
            $where = [
                'ismenu' => 1,
                'module' => 'admin',
            ];
            if ($uid != '-1'){
                $where['status'] = 1;
            }
            $arModel = new AuthRules();
            $lists =  $arModel->where($where)->order('sorts ASC,id ASC')->select();
            $treeMenu = $lists;
            /*$treeClass = new \expand\Tree();
            $treeMenu = $treeClass->create($lists);*/
            //判断导航tree用户使用权限
            foreach($treeMenu as $k=>$val){
                if( authcheck($val['name'], $uid) == 'noauth' ){
                    unset($treeMenu[$k]);
                }
            }
            $treeMenu = $arModel->mergeNodes($treeMenu);
            cache('DB_TREE_MENU_'.$uid, $treeMenu);
        }
        return $treeMenu;
    }

    /**
     * 权限过滤
     * @return
     */
    protected function filterAccess() {
        if ('auth' === CONTROLLER_PARSENAME && 'login' === ACTION_PARSENAME) {
            return ;
        }
        if ('upload' === CONTROLLER_PARSENAME || 'region' === CONTROLLER_PARSENAME || 'ueditor' === CONTROLLER_PARSENAME) {
            return ;
        }

        $auth = new Auth();
        $auth_action_array = [
            'post_category', 'post', 'save', 'check', 'authgroup'
        ];
        $name = CONTROLLER_PARSENAME.'/'.str_replace($auth_action_array, 'index', ACTION_PARSENAME);
        if (!$auth->check($name, $this->administrator['id'])){
            message('没有权限', U('auth/login'), 'error');
        }
    }

    public function _empty() {
        abort(404,'页面不存在');
    }
}
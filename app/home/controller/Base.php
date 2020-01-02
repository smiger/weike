<?php
namespace app\home\controller;

use app\admin\model\Site;
use app\home\model\Member;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Loader;
use think\Db;
use think\Log;
use app\admin\model\Uploads;

/**
 * Class Base
 * @package app\home\controller
 * 控制器基类
 */
class Base extends Controller {

    //是否开启wap端模板，设置true时，手机端自动切换到mobile下的视图
    const AUTO_MOBILE = true;

    //登录后的会员信息
    protected $member = [];

    //网站信息
    protected $site = [];

    /**
     * Base constructor.
     */
    public function __construct(){
        //初始化视图
        $this->_initViewPath();
        //最后初始化父类
        parent::__construct();

        //获取网站信息
        $config = \app\admin\model\Config::getInfo();
        if(!empty($config['setting'])){
            $this->site = $config['setting'];
        }
		
        //如果登录，获取会员信息
        if(Cookie::has('member')){
            $token = (array)json_decode(base64_decode(Cookie::get('member')));
            if(!empty($token['uid'])){
                $member = Member::getUserInfoById($token['uid']);
                if(!empty($member)){
                    $this->member = $member;
                }
            }
        }

        //向视图传递变量
        $this->assign([
            'controller' => strtolower(request()->controller()),
            'action' => strtolower(request()->action()),
            'site' => $this->site,
            'member' => $this->member
        ]);
    }

    /**
     * 初始化视图路径
     */
    private function _initViewPath(){
        $viewPath = "./views/home/pc/";
        //网站没有PC端
        if(true || self::AUTO_MOBILE && request()->isMobile()){
            $viewPath = "./views/home/mobile/";
            //Config::set('exception_tmpl','./static/error/mobile/default.phtml');
        }
        Config::set('template.view_path',$viewPath);
    }

    /**
     * 检查是否需要登录
     */
    private function _checkLogin(){

        //未登录，当前控制器不属于免登录
        if(!in_array(request()->controller(),['Auth','Index','Activity']) && !check_array($this->member)){
            if(request()->isAjax()){
                message('请先登录','/home/auth/login.html','error');
            }
            $this->redirect('/home/auth/login.html');
        }
    }

    /**
     * 检测是否登录
     */
    protected function checkLogin(){
        $token = [];
        if(Cookie::has('member')){
            $token = (array)json_decode(base64_decode(Cookie::get('member')));
        }
        if(!check_array($token)){
            message('请先登录','/home/auth/login.html','error');
        }
        $member = Member::getUserInfoById($token['uid']);
        if(empty($member)){
            message('会员信息不存在','/home/auth/login.html','error');
        }
        return $member;
    }

    /**
     * 得到数据分页
     * @param  string $modelName 模型名称
     * @param  array  $where     分页条件
     * @return array
     */
    protected function getPagination($modelName, $pageSize, $where, $order, $fields) {
        $service = Loader::model($modelName, 'service');
        // 总数据行数
        $total = $service->getCount($where);

        // 得到分页数据
        $lists = $service->getPagination($where,
                                        $fields,
                                        $order,
                                        $pageSize,
                                        $total);
        
        $result = [];
        $result['lists'] = $lists;
        $result['total'] = $total;
        $result['page'] = $lists->render();
        $result['page_count'] = ceil($total / $pageSize);
        return $result;
    }

    protected function taskProcessFile($key) {
        $file = request()->file('processFile' . $key);
        if (!$file || !is_object($file)) {
            return "";
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if(!$info){
            Db::rollback();
            message($file->getError(),'','error');
        }
        $record = [
            'uid' => $this->member['uid'],
            'extension' => $info->getExtension(),
            'save_name' => str_replace('\\','/',$info->getSaveName()),
            'filename' => $info->getFilename(),
            'md5' => $info->hash('md5'),
            'sha1' => $info->hash('sha1'),
            'size' => $info->getSize(),
            'create_time' => TIMESTAMP
        ];

        //数据库存入失败记录日志
        if(!Uploads::addInfo($record)){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
        }

        return $record['save_name'];
    }

    protected function get_domain() {
        /* 协议 */
        $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        else
        {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT']))
            {
                $port = ':' . $_SERVER['SERVER_PORT'];

                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
                {
                    $port = '';
                }
            }
            else
            {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME']))
            {
                $host = $_SERVER['SERVER_NAME'] . $port;
            }
            elseif (isset($_SERVER['SERVER_ADDR']))
            {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }

        return $protocol . $host . dirname(rtrim($_SERVER['SCRIPT_NAME'], '/'));
    }

}
<?php
namespace app\home\controller;
use app\admin\model\TaskCategory;

/**
 * Class Index
 * @package app\home\controller
 * 首页控制器
 */
class Index extends Base{

    public function index(){

        //所有分类
        $categories = TaskCategory::getList();
        //获取今日所有任务个人所得积分和金额
        list($today_credit1,$today_credit2) = \app\home\model\Task::getTodayTotalCredit();

        //获取最新的轮播图
        $banners = \app\home\model\Banner::getList();

        //获取最新的公告
        $notices = \app\admin\model\Notice::getLastList(10);

        //获取最新的轮播图
        $withdraw_where = ['status' => 1];
        $withdraws = \app\admin\model\Withdraw::getPagination($withdraw_where, 10, 10, "id DESC");

        $withdrawsCallback = function ($item, $key) {
            $username = $item['username'];
            if (strlen($username) >= 11) {
                $item['username'] = substr($username, 0, 3) . "*****" . substr($username, -3);
            }
            return $item;
        };

        $withdraws->each($withdrawsCallback);

        //任务列表
        $params = request()->request();
        if(check_array($params)){
            array_trim($params);
        }
        $pszie = 15;
        $tasks = \app\home\model\Task::getListByParams($params,$pszie);
        if(request()->isAjax()){
            if(empty($tasks)){
                message('没有更多任务','','error');
            }
            message($tasks,'','success');
        }
        $count = \app\home\model\Task::getCountByParams($params);
        $pageCount = ceil($count/$pszie);

        return $this->fetch(__FUNCTION__,[
            'categories' => $categories,
            'banners' => $banners,
            'notices' => $notices,
            'withdraws' => $withdraws,
            'today_credit1' => $today_credit1,
            'today_credit2' => $today_credit2,
            'tasks' => $tasks,
            'pageCount' => $pageCount
        ]);
    }

}

<?php
namespace app\home\controller;

use think\Db;
use think\Log;
use app\home\model\Follow;

class Fans extends Base{

	public function __construct(){
		parent::__construct();

		$this->member = $this->checkLogin();
	}

    public function index(){
        $data = [
            'member' => $this->member,
            'fans' => Follow::getFansCount($this->member['uid']),
            'follows' => Follow::getFollowsCount($this->member['uid']),
        ];

        $where = ['follows.follow_uid' => $this->member['uid']];
        $pszie = 15;

        $count = $data['fans'];
        $list = Follow::getPagination($where, $pszie, $count, "id DESC");
        if(request()->isAjax()){
            if(empty($list)){
                message('没有更多信息','','error');
            }

            $html = $this->fetch('_list', [
                'list' => $list
            ]);
            message($html,'','success');
        }
        $pageCount = ceil($count/$pszie);

        $data['list'] = $list;
        $data['items'] = $list->items();
        $data['count'] = $count;
        $data['pageCount'] = $pageCount;

        return $this->fetch(__FUNCTION__, $data);
    }

    public function follow(){
        $data = [
            'member' => $this->member,
            'fans' => Follow::getFansCount($this->member['uid']),
            'follows' => Follow::getFollowsCount($this->member['uid']),
        ];

        $where = ['follows.uid' => $this->member['uid']];
        $pszie = 15;

        $count = $data['follows'];
        $list = Follow::getPagination($where, $pszie, $count, "id DESC");
        if(request()->isAjax()){
            if(empty($list)){
                message('没有更多信息','','error');
            }

            $html = $this->fetch('_list', [
                'list' => $list
            ]);
            message($html,'','success');
        }
        $pageCount = ceil($count/$pszie);

        $data['list'] = $list;
        $data['items'] = $list->items();
        $data['count'] = $count;
        $data['pageCount'] = $pageCount;

        return $this->fetch(__FUNCTION__, $data);
    }

    public function followUser() {
        $user_id = floor(trim(params('user_id')));
        if(!check_id($user_id)){
            message('关注用户错误','','error');
        }

        if ($user_id == $this->member['uid']) {
            message("不能关注自己！",'','error');
        }

        $isFollow = Follow::getIsFollow($this->member['uid'], $user_id);
        if ($isFollow) {
            message("您已关注过该用户！",'','error');
        }

        Db::startTrans();
        $status = Follow::addInfo([
            'uid' => $this->member['uid'],
            'follow_uid' => $user_id,
            'create_time' => TIMESTAMP,
            'update_time' => TIMESTAMP
        ]);
        if(!$status){
            Db::rollback();
            message('关注失败','','error');
        }
        Db::commit();
        
        message("关注成功",'reload','success');
    }

    public function unFollowUser() {
        $user_id = floor(trim(params('user_id')));
        if(!check_id($user_id)){
            message('关注用户错误','','error');
        }

        if ($user_id == $this->member['uid']) {
            message("不能关注自己！",'','error');
        }

        $followInfo = Follow::getFollowInfo($this->member['uid'], $user_id);
        if (!$followInfo) {
            message("您还未关注过该用户！",'','error');
        }

        Db::startTrans();
        $status = Follow::deleteInfoById($followInfo['id']);
        if(!$status){
            Db::rollback();
            message('取消关注失败','','error');
        }
        Db::commit();
        
        message("取消关注成功",'reload','success');
    }

}
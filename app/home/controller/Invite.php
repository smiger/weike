<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 19:34
 */

namespace app\home\controller;

class Invite extends Base{

	public function __construct(){
		parent::__construct();

		$this->member = $this->checkLogin();
	}

    public function index(){
        $money = \app\admin\model\InvitationRebateRecord::getTotalMoney($this->member['uid']);

        return $this->fetch(__FUNCTION__, [
            'money' => $money,
            'domain' => $this->get_domain()
        ]);
    }

    public function silver(){
        return $this->fetch(__FUNCTION__);
    }

    public function silver_ajax() {
        $where = ['uid' => $this->member['uid']];
        $list = \app\admin\model\Invitation::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }

    public function gold(){
        return $this->fetch(__FUNCTION__);
    }

    public function gold_ajax() {
        $where = ['uid' => $this->member['uid']];
        $list = \app\admin\model\InvitationRebateRecord::getPagination($where, 15, NULL, "id DESC");

        to_json(0, '', $list);
    }
}
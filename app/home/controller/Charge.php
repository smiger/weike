<?php
namespace app\home\controller;

class Charge extends Base {

    public function index() {
        $member = $this->checkLogin();

        $this->assign('credit2', $member['credit2']);

        return $this->fetch(__FUNCTION__);
    }

    public function ajax() {
        $member = $this->checkLogin();

        $where = ['uid' => $member['uid'], 'type' => 'credit2'];
        $result = $this->getPagination('Charge', 10, $where, "id DESC", "*");

        to_json(0, '', $result['lists']);
    }

}
<?php
namespace app\home\controller;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\home\model\Member;
use app\home\model\Sign;
use think\Db;
use think\Log;

class Signin extends Base
{
	public function index(){
		$member = $this->checkLogin();
		$is_sign = Sign::getTodayInfoByUid($member['uid']);
		$signs = Sign::getCurMonthAllByUid($member['uid']);

		$myday = array();
		foreach($signs as $value) {
			$myday[] = strtotime($value['create_time']);
		}

		//查询昨天是否有签到
		$yesterdaySign = Sign::getYesterdayInfoByUid($member['uid']);
		if (!$yesterdaySign && !$is_sign) {
			$member['sign_continue'] = 0;
		}

		return $this->fetch(__FUNCTION__, [
			'member' => $member,
			'is_sign' => !empty($is_sign)?1:0,
			'signs' => $signs,
			'myday' => json_encode($myday)
		]);
	}

	public function post() {
		$member = $this->checkLogin();
		if(request()->isAjax()){
			$is_sign = Sign::getTodayInfoByUid($member['uid']);
			if(!empty($is_sign)){
				message('今日已签到','','error');
			}
			$config = Config::getInfo();
			$sign_credit1 = 0;
			if(!empty($config['setting']['sign_give_credit1'])){
				$sign_credit1 = floatval(trim($config['setting']['sign_give_credit1']));
			}
			if($sign_credit1 <= 0){
				message('平台未开启签到送积分','','error');
			}
			$sign_continue_give = 0;
			if(!empty($config['setting']['sign_continue_give'])){
				$sign_continue_give = floatval(trim($config['setting']['sign_continue_give']));
			}

			Db::startTrans();
			$status1 = Member::updateCreditById($member['uid'], $sign_credit1, 0);
            if(!$status1){
				Db::rollback();
				message('签到失败：-1','','error');
			}

			//查询本月都还没有签到，将连接签到重置为0
			$signs = Sign::getCurMonthCountByUid($member['uid']);
			if ($signs == 0) {
				$member['sign_continue'] = 0;
			}

			//查询昨天是否有签到
			$yesterdaySign = Sign::getYesterdayInfoByUid($member['uid']);
			if ($yesterdaySign) {
				$member['sign_continue'] = intval($member['sign_continue']) + 1;
			} else {
				$member['sign_continue'] = 1;
			}

			$status2= Member::updateInfoById($member['uid'], [
				'sign_continue' => $member['sign_continue']
			]);
			if(!$status2){
				Db::rollback();
				message('签到失败：-2','','error');
			}

			$status2 = Sign::addInfo([
				'uid' => $member['uid'],
				'credit1' => $sign_credit1
			]);
			if(!$status2){
				Db::rollback();
				message('签到失败：-3','','error');
			}
			$status3 = CreditRecord::addInfo([
				'uid' => $member['uid'],
				'type' => 'credit1',
				'num' => $sign_credit1,
				'title' => '签到',
				'remark' => "签到成功，获得{$sign_credit1}积分。"
			]);
			if(!$status3){
				Db::rollback();
				message('签到失败：-4','','error');
			}

			if ($sign_continue_give > 0 && $member['sign_continue'] > 0) {
				$sign_continue_credit1 = $member['sign_continue'] * $sign_continue_give;

				$status4 = Member::updateCreditById($member['uid'], $sign_continue_credit1, 0);
	            if(!$status4){
					Db::rollback();
					message('签到失败：-5','','error');
				}

				$status5 = CreditRecord::addInfo([
					'uid' => $member['uid'],
					'type' => 'credit1',
					'num' => $sign_continue_credit1,
					'title' => '签到',
					'remark' => "您已连续" . $member['sign_continue'] . "天签到，额外获得{$sign_continue_credit1}积分。"
				]);
				if(!$status5){
					Db::rollback();
					message('签到失败：-6','','error');
				}

				$sign_credit1 += $sign_continue_credit1;
			}

			Db::commit();

			message("签到成功，+{$sign_credit1}积分",'reload','success');
		}
	}
}
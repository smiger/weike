<?php
namespace app\home\controller;

use app\home\model\Recharge as UserRecharge;
use think\Db;
use think\Log;

class Recharge extends Base
{
    public function index(){
        $member = $this->checkLogin();
        return $this->fetch(__FUNCTION__);
    }

    public function post() {
        $member = $this->checkLogin();

        $params = request()->post();
        $result = $this->validate($params,'Recharge');
        if($result !== true){
            message($result,'','error');
        }

        //处理是整数的
        //params_floor(['credit2'], $params);

        if($params['credit2'] < 0){
            message('充值金额不能少于0元','','error');
        }

        $params['thumbs'] = '';

        Db::startTrans();
        $status3 = UserRecharge::addInfo([
            'uid' => $member['uid'],
            'username' => $member['username'],
            'credit2' => $params['credit2'],
            'realname' => $params['realname'],
            'account' => $params['account'],
            'pay_time' => '',
            'thumbs' => $params['thumbs'],
            'create_time' => TIMESTAMP
        ]);
        if(!$status3){
            Db::rollback();
            message('提现失败：-3','','error');
        }

        $payparams = array(
                    'subject' => '充值-' . $params['credit2'],
                    'out_trade_no' => $status3,
                    'total_amount' => $params['credit2'],
                    'domain' => $this->get_domain()
                );

        if ($params['pay_type'] == 1) {
            $payparams['goods_tag'] = '';
            $payparams['notify_url'] = $this->get_domain() . 'home/wxnotify/index.html';
            $payparams['wap_url'] = $this->get_domain() . '/home/account.html';
            $payparams['wap_name'] = '充值-' . $params['credit2'];

            $h5pay = new \wxpay\H5pay();
            $result = $h5pay->pay($payparams);

            Db::commit();

            $this->redirect($result["mweb_url"] . "&redirect_url=" . urlencode($payparams['wap_url']));

        } elseif ($params['pay_type'] == 2) {
            $wappay = new \alipay\Wappay();
            $result = $wappay->pay($payparams);
            echo $result;

            Db::commit();
        } else {
            message('提现失败','','error');
        }
    }
}
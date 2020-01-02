<?php

namespace app\home\controller;

use think\Controller;

use app\home\model\Recharge as UserRecharge;
use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

/**
 * 通知处理控制器
 */
class Wxnotify extends Controller
{
    public function index()
    {
        $notify = new \wxpay\Notify();
        $notify->check($this);
    }

    /**
     * 业务处理
     * @return Boolean true表示业务处理成功 false表示处理失败
     */
    public function handle($data)
    {
        Db::startTrans();

        $out_trade_no = $data['out_trade_no'];

        Log::info(__FILE__.':'.__LINE__.' Data: '. $out_trade_no);

        $recharge = UserRecharge::getInfoById($out_trade_no);
        if (!$recharge) {
            return false;
        }

        Log::info(__FILE__.':'.__LINE__.' recharge: '. $recharge['id']);

        $update = [];
        $update['status'] = 1;
        $update['note'] = '';
        $update['pay_time'] = date('Y-m-d H:i:s', TIMESTAMP);
        $update['update_time'] = TIMESTAMP;
        $status = UserRecharge::updateInfoById($recharge['id'], $update);
        if(!$status){
            Db::rollback();
            return false;
        }

        Log::info(__FILE__.':'.__LINE__.' UserRecharge::updateInfoById: '. $status);

        $credit2 = $recharge['credit2'];
        $status1 = Member::updateCreditById($recharge['uid'], 0, $credit2);
        if(!$status1){
            Db::rollback();
            return false;
        }

        Log::info(__FILE__.':'.__LINE__.' Member::updateCreditById: '. $status1);

        $status2 = CreditRecord::addInfo([
            'uid' => $recharge['uid'],
            'type' => 'credit2',
            'num' => $credit2,
            'title' => '会员充值',
            'remark' => "支付宝充值金额：{$credit2}。",
            'create_time' => TIMESTAMP
        ]);
        if(!$status2){
            Db::rollback();
            return false;
        }

        Log::info(__FILE__.':'.__LINE__.' CreditRecord::addInfo: '. $status2);

        Db::commit();

        return true;
    }
}
<?php

namespace app\home\controller;

use app\common\controller\NotifyHandler;

use app\home\model\Recharge as UserRecharge;
use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

/**
* 通知处理控制器
*
* 完善getOrder, 获取订单信息, 注意必须数组必须包含out_trade_no与total_amount
* 完善checkOrderStatus, 返回订单状态, 按要求返回布尔值
* 完善handle, 进行业务处理, 按要求返回布尔值
*
* 请求地址为index, NotifyHandler会自动调用以上三个方法
*/
class Notify extends NotifyHandler
{
    protected $params; // 订单信息

    public function index()
    {
        parent::init();
    }

    /**
     * 获取订单信息, 必须包含订单号和订单金额
     *
     * @return string $params['out_trade_no'] 商户订单
     * @return float  $params['total_amount'] 订单金额
     */
    public function getOrder()
    {
        $out_trade_no = $_POST['out_trade_no'];

        Log::error(__FILE__.':'.__LINE__.' Data: '. $out_trade_no);

        $recharge = UserRecharge::getInfoById($out_trade_no);
        $params = [
            'out_trade_no' => $recharge['id'],
            'total_amount' => $recharge['credit2'],
            'status'       => $recharge['status'],
            'credit2'      => $recharge['credit2'],
            'uid'          => $recharge['uid'],
            'id'           => $recharge['id']
        ];
        $this->params = $params;
    }

    /**
     * 检查订单状态
     *
     * @return Boolean true表示已经处理过 false表示未处理过
     */
    public function checkOrderStatus()
    {
        if($this->params['status'] == 0) {
            // 表示未处理
            return false;
        } else {
            return true;
        }
    }

    /**
     * 业务处理
     * @return Boolean true表示业务处理成功 false表示处理失败
     */
    public function handle()
    {

        Db::startTrans();

        $update = [];
        $update['status'] = 1;
        $update['note'] = '';
        $update['pay_time'] = date('Y-m-d H:i:s', TIMESTAMP);
        $update['update_time'] = TIMESTAMP;
        $status = UserRecharge::updateInfoById($this->params['id'], $update);
        if(!$status){
            Db::rollback();
            return false;
        }

        $credit2 = $this->params['credit2'];
        $status1 = Member::updateCreditById($this->params['uid'], 0, $credit2);
        if(!$status1){
            Db::rollback();
            return false;
        }
        $status2 = CreditRecord::addInfo([
            'uid' => $this->params['uid'],
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

        Db::commit();

        return true;
    }
}
<?php

namespace wxpay;

use think\Loader;

Loader::import('wxpay/lib/WxPay', EXTEND_PATH, '.Api.php');
Loader::import('wxpay/WxPay', EXTEND_PATH, '.NativePay.php');

class H5pay
{
    /**
     * 主入口
     * @param array  $params 支付参数, 具体如下
     * @param string $params['subject'] 订单标题
     * @param string $params['out_trade_no'] 订单商户号
     * @param float  $params['total_amount'] 订单金额
     */
    public static function pay($params)
    {
        // 1.校检参数
        self::checkParams($params);

        /**
         * 流程：
         * 1、调用统一下单，取得mweb_url，通过mweb_url调起微信支付中间页
         * 2、用户在微信支付收银台完成支付或取消支付
         * 3、支付完成之后，微信服务器会通知支付成功
         * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
         */
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($params['subject']);
        $input->SetAttach("");
        $input->SetOut_trade_no($params['out_trade_no']);
        $input->SetTotal_fee($params['total_amount'] * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($params['goods_tag']);
        $input->SetNotify_url($params['notify_url']);
        $input->SetTrade_type("MWEB");
        $input->SetScene_info('{"h5_info": {"type":"Wap", "wap_url": "' . $params['wap_url'] . '", "wap_name": "' . $params['wap_name'] . '"}}');
        $notify = new \NativePay();
        $result = $notify->GetH5PayUrl($input);
        if (!$result) {
            message('支付失败，请联系客服', '/', 'error');
        }

        if (isset($result['return_code']) && $result['return_code'] != 'SUCCESS') {
            message($result['return_msg'], '/', 'error');
        }

        return $result;
    }

    /**
     * 校检参数
     */
    private static function checkParams($params)
    {
        if (empty(trim($params['out_trade_no']))) {
            self::processError('商户订单号(out_trade_no)必填');
        }

        if (empty(trim($params['subject']))) {
            self::processError('商品标题(subject)必填');
        }

        if (floatval(trim($params['total_amount'])) <= 0) {
            self::processError('金额(total_amount)为大于0的数');
        }
    }

    /**
     * 统一错误处理接口
     * @param  string $msg 错误描述
     */
    private static function processError($msg)
    {
        message($msg, '/', 'error');
    }
}
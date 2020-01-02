<?php

namespace alipay;

use think\Loader;

Loader::import('alipay.aop.AopClient');
Loader::import('alipay.aop.SignData');
Loader::import('alipay.aop.request.AlipayTradeWapPayRequest');

class Wappay
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

        $bizContent = array(
            'body' => $params['subject'],
            'subject' => $params['subject'],
            'out_trade_no' => $params['out_trade_no'],
            'timeout_express' => '1m',
            'total_amount' => $params['total_amount'],
            'product_code' => 'QUICK_WAP_PAY',
        );

        $config = config('alipay');

        $aop = new \AopClient;
        $aop->gatewayUrl = $config['gatewayUrl'];
        $aop->appId = $config['app_id'];
        $aop->rsaPrivateKey = $config['merchant_private_key'];
        $aop->format = "json";
        $aop->charset = $config['charset'];
        $aop->signType = $config['sign_type'];
        $aop->alipayrsaPublicKey = $config['alipay_public_key'];
        //实例化具体API对应的request类,类名称和接口名称对应
        $request = new \AlipayTradeWapPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = json_encode($bizContent);
        $request->setReturnUrl($params['domain'] . $config['return_url']);
        $request->setNotifyUrl($params['domain'] . $config['notify_url']);
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->pageExecute($request);
        return $response;
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
        throw new \think\Exception($msg);
    }
}
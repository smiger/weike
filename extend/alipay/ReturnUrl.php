<?php

namespace alipay;

use think\Loader;
use think\Log;

Loader::import('alipay.aop.AopClient');

/**
* 支付同步跳转处理类
*
* 用法:
* 调用 \alipay\ReturnUrl::check($params) 即可
*
*/
class ReturnUrl
{
    /**
     * 异步通知校检, 包括验签和数据库信息与通知信息对比
     *
     * @param array  $params 数据库中查询到的订单信息
     * @param string $params['out_trade_no'] 商户订单
     * @param float  $params['total_amount'] 订单金额
     */
    public static function check($params)
    {
        // 1.第一步校检签名
        $config = config('alipay');

        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = $config['alipay_public_key'];
        //此处验签方式必须与下单时的签名方式一致
        foreach ($_GET as $k => $v) {
            $_GET[$k] = stripslashes($v);
        }

        Log::error(__FILE__.':'.__LINE__.' Data: '.var_export($_GET, true));

        $signResult = $aop->rsaCheckV1($_GET, NULL, $config['sign_type']);

        // 2.和数据库信息做对比
        $paramsResult = self::checkParams($params);

        // 3.返回结果
        if($signResult && $paramsResult) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断两个数组是否一致, 两个数组的参数如下：
     * $params['out_trade_no'] 商户单号
     * $params['total_amount'] 订单金额
     * $params['app_id']       app_id号
     */
    public static function checkParams($params)
    {
        $notifyArr = [
            'out_trade_no' => $_GET['out_trade_no'],
            'total_amount' => $_GET['total_amount'],
            'app_id'       => $_GET['app_id'],
        ];
        $paramsArr = [
            'out_trade_no' => $params['out_trade_no'],
            'total_amount' => $params['total_amount'],
            'app_id'       => config('alipay.app_id'),
        ];
        $result = array_diff_assoc($paramsArr, $notifyArr);
        return empty($result) ? true : false;
    }
}
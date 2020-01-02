<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/3/10
 * Time: 17:39
 */

namespace app\admin\model;


class Withdraw extends Base
{
    //当前操作表
    protected $table = 'tb_withdraw';

    /**
     * @return int|null|string
     * 获取会员提现累计总额
     */
    public static function getCredit2Total($where){
        try{
            return self::where($where)->sum("credit2");
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
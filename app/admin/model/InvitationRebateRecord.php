<?php
namespace app\admin\model;

class InvitationRebateRecord extends Base {

    //当前操作表
    protected $table = 'tb_invitation_rebate_record';

    /**
     * @return int|null|string
     * 获取累计邀请返利
     */
    public static function getTotalMoney($uid){
        try{
            return self::where(['uid' => $uid])->sum('num');
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return 0;
        }
    }

}
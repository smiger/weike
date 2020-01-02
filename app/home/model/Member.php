<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:46
 */
namespace app\home\model;
use think\Exception;
use think\Log;
use think\Model;
use think\Db;

class Member extends Base{

    //当前操作表
    protected $table = 'tb_member';

    /**
     * @param $uid
     * @param $data
     * @return $this|bool|null
     * 根据ID修改信息
     */
    public static function updateInfoById($uid,$data){
        if(!check_id($uid) || !check_array($data)){
            return false;
        }
        try{
            $data['uid'] = $uid;
            $data['update_time'] = TIMESTAMP;
            return self::update($data);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param string $username
     * @return null|static
     * 根据username获取会员信息
     */
    public static function getUserInfoByUsername($username = ''){
		
        if(!check_username($username)){
            return null;
        }
        try{
            return self::get(['username' => $username]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $id
     * @return null|static
     * 根据ID获取会员信息
     */
    public static function getUserInfoById($id){
        if(!check_id($id)){
            return null;
        }
        try{
            return self::get(['uid' => $id]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $id
     * @param $credit1
     * @param $credit2
     * @return $this|bool|null
     * 根据ID修改余额和积分
     */
    public static function updateCreditById($id, $credit1, $credit2){
        if(!check_id($id)){
            return false;
        }
        try{
            $where = [
                'uid' => $id
            ];

            if ($credit1 < 0) {
                $where['credit1'] = ['>=', abs($credit1)];
            }

            if ($credit2 < 0) {
                $where['credit2'] = ['>=', abs($credit2)];
            }

            $result = Db::table('tb_member')
            ->where($where)
            ->inc('credit1', $credit1)
            ->inc('credit2', $credit2)
            ->exp('update_time', TIMESTAMP)
            ->update();
            //echo Db::getLastSql();exit;
            return $result > 0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function updateInviteInfo($id, $invite_total = 1, $invite_rebate = 0){
        if(!check_id($id)){
            return false;
        }
        try{
            $result = Db::table('tb_member')
            ->where('uid', $id)
            ->inc('invite_total', $invite_total)
            ->inc('invite_rebate', $invite_rebate)
            ->exp('update_time', TIMESTAMP)
            ->update();
            return $result > 0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

}
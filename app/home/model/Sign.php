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

class Sign extends Base{

    //当前操作表
    protected $table = 'tb_sign';

    /**
     * @param $uid
     * @return null|static
     * 获取今日会员签到信息
     */
    public static function getTodayInfoByUid($uid){
        if(!check_id($uid)){
            return null;
        }
        try{
            return self::where(['uid' => $uid,'create_time' => ['between time',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]]])->find();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $uid
     * @return array|static
     * 获取当前月会员签到信息
     */
    public static function getCurMonthAllByUid($uid){
        if(!check_id($uid)){
            return [];
        }
        try{
            $MonthFirstDay = date('Y-m-1 00:00:00');

            $where = [
                'uid' => $uid,
                'create_time' => [
                    'between time',
                    [
                        $MonthFirstDay,
                        date('Y-m-d 23:59:59', strtotime($MonthFirstDay . ' +1 month -1 day'))
                    ]
                ]
            ];
            return self::where($where)->select();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return [];
        }
    }

    /**
     * @param $uid
     * @return int|static
     * 获取当前月会员签到总数
     */
    public static function getCurMonthCountByUid($uid){
        if(!check_id($uid)){
            return [];
        }
        try{
            $MonthFirstDay = date('Y-m-1 00:00:00');

            $where = [
                'uid' => $uid,
                'create_time' => [
                    'between time',
                    [
                        $MonthFirstDay,
                        date('Y-m-d 23:59:59', strtotime($MonthFirstDay . ' +1 month -1 day'))
                    ]
                ]
            ];
            return self::where($where)->count();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return [];
        }
    }

    /**
     * @param $uid
     * @return null|static
     * 获取今日会员签到信息
     */
    public static function getYesterdayInfoByUid($uid){
        if(!check_id($uid)){
            return null;
        }
        try{
            $item = self::where(['uid' => $uid])->order("id desc")->find();
            if ($item) {
                $origin = $item->getData();
                $today_start = strtotime(date('Y-m-d 00:00:00') . ' -1 day');
                $today_end = strtotime(date('Y-m-d 23:59:59') . ' -1 day');

                if ($origin['create_time'] >= $today_start && $origin['create_time'] <= $today_end) {
                    return $item;
                }
            }
            
            return null;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
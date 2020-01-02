<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:46
 */
namespace app\admin\model;
use think\Exception;
use think\Log;

class Notice extends Base{

    //当前操作表
    protected $table = 'tb_notice';

    /**
     * @param int $psize
     * @param array $where
     * @return false|null|\PDOStatement|string|\think\Collection
     * 获取列表
     */
    public static function getList($psize = 15,$where = []){
        try{
            $psize = parent::paginateSize($psize);
            return self::where($where)->order('order_by desc')->paginate($psize, false, parent::paginateParam());
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param int $count
     * @return $this|null
     * 获取最新的$count条数据
     */
    public static function getLastList($count = 10){
        try{
            return self::where(['is_display' => 1])->order('order_by desc,id desc')->limit($count)->select();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
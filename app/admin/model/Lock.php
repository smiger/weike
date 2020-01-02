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

class Lock extends Base{

    //当前操作表
    protected $table = 'tb_lock';

    /**
     * @param int $psize
     * @param array $where
     * @return false|null|\PDOStatement|string|\think\Collection
     * 获取列表
     */
    public static function getList($psize = 15,$where = []){
        try{
            $psize = parent::paginateSize($psize);
            return self::where($where)->order('id desc')->paginate($psize, false, parent::paginateParam());
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
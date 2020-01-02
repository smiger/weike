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

class Uploads extends Base{

    //当前操作表
    protected $table = 'tb_uploads';

    /**
     * @param array $where
     * @return bool|false|\PDOStatement|string|\think\Collection
     * 分页获取所有文件信息
     */
    public static function getList($where = []){
        try{
            $psize = parent::paginateSize(15);
            return self::where($where)->order('id desc')->paginate($psize, false, parent::paginateParam());
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return false;
        }
    }
}
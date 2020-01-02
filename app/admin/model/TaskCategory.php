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

class TaskCategory extends Base{

    //当前操作表
    protected $table = 'tb_task_category';

    /**
     * @param int $page
     * @param int $psize
     * @return false|null|\PDOStatement|string|\think\Collection
     * 获取分类列表
     */
    public static function getList($page = 0,$psize = 100){
        try{
            return self::order('order_by desc')->page($page,$psize)->select();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param int $page
     * @param int $psize
     * @return false|null|\PDOStatement|string|\think\Collection
     * 获取分类列表
     */
    public static function getListByKey($page = 0,$psize = 100){
        try{
            $new = [];
            $lists = self::order('order_by desc')->page($page,$psize)->select();
            if ($lists) {
                foreach ($lists as $key => $value) {
                    $new[$value->id] = $value;
                }
            }
            return $new;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
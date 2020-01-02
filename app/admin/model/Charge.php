<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/3/10
 * Time: 17:20
 */

namespace app\admin\model;


use think\Exception;
use think\Log;

class Charge extends Base
{
    //当前操作表
    protected $table = 'tb_charge';


    /**
     * @param array $params
     * @return null|\think\Paginator
     * 获取列表
     */
    public static function getList($params = []){
        try{
            $where = [];
            if(!empty($params['keyword']) && check_id($params['keyword'])){
                $where['uid'] = $params['keyword'];
            }
            $psize = parent::paginateSize(15);
            return self::where($where)->order('id desc')->paginate($psize, false, parent::paginateParam());
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param array $params
     * @return int|null|string
     * 获取数目
     */
    public static function getTotal($params = []){
        try{
            $where = [];
            if(!empty($params['keyword']) && check_id($params['keyword'])){
                $where['uid'] = $params['keyword'];
            }
            return self::where($where)->count();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

}
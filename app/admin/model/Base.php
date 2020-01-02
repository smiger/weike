<?php
/**
 * Created by PhpStorm.
 * User: 俊俊de小嘉琪
 * Date: 2018/2/14
 * Time: 11:39
 */

namespace app\admin\model;


use think\Exception;
use think\Log;
use think\Model;

/**
 * Class Base
 * @package app\admin\model
 * 公共的处理方法
 */
class Base extends Model
{
    /**
     * @param $ids
     * @return bool|int|null
     * 批量删除数据
     */
    public static function deleteByIds($ids){
        if(empty($ids) || !is_array($ids)){
            return false;
        }
        try{
            return self::where(['id'=>['in',$ids]])->delete();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $id
     * @return bool|\think\Paginator
     * 删除记录
     */
    public static function deleteInfoById($id){
        if(!check_id($id)){
            return false;
        }
        try{
            return self::where(['id'=>$id])->delete();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return null|static
     * 根据id获取信息
     */
    public static function getInfoById($id){
        if(!check_id($id)){
            return null;
        }
        try{
            return self::get(['id' => $id]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $id
     * @return null|static
     * 根据id获取信息
     */
    public static function getInfoByIds($ids){
        try{
            return self::where(['uid'=>['in',$ids]])->select();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return [];
        }
    }

    /**
     * @param array $data
     * @return bool|false|int|null
     * 新增数据
     */
    public static function addInfo($data){
        if(!check_array($data)){
            return false;
        }
        try{
            $data['create_time'] = TIMESTAMP;
            return self::insertGetId($data);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @param $data
     * @return $this|bool|null
     * 根据ID修改信息
     */
    public static function updateInfoById($id,$data){
        if(!check_id($id) || !check_array($data)){
            return false;
        }
        try{
            $data['id'] = $id;
            $data['update_time'] = TIMESTAMP;
            return self::update($data);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * 根据ID获取数据
     * @param  int $id
     * @return array
     */
    public static function getById($id) {
        return self::get(['id' => $id]);
    }

    /**
     * 得到数据行数
     * @param  array $where
     * @return int
     */
    public static function getCount(array $where, $filter = null) {
        // 条件查找
        $M = self::where($where);

        if($filter && is_array($filter)) {
            $flag = false;

            if(isset($filter['_string']) && $filter['_string'])
            {
                foreach ($filter['_string'] as $value)
                {
                    $M = $M->where($value);
                }
                $flag = true;
            }

            if(isset($filter['_array']) && $filter['_array'])
            {
                $M = $M->where($filter['_array']);
                $flag = true;
            }

            if(isset($filter['_like']) && $filter['_like'])
            {
                $M = $M->where($filter['_like']);
                $flag = true;
            }

            if(!$flag && $filter)
            {
                $M = $M->where($filter);
            }
        }

        $count = $M->count();
        //echo $M->getLastSql();
        //exit;

        return $count;
    }

    /**
     * 得到分页数据
     * @param  array $where    分页条件
     * @param  int   $pageSize 行数
     * @param  int   $listRows 总记录数
     * @return array
     */
    public static function getPagination($where, $pageSize, $total = null, $order = null, $fields = null, $filter = null) {
        // 条件查找
        $M = self::where($where);

        // 需要查找的字段
        if (isset($fields)) {
            $M = $M->field($fields);
        }

        if($filter && is_array($filter)) {
            $flag = false;

            if(isset($filter['_string']) && $filter['_string'])
            {
                foreach ($filter['_string'] as $value)
                {
                    $M = $M->where($value);
                }
                $flag = true;
            }

            if(isset($filter['_array']) && $filter['_array'])
            {
                $M = $M->where($filter['_array']);
                $flag = true;
            }

            if(isset($filter['_like']) && $filter['_like'])
            {
                $M = $M->where($filter['_like']);
                $flag = true;
            }

            if(!$flag && $filter)
            {
                $M = $M->where($filter);
            }
        }

        // 数据排序
        if (isset($order)) {
            $M = $M->order($order);
        }

        $pageSize = self::paginateSize($pageSize);

        // 查询限制
        if (isset($pageSize) && isset($total)) {
            return $M->paginate($pageSize, $total, self::paginateParam());
        }

        return $M->paginate($pageSize, false, self::paginateParam());
    }

    /**
     * 得到所有数据
     * @param  array $where    分页条件
     * @return array
     */
    public static function getAll($where, $order = null, $fields = null, $filter = null) {
        // 条件查找
        $M = self::where($where);

        // 需要查找的字段
        if (isset($fields)) {
            $M = $M->field($fields);
        }

        if($filter && is_array($filter)) {
            $flag = false;

            if(isset($filter['_string']) && $filter['_string'])
            {
                foreach ($filter['_string'] as $value)
                {
                    $M = $M->where($value);
                }
                $flag = true;
            }

            if(isset($filter['_array']) && $filter['_array'])
            {
                $M = $M->where($filter['_array']);
                $flag = true;
            }

            if(isset($filter['_like']) && $filter['_like'])
            {
                $M = $M->where($filter['_like']);
                $flag = true;
            }

            if(!$flag && $filter)
            {
                $M = $M->where($filter);
            }
        }

        // 数据排序
        if (isset($order)) {
            $M = $M->order($order);
        }

        return $M->select();
    }

    public static function paginateParam() {
        $param = request()->request();
        if ($param) {
            foreach ($param as $key => $value) {
                if ($value === "") {
                    unset($param[$key]);
                }
            }
        }

        return ['query' => $param];
    }

    public static function paginateSize($pageSize) {
        return request()->param('pageSize', $pageSize);
    }

}
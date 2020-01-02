<?php
/**
 * Created by PhpStorm.
 * User: 俊俊de小嘉琪
 * Date: 2018/2/15
 * Time: 10:22
 */

namespace app\api\model;


use think\Exception;
use think\Log;
use think\Model;

class Base extends Model
{
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
     * @param $id
     * @return null|static
     * 根据ID获取会员信息
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

}
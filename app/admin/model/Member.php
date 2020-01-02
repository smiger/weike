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
use think\Db;

class Member extends Base{

    //当前操作表
    protected $table = 'tb_member';

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
            return self::where(['uid'=>['in',$ids]])->update(['is_del' => 1, 'update_time' => TIMESTAMP]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param array $params
     * @return null
     * 获取会员信息列表
     */
    public static function getList($params = []){
        try{
            $where = ['is_del' => 0];
            if(check_array($params)){
                if(!empty($params['keyword'])){
                    $where['uid|username|mobile'] = ['like',"%{$params['keyword']}%"];
                }
                if(!empty($params['is_check'])){
                    $where['is_check'] = ['in',$params['is_check']];
                }
            }
            $psize = parent::paginateSize(15);
            return self::where($where)->order('uid desc')->paginate($psize, false, parent::paginateParam());
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $username
     * @return null|static
     * 根据id获取信息
     */
    public static function getInfoByName($username){
        if(empty($username)){
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
     * 根据id获取信息
     */
    public static function getInfoById($id){
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
     * @param $data
     * @return $this|bool|null
     * 根据ID修改信息
     */
    public static function updateInfoById($id,$data){
        if(!check_id($id) || !check_array($data)){
            return false;
        }
        try{
            $data['uid'] = $id;
            $data['update_time'] = TIMESTAMP;
            return self::update($data);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @return int|null|string
     * 获取会员数量
     */
    public static function getTotal(){
        try{
            return self::where(['is_del' => 0])->count();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @return int|null|string
     * 获取会员余额累计总额
     */
    public static function getCredit2Total(){
        try{
            return self::where(['is_del' => 0])->sum("credit2");
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
            $result = Db::table('tb_member')
            ->where('uid', $id)
            ->inc('credit1', $credit1)
            ->inc('credit2', $credit2)
            ->exp('update_time', TIMESTAMP)
            ->update();
            return $result > 0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
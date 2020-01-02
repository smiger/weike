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

class Administrator extends Base{

    //当前操作表
    protected $table = 'tb_administrator';

    /**
     * @param string $username
     * @return null|static
     * 根据username获取用户信息
     */
    public static function getInfoByUsername($username = ''){
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
}
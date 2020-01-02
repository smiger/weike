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

class Config extends Base{

    //当前操作表
    protected $table = 'tb_config';

    /**
     * @return null|static
     * 获取配置信息
     */
    public static function getInfo(){
        try{
            $config = self::find();
            if(!empty($config['setting'])){
                $config['setting'] = unserialize($config['setting']);
            }
            return $config;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
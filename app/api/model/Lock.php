<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/4/2
 * Time: 下午1:08
 */

namespace app\api\model;

use think\Exception;
use think\Log;

class Lock extends Base
{
    protected $table = "tb_lock";

    /**
     * @param string $domain
     * @return null|static
     * 根据域名获取授权信息
     */
    public static function getByDomain($domain = ''){
        try{
            return self::get(['domain' => $domain]);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }
}
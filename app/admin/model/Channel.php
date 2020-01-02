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

class Channel extends Base{

    //当前操作表
    protected $table = 'tb_channel';

    /**
     * @param array $params
     * @return null|\think\Paginator
     * 获取列表信息
     */
    public static function getList($params = [])
    {
        try {
            $psize = parent::paginateSize(15);
            return self::order('order_by desc')->paginate($psize, false, parent::paginateParam());
        } catch (Exception $e) {
            Log::error(__FILE__ . ':' . __LINE__ . ' 错误：' . $e->getMessage());
            return null;
        }
    }
}
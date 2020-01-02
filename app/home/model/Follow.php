<?php
namespace app\home\model;

use think\Db;

class Follow extends Base{

    //当前操作表
    protected $table = 'tb_follows';

    /**
     * @param int $uid
     * @return int|null
     * 获取用户粉丝数
     */
    public static function getFansCount($uid){
        try{
            $where = "follow_uid=" . $uid;
            $info = self::query("SELECT COUNT(1) AS num FROM tb_follows WHERE {$where}");
            return !empty($info[0]['num'])?$info[0]['num']:0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return 0;
        }
    }

    /**
     * @param int $uid
     * @return int|null
     * 获取用户关注数
     */
    public static function getFollowsCount($uid){
        try{
            $where = "uid=" . $uid;
            $info = self::query("SELECT COUNT(1) AS num FROM tb_follows WHERE {$where}");
            return !empty($info[0]['num'])?$info[0]['num']:0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return 0;
        }
    }

    /**
     * @param int $uid
     * @return int|null
     * 获取用户是否关注指定用户
     */
    public static function getIsFollow($uid, $follow_uid){
        try{
            $where = "uid=" . $uid . " AND follow_uid=" . $follow_uid;
            $info = self::query("SELECT COUNT(1) AS num FROM tb_follows WHERE {$where}");
            return !empty($info[0]['num'])?$info[0]['num']>0:false;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return false;
        }
    }

    /**
     * @param int $uid
     * @return int|null
     * 获取用户关注指定用户数据
     */
    public static function getFollowInfo($uid, $follow_uid){
        try{
            $where = ["uid" => $uid, "follow_uid" => $follow_uid];
            return self::get($where);
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return array();
        }
    }

    /**
     * 得到分页数据
     * @param  array $where    分页条件
     * @param  int   $pageSize 行数
     * @param  int   $listRows 总记录数
     * @return array
     */
    public static function getPagination($where, $pageSize, $total = null, $order = null, $fields = null, $filter = null) {
        $M = Db::table('tb_follows')
        ->alias('follows')
        ->join('tb_member member', 'follows.follow_uid = member.uid')
        ->field(['follows.*', 'member.uid', 'member.username', 'member.level', 'member.avatar'])
        ->where($where);

        // 数据排序
        if (isset($order)) {
            $M = $M->order($order);
        }

        $pageSize = self::paginateSize($pageSize);

        // 查询限制
        if (isset($pageSize) && isset($total)) {
            $list = $M->paginate($pageSize, $total, self::paginateParam());
        } else {
            $list = $M->paginate($pageSize, false, self::paginateParam());
        }

        $itemsCallback = function ($item, $key) {
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['update_time'] = date('Y-m-d H:i', $item['update_time']);
            return $item;
        };

        $list->each($itemsCallback);

        return $list;
    }
}
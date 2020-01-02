<?php
namespace app\api\model;

use think\Exception;
use think\Log;
use think\Db;

class Task extends Base
{
    protected $table = "tb_task";

    public static function updateTaskReceiveOrderOutTime($ids) {
        $result = Db::table('tb_task')
        ->where('id', 'in', $ids)
        ->inc('join_num', -1)
        ->exp('is_complete', 0)
        ->exp('update_time', TIMESTAMP)
        ->update();
        return $result > 0;
    }

    public static function getTopOutTimeList($limit) {
        $where = ['top_end_time' => ['>', 0], 'top_end_time' => ['<=', TIMESTAMP]];
        return self::where($where)->order("id")->limit($limit)->select();
    }

    public static function cancelTopState($ids) {
        $data = [];
        $data['top_end_time'] = 0;
        return self::where('id', 'in', $ids)->update($data);
    }

    public static function getRecommendOutTimeList($limit) {
        $where = ['top_end_time' => ['>', 0], 'top_end_time' => ['<=', TIMESTAMP]];
        return self::where($where)->order("id")->limit($limit)->select();
    }

    public static function getTaskDueTimeList($limit) {
        $where = ['is_display' => 1, 'is_complete' => 0, 'end_time' => ['<', TIMESTAMP]];
        return self::where($where)->order("id")->limit($limit)->select();
    }
}
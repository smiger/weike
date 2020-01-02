<?php
namespace app\api\model;

use think\Exception;
use think\Log;
use think\Db;

class TaskJoin extends Base
{
    protected $table = "tb_task_join";

    /**
     * @param int $limit
     * @return null|static
     * 查询接单超时订单
     */
    public static function getOutTimeList($receive_order_limit_time, $limit) {
        $outtime = TIMESTAMP - $receive_order_limit_time * 60;
        $where = ['delflag' => 1, 'status' => 1, 'create_time' => ['<=', $outtime]];
        return self::where($where)->order("id")->limit($limit)->select();
    }

    public static function delReceiveOrderOutTime($ids) {
        $data = [];
        $data['delflag'] = 2;
        $data['update_time'] = TIMESTAMP;
        return self::where('id', 'in', $ids)->update($data);
    }

    public static function getCheckOutTimeList($check_order_limit_time, $limit) {
        $outtime = TIMESTAMP - $check_order_limit_time * 60 * 60;
        $where = ['delflag' => 1, 'status' => 2, 'create_time' => ['<=', $outtime]];
        return self::where($where)->order("id")->limit($limit)->select();
    }

    public static function getDoneCountByTaskId($task_id) {
        $where = ['task_id' => $task_id, 'status' => 3];
        return self::where($where)->count();
    }

    public static function getListByTaskId($task_id) {
        $where = ['task_id' => $task_id, 'status' => 2];
        return self::where($where)->order("id")->select();
    }

    public static function updateDoneStatus($task_id) {
        Db::table('tb_task_join')
        ->where('task_id', $task_id)
        ->where('status', '<>', 3)
        ->exp('status', 4)
        ->exp('update_time', TIMESTAMP)
        ->update();
        return true;
    }
}
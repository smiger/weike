<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:46
 */
namespace app\home\model;

use think\Exception;
use think\Log;
use think\Db;

class TaskJoin extends Base{

    //当前操作表
    protected $table = 'tb_task_join';
    
    /**
     * @param $uid
     * @return int|null|string
     * 根据会员的发布的任务数
     */
    public static function getTotalCountByMemberId($uid){
        if(!check_id($uid)){
            return 0;
        }
        try{
            //return self::where(['uid' => $uid, 'delflag' => 1])->count();
            $where = "task_join.uid=" . $uid;
            $where .= " AND task_join.delflag=1 AND task.is_display=1 ";
            $sql = "SELECT COUNT(*) AS num FROM tb_task_join task_join INNER JOIN tb_task task ON task_join.task_id=task.id WHERE {$where}";
            $info = self::query($sql);
            return !empty($info[0]['num'])?$info[0]['num']:0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param $uid
     * @return int|null|string
     * 根据任务id获取待审核数
     */
    public static function getAuditNumById($id) {
        if(!check_id($id)){
            return 0;
        }
        try{
            return self::where(['task_id' => $id, 'status' => 2, 'delflag' => 1])->count();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return 0;
        }
    }

    /**
     * @param $uid
     * @return int|null|string
     * 根据任务id获取已完成数
     */
    public static function getPassNumById($id) {
        if(!check_id($id)){
            return 0;
        }
        try{
            return self::where(['task_id' => $id, 'status' => 3, 'delflag' => 1])->count();
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return 0;
        }
    }

    /**
     * @param $task_id
     * @param $uid
     * @return bool|int|null|string
     * 根据用户ID和任务ID获取任务接单详情
     */
    public static function getInfoByTaskIdAndUid($task_id, $uid){
        if(!check_id($task_id) || !check_id($uid)){
            return null;
        }
        try{
            return self::get(['task_id' => $task_id, 'uid' => $uid, 'delflag' => 1]);
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
            return null;
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

    public static function getTaskPassJoin($where, $pageSize) {
        $where['task_join.status'] = 3;
        $where['task_join.delflag'] = 1;

        $list = Db::table('tb_task_join')
        ->alias('task_join')
        ->join('tb_member member', 'task_join.uid = member.uid')
        ->field(['task_join.*', 'member.uid', 'member.username', 'member.level', 'member.avatar'])
        ->where($where)
        ->paginate($pageSize);

        $itemsCallback = function ($item, $key) {
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['update_time'] = date('Y-m-d H:i', $item['update_time']);
            $item['audit_time'] = date('Y-m-d H:i', $item['audit_time']);
            unset($item['thumbs']);
            return $item;
        };

        $list->each($itemsCallback);

        return $list;
    }

    public static function getJoinNumByTaskId($task_id) {
        $where = ['task_id' => $task_id, 'delflag' => 1, 'status' => ['in', [1, 2, 3]]];
        return self::where($where)->count();
    }

}
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

class MyTaskAudit extends Base {

    //当前操作表
    protected $table = 'tb_task_join';

    private static function _get_where($params = [], $is_count = false) {
        $where = "task_join.task_id=" . (isset($params['task_id']) ? intval($params['task_id']) : -1);
        $where .= " AND task_join.delflag=1 AND task.is_display=1 ";
        if(check_array($params)){
            if(!empty($params['category_type'])) {
                switch ($params['category_type']){
                    case "lock":
                        //已抢单
                        $where .= " AND task_join.status=1 ";
                        break;
                    case "wait":
                        //待审核
                        $where .= " AND task_join.status>=2 ";
                        break;
                    case "pass":
                        //通过
                        $where .= " AND task_join.status=3 ";
                        break;
                    case "nopass":
                        //不通过
                        $where .= " AND task_join.status=4 ";
                        break;
                    default:
                        //全部
                }
            }
        }
        if (!$is_count) {
            $where .= " ORDER BY task_join.update_time DESC";
        }
        return $where;
    }

    /**
     * @param array $params
     * @param int $psize
     * @return mixed|null
     * 获取首页任务列表
     */
    public static function getListByParams($params = [], $psize = 15) {
        //try{
            $page = get_now_page();
            $pindex = ($page-1)*$psize;
            $where = self::_get_where($params);
            $sql = "SELECT task.*, task_join.id as join_id, task_join.uid as join_uid, task_join.status as join_status, task_join.create_time as join_create_time, task_join.audit_time as join_audit_time FROM tb_task_join task_join INNER JOIN tb_task task ON task_join.task_id=task.id WHERE {$where} LIMIT {$pindex},{$psize}";
            $tasks = self::query($sql);
            if(!empty($tasks)){
                $no = $pindex + 1;
                foreach ($tasks as &$v){
                    $v['no'] = $no++;
                    $v['percent'] = round(floatval($v['join_num']/$v['ticket_num']*100),2);
                    $v['end_time'] = date('Y-m-d H:i',$v['end_time']);
                    $v['join_create_time'] = date('Y-m-d H:i',$v['join_create_time']);
                    $v['join_audit_time'] = date('Y-m-d H:i',$v['join_audit_time']);
                }
            }
            return $tasks;
        /*}catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }*/
    }

    /**
     * @param array $params
     * @param int $psize
     * @return mixed|null
     * 获取任务数目
     */
    public static function getCountByParams($params = [],$psize = 15){
        try{
            $where = self::_get_where($params, true);
            $sql = "SELECT COUNT(*) AS num FROM tb_task_join task_join INNER JOIN tb_task task ON task_join.task_id=task.id WHERE {$where}";
            $info = self::query($sql);
            return !empty($info[0]['num'])?$info[0]['num']:0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

}
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

class MyTask extends Base{

    //当前操作表
    protected $table = 'tb_task';

    /**
     * @param $id
     * @return bool|int|null|string
     * 根据ID获取任务详情
     */
    public static function getInfoById($id){
        if(!check_id($id)){
            return null;
        }
        try{
            $list = self::query("SELECT a.*,b.username,b.avatar,b.level FROM tb_task a LEFT JOIN tb_member b ON a.uid=b.uid WHERE a.id={$id}");
            if(!empty($list[0])){
                $list[0]['percent'] = round(floatval($list[0]['join_num']/$list[0]['ticket_num']*100),2);
                $list[0]['end_time'] = date('Y-m-d H:i',$list[0]['end_time']);
                if(!empty($list[0]['thumbs'])){
                    $list[0]['thumbs'] = unserialize($list[0]['thumbs']);
                }
                return $list[0];
            }
            return null;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    /**
     * @param array $params
     * @param int $psize
     * @return mixed|null
     * 获取首页任务列表
     */
    public static function getListByParams($params = [], $psize = 15){
        //try{
            $page = get_now_page();
            $pindex = ($page-1)*$psize;
            $where = "uid=" . (isset($params['uid']) ? intval($params['uid']) : 0);
            if (isset($params['category_type']) && $params['category_type'] != 'pass') {
                $where .= " AND is_complete=0 ";
            }
            //$where .= " AND is_display=1 ";//AND check_period*3600+create_time<".TIMESTAMP;
            if(check_array($params)){
                if(!empty($params['category_type'])) {
                    switch ($params['category_type']){
                        case "wait":
                            //待审核
                            $where .= " AND is_display=0 ";
                            break;
                        case "ing":
                            //进行中
                            $where .= " AND is_display=1 ";
                            $where .= " AND start_time<" . TIMESTAMP;
                            $where .= " AND end_time>" . TIMESTAMP;
                            break;
                        case "pass":
                            //已完成
                            $where .= " AND is_complete=1 ";
                            break;
                        case "past":
                            //已失效
                            $where .= " AND end_time<" . TIMESTAMP;
                            break;
                        case "audit":
                            //审核的
                            $where .= " AND join_num>0 ";
                            break;
                        default:
                            //全部
                    }
                }

                if(!empty($params['search_type']) && $params['search_type'] == 1){
                    //今日任务
                    $t_start_time = strtotime(date('Y-m-d 00:00:00'));
                    $t_end_time = strtotime(date('Y-m-d 23:59:59'));
                    $where .= " AND end_time BETWEEN {$t_start_time} AND {$t_end_time}";
                }
                if(!empty($params['keyword'])){
                    $where .= " AND title LIKE '%{$params['keyword']}%'";
                }
                if(!empty($params['category_id'])){
                    $where .= " AND category_id='{$params['category_id']}'";
                }
                if(!empty($params['order_type'])){
                    switch ($params['order_type']){
                        case 1:$where.=" ORDER BY give_credit2 DESC";break;//金额数
                        case 2:$where.=" ORDER BY give_credit1 DESC";break;//积分数
                        case 3:$where.=" ORDER BY join_num/ticket_num DESC";break;//进度
                        case 4:;break;//等级
                        default:$where .= " ORDER BY order_by DESC,end_time-".TIMESTAMP." ASC";//哪一个任务快结束了，哪一个在前面

                    }
                } else {
                    $where .= " ORDER BY id DESC";
                }
            }
            $sql = "SELECT * FROM tb_task WHERE {$where} LIMIT {$pindex},{$psize}";
            $tasks = self::query($sql);
            if(!empty($tasks)){
                foreach ($tasks as &$v){
                    $v['category_type'] = "";

                    if ($v['end_time'] < TIMESTAMP) {
                        $v['category_type'] = "past";
                    } else if ($v['is_complete'] == 1) {
                        $v['category_type'] = "complete";
                    } else if ($v['is_display'] == 0) {
                        $v['category_type'] = "wait";
                    } else if ($v['is_display'] == -1) {
                        $v['category_type'] = "nopass";
                    } else if ($v['start_time'] < TIMESTAMP && $v['end_time'] > TIMESTAMP) {
                        $v['category_type'] = "ing";
                    }

                    $v['percent'] = round(floatval($v['join_num']/$v['ticket_num']*100),2);
                    $v['end_time'] = date('Y-m-d H:i',$v['end_time']);
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
    public static function getCountByParams($params = []){
        try{
            $where = "uid=" . (isset($params['uid']) ? intval($params['uid']) : 0);
            if (isset($params['category_type']) && $params['category_type'] != 'pass') {
                $where .= " AND is_complete=0 ";
            }
            //$where .= " AND is_display=1 ";//AND check_period*3600+create_time<".TIMESTAMP;
            if(check_array($params)){
                if(!empty($params['category_type'])) {
                    switch ($params['category_type']){
                        case "wait":
                            //待审核
                            $where .= " AND is_display=0 ";
                            break;
                        case "ing":
                            //进行中
                            $where .= " AND start_time<" . TIMESTAMP;
                            $where .= " AND end_time>" . TIMESTAMP;
                            break;
                        case "pass":
                            //已完成
                            $where .= " AND is_complete=1 ";
                            break;
                        case "past":
                            //已失效
                            $where .= " AND end_time<" . TIMESTAMP;
                            break;
                        case "audit":
                            //审核的
                            $where .= " AND join_num>0 ";
                            break;
                        default:
                            //全部
                    }
                }
                if(!empty($params['search_type']) && $params['search_type'] == 1){
                    //今日任务
                    $t_start_time = strtotime(date('Y-m-d 00:00:00'));
                    $t_end_time = strtotime(date('Y-m-d 23:59:59'));
                    $where .= " AND end_time BETWEEN {$t_start_time} AND {$t_end_time}";
                }
                if(!empty($params['keyword'])){
                    $where .= " AND title LIKE '%{$params['keyword']}%'";
                }
                if(!empty($params['category_id'])){
                    $where .= " AND category_id='{$params['category_id']}'";
                }
            }
            $info = self::query("SELECT COUNT(1) AS num FROM tb_task WHERE {$where}");
            return !empty($info[0]['num'])?$info[0]['num']:0;
        }catch (Exception $e){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            return null;
        }
    }

    public static function delById($id) {
        return self::destroy($id);
    } 

}
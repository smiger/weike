<?php
namespace app\api\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\admin\model\Config;
use think\Db;
use think\Log;

/*
 * 用户抢单后5分钟内未上传验证，抢单将自己删除
 */
class TaskJoinTimer extends Command
{
    protected function configure()
    {
        $this->setName('delreceiveorderouttime')->setDescription('每分钟执行接单限时超时处理');
    }

    // 每分钟执行接单限时超时处理
    // php think delreceiveorderouttime
    protected function execute(Input $input, Output $output)
    {
        $setting = [];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['receive_order_limit_time'])){
                $setting['receive_order_limit_time'] = intval($setting['receive_order_limit_time']);
            }
        }

        if (!$setting['receive_order_limit_time'] || $setting['receive_order_limit_time'] <= 0) {
            exit('后台接单限时配置无效');
        }

        try {
            $task_joins = \app\api\model\TaskJoin::getOutTimeList($setting['receive_order_limit_time'], 50);

            $ids = [];
            $task_ids = [];
            foreach ($task_joins as $value) {
                $ids[] = $value['id'];
                $task_ids[] = $value['task_id'];
            }

            if (!empty($ids) && !empty($task_ids)) {
                Db::startTrans();

                $status1 = \app\api\model\TaskJoin::delReceiveOrderOutTime($ids);
                if(!$status1){
                    Db::rollback();
                    Log::error(__FILE__.':'.__LINE__.' 错误：delReceiveOrderOutTime执行失败');
                    $output->writeln('delReceiveOrderOutTime执行失败');
                }

                $status2 = \app\api\model\Task::updateTaskReceiveOrderOutTime($task_ids);
                if(!$status2){
                    Db::rollback();
                    Log::error(__FILE__.':'.__LINE__.' 错误：updateTaskReceiveOrderOutTime执行失败');
                    $output->writeln('updateTaskReceiveOrderOutTime执行失败');
                }

                Db::commit();
            }

            Log::info(__FILE__.':'.__LINE__.' 每分钟执行接单限时超时处理');
            $output->writeln('每分钟执行接单限时超时处理');

        } catch (Exception $e) {
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            $output->writeln(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
        }
    }
}
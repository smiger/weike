<?php
namespace app\api\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\admin\model\Config;
use think\Db;
use think\Log;

class CheckTopState extends Command
{
    protected function configure()
    {
        $this->setName('checktopstate')->setDescription('每分钟执行置顶有效期检查');
    }
    
    // 每分钟执行置顶有效期检查
    // php think checktopstate
    protected function execute(Input $input, Output $output)
    {
        try {
            $tasks = \app\api\model\Task::getTopOutTimeList(50);

            $ids = [];
            foreach ($tasks as $value) {
                $ids[] = $value['id'];
            }

            if (!empty($ids)) {
                Db::startTrans();

                $status1 = \app\api\model\Task::cancelTopState($ids);
                /*if(!$status1){
                    Db::rollback();
                    Log::error(__FILE__.':'.__LINE__.' 错误：cancelTopState执行失败');
                    $output->writeln('cancelTopState执行失败');
                }*/

                Db::commit();
            }

            Log::info(__FILE__.':'.__LINE__.' 每分钟执行置顶有效期检查');
            $output->writeln('每分钟执行置顶有效期检查');

        } catch (Exception $e) {
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            $output->writeln(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
        }
    }
}
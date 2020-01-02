<?php
namespace app\api\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\home\model\Member;
use app\admin\model\InvitationRebateRecord;
use think\Db;
use think\Log;

/*
 * 任务到期了 自动结算任务
 */
class OutStockTaskDueTime extends Command
{
    protected function configure()
    {
        $this->setName('outstocktaskduetime')->setDescription('任务到期了');
    }

    // 任务到期了
    // php think outstocktaskduetime
    protected function execute(Input $input, Output $output)
    {
        $setting = ['check_order_limit_time' => 0, 'invitation_rebate' => 0];
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $setting = $config['setting'];
            if(!empty($setting['check_order_limit_time'])){
                $setting['check_order_limit_time'] = intval($setting['check_order_limit_time']);
            }
            if(!empty($setting['invitation_rebate'])){
                $setting['invitation_rebate'] = round(floatval($setting['invitation_rebate']/100),2);
            }
        }

        try {
            $taskService = \think\Loader::model("Task", 'common\service');

            $tasks = \app\api\model\Task::getTaskDueTimeList(50);

            $error = '';

            foreach ($tasks as $params) {
                $error .= $taskService->outStockTask($params, $output);
            }

            if ($error == '') {
                Log::info(__FILE__.':'.__LINE__.' 任务到期了');
                $error = " 任务到期了\n";
                $output->writeln($error);
            }

        } catch (Exception $e) {
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            $output->writeln(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
        }
    }
}
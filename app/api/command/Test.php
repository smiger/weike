<?php
namespace app\api\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\admin\model\Config;
use think\Db;
use think\Log;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')->setDescription('Here is the remark ');
    }
    protected function execute(Input $input, Output $output)
    {
        try {
            $output->writeln('每分钟执行置顶有效期检查');

        } catch (Exception $e) {
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            $output->writeln(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
        }
    }
}
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
 * 雇主发布任务后1小时内不审核任务，系统会自动审核通过，佣金进入用户帐号中
 */
class PassCheckOrderOutTime extends Command
{
    protected function configure()
    {
        $this->setName('passcheckorderouttime')->setDescription('审核前后订单');
    }

    // 审核前后订单
    // php think passcheckorderouttime
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

        if (!$setting['check_order_limit_time'] || $setting['check_order_limit_time'] <= 0) {
            Log::info(__FILE__.':'.__LINE__.' 后台审核限时配置无效');
            $output->writeln('后台审核限时配置无效');
            exit;
        }

        try {
            $task_joins = \app\api\model\TaskJoin::getCheckOutTimeList($setting['check_order_limit_time'], 50);

            $ids = [];
            foreach ($task_joins as $joinInfo) {
                $id = $joinInfo['id'];
                $ids[] = $joinInfo['id'];

                $params = \app\home\model\Task::getInfoById($joinInfo['task_id']);
                if(empty($params)){
                    \app\home\model\TaskJoin::updateInfoById($id, ['delflag' => 2]);

                    $error =  $joinInfo['task_id'] . "---任务不存在\n";
                    $output->writeln($error);
                    Log::error(__FILE__.':'.__LINE__.' ' . $error);
                    continue;
                }

                $joinMemberInfo = Member::getUserInfoById($joinInfo['uid']);
                if (!$joinMemberInfo){
                    \app\home\model\TaskJoin::updateInfoById($id, ['delflag' => 2]);

                    $error =  $joinInfo['uid'] . "---任务会员不存在\n";
                    $output->writeln($error);
                    Log::error(__FILE__.':'.__LINE__.' ' . $error);
                    continue;
                }

                $insert_task_id = $joinInfo['task_id'];

                Db::startTrans();

                $update = array(
                    'status' => 4,
                    'audit_time' => TIMESTAMP,
                    'update_time' => TIMESTAMP
                );
                $status = \app\home\model\TaskJoin::updateInfoById($id, $update);
                if(!$status){
                    Db::rollback();
                    $error = $id . "---审核失败:-1\n";
                    $output->writeln($error);
                    Log::error(__FILE__.':'.__LINE__.' ' . $error);
                    continue;
                }

                if($params['give_credit1']>0 || $params['unit_price']>0){
                    $give_credit1 = $params['give_credit1'];
                    $unit_price = $params['unit_price'];

                    //有推荐人，做完任务审核通过需要按后台设置比例分成给推荐人
                    if ($joinMemberInfo['parent_uid'] > 0 && $setting['invitation_rebate'] > 0) {
                        $parent_money = round($unit_price * $setting['invitation_rebate'], 2);
                        $unit_price -= $parent_money;

                        $status1 = Member::updateCreditById($joinMemberInfo['parent_uid'], 0, $parent_money);
                        if(!$status1){
                            Db::rollback();
                            $error = $id . "---审核失败:-2\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }

                        $status3 = CreditRecord::addInfo([
                            'uid' => $joinMemberInfo['parent_uid'],
                            'type' => 'credit2',
                            'num' => $parent_money,
                            'title' => '任务审核',
                            'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "自动审核成功，获得推荐收入{$parent_money}元。",
                            'create_time' => TIMESTAMP
                        ]);
                        if(!$status3){
                            Db::rollback();
                            $error = $id . "---审核失败:-3\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }

                        $status3 = InvitationRebateRecord::addInfo([
                            'uid' => $joinMemberInfo['parent_uid'],
                            'num' => $parent_money,
                            'task_id' => $insert_task_id,
                            'remark' => "徒弟[" . $joinMemberInfo['username'] . "]完成任务[" . $params['id'] . "]-" . $params['title'] . "，获得推荐收入{$parent_money}元。",
                            'create_time' => TIMESTAMP
                        ]);
                        if(!$status3){
                            Db::rollback();
                            $error = $id . "---审核失败:-4\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }

                        $status4 = Member::updateInviteInfo($joinMemberInfo['parent_uid'], 0, $parent_money);
                        if(!$status4){
                            Db::rollback();
                            $error = $id . "---审核失败:-5\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }
                    }

                    $taskService = \think\Loader::model("Task", 'common\service');
                    $taskService->invitationFirstTaskAward($joinMemberInfo, $params);

                    $status1 = Member::updateCreditById($joinInfo['uid'], $give_credit1, $unit_price);
                    if(!$status1){
                        Db::rollback();
                        $error = $id . "---审核失败:-8\n";
                        $output->writeln($error);
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);
                        continue;
                    }
                    //分别记录积分和余额记录
                    if($give_credit1>0){
                        $status2 = CreditRecord::addInfo([
                            'uid' => $joinInfo['uid'],
                            'type' => 'credit1',
                            'num' => $give_credit1,
                            'title' => '任务审核',
                            'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "自动审核成功，奖励{$give_credit1}积分。",
                            'create_time' => TIMESTAMP
                        ]);
                        if(!$status2){
                            Db::rollback();
                            $error = $id . "---审核失败:-6\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }
                    }
                    if($unit_price>0){
                        $status3 = CreditRecord::addInfo([
                            'uid' => $joinInfo['uid'],
                            'type' => 'credit2',
                            'num' => $unit_price,
                            'title' => '任务审核',
                            'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "自动审核成功，奖励{$unit_price}元。",
                            'create_time' => TIMESTAMP
                        ]);
                        if(!$status3){
                            Db::rollback();
                            $error = $id . "---审核失败:-7\n";
                            $output->writeln($error);
                            Log::error(__FILE__.':'.__LINE__.' ' . $error);
                            continue;
                        }
                    }
                }

                Db::commit();
            }

            Log::info(__FILE__.':'.__LINE__.' 审核前后订单');
            $output->writeln('审核前后订单');

        } catch (Exception $e) {
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
            $output->writeln(__FILE__.':'.__LINE__.' 错误：'.$e->getMessage());
        }
    }
}
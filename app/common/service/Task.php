<?php
namespace app\common\service;

use app\admin\model\Config;
use app\home\model\CreditRecord;
use app\home\model\Member;
use app\admin\model\InvitationRebateRecord;
use think\Db;
use think\Log;

/**
 * Task Service
 */
class Task extends Common {

    private $setting = [
        'invitation_rebate' => 0,
        'invitation_first_task_award' => 0
    ];

    protected function getModelName() {
        return 'Task';
    }

    private function getSetting() {
        $config = Config::getInfo();
        if(check_array($config['setting'])){
            $this->setting = $config['setting'];
            if(!empty($this->setting['invitation_rebate'])){
                $this->setting['invitation_rebate'] = round(floatval($this->setting['invitation_rebate']/100),2);
            }

            if(!empty($setting['invitation_first_task_award'])){
                $this->setting['invitation_first_task_award'] = floatval($this->setting['invitation_first_task_award']);
            }
        }
        return $this->setting;
    }

    public function outStockTask($params, Output $output = null) {
        $setting = $this->getSetting();

        $error = '';

        $isAjax = request()->isAjax();

        if(isset($params['out_stock_flag']) && $params['out_stock_flag'] == 1){
            $error = $params['id'] . "---任务已下架\n";
            if ($isAjax) {
                message("任务已下架", "", "error");
            } else {
                $output->writeln($error);
            }
            return;
        }

        Db::startTrans();

        $update = [];
        $update['is_complete'] = 1;

        $task_joins = \app\api\model\TaskJoin::getListByTaskId($params['id']);

        foreach ($task_joins as $joinInfo) {
            $id = $joinInfo['id'];
            $joinMemberInfo = Member::getUserInfoById($joinInfo['uid']);
            if (!$joinMemberInfo){
                $error =  $joinInfo['uid'] . "---任务会员不存在\n";
                Log::error(__FILE__.':'.__LINE__.' ' . $error);

                if ($isAjax) {
                    message("任务会员不存在", "", "error");
                } else {
                    $output->writeln($error);
                }
                continue;
            }

            $insert_task_id = $joinInfo['task_id'];
            $insert_task_title = $params['title'];

            $update1 = array(
                'status' => 4,
                'audit_time' => TIMESTAMP,
                'update_time' => TIMESTAMP
            );
            $status = \app\home\model\TaskJoin::updateInfoById($id, $update1);
            if(!$status){
                Db::rollback();
                $error = $id . "---下架失败:-1\n";
                Log::error(__FILE__.':'.__LINE__.' ' . $error);

                if ($isAjax) {
                    message("下架失败:-1", "", "error");
                } else {
                    $output->writeln($error);
                }
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
                        $error = $id . "---下架失败:-2\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-2", "", "error");
                        } else {
                            $output->writeln($error);
                        }
                        continue;
                    }

                    $status3 = CreditRecord::addInfo([
                        'uid' => $joinMemberInfo['parent_uid'],
                        'type' => 'credit2',
                        'num' => $parent_money,
                        'title' => '任务到期',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "到期自动审核，获得推荐收入{$parent_money}元。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        $error = $id . "---下架失败:-3\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-3", "", "error");
                        } else {
                            $output->writeln($error);
                        }
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
                        $error = $id . "---下架失败:-4\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-4", "", "error");
                        } else {
                            $output->writeln($error);
                        }
                        continue;
                    }

                    $status4 = Member::updateInviteInfo($joinMemberInfo['parent_uid'], 0, $parent_money);
                    if(!$status4){
                        Db::rollback();
                        $error = $id . "---下架失败:-5\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-5", "", "error");
                        } else {
                            $output->writeln($error);
                        }
                        continue;
                    }
                }

                $this->invitationFirstTaskAward($joinMemberInfo, $params);

                $status1 = Member::updateCreditById($joinInfo['uid'], $give_credit1, $unit_price);
                if(!$status1){
                    Db::rollback();
                    $error = $id . "---下架失败:-6\n";
                    Log::error(__FILE__.':'.__LINE__.' ' . $error);

                    if ($isAjax) {
                        message("下架失败:-6", "", "error");
                    } else {
                        $output->writeln($error);
                    }
                    continue;
                }
                //分别记录积分和余额记录
                if($give_credit1>0){
                    $status2 = CreditRecord::addInfo([
                        'uid' => $joinInfo['uid'],
                        'type' => 'credit1',
                        'num' => $give_credit1,
                        'title' => '任务到期',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "到期自动审核，奖励{$give_credit1}积分。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status2){
                        Db::rollback();
                        $error = $id . "---下架失败:-7\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-7", "", "error");
                        } else {
                            $output->writeln($error);
                        }
                        continue;
                    }
                }
                if($unit_price>0){
                    $status3 = CreditRecord::addInfo([
                        'uid' => $joinInfo['uid'],
                        'type' => 'credit2',
                        'num' => $unit_price,
                        'title' => '任务到期',
                        'remark' => "任务[{$insert_task_id}]-" . $params['title'] . "到期自动审核，奖励{$unit_price}元。",
                        'create_time' => TIMESTAMP
                    ]);
                    if(!$status3){
                        Db::rollback();
                        $error = $id . "---下架失败:-8\n";
                        Log::error(__FILE__.':'.__LINE__.' ' . $error);

                        if ($isAjax) {
                            message("下架失败:-8", "", "error");
                        } else {
                            $output->writeln($error);
                        }
                        continue;
                    }
                }
            }
        }

        //即时查询已完成任务人数
        $join_done_num = \app\api\model\TaskJoin::getDoneCountByTaskId($params['id']);
        $update['join_num'] = $join_done_num;

        //计算需要退还雇主的费用
        //任务总金额
        $task_total_money = $params['ticket_num'] * $params['unit_price'];
        //任务已完的总金额
        $join_done_money = $join_done_num * $params['unit_price'];
        //实际需要退还给雇主的金额
        $refund_money = $task_total_money - $join_done_money;
        if ($refund_money > 0) {
            $status1 = Member::updateCreditById($params['uid'], 0, $refund_money);
            if(!$status1){
                Db::rollback();
                $error = $id . "---下架失败:-9\n";
                Log::error(__FILE__.':'.__LINE__.' ' . $error);

                if ($isAjax) {
                    message("下架失败:-9", "", "error");
                } else {
                    $output->writeln($error);
                }
                continue;
            }
            $status3 = CreditRecord::addInfo([
                'uid' => $params['uid'],
                'type' => 'credit2',
                'num' => $refund_money,
                'title' => '任务到期',
                'remark' => "任务[" . $params['id'] . "]-" . $params['title'] . "到期处理，退回{$refund_money}元。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status3){
                Db::rollback();
                $error = $id . "---下架失败:-10\n";
                Log::error(__FILE__.':'.__LINE__.' ' . $error);

                if ($isAjax) {
                    message("下架失败:-10", "", "error");
                } else {
                    $output->writeln($error);
                }
                continue;
            }
        }

        $status = \app\api\model\TaskJoin::updateDoneStatus($params['id']);
        if(!$status){
            Db::rollback();
            $error = $id . "---下架失败:-11\n";
            Log::error(__FILE__.':'.__LINE__.' ' . $error);

            if ($isAjax) {
                message("下架失败:-11", "", "error");
            } else {
                $output->writeln($error);
            }
            continue;
        }

        $update['out_stock_flag'] = 1;
        $update['out_stock_time'] = TIMESTAMP;

        $status = \app\home\model\Task::updateInfoById($params['id'], $update);
        if(!$status){
            Db::rollback();
            $error = $id . "---下架失败:-12\n";
            Log::error(__FILE__.':'.__LINE__.' ' . $error);

            if ($isAjax) {
                message("下架失败:-12", "", "error");
            } else {
                $output->writeln($error);
            }
            continue;
        }

        Db::commit();

        return $error;
    }

    public function invitationFirstTaskAward($memberInfo, $taskInfo) {
        $setting = $this->getSetting();

        if (!($memberInfo['parent_uid'] > 0 && $setting['invitation_first_task_award'] > 0)) {
            return -1;
        }

        //查询是否第一次完成任务
        $params = array();
        $params['uid'] = $memberInfo['uid'];
        $params['category_type'] = "pass";
        $count = \app\home\model\MyTaskJoin::getCountByParams($params);
        if ($count > 0) {
            return -2;
        }

        $parent_money = $setting['invitation_first_task_award'];
        $status1 = Member::updateCreditById($memberInfo['parent_uid'], 0, $parent_money);
        if(!$status1){
            Db::rollback();
            return -3;
        }

        $status3 = CreditRecord::addInfo([
            'uid' => $memberInfo['parent_uid'],
            'type' => 'credit2',
            'num' => $parent_money,
            'title' => '任务完成',
            'remark' => "徒弟[" . $memberInfo['username'] . "]完成任务[" . $taskInfo['id'] . "]-" . $taskInfo['title'] . "，获得推荐收入{$parent_money}元。",
            'create_time' => TIMESTAMP
        ]);
        if(!$status3){
            Db::rollback();
            return -4;
        }

        return 0;
    }
}

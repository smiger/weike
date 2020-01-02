<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:09
 */
namespace app\home\validate;
use app\admin\model\Config;
use app\home\model\Area;
use app\home\model\TaskCategory;

class Task extends Base {

    //验证规则
    protected $rule = [
        'title|任务标题' => 'require|max:50|min:2',
        'detail|任务详情' => 'require|max:500|min:10',
        'category_id|任务类别' => 'require|checkCategory:',
        'start_time|开始时间'=>'require|checkStartTime:',
        'end_time|结束时间'=>'require|checkEndTime:',
        'ticket_num|需要票数'=>'require|number',
        'give_credit2|奖励金额'=>'require|checkGiveCredit2:',
        'give_credit1|奖励积分'=>'require|checkGiveCredit1:',
        'check_period|审核周期'=>'require|checkPeriod:',
//        'about_url|相关地址'=>'require',
        'is_screenshot|截图设置'=>'require|in:0,1',
        'is_ip_restriction|限制IP设置'=>'require|in:0,1',
        'province|限制区域'=>'requireIf:is_ip_restriction,1|checkProvince:',
        'rate|参与频率选择'=>'require|in:0,1,2',
        'interval_hour|参与间隔小时数'=>'requireIf:rate,2|checkIntervalHour:',
        'is_limit_speed|限速设置'=>'require|in:0,1',
        'limit_ticket_num|限制票数'=>'requireIf:is_limit_speed,1|checkLimitTicketNum:'
    ];

    //验证分类是否存在
    protected function checkCategory($category_id){
        if(empty($category_id)){
            return '请选择任务类别';
        }
        if(!check_id($category_id)){
            return '任务类别选择错误';
        }
        $category = TaskCategory::getInfoById($category_id);
        if(empty($category)){
            return '任务类别不存在';
        }
        return true;
    }

    //检测开始时间
    protected function checkStartTime($date){
        if(empty($date)){
            return '请选择开始时间';
        }
        if(!is_string($date) || !check_date($date,'Y-m-d H:i')){
            return '开始时间格式错误';
        }
        return true;
    }

    //检测结束时间
    protected function checkEndTime($date){
        if(empty($date)){
            return '请选择结束时间';
        }
        if(!is_string($date) || !check_date($date,'Y-m-d H:i')){
            return '结束时间格式错误';
        }
        $start_time = trim(params('start_time'));
        if(strtotime($date) <= strtotime($start_time)){
            return '结束时间必须大于开始时间';
        }
        if(strtotime($date) <= TIMESTAMP){
            return '结束时间必须大于当前时间';
        }
        return true;
    }

    //检测奖励的金额
    protected function checkGiveCredit2($credit2){
        $credit2 = floatval($credit2);
        $category_id = floor(trim(params('category_id')));
        if(!check_id($category_id)){
            return '请选择任务类别';
        }
        $category = TaskCategory::getInfoById($category_id);
        if(empty($category)){
            return '任务类别不存在';
        }
        if($credit2 < $category['min_give_credit2']){
            return "该分类的奖励金额不能低于{$category['min_give_credit2']}元";
        }
        //此处不验证会员的余额是否足够，因为控制器中，还需要扣除余额处理，避免逻辑重复
        return true;
    }

    //检测奖励的积分
    protected function checkGiveCredit1($credit1){
        $credit1 = floatval($credit1);
        $category_id = floor(trim(params('category_id')));
        if(!check_id($category_id)){
            return '请选择任务类别';
        }
        $category = TaskCategory::getInfoById($category_id);
        if(empty($category)){
            return '任务类别不存在';
        }
        if($credit1 < $category['min_give_credit1']){
            return "该分类的奖励积分不能低于{$category['min_give_credit1']}积分";
        }
        //此处不验证会员的积分是否足够，因为控制器中，还需要扣除余额处理，避免逻辑重复
        return true;
    }

    //检测，审核周期
    protected function checkPeriod($period){
        $period_arr = [];
        $config = Config::getInfo();
        if(empty($config)){
            return '平台未进行相关设置';
        }
        if(!check_array($config['setting'])){
            return '平台未进行相关设置';
        }
        if(!empty($config['setting']['period'])){
            $period_arr = explode('#',$config['setting']['period']);
        }
        if(!check_array($period_arr)){
            return '平台未进行相关设置';
        }
        if(!in_array($period,$period_arr)){
            return '审核周期选择错误';
        }
        return true;
    }

    //检测区域
    protected function checkProvince($province){
        if(floor(trim(params('is_ip_restriction'))) != 1){
            return true;
        }
        if(!in_array($province,Area::$provinces)){
            return '地区选择错误';
        }
        return true;
    }

    //检测参与小时
    protected function checkIntervalHour($hour){
        if(floor(trim(params('rate'))) != 2){
            return true;
        }
        if(floatval($hour) <= 0){
            return '请输入参与间隔的小时数';
        }
        return true;
    }

    //检测参与票数
    protected function checkLimitTicketNum($num){
        if(floor(trim(params('is_limit_speed'))) != 1){
            return true;
        }
        if(floor($num) <= 0){
            return '请输入限制的票数';
        }
        return true;
    }

}
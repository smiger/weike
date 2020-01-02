<?php
namespace app\home\controller;

use app\home\model\CreditRecord;
use app\home\model\Member;
use think\Db;
use think\Log;

class Activity extends Base{

    public function index(){
        return $this->fetch(__FUNCTION__);
    }

    public function luckydraw(){
        return $this->fetch(__FUNCTION__);
    }

    public function spin(){
        $member = $this->checkLogin();

        $params = ['give_credit1' => 100];
        if($params['give_credit1'] > $member['credit1']){
            message('积分不足','','error');
        }

        $spin = $this->_spin();

        Db::startTrans();

        $give_credit1 = -$params['give_credit1'] + $spin['credit'];
        $status1 = Member::updateCreditById($member['uid'], $give_credit1, 0);
        if(!$status1){
            Db::rollback();
            message('抽奖失败:-1','','error');
        }

        $status2 = CreditRecord::addInfo([
            'uid' => $member['uid'],
            'type' => 'credit1',
            'num' => -$params['give_credit1'],
            'title' => '活动抽奖',
            'remark' => "使用积分抽奖，扣除{$params['give_credit1']}积分。",
            'create_time' => TIMESTAMP
        ]);
        if(!$status2){
            Db::rollback();
            message('抽奖失败:-2','','error');
        }

        if($spin['credit'] > 0){
            $status3 = CreditRecord::addInfo([
                'uid' => $member['uid'],
                'type' => 'credit1',
                'num' => $params['give_credit1'],
                'title' => '活动抽奖',
                'remark' => "成功抽到" . $spin['name'] . "，赠送{$spin['credit']}积分。",
                'create_time' => TIMESTAMP
            ]);
            if(!$status3){
                Db::rollback();
                message('抽奖失败:-3','','error');
            }
        }

        Db::commit();

        to_json(0, '', $spin);
    }

    private function _spin() {
        $prizes = [
            ['name' => '没洗手？', 'credit' => 0],
            ['name' => '200积分', 'credit' => 200],
            ['name' => '人品差？', 'credit' => 0],
            ['name' => '50积分', 'credit' => 50],
            ['name' => '运气背？', 'credit' => 0],
            ['name' => '100积分', 'credit' => 100],
        ];

        $a = 25;
        $b = 3;
        $c = 32;
        $d = 10;
        $e = 25;
        $f = 5;

        $a2 = 0;
        $b2 = 1;
        $c2 = 2;
        $d2 = 3;
        $e2 = 4;
        $f2 = 5;

        $i = 0;
        $v = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');

        for($i=0; $i<$a; $i++){  
            $result[$i] = ""+$a2;
        }   
        for($i=$a; $i<$a+$b; $i++){  
            $result[$i] = ""+$b2;
        }   
        for($i=$a+$b; $i<$a+$b+$c; $i++){  
            $result[$i] = ""+$c2;
        }
        for($i=$a+$b+$c; $i<$a+$b+$c+$d; $i++){  
            $result[$i] = ""+$d2;
        }
        for($i=$a+$b+$c+$d; $i<$a+$b+$c+$d+$e; $i++){  
            $result[$i] = ""+$e2;
        }
        for($i=$a+$b+$c+$d+$e; $i<$a+$b+$c+$d+$e+$f; $i++){  
            $result[$i] = ""+$f2;
        }

        $randmax = 99;
        $mt_rand = mt_rand(0, $randmax);
        $key = isset($result[$mt_rand]) ? intval($result[$mt_rand]) : 0;
        $prize = isset($prizes[$key]) ? $prizes[$key] : ['name' => '人品差？'];

        $key2 = $key + 6;
        $rotate = [$key, $key2];
        $prize['rotate'] = $rotate[array_rand($rotate)];

        return $prize;
    }
}
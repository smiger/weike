<?php

/**
 * @param $id
 * @return false|int
 * 检测id
 */
function check_id($id){
    return preg_match('/^[1-9][0-9]*$/', $id);
}

/**
 * @param $mobile
 * @return false|int
 * 检测手机号格式
 */
function check_mobile($mobile) {
    return preg_match('/^1[3|4|5|6|7|8|9]\d{9}$/', $mobile);
}

/**
 * @param $phone
 * @return false|int
 * 检测电话号码
 */
function check_phone($phone) {
    return preg_match ( '/^(0[0-9]{2,3}-?)?[0-9]{7,8}$/', $phone );
}

/**
 * @param $mobile_phone
 * @return bool
 * 检测是手机或者电话
 */
function check_contacts($mobile_phone) {
    return check_mobile($mobile_phone) || check_phone($mobile_phone);
}

/**
 * @param $nickname
 * @return false|int
 * 检测昵称
 */
function check_nickname($nickname){
    return preg_match ( "/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_\-\*]+$/u", $nickname );
}

/**
 * @param $username
 * @return false|int
 * 检测用户名
 */
function check_username($username){
    return preg_match ( "/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_\-\*]+$/u", $username );
}

/**
 * @param $keyword
 * @return false|int
 * 检测关键词
 */
function check_keyword($keyword){
    return preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_-]+$/u" , $keyword);
}

/**
 * @param $nickname
 * @return bool
 * 检测昵称可用性
 */
function check_nickname_available($nickname) {
    $black_list = array("慕马");
    foreach($black_list as $name) {
        if(strpos($nickname , $name) !== false) {
            return false;
        }
    }
    return true;
}

/**
 * @param $data
 * @return bool
 * 检测非空数组
 */
function check_array($data){
    return !empty($data) && is_array($data);
}

/**
 * @param $str
 * @param string $separate
 * @return false|int
 * 检测是否是数字+分割服间断,如：1#2#3
 */
function check_number_separate($str,$separate = '#'){
    return preg_match("/^(\d+)({$separate}(\d)+)*$/u",$str);
}

/**
 * @param $date
 * @param string $format
 * @return bool
 * 检测是否符合指定格式的日期字符串
 */
function check_date($date,$format = 'Y-m-d H:i:s'){
    return $date == date($format,strtotime($date));
}

/**
 * @param $email
 * @return false|int
 * 验证邮箱
 */
function check_email($email){
    return preg_match ( '/^[0-9a-zA-Z_][-_\.0-9a-zA-Z]{0,63}@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$/', $email);
}
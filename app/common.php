<?php

/**
 * @param $password
 * @param string $salt
 * @return string
 * md5根据加盐值加密密码
 */
function md5_password($password,$salt = ''){
    return md5(md5($password).md5(AUTH_KEY).md5($salt));
}

/**
 * @param $password
 * @param $sql_password
 * @param string $salt
 * @return bool
 * 检查md5加密的密码
 */
function md5_password_check($password,$sql_password,$salt = ''){
    return $sql_password == md5_password($password,$salt);
}

/**
 * @param string $message
 * @param string $redirect
 * @param string $type
 * 返回json消息
 */
function message($message = '未知错误',$redirect = '',$type = 'error'){
    if(request()-> isAjax() || strtolower(request()->controller()) == 'upload'){
        header('content-type:application/json;charset=utf8');
        exit(json_encode([
            'message' => $message,
            'redirect' => $redirect,
            'type' => $type
        ]));
    }
    tpl_mobile_message($message,$redirect);
}


/**
 * @param $length
 * @param bool|false $numeric
 * @return string
 * 生成指定长度的随机字符串并返回。
 */
function random($length, $numeric = false) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

/**
 * @param $string
 * @param $find
 * @return bool
 * 判断字符串 $string 中是否包含 $find，如果包含返回 true ，否则返回 false。
 */
function str_exists($string, $find){
    return !(strpos($string, $find) === false);
}

/**
 * @param $src
 * @param string $ext_path
 * @return mixed|string
 * 将参数转换为HTTP绝对路径并返回
 */
function to_media($src,$ext_path = '/uploads/'){
    if (empty($src)) {
        return '';
    }
    $src = strtolower($src);
    //如果已经是网络图片，返回原地址
    if (str_exists($src, 'http://') || str_exists($src, 'https://')) {
        return $src;
    }
    //默认本地uploads下的文件，转为网络地址
    return str_replace('\\','/',"{$ext_path}{$src}");
}

/**
 * @param $path
 * @return string
 * 文件完整的路径
 */
function full_uploads_file($path){
    if(empty($path)){
        return '';
    }
    return ROOT_PATH . 'public' . DS . 'uploads' . DS . $path;
}



/**
 * @param $size
 * @return string
 * 文件字节转大小 K/M
 */
function format_file_size($size){
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++){
        $size /= 1024;
    }
    return round($size, 2).$units[$i];
}

/**
 * @param string $key
 * @return mixed
 * 获取当前的页数
 */
function get_now_page($key = 'page'){
    return max(1,floor(trim(params($key,1))));
}

/**
 * @param $key
 * @param string $if_not_exist
 * @return mixed
 * 获取当前参数的值
 */
function params($key,$if_not_exist = ""){
    return request()->param($key,$if_not_exist);
}

/**
 * @param $key
 * @param string $if_not_exist
 * @return mixed
 * 获取当前参数的值 数组
 */
function params_array($key,$if_not_exist = ""){
    return request()->param("{$key}/a",$if_not_exist);
}

/**
 * @param $array
 * @return array|string
 * 数组去空格
 */
function array_trim($array){
    if(!is_array($array)){
        return trim($array);
    }
    return array_map('array_trim', $array);
}


/**
 * @param $keys
 * @param $params
 * 是否的值
 */
function param_is_or_no($keys,&$params){
    if(check_array($keys) && $params){
        foreach ($keys as $key){
            if(!isset($params[$key])){
                continue;
            }
            $params[$key] = trim($params[$key]) == 1?1:0;
        }
    }
}

/**
 * @param $keys
 * @param $params
 * @param int $point
 * 返回保留小数，默认2位
 */
function params_round($keys,&$params,$point = 2){
    if(check_array($keys) && $params){
        foreach ($keys as $key){
            if(!isset($params[$key])){
                continue;
            }
            $params[$key] = round(floatval(trim($params[$key])),$point);
        }
    }
}

/**
 * @param $keys
 * @param $params
 * 去掉小数位
 */
function params_floor($keys,&$params){
    if(check_array($keys) && $params){
        foreach ($keys as $key){
            if(!isset($params[$key])){
                continue;
            }
            $params[$key] = floor(trim($params[$key]));
        }
    }
}

/**
 * @param $num
 * @param int $point
 * @return float|int
 * 舍去法格式化数字
 */
function floor_float($num,$point = 2){
    if(!is_numeric($num)){
        return $num;
    }
    return floor($num*pow(10,$point))/pow(10,$point);
}



/**
 * @param array $data
 * @param string $message
 * @param int $code
 * json格式返回数据
 */
function to_json($code = 0,$message = '访问成功',$data =[]){
    // utf-8编码
    @header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(array(
        'data' => $data,
        'message' => $message,
        'code' => $code
    )));
}

/**
 * @param $to
 * @param $title
 * @param $content
 * @param string $subject
 * @param string $language
 * @param null $attachment
 * @return bool|string
 * @throws phpmailerException
 * 系统邮件发送函数
 */
function send_email($to, $title, $content,$subject = '',$language = 'zh_cn',$attachment = null){
    $site = \app\admin\model\Config::getInfo();
    $config = !empty($site['setting'])?$site['setting']:[];
    if(!check_array($config)){
        message('请先配置邮件','','error');
    }
    vendor('PHPMailer.class#smtp');
    vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    $mail = new PHPMailer(); //PHPMailer对象
    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->setLanguage($language);
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug = 0; // 关闭SMTP调试功能
    $mail->SMTPAuth = true; // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl'; // 使用安全协议
    //$mail->SMTPSecure = false;
    $mail->Host = $config['SMTP_HOST']; // SMTP 服务器
    $mail->Port = $config['SMTP_PORT']; // SMTP服务器的端口号
    $mail->Username = $config['SMTP_USER']; // SMTP服务器用户名
    $mail->Password = $config['SMTP_PASS']; // SMTP服务器密码
    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
    $replyEmail = !empty($config['REPLY_EMAIL'])?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
    $replyName = !empty($config['REPLY_NAME'])?$config['REPLY_NAME']:$config['FROM_NAME'];
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = !empty($subject)?$subject:$title;
    $mail->MsgHTML($content);
    $mail->AddAddress($to, $title);
    if(is_array($attachment)){ // 添加附件
        foreach ($attachment as $file){
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

/**
 * @param array $params
 * @param string $auth_key
 * @return string
 * jwt加密
 */
function jwt_encode($params = [],$auth_key = ''){
    return \jwt\JWT::encode($params,empty($auth_key)?AUTH_KEY:$auth_key);
}

/**
 * @param string $jwt_str
 * @param string $auth_key
 * @return array|object
 * 解密jwt字符串
 */
function jwt_decode($jwt_str = '',$auth_key = ''){
    $result = \jwt\JWT::decode($jwt_str,empty($auth_key)?AUTH_KEY:$auth_key,array('HS256'));
    return is_object($result)?(array)$result:$result;
}

/**
 * @param $keys
 * @param $array
 * 数组中删除某个key值的数据
 */
function unset_array($keys,&$array){
    if(check_array($keys)){
        foreach ($keys as $key){
            if(isset($array[$key])){
                unset($array[$key]);
            }
        }
    }else{
        if(isset($array[$keys])){
            unset($array[$keys]);
        }
    }
}



/**
 * @param $data
 * @return bool
 * 检测给定的参数是否产生错误，并返回一个布尔值结果。
 */
function is_error($data) {
    if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
        return false;
    } else {
        return true;
    }
}


/**
 * @param $arr
 * 数组转化，在其中加key
 */
function array_trans_by_key(&$arr){
    $arr1 = array();
    if(!empty($arr)){
        foreach ($arr as $k1 => &$v1){
            $v1['key'] = $k1;
            array_push($arr1,$v1);
        }
        $arr = $arr1;
    }
}


/**
 * @param $errno
 * @param string $message
 * @return array
 * 通过参数构造并返回相应的错误数组，如果参数 $errno 为0，则表示没有任何错误。
 */
function error($errno, $message = '') {
    return array(
        'errno' => $errno,
        'message' => $message,
    );
}


/**
 * @param $mobile
 * @param array $params
 * @param int $type
 * @return array|bool
 * 发送短信,type=0代表验证码
 */
function sms_send($mobile,$params = array(),$type = 0){
    //短信限制校验
    $config = \app\admin\model\Config::getInfo();
    if(empty($config['setting']['sms']) || !is_array($config['setting']['sms'])){
        return error('-1','短信未配置，请联系客服');
    }
    $smsConfig = $config['setting']['sms'];
    if($smsConfig['status'] == 0){
        return error(-1,'短信功能暂未开启');
    }
    if(!check_mobile($mobile)){
        return error('-1','手机号格式错误');
    }
    $mobile_count = \app\api\model\SmsLog::getTodayCountByMobile($type,$mobile);
    if(!empty($smsConfig['mobile_limit']) && $mobile_count >= $smsConfig['mobile_limit']){
        return error('-1','每天最多发送5条信息:-1');
    }
    $ip_count = \app\api\model\SmsLog::getTodayCountByIp($type,request()->ip());
    if(!empty($smsConfig['ip_limit']) && $ip_count >= $smsConfig['ip_limit']){
        return error('-1','每天最多发送5条信息:-2');
    }
    $last_time = \app\api\model\SmsLog::getLastTimeByMobile($type,$mobile);
    if(!empty($smsConfig['time_range']) && (TIMESTAMP - $last_time < $smsConfig['time_range'])){
        return error('-1',"操作频繁，请{$smsConfig['time_range']}秒后重试");
    }
    //短信参数
    $random = random(8,true);
    $appid = trim($smsConfig['tencent']['appid']);
    $appkey = trim($smsConfig['tencent']['appkey']);
    $apiUrl = trim($smsConfig['tencent']['apiurl'])."?sdkappid={$appid}&random={$random}";
    $params = array(
        'tel' => array(
            'nationcode' => '86',
            'mobile' => $mobile
        ),
        'sign' => $smsConfig['tencent']['sign'],
        "tpl_id" => $smsConfig['tencent']['tpl_id'],
        "params" => $params,
        'sig' => hash("sha256", http_build_query(array(
            'appkey'=>$appkey,
            'random'=> $random,
            'time'=>TIMESTAMP,
            'mobile'=>$mobile
        ))),
        'time' => TIMESTAMP,
        'extend' => '',
        'ext' => ''
    );
    $res = (array)json_decode(simple_curl($apiUrl,$params));
    if(!check_array($res)){
        return error(-1,'请求失败');
    }
    //发送成功
    if($res['result'] == 0){
        return true;
    }
    return error(-1,$res['errmsg']);
}


//发送验证码
function  sms_send_code($mobile){
    $type = 0;//代表发送短信
    $code = random(6,true);
    $res = sms_send($mobile,array(
        $code,
        10
    ));
    if(!is_error($res)){
        //成功发送
        $status = \app\api\model\SmsLog::addInfo([
            'mobile' => $mobile,
            'content' => $code,
            'code' => $code,
            'type' => $type,
            'ip' => request()->ip()
        ]);
        if(!$status){
            return error('-1','验证码记录失败，请联系管理员');
        }
    }
    return $res;
}

//验证手机验证码
function sms_verify_code($mobile,$code){
    $config = \app\admin\model\Config::getInfo();
    if(empty($config['setting']['sms']) || !is_array($config['setting']['sms'])){
        return error('-1','短信未配置，请联系客服');
    }
    $smsConfig = $config['setting']['sms'];
    if($smsConfig['status'] == 0){
        return error(-1,'短信功能暂未开启');
    }
    if(!check_mobile($mobile)){
        return error(-1,'手机号格式错误');
    }
    if(empty($code)) {
        return error(-2,'未输入验证码');
    }
    $sql_code = \app\api\model\SmsLog::getLastTimeByMobile(0,$mobile);
    if(empty($sql_code)){
        return error(-3,'验证码信息不存在');
    }
    if(TIMESTAMP - $sql_code['create_time'] > $smsConfig['effective_time']){
        return error(-4,'验证码已失效，请重新获取');
    }
    if($code != $sql_code['code']){
        return error(-5,'验证码输入不正确');
    }
    return true;
}



/**
 * @param $url
 * @param array $params
 * @return mixed
 * 请求
 */
function simple_curl($url, $params = array()) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($curl);
    curl_close($curl);
    return $ret;
}


//友好的时间显示
function date_friend_tips($time){
    if (!$time)
        return false;
    $d = TIMESTAMP - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}


/**
 * @param $str
 * @return bool
 * 判断参数是否为加密后的字符串，并返回一个布尔值的结果。
 */
function is_base64($str){
    if(!is_string($str)){
        return false;
    }
    return $str == base64_encode(base64_decode($str));
}


/**
 * @param $base64
 * @param string $ext
 * @param int $uid
 * @param int $admin_id
 * @return bool|string
 * base64转图片
 */
function base64_upload($base64,$ext = 'jpg',$uid = 0,$admin_id = 0) {
    if(empty($base64)){
        return false;
    }
    $base64_image = str_replace(' ', '+', $base64);//post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
        $base64 = str_replace($result[1], '', $base64_image);
        $ext = $result[2];
    }
    if(!is_base64($base64)){
        return false;
    }
    $image_name = uniqid().'.'.$ext;
    $save_name = date('Ymd')."/{$image_name}";
    $image_file = ROOT_PATH . "public/uploads/{$save_name}";
    if(!is_dir(dirname($image_file))){
        make_dirs(dirname($image_file));
    }
    //服务器文件存储路径
    if (file_put_contents($image_file, base64_decode($base64))){
        $data = [
            'admin_id' => $admin_id,
            'uid' =>$uid,
            'extension' => $result[2],
            'original_name' => "base64",
            'save_name' => $save_name,
            'filename' => $image_name,
            'md5' => "",
            'sha1' => "",
            'size' => filesize($image_file),
            'create_time' => TIMESTAMP
        ];
        ;
        //数据库存入失败记录日志
        if(!\app\admin\model\Uploads::addInfo($data)){
            \think\Log::error(__FILE__.':'.__LINE__.' 错误：'.$data['save_name'].'数据库记录失败');
        }
        return $save_name;
    }
    return false;
}

/**
 * @param $path
 * @return bool
 * 递归方式创建目录
 */
function make_dirs($path) {
    if (!is_dir($path)) {
        make_dirs(dirname($path));
        mkdir($path);
    }
    return is_dir($path);
}


/**
 * @param $list
 * @param int $parent_id
 * @param int $level
 * @param string $html
 * @return array
 * 获取无限极分类标题形式
 * $list 数据列可以不用以分类ID为key
 */
function category_to_tree(&$list,$parent_id=0,$level =0,$html='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'){
    static $tree = array();
    foreach($list as $k=> $v){
        if($v['parent_id'] == $parent_id){
            if($level > 0){
                $v['title'] = str_repeat($html,$level).$v['title'];
            }
            $tree[] = $v;
            category_to_tree($list,$v['id'],$level+1);
        }
    }
    return $tree;
}



/**
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @return float
 * 根据两个经纬度算距离
 */
function location_distance($lat1, $lng1, $lat2, $lng2){
    $earthRadius = 6378137;//单位:m
    $lat1 = ($lat1 * M_PI)/180;
    $lng1 = ($lng1 * M_PI)/180;
    $lat2 = ($lat2 * M_PI)/180;
    $lng2 = ($lng2 * M_PI)/180;
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    return round($calculatedDistance);
}

/**
 * @param $lng
 * @param $lat
 * @param float $distance 单位：km
 * @return array
 * 根据传入的经纬度，和距离范围，返回所有在距离范围内的经纬度的取值范围
 */
function location_range($lng, $lat,$distance = 0.5){
    $earthRadius = 6378.137;//单位km
    $d_lng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
    $d_lng = rad2deg($d_lng);
    $d_lat = $distance/$earthRadius;
    $d_lat = rad2deg($d_lat);
    return array(
        'lat_start' => $lat - $d_lat,//纬度开始
        'lat_end' => $lat + $d_lat,//纬度结束
        'lng_start' => $lng-$d_lng,//纬度开始
        'lng_end' => $lng + $d_lng//纬度结束
    );
}
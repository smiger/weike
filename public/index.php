<?php

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

//全局加密key
define('AUTH_KEY','0DHn1sRH0pkhkEgH');

//设置当前时间戳
date_default_timezone_set('PRC');
define('TIMESTAMP',time());


// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

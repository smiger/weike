<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./views/home/mobile/profile\phone.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>绑定手机</title>
    <link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css"/>
    <link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
    <link rel="shortcut icon" href="clientapp/images/new_images/favicon.ico" />
<link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css" />
<link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
<link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css" />
<!-- 弹出层 -->
<link rel="stylesheet" href="/static/plugins/dialog/css/dialog.css" />
<script src="/static/home/mobile/js/jquery-2.0.3.min.js"></script>
<script src="/static/plugins/dialog/js/dialog.js"></script>
<!-- 弹出层 -->
<script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
<script type="text/javascript" src="/static/home/mobile/js/global.js?v=1001"></script>
</head>
<body>
<div class="site-header header-fixed">
    <a href="javascript:history.back()" class="back"></a>
    <div class="tit-name">绑定手机</div>
</div>
<div class="main bindMail">

    <div class="member-form">
        <div class="item-box">
            <input type="text" class="m_txt" placeholder="请输入手机号" id="phone"  value="<?php echo !empty($member['mobile'])?$member['mobile']:''; ?>" <?php echo !empty($member['is_bind_mobile'])?'readonly':''; ?>>
        </div>
    </div>
    <div class="member-btn">
        <input type="button" class="button1" value="确认" <?php echo !empty($member['is_bind_mobile'])?'disabled':''; ?>>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        //设置邮箱
        $('.button1').click(function () {
            var mobile = $('#phone').val();
            $.post(
                window.location.href,
                {
                    mobile:mobile
                },
                function (ret) {
                    message(ret.message,ret.redirect,ret.type);
                },'json'
            );
        });
    });
</script>
</body>
</html>
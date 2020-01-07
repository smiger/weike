<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./views/home/mobile/profile\email.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;}*/ ?>
﻿<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>用户中心-绑定邮箱</title>
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
    <link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css"/>
    <link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="site-header header-fixed">
        <a onclick="history.back()" class="back"></a>
        <div class="tit-name">绑定邮箱</div>
    </div>
    <div class="main bindMail">
        <div class="member-form">
            <div class="item-box">
                <input type="text" class="m_txt" placeholder="请输入邮箱账号" id="email" value="<?php echo !empty($member['email'])?$member['email']:''; ?>" <?php echo !empty($member['is_bind_email'])?'readonly':''; ?>>
            </div>
        </div>
        <div class="member-btn">
            <button type="button" class="button1 js-bind"  <?php echo !empty($member['is_bind_email'])?'disabled':''; ?>>确认</button>
        </div>
        <div class="member-ts">
            <label>温馨提示</label>
            <p>邮箱绑定后，不可修改，是找回密码的凭证。</p>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            //设置邮箱
            $('.js-bind').click(function () {
                var email = $('#email').val();
                $.post(
                    window.location.href,
                    {email:email},
                    function (ret) {
                        message(ret.message,ret.redirect,ret.type);
                    },'json'
                );
            });
        });
    </script>
</body>
</html>
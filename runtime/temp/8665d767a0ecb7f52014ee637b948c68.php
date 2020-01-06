<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:44:"./views/home/mobile/activity\luckydraw.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1541935408;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <title>推推推广活动</title>
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
        <link rel="shortcut icon" href="clientapp/images/new_images/favicon.ico"/>
        <link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css" />
        <link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
        <link rel="stylesheet" href="/static/home/mobile/css/new_page.css"/>
        <link rel="stylesheet" href="/static/plugins/dialog/css/dialog.css" />
        <script type="text/javascript" src="/static/home/mobile/js/jquery.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/jQueryRotate.js"></script>
        <!-- 弹出层 -->
        <script src="/static/plugins/dialog/js/dialog.js"></script>
        <!-- 弹出层 -->
    </head>

    <body>
        <header class="new-header">
            <a href="javascript:history.back()" class="back">
                <img src="/static/home/mobile/picture/return.png" />
            </a>
            <div class="tit-name">抽奖</div>
        </header>
        <div class="new-luckdraw">
            <div class="yonghu-item yonghu-item-fabu">
                <div class="fb-info" style="height:300px;">
                    <div class="rotate-con-pan">
                        <div class="rotate-con-zhen"></div>
                    </div>
                </div>
            </div>

            <div id="error" style="display:none;"></div>
        </div>
        <footer class="new-footer">
    <ul>
        <li>
            <a href="/home/index.html">
                <img <?php if($controller != 'index'): ?>class="gray"<?php endif; ?> src="/static/home/mobile/picture/home.png" />
                <span>首页</span>
            </a>
        </li>
        <li>
            <a href="/home/activity.html">
                <img  <?php if($controller != 'activity'): ?>class="gray"<?php endif; ?>  src="/static/home/mobile/picture/activity.png" />
                <span>活动</span>
            </a>
        </li>
        <li>
            <a href="/home/task/add.html">
                <span class="add-span"></span>
                <span>发布</span>
            </a>
        </li>
        <li>
            <a href="/home/invite.html">
                <img  <?php if($controller != 'invite'): ?>class="gray"<?php endif; ?>  src="/static/home/mobile/picture/news.png" />
                <span>收徒</span>
            </a>
        </li>
        <li>
            <a href="/home/account.html">
                <img <?php if($controller != 'account'): ?>class="gray"<?php endif; ?>  src="/static/home/mobile/picture/users.png" />
                <span>我的</span>
            </a>
        </li>
    </ul>
</footer>
        <!--弹出框，0824修改-->
        <div class="overlay" onClick="hideUpdateWin();"></div>
        <div class="yanzheng-box">
            <div class="yz-item" style="text-align: center;">
                <p style="color:#E84C3D;margin-bottom:8px;text-align: left;" id="price"></p>
                <input type="button" class="tongguo" value="确认" onClick="hideUpdateWin();" style="float: none;" />
            </div>
        </div>
    <script type="text/javascript">
        function hideUpdateWin() {
            $(".overlay").hide();
            $(".yanzheng-box").hide();
        }

        $(function() {

            $rotate = $(".rotate-con-zhen");
            $rotate.rotate({
                bind: {
                    click: function() {
                        Common.ApiPost('/home/activity/spin', {}, function(json){
                            if(json.code != 0){
                                message(json.message,'','error');
                                return;
                            }

                            if (json.data.credit > 0) {
                                $("#price").html("恭喜您抽中<span>" + json.data.name + "</span>奖励，已消耗100积分！");
                            } else {
                                $("#price").html("<span>" + json.data.name + "</span>继续努力，已消耗100积分！");
                            }

                            var animateTo = 1440 + 30 * json.data.rotate;

                            $rotate.rotate({
                                duration: 6000, //转动时间
                                angle: 0, //起始角度
                                animateTo: animateTo, //结束的角度
                                easing: $.easing.easeOutSine, //动画效果，需加载jquery.easing.min.js
                                callback: function() {
                                    $(".overlay").show();
                                    $(".yanzheng-box").show();
                                }
                            });
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>
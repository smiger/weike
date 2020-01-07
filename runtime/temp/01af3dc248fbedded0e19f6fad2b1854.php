<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:38:"./views/home/mobile/account\info.phtml";i:1578389120;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1578387613;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>账户信息</title>
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
	<style type="text/css">
		.maskInfo {
			position: fixed;
			left: 0;
			top: 0;
			background-color: rgba(0, 0, 0, 0.4);
			height: 100%;
			width: 100%;
			display: none;
		}
		
		.maskInfo div {
			position: absolute;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			background-color: #fff;
			height: 60px;
			width: 60%;
			line-height: 60px;
			color: #333;
			font-size: 1.6rem;
			text-align: center;
			border-radius: 3px;
		}
		
		.zhuanghu-info .info-item .noset {
			float: right;
			height: 30px;
			line-height: 30px;
			color: #fff;
			font-size: 16px;
			margin-right: 0;
			border-radius: 0;
			background: none;
			padding: 0;
		}
		
		.info-item a {
			display: block;
			overflow: hidden;
		}
	</style>

	<body>
		<div class="site-header header-fixed">
			<a onclick="history.back()" class="back"></a>
			<div class="tit-name">账号信息</div>
		</div>
		<div class="main accountInfo">
			<div class="zhuanghu-info">
				<div class="info-item">
					<i class="touxiang"></i>
					<span class="i_txt" style="margin-top:10px;">头像</span>
                    <form id="post_form" enctype="multipart/form-data">
                        <div class="t_img">
                            <img onerror="this.src='/static/home/mobile/picture/user.png'" src="<?php echo to_media($member['avatar']); ?>" id="photo" style="width:50px;height:50px;">
                            <input type="file" name="avatar" style="position: absolute;width: 100%;height: 100%;left: 0;top: 0;opacity: 0;z-index: 2;" >
                        </div>
                    </form>
				</div>
				<div class="info-item">
					<i class="username"></i>
					<span class="i_txt">用户名</span>
					<div class="i_info" id="accountName"><?php echo $member['username']; ?></div>
				</div>
			</div>
			<div class="zhuanghu-info">
				<div class="info-item info-item-emial">
					<a class="linkDress" href="/home/profile/email.html">
						<i class="email"></i>
						<span class="i_txt">绑定邮箱</span>
						<span id="email" class="more-btn"><?php if(empty($member['is_bind_email'])): ?>未绑定<?php else: ?>已绑定<?php endif; ?> &nbsp;&nbsp;<img src="/static/home/mobile/picture/ard.png"/></span>
					</a>
				</div>
<!--				<div class="info-item info-item-zfb">-->
<!--					<a class="linkDress"  href="/home/profile/alipay.html">-->
<!--						<i class="alpay"></i>-->
<!--						<span class="i_txt">绑定支付宝</span>-->
<!--						<span class="more-btn"><?php if(empty($member['is_bind_alipay'])): ?>未绑定<?php else: ?>已绑定<?php endif; ?> &nbsp;&nbsp;<img src="/static/home/mobile/picture/ard.png"/></span>-->
<!--					</a>-->
<!--				</div>-->
				<div class="info-item info-item-phone">
					<a class="linkDress" href="/home/profile/phone.html">
						<i class="phone"></i>
						<span class="i_txt">绑定手机</span>
						<span class="more-btn"><?php if(empty($member['is_bind_mobile'])): ?>未绑定<?php else: ?>已绑定<?php endif; ?> &nbsp;&nbsp;<img src="/static/home/mobile/picture/ard.png"/></span>
					</a>
				</div>
			</div>
			<div class="zhuanghu-info">
				<div class="info-item uppass" id="email2">
					<a href="/home/profile/password.html">
						<i class="upass"></i>
						<span class="i_txt">修改密码</span>
						<span class="more-btn">
							<img src="/static/home/mobile/picture/ard.png"/>
						</span>
					</a>
				</div>
<!--				<div class="info-item info-item-linkAccount">-->
<!--					<a class="linkDress" href="/home/profile/channel.html">-->
<!--						<i class="partner"></i>-->
<!--						<span class="i_txt">合作渠道登记</span>-->
<!--						<span id="accountsLink" class="more-btn">-->
<!--							<?php if(empty($member['is_bind_channel'])): ?>未登记<?php else: ?>已登记<?php endif; ?> &nbsp; &nbsp;&nbsp;<img src="/static/home/mobile/picture/ard.png"/>-->
<!--						</span>-->
<!--					</a>-->
<!--				</div>-->
			</div>
			<div class="member-btn">
				<input type="button" class="button3" value="退出账号" onclick="location.href='/home/auth/login.html'">
			</div>
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
                <span>邀请</span>
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
        <script type="text/javascript">
           $(function () {
               
               $('#accountName').click(function () {
                   message('用户名不能修改','','error');
               });
               
               $('input[name="avatar"]').bind('change',function(){
                   var formData = new FormData($( "#post_form" )[0]);
                   $.ajax({
                       url: window.location.href ,
                       type: 'POST',
                       data: formData,
                       dataType: "json",
                       async: false,
                       cache: false,
                       contentType: false,
                       processData: false,
                       success: function (ret) {
                           message(ret.message,ret.redirect,ret.type);
                       },
                       error: function () {
                          message('出现错误','','error');
                       }
                   });
               });
           });
        </script>
</body>
</html>
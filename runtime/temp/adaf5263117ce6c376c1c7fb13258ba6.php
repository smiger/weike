<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./views/home/mobile/account\index.phtml";i:1578038127;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1541935408;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>推推推广平台用户中心</title>
		<link rel="shortcut icon" href="clientapp/images/new_images/favicon.ico" />
		<link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css" />
		<link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
		<link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css" />
        <!-- 弹出层 -->
        <link rel="stylesheet" href="/static/plugins/dialog/css/dialog.css" />
        <script src="/static/plugins/dialog/lib/zepto.min.js"></script>
        <script src="/static/plugins/dialog/js/dialog.js"></script>
        <!-- 弹出层 -->
        <script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/global.js?v=1001"></script>
	</head>
	<style type="text/css">
		.site-header .tit-name {
			text-indent: -30px;
		}
		
		.site-header {
			padding-top: 20px;
		}
		
		.site-header .sc {
			top: 20px;
		}
		.site-header .member-intro{
			text-indent: 0;
		}
		.member-tx{
			margin-top: 10px;
		}
	</style>

	<body>
		<div class="site-header user-center">
			<div class="tit-name">
				<div class="member-tx">
					<a href="/home/account/info.html">
                        <img src="<?php echo to_media($member['avatar']); ?>" onerror="this.src='/static/home/mobile/picture/user.png'" style="width:94px;height:94px;">
                    </a>
				</div>
				<div id="accountName" style="font-size: 14px;"><?php echo !empty($member['nickname'])?$member['nickname']:$member['username']; ?></div>
				<div style="font-size: 14px;">编号：<?php echo $member['uid']; ?></div>
				<div class="member-intro">
					<div id="level" class="level">V1</div>
					<span id="sign" style="color:#fff;"><?php echo !empty($is_sign)?'已签到':'签到'; ?></span>
				</div>
			</div>
			<a href="/home/account/info.html" class="sc"></a>
		</div>
		<div class="main accountCenter">

			<div class="jifen cl">
				<div class="jf_one">
					<i class="jf_ico"><img src="/static/home/mobile/picture/money.png"></i>
					<div class="jf_desc">余额<span id="gold"><?php echo !empty($member['credit2'])?$member['credit2']:0; ?></span>元
						<ul>
							<li onclick="location.href='/home/recharge.html'">充值</li>
							<li onclick="location.href='/home/account/withdraw.html'">取现</li>
							<li onclick="location.href='/home/charge.html'">流水</li>
						</ul>
					</div>
				</div>

			</div>
			<div class="jifen cl">
				<div class="jf_two">
					<ul>
						<li onclick="location.href='/home/mytask.html'">
							<span id="myTask"><?php echo $task_num; ?></span>
							<p>发布</p>
						</li>
                        <li onclick="location.href='/home/mytask/audit.html'">
							<span id="myHidden"><?php echo $audit_num; ?></span>
							<p>审核</p>
						</li>
                        <li onclick="location.href='/home/mytaskjoin.html'">
							<span id="myAttend"><?php echo $join_num; ?></span>
							<p>参与</p>
						</li>
                        <li onclick="location.href='/home/account/credit.html'">
							<span id="silver"><?php echo !empty($member['credit1'])?$member['credit1']:0; ?></span>
							<p>积分</p>
						</li>
					</ul>
				</div>
			</div>
			<div class="jifen cl">
				<div class="jf_one">
					<a href="/home/fans.html" class="help">
						<i class="jf_ico"><img src="/static/home/mobile/images/users_08.png"/></i>
						<div class="jf_desc">粉丝关注</div>
					</a>
				</div>
			</div>
			<div class="jifen cl">
				<div class="jf_one">
					<a href="/home/feedback.html" class="help">
						<i class="jf_ico"><img src="/static/home/mobile/images/users_07.png"/></i>
						<div class="jf_desc">我的反馈</div>
					</a>
				</div>
			</div>
			<div class="jifen cl clipboards">
				<div class="jf_one">
					<i class="jf_ico"><img src="/static/home/mobile/picture/qq1.png"/></i>
					<div class="jf_desc" style="display: block;width: 70%">
						<a href="<?php echo !empty($site['qq'])?$site['qq']:''; ?>" target="_blank" style="display: block;">
						QQ群
						</a>
					</div>
					<!-- <span style="cursor: pointer" class="more-btn" data-copy="<?php echo !empty($site['qq'])?$site['qq']:''; ?>" onclick="copy(this)"><?php echo !empty($site['qq'])?$site['qq']:''; ?></span> -->
				</div>
			</div>
			<div class="jifen cl clipboards">
				<div class="jf_one">
					<i class="jf_ico"><img src="/static/home/mobile/picture/customer.png"/></i>
					<div class="jf_desc">
						微信客服
					</div>
					<span style="cursor: pointer" class="more-btn" data-copy="<?php echo !empty($site['wechat'])?$site['wechat']:''; ?>" onclick="copy(this)"><?php echo !empty($site['wechat'])?$site['wechat']:''; ?></span>
				</div>
			</div>
			<div class="jifen cl">
				<div class="jf_one">
					<a href="/home/notice.html" class="notice-list">
						<i class="jf_ico"><img src="/static/home/mobile/picture/notice.png"/></i>
						<div class="jf_desc">公告</div>
					</a>
				</div>
			</div>
			<div class="jifen cl">
				<div class="jf_one">
					<a href="/home/help.html" class="help">
						<i class="jf_ico"><img src="/static/home/mobile/picture/help.png"/></i>
						<div class="jf_desc">新手引导</div>
					</a>
				</div>
			</div>
		</div>
        <script type="text/javascript">
            $(function () {
                $('#sign').click(function () {
                    /*$.post(
                        window.location.href,
                        {},
                        function (res) {
                            message(res.message,res.redirect,res.type);
                        }
                    );*/
                    window.location.href = '/home/signin.html';
                });
            });
        </script>
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
</body>
</html>
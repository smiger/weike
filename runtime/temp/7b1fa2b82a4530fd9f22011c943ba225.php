<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:37:"./views/home/mobile/task\detail.phtml";i:1578379034;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1578387613;}*/ ?>
﻿<!doctype html>
<html>
	<head>
	<meta name="apple-mobile-web-app-capable" content="yes">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>[<?php echo !empty($item['id'])?$item['id']:''; ?>]<?php echo !empty($item['title'])?$item['title']:''; ?>,佣金<?php echo !empty($item['unit_price'])?$item['unit_price']:''; ?>积分</title>
	<link rel="stylesheet" href="/static/home/mobile/css/bootstrap.css">
	<link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css" />
	<link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
    <link href="/static/home/mobile/css/lightbox.min.css" rel="stylesheet" type="text/css" />
	<link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css" />
    <link href="/static/home/mobile/css/swiper.min.css" type="text/css" rel="stylesheet">
	<link href="/static/home/mobile/css/new_page.css" rel="stylesheet" type="text/css" />
    <link href="/static/home/mobile/css/new_style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/static/plugins/dialog/css/dialog.css" />
    <link rel="stylesheet" href="/static/home/mobile/css/layui.css">
    <style type="text/css">
        .task-list-remain-num span {
            width: 33.33%;
            float: left;
            text-align: center;
        }
        .paging {
            padding: 30px 0;
        }
        .dialog {
          position: fixed;
          left: 0;
          top: 0;
          z-index: 10001;
          width: 100%;
          height: 100%;
        }
        .dialog-content {
            width: 70%;
        }
        .dialog-overlay {
          position: absolute;
          top: 0;
          left: 0;
          z-index: 10002;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
        }
        .pr-info .pic a.user_opera_btn {
            display:block;
            width:56px;
            font-size:12px;
            line-height:22px;
            color:#FE4B1C;
            text-align:center;
            border:1px solid #FE4B1C;
            box-sizing: border-box;
            border-radius:4px;
        }
    </style>
    </head>
    <body data-offset="50" id="htmlBody">
    <header class="site-header header-fixed">
        <input type="hidden" id="taskId">
        <a onclick="history.back()" class="back"></a>
        <div class="tit-name" id="title_txt">任务详情</div>
        <?php if($is_can_op == 1): ?>
        <a href="/home/mytaskaudit/index/id/<?php echo !empty($item['id'])?$item['id']:''; ?>.html" class="sc" id="schide_sh">审核</a>
        <!-- <a href="#" class="sc" style="right: 110px;" id="schide_sh">审核</a>
        <a href="#" class="sc" id="schide" style="right:60px;" onclick="TaskDetail.hideTask()">下架</a>
        <a href="#" class="sc" id="sc">隐藏</a>
        <a href="#" class="sc" id="qx">取消隐藏</a> -->
        <?php endif; ?>
    </header>
    <div class="main fixed-padding">
        <div class="pr-info borm cl" style=" margin-bottom:10px;">
            <div class="pic detaill">
                <div class="type-txuser">
                    <a href="#"><img onerror="this.src='/static/home/mobile/picture/pic2.png'" src="<?php echo to_media($item['avatar']?$item['avatar']:''); ?>" id="task_account_img"></a>
                </div>
                <a href="#" class="user_opera_btn" onclick="followUser('<?php echo $item['uid']; ?>');">+关注</a>
            </div>
            <div class="info">
                <div class="bt"><i class="bianhao">编号：</i><span id="task_id"><?php echo !empty($item['id'])?$item['id']:''; ?></span></div>
                <div class="bt"><i class="fabu">发布者：</i><span id="task_account_name"><?php echo !empty($item['username'])?$item['username']:''; ?></span><i class="dengji">V<?php echo !empty($item['level'])?$item['level']:''; ?></i></div>
                <div class="persent-box">
                    <div class="persent-all"><span class="persent-bi" style="width: <?php echo !empty($item['percent'])?$item['percent']:0; ?>%;"></span></div><span class="num"><?php echo !empty($item['percent'])?$item['percent']:0; ?>%</span></div>
                <div class="time">结束：<span id="task_endTime"><?php echo !empty($item['end_time'])?$item['end_time']:''; ?></span>剩余：<span id="task_remain"><?php echo $item['ticket_num']-$item['join_num']; ?></span>份</div>
                <div class="time" style="" id="div_task_stateNum">待审核：<span id="task_stateNum"><?php echo $item['audit_num']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;已抢单：<span id="task_allNum"><?php echo $item['join_num']; ?></span></div>
            </div>
        </div>
        <?php if($is_can_op == -1): ?>
        <div class="re-tit" id="pubshDiv" style="margin-left:2px;margin-bottom:2px;margin-top:2px;">
            <ul class="nav nav-pills new-nav-pills">
                <li onclick="TaskDetail.manual_refresh()">
                    手动刷新
                </li>
                <li onclick="TaskDetail.auto_refresh()">
                    自动刷新
                </li>
                <li onclick="TaskDetail.home_page_top()">
                    首页置顶
                </li>
                <li onclick="TaskDetail.page_set_up()">
                    分页置顶
                </li>
                <li onclick="TaskDetail.showUpdateWin2()">
                    追加票数
                </li>
            </ul>
        </div>
        <?php endif; ?>
        <div class="pr-shuxing">
            <p>标题：<span id="task_title" style="color:#666666"><?php echo $item['title']; ?></span></p>
<!--            <p>审核周期：<span id="task_cycle" style="color:#666666"><?php echo $item['check_period']; ?>小时</span></p>-->
            <p>是否需要截图：<span id="task_needImg" style="color:#666666"><?php echo !empty($item['is_screenshot'])?'是':'否'; ?></span></p>
            <p style="display: none;">是否限制区域：<span id="task_limitIp" style="color:#666666"><?php echo !empty($item['is_ip_restriction'])?'是':'否'; ?></span></p>
            <p style="display: none;"><span id="task_isRepeat" style="color:red;"><?php echo !empty($item['province'])?$item['province']:'无地区限制'; ?></span></p>
            <p id="task_number">
                <?php if($item['rate'] == 0): ?>
                仅限一次
                <?php elseif($item['rate'] == 1): ?>
                每天一次
                <?php elseif($item['rate'] == 2): ?>
                <?php echo $item['interval_hour']; ?>小时一次
                <?php endif; ?>
            </p>
            <p>任务奖励：单价&nbsp;<span id="task_gold"><?php echo $item['unit_price']; ?></span>&nbsp;积分</p>
        </div>
        <div class="pr-xiangqing" id="task_url_div">
            <p class="tit" id="task_url_txt">相关地址</p>
            <p id="task_url" data-copy="<?php echo $item['about_url']; ?>"><?php echo $item['about_url']; ?></p>
        </div>
        <div class="pr-xiangqing">
            <p class="tit">任务需求</p>
            <p id="task_description" style="width:98%">
                <?php echo nl2br($item['detail']); ?>
            </p>
        </div>
        <?php if(!empty($item['check_text_content'])): ?>
        <div class="pr-xiangqing">
            <p class="tit">文字验证</p>
            <p id="task_description" style="width:98%">
                <?php echo nl2br($item['check_text_content']); ?>
            </p>
        </div>
        <?php endif; if(!empty($item['remarks'])): ?>
        <div class="pr-xiangqing">
            <p class="tit">备注</p>
            <p id="task_description" style="width:98%">
                <?php echo nl2br($item['remarks']); ?>
            </p>
        </div>
        <?php endif; if(!empty($item['thumbs'])): ?>
        <div class="fujian cl">
            <p class="tit">审核图样</p>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if(is_array($item['thumbs']) || $item['thumbs'] instanceof \think\Collection || $item['thumbs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['thumbs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$thumb): $mod = ($i % 2 );++$i;?>
                    <div class="swiper-slide">
                        <a data-lightbox="thumbs-set" href="<?php echo to_media($thumb); ?>"><img src="<?php echo to_media($thumb); ?>"></a></div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <?php endif; if(!empty($operate_steps)): ?>
        <div class="replies" style="margin-bottom: 10px;">
            <div class="replies-list" style="margin-bottom: 0; font-size: 15px;">
                <div class="user-s cl">
                    操作说明
                </div>
            </div>
            <?php if(is_array($operate_steps) || $operate_steps instanceof \think\Collection || $operate_steps instanceof \think\Paginator): $k = 0; $__LIST__ = $operate_steps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$operate_step): $mod = ($k % 2 );++$k;?>
            <div class="replies-list" style="margin-bottom: 0; font-size: 13px;">
                <div class="user-s cl">
                    步骤<?php echo $k; ?>
                </div>
                <div class="replies-desc">
                    <p><span style="color:#E84C3D;"></span></p>
                    <p mode="wrap" style="font-size: 13px;"><?php echo $operate_step['content']; ?></p>
                    <?php if(!empty($operate_step['image'])): ?>
                    <p mode="wrap" style="font-size: 13px;"><a data-lightbox="operate_steps-set" href="<?php echo to_media($operate_step['image']); ?>"><img src="<?php echo to_media($operate_step['image']); ?>" /></a></p>
                    <?php endif; ?>
                </div>
                <div class="replies-img cl"></div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php endif; ?>

        <div class="replies" id="replies">
            <ul id="flow">
            </ul>
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

        <?php if($allow_accept): if(!is_null($member_task_join_info)): if($member_task_join_info['status'] == 1): ?>
            <a href="/home/taskcheck/index/id/<?php echo !empty($item['id'])?$item['id']:''; ?>.html" class="upload_yanzheng" style="bottom: 142px;">
                <div class="vip_btn_wrap">
                    <div class="btn_top">上传</div><div>验证</div>
                </div>
            </a>
            <a href="javascript:" onclick="remove(<?php echo $member_task_join_info['id']; ?>)" class="upload_yanzheng">     
                <div class="vip_btn_wrap">
                    <div class="btn_top">放弃</div><div>接单</div>
                </div>
            </a>
            <?php endif; else: ?>
        <a id="replyUrl">
            <div class="s-more-1"></div>
        </a>
        <?php endif; endif; ?>

        <script src="/static/home/mobile/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/lightbox.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/swiper.min.js"></script>
        <script type="text/javascript">
            var swiper = new Swiper('.fujian .swiper-container', {
                nextButton: '.fujian .swiper-button-next',
                prevButton: '.fujian .swiper-button-prev',
                spaceBetween: 0,
                speed: 1000,
                autoplay: 3000
            });
        </script>
        <!-- 弹出层 -->
        <script src="/static/plugins/dialog/js/dialog.js"></script>
        <!-- 弹出层 -->
        <script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/global.js?v=1001"></script>
        <script type="text/javascript">
        var id = <?php echo !empty($item['id'])?$item['id']:''; ?>;
        $("#replyUrl").click(function(){
            $(document).dialog({
                type : 'confirm',
                titleText: '任务抢单',
                content: '请按备注及操作说明完成任务，完成后按要求在【我的任务】里上传验证图',
                onClickConfirmBtn: function(){
                    $loading = message('正在抢单','','loading');
                    $.post('/home/task/accept.html', { id: id }, function(res) {
                        message(res.message,res.redirect,res.type);
                        $loading.close();
                    });
                }
            });
        });

        $("#task_url").click(function(){
            copy("#task_url");

            $(document).dialog({
                type : 'confirm',
                titleShow: false,
                overlayClose: true,
                content: '任务相关地址操作',
                buttonStyle: 'stacked',  // side: 并排; stacked: 堆叠
                buttons: [          
                    {
                        name: '打开网址',
                        callback: function() {
                            window.location.href = $("#task_url").text();
                        }
                    },
                    {
                        name: '复制网址',
                        class: 'clipboards',
                        callback: function() {
                        }
                    }
                ]
            });
        });
        </script>

        <script type="text/javascript" src="/static/home/mobile/js/layui/layui.js"></script>
        <script type="text/javascript">
        function remove(id) {
            $(document).dialog({
                type : 'confirm',
                titleText: '放弃接单',
                content: '确定要放弃编号[' + <?php echo !empty($item['id'])?$item['id']:''; ?> + ']的任务吗？',
                onClickConfirmBtn: function(){
                    Common.loading.show();
                    $.post('/home/mytaskjoin/del.html', { id: id }, function(res) {
                        message(res.message,res.redirect,res.type);
                        Common.loading.hide();
                    });
                }
            });
        }

        var saveFlag = "0";
        function followUser(user_id){
            if(saveFlag == '1'){
                return false;
            }
            saveFlag = "1";
            Common.loading.show();
            $.post('/home/fans/followUser.html', { user_id: user_id }, function(res) {
                message(res.message,res.redirect,res.type);
                Common.loading.hide();
            });     
        }

        $(function(){
                //流加载
                layui.use('flow', function(){
                    var flow = layui.flow;
                    flow.load({
                            elem: '#replies'
                            ,done: function(page, next){
                                    var lis = [];
                                    $.get('/home/task/task_join_ajax?id='+id+'&page='+page, function(res){
                                        layui.each(res.data.data, function(index, item){
                                            var html = ['            <div class="replies-list">',
                                            '                <div class="user-s cl"><i class="u-pic"><img',
                                            '                                src="' + item.avatar + '" onerror="this.src=\'/static/home/mobile/picture/user.png\'"></i>',
                                            '                    <div class="u-info">',
                                            '                        <div class="bt"><span class="name">' + item.username + '</span><span class="level">V' + item.level + '</span></div>',
                                            '                        <div class="time">' + item.audit_time + '</div>',
                                            '                    </div>',
                                            '                    <i class="static static-sh1">已通过</i>',
                                            '                </div>',
                                            '                <div class="replies-desc"><p><span style="color:#E84C3D;"></span></p>',
                                            '                    </div>',
                                            '                <div class="replies-img cl"></div>',
                                            '            </div>'].join("");
                                            $("#flow").append(html);
                                        });
                                        next(lis.join(''), res.data.length > 0 ? true : false);
                                    });
                            }
                    });
                });
        });
        </script>
    </body>
</html>
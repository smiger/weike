<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:37:"./views/home/mobile/index\index.phtml";i:1578038129;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1541935408;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo !empty($site['seo_title'])?$site['seo_title']:''; ?></title>
		<meta name="Description" content="<?php echo !empty($site['seo_description'])?$site['seo_description']:''; ?>" />
		<meta name="Keywords" content="<?php echo !empty($site['seo_keywords'])?$site['seo_keywords']:''; ?>" />
		<link rel="stylesheet" href="/static/home/mobile/css/bootstrap.css">
		<link rel="stylesheet" href="/static/home/mobile/css/font-awesome.min.css">
		<link href="/static/home/mobile/css/reset_5.css" rel="stylesheet" type="text/css" />
		<link href="/static/home/mobile/css/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="/static/home/mobile/css/new_page.css?v=2" />
		<link rel="stylesheet" href="/static/home/mobile/css/swiper.min.css" />
		<style type="text/css">
			.new-main {
				padding-bottom: 40px;
			}
            .load-more{
                margin-top: -10px;
                margin-bottom: 30px;
                height: 30px;
                line-height: 30px;
                text-align: center;
                background: #ffffff;
            }
		</style>
        <!-- 弹出层 -->
        <link rel="stylesheet" href="/static/plugins/dialog/css/dialog.css" />
        <script type="text/javascript" src="/static/home/mobile/js/jquery-2.0.3.min.js"></script>
        <script src="/static/plugins/dialog/js/dialog.js"></script>
        <!-- 弹出层 -->
        <script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/swiper.min.js"></script>
        <script type="text/javascript" src="/static/home/mobile/js/global.js?v=1001"></script>
	</head>
    <body class="new-body">
    <header class="new-header" style="padding-top: 12px;">
        <form id="search_form" action="/home/task/index.html">
            <input type="hidden" name="search_type" value="1">
            <input type="hidden" name="order_type" value="0">
            <input type="hidden" name="page" value="1">
            <div class="container">
                <span class="daily-task">每日任务</span>
                <div class="input-group">
                    <input type="text" placeholder="请输入标题关键词" name="keyword">
                    <img class="search-btn" src="/static/home/mobile/picture/search.png">
                </div>
                <span class="dowm-btn">
                    <img src="/static/home/mobile/picture/down.png">
                </span>
            </div>
        </form>
    </header>
    <div class="add-dowmload">
        <span>公告</span>
        <div id="notice" class="swiper-container swiper-container-vertical">
            <div class="swiper-wrapper">
                <?php if(!empty($notices)): if(is_array($notices) || $notices instanceof \think\Collection || $notices instanceof \think\Paginator): $i = 0; $__LIST__ = $notices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$notice): $mod = ($i % 2 );++$i;?>
                        <div class="swiper-slide swiper-slide-duplicate">
                            <a id="slide_href" href="/home/notice/detail/id/<?php echo $notice['id']; ?>.html" hid="12"><?php echo $notice['title']; ?></a>
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </div>
        </div>
    </div>
    <div class="dowm-list">
        <ul class="second-nav">
            <li data-type="0" class="active">智能排序</li>
            <li data-type="1">金额数</li>
            <li data-type="2">积分数</li>
            <li data-type="3">进度</li>
            <li data-type="4">等级</li>
        </ul>
    </div>
    <div class="openNotice-back">
        <div class="openNotice">
            <span class="notice-ban">今日不再提示</span>
            <span class="notice-close"><img src="/static/home/mobile/picture/close.png"></span>
            <div class="noticeDetail">

            </div>
        </div>
    </div>
    <?php if(!empty($banners)): ?>
    <div class="">
      <div id="banner" class="swiper-container">
        <div class="swiper-wrapper">
          <?php if(is_array($banners) || $banners instanceof \think\Collection || $banners instanceof \think\Paginator): $i = 0; $__LIST__ = $banners;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$banner): $mod = ($i % 2 );++$i;?>
          <div class="swiper-slide">
            <a href="<?php echo $banner['url']; ?>" target="_blank"><img src="<?php echo to_media($banner['thumb']); ?>" border="0" alt="<?php echo to_media($banner['title']); ?>" /></a>
          </div>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!-- Add Pagination -->
        <div id="banner-pagination" class="swiper-pagination"></div>
      </div>
    </div>
    <?php endif; if(!empty($categories)): ?>
    <div class="new-task-content">
        <ul>
            <?php if(is_array($categories) || $categories instanceof \think\Collection || $categories instanceof \think\Paginator): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$category): $mod = ($i % 2 );++$i;?>
            <li>
                <a href="/home/task/index/category_id/<?php echo $category['id']; ?>.html">
                    <img src="<?php echo to_media($category['thumb']); ?>">
                    <span><?php echo $category['title']; ?></span>
                </a>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div class="index-withdraws" style="padding-top:5px;border-top: solid 1px #E9E9E9;">
        <div id="withdraw" class="swiper-container swiper-container-vertical">
            <div class="swiper-wrapper">
                <?php if(!empty($withdraws)): if(is_array($withdraws) || $withdraws instanceof \think\Collection || $withdraws instanceof \think\Paginator): $i = 0; $__LIST__ = $withdraws;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$withdraw): $mod = ($i % 2 );++$i;?>
                        <div class="swiper-slide swiper-slide-duplicate">
                            <em style="color: #FD2D2E;"><?php echo $withdraw['username']; ?></em> 于<?php echo date("Y-n-j",strtotime($withdraw['create_time'])); ?> 提现：<em style="color: #FD2D2E;"><?php echo $withdraw['credit2']; ?></em> 元
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </div>
        </div>
    </div>
    <div class="new-site-tit">
        今天完成所有任务可获得：<span id="expect_gold"><?php echo $today_credit2; ?></span> 元，<span id="expect_silver"><?php echo $today_credit1; ?></span> 积分
    </div>
    <div class="new-main index-new-main">
        <?php if(!empty($tasks)): ?>
        <div class="new-task-list">
            <ul>
                <?php if(is_array($tasks) || $tasks instanceof \think\Collection || $tasks instanceof \think\Paginator): $task_index = 0; $__LIST__ = $tasks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($task_index % 2 );++$task_index;?>
                <li>
                    <a href="/home/task/detail/id/<?php echo $task['id']; ?>.html">
                        <?php if($task_index == 0): ?>
                            <img class="task-list-hot" src="/static/home/mobile/images/hot.png">
                        <?php endif; ?>
                        <div class="task-list-title-silver">
                            <div class="task-list-title"><?php echo $task['title']; ?></div>
                            <span class="task-list-gold">
                                <span>￥</span>
                                <?php echo $task['unit_price']; ?>元
                            </span>
                        </div>
                        <span class="task-list-prefix">商</span>
                        <span class="task-list-id"><?php echo $task['id']; ?></span>
                        <span class="task-jifen"><?php echo $task['give_credit1']; ?>积分</span>
                        <span class="task-list-remain-num">剩余<?php echo $task['ticket_num']-$task['join_num']; ?>份</span>
                        <div class="task-list-progress-content">
                            <div class="task-list-progress"><span style="width:<?php echo $task['percent']; ?>%"></span></div>
                            <div class="task-list-progress-num"><?php echo $task['percent']; ?>%</div>
                        </div>
                        <div class="task-list-gold-silver">
                            <span class="task-list-end-time">结束时间：<?php echo $task['end_time']; ?> </span>
                        </div>
                    </a>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="paging">
            <span class="paging-prev">上一页</span>
            <span class="paging-num-total">
					<select name="page" class="paging-selct">
                        <?php $__FOR_START_310480165__=1;$__FOR_END_310480165__=$pageCount+1;for($i=$__FOR_START_310480165__;$i < $__FOR_END_310480165__;$i+=1){ ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
				</span>
            <span class="paging-next" data-page="<?php echo $pageCount; ?>">下一页</span>
        </div>
        <?php endif; ?>
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
    <script type="text/javascript" src="/static/home/mobile/js/index.js"></script>
    <?php echo !empty($site['baidu_stat'])?$site['baidu_stat']:''; ?>
    <?php echo !empty($site['baidu_chat'])?$site['baidu_chat']:''; ?>
    </body>
</html>
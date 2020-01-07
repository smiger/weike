<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:36:"./views/home/mobile/task\index.phtml";i:1578379044;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1578387613;}*/ ?>
<!DOCTYPE HTML>
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
		<link rel="stylesheet" type="text/css" href="/static/home/mobile/css/new_page.css" />
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
            <input type="hidden" name="search_type" value="0">
            <input type="hidden" name="order_type" value="0">
            <input type="hidden" name="page" value="1">
            <div class="container">
                <span class="daily-task">全部任务</span>
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
    <div class="dowm-list">
        <ul class="second-nav">
            <li data-type="0" class="active">智能排序</li>
            <li data-type="1">金额数</li>
            <li data-type="2">积分数</li>
            <li data-type="3">进度</li>
            <li data-type="4">等级</li>
        </ul>
    </div>
    <div style="width: 100%;height: 65px;"></div>
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
                                <?php echo $task['unit_price']; ?>积分
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
                        <?php $__FOR_START_248294990__=1;$__FOR_END_248294990__=$pageCount+1;for($i=$__FOR_START_248294990__;$i < $__FOR_END_248294990__;$i+=1){ ?>
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
    <script type="text/javascript" src="/static/home/mobile/js/task.js?v=2"></script>
    <?php echo !empty($site['baidu_stat'])?$site['baidu_stat']:''; ?>
    <?php echo !empty($site['baidu_chat'])?$site['baidu_chat']:''; ?>
    </body>
</html>
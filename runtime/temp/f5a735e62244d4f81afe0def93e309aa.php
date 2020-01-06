<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:38:"./views/home/mobile/mytask\index.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;s:70:"D:\BaiduYunDownload\weike2\public\views\home\mobile\mytask\_list.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1541935408;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>我发布的</title>
    <link rel="stylesheet" href="/static/home/mobile/css/bootstrap.css">
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
    <style type="text/css">
        .zhuanghu-tab li {
            width: 20%;
        }
        .task-list-remain-num span {
            width: 33.33%;
            float: left;
            text-align: center;
        }
        .paging {
            margin: 30px 0;
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
    </style>
</head>

<body>
<div class="site-header header-fixed">
    <a onclick="history.back()" class="back"></a>
    <div class="tit-name">我发布的</div>
</div>
<div class="main Myrelease">
    <div class="zhuanghu-tab">
        <ul class="zh-tab-tit">
            <li id="all" class="<?php echo $category_type=='all'?'active':''; ?>" onclick="location.href='/home/mytask.html'">全部</li>
            <li id="wait" class="<?php echo $category_type=='wait'?'active':''; ?>" onclick="location.href='/home/mytask/category/t/wait.html'">待审核</li>
            <li id="ing" class="<?php echo $category_type=='ing'?'active':''; ?>" onclick="location.href='/home/mytask/category/t/ing.html'">进行中</li>
            <li id="pass" class="<?php echo $category_type=='pass'?'active':''; ?>" onclick="location.href='/home/mytask/category/t/pass.html'">已完成</li>
            <li id="past" class="<?php echo $category_type=='past'?'active':''; ?>" onclick="location.href='/home/mytask/category/t/past.html'">已失效</li>
        </ul>
    </div>
    <div class="task-list">
        <?php if(!empty($tasks)): ?>
        <div class="new-task-list">
            <ul>
                                <?php if(is_array($tasks) || $tasks instanceof \think\Collection || $tasks instanceof \think\Paginator): $task_index = 0; $__LIST__ = $tasks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($task_index % 2 );++$task_index;?>
                <li>
                    <div class="task-list-wrap">
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
                        <!-- <span class="task-list-prefix">商</span> -->
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
                        <div class="task-list-gold-silver">
                            <span class="task-list-end-time" style="width: auto;">任务状态：
                            <?php if($task['category_type'] == "past"): ?>
                            任务已到期
                            <?php endif; if($task['category_type'] == "complete"): ?>
                            任务已完成
                            <?php endif; if($task['category_type'] == "wait"): ?>
                            任务待审核
                            <?php endif; if($task['category_type'] == "nopass"): ?>
                            任务未通过审核，编辑任务查看详情
                            <?php endif; if($task['category_type'] == "ing"): ?>
                            任务进行中
                            <?php endif; ?>
                            </span>
                        </div>
                        <div class="task-list-manage">
                            <?php if($task['category_type'] == "ing" && $task['join_num'] > 0): ?>
                            <a href="/home/mytaskaudit/index/id/<?php echo $task['id']; ?>.html" onclick="check(<?php echo $task['id']; ?>)" class="task-list-manage-button">审核</a>
                            <?php endif; if($task['category_type'] == "ing"): ?>
                            <a href="javascript:" id="setTop<?php echo $task['id']; ?>" onclick="setTop(<?php echo $task['id']; ?>)" class="task-list-manage-button<?php echo $task['top_hour']>0?' ajax-ok':''; ?>"><?php echo $task['top_hour']>0?'已置顶':'置顶'; ?></a>
                            <a href="javascript:" id="outStockTask<?php echo $task['id']; ?>" onclick="outStockTask(<?php echo $task['id']; ?>)" class="task-list-manage-button<?php echo $task['out_stock_flag']==1?' ajax-ok':''; ?>"><?php echo $task['out_stock_flag']==1?'已下架':'下架'; ?></a>
                            <?php endif; ?>
                            <a href="/home/task/detail/id/<?php echo $task['id']; ?>.html" class="task-list-manage-button">查看任务</a>
                            <?php if($task['category_type'] == "wait" || $task['category_type'] == "nopass"): ?>
                            <a href="/home/task/edit/id/<?php echo $task['id']; ?>.html" class="task-list-manage-button">编辑任务</a>
                            <a href="javascript:" class="task-list-manage-button task-list-manage-del" id="delMyTask<?php echo $task['id']; ?>" onclick="delMyTask(<?php echo $task['id']; ?>)">删除任务</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="task-del" style="display:none;">
            <a href="#" class="button4" onclick="MySaveTask.delSave()">删除(0)</a>
        </div>
        <div class="paging clearfix" style="display: block;">
            <span class="paging-prev">上一页</span>
            <span class="paging-num-total">
                    <select name="page" class="paging-selct">
                        <?php $__FOR_START_376974905__=1;$__FOR_END_376974905__=$pageCount+1;for($i=$__FOR_START_376974905__;$i < $__FOR_END_376974905__;$i+=1){ ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </span>
            <span class="paging-next" data-page="<?php echo $pageCount; ?>">下一页</span>
            <form id="page_form">
                <input type="hidden" name="page" value="1">
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript" src="/static/plugins/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/static/home/mobile/js/bootbox.min.js"></script>
<script type="text/javascript">
var $setting_top_fee = '<?php echo $setting['top_fee']; ?>';
var $setting_top_max_hour = '<?php echo $setting['top_max_hour']; ?>';
</script>
<script type="text/javascript" src="/static/home/mobile/js/mytask.js?v=4"></script>

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
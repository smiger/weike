<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:38:"./views/home/mobile/mytask\audit.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;s:76:"D:\BaiduYunDownload\weike2\public\views\home\mobile\mytask\_audit_list.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1541935408;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>我审核的</title>
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
    <a href="/home/account.html" class="back"></a>
    <div class="tit-name">我审核的</div>
</div>
<div class="main Myrelease">
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
                        <div class="task-list-manage">
                            <a href="/home/mytaskaudit/index/id/<?php echo $task['id']; ?>.html" onclick="check(<?php echo $task['id']; ?>)" class="task-list-manage-button">审核</a>
                        </div>
                    </div>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="task-del" style="display:none;">
            <a href="#" class="button4" onclick="MySaveTask.delSave()">删除(0)</a>
        </div>
        <div class="paging" style="display: block;">
            <span class="paging-prev">上一页</span>
            <span class="paging-num-total">
                    <select name="page" class="paging-selct">
                        <?php $__FOR_START_1070970800__=1;$__FOR_END_1070970800__=$pageCount+1;for($i=$__FOR_START_1070970800__;$i < $__FOR_END_1070970800__;$i+=1){ ?>
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
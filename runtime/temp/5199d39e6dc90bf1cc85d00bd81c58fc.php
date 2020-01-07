<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:43:"./views/home/mobile/mytaskaudit\index.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\header.phtml";i:1578038141;s:75:"D:\BaiduYunDownload\weike2\public\views\home\mobile\mytaskaudit\_list.phtml";i:1541935408;s:71:"D:\BaiduYunDownload\weike2\public\views\home\mobile\common\footer.phtml";i:1578387613;}*/ ?>
﻿<!DOCTYPE HTML>
<html>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>审核任务[<?php echo !empty($item['id'])?$item['id']:''; ?>]</title>
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
    </style>
</head>

<body data-offset="140" id="htmlBody">
<div class="site-header header-fixed">
    <a onclick="history.back()" class="back"></a>
    <div class="tit-name">审核任务[<?php echo !empty($item['id'])?$item['id']:''; ?>]</div>
</div>
<div class="main myAttend">
    <div class="task-list">
        <?php if(!empty($tasks)): ?>
        <div class="new-task-list">
                        <?php if(is_array($tasks) || $tasks instanceof \think\Collection || $tasks instanceof \think\Paginator): $task_index = 0; $__LIST__ = $tasks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$task): $mod = ($task_index % 2 );++$task_index;?>
            <ul class="ui-list-text reward-icon">
                <li class="clearfix">
                    <i class="iconfont" style="background-image: url(<?php echo $task['category_icon']; ?>);"></i>
                    <div class="ui-vertical">
                        <h3 class="ui-row"><span class="text-color-orange fr">￥<?php echo $task['unit_price']; ?></span><?php echo $task['title']; ?></h3>
                        <p>NO.<?php echo $task['no']; ?>&nbsp;&nbsp;
                            <?php if($task['join_status'] == 1): ?>
                            <span class="fr">等待上传验证</span>
                            <?php echo $task['join_create_time']; ?>&nbsp;
                            抢单
                            <?php endif; if($task['join_status'] == 2): ?>
                            <span class="fr">等待审核</span>
                            <?php echo $task['join_create_time']; ?>&nbsp;
                            抢单
                            <?php endif; if($task['join_status'] == 3): ?>
                            <span class="fr">奖励已发放</span>
                            <?php echo $task['join_create_time']; ?>&nbsp;<!-- <?php echo $task['join_audit_time']; ?>&nbsp; -->
                            审核
                            <?php endif; if($task['join_status'] == 4): ?>
                            <span class="fr">审核不通过</span>
                            <?php echo $task['join_create_time']; ?>&nbsp;<!-- <?php echo $task['join_audit_time']; ?>&nbsp; -->
                            审核不通过
                            <?php endif; ?>
                        </p>
                    </div>
                </li>
                <?php if($task['join_status'] == 2): ?>
                <li class="operate ui-flex mb15">
                    <div class="ui-col text-color-blue">
                        <a style="padding:0;" href="/home/mytaskaudit/detail/id/<?php echo $task['join_id']; ?>.html">审核</a>
                    </div>
                </li>
                <?php endif; if($task['join_status'] == 4): ?>
                <li class="operate ui-flex mb15">
                    <div class="ui-col text-color-blue">
                        <a style="padding:0;" href="/home/mytaskaudit/view/id/<?php echo $task['join_id']; ?>.html" target="_blank">查看</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="paging" style="display: block;">
            <span class="paging-prev">上一页</span>
            <span class="paging-num-total">
                    <select name="page" class="paging-selct">
                        <?php $__FOR_START_2041997495__=1;$__FOR_END_2041997495__=$pageCount+1;for($i=$__FOR_START_2041997495__;$i < $__FOR_END_2041997495__;$i+=1){ ?>
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
<script type="text/javascript" src="/static/home/mobile/js/mytaskjoin.js?v=4"></script>
<script type="text/javascript">
function check(join_id){
    window.location.href = "/home/mytaskaudit/detail/id/"+join_id+".html";
}
</script>
</body>
</html>
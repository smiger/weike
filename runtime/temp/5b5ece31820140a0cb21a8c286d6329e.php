<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:34:"./views/admin/pc/index\index.phtml";i:1568117985;s:68:"D:\BaiduYunDownload\weike2\public\views\admin\pc\common\header.phtml";i:1578038128;s:69:"D:\BaiduYunDownload\weike2\public\views\admin\pc\common\sidebar.phtml";i:1541967454;s:68:"D:\BaiduYunDownload\weike2\public\views\admin\pc\common\footer.phtml";i:1568118028;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理系统 | 管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- 弹出框 -->
    <link rel="stylesheet" type="text/css" href="/static/plugins/SmallPop/spop.min.css">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/plugins/Ionicons/css/ionicons.min.css">
    <!-- 图片展示插件样式 -->
    <link rel="stylesheet" href="/static/plugins/magnify/dist/jquery.magnify.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/plugins/AdminLTE/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/plugins/AdminLTE/css/skins/_all-skins.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/plugins/html5shiv.min.js"></script>
    <script src="/static/plugins/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/static/plugins/SmallPop/spop.min.js"></script>
    <!-- jQuery 3 -->
    <script src="/static/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- 剪切板 -->
    <script type="text/javascript" src="/static/plugins/clipboard.min.js"></script>
    <!-- 图片展示插件 -->
    <script type="text/javascript" src="/static/plugins/magnify/dist/jquery.magnify.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/static/plugins/AdminLTE/js/adminlte.min.js"></script>
    <!--引入JS-->
    <script src="/static/admin/web/js/global.js?v=1001"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/admin" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>系统</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>管理系统</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $admin['username']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $admin['username']; ?>
                                    <small><?php echo date('Y年m月d日'); ?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo U('index/profile'); ?>" class="btn btn-default btn-flat">资料设置</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo U('auth/login'); ?>" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img onerror="this.src='/static/admin/web/images/avatar.png'" src="<?php echo to_media($admin['avatar']); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $admin['username']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i>在线</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">功能菜单</li>

            <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$oo): $mod = ($i % 2 );++$i;if($oo['childnum'] == '0'): ?>
                <li <?php if(menuActive($oo['name'], $oo['level'])): ?>class="active"<?php endif; ?>><a href="<?php echo U($oo['name']); ?>"><i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span></a></li>
                <?php elseif($oo['level'] == '1'): ?>
                <li class="treeview <?php if(menuActive($oo['name'], $oo['level'])): ?>active<?php endif; ?>">
                    <a href="javascript:void(0);">
                        <i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                        <?php if(is_array($oo['children']) || $oo['children'] instanceof \think\Collection || $oo['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $oo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$to): $mod = ($i % 2 );++$i;?>
                        <li <?php if(menuActive($to['name'])): ?>class="active"<?php endif; ?>><a href="<?php echo U($to['name']); ?>"><i class="<?php echo $to['icon']; ?>"></i><?php echo $to['title']; ?></a></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </li>
                <?php endif; endforeach; endif; else: echo "" ;endif; if($_SERVER['SERVER_NAME'] == 'tuiguang.51muma.com'): ?>
            <li <?php if(in_array($controller,['lock'])): ?>class="active"<?php endif; ?>>
            <a href="<?php echo U('lock/index'); ?>">
                <i class="fa fa-dashboard"></i>
                <span>授权管理</span>
            </a>
            </li>
            <?php endif; ?>

            <li class="header">其他功能</li>
            <li><a href="#">
                    <i class="fa fa-circle-o text-red"></i>
                    <span>帮助手册</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-circle-o text-yellow"></i>
                    <span>网站概况</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-circle-o text-aqua"></i>
                    <span>技术支持</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            网站管理
            <small>网站设置</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 网站管理</a></li>
            <li class="active">网站设置</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#base" data-toggle="tab">基本设置</a></li>
                        <li><a href="#seo" data-toggle="tab">SEO设置</a></li>
                        <li><a href="#email" data-toggle="tab">邮箱配置</a></li>
                        <li><a href="#credit" data-toggle="tab">奖励设置</a></li>
                        <li><a href="#task" data-toggle="tab">任务设置</a></li>
                        <li><a href="#study" data-toggle="tab">教程设置</a></li>
                    </ul>
                    <div class="page-content" style="padding: 20px 0">
                        <form method="post" class="form-horizontal form" id="post_form">
                            <div class="tab-content">
                                <div class="tab-pane active" id="base">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">网站名称</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[title]" value="<?php echo !empty($config['setting']['title'])?$config['setting']['title']:''; ?>" placeholder="请输入网站名称">
                                        </div>
                                    </div>
                                    <?php echo tpl_upload_image(['name'=>'setting[logo]','title' => '网站logo','value'=>!empty($config['setting']['logo'])?$config['setting']['logo']:'','placeholder'=>'请上传网站logo','help'=>'建议宽高：138*56']); ?>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">QQ群</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[qq]" value="<?php echo !empty($config['setting']['qq'])?$config['setting']['qq']:''; ?>" placeholder="请输入QQ群号码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">客服微信号</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[wechat]" value="<?php echo !empty($config['setting']['wechat'])?$config['setting']['wechat']:''; ?>" placeholder="请输入客服微信号码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">备案信息</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[copyright]" value="<?php echo !empty($config['setting']['copyright'])?$config['setting']['copyright']:''; ?>" placeholder="请输入备案信息">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">百度统计代码</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="setting[baidu_stat]" maxlength="1000" rows="5" placeholder="请输入百度统计代码"><?php echo !empty($config['setting']['baidu_stat'])?$config['setting']['baidu_stat']:''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">百度商桥代码</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="setting[baidu_chat]" maxlength="1000" rows="5" placeholder="请输入百度统计代码"><?php echo !empty($config['setting']['baidu_chat'])?$config['setting']['baidu_chat']:''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="seo">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SEO标题</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="setting[seo_title]" maxlength="255" placeholder="请输入SEO标题"><?php echo !empty($config['setting']['seo_title'])?$config['setting']['seo_title']:''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SEO关键词</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="setting[seo_keywords]" maxlength="255" placeholder="请输入SEO关键词"><?php echo !empty($config['setting']['seo_keywords'])?$config['setting']['seo_keywords']:''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SEO描述</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="setting[seo_description]" maxlength="255" placeholder="请输入SEO描述"><?php echo !empty($config['setting']['seo_description'])?$config['setting']['seo_description']:''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="email">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SMTP服务器</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[SMTP_HOST]" value="<?php echo !empty($config['setting']['SMTP_HOST'])?$config['setting']['SMTP_HOST']:''; ?>" placeholder="请输入SMTP服务器地址">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SMTP服务器端口</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[SMTP_PORT]" value="<?php echo !empty($config['setting']['SMTP_PORT'])?$config['setting']['SMTP_PORT']:''; ?>" placeholder="请输入端口号">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SMTP服务器用户名</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[SMTP_USER]" value="<?php echo !empty($config['setting']['SMTP_USER'])?$config['setting']['SMTP_USER']:''; ?>" placeholder="请输入用户名">
                                            <span class="help-block">邮箱地址</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">SMTP服务器密码</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[SMTP_PASS]" value="<?php echo !empty($config['setting']['SMTP_PASS'])?$config['setting']['SMTP_PASS']:''; ?>" placeholder="请输入密码">
                                            <span class="help-block">申请SMTP服务器的密码</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">发件人邮箱</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[FROM_EMAIL]" value="<?php echo !empty($config['setting']['FROM_EMAIL'])?$config['setting']['FROM_EMAIL']:''; ?>" placeholder="请输入邮箱地址">
                                            <span class="help-block">申请SMTP服务器的邮箱地址</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">发件人名称</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[FROM_NAME]" value="<?php echo !empty($config['setting']['FROM_NAME'])?$config['setting']['FROM_NAME']:''; ?>" placeholder="请输入姓名、公司名、发件人">
                                            <span class="help-block">邮件发送显示的发送人姓名、公司名称等</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">测试邮箱</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[TEST_EMAIL]" value="<?php echo !empty($config['setting']['TEST_EMAIL'])?$config['setting']['TEST_EMAIL']:''; ?>" placeholder="请输入测试收件人邮箱地址">
                                            <span class="help-block js-send-email" style="cursor: pointer;">点击发送测试邮件</span>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            //发送测试邮件
                                            $(".js-send-email").click(function () {
                                                message('正在发送','','info');
                                                var email = $(this).prev().val();
                                                $.post(
                                                    "<?php echo U('test/email'); ?>",
                                                    {email:email},
                                                    function (res) {
                                                        message(res.message,res.redirect,res.type)
                                                    },'json'
                                                );
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="tab-pane" id="credit">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请好友奖励</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">会员邀请好友</span>
                                                <input type="number" name="setting[invitation_first_task_award]" value="<?php echo !empty($config['setting']['invitation_first_task_award'])?$config['setting']['invitation_first_task_award']:'0'; ?>" class="form-control" placeholder="请输入邀请返利">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                            <span class="help-block">会员邀请好友第一次奖励多少金额，刷手做第一单任务后才会给奖励，关闭请设置为0</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请提现返利</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">提现时返利</span>
                                                <input type="number" name="setting[invitation_withdraw_rebate]" value="<?php echo !empty($config['setting']['invitation_withdraw_rebate'])?$config['setting']['invitation_withdraw_rebate']:'0'; ?>" class="form-control" placeholder="请输入邀请返利">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                            <span class="help-block">会员提现金额百分之几扣除给邀请上线，关闭请设置为0</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请好友返利</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">做任务奖励</span>
                                                <input type="number" name="setting[invitation_rebate]" value="<?php echo !empty($config['setting']['invitation_rebate'])?$config['setting']['invitation_rebate']:'0'; ?>" class="form-control" placeholder="请输入邀请返利">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                            <span class="help-block">下线做任务每份百分之几扣除给邀请上线</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">每日签到设置</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">每日签到送</span>
                                                <input type="number" name="setting[sign_give_credit1]" value="<?php echo !empty($config['setting']['sign_give_credit1'])?$config['setting']['sign_give_credit1']:''; ?>" class="form-control" placeholder="请输入金额">
                                                <span class="input-group-addon">积分</span>
                                            </div>
                                            <span class="help-block">每天每人限制1次</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">连续签到设置</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">连接签到倍数</span>
                                                <input type="number" name="setting[sign_continue_give]" value="<?php echo !empty($config['setting']['sign_continue_give'])?$config['setting']['sign_continue_give']:''; ?>" class="form-control" placeholder="请输入倍数">
                                            </div>
                                            <span class="help-block">例如，设置为2，第一天签到领2分,第二天签到4分，第三天6分，如果隔一天没签到，从头开始计算</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="task">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现最低金额</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">最低金额</span>
                                                <input type="number" name="setting[withdraw_min]" value="<?php echo !empty($config['setting']['withdraw_min'])?$config['setting']['withdraw_min']:0; ?>" class="form-control" placeholder="请输入服务费率">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                            <span class="help-block">如设置1，提现最低金额为：1元</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现手续费</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">提现金额的</span>
                                                <input type="number" name="setting[withdraw_fee]" value="<?php echo !empty($config['setting']['withdraw_fee'])?$config['setting']['withdraw_fee']:0; ?>" class="form-control" placeholder="请输入服务费率">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                            <span class="help-block">如设置10%，提现金额为：100元，则扣除100*10%=10元手续费</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">服务费</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">奖励金额的</span>
                                                <input type="number" name="setting[fee]" value="<?php echo !empty($config['setting']['fee'])?$config['setting']['fee']:0; ?>" class="form-control" placeholder="请输入服务费率">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                            <span class="help-block">如设置10%，发布任务的奖励金额为：100元，则扣除100*10%=10元手续费</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核周期</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="setting[period]" placeholder="请输入小时数，多个'#'隔开" value="<?php echo !empty($config['setting']['period'])?$config['setting']['period']:''; ?>">
                                            <span class="help-block">输入0代表免审核，1代表1小时，多个数字请用#分割，如0#6#12</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">限速频率</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="number" name="setting[speed]" value="<?php echo !empty($config['setting']['speed'])?$config['setting']['speed']:0; ?>" class="form-control" placeholder="请输入分钟数">
                                                <span class="input-group-addon">分钟</span>
                                            </div>
                                            <span class="help-block">如设置5，代表发布任务时可设置5分钟内限制的抢单人数</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台审核</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="setting[push_check]" value="1" <?php echo !empty($config['setting']['push_check'])?'checked':''; ?>>
                                                开启
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="setting[push_check]" value="0" <?php echo !empty($config['setting']['push_check'])?'':'checked'; ?>>
                                                关闭
                                            </label>
                                            <span class="help-block">开启后，发布的任务需要平台审核后才能显示</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">置顶费用</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="number" name="setting[top_fee]" value="<?php echo !empty($config['setting']['top_fee'])?$config['setting']['top_fee']:0; ?>" class="form-control" placeholder="请输入置顶费用">
                                                <span class="input-group-addon">元</span>
                                            </div>
                                            <span class="help-block">如设置15，置顶费用为每小时15元</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">置顶限时</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="number" name="setting[top_max_hour]" value="<?php echo !empty($config['setting']['top_max_hour'])?$config['setting']['top_max_hour']:0; ?>" class="form-control" placeholder="请输入置顶限时">
                                                <span class="input-group-addon">小时</span>
                                            </div>
                                            <span class="help-block">如设置5，最多能置顶5个小时</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">接单限时</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="number" name="setting[receive_order_limit_time]" value="<?php echo !empty($config['setting']['receive_order_limit_time'])?$config['setting']['receive_order_limit_time']:0; ?>" class="form-control" placeholder="请输入接单限时分钟数">
                                                <span class="input-group-addon">分钟</span>
                                            </div>
                                            <span class="help-block">如设置5，用户抢单后5分钟内未上传验证，抢单将自己删除</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核限时</label>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="number" name="setting[check_order_limit_time]" value="<?php echo !empty($config['setting']['check_order_limit_time'])?$config['setting']['check_order_limit_time']:0; ?>" class="form-control" placeholder="请输入审核限时">
                                                <span class="input-group-addon">小时</span>
                                            </div>
                                            <span class="help-block">如设置1，代表雇主发布任务后1小时内不审核任务，系统会自动审核通过，佣金进入用户帐号中</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="study">
                                    <?php echo tpl_ueditor(['title'=>'接任务教程','name'=>'setting[join_task_detail]','value'=>!empty($config['setting']['join_task_detail'])?$config['setting']['join_task_detail']:'']); ?>
                                    <?php echo tpl_ueditor(['title'=>'发任务教程','name'=>'setting[push_task_detail]','value'=>!empty($config['setting']['push_task_detail'])?$config['setting']['push_task_detail']:'']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-xs-12 col-sm-offset-2">
                                    <button type="button" name="submit" class="btn btn-primary">提交</button>
                                </div>
								<div>&#25042;&#20154;&#28304;&#30721;&#119;&#119;&#119;&#46;&#108;&#97;&#110;&#114;&#101;&#110;&#122;&#104;&#105;&#106;&#105;&#97;&#46;&#99;&#111;&#109;&#32;&#20840;&#31449;&#36164;&#28304;&#50;&#48;&#22359;&#20219;&#24847;&#19979;&#36733;</div>
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            //提交分类信息
                            $("button[name='submit']").click(function () {
                                $.post(
                                    window.location.href,
                                    $('#post_form').serialize(),
                                    function (res) {
                                        message(res.message,res.redirect,res.type)
                                    },'json'
                                );
                            });
                        });
                    </script>
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </section>
</div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0.0
                </div>
                <strong>Copyright &copy; 2018 <a target="_blank" href="http://www.1.com">人人帮</a>.</strong> All rights reserved.
				<div>&#25042;&#20154;&#28304;&#30721;&#119;&#119;&#119;&#46;&#108;&#97;&#110;&#114;&#101;&#110;&#122;&#104;&#105;&#106;&#105;&#97;&#46;&#99;&#111;&#109;&#32;&#20840;&#31449;&#36164;&#28304;&#50;&#48;&#22359;&#20219;&#24847;&#19979;&#36733;</div>
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
    </body>
</html>


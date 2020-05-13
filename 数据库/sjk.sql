-- MySQL dump 10.16  Distrib 10.1.31-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: web32
-- ------------------------------------------------------
-- Server version	10.1.31-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_administrator`
--

DROP TABLE IF EXISTS `tb_administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_administrator` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(20) DEFAULT NULL COMMENT '盐',
  `gender` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0保密，1男，2女',
  `level` int(11) DEFAULT '0' COMMENT '等级',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `is_check` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '0禁用',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `user_login` (`username`) USING BTREE,
  KEY `user_nickname` (`nickname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_administrator`
--

LOCK TABLES `tb_administrator` WRITE;
/*!40000 ALTER TABLE `tb_administrator` DISABLE KEYS */;
INSERT INTO `tb_administrator` VALUES (1,'admin','cef18bad9d10bd9eb1d1530c8b5b9de4','zSeSs7Q0',0,0,0,'','','','',1,'',0,1534598045,0),(3,'1055133613','36cbb1642701e0dfd545beb5f2dea827','koFgCSg4',0,0,0,'','','','',1,'',0,0,1534763797);
/*!40000 ALTER TABLE `tb_administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_auth_group`
--

DROP TABLE IF EXISTS `tb_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_auth_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `module` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '所属模块',
  `level` int(11) NOT NULL COMMENT '角色等级',
  `title` varchar(200) NOT NULL COMMENT '用户组中文名称',
  `status` tinyint(1) NOT NULL COMMENT '状态：为1正常，为0禁用',
  `rules` text COMMENT '用户组拥有的规则id， 多个规则","隔开',
  `notation` varchar(100) DEFAULT NULL COMMENT '组别描述',
  `pic` varchar(200) DEFAULT NULL COMMENT '角色图标',
  `recom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐首页显示',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_auth_group`
--

LOCK TABLES `tb_auth_group` WRITE;
/*!40000 ALTER TABLE `tb_auth_group` DISABLE KEYS */;
INSERT INTO `tb_auth_group` VALUES (1,'admin',1090,'超级管理员',1,'1,2,3,4,5,6,7,8,9,10,23,24,25,26,11,12,15,14,16,17,18,19,20,21,22','我能干任何事','#dd4b39',0,1502780231,1536659809),(2,'admin',1,'后台浏览',1,'1,2,3,4,5,10,23,24,15,14,16,19','只能查看列表','#f39c12',0,1502784113,1534808239),(3,'admin',0,'雇主',1,NULL,'',NULL,0,1536592696,0);
/*!40000 ALTER TABLE `tb_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_auth_group_access`
--

DROP TABLE IF EXISTS `tb_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_auth_group_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `group_id` int(11) unsigned NOT NULL COMMENT '用户组id',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_auth_group_access`
--

LOCK TABLES `tb_auth_group_access` WRITE;
/*!40000 ALTER TABLE `tb_auth_group_access` DISABLE KEYS */;
INSERT INTO `tb_auth_group_access` VALUES (3,1,1,0,0),(6,3,2,0,0);
/*!40000 ALTER TABLE `tb_auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_auth_rule`
--

DROP TABLE IF EXISTS `tb_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(11) unsigned NOT NULL COMMENT '父id',
  `module` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '权限节点所属模块',
  `level` tinyint(1) NOT NULL COMMENT '1-项目;2-模块;3-操作',
  `name` varchar(80) NOT NULL COMMENT '规则唯一标识',
  `title` varchar(20) NOT NULL COMMENT '规则中文名称',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `ismenu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `condition` varchar(200) DEFAULT NULL COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `icon` varchar(50) DEFAULT NULL COMMENT '节点图标',
  `sorts` mediumint(8) DEFAULT '50' COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `module` (`module`) USING BTREE,
  KEY `level` (`level`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_auth_rule`
--

LOCK TABLES `tb_auth_rule` WRITE;
/*!40000 ALTER TABLE `tb_auth_rule` DISABLE KEYS */;
INSERT INTO `tb_auth_rule` VALUES (1,0,'admin',1,'Index/index','后台首页',1,1,1,NULL,'fa fa-home',1000,1532168003,1532169104),(2,0,'admin',1,'index','系统设置',1,1,1,NULL,'fa fa-dashboard',1200,1532168495,1532169112),(3,2,'admin',2,'index/index','网站设置',1,1,1,NULL,'fa fa-circle-o',1,1532168566,1532169121),(4,2,'admin',2,'index/profile','资料设置',1,1,1,NULL,'fa fa-circle-o',2,1532169226,0),(5,2,'admin',2,'index/uploads','文件管理',1,1,1,NULL,'fa fa-circle-o',3,1532169267,0),(6,0,'admin',1,'auth','权限管理',1,1,1,NULL,'fa fa-users',1300,1532177458,0),(7,6,'admin',2,'auth_admin/index','管理员列表',1,1,1,NULL,'fa fa-user-o',1,1532177508,1532189126),(8,6,'admin',2,'auth_group/index','角色列表',1,1,1,NULL,'fa fa-vcard',2,1532177534,1532188696),(9,6,'admin',2,'auth_rule/index','菜单规则列表',1,1,1,NULL,'fa fa-user-circle',3,1532177559,1532188705),(10,0,'admin',1,'member','会员管理',1,1,1,NULL,'fa fa-users',1400,1532177701,1532241958),(11,0,'admin',1,'invitation/index','邀请管理',1,1,1,NULL,'fa fa-users',1500,1532177768,0),(12,0,'admin',1,'task','任务管理',1,1,1,NULL,'fa fa-dashboard',1600,1532177915,0),(14,12,'admin',2,'taskjoin/index','任务数据',1,1,1,NULL,'fa fa-circle-o',3,1532177961,0),(15,12,'admin',2,'task/index','任务列表',1,1,1,NULL,'fa fa-circle-o',2,1532178005,0),(16,12,'admin',2,'taskcategory/index','分类列表',1,1,1,NULL,'fa fa-circle-o',4,1532178030,0),(17,0,'admin',1,'channel/index','渠道管理',1,1,1,NULL,'fa fa-dashboard',1700,1532178159,0),(18,0,'admin',1,'recharge/index','充值管理',1,1,1,NULL,'fa fa-dashboard',1800,1532178193,0),(19,0,'admin',1,'withdraw/index','提现管理',1,1,1,NULL,'fa fa-dashboard',1900,1532178216,0),(20,0,'admin',1,'notice/index','公告管理',1,1,1,NULL,'fa fa-home',2000,1532178266,0),(21,0,'admin',1,'banner/index','轮播图管理',1,1,1,NULL,'fa fa-dashboard',2100,1532178294,0),(22,0,'admin',1,'feedback/index','反馈管理',1,1,1,NULL,'fa fa-dashboard',2200,1532178317,0),(23,10,'admin',2,'member/index','会员列表',1,1,1,NULL,'fa fa-circle-o',1,1532241379,0),(24,10,'admin',2,'member/charge','充值记录',1,1,1,NULL,'fa fa-circle-o',2,1532241433,0),(25,10,'admin',2,'credit_record/index','资金流水',1,1,1,NULL,'fa fa-circle-o',50,1536659743,0),(26,2,'admin',2,'pay/index','充值配置',1,1,1,NULL,'fa fa-circle-o',50,1565877365,1565877631);
/*!40000 ALTER TABLE `tb_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_banner`
--

DROP TABLE IF EXISTS `tb_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_banner` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `order_by` int(10) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order_by` (`is_display`,`order_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='轮播图';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_banner`
--

LOCK TABLES `tb_banner` WRITE;
/*!40000 ALTER TABLE `tb_banner` DISABLE KEYS */;
INSERT INTO `tb_banner` VALUES (1,'1','20200116/2b609c38cbea6414182b05900eb24668.png','http://huzhu.cocogo.xyz',3,1,1579157669,1524738575);
/*!40000 ALTER TABLE `tb_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_channel`
--

DROP TABLE IF EXISTS `tb_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '渠道名',
  `order_by` int(11) DEFAULT '0',
  `is_display` tinyint(3) DEFAULT '0' COMMENT '1显示',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_channel`
--

LOCK TABLES `tb_channel` WRITE;
/*!40000 ALTER TABLE `tb_channel` DISABLE KEYS */;
INSERT INTO `tb_channel` VALUES (4,'QQ',0,1,0,1520213049),(5,'111',0,1,0,1565937567);
/*!40000 ALTER TABLE `tb_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_charge`
--

DROP TABLE IF EXISTS `tb_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作管理员ID，0代表系统',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'credit1积分，credit2余额',
  `num` decimal(10,2) DEFAULT '0.00' COMMENT '充值数量，负数代表减少',
  `remark` varchar(200) CHARACTER SET utf8 NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_charge`
--

LOCK TABLES `tb_charge` WRITE;
/*!40000 ALTER TABLE `tb_charge` DISABLE KEYS */;
INSERT INTO `tb_charge` VALUES (50,1,181,'credit2',1000.00,'管理员后台操作，充值1000余额。',1579162922),(51,1,183,'credit2',100.00,'管理员后台操作，充值100余额。',1579172674);
/*!40000 ALTER TABLE `tb_charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_code`
--

DROP TABLE IF EXISTS `tb_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) CHARACTER SET utf8 NOT NULL,
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `code` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '验证码',
  `status` tinyint(3) DEFAULT '0' COMMENT '1已使用',
  `ip` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'IP地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_code`
--

LOCK TABLES `tb_code` WRITE;
/*!40000 ALTER TABLE `tb_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_config`
--

DROP TABLE IF EXISTS `tb_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` text COMMENT '设置选项，序列化存储',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='任务配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_config`
--

LOCK TABLES `tb_config` WRITE;
/*!40000 ALTER TABLE `tb_config` DISABLE KEYS */;
INSERT INTO `tb_config` VALUES (1,'a:34:{s:5:\"title\";s:9:\"人人帮\";s:4:\"logo\";s:45:\"20180814/85e207722bf9bfb3aa3fabb5b7bd047c.png\";s:2:\"qq\";s:6:\"250000\";s:6:\"wechat\";s:6:\"a72670\";s:9:\"copyright\";s:17:\"@2018京-10000100\";s:10:\"baidu_stat\";s:0:\"\";s:10:\"baidu_chat\";s:0:\"\";s:9:\"seo_title\";s:80:\"人人帮-微信投票|微信助力|APP下载|游戏推广|营销推广任务墙\";s:12:\"seo_keywords\";s:34:\"懒人源码www.lanrenzhijia.com\";s:15:\"seo_description\";s:183:\"人人帮推广是一站式的移动端任务式赚钱营销推广服务平台，提供微信拉票、微信助力、朋友圈转发、APP下载推广、游戏网贷理财推广等！\";s:9:\"SMTP_HOST\";s:0:\"\";s:9:\"SMTP_PORT\";s:0:\"\";s:9:\"SMTP_USER\";s:0:\"\";s:9:\"SMTP_PASS\";s:0:\"\";s:10:\"FROM_EMAIL\";s:0:\"\";s:9:\"FROM_NAME\";s:6:\"系统\";s:10:\"TEST_EMAIL\";s:0:\"\";s:27:\"invitation_first_task_award\";s:3:\"0.1\";s:26:\"invitation_withdraw_rebate\";s:1:\"0\";s:17:\"invitation_rebate\";s:2:\"10\";s:17:\"sign_give_credit1\";s:2:\"20\";s:18:\"sign_continue_give\";s:1:\"3\";s:12:\"withdraw_min\";s:1:\"5\";s:12:\"withdraw_fee\";s:2:\"10\";s:3:\"fee\";s:1:\"1\";s:6:\"period\";s:9:\"0#6#12#24\";s:5:\"speed\";s:1:\"6\";s:10:\"push_check\";s:1:\"0\";s:7:\"top_fee\";s:1:\"3\";s:12:\"top_max_hour\";s:1:\"5\";s:24:\"receive_order_limit_time\";s:1:\"5\";s:22:\"check_order_limit_time\";s:1:\"2\";s:16:\"join_task_detail\";s:1372:\"<h5>1、点击一个任务。</h5><p><img alt=\"1517191346729689.png\" src=\"/ueditor/php/upload/image/20180129/1517191346729689.png\"/></p><h5>2、浏览完任务需求后点击 “抢单” 按键，若跳转到做任务界面则抢单成功，如果失败则是目前单数已满。请在抢单后的两小时内完成任务，逾期则自动解锁。</h5><p><img title=\"1517191382134000.png\" alt=\"help2.png\" src=\"/ueditor/php/upload/image/20180129/1517191382134000.png\"/></p><h5>3、跳转到做任务页面，在文本框写上任务需要的文字内容，如果任务有要求的话，还需要上传相应的图片，一切都弄好之后点击右上角的发布，静待文件上传成功后自动跳转。</h5><p><img title=\"1517191394899149.png\" alt=\"help3.png\" src=\"/ueditor/php/upload/image/20180129/1517191394899149.png\"/></p><h5>4、如果抢单后发现自己不符合任务需求，则退回任务详情页，点击解锁即可，如果不点击而又不在两小时内上传任务相关内容的话，系统将会自动解锁。</h5><p><img title=\"1517191401639366.png\" alt=\"help4.png\" src=\"/ueditor/php/upload/image/20180129/1517191401639366.png\"/></p><h5>5、做过的、正在做的任务可以在个人中心的参与中查看。</h5><p><img title=\"1517191411866530.png\" alt=\"help5.png\" src=\"/ueditor/php/upload/image/20180129/1517191411866530.png\"/></p>\";s:16:\"push_task_detail\";s:1599:\"<h5>1、点击页面下方发布按键。</h5><p><img title=\"1517191633304320.png\" alt=\"help6.png\" src=\"/ueditor/php/upload/image/20180129/1517191633304320.png\"/></p><h5>2、根据发布任务的要求填写相关内容。</h5><p><img title=\"1517191637329752.png\" alt=\"help7.png\" src=\"/ueditor/php/upload/image/20180129/1517191637329752.png\"/></p><h5>3、点击右上角的发布。如果页面跳转则发布成功，若发布失败，则需根据弹出的错误信息进行相应的修改。</h5><p><img title=\"1517191642115923.png\" alt=\"help8.png\" src=\"/ueditor/php/upload/image/20180129/1517191642115923.png\"/></p><h5>4、发布过的任务可以在“发布”中查看。</h5><p><img title=\"1517191647505020.png\" alt=\"help9.png\" src=\"/ueditor/php/upload/image/20180129/1517191647505020.png\"/></p><h5>5、点击在进行中的任务。</h5><p><img title=\"1517191652117756.png\" alt=\"help10.png\" src=\"/ueditor/php/upload/image/20180129/1517191652117756.png\"/></p><h5>6、在发布任务的任务详情里顶部的审核按键或者直接在底部皆可审核任务。</h5><p><img title=\"1517191660163219.png\" alt=\"help11.png\" src=\"/ueditor/php/upload/image/20180129/1517191660163219.png\"/></p><h5>7、如果审核不通过的话要写明不通过的理由，方便推手进行修改。</h5><p><img title=\"1517191670623745.png\" alt=\"help12.png\" src=\"/ueditor/php/upload/image/20180129/1517191670623745.png\"/></p><h5>8、在任务详情里还可以进行额外的操作。</h5><p><img title=\"1517191675786750.png\" alt=\"help13.png\" src=\"/ueditor/php/upload/image/20180129/1517191675786750.png\"/></p>\";}',1568015798,1516952068),(2,'a:34:{s:5:\"title\";s:18:\"互帮互助平台\";s:4:\"logo\";s:45:\"20200116/94a5176dfb6d40d82d500425370627a0.png\";s:2:\"qq\";s:9:\"727444088\";s:6:\"wechat\";s:10:\"cocovip555\";s:9:\"copyright\";s:0:\"\";s:10:\"baidu_stat\";s:0:\"\";s:10:\"baidu_chat\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";s:9:\"SMTP_HOST\";s:0:\"\";s:9:\"SMTP_PORT\";s:0:\"\";s:9:\"SMTP_USER\";s:0:\"\";s:9:\"SMTP_PASS\";s:0:\"\";s:10:\"FROM_EMAIL\";s:0:\"\";s:9:\"FROM_NAME\";s:0:\"\";s:10:\"TEST_EMAIL\";s:0:\"\";s:27:\"invitation_first_task_award\";s:1:\"0\";s:26:\"invitation_withdraw_rebate\";s:1:\"0\";s:17:\"invitation_rebate\";s:1:\"1\";s:17:\"sign_give_credit1\";s:1:\"1\";s:18:\"sign_continue_give\";s:0:\"\";s:12:\"withdraw_min\";s:1:\"0\";s:12:\"withdraw_fee\";s:1:\"0\";s:3:\"fee\";s:1:\"0\";s:6:\"period\";s:0:\"\";s:5:\"speed\";s:1:\"0\";s:10:\"push_check\";s:1:\"0\";s:7:\"top_fee\";s:2:\"10\";s:12:\"top_max_hour\";s:1:\"5\";s:24:\"receive_order_limit_time\";s:1:\"0\";s:22:\"check_order_limit_time\";s:2:\"10\";s:16:\"join_task_detail\";s:0:\"\";s:16:\"push_task_detail\";s:0:\"\";}',1579168196,1579158512);
/*!40000 ALTER TABLE `tb_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_credit_record`
--

DROP TABLE IF EXISTS `tb_credit_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_credit_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` enum('credit1','credit2') NOT NULL COMMENT 'credit1积分，credit2余额',
  `num` decimal(10,2) DEFAULT '0.00' COMMENT '充值数量，负数代表减少',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `remark` varchar(200) NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `uid_2` (`uid`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=908 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_credit_record`
--

LOCK TABLES `tb_credit_record` WRITE;
/*!40000 ALTER TABLE `tb_credit_record` DISABLE KEYS */;
INSERT INTO `tb_credit_record` VALUES (893,181,'credit2',1000.00,'后台会员充值','管理员后台操作，充值1000余额。',1579162922),(894,181,'credit2',-100.00,'发布任务','任务[28]-关注coco视频微信公众号发布成功，扣除100余额。',1579163023),(895,181,'credit2',-10.00,'发布任务','任务[29]-点击广告并截图发布成功，扣除10余额。',1579164004),(896,181,'credit2',-10.00,'发布任务','任务[30]-点击广告并截图发布成功，扣除10余额。',1579164007),(897,181,'credit2',10.00,'任务到期','任务[30]-点击广告并截图到期处理，退回10积分。',1579166187),(898,181,'credit2',-16.00,'发布任务','任务[31]-https://mp.weixin.qq.com/s/twjDzBcFDyYV5p-N86dn0A发布成功，扣除16余额。',1579166291),(899,181,'credit2',16.00,'任务到期','任务[31]-https://mp.weixin.qq.com/s/twjDzBcFDyYV5p-N86dn0A到期处理，退回16积分。',1579166306),(900,181,'credit2',1.00,'签到','签到成功，获得1积分。',1579166320),(901,181,'credit2',2.00,'签到','您已连续1天签到，额外获得2积分。',1579166320),(902,183,'credit2',-10.00,'发布任务','任务[32]-关注coco视频公众号发布成功，扣除10余额。',1579167446),(903,183,'credit2',100.00,'后台会员充值','管理员后台操作，充值100余额。',1579172674),(904,183,'credit2',-15.00,'发布任务','任务[33]-点击文中广告并截图发布成功，扣除15余额。',1579172773),(905,184,'credit2',1.00,'签到','签到成功，获得1积分。',1579186395),(906,184,'credit2',1.50,'审核任务','任务[33]-点击文中广告并截图审核成功，奖励1.50积分。',1579186553),(907,184,'credit2',1.00,'审核任务','任务[32]-关注coco视频公众号审核成功，奖励1.00积分。',1579186566);
/*!40000 ALTER TABLE `tb_credit_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_feedback`
--

DROP TABLE IF EXISTS `tb_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '反馈id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '反馈内容',
  `ip` varchar(20) NOT NULL DEFAULT '0' COMMENT 'ip地址',
  `ip_addr` varchar(200) NOT NULL DEFAULT '' COMMENT '地理位置',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回复',
  `son` int(11) NOT NULL DEFAULT '0' COMMENT '子反馈数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`parent_id`),
  KEY `update_time` (`update_time`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_feedback`
--

LOCK TABLES `tb_feedback` WRITE;
/*!40000 ALTER TABLE `tb_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_follows`
--

DROP TABLE IF EXISTS `tb_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `follow_uid` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_follows`
--

LOCK TABLES `tb_follows` WRITE;
/*!40000 ALTER TABLE `tb_follows` DISABLE KEYS */;
INSERT INTO `tb_follows` VALUES (2,123,3,1534752719,1534752719),(3,3,123,1534938105,1534938105),(4,143,3,1535113569,1535113569),(5,148,3,1535964838,1535964838),(6,165,123,1540916920,1540916920),(7,171,123,1540972475,1540972475),(8,184,183,1579173021,1579173021);
/*!40000 ALTER TABLE `tb_follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_invitation_code`
--

DROP TABLE IF EXISTS `tb_invitation_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_invitation_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='邀请码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_invitation_code`
--

LOCK TABLES `tb_invitation_code` WRITE;
/*!40000 ALTER TABLE `tb_invitation_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_invitation_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_invitation_log`
--

DROP TABLE IF EXISTS `tb_invitation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_invitation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `invite_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请用户id',
  `invite_username` varchar(50) NOT NULL DEFAULT '' COMMENT '邀请用户名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `invite_uid` (`invite_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='邀请记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_invitation_log`
--

LOCK TABLES `tb_invitation_log` WRITE;
/*!40000 ALTER TABLE `tb_invitation_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_invitation_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_invitation_rebate_record`
--

DROP TABLE IF EXISTS `tb_invitation_rebate_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_invitation_rebate_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值数量，负数代表减少',
  `task_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `remark` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_invitation_rebate_record`
--

LOCK TABLES `tb_invitation_rebate_record` WRITE;
/*!40000 ALTER TABLE `tb_invitation_rebate_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_invitation_rebate_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_lock`
--

DROP TABLE IF EXISTS `tb_lock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_lock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '网站名称',
  `domain` varchar(255) DEFAULT NULL COMMENT '授权域名',
  `is_forever` tinyint(3) DEFAULT '0' COMMENT '1代表永久授权',
  `to_date` int(11) DEFAULT NULL COMMENT '到期时间戳',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_lock`
--

LOCK TABLES `tb_lock` WRITE;
/*!40000 ALTER TABLE `tb_lock` DISABLE KEYS */;
INSERT INTO `tb_lock` VALUES (2,'测试域名','tuiguang.com',1,1525151700,1522850473,1522646138),(4,'萌手赚网','www.heimengw.cn',1,1522981620,NULL,1522981699),(5,'测试域名','localhost',1,1525151700,1522850473,1522646138);
/*!40000 ALTER TABLE `tb_lock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_member`
--

DROP TABLE IF EXISTS `tb_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `gender` tinyint(3) DEFAULT '0',
  `parent_uid` int(11) DEFAULT '0' COMMENT '上级ID',
  `experience_value` decimal(10,2) DEFAULT '0.00' COMMENT '经验值',
  `level` int(11) DEFAULT '1' COMMENT '等级',
  `invitation_code` varchar(20) DEFAULT NULL COMMENT '邀请码/手机号',
  `oath_type` tinyint(3) DEFAULT '0' COMMENT '0默认，1微信，2QQ，3微博，4支付宝等',
  `openid` varchar(255) DEFAULT NULL COMMENT '第三方openid',
  `credit1` decimal(10,2) DEFAULT '0.00' COMMENT '积分',
  `credit2` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `salt` varchar(8) DEFAULT NULL COMMENT '盐',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `is_bind_email` tinyint(3) DEFAULT '0' COMMENT '1已绑定',
  `alipay_account` varchar(50) DEFAULT NULL COMMENT '支付宝账号',
  `alipay_realname` varchar(50) DEFAULT NULL COMMENT '支付宝真实姓名',
  `is_bind_alipay` tinyint(1) DEFAULT '0' COMMENT '1已绑定支付宝',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机',
  `is_bind_mobile` tinyint(3) DEFAULT '0' COMMENT '1绑定手机',
  `channel_id` int(11) DEFAULT '0' COMMENT '渠道登记',
  `channel_name` varchar(50) DEFAULT NULL COMMENT '渠道名称',
  `channel_desc` varchar(500) DEFAULT NULL COMMENT '渠道描述',
  `is_bind_channel` tinyint(3) DEFAULT '0' COMMENT '1已登记渠道',
  `is_check` tinyint(3) DEFAULT '1' COMMENT '0禁用',
  `is_del` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除标识',
  `invite_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请总人数',
  `invite_rebate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '邀请总返利收益',
  `sign` varchar(30) NOT NULL DEFAULT '' COMMENT '随机签名',
  `sign_continue` int(10) unsigned DEFAULT '0' COMMENT '连续签到天数',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_member`
--

LOCK TABLES `tb_member` WRITE;
/*!40000 ALTER TABLE `tb_member` DISABLE KEYS */;
INSERT INTO `tb_member` VALUES (181,'13216842244',0,0,0.00,1,NULL,0,NULL,2.00,901.00,'fbccb01d97ce2be48c08f4c719aec99a','VYmXbQ6b',NULL,'',0,NULL,NULL,0,NULL,0,0,NULL,NULL,0,1,1,0,0.00,'',1,1579167195,1579161576),(182,'18659652028',0,0,0.00,1,NULL,0,NULL,0.00,10.00,'1fb35ac140a28427382938f0b02ae3ce','CfEt6Bno',NULL,'',0,NULL,NULL,0,NULL,0,0,NULL,NULL,0,1,1,0,0.00,'',0,1579167195,1579166445),(183,'aaa123',0,0,0.00,1,NULL,0,NULL,0.00,85.00,'39f0f5bd0dca9adf0d6a0e3633be6bfb','n23F3c33',NULL,'',0,NULL,NULL,0,NULL,0,0,NULL,NULL,0,1,0,0,0.00,'',0,1579172773,1579167307),(184,'bbb123',0,0,0.00,1,NULL,0,NULL,0.00,13.50,'ee1850b3668de1bb553dbf50f6ab7af3','ugRjeZ5u','20200116/fb9b9c886526276fa5d6d91db350f9a7.jpg','',0,NULL,NULL,0,NULL,0,0,NULL,NULL,0,1,0,0,0.00,'',1,1579186566,1579172895);
/*!40000 ALTER TABLE `tb_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_notice`
--

DROP TABLE IF EXISTS `tb_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `detail` text,
  `order_by` int(11) DEFAULT '0',
  `is_display` tinyint(3) DEFAULT '1',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='公告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_notice`
--

LOCK TABLES `tb_notice` WRITE;
/*!40000 ALTER TABLE `tb_notice` DISABLE KEYS */;
INSERT INTO `tb_notice` VALUES (2,'平台运营初期，人数较少，需要大家积极参与，营造一个良好的互助共享平台。','<p>平台运营初期，人数较少，需要大家积极参与，营造一个良好的互助共享平台。</p>',0,1,1579156484,1536230347);
/*!40000 ALTER TABLE `tb_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_pay`
--

DROP TABLE IF EXISTS `tb_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_pay` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(80) NOT NULL COMMENT '通道名称',
  `Identification` varchar(80) NOT NULL COMMENT '通道标识',
  `configure` text NOT NULL COMMENT '通道参数',
  `order_by` int(3) NOT NULL COMMENT '排序',
  `is_display` int(1) NOT NULL COMMENT '状态',
  `app_id` varchar(120) NOT NULL COMMENT '商户appid',
  `url` varchar(255) NOT NULL,
  `alipay_public_key` text NOT NULL COMMENT '支付宝公钥',
  `merchant_private_key` text NOT NULL COMMENT '支付宝商户私钥',
  `appserver` varchar(120) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_pay`
--

LOCK TABLES `tb_pay` WRITE;
/*!40000 ALTER TABLE `tb_pay` DISABLE KEYS */;
INSERT INTO `tb_pay` VALUES (1,'支付宝','alipay','appid:2018060560255790\r\nmerchant_private_key:MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCL2+mooXA4dUdeJJSwQHN/hB/jlmrRbbdxB4rg5fMhM32rokDrkzeuIeeRzvlC4SAafR1Mz+Z1Dzrr06w6UL08OTL8j8xX2W1kZI30+MkAteJ5aaAVpEsMkOgm2iPk5L9iPc+BvsuvVbY7MIUxtjKNdOkUitwRGG',0,1,'2018060560255790','https://openhome.alipay.com/platform/keyManage.htm','MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi9vpqKFwOHVHXiSUsEBzf4Qf45Zq0W23cQeK4OXzITN9q6JA65M3riHnkc75QuEgGn0dTM/mdQ8669OsOlC9PDky/I/MV9ltZGSN9PjJALXieWmgFaRLDJDoJtoj5OS/Yj3Pgb7Lr1W2OzCFMbYyjXTpFIrcERhi3/It1EZSLlALvsxRSAxK82FSfTFrbRQWFIKvDLiZraWediivhLpyi+Gq8rg61KCCNLpUzmYWsr5LZWaRIC2g7yZE5YT0HEHId0vVmOVpbwv8CZhXDK+KxG5JI7RSdgwrKf2CtW2PSKyK02P+S8tRk+8Z/NzGOO0mIkmu0C48yAyoMt8vN2qkiwIDAQAB','MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCL2+mooXA4dUdeJJSwQHN/hB/jlmrRbbdxB4rg5fMhM32rokDrkzeuIeeRzvlC4SAafR1Mz+Z1Dzrr06w6UL08OTL8j8xX2W1kZI30+MkAteJ5aaAVpEsMkOgm2iPk5L9iPc+BvsuvVbY7MIUxtjKNdOkUitwRGGLf8i3URlIuUAu+zFFIDErzYVJ9MWttFBYUgq8MuJmtpZ52KK+EunKL4aryuDrUoII0ulTOZhayvktlZpEgLaDvJkTlhPQcQch3S9WY5WlvC/wJmFcMr4rEbkkjtFJ2DCsp/YK1bY9IrIrTY/5Ly1GT7xn83MY47SYiSa7QLjzIDKgy3y83aqSLAgMBAAECggEAOelgslvOvQILADeDfgviB14tWi7RolCdEed+oSt2ZjwNAIHaAfHer3MIkT6zxfa0NWOzOzgnBDe/PSFUAn2mLga9Twk4IvQ8MMLWaSaPDIVD9uQ+zldOYDCsgFH5ZPE3MjXH88COVNbX82Be9rur6RkM3l21TDrdzj9YrLpFkzkhrUAUbFtKtfLCSefrva/0nZEuwXWrKpmbxruElQtjJmczN9Mam/BYbb9NB54IgPLpXtmfHsXQ7HweWD/e2au3rSFwlkdGrCUdb3XjojF5ZzLj6VvCApo71BRsWJOtw2xTn9lsGGzC5YIWY1ZqYrBbSGKMHnHmwMawb+CS6HT2yQKBgQDZ/1O1bWSe3BUu7HjNa6Q2MypNtNWIpTyJNwgZewjJa6UxxFJoPxG0NVo82fPzapDM+JTJgh8dLPW4H/BI1+b3qQ92F0Fi1tNntcat8pi6VV4dxEJKAOyjIqCJ4S/69NhxYSNZA5w6cZ7+LSDRuNTIdLAii+evyqbnrUOtcwEWrQKBgQCkPXgCFQOVU09Exllqn9lgkZX/YOud7kgP0az7zWnZorMm3QBg+/XMI2U8q9gayE0X6/JpMVDm4lbJMzdXYNHuPWtYf/jmhC+fFfNFiexxNF+2bnBGYFkZxAYOW0hSMmUaYOnAzR+xEbgnJ/WPMRm77CCdGKnul9JSTbtnazpnFwKBgHeC9Ahd3bD95RshhyTPI2qXaFTLk9ljBSoQon8dpXaPbjQ3dhoyoWkTatI7hvNm89V7Xk6O6LHdCSUVVW0J+FTEOXa7Txx5u9J6pF6Oxk45KOzWwKTDlvfkrvCIJP7HJrYZ1AAj641a5xhf80MmunjfCAUYgD8usYwHwDeh+fHNAoGAbmhD/GJT/lX6u6j7Awph/uDfjMWCnrBIERpKxxrXRU5yUHXQg2HdYlWJALgklhyAdsxOMRjN4efVn3umgD694QG4381nbM7/lFoVJ3IIWDF1BhZHs4ehXgjAaXZDr73g6VKs0McTvtzChs/96zx+qC2b3v9tfM7ivCE1EdirchECgYEAwjQy+ZxIYtL7OxxSe+We8qHChAgIGJdOskJExcaEHsf4ydVg1f9nuQILOOMNKrBL9I3u+xzvNxMsHv4T6CDTC3J5ZYWmja1I3N3bUKzLadXnnxY1O6IM6cCbrl5EUpamhjWnwYpfMjyIfYtqT2gtdE+xBT2BHMBOcwIgiGfYQtM=','',1565881064,1566576938),(2,'微信支付','wxpay','appid:wx3944fcf8bf46d14c\r\nGetAppSecret:08521a982a98a235878c493b2d745980\r\nMerchantId:1234709902\r\nKey:08521a982a98a235878c493b2d788888',0,1,'','','','','',1565882836,1565883705);
/*!40000 ALTER TABLE `tb_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_recharge`
--

DROP TABLE IF EXISTS `tb_recharge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `credit2` decimal(10,2) DEFAULT '0.00' COMMENT '充值金额',
  `pay_method` tinyint(3) DEFAULT '0' COMMENT '0银行卡，1支付宝，2微信',
  `account` varchar(50) DEFAULT NULL COMMENT '账号',
  `realname` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `pay_time` varchar(50) DEFAULT NULL COMMENT '充值时间',
  `thumbs` varchar(255) NOT NULL DEFAULT '' COMMENT '充值截图',
  `status` tinyint(3) DEFAULT '0' COMMENT '0待审核，1已发放，-1审核未通过',
  `note` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_recharge`
--

LOCK TABLES `tb_recharge` WRITE;
/*!40000 ALTER TABLE `tb_recharge` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_recharge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_sign`
--

DROP TABLE IF EXISTS `tb_sign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `credit1` decimal(10,2) DEFAULT '0.00' COMMENT '赠送的积分',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid_create_time` (`uid`,`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='签到表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_sign`
--

LOCK TABLES `tb_sign` WRITE;
/*!40000 ALTER TABLE `tb_sign` DISABLE KEYS */;
INSERT INTO `tb_sign` VALUES (146,97,20.00,1529771498),(147,96,20.00,1529836860),(148,3,20.00,1530070815),(149,98,20.00,1530179685),(150,98,20.00,1530231794),(151,3,20.00,1530435205),(152,102,20.00,1530777458),(153,104,20.00,1531150541),(154,106,20.00,1531248603),(155,117,20.00,1532140255),(156,3,20.00,1532153864),(157,3,20.00,1532483534),(158,118,20.00,1532567739),(159,120,20.00,1532781159),(160,3,20.00,1532921721),(161,121,20.00,1532930446),(162,122,20.00,1532941009),(163,123,20.00,1533000821),(164,121,20.00,1533174135),(165,126,20.00,1533182434),(166,123,20.00,1533205353),(167,127,20.00,1533240364),(168,3,20.00,1533467156),(169,123,20.00,1533882266),(170,132,20.00,1533908509),(171,132,20.00,1534035265),(172,121,20.00,1534482008),(173,3,20.00,1534561094),(174,3,20.00,1534769911),(175,123,20.00,1534776001),(176,123,20.00,1534807915),(177,3,20.00,1534811957),(178,3,20.00,1534938513),(179,141,20.00,1535068361),(180,3,20.00,1535072047),(181,3,20.00,1535259051),(182,3,20.00,1535497878),(183,3,20.00,1535649847),(184,147,20.00,1535691416),(185,123,20.00,1535729730),(186,123,20.00,1535816758),(187,123,20.00,1535948454),(188,148,20.00,1535964770),(189,123,20.00,1536209076),(190,156,20.00,1536382008),(191,3,20.00,1536681685),(192,161,20.00,1536837268),(193,159,20.00,1536928433),(194,165,20.00,1537079984),(195,123,20.00,1537086402),(196,123,20.00,1537349523),(197,166,20.00,1537589243),(198,123,20.00,1538884376),(199,170,20.00,1539155811),(200,123,20.00,1540915742),(201,171,20.00,1540973313),(202,123,20.00,1541042058),(203,172,20.00,1564802311),(204,173,20.00,1564825170),(205,123,20.00,1565631123),(206,181,1.00,1579166320),(207,184,1.00,1579186395);
/*!40000 ALTER TABLE `tb_sign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_task`
--

DROP TABLE IF EXISTS `tb_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '会员ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `detail` text,
  `category_id` int(11) DEFAULT '0' COMMENT '类别ID',
  `start_time` int(11) DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) DEFAULT '0' COMMENT '结束时间',
  `ticket_num` int(11) DEFAULT '0' COMMENT '票数',
  `join_num` int(11) DEFAULT '0' COMMENT '已加入的票数',
  `unit_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '任务单价',
  `give_credit1` int(10) unsigned DEFAULT '0' COMMENT '奖励积分',
  `give_credit2` decimal(10,2) DEFAULT '0.00' COMMENT '奖励金额',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '任务总金额',
  `top_hour` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '置顶小时',
  `top_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '置顶时间',
  `top_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '置顶费用',
  `top_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '置顶结束时间',
  `check_period` int(11) DEFAULT '0' COMMENT '审核周期，小时',
  `check_period_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核周期时间',
  `about_url` varchar(255) DEFAULT NULL COMMENT '相关地址',
  `check_text_content` varchar(255) NOT NULL DEFAULT '' COMMENT '文字验证',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `is_screenshot` tinyint(3) DEFAULT '0' COMMENT '1需要截图',
  `is_ip_restriction` tinyint(3) DEFAULT '0' COMMENT '1限制IP',
  `province` varchar(20) DEFAULT NULL COMMENT '限制省份',
  `rate` tinyint(3) DEFAULT '0' COMMENT '0默认，1仅限一次，2每天一次，3定时任务',
  `interval_hour` int(11) DEFAULT '0' COMMENT '间隔时间',
  `is_limit_speed` tinyint(3) DEFAULT '0' COMMENT '1限速',
  `limit_ticket_num` int(11) DEFAULT '0' COMMENT '每5分钟限制票数',
  `thumbs` text COMMENT '任务多图',
  `order_by` int(11) DEFAULT '0',
  `is_display` tinyint(3) DEFAULT '1' COMMENT '1显示',
  `out_stock_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '下架,0,未下架,1,下架 ',
  `out_stock_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下架时间',
  `is_complete` tinyint(3) DEFAULT '0' COMMENT '1已完成',
  `complete_time` int(11) DEFAULT '0' COMMENT '完成时间',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '审核备注',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='任务表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_task`
--

LOCK TABLES `tb_task` WRITE;
/*!40000 ALTER TABLE `tb_task` DISABLE KEYS */;
INSERT INTO `tb_task` VALUES (32,183,'关注coco视频公众号','关注coco视频公众号',2,1579167360,1581845760,10,1,1.00,0,10.00,0.00,10.00,0,0,0.00,0,0,1579167446,'','','',1,0,'0',0,0,0,0,'a:1:{i:0;s:45:\"20200116/cf7ffd289c71cd01f615923d258be9b7.jpg\";}',0,1,0,0,0,0,0,'',1579167446,1579167446),(33,183,'点击文中广告并截图','点击文中广告并截图。。。',9,1579172640,1581851040,10,1,1.50,0,15.00,0.00,15.00,0,0,0.00,0,0,1579172773,'https://mp.weixin.qq.com/s/twjDzBcFDyYV5p-N86dn0A','','',1,0,'0',1,0,0,0,'a:2:{i:0;s:45:\"20200116/b37c6f53ba5391fbe6208614460915ff.jpg\";i:1;s:45:\"20200116/4ec6b1b8e11d2f6e8b1397efa82a0b24.jpg\";}',0,1,0,0,0,0,0,'',1579172773,1579172773);
/*!40000 ALTER TABLE `tb_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_task_category`
--

DROP TABLE IF EXISTS `tb_task_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_task_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `min_give_credit1` decimal(10,2) DEFAULT '0.00' COMMENT '最小奖励积分',
  `min_give_credit2` decimal(10,2) DEFAULT '0.00' COMMENT '最小奖励金额',
  `order_by` int(11) DEFAULT '0' COMMENT '数字越大越靠前',
  `is_display` tinyint(3) DEFAULT '1' COMMENT '1显示',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_task_category`
--

LOCK TABLES `tb_task_category` WRITE;
/*!40000 ALTER TABLE `tb_task_category` DISABLE KEYS */;
INSERT INTO `tb_task_category` VALUES (2,'关注','20200116/2d8b2619cab03b7e5ec522c43c4fe121.png',0.00,1.00,0,1,1579155812,1516799416),(7,'投票','20200116/49fe707a57126a93a33b4ed1a06b0354.png',0.00,1.00,0,1,1579155958,1516799501),(8,'砍价','20200116/cbda8f5e01d5a62370081b2a2cb5cd84.png',0.00,1.00,0,1,1579156029,1516799518),(9,'广告点击','20200116/484d12255b8917ee9c5115614d8f22a6.png',0.00,1.00,0,1,1579163617,1516799649),(14,'其他','20200116/7dd69cef170d14a285206b0e93feb9f7.png',0.00,1.00,0,1,0,1579163606);
/*!40000 ALTER TABLE `tb_task_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_task_join`
--

DROP TABLE IF EXISTS `tb_task_join`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_task_join` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '接单id',
  `task_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `thumbs` text COMMENT '审核图样',
  `check_text_content` varchar(255) NOT NULL DEFAULT '' COMMENT '文字确认',
  `communication` varchar(255) NOT NULL DEFAULT '' COMMENT '信息交流',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '接单状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接单时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `audit_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delflag` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '删除标识  1.未删除 2.假删除',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='任务接单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_task_join`
--

LOCK TABLES `tb_task_join` WRITE;
/*!40000 ALTER TABLE `tb_task_join` DISABLE KEYS */;
INSERT INTO `tb_task_join` VALUES (37,32,184,'a:1:{i:0;s:45:\"20200116/457264b97a113c9d8b9178f8d03d4da8.jpg\";}','','',3,1579172933,1579186566,1579186566,1),(38,33,184,'a:2:{i:0;s:45:\"20200116/160b483512c25da0a12b7fcf80fd0402.jpg\";i:1;s:45:\"20200116/179bb5faeea05978569f775ed20a89ee.jpg\";}','','',3,1579172956,1579186553,1579186553,1);
/*!40000 ALTER TABLE `tb_task_join` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_task_operate_steps`
--

DROP TABLE IF EXISTS `tb_task_operate_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_task_operate_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作id',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `content` text COMMENT '操作说明文字',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '操作说明配图',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '操作步骤顺序',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COMMENT='操作说明表  与发布任务表关联';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_task_operate_steps`
--

LOCK TABLES `tb_task_operate_steps` WRITE;
/*!40000 ALTER TABLE `tb_task_operate_steps` DISABLE KEYS */;
INSERT INTO `tb_task_operate_steps` VALUES (1,1,3,'测试任务测试任务测试任务测试任务','20180628/9959c1778abc2f1c41fcfe9fdcf2d476.jpg',0,0,1530180315),(2,1,3,'测试任务测试任务测试任务','20180628/54e3fcf97da712d495cb3b35eb6bae94.jpg',1,0,1530180315),(3,2,3,'555555','20180629/a411ae1d89849d09ac156667fd0e645c.jpg',0,0,1530219264),(4,2,3,'6666','20180629/3f71e84004abcb45c83aada33cd77a75.jpeg',1,0,1530219264),(5,3,3,'测试任务标题测试任务标题测试任务标题','20180701/a8542f5a44f3c427e8e72ba276270147.jpg',0,0,1530451481),(6,3,3,'测试任务标题测试任务标题测试任务标题','20180701/7e354e8b04d53313a72691f2ce6c488d.png',1,0,1530451481),(7,4,3,'测试任务标题测试任务标题测试任务标题','20180701/4a7f4c3f4a3376e3abaa85bc9ea9b32e.jpg',0,0,1530451489),(8,4,3,'测试任务标题测试任务标题测试任务标题','20180701/a435de438ac607ef1aa6157fbe5af32f.png',1,0,1530451489),(9,5,3,'用手机微信扫描二维码，下载安装帮小咖','20180730/56d4f3e6225c762c420a97f8eb4cff43.png',0,0,1532921298),(10,5,3,'进入任务页上任意做一单，','20180730/8877045df242d0fd6c0d5ac6446238fb.png',1,0,1532921298),(11,5,3,'提供任务提现详细图','20180730/81febc0d0b8ffc6aee2d9084d85a7ee2.png',2,0,1532921298),(12,6,3,'用手机微信扫描二维码，下载安装帮小咖','20180730/eb065566dcd00e694815666104ca452b.png',0,1532921371,1532921304),(13,6,3,'进入任务页上任意做一单，','20180730/8a73272284b058226bce1582d6f3475a.png',1,1532921371,1532921304),(14,6,3,'提供任务提现详细图','20180730/5612f792864934c281cd692c6ef6fcaf.png',2,1532921371,1532921304),(15,7,3,'用微信扫描二码注册，下载','20180730/1a6fc1afb8ad647b278ecc5b7845a3ce.png',0,1532922529,1532922411),(16,7,3,'进入任务页完成任意一单任务提交审核图','20180730/d9eb7804ee5fcffd72265015bb638b0f.png',1,1532922529,1532922411),(17,7,3,'提供完成图','20180730/6d8c30668ceae3cf9800211a184cd562.png',2,0,1532922529),(18,8,3,'5555555555555555555','20180811/525a336c398fb585b8863fc6c24ccac1.jpg',0,0,1533950160),(19,8,3,'6666666666666','20180811/f6acbcd867cf3af69a7c12613a79b413.jpg',1,0,1533950160),(20,9,3,'3333333333333','20180812/2ebf8889bff311868864acc9697b3e63.jpg',0,1534032125,1534031530),(21,9,3,'2222222222222222222222','20180812/ed1d2bf4e4e935a89b74ae55812e06e6.png',1,1534032125,1534031530),(22,9,3,'2222222222222222222222','20180812/2b6bd44398abd9420fcf0d88e8e7939e.png',2,1534032125,1534031530),(23,10,3,'33333','20180820/79fefd14a415a889252f3624a8ea9110.png',0,0,1534730823),(28,15,3,'嗡嗡嗡嗡嗡嗡嗡嗡嗡嗡嗡嗡','',0,0,1534769721),(29,16,3,'4444','20180820/a3cb93154ce0ba5cfabb9c88a0188bf3.png',0,1535933732,1534770450),(30,17,123,'65555','20180822/a9b19361cc534e293ff03ac6c0db308c.png',0,0,1534938050),(31,18,123,'65555','20180822/2e4d6228eff3b8c03a8ee8a788b1afdc.png',0,0,1534938053),(32,1,3,'55555','20180826/50bb2aa3b6e0a5622b2f2f3622cdea6d.jpg',0,0,1535281864),(33,2,3,'55555','20180826/4a426deb7ec6ba288bacd3c56e73bd0f.jpg',0,0,1535281866),(34,3,3,'下载','20180829/91dd42c81d27acf2167bdec3cd29b970.jpg',0,0,1535497730),(35,3,3,'55555','20180829/1f05cef08e354108004b5c9be3321cc7.jpg',1,0,1535497730),(36,4,123,'33','20180831/253395f86fbd328e19ad4f2dca72e828.jpg',0,0,1535667815),(37,5,123,'wewewqewqe','',0,0,1535692558),(38,6,123,'fsdfsdfds','',0,0,1535692637),(39,7,123,'33','20180831/ec6e820ca41422c17b36e4eae294bd91.jpg',0,0,1535692733),(40,8,123,'wqwewqeqw','',0,0,1535694986),(41,9,123,'2222','20180831/46c583dea02ffd8dd7add99fdc8a2c8c.jpg',0,0,1535695393),(42,10,123,'22','20180831/b8ec521fc9c281fd774d0d6e95608aaa.jpg',0,0,1535695506),(43,11,123,'555','20180831/9820745b503734825055e90987e78970.jpg',0,0,1535695709),(44,12,123,'444444444444','20180831/b645e6e71d4f0484198eccd3d8fe7cf9.jpg',0,0,1535695977),(45,13,3,'222','20180901/bb91a086ef63039113d5a4aa1a936121.jpg',0,0,1535787726),(46,14,3,'666','20180901/2d48b51f12b8808ac8cab1ebbd6667bc.jpg',0,0,1535787853),(47,15,123,'的撒大大说','',0,0,1535816079),(48,16,123,'111','20180903/9c2445dbd6454975d65b54d6df0569af.png',0,1535933732,1535933587),(49,17,123,'111111111111','',0,0,1535946920),(50,18,123,'33333333333','',0,0,1535947555),(51,19,123,'2222222222222','20180906/028cac4fc462d600b22a60776b70bd60.jpg',0,0,1536209066),(52,20,165,'分红法规和法国很反感和','20180919/0ec589718db0cdbe9f495bc950bf88d3.png',0,0,1537360111),(53,20,165,'电饭锅地方','20180919/c847609ed63324c8b9d68bace808cf0b.png',1,0,1537360111),(54,21,123,'11111111111111','',0,0,1537433378),(55,22,161,'11111111','20180928/e08c69c274b3cd3a3f9e932847362cd2.png',0,0,1538133121),(56,23,123,'FDSAFDSAFDS','',0,0,1538923591),(57,24,123,'扫码投票','20181026/eeb9622b066107a0b369579029e16cd0.jpg',0,0,1540533703),(58,25,123,'123123132','20181112/0b8e6f692e32a5c6b7adb849a0f17faf.png',0,0,1541967421),(59,26,3,'尊敬的互助雇主们，请大家根据自己的任务需求选择分类， 正确的分类会提供用户做任务的','',0,0,1565512330),(60,26,3,'尊敬的互助雇主们，请大家根据自己的任务需求选择分类， 正确的分类会提供用户做任务的','',1,0,1565512330),(61,27,172,'111','',0,0,1566266605),(62,27,172,'222','',1,0,1566266605),(63,27,172,'333','',2,0,1566266605),(64,27,172,'444','',3,0,1566266605),(65,27,172,'555','',4,0,1566266605),(66,28,181,'扫描二维码关注公众号','20200116/add589cc844710ec3a4f115f92e5c8ad.png',0,0,1579163023),(67,29,181,'微信打开链接，点击文中的广告','20200116/6d7a6e5a57101a5423c8e574e8e72a9b.jpg',0,0,1579164004),(68,29,181,'截图广告页面','20200116/2a57cd50735bae85177aaddbecc108c2.jpg',1,0,1579164004),(69,30,181,'微信打开链接，点击文中的广告','20200116/e190ac1db8a6ebbbca4d871759047bf6.jpg',0,0,1579164007),(70,30,181,'截图广告页面','20200116/91dfc68164f157ab66570c2e989101da.jpg',1,0,1579164007),(71,31,181,'啊啊啊','',0,0,1579166291),(72,32,183,'扫描二维码关注','20200116/89ba30f7c2e7ac5fe04d0309e1ff8f9e.png',0,0,1579167446),(73,33,183,'打开链接，点击文中广告并截图','',0,0,1579172773);
/*!40000 ALTER TABLE `tb_task_operate_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_uploads`
--

DROP TABLE IF EXISTS `tb_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT '0' COMMENT '管理员ID',
  `uid` int(11) DEFAULT '0' COMMENT '会员ID',
  `extension` varchar(20) DEFAULT NULL COMMENT '扩展名',
  `original_name` varchar(255) DEFAULT NULL COMMENT '原文件名',
  `save_name` varchar(255) DEFAULT NULL COMMENT '保存名称',
  `filename` varchar(255) DEFAULT NULL COMMENT '文件名',
  `md5` varchar(255) DEFAULT NULL COMMENT '文件md5',
  `sha1` varchar(255) DEFAULT NULL COMMENT '文件sha1值',
  `size` varchar(255) DEFAULT NULL COMMENT '文件大小',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1874 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='上传信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_uploads`
--

LOCK TABLES `tb_uploads` WRITE;
/*!40000 ALTER TABLE `tb_uploads` DISABLE KEYS */;
INSERT INTO `tb_uploads` VALUES (1844,1,0,'png',NULL,'20200116/2d8b2619cab03b7e5ec522c43c4fe121.png','2d8b2619cab03b7e5ec522c43c4fe121.png','dabfc53b5c00e9f825d131ebc7c6ea01','05dbb97418fb3933f584022c4611461bc35e9a79','49384',1579155804),(1845,1,0,'png',NULL,'20200116/49fe707a57126a93a33b4ed1a06b0354.png','49fe707a57126a93a33b4ed1a06b0354.png','6614e1c7a009235ae79bc9046f2d0fa3','681c9245b5009b56489021c12f77a35e43d448c5','47260',1579155955),(1846,1,0,'png',NULL,'20200116/cbda8f5e01d5a62370081b2a2cb5cd84.png','cbda8f5e01d5a62370081b2a2cb5cd84.png','8ddf5dc5223f359e4ee639e85d3717c0','b6c9ca0b2d7e9c2bcceec49929d15d2cabd73ad5','46929',1579156026),(1847,1,0,'png',NULL,'20200116/484d12255b8917ee9c5115614d8f22a6.png','484d12255b8917ee9c5115614d8f22a6.png','636c46e45bb90924d1a83eac5920cf27','7e687850b660115e997ce2cb97cded98076bf183','46624',1579156094),(1848,1,0,'png',NULL,'20200116/1e3c2fd8b5e924afd2e41a57af94625b.png','1e3c2fd8b5e924afd2e41a57af94625b.png','f1319c211397f53f7622ae34d458ea7d','e782e161ffc561b2886e2d3316a37fa9523094a6','46478',1579156181),(1849,1,0,'png',NULL,'20200116/0f70fbc322197c17a1d0b40c0157b9c9.png','0f70fbc322197c17a1d0b40c0157b9c9.png','85e2a8afd5580e6c00ce36b4349f6a3d','cacc7fc2c2e3e275e7cdc89fa30a92debdaa09e0','30118',1579157587),(1850,1,0,'png',NULL,'20200116/2b609c38cbea6414182b05900eb24668.png','2b609c38cbea6414182b05900eb24668.png','269425b9e672f3c8aa5da0833d9b7cd0','da8a5e80e53307ce37380f538f8ea8264429b617','30120',1579157667),(1851,1,0,'png',NULL,'20200116/94a5176dfb6d40d82d500425370627a0.png','94a5176dfb6d40d82d500425370627a0.png','f60077f0899480b7737d6eec06717671','7aa76c04c9dce45d16a4a94f1e2711603eb5fcc2','25127',1579157837),(1852,0,181,'jpg',NULL,'20200116/1cc8830496d070f466c91f49dcfc5ab8.jpg','1cc8830496d070f466c91f49dcfc5ab8.jpg','37a1a897638d8dde5f450ea39d1d6c38','db781f2ebe41c4c31f0622e4b823b2e63f59118b','146217',1579163023),(1853,0,181,'png',NULL,'20200116/add589cc844710ec3a4f115f92e5c8ad.png','add589cc844710ec3a4f115f92e5c8ad.png','c3d734f2c5ca8c99dcac7774a8d91bf9','bcde2ffc8be5f9a10d491e9d71cb028149af292f','28187',1579163023),(1854,1,0,'png',NULL,'20200116/7dd69cef170d14a285206b0e93feb9f7.png','7dd69cef170d14a285206b0e93feb9f7.png','f1319c211397f53f7622ae34d458ea7d','e782e161ffc561b2886e2d3316a37fa9523094a6','46478',1579163594),(1855,0,181,'jpg',NULL,'20200116/2c4d6a07f50ed01546e1dcb47f5d0652.jpg','2c4d6a07f50ed01546e1dcb47f5d0652.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579164004),(1856,0,181,'jpg',NULL,'20200116/4558957451ec8ae83b6bb7dd21e40032.jpg','4558957451ec8ae83b6bb7dd21e40032.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579164004),(1857,0,181,'jpg',NULL,'20200116/6d7a6e5a57101a5423c8e574e8e72a9b.jpg','6d7a6e5a57101a5423c8e574e8e72a9b.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579164004),(1858,0,181,'jpg',NULL,'20200116/2a57cd50735bae85177aaddbecc108c2.jpg','2a57cd50735bae85177aaddbecc108c2.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579164004),(1859,0,181,'jpg',NULL,'20200116/76f9d8e80061af068cb028c55240305e.jpg','76f9d8e80061af068cb028c55240305e.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579164007),(1860,0,181,'jpg',NULL,'20200116/6bccfddb4bca9e57dab946497220a580.jpg','6bccfddb4bca9e57dab946497220a580.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579164007),(1861,0,181,'jpg',NULL,'20200116/e190ac1db8a6ebbbca4d871759047bf6.jpg','e190ac1db8a6ebbbca4d871759047bf6.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579164007),(1862,0,181,'jpg',NULL,'20200116/91dfc68164f157ab66570c2e989101da.jpg','91dfc68164f157ab66570c2e989101da.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579164007),(1863,0,181,'jpg',NULL,'20200116/fe233dc9f14cc1cd2c5fec4b79d2ab24.jpg','fe233dc9f14cc1cd2c5fec4b79d2ab24.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579166291),(1864,0,182,'jpg',NULL,'20200116/af838e313e9fb07d0874efa07a5ba2ee.jpg','af838e313e9fb07d0874efa07a5ba2ee.jpg','37a1a897638d8dde5f450ea39d1d6c38','db781f2ebe41c4c31f0622e4b823b2e63f59118b','146217',1579166483),(1865,0,182,'jpg',NULL,'20200116/6391b8d7bcc44c652da3b269d8e5e86f.jpg','6391b8d7bcc44c652da3b269d8e5e86f.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579166566),(1866,0,183,'jpg',NULL,'20200116/cf7ffd289c71cd01f615923d258be9b7.jpg','cf7ffd289c71cd01f615923d258be9b7.jpg','37a1a897638d8dde5f450ea39d1d6c38','db781f2ebe41c4c31f0622e4b823b2e63f59118b','146217',1579167446),(1867,0,183,'png',NULL,'20200116/89ba30f7c2e7ac5fe04d0309e1ff8f9e.png','89ba30f7c2e7ac5fe04d0309e1ff8f9e.png','c3d734f2c5ca8c99dcac7774a8d91bf9','bcde2ffc8be5f9a10d491e9d71cb028149af292f','28187',1579167446),(1868,0,183,'jpg',NULL,'20200116/b37c6f53ba5391fbe6208614460915ff.jpg','b37c6f53ba5391fbe6208614460915ff.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579172773),(1869,0,183,'jpg',NULL,'20200116/4ec6b1b8e11d2f6e8b1397efa82a0b24.jpg','4ec6b1b8e11d2f6e8b1397efa82a0b24.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579172773),(1870,0,184,'jpg',NULL,'20200116/457264b97a113c9d8b9178f8d03d4da8.jpg','457264b97a113c9d8b9178f8d03d4da8.jpg','37a1a897638d8dde5f450ea39d1d6c38','db781f2ebe41c4c31f0622e4b823b2e63f59118b','146217',1579172942),(1871,0,184,'jpg',NULL,'20200116/160b483512c25da0a12b7fcf80fd0402.jpg','160b483512c25da0a12b7fcf80fd0402.jpg','87806aeb60f37d8f79bff6f9be27b2df','df5da983a6df7e65456ac4f52721af85466611fb','622460',1579172972),(1872,0,184,'jpg',NULL,'20200116/179bb5faeea05978569f775ed20a89ee.jpg','179bb5faeea05978569f775ed20a89ee.jpg','9641cc5463cb96d6c13f3e0f20989391','7fd9af4b40961687b1230d44e9230e85f3de4aa9','1156270',1579172972),(1873,0,184,'jpg',NULL,'20200116/fb9b9c886526276fa5d6d91db350f9a7.jpg','fb9b9c886526276fa5d6d91db350f9a7.jpg','33147c4b4c50f405e907374f22833993','b51b374636ee1979d734ce2c9a89ede8a40e0f1b','40226',1579173039);
/*!40000 ALTER TABLE `tb_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_withdraw`
--

DROP TABLE IF EXISTS `tb_withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `credit2` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `pay_method` tinyint(3) DEFAULT '0' COMMENT '0银行卡，1支付宝，2微信',
  `account` varchar(50) DEFAULT NULL COMMENT '账号',
  `realname` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `status` tinyint(3) DEFAULT '0' COMMENT '0待审核，1已发放，-1审核未通过',
  `note` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `update_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_withdraw`
--

LOCK TABLES `tb_withdraw` WRITE;
/*!40000 ALTER TABLE `tb_withdraw` DISABLE KEYS */;
INSERT INTO `tb_withdraw` VALUES (47,0,'1',0.00,0.00,0,'诚信互助，互利共赢',NULL,NULL,1,NULL,0,0),(48,0,'2',0.00,0.00,0,'平台运营初期，需要大家的共同参与',NULL,NULL,1,NULL,0,0),(49,0,'3',0.00,0.00,0,'做任务获得积分，积分可用于发布任务',NULL,NULL,1,NULL,0,0);
/*!40000 ALTER TABLE `tb_withdraw` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-13 14:20:07

-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-08-26 22:49:23
-- 服务器版本： 5.6.48-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- 表的结构 `hisi_ad`
--

CREATE TABLE IF NOT EXISTS `hisi_ad` (
  `id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 启动页广告 2 首页广告 3 播放页弹窗广告 4 播放页底部广告 5 视频播放前广告',
  `title` varchar(255) DEFAULT '' COMMENT '广告标题',
  `desc` varchar(255) DEFAULT '' COMMENT '广告简介',
  `ad_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 图片 2 视频 3 小程序',
  `img` varchar(255) DEFAULT '' COMMENT '图片地址',
  `v_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `url` varchar(255) DEFAULT '' COMMENT '跳转链接',
  `status` tinyint(1) DEFAULT '1' COMMENT '1 启用 0 禁用',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `hisi_attachment`
--

CREATE TABLE IF NOT EXISTS `hisi_attachment` (
  `id` int(11) NOT NULL,
  `savepath` varchar(255) NOT NULL DEFAULT '' COMMENT '保存目录',
  `md5` varchar(255) NOT NULL DEFAULT '' COMMENT '文件md5',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '图片类型',
  `location` tinyint(1) NOT NULL DEFAULT '0' COMMENT '储存环境 0 本地 1 云端',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_banner`
--

CREATE TABLE IF NOT EXISTS `hisi_banner` (
  `id` int(11) unsigned NOT NULL,
  `vid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '视频id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT 'banner标题',
  `img` varchar(1000) NOT NULL DEFAULT '' COMMENT 'banner图片地址',
  `isad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0  banner 1 广告 2 小程序广告',
  `ad_url` varchar(255) DEFAULT NULL COMMENT '广告链接',
  `type` varchar(255) NOT NULL DEFAULT 'image' COMMENT '展示类型 image video mpad',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 启用 0 禁用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='轮播图';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_feedback`
--

CREATE TABLE IF NOT EXISTS `hisi_feedback` (
  `id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '留言类型',
  `content` text NOT NULL COMMENT '留言内容',
  `imgs` text NOT NULL COMMENT '留言图片',
  `contact` varchar(50) NOT NULL DEFAULT '' COMMENT '联系方式',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户反馈';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_goods`
--

CREATE TABLE IF NOT EXISTS `hisi_goods` (
  `id` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `goods_name` varchar(100) NOT NULL DEFAULT '' COMMENT '商品名',
  `ext` varchar(3000) NOT NULL DEFAULT '' COMMENT '扩展字段',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品金额',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上下架状态 1上架 0 下架',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品管理';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_goods_category`
--

CREATE TABLE IF NOT EXISTS `hisi_goods_category` (
  `id` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '商品分类名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 正常 2 停用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_live`
--

CREATE TABLE IF NOT EXISTS `hisi_live` (
  `id` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '直播分类id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '节目名',
  `url` text NOT NULL COMMENT '直播地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 正常 2 停用',
  `sort` int(11) NOT NULL DEFAULT '10' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网络电视';

--
-- 表的结构 `hisi_live_category`
--

CREATE TABLE IF NOT EXISTS `hisi_live_category` (
  `id` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 正常 2 停用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播分类';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_msg`
--

CREATE TABLE IF NOT EXISTS `hisi_msg` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(1500) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 正常 0 停用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公告';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_nb_api`
--

CREATE TABLE IF NOT EXISTS `hisi_nb_api` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `secret` varchar(100) NOT NULL DEFAULT '' COMMENT '密钥',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0禁用 1启用'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 表的结构 `hisi_order`
--

CREATE TABLE IF NOT EXISTS `hisi_order` (
  `id` int(10) unsigned NOT NULL,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_sn` varchar(50) NOT NULL DEFAULT '' COMMENT '订单编号',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `cid` int(10) NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `ext` varchar(255) NOT NULL COMMENT '商品ext',
  `pay_method` varchar(50) NOT NULL DEFAULT '' COMMENT '支付方式',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1待支付 2 已支付 3 已取消',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态 1 待支付 2 已支付',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付完成时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `hisi_order_trade`
--

CREATE TABLE IF NOT EXISTS `hisi_order_trade` (
  `id` int(11) unsigned NOT NULL,
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '支付单号',
  `pay_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方支付号',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `method` varchar(50) NOT NULL DEFAULT '' COMMENT '支付方式',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1已取消 1待支付 2已支付',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单号关联表';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_pay_log`
--

CREATE TABLE IF NOT EXISTS `hisi_pay_log` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户标识',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1付款，2退款',
  `product_id` varchar(50) DEFAULT '' COMMENT '产品ID[选填]',
  `order_sn` varchar(50) NOT NULL COMMENT '商户订单号',
  `order_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '商户支付单号',
  `refund_no` varchar(64) DEFAULT '' COMMENT '退款单号',
  `trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '支付平台交易号',
  `method` varchar(50) NOT NULL COMMENT '支付方式code',
  `bank` varchar(50) NOT NULL DEFAULT '' COMMENT '支付银行code',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `request` text NOT NULL COMMENT '请求数据',
  `return` text COMMENT '返回数据',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0失败，1待处理，2成功)',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[pay] 支付日志';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_pay_payment`
--

CREATE TABLE IF NOT EXISTS `hisi_pay_payment` (
  `id` int(10) unsigned NOT NULL,
  `code` varchar(50) NOT NULL COMMENT '支付平台code',
  `title` varchar(50) NOT NULL COMMENT '支付平台标题',
  `intro` varchar(255) NOT NULL COMMENT '支付平台简介',
  `config` text NOT NULL COMMENT '配置',
  `applies` varchar(10) NOT NULL DEFAULT 'pc' COMMENT '适用环境(pc,wap,wechat,app)',
  `sort` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态(0停用，1启用)',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[pay] 支付平台';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_plugins_sms`
--

CREATE TABLE IF NOT EXISTS `hisi_plugins_sms` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '网关标题',
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `config` text COMMENT '网关配置',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0停用，1启用)',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认',
  `bind_template` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '绑定模板',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信网关平台';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_plugins_sms_log`
--

CREATE TABLE IF NOT EXISTS `hisi_plugins_sms_log` (
  `id` int(11) unsigned NOT NULL,
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `template` varchar(50) DEFAULT '' COMMENT '网关模板',
  `content` text COMMENT '短信内容',
  `params` text COMMENT '请求参数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态(1成功，0失败)',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信发送日志';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_plugins_sms_template`
--

CREATE TABLE IF NOT EXISTS `hisi_plugins_sms_template` (
  `id` int(11) unsigned NOT NULL,
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '模板别名',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '模板标题',
  `example` text COMMENT '示例内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信模板';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_plugins_sms_template_index`
--

CREATE TABLE IF NOT EXISTS `hisi_plugins_sms_template_index` (
  `id` int(11) unsigned NOT NULL,
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '模板别名',
  `gateway_template` varchar(50) NOT NULL DEFAULT '' COMMENT '网关平台模板id或code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信模板索引';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_annex`
--

CREATE TABLE IF NOT EXISTS `hisi_system_annex` (
  `id` int(10) unsigned NOT NULL,
  `data_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联的数据ID',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `group` varchar(100) NOT NULL DEFAULT 'sys' COMMENT '文件分组',
  `file` varchar(255) NOT NULL COMMENT '上传文件',
  `hash` varchar(64) NOT NULL COMMENT '文件hash值',
  `size` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '附件大小KB',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '使用状态(0未使用，1已使用)',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 上传附件';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_annex_group`
--

CREATE TABLE IF NOT EXISTS `hisi_system_annex_group` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '附件分组',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `size` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '附件大小kb'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 附件分组';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_config`
--

CREATE TABLE IF NOT EXISTS `hisi_system_config` (
  `id` int(10) unsigned NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统配置(1是，0否)',
  `group` varchar(20) NOT NULL DEFAULT 'base' COMMENT '分组',
  `title` varchar(20) NOT NULL COMMENT '配置标题',
  `name` varchar(50) NOT NULL COMMENT '配置名称，由英文字母和下划线组成',
  `value` text NOT NULL COMMENT '配置值',
  `type` varchar(20) NOT NULL DEFAULT 'input' COMMENT '配置类型()',
  `options` text NOT NULL COMMENT '配置项(选项名:选项值)',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件上传接口',
  `tips` varchar(255) NOT NULL COMMENT '配置提示',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 系统配置';

--
-- 转存表中的数据 `hisi_system_config`
--

INSERT INTO `hisi_system_config` (`id`, `system`, `group`, `title`, `name`, `value`, `type`, `options`, `url`, `tips`, `sort`, `status`, `ctime`, `mtime`) VALUES
(1, 1, 'sys', '扩展配置分组', 'config_group', 'commoncfg:全局配置\r\nplayad:激励广告\r\nucenter:个人中心\r\nvideos:视频配置\r\nupdate_app:版本', 'array', ' ', '', '请按如下格式填写：&lt;br&gt;键值:键名&lt;br&gt;键值:键名&lt;br&gt;&lt;span style=&quot;color:#f00&quot;&gt;键值只能为英文、数字、下划线&lt;/span&gt;', 2, 1, 1492140215, 1597384449),
(13, 1, 'base', '网站域名', 'site_domain', '617kan', 'input', '', '', '', 2, 1, 1492140215, 1492140215),
(14, 1, 'upload', '图片上传大小限制', 'upload_image_size', '1536', 'input', '', '', '单位：KB，0表示不限制大小', 3, 1, 1490841797, 1491040778),
(15, 1, 'upload', '允许上传图片格式', 'upload_image_ext', 'jpg,png,gif,jpeg', 'input', '', '', '多个格式请用英文逗号（,）隔开', 4, 1, 1490842130, 1491040778),
(16, 1, 'upload', '缩略图裁剪方式', 'thumb_type', '2', 'select', '1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放\r\n', '', '', 5, 1, 1490842450, 1491040778),
(17, 1, 'upload', '图片水印开关', 'image_watermark', '0', 'switch', '0:关闭\r\n1:开启', '', '', 6, 1, 1490842583, 1491040778),
(18, 1, 'upload', '图片水印图', 'image_watermark_pic', '', 'image', '', '', '', 7, 1, 1490842679, 1491040778),
(19, 1, 'upload', '图片水印透明度', 'image_watermark_opacity', '50', 'input', '', '', '可设置值为0~100，数字越小，透明度越高', 8, 1, 1490857704, 1491040778),
(20, 1, 'upload', '图片水印图位置', 'image_watermark_location', '9', 'select', '7:左下角\r\n1:左上角\r\n4:左居中\r\n9:右下角\r\n3:右上角\r\n6:右居中\r\n2:上居中\r\n8:下居中\r\n5:居中', '', '', 9, 1, 1490858228, 1491040778),
(21, 1, 'upload', '文件上传大小限制', 'upload_file_size', '0', 'input', '', '', '单位：KB，0表示不限制大小', 1, 1, 1490859167, 1491040778),
(22, 1, 'upload', '允许上传文件格式', 'upload_file_ext', 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip', 'input', '', '', '多个格式请用英文逗号（,）隔开', 2, 1, 1490859246, 1491040778),
(23, 1, 'upload', '文字水印开关', 'text_watermark', '0', 'switch', '0:关闭\r\n1:开启', '', '', 10, 1, 1490860872, 1491040778),
(24, 1, 'upload', '文字水印内容', 'text_watermark_content', '', 'input', '', '', '', 11, 1, 1490861005, 1491040778),
(25, 1, 'upload', '文字水印字体', 'text_watermark_font', '', 'file', '', '', '不上传将使用系统默认字体', 12, 1, 1490861117, 1491040778),
(26, 1, 'upload', '文字水印字体大小', 'text_watermark_size', '20', 'input', '', '', '单位：px(像素)', 13, 1, 1490861204, 1491040778),
(27, 1, 'upload', '文字水印颜色', 'text_watermark_color', '#000000', 'input', '', '', '文字水印颜色，格式:#000000', 14, 1, 1490861482, 1491040778),
(28, 1, 'upload', '文字水印位置', 'text_watermark_location', '7', 'select', '7:左下角\r\n1:左上角\r\n4:左居中\r\n9:右下角\r\n3:右上角\r\n6:右居中\r\n2:上居中\r\n8:下居中\r\n5:居中', '', '', 11, 1, 1490861718, 1491040778),
(29, 1, 'upload', '缩略图尺寸', 'thumb_size', '300x300;500x500', 'input', '', '', '为空则不生成，生成 500x500 的缩略图，则填写 500x500，多个规格填写参考 300x300;500x500;800x800', 4, 1, 1490947834, 1491040778),
(30, 1, 'sys', '开发模式', 'app_debug', '1', 'switch', '0:关闭\r\n1:开启', '', '&lt;strong class=&quot;red&quot;&gt;生产环境下一定要关闭此配置&lt;/strong&gt;', 3, 1, 1491005004, 1492093874),
(31, 1, 'sys', '页面Trace', 'app_trace', '0', 'switch', '0:关闭\r\n1:开启', '', '&lt;strong class=&quot;red&quot;&gt;生产环境下一定要关闭此配置&lt;/strong&gt;', 4, 1, 1491005081, 1492093874),
(33, 1, 'sys', '富文本编辑器', 'editor', 'umeditor', 'select', 'ueditor:UEditor\r\numeditor:UMEditor\r\nkindeditor:KindEditor\r\nckeditor:CKEditor', '', '', 0, 1, 1491142648, 1492140215),
(35, 1, 'databases', '备份目录', 'backup_path', './backup/database/', 'input', '', '', '数据库备份路径,路径必须以 / 结尾', 0, 1, 1491881854, 1491965974),
(36, 1, 'databases', '备份分卷大小', 'part_size', '20971520', 'input', '', '', '用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 0, 1, 1491881975, 1491965974),
(37, 1, 'databases', '备份压缩开关', 'compress', '1', 'switch', '0:关闭\r\n1:开启', '', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 0, 1, 1491882038, 1491965974),
(38, 1, 'databases', '备份压缩级别', 'compress_level', '4', 'radio', '1:最低\r\n4:一般\r\n9:最高', '', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 0, 1, 1491882154, 1491965974),
(39, 1, 'base', '网站状态', 'site_status', '1', 'switch', '0:关闭\r\n1:开启', '', '站点关闭后将不能访问，后台可正常登录', 1, 1, 1492049460, 1494690024),
(40, 1, 'sys', '后台管理路径', 'admin_path', 'admin.php', 'input', '', '', '必须以.php为后缀', 1, 1, 1492139196, 1492140215),
(41, 1, 'base', '网站标题', 'site_title', '617kan', 'input', '', '', '网站标题是体现一个网站的主旨，要做到主题突出、标题简洁、连贯等特点，建议不超过28个字', 6, 1, 1492502354, 1494695131),
(42, 1, 'base', '网站关键词', 'site_keywords', '', 'input', '', '', '网页内容所包含的核心搜索关键词，多个关键字请用英文逗号&quot;,&quot;分隔', 7, 1, 1494690508, 1494690780),
(43, 1, 'base', '网站描述', 'site_description', '', 'textarea', '', '', '网页的描述信息，搜索引擎采纳后，作为搜索结果中的页面摘要显示，建议不超过80个字', 8, 1, 1494690669, 1494691075),
(44, 1, 'base', 'ICP备案信息', 'site_icp', '', 'input', '', '', '请填写ICP备案号，用于展示在网站底部，ICP备案官网：&lt;a href=&quot;http://www.miibeian.gov.cn&quot; target=&quot;_blank&quot;&gt;http://www.miibeian.gov.cn&lt;/a&gt;', 9, 1, 1494691721, 1494692046),
(45, 1, 'base', '站点统计代码', 'site_statis', '', 'textarea', '', '', '第三方流量统计代码，前台调用时请先用 htmlspecialchars_decode函数转义输出', 10, 1, 1494691959, 1494694797),
(46, 1, 'base', '网站名称', 'site_name', '617kan', 'input', '', '', '将显示在浏览器窗口标题等位置', 3, 1, 1494692103, 1494694680),
(47, 1, 'base', '网站LOGO', 'site_logo', '', 'image', '', '', '网站LOGO图片', 4, 1, 1494692345, 1494693235),
(48, 1, 'base', '网站图标', 'site_favicon', '', 'image', '', '/system/annex/favicon', '又叫网站收藏夹图标，它显示位于浏览器的地址栏或者标题前面，&lt;strong class=&quot;red&quot;&gt;.ico格式&lt;/strong&gt;，&lt;a href=&quot;https://www.baidu.com/s?ie=UTF-8&amp;wd=favicon&quot; target=&quot;_blank&quot;&gt;点此了解网站图标&lt;/a&gt;', 5, 1, 1494692781, 1494693966),
(49, 1, 'base', '手机网站', 'wap_site_status', '0', 'switch', '0:关闭\r\n1:开启', '', '如果有手机网站，请设置为开启状态，否则只显示PC网站', 2, 1, 1498405436, 1498405436),
(50, 1, 'sys', '云端推送', 'cloud_push', '0', 'switch', '0:关闭\r\n1:开启', '', '关闭之后，无法通过云端推送安装扩展', 5, 1, 1504250320, 1504250320),
(51, 1, 'base', '手机网站域名', 'wap_domain', '', 'input', '', '', '手机访问将自动跳转至此域名', 2, 1, 1504304776, 1504304837),
(52, 1, 'sys', '多语言支持', 'multi_language', '0', 'switch', '0:关闭\r\n1:开启', '', '开启后你可以自由上传多种语言包', 6, 1, 1506532211, 1506532211),
(53, 1, 'sys', '后台白名单验证', 'admin_whitelist_verify', '0', 'switch', '0:禁用\r\n1:启用', '', '禁用后不存在的菜单节点将不在提示', 7, 1, 1542012232, 1542012321),
(54, 1, 'sys', '系统日志保留', 'system_log_retention', '30', 'input', '', '', '单位天，系统将自动清除 ? 天前的系统日志', 8, 1, 1542013958, 1542014158),
(55, 1, 'upload', '上传驱动', 'upload_driver', 'local', 'select', 'local:本地上传', '', '资源上传驱动设置', 0, 1, 1558599270, 1558618703),
(56, 0, 'videos', '解析地址', 'base_url', '', 'input', '', '', '', 0, 1, 1566973570, 1566973570),
(57, 0, 'commoncfg', '开屏广告', 'show_ad', '0', 'switch', '0:关闭\r\n1:开启', '', '', 2, 1, 1567074833, 1567074833),
(58, 0, 'commoncfg', '摇一摇阙值', 'shake', '2000', 'input', '', '', '数值越大，就要摇的就更加用力', 1, 1, 1567081743, 1567673891),
(59, 0, 'videos', 'BILI手机地址', 'bilibili_host', 'b23.tv/', 'input', '', '', '点击分享host', 0, 1, 1567159506, 1567159506),
(60, 0, 'ucenter', '关于应用', 'about_app', '1、本APP仅做交流学习使用。\r\n2、QQ交流群：516769607', 'textarea', '', '', '', 0, 1, 1567256758, 1567256758),
(61, 0, 'ucenter', '服务条款', 'punch', '', 'textarea', '', '', '', 0, 1, 1567260816, 1567260816),
(62, 0, 'commoncfg', '全局广告开关', 'ad_switch', '0', 'switch', '0:关闭\r\n1:开启', '', '此开关控制首页、分类列表页、个人中心页及所有内页的广告', 3, 1, 1567261854, 1597309400),
(74, 0, 'update_app', 'IOS版本', 'ios_version', '1.0.0', 'input', '', '', '当前最新版本的app版本号，和客户端的globalConfigs.js里面配置的iosVersion如果相等则不跟新，如果不相等且atOnce为true时，会提示更新', 0, 1, 1565256540, 1565257040),
(75, 0, 'update_app', 'IOS下载地址', 'ios_download_url', '', 'input', '', '', '此url为APP STORE的下载地址', 0, 1, 1565256579, 1565257051),
(76, 0, 'update_app', 'IOS更新日志', 'ios_log', '', 'textarea', '', '', '更新日志', 0, 1, 1565256604, 1565257061),
(77, 0, 'update_app', '立即更新', 'ios_atonce', '0', 'switch', '0:关闭\r\n1:开启', '', '如果有更新，是否立即提示更新，关闭时不更新，开启时会提示更新，此提示可用于app上架审核期间控制不提示更新', 0, 1, 1565256677, 1565257029),
(78, 0, 'update_app', '安卓版本号', 'android_version', '1.0.0', 'input', '', '', '当前最新版本的app版本号，和客户端的globalConfigs.js里面配置的iosVersion如果相等则不跟新，如果不相等且atOnce为true时，会提示更新', 0, 1, 1565256723, 1565256723),
(79, 0, 'update_app', '安卓下载地址', 'android_download_url', '', 'input', '', '', '安卓文件的链接地址，例如：http://xxx/download/xxx.apk', 0, 1, 1565256870, 1565256870),
(80, 0, 'update_app', '安卓更新日志', 'android_log', '', 'textarea', '', '', '安卓的更新日志内容', 0, 1, 1565256903, 1565256903),
(81, 0, 'update_app', '立即更新', 'android_atonce', '0', 'switch', '0:关闭\r\n1:开启', '', '如果有更新，是否立即提示更新，关闭时不更新，开启会提示更新，此提示可用于app上架审核期间控制不提示更新', 0, 1, 1565256985, 1565256985),
(82, 0, 'commoncfg', '播放前弹窗广告', 'player_show_ad', '0', 'switch', '0:关闭\r\n1:开启', '', '', 4, 1, 1567673783, 1567673814),
(83, 0, 'commoncfg', '欺骗式关闭按钮', 'ad_close', '0', 'switch', '0:关闭\r\n1:开启', '', '广告上的关闭按钮，若开启，点击关闭即为点击广告，后退后广告窗口自动关闭；若关闭，点击后直接关闭广告弹窗', 5, 1, 1567677067, 1567677086),
(84, 0, 'commoncfg', '播放页底部广告', 'player_bottom_ad', '0', 'switch', '0:关闭\r\n1:开启', '', '', 6, 1, 1567680230, 1567680230),
(85, 0, 'commoncfg', '小程序过审页', 'show_xx', '1', 'switch', '0:关闭\r\n1:开启', '', '开启后小程序首页会换成banner+新闻列表页，新闻内容需在新闻模块添加，用于小程序骗审', 1, 1, 1592710349, 1592727135),
(86, 0, 'commoncfg', '视频播放前广告', 'show_video_ad', '0', 'switch', '0:关闭\r\n1:开启', '', '此配置项打开后，在加载视频前会先展示小程序视频贴片广告', 7, 1, 1593677118, 1593677118),
(87, 0, 'commoncfg', '首页关注弹窗二维码', 'popup_img', '', 'image', '', '', '配置引导关注的公众号二维码', 10, 1, 1596690063, 1596690129),
(88, 0, 'commoncfg', '首页关注弹窗提示语', 'popup_title', '关注公众号不迷路', 'input', '', '', '首页引导关注提示语', 11, 1, 1596690120, 1596690120),
(89, 0, 'commoncfg', '首页引导关注弹窗', 'show_popup', '0', 'switch', '0:关闭\r\n1:开启', '', '是否展示首页引导关注弹窗', 9, 1, 1596690430, 1596690430),
(90, 0, 'playad', '播放前激励广告', 'play_start_ad', '0', 'switch', '0:关闭\r\n1:开启', '', '视频播放前的激励广告；如果打开该配置，播放页弹窗广告和视频播放前广告都将自动失效', 1, 1, 1597163143, 1597384467),
(91, 0, 'playad', '激励广告必看', 'should_play_end', '0', 'switch', '0:关闭\r\n1:开启', '', '开启后用户必须看完激励广告才可以观看视频，否则直接返回到上一页，不可观看视频', 2, 1, 1597174035, 1597384481),
(92, 0, 'playad', '激励广告出现次数', 'play_ad_num', '2', 'input', '', '', '一天内展示激励广告的次数', 4, 1, 1597381977, 1597384490),
(93, 0, 'playad', '激励广告限次展示', 'play_ad_switch', '0', 'switch', '0:关闭\r\n1:开启', '', '开启后，激励广告将根据设置的次数展示，达到设定次数后不再展示激励广告', 3, 1, 1597382116, 1597384500),
(94, 0, 'playad', '激励广告弹窗内容', 'play_ad_text', '看完广告即可播放，同时会切换到独享高速线路，独享线路基本无卡顿哦！（高峰期除外）', 'textarea', '', '', '', 6, 1, 1597384395, 1597384516),
(95, 0, 'playad', '激励广告弹窗标题', 'play_ad_title', '广告', 'input', '', '', '', 5, 1, 1597384422, 1597384507),
(96, 0, 'commoncfg', '会员播放等级限制', 'member_group_switch', '1', 'switch', '0:关闭\r\n1:开启', '', '开启后，将根据设定的会员等级进行判断是否给予观看权限，该配置优先级高于小程序审核开关（如果开了小程序审核开关，但是会员满足设定的等级，那么观看将不受影响）', 8, 1, 1597657554, 1597657554),
(97, 0, 'commoncfg', '会员播放等级ID', 'member_group_limit', '2', 'input', '', '', '可以正常观看的会员等级，设定3级的话，3级以下的会员将无法观看。&lt;br&gt;&lt;span style=&quot;color: red&quot;&gt;注：当设置为最低等级id的时候，代表着所有人都可以正常观看！谨慎设置&lt;/span&gt;', 8, 1, 1597657755, 1597660916);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_hook`
--

CREATE TABLE IF NOT EXISTS `hisi_system_hook` (
  `id` int(10) unsigned NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统插件',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '钩子来源[plugins.插件名，module.模块名]',
  `intro` varchar(200) NOT NULL DEFAULT '' COMMENT '钩子简介',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 钩子表';

--
-- 转存表中的数据 `hisi_system_hook`
--

INSERT INTO `hisi_system_hook` (`id`, `system`, `name`, `source`, `intro`, `status`, `ctime`, `mtime`) VALUES
(1, 1, 'system_admin_index', '', '后台首页', 1, 1490885108, 1490885108),
(2, 1, 'system_admin_tips', '', '后台所有页面提示', 1, 1490713165, 1490885137),
(3, 1, 'system_annex_upload', '', '附件上传钩子，可扩展上传到第三方存储', 1, 1490884242, 1490885121),
(4, 1, 'system_member_login', '', '会员登陆成功之后的动作', 1, 1490885108, 1490885108),
(5, 1, 'system_member_register', '', '会员注册成功后的动作', 1, 1512610518, 1512610518),
(9, 0, 'user_login', 'module.user', '会员登陆成功之后的动作', 1, 1566055396, 1566055396),
(10, 0, 'user_register', 'module.user', '会员注册成功后的动作', 1, 1566055396, 1566055396),
(11, 0, 'user_delete', 'module.user', '会员删除成功后的动作', 1, 1566055396, 1566055396),
(14, 0, 'send_sms', 'plugins.sms', '短信触发钩子', 1, 1568014754, 1568014754);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_hook_plugins`
--

CREATE TABLE IF NOT EXISTS `hisi_system_hook_plugins` (
  `id` int(11) unsigned NOT NULL,
  `hook` varchar(32) NOT NULL COMMENT '钩子id',
  `plugins` varchar(32) NOT NULL COMMENT '插件标识',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0',
  `mtime` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 钩子-插件对应表';

--
-- 转存表中的数据 `hisi_system_hook_plugins`
--

INSERT INTO `hisi_system_hook_plugins` (`id`, `hook`, `plugins`, `ctime`, `mtime`, `sort`, `status`) VALUES
(1, 'system_admin_index', 'hisiphp', 1509380301, 1509380301, 0, 1),
(4, 'send_sms', 'sms', 1568014754, 1568014754, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_language`
--

CREATE TABLE IF NOT EXISTS `hisi_system_language` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '语言包名称',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '编码',
  `locale` varchar(255) NOT NULL DEFAULT '' COMMENT '本地浏览器语言编码',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT '图标',
  `pack` varchar(100) NOT NULL DEFAULT '' COMMENT '上传的语言包',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 语言包';

--
-- 转存表中的数据 `hisi_system_language`
--

INSERT INTO `hisi_system_language` (`id`, `name`, `code`, `locale`, `icon`, `pack`, `sort`, `status`) VALUES
(1, '简体中文', 'zh-cn', 'zh-CN,zh-CN.UTF-8,zh-cn', '', '1', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_log`
--

CREATE TABLE IF NOT EXISTS `hisi_system_log` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `url` varchar(200) DEFAULT '',
  `param` text,
  `remark` varchar(255) DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `ip` varchar(128) DEFAULT '',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 操作日志';

--
-- 表的结构 `hisi_system_menu`
--

CREATE TABLE IF NOT EXISTS `hisi_system_menu` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID(快捷菜单专用)',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL COMMENT '模块名或插件名，插件名格式:plugins.插件名',
  `title` varchar(20) NOT NULL COMMENT '菜单标题',
  `icon` varchar(80) NOT NULL DEFAULT 'aicon ai-shezhi' COMMENT '菜单图标',
  `url` varchar(200) NOT NULL COMMENT '链接地址(模块/控制器/方法)',
  `param` varchar(200) NOT NULL DEFAULT '' COMMENT '扩展参数',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '打开方式(_blank,_self)',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `debug` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开发模式可见',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `nav` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否为菜单显示，1显示0不显示',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态1显示，0隐藏',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 管理菜单';

--
-- 转存表中的数据 `hisi_system_menu`
--

INSERT INTO `hisi_system_menu` (`id`, `uid`, `pid`, `module`, `title`, `icon`, `url`, `param`, `target`, `sort`, `debug`, `system`, `nav`, `status`, `ctime`) VALUES
(1, 0, 0, 'system', '首页', '', 'system/index', '', '_self', 0, 0, 1, 1, 1, 1490315067),
(2, 0, 0, 'system', '系统', '', 'system/system', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(3, 0, 0, 'system', '插件', 'aicon ai-shezhi', 'system/plugins', '', '_self', 999, 0, 1, 1, 1, 1490315067),
(4, 0, 1, 'system', '快捷菜单', 'aicon ai-caidan', 'system/quick', '', '_self', 0, 0, 1, 1, 1, 1490315067),
(5, 0, 3, 'system', '插件列表', 'aicon ai-mokuaiguanli', 'system/plugins', '', '_self', 0, 0, 1, 1, 1, 1490315067),
(6, 0, 2, 'system', '系统基础', 'aicon ai-gongneng', 'system/system', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(7, 0, 17, 'system', '导入主题SQL', '', 'system/module/exeSql', '', '_self', 10, 0, 1, 0, 1, 1490315067),
(8, 0, 2, 'system', '系统扩展', 'aicon ai-shezhi', 'system/extend', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(9, 0, 4, 'system', '预留占位', '', '', '', '_self', 4, 0, 1, 1, 0, 1490315067),
(10, 0, 6, 'system', '系统设置', 'aicon ai-icon01', 'system/system/index', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(11, 0, 6, 'system', '配置管理', 'aicon ai-peizhiguanli', 'system/config/index', '', '_self', 2, 1, 1, 1, 1, 1490315067),
(12, 0, 6, 'system', '系统菜单', 'aicon ai-systemmenu', 'system/menu/index', '', '_self', 3, 1, 1, 1, 1, 1490315067),
(13, 0, 6, 'system', '管理员角色', '', 'system/user/role', '', '_self', 4, 0, 1, 0, 1, 1490315067),
(14, 0, 6, 'system', '系统管理员', 'aicon ai-tubiao05', 'system/user/index', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(15, 0, 6, 'system', '系统日志', 'aicon ai-xitongrizhi-tiaoshi', 'system/log/index', '', '_self', 7, 0, 1, 1, 1, 1490315067),
(16, 0, 6, 'system', '附件管理', '', 'system/annex/index', '', '_self', 8, 0, 1, 0, 1, 1490315067),
(17, 0, 8, 'system', '本地模块', 'aicon ai-mokuaiguanli1', 'system/module/index', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(18, 0, 8, 'system', '本地插件', 'aicon ai-chajianguanli', 'system/plugins/index', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(19, 0, 8, 'system', '插件钩子', 'aicon ai-icon-test', 'system/hook/index', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(20, 0, 4, 'system', '预留占位', '', '', '', '_self', 1, 0, 1, 1, 0, 1490315067),
(21, 0, 4, 'system', '预留占位', '', '', '', '_self', 2, 0, 1, 1, 0, 1490315067),
(22, 0, 4, 'system', '预留占位', '', '', '', '_self', 1, 0, 1, 1, 0, 1490315067),
(23, 0, 4, 'system', '预留占位', '', '', '', '_self', 2, 0, 1, 1, 0, 1490315067),
(24, 0, 4, 'system', '后台首页', '', 'system/index/index', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(25, 0, 4, 'system', '清空缓存', '', 'system/index/clear', '', '_self', 2, 0, 1, 0, 1, 1490315067),
(26, 0, 12, 'system', '添加菜单', '', 'system/menu/add', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(27, 0, 12, 'system', '修改菜单', '', 'system/menu/edit', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(28, 0, 12, 'system', '删除菜单', '', 'system/menu/del', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(29, 0, 12, 'system', '状态设置', '', 'system/menu/status', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(30, 0, 12, 'system', '排序设置', '', 'system/menu/sort', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(31, 0, 12, 'system', '添加快捷菜单', '', 'system/menu/quick', '', '_self', 6, 0, 1, 1, 1, 1490315067),
(32, 0, 12, 'system', '导出菜单', '', 'system/menu/export', '', '_self', 7, 0, 1, 1, 1, 1490315067),
(33, 0, 13, 'system', '添加角色', '', 'system/user/addrole', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(34, 0, 13, 'system', '修改角色', '', 'system/user/editrole', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(35, 0, 13, 'system', '删除角色', '', 'system/user/delrole', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(36, 0, 13, 'system', '状态设置', '', 'system/user/statusRole', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(37, 0, 14, 'system', '添加管理员', '', 'system/user/adduser', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(38, 0, 14, 'system', '修改管理员', '', 'system/user/edituser', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(39, 0, 14, 'system', '删除管理员', '', 'system/user/deluser', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(40, 0, 14, 'system', '状态设置', '', 'system/user/status', '', '_self', 4, 0, 1, 0, 1, 1490315067),
(41, 0, 4, 'system', '个人信息设置', '', 'system/user/info', '', '_self', 5, 0, 1, 0, 1, 1490315067),
(42, 0, 18, 'system', '安装插件', '', 'system/plugins/install', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(43, 0, 18, 'system', '卸载插件', '', 'system/plugins/uninstall', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(44, 0, 18, 'system', '删除插件', '', 'system/plugins/del', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(45, 0, 18, 'system', '状态设置', '', 'system/plugins/status', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(46, 0, 18, 'system', '生成插件', '', 'system/plugins/design', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(47, 0, 18, 'system', '运行插件', '', 'system/plugins/run', '', '_self', 6, 0, 1, 1, 1, 1490315067),
(48, 0, 18, 'system', '更新插件', '', 'system/plugins/update', '', '_self', 7, 0, 1, 1, 1, 1490315067),
(49, 0, 18, 'system', '插件配置', '', 'system/plugins/setting', '', '_self', 8, 0, 1, 1, 1, 1490315067),
(50, 0, 19, 'system', '添加钩子', '', 'system/hook/add', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(51, 0, 19, 'system', '修改钩子', '', 'system/hook/edit', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(52, 0, 19, 'system', '删除钩子', '', 'system/hook/del', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(53, 0, 19, 'system', '状态设置', '', 'system/hook/status', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(54, 0, 19, 'system', '插件排序', '', 'system/hook/sort', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(55, 0, 11, 'system', '添加配置', '', 'system/config/add', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(56, 0, 11, 'system', '修改配置', '', 'system/config/edit', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(57, 0, 11, 'system', '删除配置', '', 'system/config/del', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(58, 0, 11, 'system', '状态设置', '', 'system/config/status', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(59, 0, 11, 'system', '排序设置', '', 'system/config/sort', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(60, 0, 10, 'system', '基础配置', '', 'system/system/index', 'group=base', '_self', 1, 0, 1, 1, 1, 1490315067),
(61, 0, 10, 'system', '系统配置', '', 'system/system/index', 'group=sys', '_self', 2, 0, 1, 1, 1, 1490315067),
(62, 0, 10, 'system', '上传配置', '', 'system/system/index', 'group=upload', '_self', 3, 0, 1, 1, 1, 1490315067),
(63, 0, 10, 'system', '开发配置', '', 'system/system/index', 'group=develop', '_self', 4, 0, 1, 1, 1, 1490315067),
(64, 0, 17, 'system', '生成模块', '', 'system/module/design', '', '_self', 6, 1, 1, 1, 1, 1490315067),
(65, 0, 17, 'system', '安装模块', '', 'system/module/install', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(66, 0, 17, 'system', '卸载模块', '', 'system/module/uninstall', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(67, 0, 17, 'system', '状态设置', '', 'system/module/status', '', '_self', 3, 0, 1, 1, 1, 1490315067),
(68, 0, 17, 'system', '设置默认模块', '', 'system/module/setdefault', '', '_self', 4, 0, 1, 1, 1, 1490315067),
(69, 0, 17, 'system', '删除模块', '', 'system/module/del', '', '_self', 5, 0, 1, 1, 1, 1490315067),
(70, 0, 4, 'system', '预留占位', '', '', '', '_self', 1, 0, 1, 1, 0, 1490315067),
(71, 0, 4, 'system', '预留占位', '', '', '', '_self', 2, 0, 1, 1, 0, 1490315067),
(72, 0, 4, 'system', '预留占位', '', '', '', '_self', 3, 0, 1, 1, 0, 1490315067),
(73, 0, 4, 'system', '预留占位', '', '', '', '_self', 4, 0, 1, 1, 0, 1490315067),
(74, 0, 4, 'system', '预留占位', '', '', '', '_self', 5, 0, 1, 1, 0, 1490315067),
(75, 0, 4, 'system', '预留占位', '', '', '', '_self', 0, 0, 1, 1, 0, 1490315067),
(76, 0, 4, 'system', '预留占位', '', '', '', '_self', 0, 0, 1, 1, 0, 1490315067),
(77, 0, 4, 'system', '预留占位', '', '', '', '_self', 0, 0, 1, 1, 0, 1490315067),
(78, 0, 16, 'system', '附件上传', '', 'system/annex/upload', '', '_self', 1, 0, 1, 1, 1, 1490315067),
(79, 0, 16, 'system', '删除附件', '', 'system/annex/del', '', '_self', 2, 0, 1, 1, 1, 1490315067),
(80, 0, 8, 'system', '框架升级', 'aicon ai-iconfontshengji', 'system/upgrade/index', '', '_self', 4, 0, 1, 1, 1, 1491352728),
(81, 0, 80, 'system', '获取升级列表', '', 'system/upgrade/lists', '', '_self', 0, 0, 1, 1, 1, 1491353504),
(82, 0, 80, 'system', '安装升级包', '', 'system/upgrade/install', '', '_self', 0, 0, 1, 1, 1, 1491353568),
(83, 0, 80, 'system', '下载升级包', '', 'system/upgrade/download', '', '_self', 0, 0, 1, 1, 1, 1491395830),
(84, 0, 6, 'system', '数据库管理', 'aicon ai-shujukuguanli', 'system/database/index', '', '_self', 6, 0, 1, 1, 1, 1491461136),
(85, 0, 84, 'system', '备份数据库', '', 'system/database/export', '', '_self', 0, 0, 1, 1, 1, 1491461250),
(86, 0, 84, 'system', '恢复数据库', '', 'system/database/import', '', '_self', 0, 0, 1, 1, 1, 1491461315),
(87, 0, 84, 'system', '优化数据库', '', 'system/database/optimize', '', '_self', 0, 0, 1, 1, 1, 1491467000),
(88, 0, 84, 'system', '删除备份', '', 'system/database/del', '', '_self', 0, 0, 1, 1, 1, 1491467058),
(89, 0, 84, 'system', '修复数据库', '', 'system/database/repair', '', '_self', 0, 0, 1, 1, 1, 1491880879),
(90, 0, 21, 'system', '设置默认等级', '', 'system/member/setdefault', '', '_self', 0, 0, 1, 1, 1, 1491966585),
(91, 0, 10, 'system', '数据库配置', '', 'system/system/index', 'group=databases', '_self', 5, 0, 1, 0, 1, 1492072213),
(92, 0, 17, 'system', '模块打包', '', 'system/module/package', '', '_self', 7, 0, 1, 1, 1, 1492134693),
(93, 0, 18, 'system', '插件打包', '', 'system/plugins/package', '', '_self', 0, 0, 1, 1, 1, 1492134743),
(94, 0, 17, 'system', '主题管理', '', 'system/module/theme', '', '_self', 8, 0, 1, 1, 1, 1492433470),
(95, 0, 17, 'system', '设置默认主题', '', 'system/module/setdefaulttheme', '', '_self', 9, 0, 1, 1, 1, 1492433618),
(96, 0, 17, 'system', '删除主题', '', 'system/module/deltheme', '', '_self', 10, 0, 1, 1, 1, 1490315067),
(97, 0, 6, 'system', '语言包管理', '', 'system/language/index', '', '_self', 9, 0, 1, 0, 1, 1490315067),
(98, 0, 97, 'system', '添加语言包', '', 'system/language/add', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(99, 0, 97, 'system', '修改语言包', '', 'system/language/edit', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(100, 0, 97, 'system', '删除语言包', '', 'system/language/del', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(101, 0, 97, 'system', '排序设置', '', 'system/language/sort', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(102, 0, 97, 'system', '状态设置', '', 'system/language/status', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(103, 0, 16, 'system', '收藏夹图标上传', '', 'system/annex/favicon', '', '_self', 3, 0, 1, 0, 1, 1490315067),
(104, 0, 17, 'system', '导入模块', '', 'system/module/import', '', '_self', 11, 0, 1, 0, 1, 1490315067),
(105, 0, 4, 'system', '后台首页', '', 'system/index/welcome', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(106, 0, 4, 'system', '布局切换', '', 'system/user/iframe', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(107, 0, 15, 'system', '删除日志', '', 'system/log/del', 'table=admin_log', '_self', 100, 0, 1, 0, 1, 1490315067),
(108, 0, 15, 'system', '清空日志', '', 'system/log/clear', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(109, 0, 17, 'system', '编辑模块', '', 'system/module/edit', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(110, 0, 17, 'system', '模块图标上传', '', 'system/module/icon', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(111, 0, 18, 'system', '导入插件', '', 'system/plugins/import', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(112, 0, 4, 'system', '钩子插件状态', '', 'system/hook/hookPluginsStatus', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(113, 0, 4, 'system', '设置主题', '', 'system/user/setTheme', '', '_self', 100, 0, 1, 0, 1, 1490315067),
(114, 0, 8, 'system', '应用市场', 'aicon ai-app-store', 'system/store/index', '', '_self', 0, 0, 1, 1, 1, 1490315067),
(115, 0, 114, 'system', '安装应用', '', 'system/store/install', '', '_self', 0, 0, 1, 1, 1, 1490315067),
(116, 0, 21, 'system', '重置密码', '', 'system/member/resetPwd', '', '_self', 6, 0, 1, 1, 1, 1490315067),
(117, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(118, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(119, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(120, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(121, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(122, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(123, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(124, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(125, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(126, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(127, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(128, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(129, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(130, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(131, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(132, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(133, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(134, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(135, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(136, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(137, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(138, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(139, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(140, 0, 4, 'system', '预留占位', '', '', '', '_self', 100, 0, 1, 1, 0, 1490315067),
(141, 0, 6, 'nb_api', 'API授权', 'aicon ai-shezhi', 'nb_api/index/index', '', '_self', 100, 0, 0, 1, 1, 1566054725),
(156, 0, 0, 'user', '会员', 'hs-icon hs-icon-vip', 'user', '', '_self', 100, 0, 0, 1, 1, 1566055396),
(157, 0, 156, 'user', '会员管理', 'hs-icon hs-icon-users', 'user/index', '', '_self', 2, 0, 1, 1, 1, 1566055396),
(158, 0, 157, 'user', '会员分组', 'aicon ai-huiyuandengji', 'user/group/index', '', '_self', 1, 0, 1, 1, 1, 1566055396),
(159, 0, 158, 'user', '添加分组', '', 'user/group/add', '', '_self', 0, 0, 1, 1, 1, 1566055396),
(160, 0, 158, 'user', '修改分组', '', 'user/group/edit', '', '_self', 0, 0, 1, 1, 1, 1566055396),
(161, 0, 158, 'user', '删除分组', '', 'user/group/del', '', '_self', 0, 0, 1, 1, 1, 1566055396),
(162, 0, 158, 'user', '设置默认分组', '', 'user/group/setDefault', '', '_self', 0, 0, 1, 1, 1, 1566055396),
(163, 0, 157, 'user', '会员列表', 'aicon ai-huiyuanliebiao', 'user/index/index', '', '_self', 2, 0, 1, 1, 1, 1566055396),
(164, 0, 163, 'user', '实名认证', '', 'user/index/certification', '', '_self', 0, 0, 0, 0, 1, 1566055396),
(165, 0, 163, 'user', '添加会员', '', 'user/index/add', '', '_self', 1, 0, 1, 1, 1, 1566055396),
(166, 0, 163, 'user', '修改会员', '', 'user/index/edit', '', '_self', 2, 0, 1, 1, 1, 1566055396),
(167, 0, 163, 'user', '删除会员', '', 'user/index/del', '', '_self', 3, 0, 1, 1, 1, 1566055396),
(168, 0, 163, 'user', '状态设置', '', 'user/index/status', '', '_self', 4, 0, 1, 1, 1, 1566055396),
(169, 0, 163, 'user', '[弹窗]会员选择', '', 'user/index/pop', '', '_self', 5, 0, 1, 1, 1, 1566055396),
(170, 0, 163, 'user', '重置密码', '', 'user/index/resetPwd', '', '_self', 6, 0, 1, 1, 1, 1566055396),
(171, 0, 0, 'videos', '视频管理', 'aicon ai-shezhi', 'videos', '', '_self', 101, 0, 0, 0, 1, 1566055580),
(172, 0, 0, 'banner', '广告管理', 'aicon ai-shezhi', 'banner', '', '_self', 102, 0, 0, 1, 1, 1566180353),
(173, 0, 172, 'banner', '广告管理', '', 'banner/banner/index', '', '_self', 0, 0, 0, 1, 1, 1566180353),
(174, 0, 173, 'banner', '轮播图广告', 'typcn typcn-chart-bar-outline', 'banner/banner/index', '', '_self', 1, 0, 0, 1, 1, 1566180353),
(175, 0, 174, 'banner', '编辑', '', 'banner/banner/add', '', '_self', 0, 0, 0, 0, 1, 1566180353),
(176, 0, 173, 'banner', '启动广告图', 'typcn typcn-chart-bar-outline', 'banner/banner/indexad', '', '_self', 2, 0, 0, 1, 0, 1567050936),
(177, 0, 176, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 0, 1567052666),
(178, 0, 0, 'attachment', '附件管理', 'aicon ai-shezhi', 'attachment', '', '_self', 106, 0, 0, 0, 1, 1567095022),
(190, 0, 157, 'user', '反馈留言', 'aicon ai-error', 'user/feedback/index', '', '_self', 3, 0, 0, 1, 1, 1567661975),
(197, 0, 173, 'banner', '首页广告', 'typcn typcn-chart-bar-outline', 'banner/banner/indexHomeAd', '', '_self', 3, 0, 0, 1, 1, 1567770504),
(198, 0, 173, 'banner', '播放页弹窗广告', 'typcn typcn-chart-bar-outline', 'banner/banner/indexPlayerAd', '', '_self', 4, 0, 0, 1, 1, 1567770525),
(199, 0, 173, 'banner', '播放页底部广告', 'typcn typcn-chart-bar-outline', 'banner/banner/publicAd', '', '_self', 5, 0, 0, 1, 1, 1567770539),
(200, 0, 197, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 1, 1567770566),
(201, 0, 198, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 1, 1567770566),
(202, 0, 199, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 1, 1567770566),
(230, 0, 3, 'plugins.sms', '短信服务', 'typcn typcn-messages', 'system/plugins/run', '', '_self', 0, 0, 0, 1, 1, 1568014771),
(231, 0, 230, 'plugins.sms', '短信平台', 'fa fa-gear', 'system/plugins/run', '_a=index&_c=index&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(232, 0, 231, 'plugins.sms', '网关配置', '', 'system/plugins/run', '_a=config&_c=index&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(233, 0, 231, 'plugins.sms', '默认设置', '', 'system/plugins/run', '_a=def&_c=index&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(234, 0, 231, 'plugins.sms', '状态设置', '', 'admin/plugins/status', '_a=status&_c=index&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(235, 0, 230, 'plugins.sms', '短信模板', 'fa fa-envelope', 'system/plugins/run', '_a=index&_c=template&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(236, 0, 235, 'plugins.sms', '删除模板', '', 'system/plugins/run', '_a=del&_c=template&_p=sms', '_self', 0, 0, 0, 0, 1, 1568014771),
(237, 0, 230, 'plugins.sms', '短信日志', 'fa fa-list-ul', 'system/plugins/run', '_a=index&_c=logs&_p=sms', '_self', 0, 0, 0, 1, 1, 1568014771),
(238, 0, 237, 'plugins.sms', '删除日志', '', 'system/plugins/run', '_a=del&_c=logs&_p=sms', '_self', 0, 0, 0, 0, 1, 1568014771),
(239, 0, 5, 'pay', '在线支付', 'fa fa-credit-card', 'pay/index/index', '', '_self', 100, 0, 0, 1, 1, 1568014790),
(240, 0, 239, 'pay', '支付平台安装', '', 'pay/index/install', '', '_self', 0, 0, 0, 1, 1, 1568014790),
(241, 0, 239, 'pay', '支付平台卸载', '', 'pay/index/uninstall', '', '_self', 0, 0, 0, 1, 1, 1568014790),
(242, 0, 239, 'pay', '支付平台状态设置', '', 'pay/index/status', '', '_self', 0, 0, 0, 1, 1, 1568014790),
(243, 0, 239, 'pay', '支付平台配置', '', 'pay/index/config', '', '_self', 0, 0, 0, 1, 1, 1568014790),
(244, 0, 239, 'pay', '支付平台排序', '', 'pay/index/sort', '', '_self', 0, 0, 0, 1, 1, 1568014790),
(245, 0, 239, 'pay', '支付日志', '', 'pay/logs/index', '', '_self', 0, 0, 0, 0, 1, 1568014790),
(246, 0, 239, 'pay', '删除支付日志', '', 'pay/index/logsDel', '', '_self', 0, 0, 0, 0, 1, 1568014790),
(247, 0, 239, 'pay', '上传文件', '', 'pay/index/upload', '', '_self', 0, 0, 0, 0, 1, 1568014790),
(248, 0, 0, 'live', '直播管理', 'aicon ai-shezhi', 'live', '', '_self', 103, 0, 0, 1, 1, 1568105670),
(249, 0, 248, 'live', '直播管理', 'typcn typcn-eye-outline', 'live/index/categoryList', '', '_self', 0, 0, 0, 1, 1, 1568285050),
(250, 0, 249, 'live', '直播栏目', 'typcn typcn-eye-outline', 'live/index/categoryList', '', '_self', 0, 0, 0, 1, 1, 1568285082),
(251, 0, 249, 'live', '节目清单', 'typcn typcn-social-tumbler-circular', 'live/index/liveList', '', '_self', 0, 0, 0, 1, 1, 1568285096),
(257, 0, 0, 'index', '公告管理', 'fa fa-volume-up', 'index', '', '_self', 106, 0, 0, 1, 1, 1568790118),
(258, 0, 257, 'index', '公告管理', '', 'index/msg/index', '', '_self', 1, 0, 0, 1, 1, 1568790118),
(259, 0, 258, 'index', '公告列表', 'fa fa-volume-up', 'index/msg/index', '', '_self', 1, 0, 0, 1, 1, 1568790118),
(269, 0, 173, 'banner', '视频播放前广告', 'typcn typcn-chart-bar-outline', 'banner/banner/videoAd', '', '_self', 6, 0, 0, 1, 1, 1593681051),
(270, 0, 269, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 1, 1593681065),
(271, 0, 173, 'banner', '播放前激励广告', 'typcn typcn-chart-bar-outline', 'banner/banner/playstartad', '', '_self', 7, 0, 0, 1, 1, 1597167518),
(272, 0, 271, 'banner', '编辑', '', 'banner/banner/addad', '', '_self', 0, 0, 0, 0, 1, 1597167721),
(296, 0, 0, 'codekey', '卡密管理', 'aicon ai-shezhi', 'codekey', '', '_self', 100, 0, 0, 1, 1, 1598259228),
(297, 0, 296, 'codekey', '卡密管理', 'fa fa-credit-card', 'codekey/index/index', '', '_self', 0, 0, 1, 1, 1, 1598259228),
(298, 0, 297, 'codekey', '卡密列表', 'fa fa-credit-card', 'codekey/index/index', '', '_self', 0, 0, 0, 1, 1, 1598259228);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_menu_lang`
--

CREATE TABLE IF NOT EXISTS `hisi_system_menu_lang` (
  `id` int(11) unsigned NOT NULL,
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '标题',
  `lang` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '语言包'
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 管理菜单语言包';

--
-- 转存表中的数据 `hisi_system_menu_lang`
--

INSERT INTO `hisi_system_menu_lang` (`id`, `menu_id`, `title`, `lang`) VALUES
(131, 1, '首页', 1),
(132, 2, '系统', 1),
(133, 3, '插件', 1),
(134, 4, '快捷菜单', 1),
(135, 5, '插件列表', 1),
(136, 6, '系统基础', 1),
(137, 7, '预留占位', 1),
(138, 8, '系统扩展', 1),
(139, 9, '开发专用', 1),
(140, 10, '系统设置', 1),
(141, 11, '配置管理', 1),
(142, 12, '系统菜单', 1),
(143, 13, '管理员角色', 1),
(144, 14, '系统管理员', 1),
(145, 15, '系统日志', 1),
(146, 16, '附件管理', 1),
(147, 17, '本地模块', 1),
(148, 18, '本地插件', 1),
(149, 19, '插件钩子', 1),
(150, 20, '预留占位', 1),
(151, 21, '预留占位', 1),
(152, 22, '预留占位', 1),
(153, 23, '预留占位', 1),
(154, 24, '后台首页', 1),
(155, 25, '清空缓存', 1),
(156, 26, '添加菜单', 1),
(157, 27, '修改菜单', 1),
(158, 28, '删除菜单', 1),
(159, 29, '状态设置', 1),
(160, 30, '排序设置', 1),
(161, 31, '添加快捷菜单', 1),
(162, 32, '导出菜单', 1),
(163, 33, '添加角色', 1),
(164, 34, '修改角色', 1),
(165, 35, '删除角色', 1),
(166, 36, '状态设置', 1),
(167, 37, '添加管理员', 1),
(168, 38, '修改管理员', 1),
(169, 39, '删除管理员', 1),
(170, 40, '状态设置', 1),
(171, 41, '个人信息设置', 1),
(172, 42, '安装插件', 1),
(173, 43, '卸载插件', 1),
(174, 44, '删除插件', 1),
(175, 45, '状态设置', 1),
(176, 46, '生成插件', 1),
(177, 47, '运行插件', 1),
(178, 48, '更新插件', 1),
(179, 49, '插件配置', 1),
(180, 50, '添加钩子', 1),
(181, 51, '修改钩子', 1),
(182, 52, '删除钩子', 1),
(183, 53, '状态设置', 1),
(184, 54, '插件排序', 1),
(185, 55, '添加配置', 1),
(186, 56, '修改配置', 1),
(187, 57, '删除配置', 1),
(188, 58, '状态设置', 1),
(189, 59, '排序设置', 1),
(190, 60, '基础配置', 1),
(191, 61, '系统配置', 1),
(192, 62, '上传配置', 1),
(193, 63, '开发配置', 1),
(194, 64, '生成模块', 1),
(195, 65, '安装模块', 1),
(196, 66, '卸载模块', 1),
(197, 67, '状态设置', 1),
(198, 68, '设置默认模块', 1),
(199, 69, '删除模块', 1),
(200, 70, '预留占位', 1),
(201, 71, '预留占位', 1),
(202, 72, '预留占位', 1),
(203, 73, '预留占位', 1),
(204, 74, '预留占位', 1),
(205, 75, '预留占位', 1),
(206, 76, '预留占位', 1),
(207, 77, '预留占位', 1),
(208, 78, '附件上传', 1),
(209, 79, '删除附件', 1),
(210, 80, '框架升级', 1),
(211, 81, '获取升级列表', 1),
(212, 82, '安装升级包', 1),
(213, 83, '下载升级包', 1),
(214, 84, '数据库管理', 1),
(215, 85, '备份数据库', 1),
(216, 86, '恢复数据库', 1),
(217, 87, '优化数据库', 1),
(218, 88, '删除备份', 1),
(219, 89, '修复数据库', 1),
(220, 90, '设置默认等级', 1),
(221, 91, '数据库配置', 1),
(222, 92, '模块打包', 1),
(223, 93, '插件打包', 1),
(224, 94, '主题管理', 1),
(225, 95, '设置默认主题', 1),
(226, 96, '删除主题', 1),
(227, 97, '语言包管理', 1),
(228, 98, '添加语言包', 1),
(229, 99, '修改语言包', 1),
(230, 100, '删除语言包', 1),
(231, 101, '排序设置', 1),
(232, 102, '状态设置', 1),
(233, 103, '收藏夹图标上传', 1),
(234, 104, '导入模块', 1),
(235, 105, '后台首页', 1),
(236, 106, '布局切换', 1),
(237, 107, '删除日志', 1),
(238, 108, '清空日志', 1),
(239, 109, '编辑模块', 1),
(240, 110, '模块图标上传', 1),
(241, 111, '导入插件', 1),
(242, 112, '钩子插件状态', 1),
(243, 113, '设置主题', 1),
(244, 114, '应用市场', 1),
(245, 115, '安装应用', 1),
(246, 116, '重置密码', 1),
(247, 117, '预留占位', 1),
(248, 118, '预留占位', 1),
(249, 119, '预留占位', 1),
(250, 120, '预留占位', 1),
(251, 121, '预留占位', 1),
(252, 122, '预留占位', 1),
(253, 123, '预留占位', 1),
(254, 124, '预留占位', 1),
(255, 125, '预留占位', 1),
(256, 126, '预留占位', 1),
(257, 127, '预留占位', 1),
(258, 128, '预留占位', 1),
(259, 129, '预留占位', 1),
(260, 130, '预留占位', 1),
(261, 131, '预留占位', 1),
(262, 132, '预留占位', 1),
(263, 133, '预留占位', 1),
(264, 134, '预留占位', 1),
(265, 135, '预留占位', 1),
(266, 136, '预留占位', 1),
(267, 137, '预留占位', 1),
(268, 138, '预留占位', 1),
(269, 139, '预留占位', 1),
(270, 140, '预留占位', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_module`
--

CREATE TABLE IF NOT EXISTS `hisi_system_module` (
  `id` int(10) unsigned NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统模块',
  `name` varchar(50) NOT NULL COMMENT '模块名(英文)',
  `identifier` varchar(100) NOT NULL COMMENT '模块标识(模块名(字母).开发者标识.module)',
  `title` varchar(50) NOT NULL COMMENT '模块标题',
  `intro` varchar(255) NOT NULL COMMENT '模块简介',
  `author` varchar(100) NOT NULL COMMENT '作者',
  `icon` varchar(80) NOT NULL DEFAULT 'aicon ai-mokuaiguanli' COMMENT '图标',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `sort` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未安装，1未启用，2已启用',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认模块(只能有一个)',
  `config` text NOT NULL COMMENT '配置',
  `app_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '应用市场ID(0本地)',
  `app_keys` varchar(200) DEFAULT '' COMMENT '应用秘钥',
  `theme` varchar(50) NOT NULL DEFAULT 'default' COMMENT '主题模板',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 模块';

--
-- 转存表中的数据 `hisi_system_module`
--

INSERT INTO `hisi_system_module` (`id`, `system`, `name`, `identifier`, `title`, `intro`, `author`, `icon`, `version`, `url`, `sort`, `status`, `default`, `config`, `app_id`, `app_keys`, `theme`, `ctime`, `mtime`) VALUES
(1, 1, 'system', 'system.hisiphp.module', '系统管理模块', '系统核心模块，用于后台各项管理功能模块及功能拓展', 'HisiPHP官方出品', '', '1.0.0', 'http://www.hisiphp.com', 0, 2, 0, '', '0', '', 'default', 1489998096, 1489998096),
(2, 1, 'index', 'index.hisiphp.module', '默认模块', '推荐使用扩展模块作为默认首页。', 'HisiPHP官方出品', '', '1.0.0', 'http://www.hisiphp.com', 0, 2, 0, '', '0', '', 'default', 1489998096, 1489998096),
(3, 1, 'install', 'install.hisiphp.module', '系统安装模块', '系统安装模块，勿动。', 'HisiPHP官方出品', '', '1.0.0', 'http://www.hisiphp.com', 0, 2, 0, '', '0', '', 'default', 1489998096, 1489998096),
(4, 0, 'nb_api', 'nbnat', '分层API', '分层API', 'nbnat', '/static/nb_api/nb_api.png', '1.0.0', 'http://www.nbnat.com', 0, 2, 0, '', '0', '', 'default', 1566054716, 1566054716),
(5, 0, 'user', 'user.1000020.module.2000019', '会员', '', 'hisiphp', '/static/user/user.png', '1.0.2', 'http://www.hisiphp.com', 0, 2, 0, '', '0', 'e2fff5de0f42b28e4aec032d19f27b29', 'default', 1566055017, 1566055017),
(6, 0, 'videos', 'videos.617.module', '视频管理', '视频管理模块', '617', '/static/videos/videos.png', '1.0.0', '', 0, 2, 0, '', '0', '', 'default', 1566055569, 1566055569),
(7, 0, 'banner', 'banner.617.module', '轮播图', '', '617', '/static/banner/banner.png', '1.0.0', '', 0, 2, 0, '', '0', '', 'default', 1566180346, 1566180346),
(8, 0, 'attachment', 'attachment.617.module', '附件管理', '', '617', '/static/attachment/attachment.png', '1.0.0', '', 0, 2, 0, '', '0', '', 'default', 1567095015, 1567095015),
(11, 0, 'pay', 'pay.1000019.module.2000018', '在线支付', '通用支付模块，可用于管理及拓展各类支付方式', 'HisiPHP', '/static/pay/pay.png', '1.0.2', 'http://www.hisiphp.com', 0, 2, 0, '', '0', '', 'default', 1568014703, 1568014703),
(12, 0, 'live', 'live.617.module', '直播管理', '直播管理', '617', '/static/live/live.png', '1.0.0', 'qdapi.com', 0, 2, 0, '', '0', '', 'default', 1568105666, 1568105666),
(13, 0, 'goods', 'goods.617.module', '商品管理', '', '', '/static/goods/goods.png', '1.0.0', '', 0, 0, 0, '', '0', '', 'default', 1568791649, 1568791649),
(14, 0, 'order', 'order.617.module', '订单管理', '', '617', '/static/order/order.png', '1.0.0', 'qdapi.com', 0, 0, 0, '', '0', '', 'default', 1568016018, 1568016018),
(15, 0, 'like', 'like.617.module', '用户喜欢', '用户喜欢的电影', '617', '/static/like/like.png', '1.0.0', '', 0, 0, 0, '', '0', '', 'default', 1573451962, 1573451962),
(16, 0, 'news', 'news.617.module', '新闻管理', '', '617', '/static/news/news.png', '1.0.0', 'https://saas.edkey.cn', 0, 0, 0, '', '0', '', 'default', 1592712915, 1592712915),
(17, 0, 'codekey', 'codekey.617.module', '卡密管理', '会员卡密管理', '617', '/static/codekey/codekey.png', '1.0.0', 'www.qdapi.com', 0, 2, 0, '', '0', '', 'default', 1598259092, 1598259092);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_plugins`
--

CREATE TABLE IF NOT EXISTS `hisi_system_plugins` (
  `id` int(11) unsigned NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL COMMENT '插件名称(英文)',
  `title` varchar(32) NOT NULL COMMENT '插件标题',
  `icon` varchar(64) NOT NULL COMMENT '图标',
  `intro` text NOT NULL COMMENT '插件简介',
  `author` varchar(32) NOT NULL COMMENT '作者',
  `url` varchar(255) NOT NULL COMMENT '作者主页',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `config` text NOT NULL COMMENT '插件配置',
  `app_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '来源(0本地)',
  `app_keys` varchar(200) DEFAULT '' COMMENT '应用秘钥',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 插件表';

--
-- 转存表中的数据 `hisi_system_plugins`
--

INSERT INTO `hisi_system_plugins` (`id`, `system`, `name`, `title`, `icon`, `intro`, `author`, `url`, `version`, `identifier`, `config`, `app_id`, `app_keys`, `ctime`, `mtime`, `sort`, `status`) VALUES
(1, 1, 'hisiphp', '系统基础信息', '/static/plugins/hisiphp/hisiphp.png', '后台首页展示系统基础信息和开发团队信息', 'HisiPHP', 'http://www.hisiphp.com', '1.0.0', 'hisiphp.hisiphp.plugins', '', '0', '', 1509379331, 1509379331, 0, 2),
(2, 0, 'sms', '短信服务', '/static/plugins/sms/sms.png', '整合多家短信平台，后台可视化配置，统一发送标准，简单几步即可完成配置和使用。', '617', 'http://www.qdapi.com', '1.0.1', 'sms.1000026.plugins.2000024', '', '0', '', 1568014708, 1568014708, 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_role`
--

CREATE TABLE IF NOT EXISTS `hisi_system_role` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `intro` varchar(200) NOT NULL COMMENT '角色简介',
  `auth` text NOT NULL COMMENT '角色权限',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 管理角色';

--
-- 转存表中的数据 `hisi_system_role`
--

INSERT INTO `hisi_system_role` (`id`, `name`, `intro`, `auth`, `ctime`, `mtime`, `status`) VALUES
(1, '超级管理员', '拥有系统最高权限', '0', 1489411760, 0, 1),
(2, '系统管理员', '拥有系统管理员权限', '["1","4","25","24","2","6","10","60","61","62","63","91","11","55","56","57","58","59","12","26","27","28","29","30","31","32","13","33","34","35","36","14","37","38","39","40","41","16","78","79","84","85","86","87","88","89","7","20","75","76","77","21","90","70","71","72","73","74","8","17","65","66","67","68","94","95","18","42","43","45","47","48","49","19","80","81","82","83","9","22","23","3","5"]', 1489411760, 1507731116, 1),
(3, '普通管理员', '普通管理员', '{"0":"1","1":"4","2":"25","4":"24","6":"106","8":"113"}', 1507737902, 1542075415, 1),
(4, '演示', '', '{"0":"1","1":"4","3":"41","4":"24","5":"105","8":"113","9":"2","10":"6","11":"10","12":"60","17":"11","23":"12","31":"13","36":"14","41":"84","47":"15","50":"16","54":"97","60":"141","61":"179","72":"8","75":"17","90":"18","101":"19","113":"156","114":"157","115":"158","120":"163","128":"190","130":"172","131":"173","132":"174","134":"176"}', 1567737988, 1567737988, 1);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_system_user`
--

CREATE TABLE IF NOT EXISTS `hisi_system_user` (
  `id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL,
  `nick` varchar(50) NOT NULL COMMENT '昵称',
  `mobile` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `auth` text NOT NULL COMMENT '权限',
  `iframe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0默认，1框架',
  `theme` varchar(50) NOT NULL DEFAULT 'default' COMMENT '主题',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `last_login_ip` varchar(128) NOT NULL COMMENT '最后登陆IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 管理用户';

--
-- 转存表中的数据 `hisi_system_user`
--

INSERT INTO `hisi_system_user` (`id`, `role_id`, `username`, `password`, `nick`, `mobile`, `email`, `auth`, `iframe`, `theme`, `status`, `last_login_ip`, `last_login_time`, `ctime`, `mtime`) VALUES
(1, 1, 'admin', '$2y$10$WXqxZPgbA09XnaRPu7ZSZ.Z5HVNdr1N9K5vhJQBjIplL6Q764ZZ1W', '超级管理员', '', '', '', 0, '0', 1, '101.88.238.113', 1598452245, 1566054317, 1598453146);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user`
--

CREATE TABLE IF NOT EXISTS `hisi_user` (
  `id` int(10) unsigned NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `pidstr` text COMMENT '父id目录',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员分组ID',
  `nick` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `mobile` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '手机号',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(128) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(16) NOT NULL DEFAULT '' COMMENT '密码混淆',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用金额',
  `number` smallint(6) NOT NULL DEFAULT '1' COMMENT '计次条数',
  `frozen_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `income` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '收入统计',
  `expend` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '开支统计',
  `exper` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `integral` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '信用评分',
  `lock_number` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '超时未支付次数',
  `frozen_integral` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '冻结积分',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别(1男，0女)',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `drice_id` varchar(2000) NOT NULL DEFAULT '' COMMENT '设备信息',
  `last_login_ip` varchar(128) NOT NULL DEFAULT '' COMMENT '最后登陆IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间(0永久)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0禁用，1正常)',
  `wxmp_openid` varchar(100) NOT NULL DEFAULT '' COMMENT '小程序openid',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 会员表';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_certification`
--

CREATE TABLE IF NOT EXISTS `hisi_user_certification` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `man_id` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
  `true_name` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别 1男2女',
  `man_avatar` text NOT NULL COMMENT '真实自拍头像',
  `man_id_img` text NOT NULL COMMENT '身份证正反照片',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态 1 待审核 2 已通过 3 已驳回',
  `overrule_msg` text NOT NULL COMMENT '驳回理由',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户实名认证';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_group`
--

CREATE TABLE IF NOT EXISTS `hisi_user_group` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL COMMENT '等级名称',
  `min_exper` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最小经验值',
  `max_exper` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大经验值',
  `discount` int(2) unsigned NOT NULL DEFAULT '100' COMMENT '折扣率(%)',
  `intro` varchar(255) NOT NULL COMMENT '等级简介',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认等级',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员有效期(天)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 会员等级';

--
-- 转存表中的数据 `hisi_user_group`
--

INSERT INTO `hisi_user_group` (`id`, `name`, `min_exper`, `max_exper`, `discount`, `intro`, `default`, `expire`, `status`, `ctime`, `mtime`) VALUES
(1, '普通会员', 0, 0, 100, '', 1, 0, 1, 0, 1545105600),
(2, '天卡', 0, 0, 100, '', 0, 1, 1, 0, 0),
(3, '月费', 0, 0, 100, '', 0, 30, 1, 0, 0),
(4, '季费', 0, 0, 100, '', 0, 60, 1, 0, 0),
(5, '年费', 0, 0, 100, '', 0, 365, 1, 0, 0),
(6, '超级会员', 0, 0, 100, '', 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_group_codekey`
--

CREATE TABLE IF NOT EXISTS `hisi_user_group_codekey` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '绑定用户',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '对应会员组',
  `codekey` varchar(32) NOT NULL DEFAULT '' COMMENT '卡密',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '使用状态 0 未使用 1 已使用',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员卡密';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_like`
--

CREATE TABLE IF NOT EXISTS `hisi_user_like` (
  `id` int(10) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `vid` text NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_token`
--

CREATE TABLE IF NOT EXISTS `hisi_user_token` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `token` varchar(200) NOT NULL DEFAULT '' COMMENT '用户token',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户token';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_wallet`
--

CREATE TABLE IF NOT EXISTS `hisi_user_wallet` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '返利'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='推广获利（用户钱包）';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_user_wallet_log`
--

CREATE TABLE IF NOT EXISTS `hisi_user_wallet_log` (
  `id` mediumint(8) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变动金额',
  `msg` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `admin_id` int(10) unsigned DEFAULT '0',
  `money_detail` tinytext NOT NULL COMMENT '余额明细'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='财务变动记录表';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_videos_danmu`
--

CREATE TABLE IF NOT EXISTS `hisi_videos_danmu` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `vid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '视频id',
  `index` tinyint(3) NOT NULL DEFAULT '0' COMMENT '剧集索引',
  `text` varchar(150) NOT NULL DEFAULT '' COMMENT '弹幕内容',
  `color` varchar(150) NOT NULL DEFAULT '' COMMENT '弹幕颜色',
  `time` float(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '弹幕时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='视频弹幕';

-- --------------------------------------------------------

--
-- 表的结构 `hisi_videos_type`
--

CREATE TABLE IF NOT EXISTS `hisi_videos_type` (
  `type_id` smallint(6) unsigned NOT NULL,
  `type_name` varchar(60) NOT NULL DEFAULT '',
  `type_en` varchar(60) NOT NULL DEFAULT '',
  `type_sort` smallint(6) unsigned NOT NULL DEFAULT '0',
  `type_mid` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '分类类型 1 视频 2其他',
  `type_pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `type_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type_tpl` varchar(30) NOT NULL DEFAULT '',
  `type_tpl_list` varchar(30) NOT NULL DEFAULT '',
  `type_tpl_detail` varchar(30) NOT NULL DEFAULT '',
  `type_tpl_play` varchar(30) NOT NULL DEFAULT '',
  `type_tpl_down` varchar(30) NOT NULL DEFAULT '',
  `type_key` varchar(255) NOT NULL DEFAULT '',
  `type_des` varchar(255) NOT NULL DEFAULT '',
  `type_title` varchar(255) NOT NULL DEFAULT '',
  `type_union` varchar(255) NOT NULL DEFAULT '',
  `type_extend` text
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 表的结构 `hisi_video_parse`
--

CREATE TABLE IF NOT EXISTS `hisi_video_parse` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `video_url` varchar(3000) NOT NULL DEFAULT '' COMMENT '需要解析的地址',
  `video_text` text NOT NULL COMMENT '视频信息',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='水印解析记录表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hisi_ad`
--
ALTER TABLE `hisi_ad`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_attachment`
--
ALTER TABLE `hisi_attachment`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_banner`
--
ALTER TABLE `hisi_banner`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_feedback`
--
ALTER TABLE `hisi_feedback`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_goods`
--
ALTER TABLE `hisi_goods`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_goods_category`
--
ALTER TABLE `hisi_goods_category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_live`
--
ALTER TABLE `hisi_live`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_live_category`
--
ALTER TABLE `hisi_live_category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_msg`
--
ALTER TABLE `hisi_msg`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_nb_api`
--
ALTER TABLE `hisi_nb_api`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_order`
--
ALTER TABLE `hisi_order`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_order_trade`
--
ALTER TABLE `hisi_order_trade`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `trade_no` (`trade_no`) USING BTREE;

--
-- Indexes for table `hisi_pay_log`
--
ALTER TABLE `hisi_pay_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_pay_payment`
--
ALTER TABLE `hisi_pay_payment`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_plugins_sms`
--
ALTER TABLE `hisi_plugins_sms`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_plugins_sms_log`
--
ALTER TABLE `hisi_plugins_sms_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_plugins_sms_template`
--
ALTER TABLE `hisi_plugins_sms_template`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_plugins_sms_template_index`
--
ALTER TABLE `hisi_plugins_sms_template_index`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_annex`
--
ALTER TABLE `hisi_system_annex`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `hash` (`hash`) USING BTREE;

--
-- Indexes for table `hisi_system_annex_group`
--
ALTER TABLE `hisi_system_annex_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_config`
--
ALTER TABLE `hisi_system_config`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_hook`
--
ALTER TABLE `hisi_system_hook`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `hisi_system_hook_plugins`
--
ALTER TABLE `hisi_system_hook_plugins`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_language`
--
ALTER TABLE `hisi_system_language`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `code` (`code`) USING BTREE;

--
-- Indexes for table `hisi_system_log`
--
ALTER TABLE `hisi_system_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_menu`
--
ALTER TABLE `hisi_system_menu`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_menu_lang`
--
ALTER TABLE `hisi_system_menu_lang`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_module`
--
ALTER TABLE `hisi_system_module`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE,
  ADD UNIQUE KEY `identifier` (`identifier`) USING BTREE;

--
-- Indexes for table `hisi_system_plugins`
--
ALTER TABLE `hisi_system_plugins`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_system_role`
--
ALTER TABLE `hisi_system_role`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `hisi_system_user`
--
ALTER TABLE `hisi_system_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_user`
--
ALTER TABLE `hisi_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_user_certification`
--
ALTER TABLE `hisi_user_certification`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `man_id` (`man_id`) USING BTREE;

--
-- Indexes for table `hisi_user_group`
--
ALTER TABLE `hisi_user_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_user_group_codekey`
--
ALTER TABLE `hisi_user_group_codekey`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `codekey` (`codekey`) USING BTREE;

--
-- Indexes for table `hisi_user_like`
--
ALTER TABLE `hisi_user_like`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_user_token`
--
ALTER TABLE `hisi_user_token`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `token` (`token`) USING BTREE;

--
-- Indexes for table `hisi_user_wallet`
--
ALTER TABLE `hisi_user_wallet`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_user_wallet_log`
--
ALTER TABLE `hisi_user_wallet_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_videos_danmu`
--
ALTER TABLE `hisi_videos_danmu`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hisi_videos_type`
--
ALTER TABLE `hisi_videos_type`
  ADD PRIMARY KEY (`type_id`) USING BTREE,
  ADD KEY `type_sort` (`type_sort`) USING BTREE,
  ADD KEY `type_pid` (`type_pid`) USING BTREE,
  ADD KEY `type_name` (`type_name`) USING BTREE,
  ADD KEY `type_en` (`type_en`) USING BTREE,
  ADD KEY `type_mid` (`type_mid`) USING BTREE;

--
-- Indexes for table `hisi_video_parse`
--
ALTER TABLE `hisi_video_parse`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `video_url` (`video_url`(250)) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hisi_ad`
--
ALTER TABLE `hisi_ad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_attachment`
--
ALTER TABLE `hisi_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_banner`
--
ALTER TABLE `hisi_banner`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_feedback`
--
ALTER TABLE `hisi_feedback`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_goods`
--
ALTER TABLE `hisi_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `hisi_goods_category`
--
ALTER TABLE `hisi_goods_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hisi_live`
--
ALTER TABLE `hisi_live`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `hisi_live_category`
--
ALTER TABLE `hisi_live_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `hisi_msg`
--
ALTER TABLE `hisi_msg`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_nb_api`
--
ALTER TABLE `hisi_nb_api`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hisi_order`
--
ALTER TABLE `hisi_order`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_order_trade`
--
ALTER TABLE `hisi_order_trade`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_pay_log`
--
ALTER TABLE `hisi_pay_log`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_pay_payment`
--
ALTER TABLE `hisi_pay_payment`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_plugins_sms`
--
ALTER TABLE `hisi_plugins_sms`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_plugins_sms_log`
--
ALTER TABLE `hisi_plugins_sms_log`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_plugins_sms_template`
--
ALTER TABLE `hisi_plugins_sms_template`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_plugins_sms_template_index`
--
ALTER TABLE `hisi_plugins_sms_template_index`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_system_annex`
--
ALTER TABLE `hisi_system_annex`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_system_annex_group`
--
ALTER TABLE `hisi_system_annex_group`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_system_config`
--
ALTER TABLE `hisi_system_config`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `hisi_system_hook`
--
ALTER TABLE `hisi_system_hook`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `hisi_system_hook_plugins`
--
ALTER TABLE `hisi_system_hook_plugins`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `hisi_system_language`
--
ALTER TABLE `hisi_system_language`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hisi_system_log`
--
ALTER TABLE `hisi_system_log`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT for table `hisi_system_menu`
--
ALTER TABLE `hisi_system_menu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=299;
--
-- AUTO_INCREMENT for table `hisi_system_menu_lang`
--
ALTER TABLE `hisi_system_menu_lang`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=271;
--
-- AUTO_INCREMENT for table `hisi_system_module`
--
ALTER TABLE `hisi_system_module`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `hisi_system_plugins`
--
ALTER TABLE `hisi_system_plugins`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `hisi_system_role`
--
ALTER TABLE `hisi_system_role`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `hisi_system_user`
--
ALTER TABLE `hisi_system_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `hisi_user`
--
ALTER TABLE `hisi_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_certification`
--
ALTER TABLE `hisi_user_certification`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_group`
--
ALTER TABLE `hisi_user_group`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `hisi_user_group_codekey`
--
ALTER TABLE `hisi_user_group_codekey`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_like`
--
ALTER TABLE `hisi_user_like`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_token`
--
ALTER TABLE `hisi_user_token`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_wallet`
--
ALTER TABLE `hisi_user_wallet`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_user_wallet_log`
--
ALTER TABLE `hisi_user_wallet_log`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hisi_videos_danmu`
--
ALTER TABLE `hisi_videos_danmu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `hisi_videos_type`
--
ALTER TABLE `hisi_videos_type`
  MODIFY `type_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `hisi_video_parse`
--
ALTER TABLE `hisi_video_parse`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

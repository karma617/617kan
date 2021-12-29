/*
 sql安装文件
*/
DROP TABLE IF EXISTS `hisi_plugins_sms`;
CREATE TABLE `hisi_plugins_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '网关标题',
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `config` text COMMENT '网关配置',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0停用，1启用)',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认',
  `bind_template` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '绑定模板',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='短信网关平台';

DROP TABLE IF EXISTS `hisi_plugins_sms_log`;
CREATE TABLE `hisi_plugins_sms_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `template` varchar(50) DEFAULT '' COMMENT '网关模板',
  `content` text COMMENT '短信内容',
  `params` text COMMENT '请求参数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态(1成功，0失败)',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='短信发送日志';

DROP TABLE IF EXISTS `hisi_plugins_sms_template`;
CREATE TABLE `hisi_plugins_sms_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '模板别名',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '模板标题',
  `example` text COMMENT '示例内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='短信模板';

DROP TABLE IF EXISTS `hisi_plugins_sms_template_index`;
CREATE TABLE `hisi_plugins_sms_template_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(20) NOT NULL DEFAULT '' COMMENT '网关代码',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '模板别名',
  `gateway_template` varchar(50) NOT NULL DEFAULT '' COMMENT '网关平台模板id或code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='短信模板索引';
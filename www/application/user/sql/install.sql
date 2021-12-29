/*
 sql安装文件
*/
CREATE TABLE `hisi_user` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`pid` INT(11) NOT NULL DEFAULT '0' COMMENT '父级id',
	`group_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员分组ID 1 车主 2 货主',
	`nick` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '昵称',
	`username` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '用户名',
	`mobile` BIGINT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '手机号',
	`email` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '邮箱',
	`password` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '密码',
	`salt` VARCHAR(16) NOT NULL DEFAULT '' COMMENT '密码混淆',
	`money` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '可用金额',
	`frozen_money` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
	`income` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '收入统计',
	`expend` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '开支统计',
	`exper` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '经验值',
	`integral` INT(10) UNSIGNED NOT NULL DEFAULT '100' COMMENT '信用评分',
	`lock_number` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '超时未支付次数',
	`frozen_integral` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '冻结积分',
	`sex` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '性别(1男，0女)',
	`avatar` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
	`last_login_ip` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '最后登陆IP',
	`last_login_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
	`login_count` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆次数',
	`expire_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '到期时间(0永久)',
	`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态(0禁用，1正常)',
	`ctime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`mtime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000001 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='[系统] 会员表';

INSERT INTO `hisi_user` (`id`, `group_id`, `nick`, `username`, `mobile`, `email`, `password`, `salt`, `money`, `frozen_money`, `income`, `expend`, `exper`, `integral`, `frozen_integral`, `sex`, `avatar`, `last_login_ip`, `last_login_time`, `login_count`, `expire_time`, `status`, `ctime`, `mtime`)
VALUES
  (1000001, 1, 'ceshi', 'ceshi', 0, '', '7a2936cbd4de2638447a2ac96112a24e', 'xUofqHuwo8kUV3fv', 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 1, '', '', 0, 0, 0, 0, 1545095106, 1546343702);

CREATE TABLE `hisi_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT '等级名称',
  `min_exper` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最小经验值',
  `max_exper` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大经验值',
  `discount` int(2) unsigned NOT NULL DEFAULT '100' COMMENT '折扣率(%)',
  `intro` varchar(255) NOT NULL COMMENT '等级简介',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认等级',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员有效期(天)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='[系统] 会员等级';

INSERT INTO `hisi_user_group` (`name`, `min_exper`, `max_exper`, `discount`, `intro`, `default`, `expire`, `status`, `ctime`, `mtime`)
VALUES
  ('注册会员', 0, 0, 100, '', 1, 0, 1, 0, 1545105600);

CREATE TABLE `hisi_user_token` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uid` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
	`token` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '用户token',
	`create_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
	`update_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	`expire_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '到期时间',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户token';

CREATE TABLE `hisi_user_certification` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uid` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
	`man_id` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '身份证号',
	`car_id` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '驾驶证号',
	`true_name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
	`sex` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '性别 1男2女',
	`car_date` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '驾驶证登记日期',
	`car_exp_date` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '驾驶证有效期',
	`car_number` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车牌号',
	`car_color` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车辆颜色',
	`car_number_type` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车牌类型',
	`car_type` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车型',
	`car_length` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车长',
	`car_weight` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '载重',
	`car_frame_number` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '车架号',
	`car_drever_number` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '发动机号',
	`car_man_address` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '车主地址',
	`car_man_address_info` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '详细地址',
	`man_avatar` TEXT NOT NULL COMMENT '真实自拍头像',
	`man_id_img` TEXT NOT NULL COMMENT '身份证正反照片',
	`car_id_img` TEXT NOT NULL COMMENT '驾驶证正副照片',
	`car_drever_img` TEXT NOT NULL COMMENT '行驶证正反照片',
	`car_img` TEXT NOT NULL COMMENT '车辆照片 逗号分隔',
	`status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '审核状态 1 待审核 2 已通过 3 已驳回',
	`overrule_msg` TEXT NOT NULL COMMENT '驳回理由',
	`create_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '申请时间',
	`update_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '审核时间',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `man_id` (`man_id`)
) COMMENT='用户实名认证' COLLATE='utf8_general_ci' ENGINE=MyISAM;

CREATE TABLE `hisi_user_wallet` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uid` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
	`money` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '返利',
	PRIMARY KEY (`id`)
) COMMENT='推广获利（用户钱包）' COLLATE='utf8_general_ci' ENGINE=MyISAM;

CREATE TABLE `hisi_user_wallet_log` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uid` MEDIUMINT(8) UNSIGNED NOT NULL,
	`value` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '变动金额',
	`msg` TEXT NOT NULL,
	`dateline` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`type` VARCHAR(20) NOT NULL DEFAULT '',
	`admin_id` INT(10) UNSIGNED NULL DEFAULT '0',
	`money_detail` TINYTEXT NOT NULL COMMENT '余额明细',
	PRIMARY KEY (`id`) USING BTREE
) COMMENT='财务变动记录表' COLLATE='utf8_general_ci' ENGINE=MyISAM;


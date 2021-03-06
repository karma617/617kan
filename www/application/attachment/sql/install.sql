/*
 sql安装文件
*/
CREATE TABLE `hisi_attachment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`savepath` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '保存目录',
	`md5` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '文件md5',
	`type` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '图片类型',
	`location` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '储存环境 0 本地 1 云端',
	`create_time` INT(11) NOT NULL DEFAULT '0',
	`update_time` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='附件表';

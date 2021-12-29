/*
 sql安装文件
*/
CREATE TABLE `hisi_user_group_codekey`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '绑定用户',
  `group_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应会员组',
  `codekey` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '卡密',
  `status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '使用状态 0 未使用 1 已使用',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `codekey`(`codekey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员卡密' ROW_FORMAT = Compact;
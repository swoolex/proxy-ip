SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for swx_abroad_ip
-- ----------------------------
DROP TABLE IF EXISTS `swx_abroad_ip`;
CREATE TABLE `swx_abroad_ip` (
  `id` bigint(13) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL COMMENT '端口',
  `response_time` decimal(10,3) DEFAULT NULL COMMENT '响应时间，毫秒',
  `is_ssl` tinyint(1) DEFAULT '0' COMMENT '类型 0.http  1.https',
  `create_time` int(11) DEFAULT NULL COMMENT '入库时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0.不可用 1.可用',
  `success_num` int(11) DEFAULT '0' COMMENT '历史，响应成功次数',
  `error_num` int(11) DEFAULT '0' COMMENT '历史，响应失败次数',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`) USING BTREE,
  KEY `response_time` (`response_time`),
  KEY `update_time` (`update_time`),
  KEY `port` (`port`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='国外-可用IP池';

-- ----------------------------
-- Records of swx_abroad_ip
-- ----------------------------

-- ----------------------------
-- Table structure for swx_abroad_ip_verify
-- ----------------------------
DROP TABLE IF EXISTS `swx_abroad_ip_verify`;
CREATE TABLE `swx_abroad_ip_verify` (
  `id` bigint(13) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `port` (`port`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='国外-待验证IP池';

-- ----------------------------
-- Records of swx_abroad_ip_verify
-- ----------------------------

-- ----------------------------
-- Table structure for swx_china_ip
-- ----------------------------
DROP TABLE IF EXISTS `swx_china_ip`;
CREATE TABLE `swx_china_ip` (
  `id` bigint(13) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `response_time` decimal(10,3) DEFAULT NULL COMMENT '响应时间，毫秒',
  `is_ssl` tinyint(1) DEFAULT '0' COMMENT '类型 0.http  1.https',
  `create_time` int(11) DEFAULT NULL COMMENT '入库时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0.不可用 1.可用',
  `success_num` int(11) DEFAULT '0' COMMENT '历史，响应成功次数',
  `error_num` int(11) DEFAULT '0' COMMENT '历史，响应失败次数',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`) USING BTREE,
  KEY `response_time` (`response_time`) USING BTREE,
  KEY `update_time` (`update_time`) USING BTREE,
  KEY `port` (`port`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='国内-可用IP池';

-- ----------------------------
-- Records of swx_china_ip
-- ----------------------------

-- ----------------------------
-- Table structure for swx_china_ip_verify
-- ----------------------------
DROP TABLE IF EXISTS `swx_china_ip_verify`;
CREATE TABLE `swx_china_ip_verify` (
  `id` bigint(13) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `port` (`port`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='国内-待验证IP池';

-- ----------------------------
-- Records of swx_china_ip_verify
-- ----------------------------

/*
Navicat MySQL Data Transfer

Source Server         : 192.168.117.129
Source Server Version : 50132
Source Host           : 192.168.117.129:3306
Source Database       : dcmd

Target Server Type    : MYSQL
Target Server Version : 50132
File Encoding         : 65001

Date: 2014-11-10 16:07:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dcmd_department
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_department`;
CREATE TABLE `dcmd_department` (
  `depart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`depart_id`),
  UNIQUE KEY `app` (`depart_name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group`;
CREATE TABLE `dcmd_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gname` varchar(64) NOT NULL,
  `gtype` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`gid`),
  UNIQUE KEY `app` (`gname`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_user
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_user`;
CREATE TABLE `dcmd_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `sa` int(10) NOT NULL,
  `admin` int(10) NOT NULL,
  `depart_id` int(10) NOT NULL,
  `tel` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `state` int(11) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_user_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_user_group`;
CREATE TABLE `dcmd_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_group_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group_cmd`;
CREATE TABLE `dcmd_group_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`opr_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_group_repeat_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group_repeat_cmd`;
CREATE TABLE `dcmd_group_repeat_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `repeat_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_node_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_node_group`;
CREATE TABLE `dcmd_node_group` (
  `ngroup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_name` varchar(128) NOT NULL,
  `gid`  int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ngroup_id`),
  UNIQUE KEY `ngroup_name` (`ngroup_name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_node`;
CREATE TABLE `dcmd_node` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `ngroup_id` int(10) NOT NULL,
  `host` varchar(128) NOT NULL,
  `sid` varchar(128) NOT NULL,
  `did` varchar(128) NOT NULL,
  `os_type` varchar(128) NOT NULL,
  `os_ver` varchar(128) NOT NULL,
  `bend_ip` varchar(16) NOT NULL,
  `public_ip` varchar(16) NOT NULL,
  `mach_room` varchar(128) NOT NULL,
  `rack` varchar(32) NOT NULL,
  `seat` varchar(32) NOT NULL,
  `online_time` datetime NOT NULL,
  `server_brand` varchar(128) NOT NULL,
  `server_model` varchar(32) NOT NULL,
  `cpu` varchar(32) NOT NULL,
  `memory` varchar(32) NOT NULL,
  `disk` varchar(64) NOT NULL,
  `purchase_time` datetime NOT NULL,
  `maintain_time` datetime NOT NULL,
  `maintain_fac`  varchar(128) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`nid`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_center
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_center`;
CREATE TABLE `dcmd_center` (
  `host` varchar(32) NOT NULL,
  `master` int(11) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_app
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app`;
CREATE TABLE `dcmd_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL,
  `app_alias` varchar(128) NOT NULL,
  `sa_gid` int(10) unsigned NOT NULL,
  `svr_gid` int(10) unsigned NOT NULL,
  `depart_id` int(10) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `app_name` (`app_name`),
  UNIQUE KEY `app_alias` (`app_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service`;
CREATE TABLE `dcmd_service` (
  `svr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_name` varchar(128) NOT NULL,
  `svr_alias` varchar(128) NOT NULL,
  `svr_path`  varchar(128) NOT NULL,
  `run_user`   varchar(16) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `owner` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_id`),
  UNIQUE KEY `svr_name` (`app_id`, `svr_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_pool`;
CREATE TABLE `dcmd_service_pool` (
  `svr_pool_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_pool_id`),
  UNIQUE KEY `svr_pool` (`svr_id`, `svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_pool_node`;
CREATE TABLE `dcmd_service_pool_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_pool_id` (`svr_pool_id`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_softpkg
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_softpkg`;
CREATE TABLE `dcmd_softpkg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) unsigned NOT NULL,
  `svr_id` int(11) unsigned NOT NULL,
  `version` varchar(64) NOT NULL,
  `repo_file` varchar(256) NOT NULL,
  `upload_file` varchar(256) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repo_file` (`repo_file`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_cmd`;
CREATE TABLE `dcmd_task_cmd` (
  `task_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_cmd_id`),
  UNIQUE KEY `task_cmd` (`task_cmd`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_cmd_arg
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_cmd_arg`;
CREATE TABLE `dcmd_task_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd_id` int(10) unsigned NOT NULL,
  `task_cmd`  varchar(64) NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(10) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_cmd` (`task_cmd_id`,`arg_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_template
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_template`;
CREATE TABLE `dcmd_task_template` (
  `task_tmpt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_name` varchar(128) NOT NULL,
  `task_cmd_id` int(10) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `app_id` int(10) NOT NULL,
  `update_env` int(10) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `process` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_tmpt_id`),
  UNIQUE KEY `task_tmpt_name` (`task_tmpt_name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_template_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_template_service_pool`;
CREATE TABLE `dcmd_task_template_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_tmpt_id` (`task_tmpt_id`,`svr_pool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task`;
CREATE TABLE `dcmd_task` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_name` (`task_name`),
  KEY `idx_dcmd_svr_task_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_history`;
CREATE TABLE `dcmd_task_history` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_finish_name` (`task_name`),
  KEY `idx_task_svr_task_finish_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_task_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_service_pool`;
CREATE TABLE `dcmd_task_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_task_id_svr_pool_id` (`task_id`,`svr_pool_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_service_pool_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_service_pool_history`;
CREATE TABLE `dcmd_task_service_pool_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_node`;
CREATE TABLE `dcmd_task_node` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_node_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_node_history`;
CREATE TABLE `dcmd_task_node_history` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_command
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_command`;
CREATE TABLE `dcmd_command` (
  `cmd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmd_id`),
  KEY `idx_command_svr` (`svr_name`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_command_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_command_history`;
CREATE TABLE `dcmd_command_history` (
  `cmd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmd_id`),
  KEY `idx_command_svr` (`svr_name`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd`;
CREATE TABLE `dcmd_opr_cmd` (
  `opr_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd` varchar(64) NOT NULL,
  `ui_name` varchar(255) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL DEFAULT '',
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`opr_cmd_id`),
  UNIQUE KEY `opr_cmd` (`opr_cmd`),
  UNIQUE KEY `ui_name` (`ui_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_arg
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_arg`;
CREATE TABLE `dcmd_opr_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opr_cmd_id` (`opr_cmd_id`,`arg_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_opr_cmd_exec
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_exec`;
CREATE TABLE `dcmd_opr_cmd_exec` (
  `exec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`exec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_repeat_exec
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec`;
CREATE TABLE `dcmd_opr_cmd_repeat_exec` (
  `repeat_cmd_id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_exec_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_exec_history`;
CREATE TABLE `dcmd_opr_cmd_exec_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exec_id` bigint(20) NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exec_id` (`exec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_repeat_exec_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec_history`;
CREATE TABLE `dcmd_opr_cmd_repeat_exec_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_log
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_log`;
CREATE TABLE `dcmd_opr_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_table` varchar(64) NOT NULL,
  `opr_type` int(11) NOT NULL,
  `sql_statement` text NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_opr_log_table` (`log_table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_cron
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron`;
CREATE TABLE `dcmd_cron` (
  `cron_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(64) NOT NULL,
  `script_name` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `min` varchar(32) NOT NULL,
  `hour` varchar(32) NOT NULL,
  `day` varchar(32) NOT NULL,
  `month` varchar(32) NOT NULL,
  `week` varchar(32) NOT NULL,
  `arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `cron_name` (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_cron_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron_node`;
CREATE TABLE `dcmd_cron_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cron_state` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`cron_id`, `ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_cron_event
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron_event`;
CREATE TABLE `dcmd_cron_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024)  NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`ip`, `cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_monitor
-- ----------------------------
DROP TABLE IF EXISTS Dcmd_Service_monitor;
CREATE TABLE dcmd_service_monitor (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) NOT NULL,
  svr_id int(10) NOT NULL,
  monitor_type   NOT NULL,
  version` int(10) NOT NULL,
  check_script_name varchar(64) NOT NULL,
  check_script_md5  varchar(32) NOT NULL,
  check_script_arg  text,
  start_script_name varchar(64) ,
  start_script_md5  varchar(32) ,
  start_script_arg  text,
  stop_script_name  varchar(64) ,
  stop_script_md5   varchar(32) ,
  stop_script_arg  text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_monitor` (app_id, svr_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_monitor_event
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_monitor_stat;
CREATE TABLE dcmd_service_monitor_stat (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) NOT NULL,
  svr_id int(10) NOT NULL,
  ip varchar(16) NOT NULL,
  level int(10) NOT NULL,
  message varchar(1024)  NOT NULL,
  ctime datetime NOT NULL,
  PRIMARY KEY (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_node_group_attr_def
-- ----------------------------
DROP TABLE IF EXISTS dcmd_node_group_attr_def;
CREATE TABLE dcmd_node_group_attr_def (
  attr_id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  attr_name varchar(32) NOT NULL,
  optional  int(11) NOT NULL,
  attr_type int(10) NOT NULL,
  def_value varchar(256),
  comment   varchar(512) NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  unique index `dcmd_node_group_attr_def` (attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_node_group_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_node_group_attr;
CREATE TABLE dcmd_node_group_attr (
  id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  ngroup_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_node_group_attr` (ngroup_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_arch_diagram
-- ----------------------------
DROP TABLE IF EXISTS dcmd_app_arch_diagram;
CREATE TABLE dcmd_app_arch_diagram (
  id     int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  arch_name varchar(200) NOT NULL,
  diagram   longblob,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_app_arch_diagram` (app_id, arch_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_arch_diagram
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_arch_diagram;
CREATE TABLE dcmd_service_arch_diagram (
  id     int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  arch_name varchar(200) NOT NULL,
  diagram   longblob,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_arch_diagram` (svr_id, arch_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool_attr_def
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_pool_attr_def;
CREATE TABLE dcmd_service_pool_attr_def (
  attr_id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  attr_name varchar(32) NOT NULL,
  optional  int(11) NOT NULL,
  attr_type int(10) NOT NULL,
  def_value varchar(256),
  comment   varchar(512) NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  unique index `dcmd_service_pool_attr_def` (attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_pool_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_pool_attr;
CREATE TABLE dcmd_service_pool_attr (
  id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_pool_attr` (svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_service_pool_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_task_service_pool_attr;
CREATE TABLE dcmd_task_service_pool_attr (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id int(10) unsigned NOT NULL,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_task_service_pool_attr` (task_id, svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_task_service_pool_attr_history
-- ----------------------------
DROP TABLE IF EXISTS dcmd_task_service_pool_attr_history;
CREATE TABLE dcmd_task_service_pool_attr_history (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id int(10) unsigned NOT NULL,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_task_service_pool_attr_history` (task_id, svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Records of dcmd_user
-- ----------------------------
-- INSERT INTO `dcmd_user` VALUES ('1', 'admin', '7f0e2bda548768347223d279fb940c48', '1', '', '', '', '0', '', '2014-02-20 15:55:05', '2014-02-20 15:55:02', '1');


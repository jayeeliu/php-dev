-- 替换 prefix_ 为 需要的表前缀

CREATE TABLE `prefix_allacl` (
  `controller` varchar(64) NOT NULL,
  `action` varchar(64) NOT NULL,
  UNIQUE KEY `controller` (`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='全局可访问的控制';


CREATE TABLE `prefix_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT COMMENT '组id',
  `gname` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '组名',
  `description` text CHARACTER SET utf8 NOT NULL COMMENT '描述',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户组信息';


CREATE TABLE `prefix_groupacl` (
  `gid` int(10) unsigned NOT NULL COMMENT '组号',
  `controller` char(64) NOT NULL,
  `action` char(64) NOT NULL,
  `option` enum('allow','deny') NOT NULL COMMENT '细化权限（页面内部）',
  UNIQUE KEY `gid` (`gid`,`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户组和权限的对应关系';


CREATE TABLE `prefix_left_nav` (
  `lid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lname` char(16) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `order` smallint(6) NOT NULL,
  `ctime` datetime NOT NULL,
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lid`),
  UNIQUE KEY `lname` (`lname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='左部导航菜单信息表';


CREATE TABLE `prefix_menu` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mname` char(16) CHARACTER SET utf8 NOT NULL,
  `description` char(255) CHARACTER SET utf8 NOT NULL,
  `order` int(10) NOT NULL,
  `ctime` datetime NOT NULL,
  `updtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `mname` (`mname`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='顶部导航菜单信息表' ;


CREATE TABLE `prefix_menu_controller` (
  `mid` int(10) unsigned NOT NULL,
  `lid` int(10) unsigned NOT NULL COMMENT 'å·¦å¯¼id',
  `uname` char(16) NOT NULL,
  `controller` char(64) CHARACTER SET ascii NOT NULL,
  `action` char(64) CHARACTER SET ascii NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `is_default` enum('y','n') NOT NULL DEFAULT 'n',
  UNIQUE KEY `mid` (`mid`,`lid`,`controller`,`action`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='导航菜单和action对应关系表';


CREATE TABLE `prefix_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'uid(员工号)',
  `uname` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '用户名',
  `nick` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '昵称',
  `password` char(32) CHARACTER SET utf8 NOT NULL COMMENT '用户密码（可能为空）',
  `description` text CHARACTER SET utf8 NOT NULL COMMENT '描述',
  `isadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是管理员',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `expdate` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='用户信息';


CREATE TABLE `prefix_useracl` (
  `uid` int(10) unsigned NOT NULL,
  `controller` char(64) NOT NULL,
  `action` char(64) NOT NULL,
  `option` enum('allow','deny') NOT NULL COMMENT '细化权限（页面内部）',
  UNIQUE KEY `uid` (`uid`,`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户和用户权限应关系';


CREATE TABLE `prefix_usergroup` (
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户和用户组对应关系';

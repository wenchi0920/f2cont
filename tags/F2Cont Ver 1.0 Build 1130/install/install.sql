CREATE TABLE `f2blog_attachments` (
  `id` int(8) NOT NULL auto_increment,
  `logId` int(12) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `attTitle` varchar(150) NOT NULL default '',
  `fileType` varchar(30) NOT NULL default '',
  `fileSize` int(8) NOT NULL default '0',
  `fileWidth` int(5) NOT NULL default '0',
  `fileHeight` int(5) NOT NULL default '0',
  `downloads` int(5) NOT NULL default '0',
  `postTime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_categories` (
  `id` int(3) NOT NULL auto_increment,
  `parent` int(3) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `orderNo` int(3) NOT NULL default '0',
  `cateTitle` varchar(100) NOT NULL default '',
  `outLinkUrl` varchar(100) NOT NULL default '',
  `cateCount` int(8) NOT NULL default '0',
  `isHidden` tinyint(1) NOT NULL default '0',
  `cateIcons` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`,`name`,`cateTitle`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_comments` (
  `id` int(9) NOT NULL auto_increment,
  `logId` int(11) NOT NULL default '0',
  `parent` int(9) NOT NULL default '0',
  `password` varchar(50) NOT NULL default '',
  `homepage` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `face` varchar(30) NOT NULL default '',
  `content` text NOT NULL,
  `author` varchar(30) NOT NULL default '',
  `postTime` int(10) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `isSecret` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`,`parent`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_dailystatistics` (
  `id` int(8) NOT NULL auto_increment,
  `visitDate` date NOT NULL,
  `visits` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `visitDate` (`visitDate`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_filters` (
  `id` int(3) NOT NULL auto_increment,
  `category` tinyint(1) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_guestbook` (
  `id` int(8) NOT NULL auto_increment,
  `author` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `homepage` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `face` varchar(30) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `postTime` int(10) NOT NULL default '0',
  `isSecret` tinyint(1) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_keywords` (
  `id` int(5) NOT NULL auto_increment,
  `keyword` varchar(50) NOT NULL default '',
  `linkUrl` varchar(250) NOT NULL default '',
  `linkImage` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_linkgroup` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `isSidebar` tinyint(1) NOT NULL default '0',
  `orderNo` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_links` (
  `id` int(11) NOT NULL auto_increment,
  `lnkGrpId` int(3) NOT NULL default '1',
  `name` varchar(100) NOT NULL default '',
  `blogLogo` varchar(150) NOT NULL default '',
  `blogUrl` varchar(150) NOT NULL default '',
  `orderNo` int(3) NOT NULL default '0',
  `isSidebar` tinyint(1) NOT NULL default '0',
  `isApp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_logs` (
  `id` int(12) NOT NULL auto_increment,
  `cateId` int(3) NOT NULL default '0',
  `logTitle` varchar(150) NOT NULL default '',
  `logContent` mediumtext NOT NULL,
  `author` varchar(30) NOT NULL default '',
  `quoteUrl` text NOT NULL,
  `postTime` int(10) NOT NULL default '0',
  `commNums` int(3) NOT NULL default '0',
  `viewNums` int(5) NOT NULL default '0',
  `quoteNums` int(3) NOT NULL default '0',
  `isComment` tinyint(1) NOT NULL default '0',
  `isTrackback` tinyint(1) NOT NULL default '0',
  `isTop` tinyint(1) NOT NULL default '0',
  `weather` varchar(10) NOT NULL default '',
  `saveType` tinyint(1) NOT NULL default '0',
  `markNums` int(8) NOT NULL default '0',
  `tags` varchar(150) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `logsediter` char(4) NOT NULL default 'tiny',
  `autoSplit` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cateId` (`cateId`,`logTitle`,`tags`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_members` (
  `id` int(5) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `nickname` varchar(30) NOT NULL default '',
  `gender` tinyint(1) NOT NULL default '0',
  `email` varchar(100) NOT NULL default '',
  `isHiddenEmail` tinyint(1) NOT NULL default '0',
  `homePage` varchar(100) NOT NULL default '',
  `regTime` int(10) NOT NULL default '0',
  `regIp` varchar(20) NOT NULL default '',
  `role` varchar(8) NOT NULL default 'user',
  `postLogs` int(5) NOT NULL default '0',
  `postComms` int(5) NOT NULL default '0',
  `postMessages` int(5) NOT NULL default '0',
  `lastVisitTime` int(10) NOT NULL default '0',
  `lastVisitIP` varchar(20) NOT NULL default '',
  `hashKey` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_modsetting` (
  `id` int(8) NOT NULL auto_increment,
  `modId` int(5) NOT NULL default '0',
  `keyName` varchar(100) NOT NULL default '',
  `keyValue` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `modId` (`modId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_modules` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `modTitle` varchar(50) NOT NULL default '',
  `disType` tinyint(1) NOT NULL default '0',
  `isHidden` tinyint(1) NOT NULL default '0',
  `indexOnly` tinyint(1) NOT NULL default '0',
  `orderNo` int(3) NOT NULL default '0',
  `isSystem` tinyint(1) NOT NULL default '0',
  `htmlCode` text NOT NULL,
  `pluginPath` varchar(100) NOT NULL default '',
  `isInstall` tinyint(1) NOT NULL default '0',
  `installFolder` varchar(60) NOT NULL default '',
  `installDate` int(10) NOT NULL default '0',
  `settingXml` varchar(60) NOT NULL default '',
  `cateId` int(3) NOT NULL default '0',
  `configPath` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`,`modTitle`,`disType`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_setting` (
  `settName` varchar(30) NOT NULL,
  `settValue` text NOT NULL,
  `settAuto` tinyint(1) NOT NULL default '0'
) ENGINE=MyISAM;

CREATE TABLE `f2blog_tags` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `logNums` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_trackbacks` (
  `id` int(8) NOT NULL auto_increment,
  `logId` int(12) NOT NULL default '0',
  `tbTitle` varchar(100) NOT NULL default '',
  `blogSite` varchar(50) NOT NULL default '',
  `blogUrl` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  `postTime` int(10) NOT NULL default '0',
  `isApp` tinyint(4) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_tbsession` (
  `id` int(11) NOT NULL auto_increment,
  `extra` varchar(50) NOT NULL default '',
  `tbDate` int(11) NOT NULL default '0',
  `logId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
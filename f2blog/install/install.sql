# install

CREATE TABLE `f2blog_attachments` (
  `id` int(8) NOT NULL auto_increment,
  `logId` int(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `attTitle` varchar(50) NOT NULL,
  `fileType` varchar(10) NOT NULL,
  `fileSize` int(8) NOT NULL,
  `fileWidth` int(5) NOT NULL,
  `fileHeight` int(5) NOT NULL,
  `downloads` int(5) NOT NULL,
  `postTime` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_categories` (
  `id` int(3) NOT NULL auto_increment,
  `parent` int(3) NOT NULL,
  `name` varchar(30) NOT NULL,
  `orderNo` int(3) NOT NULL,
  `cateTitle` varchar(100) NOT NULL,
  `outLinkUrl` varchar(100) NOT NULL,
  `cateCount` int(8) NOT NULL,
  `isHidden` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`,`name`,`cateTitle`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_comments` (
  `id` int(9) NOT NULL auto_increment,
  `logId` int(11) NOT NULL,
  `parent` int(9) NOT NULL,
  `password` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(20) NOT NULL,
  `postTime` int(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `isSecret` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`,`parent`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_dailystatistics` (
  `id` int(8) NOT NULL auto_increment,
  `visitDate` date NOT NULL,
  `visits` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `visitDate` (`visitDate`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_filters` (
  `id` int(3) NOT NULL auto_increment,
  `category` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_guestbook` (
  `id` int(8) NOT NULL auto_increment,
  `author` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `homepage` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `face` varchar(30) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `content` mediumtext NOT NULL,
  `postTime` int(10) NOT NULL,
  `isSecret` tinyint(1) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_keywords` (
  `id` int(5) NOT NULL auto_increment,
  `keyword` varchar(30) NOT NULL,
  `linkUrl` varchar(100) NOT NULL,
  `linkImage` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `blogUrl` varchar(70) NOT NULL,
  `orderNo` int(3) NOT NULL,
  `isHidden` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_logs` (
  `id` int(12) NOT NULL auto_increment,
  `cateId` int(3) NOT NULL,
  `logTitle` varchar(100) NOT NULL,
  `logContent` mediumtext NOT NULL,
  `author` varchar(20) NOT NULL,
  `quoteUrl` text NOT NULL,
  `postTime` int(10) NOT NULL,
  `commNums` int(3) NOT NULL default '0',
  `viewNums` varchar(5) NOT NULL default '0',
  `quoteNums` varchar(3) NOT NULL default '0',
  `isComment` tinyint(1) NOT NULL,
  `isTrackback` tinyint(1) NOT NULL,
  `isTop` tinyint(1) NOT NULL,
  `weather` varchar(10) NOT NULL,
  `saveType` tinyint(1) NOT NULL,
  `tags` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cateId` (`cateId`,`logTitle`,`tags`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_members` (
  `id` int(5) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `isHiddenEmail` tinyint(1) NOT NULL,
  `homePage` varchar(60) NOT NULL,
  `regTime` int(10) NOT NULL,
  `regIp` varchar(20) NOT NULL,
  `role` varchar(5) NOT NULL default 'user',
  `postLogs` int(5) NOT NULL,
  `postComms` int(5) NOT NULL,
  `postMessages` int(5) NOT NULL,
  `lastVisitTime` int(10) NOT NULL,
  `lastVisitIP` varchar(20) NOT NULL,
  `hashKey` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_modsetting` (
  `id` int(8) NOT NULL auto_increment,
  `modId` int(5) NOT NULL,
  `keyName` varchar(100) NOT NULL,
  `keyValue` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `modId` (`modId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_modules` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `modTitle` varchar(50) NOT NULL,
  `disType` tinyint(1) NOT NULL,
  `isHidden` tinyint(1) NOT NULL,
  `indexOnly` tinyint(1) NOT NULL,
  `orderNo` int(3) NOT NULL,
  `isSystem` tinyint(1) NOT NULL,
  `htmlCode` text NOT NULL,
  `pluginPath` varchar(60) NOT NULL,
  `isInstall` tinyint(1) NOT NULL,
  `installFolder` varchar(60) NOT NULL,
  `installDate` int(10) NOT NULL,
  `settingXml` varchar(60) NOT NULL,
  `cateId` int(3) NOT NULL,
  `configPath` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`,`modTitle`,`disType`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_setting` (
  `id` int(2) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `blogTitle` varchar(255) NOT NULL,
  `blogUrl` varchar(50) NOT NULL,
  `logo` varchar(30) NOT NULL,
  `favicon` varchar(30) NOT NULL,
  `about` varchar(30) NOT NULL,
  `master` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `logNums` int(12) NOT NULL,
  `commNums` int(8) NOT NULL,
  `tagNums` int(5) NOT NULL,
  `messageNums` int(8) NOT NULL,
  `memberNums` int(5) NOT NULL,
  `visitNums` int(10) NOT NULL,
  `tbNums` int(5) NOT NULL,
  `disType` tinyint(1) NOT NULL,
  `perPageNormal` int(2) NOT NULL,
  `perPageList` int(2) NOT NULL,
  `commPage` int(2) NOT NULL,
  `gbookPage` int(2) NOT NULL,
  `tagPage` int(2) NOT NULL,
  `isLinkTagLog` tinyint(1) NOT NULL,
  `linkTagLog` int(2) NOT NULL,
  `newLog` int(2) NOT NULL,
  `newComm` int(2) NOT NULL,
  `newGbook` int(2) NOT NULL,
  `splitType` tinyint(1) NOT NULL,
  `introChar` int(3) NOT NULL,
  `introLine` int(3) NOT NULL,
  `isProgramRun` tinyint(1) NOT NULL,
  `commTimerout` int(3) NOT NULL,
  `commLength` int(4) NOT NULL,
  `defaultSkin` varchar(20) NOT NULL,
  `createDate` int(10) NOT NULL,
  `isValidateCode` tinyint(1) NOT NULL,
  `isRegister` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `closeReason` text NOT NULL,
  `language` varchar(10) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `timeSystemFormat` varchar(20) NOT NULL,
  `timeDefViewFormat` varchar(15) NOT NULL,
  `timeListViewFormat` varchar(15) NOT NULL,
  `timeCommViewFormat` varchar(15) NOT NULL,
  `timeTbViewFormat` varchar(15) NOT NULL,
  `timeGbookViewFormat` varchar(15) NOT NULL,
  `timeSidebarViewFormat` varchar(15) NOT NULL,
  `attSaveDir` varchar(20) NOT NULL,
  `attSaveType` tinyint(1) NOT NULL,
  `isTbApp` tinyint(1) NOT NULL,
  `tbSiteList` text NOT NULL,
  `newRss` int(3) NOT NULL,
  `rssContentType` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_smilies` (
  `id` int(3) NOT NULL auto_increment,
  `imgName` varchar(12) NOT NULL,
  `imgUrl` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `imgName` (`imgName`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_tags` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `logNums` int(8) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_trackbacks` (
  `id` int(8) NOT NULL auto_increment,
  `logId` int(12) NOT NULL,
  `tbTitle` varchar(100) NOT NULL,
  `blogSite` varchar(50) NOT NULL,
  `blogUrl` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `postTime` int(10) NOT NULL,
  `isApp` tinyint(4) NOT NULL default '0',
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `logId` (`logId`)
) ENGINE=MyISAM;

CREATE TABLE `f2blog_visits` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT ,
`ip` VARCHAR( 20 ) NOT NULL ,
`visittime` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE=MyISAM;
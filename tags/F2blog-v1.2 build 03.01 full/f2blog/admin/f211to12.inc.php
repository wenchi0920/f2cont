<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//从旧数据库提取
$arrResult = $DMC->fetchArray($DMC->query("SELECT isHidden FROM ".$DBPrefix."modules where name='skinSwitch'"));
$skinSwitch = $arrResult['isHidden'];

$setInfo = $DMC->fetchArray($DMC->query("SELECT * FROM ".$DBPrefix."setting where id='1'"),MYSQL_ASSOC);

//默认值
include("setting_default.php");

foreach($arr_setting as $key=>$value){
	foreach($setInfo as $subkey=>$subvalue){
		if (strpos($value,"'$subkey'")>0 && $subkey!="isRegister" && $subkey!="gbookPage" && $subkey!="commPage"){
			$subvalue=str_replace("'","&#39;",$subvalue);
			$arr_setting[$key]=preg_replace("/\('(.*?)','(.*?)','(.*?)'\);/is","('\\1','".$subvalue."','\\3');",$value);
			break;
		}
		if (strpos($value,"skinSwitch")>0){
			$arr_setting[$key]="('skinSwitch','".$skinSwitch."','1');";
		}
	}
}

foreach($arr_setting as $key=>$value){
	$insert_sql[]="insert into ".$DBPrefix."setting values".$value;
}

if ($DMC->getServerInfo() > '4.1') $default_charset=" DEFAULT CHARACTER SET utf8";
$create_sql[]="DROP TABLE IF EXISTS `{$DBPrefix}setting`;";
$create_sql[]="CREATE TABLE `{$DBPrefix}setting` (
				`settName` varchar( 30 ) NOT NULL ,
				`settValue` text NOT NULL,
				`settAuto` tinyint(1) NOT NULL default '0'
			   ) ENGINE=MyISAM $default_charset;
			  ";
$modify_sql=array_merge($create_sql,$insert_sql);

$modify_sql[]="DROP TABLE IF EXISTS `{$DBPrefix}linkgroup`;";
$modify_sql[]="CREATE TABLE `{$DBPrefix}linkgroup` (
				`id` int(3) NOT NULL auto_increment,
				`name` varchar(50) NOT NULL,
				`isSidebar` tinyint(1) NOT NULL default '0',
				`orderNo` int(5) NOT NULL default '0',
				PRIMARY KEY  (`id`),
				KEY `name` (`name`)
				) ENGINE=MyISAM $default_charset;
			  ";
$modify_sql[]="ALTER TABLE `{$DBPrefix}logs` ADD `autoSplit` int(8) NOT NULL default '0'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}categories` ADD `cateIcons` tinyint(4) NOT NULL default '1'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}links` ADD `lnkGrpId` int(3) NOT NULL default '1'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}links` ADD `blogLogo` varchar(150) NOT NULL default ''";
$modify_sql[]="ALTER TABLE `{$DBPrefix}links` ADD `isApp` tinyint(1) NOT NULL default '0'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}links` CHANGE `isHidden` `isSidebar` tinyint(1) NOT NULL default '0'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}attachments` CHANGE `attTitle` `attTitle` varchar(150) NOT NULL default ''";
$modify_sql[]="ALTER TABLE `{$DBPrefix}members` CHANGE `role` `role` varchar(8) NOT NULL default 'user'";
$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `homepage` varchar(100) NOT NULL default ''";
$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `email` varchar(100) NOT NULL default ''";
$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `face` varchar(30) NOT NULL default ''";

$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (17, 'links', '[var]strLinks[/var]', 1, 0, 0, 4, 1, '', 'include/links.inc.php', 0, '', 0, '', 0, '')";								
$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (18, 'archives', '[var]strArchives[/var]', 1, 0, 0, 4, 1, '', 'include/archives.inc.php', 0, '', 0, '', 0, '')";
$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (19, 'userPanel', '[var]strUserPanel[/var]', 2, 1, 0, 1, 1, 'side_userPanel()', '', 0, '', 0, '', 0, '')";
$modify_sql[]="UPDATE `{$DBPrefix}modules` set modTitle='[var]strHomePageGBook[/var]' where id='16'";

$modify_sql[]="INSERT INTO `{$DBPrefix}linkgroup` VALUES ('1', 'Links', '1', '1')";
$modify_sql[]="UPDATE {$DBPrefix}links set lnkGrpId='1',isApp='1',isSidebar='1'";
$modify_sql[]="UPDATE {$DBPrefix}categories set cateIcons='1'";


//运行ＳＱＬ语句
foreach ($modify_sql as $key=>$value){		
	$DMC->query($value,"T");
}
?>
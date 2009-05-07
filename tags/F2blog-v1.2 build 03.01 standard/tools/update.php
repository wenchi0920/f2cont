<?php
header("Content-Type: text/html; charset=utf-8");
@error_reporting(E_ALL & E_NOTICE);

/*
更新说明：
1. 此为F2blog v1.0 升级到F2blog v1.1 beta 2007.02.01
升级方法：
1. 把此文件移到上一层目录就可以了。
*/
$update_time="2007-02-07";

//禁止此文件在tools目录下运行
if (strpos(";".$_SERVER['PHP_SELF'],"tools")>0){
	@header("Content-Type: text/html; charset=utf-8");
	echo "update.php文件不能运行于tools目录，如要升级，请把此文件移到上一层目录下面！";
	exit;
}

if (!defined('F2BLOG_ROOT')){
	define('IN_F2BLOG', TRUE);
	define('F2BLOG_ROOT', dirname(__FILE__)."/");
}

//检测是否已更新
if (file_exists(F2BLOG_ROOT."./cache/cache_update_logs.php")){
	include(F2BLOG_ROOT."./cache/cache_update_logs.php");
	if (!@in_array($update_time,$update_logs) || count($update_logs)<1){
		$check_update=true;
	}else{
		$check_update=false;
	}
}else{
	$check_update=true;
}

//需要更新
if ($check_update){
	//输入update.php运行
	if (preg_match("/update.php/is",$_SERVER['PHP_SELF'])){

		include_once("include/config.php");
		include_once("include/db.php");
		include_once("include/cache.php");

		//连接mysql
		$DMC = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName,$DBNewlink);
		
		echo "Start update F2blog, please wait ... <hr>";
		update_data(true,$DMC);
		echo "<hr>\n";
		echo "Update Success. please delete this update.php <br />\n";
		echo "<a href=index.php>Return Homepage</a>";
	}else{ //是在common.php中检测运行
		//运行更新文件,参数true显示更新内容。false不显示更新内容。
		include_once("include/cache.php");
		update_data(false,$DMC); 
	}
}else{
	if (preg_match("/update.php/",$_SERVER['PHP_SELF'])){
		echo "F2blog have already updated to $update_time, please delete this update.php <hr>";
		echo "<a href=index.php>Return Homepage</a>";		
	}
}

function update_data($echo,$DMC){
	global $DBPrefix,$update_time,$update_logs;	

	$modify_sql=array();
	if ($DMC->getServerInfo() > '4.1') {
		$default_charset=" DEFAULT CHARACTER SET utf8";
	}else{
		$default_charset="";
	}

	//从f2blog v1.0 build 0909升级
	$delete_setting=false;
	if ($arr_query=$DMC->query("SELECT * FROM ".$DBPrefix."setting where id='1'",'T')){
		$setInfo = $DMC->fetchArray($arr_query,MYSQL_ASSOC);
		if ($setInfo['language']=="zh_cn"){
			$strGholidayDefault="{0101,元旦}{0214,情人节}{0308,妇女节}{0401,愚人节}{0501,劳动节}{0512,F2BLOG成立日}{1001,国庆日}{1224,平安夜}{1225,圣诞节}{1226,圣诞节翌日}";
			$strNholidayDefault="{0101,年初一春节}{0102,年初二}{0103,年初三}{0104,年初四}{0105,年初五开市}{0115,元宵节}{0505,端午节}{0701,开鬼门}{0707,七夕情人节}{0815,中秋节}{0909,重阳节}";
		}else if ($setInfo['language']=="zh_tw"){
			$strNholidayDefault = "{0101,年初一春節}{0102,年初二}{0103,年初三}{0104,年初四}{0105,年初五開市}{0115,元宵節}{0505,端午節}{0701,開鬼門}{0707,七夕情人節}{0815,中秋節}{0816,中秋節翌日}{0909,重陽節}";
			$strGholidayDefault = "{0101,元旦}{0214,情人節}{0308,婦女節}{0401,愚人節}{0501,勞動節}{0512,F2BLOG成立日}{1224,平安夜}{1225,聖誕節}{1226,聖誕節翌日}";
		}else{
			$strNholidayDefault = "{0101,Lunar New Year&#39s Day}{0102,The second day of the Lunar New Year}{0103,The third day of the Lunar New Year}{0104,The forth day of the Lunar New Year}{0105,The fifth day of the Lunar New Year}{0115,Lantern Festival}{0505,Tuen Ng Festival}{0701,Ghost Gate Open Day}{0707,Chinese Valentine&#39s Day}{0815,Chinese Mid-Autumn Festival}{0816,The day following Chinese Mid-Autumn Festival}{0909,Chung Yeung Festival}";
			$strGholidayDefault = "{0101,New Year&#39s Day}{0214,Valentine&#39s Day}{0308,Women&#39s Day}{0401,Fool&#39s Day}{0501,Labour Day}{0512,F2BLOG Foundation Day}{1224,Christmas Eve}{1225,Christmas Day}{1226,The day following Christams Day}";
		}

		//默认值
		if (file_exists(F2BLOG_ROOT."./admin/admin_config.php")) {
			include(F2BLOG_ROOT."./admin/admin_config.php");
		}else{
			$cfg_page_size="15";
			$cfg_upload_file="gif,jpg,rar,zip,doc,xls,pdf,ico,png,swf,wma,wav,mp3,wmv";
			$cfg_upload_size=1024000; //以byte单位等于1M  1024000
			$cfg_mouseover_color="#FFFFCC";
			$content_width=500;
			$backup_size=1024;  // 以KB为单位
			$editor_plugins = "f2blog,f2music,flash";
			$editor_button1 = 'bold,italic,underline,separator,justifyleft,justifycenter,justifyright,separator,fontselect,fontsizeselect,forecolor,backcolor,separator,hr,link,unlink,flash,f2_music,separator,f2_hide,f2_more,f2_page,separator,code';
			$editor_button2 = '';
			$editor_button3 = '';
		}

		$editor_button1=str_replace(',justifyleft,justifycenter',',pastetext,pasteword,separator,justifyleft,justifycenter,justifyright',$editor_button1);
		$editor_plugins="paste,".$editor_plugins;

		$arr_setting[]="('about','','0');";
		$arr_setting[]="('adminPageSize','".$cfg_page_size."','0');";
		$arr_setting[]="('adminPerPage','25','0');";
		$arr_setting[]="('ajaxstatus','','0');";
		$arr_setting[]="('archivesmonth','5','0');";
		$arr_setting[]="('attachDir','1','0');";
		$arr_setting[]="('attachType','".$cfg_upload_file."','0');";
		$arr_setting[]="('autoSave','0','0');";
		$arr_setting[]="('autoSplit','0','0');";
		$arr_setting[]="('backupSize','".ceil($cfg_upload_size/1000)."','0');";
		$arr_setting[]="('blogKeyword','','0');";
		$arr_setting[]="('blogTitle','Free & Freedom Blog','0');";
		$arr_setting[]="('blogUrl','http://localhost','0');";
		$arr_setting[]="('categoryImgPath','images/tree/folder_gray','0');";
		$arr_setting[]="('closeReason','Close Blogs!','0');";
		$arr_setting[]="('commentOrder','asc','0');";
		$arr_setting[]="('commLength','800','0');";
		$arr_setting[]="('commPage','10','0');";
		$arr_setting[]="('commTimerout','30','0');";
		$arr_setting[]="('defaultedits','tiny','0');";
		$arr_setting[]="('defaultSkin','default','1');";
		$arr_setting[]="('disType','0','0');";
		$arr_setting[]="('downcode','0','0');";
		$arr_setting[]="('editPluginButton1','".$editor_button1."','0');";
		$arr_setting[]="('editPluginButton2','".$editor_button2."','0');";
		$arr_setting[]="('editPluginButton3','".$editor_button3."','0');";
		$arr_setting[]="('editPluginName','".$editor_plugins."','0');";
		$arr_setting[]="('email','yourmail@mail.com','0');";
		$arr_setting[]="('favicon','','0');";
		$arr_setting[]="('gbookOrder','desc','0');";
		$arr_setting[]="('gbookPage','10','0');";
		$arr_setting[]="('gcalendar','".$strGholidayDefault."','0');";
		$arr_setting[]="('genThumb','0','0');";
		$arr_setting[]="('gzipstatus','0','0');";
		$arr_setting[]="('headcode','','0');";
		$arr_setting[]="('img_width','400','0');";
		$arr_setting[]="('isHtmlPage','0','0');";
		$arr_setting[]="('isLinkTagLog','1','0');";
		$arr_setting[]="('isProgramRun','1','0');";
		$arr_setting[]="('isRegister','1','0');";
		$arr_setting[]="('isTbApp','0','0');";
		$arr_setting[]="('isValidateCode','0','0');";
		$arr_setting[]="('language','zh_cn','0');";
		$arr_setting[]="('linkTagLog','10','0');";
		$arr_setting[]="('loginvalid','0','0');";
		$arr_setting[]="('logo','','0');";
		$arr_setting[]="('logscomment','10','0');";
		$arr_setting[]="('logsgbook','10','0');";
		$arr_setting[]="('logspage','18','0');";
		$arr_setting[]="('master','$admin','0');";
		$arr_setting[]="('mouseovercolor','#FFFFCC','0');";
		$arr_setting[]="('name','My F2Blog','0');";
		$arr_setting[]="('ncalendar','".$strNholidayDefault."','0');";
		$arr_setting[]="('newRss','10','0');";
		$arr_setting[]="('onlineTime','3600','0');";
		$arr_setting[]="('pagebar','A','0');";
		$arr_setting[]="('perPageList','20','0');";
		$arr_setting[]="('perPageNormal','8','0');";
		$arr_setting[]="('readpagebar','1','0');";
		$arr_setting[]="('registerClose','Close Register!','0');";
		$arr_setting[]="('rewrite','0','0');";
		$arr_setting[]="('rssContentType','1','0');";
		$arr_setting[]="('rssLength','500','0');";
		$arr_setting[]="('setattachments','0','1');";
		$arr_setting[]="('setcategories','0','1');";
		$arr_setting[]="('setcomments','".$setInfo['commNums']."','1');";
		$arr_setting[]="('setguestbook','".$setInfo['messageNums']."','1');";
		$arr_setting[]="('setlogs','".$setInfo['logNums']."','1');";
		$arr_setting[]="('setmembers','".$setInfo['memberNums']."','1');";
		$arr_setting[]="('settags','".$setInfo['tagNums']."','1');";
		$arr_setting[]="('settrackbacks','".$setInfo['tbNums']."','1');";
		$arr_setting[]="('showattach','0','0');";
		$arr_setting[]="('showcalendar','1','0');";
		$arr_setting[]="('showcate','0','0');";
		$arr_setting[]="('showcomment','0','0');";
		$arr_setting[]="('showguest','0','0');";
		$arr_setting[]="('showlogs','1','0');";
		$arr_setting[]="('showonline','1','0');";
		$arr_setting[]="('showquote','0','0');";
		$arr_setting[]="('showtags','0','0');";
		$arr_setting[]="('showtoday','1','0');";
		$arr_setting[]="('showtotal','1','0');";
		$arr_setting[]="('showuser','0','0');";
		$arr_setting[]="('showyester','1','0');";
		$arr_setting[]="('sidecommentlength','12','0');";
		$arr_setting[]="('sidecommentstyle','{title}','0');";
		$arr_setting[]="('sidegbooklength','12','0');";
		$arr_setting[]="('sidegbookstyle','{title}','0');";
		$arr_setting[]="('sidelogslength','12','0');";
		$arr_setting[]="('sidelogsPage','10','0');";
		$arr_setting[]="('sidelogsstyle','{title}','0');";
		$arr_setting[]="('skinSwitch','".$skinSwitch."','1');";
		$arr_setting[]="('status','0','0');";
		$arr_setting[]="('tagNums','50','0');";
		$arr_setting[]="('tbSiteList','','0');";
		$arr_setting[]="('thumbSize','200x200','0');";
		$arr_setting[]="('timeSystemFormat','Y-m-d H:i','0');";
		$arr_setting[]="('timezone','8','0');";
		$arr_setting[]="('trackNums','10','0');";
		$arr_setting[]="('uservalid','0','0');";
		$arr_setting[]="('visitNums','0','1');";
		$arr_setting[]="('applylink','1','0');";
		$arr_setting[]="('disSearch','0','0');";
		$arr_setting[]="('gbface','1','0');";
		$arr_setting[]="('disAttach','1','0');";
		$arr_setting[]="('forum_user','','0');";
		$arr_setting[]="('loginStatus','1','0');";
		$arr_setting[]="('disTags','1','0');";
		$arr_setting[]="('fastEditor','1','0');";
		$arr_setting[]="('allowTrackback','1','0');";
		$arr_setting[]="('allowComment','1','0');";
		$arr_setting[]="('showKeyword','0','0');";
		$arr_setting[]="('autoUrl','0','0');";
		$arr_setting[]="('showPrint','0','0');";
		$arr_setting[]="('showDown','0','0');";
		$arr_setting[]="('showMail','0','0');";
		$arr_setting[]="('linkshow','0','0');";
		$arr_setting[]="('showUBB','0','0');";
		$arr_setting[]="('linklogo','images/logo.gif','0');";
		$arr_setting[]="('adminstyle','default','0');";
		$arr_setting[]="('footcode','','0');";
		$arr_setting[]="('linkmarquee','0','0');";
		$arr_setting[]="('currFormatDate','Y-m-d H:i','0');";
		$arr_setting[]="('listFormatDate','m-d','0');";
		$arr_setting[]="('showAlertStyle','0','0');";
		$arr_setting[]="('weatherStatus','1','0');";
		$arr_setting[]="('treeCategory','0','0');";

		foreach($arr_setting as $key=>$value){
			foreach($setInfo as $subkey=>$subvalue){
				if (strpos($value,"'$subkey'")>0 && $subkey!="isRegister" && $subkey!="gbookPage" && $subkey!="commPage"){
					$subvalue=str_replace("'","&#39;",$subvalue);
					$arr_setting[$key]=preg_replace("/\('(.*?)','(.*?)','(.*?)'\);/is","('\\1','".$subvalue."','\\3');",$value);
					break;
				}
			}
		}

		foreach($arr_setting as $key=>$value){
			$insert_sql[]="insert into ".$DBPrefix."setting values".$value;
		}
	
		$create_sql[]="DROP TABLE IF EXISTS `{$DBPrefix}setting`;";
		$create_sql[]="CREATE TABLE `{$DBPrefix}setting` (
						`settName` varchar( 30 ) NOT NULL ,
						`settValue` tinytext NOT NULL,
						`settAuto` tinyint(1) NOT NULL default '0'
					   ) ENGINE=MyISAM $default_charset;
					  ";
		$modify_sql=array_merge($create_sql,$insert_sql);

		$delete_setting=true;
	}else{
		//更新 v1.0 以后的设定值
		$arr_setting=array();
		$arr_setting['applylink']="1";
		$arr_setting['disSearch']="0";
		$arr_setting['gbface']="1";
		$arr_setting['disAttach']="1";
		$arr_setting['forum_user']="";
		$arr_setting['loginStatus']="1";
		$arr_setting['disTags']='1';
		$arr_setting['fastEditor']='0';
		$arr_setting['allowTrackback']='1';
		$arr_setting['allowComment']='1';
		$arr_setting['showKeyword']='0';
		$arr_setting['autoUrl']='0';
		$arr_setting['showPrint']='0';
		$arr_setting['showDown']='0';
		$arr_setting['showMail']='0';
		$arr_setting['linkshow']='0';
		$arr_setting['showUBB']='0';
		$arr_setting['linklogo']='images/logo.gif';
		$arr_setting['adminstyle']='new';
		$arr_setting['footcode']='';
		$arr_setting['linkmarquee']='0';
		$arr_setting['currFormatDate']='Y-m-d H:i';
		$arr_setting['listFormatDate']='m-d';
		$arr_setting['showAlertStyle']='0';
		$arr_setting['weatherStatus']='1';
		$arr_setting['treeCategory']='0';

		foreach($arr_setting as $key=>$value){
			if (!$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='$key'"))){
				$modify_sql[]="insert into ".$DBPrefix."setting values('$key','$value','0')";
			}
		}

		//新增粘贴插件
		if (!$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='editPluginName' and settValue like '%paste%'"))){
			$modify_sql[]="update ".$DBPrefix."setting set settValue=concat('paste,',settValue) where settName='editPluginName'";
			$modify_sql[]="update ".$DBPrefix."setting set settValue=replace(settValue,',justifyleft,justifycenter',',pastetext,pasteword,separator,justifyleft,justifycenter,justifyright') where settName='editPluginButton1'";
		}

		//清除属性(2007-02-01）
		if ($DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='disTop'"))){
			$modify_sql[]="delete from {$DBPrefix}setting where settName='disTop' or settName='calendarmonth'";
		}
	}

	//更新数据表(2006-11-11)
	if (!$DMC->query("SELECT cateIcons FROM ".$DBPrefix."categories",'T')){
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
		$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (17, 'links', '[var]strLinks[/var]', 1, 0, 0, 4, 1, '', 'include/links.inc.php', 0, '', 0, '', 0, '')";
		$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (18, 'archives', '[var]strArchives[/var]', 1, 0, 0, 4, 1, '', 'include/archives.inc.php', 0, '', 0, '', 0, '')";
		$modify_sql[]="INSERT INTO `{$DBPrefix}modules` VALUES (19, 'userPanel', '[var]strUserPanel[/var]', 2, 1, 0, 1, 1, 'side_userPanel()', '', 0, '', 0, '', 0, '')";
		$modify_sql[]="UPDATE `{$DBPrefix}modules` set modTitle='[var]strHomePageGBook[/var]' where id='16'";
		$modify_sql[]="INSERT INTO `{$DBPrefix}linkgroup` VALUES ('1', 'Links', '1', '1')";
		$modify_sql[]="UPDATE {$DBPrefix}links set lnkGrpId='1',isApp='1',isSidebar='1'";
		$modify_sql[]="UPDATE {$DBPrefix}categories set cateIcons='1'";
	}

	//更新评论2007-02-01，因为恢复的地方没有使用它。
	if (!$DMC->query("SELECT homepage FROM ".$DBPrefix."comments",'T')){
		$modify_sql[]="ALTER TABLE `{$DBPrefix}setting` CHANGE `settValue` `settValue` text NOT NULL";
		$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `homepage` varchar(100) NOT NULL default ''";
		$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `email` varchar(100) NOT NULL default ''";
		$modify_sql[]="ALTER TABLE `{$DBPrefix}comments` ADD `face` varchar(30) NOT NULL default ''";
	}

	//更新附件的文件类别(2007-02-01
	$arrResult = $DMC->fetchQueryAll($DMC->query("SELECT name,id FROM ".$DBPrefix."attachments where not fileType like '%/%' and not fileType like '%-%' "));
	foreach ($arrResult as $value){
		if ($value['name']!=""){
			$fileType=updateFileType($value['name']);
			$modify_sql[]="update ".$DBPrefix."attachments set fileType='$fileType' where id='".$value['id']."'";
		}
	}

	//运行ＳＱＬ语句
	foreach ($modify_sql as $key=>$value){
		$DMC->query($value,"T");
		if ($echo) {
			if ($DMC->error()){
				echo $value." ... <font color=red>".$DMC->error()."</font><br />";
			}else{
				echo $value." ... <font color=blue>OK</font><br />";
			}
		}
	}

	$i=0;
	$contents = "\$update_logs = array(\r\n";
	$contents.="\t'$i' => '$update_time',\r\n";
	for ($i=0;$i<count($update_logs);$i++){
		$j=$i+1;
		$contents.="\t'$j' => '".$update_logs[$i]."',\r\n";
	}
	$contents .= ");";
	writetocache('update_logs',$contents);

	//清空缓存
	if ($delete_setting==true){//0909升级需要重新建立setting文件。
		if (!@unlink(F2BLOG_ROOT."./cache/cache_setting.php")){
			echo "<script language=Javascript> \n";
			echo "alert('Please update cache!');\n";
			echo "</script>\n";
		}
	}else{
		//更新缓存
		settings_recache();
		links_recache();
	}
}

function updateFileType($filename = '') {
	$exts	=	strtolower(substr($filename,strrpos($filename,".")+1));
	switch ($exts) {
		case 'jpg':
			return 'image/pjpeg';
		break;
		case 'jpe':
			return 'image/pjpeg';
		break;
		case 'jpeg':
			return 'image/pjpeg';
		break;
		case 'pdf':
			return 'application/pdf';
		break;
		case 'gif':
			return 'image/gif';
		break;
		case 'bmp':
			return 'image/bmp';
		break;
		case 'png':
			return 'image/png';
		break;
		case 'rar':
			return 'x-rar-compressed';
		break;
		case 'txt':
			return 'text/plain';
		break;
		case 'swf':
			return 'application/x-shockwave-flash';
		break;
		case 'zip':
			return 'application/zip';
		break;
		case 'doc':
			return 'application/msword';
		break;
		default:
			return 'application/octet-stream';
		break;
	}
}
?>
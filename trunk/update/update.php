<?php
header("Content-Type: text/html; charset=utf-8");
@error_reporting(E_ALL & E_NOTICE);

/*
更新说明：
1. 此为F2blog v1.2 升级到 F2cont 
升级方法：
1. 把此文件移到上一层目录就可以了。
*/
$update_time="2009-05-12";

//禁止此文件在tools目录下运行
if (strpos(";".$_SERVER['PHP_SELF'],"tools")>0){
	@header("Content-Type: text/html; charset=utf-8");
	echo "update.php文件不能運行於tools目錄，如要升級，請把此文件移到上一層目錄下面！";
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
	
	$delete_setting=true;
	
	//更新附件的文件类别 2009-05-12
	
	$modify_sql[]="ALTER TABLE `{$DBPrefix}attachments` ADD INDEX `name` (`name`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}guestbook` ADD COLUMN `HTTP_REFERER` text NULL DEFAULT NULL AFTER `parent`";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}links` ADD INDEX `isApp` (`isApp`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}logs` ADD COLUMN `isTopNews` tinyint(1) NOT NULL DEFAULT 0 AFTER `isTop`";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}logs` ADD INDEX `postTime` (`postTime`,`saveType`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}logs` ADD INDEX `isComment` (`isComment`,`isTrackback`,`isTop`,`isTopNews`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}members` ADD INDEX `password` (`password`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}members` ADD INDEX `role` (`role`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}setting` ADD INDEX `settName` (`settName`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}trackbacks` ADD INDEX `isApp` (`isApp`)";
	$modify_sql[]="ALTER TABLE `{$DBPrefix}logs` ADD `autoSplit` int(8) NOT NULL default '0'";
	
	//	強化驗證
	$modify_sql[]="INSERT INTO `{$DBPrefix}filters` (`category`, `name`) VALUES (1, '=http')";
	$modify_sql[]="INSERT INTO `{$DBPrefix}filters` (`category`, `name`) VALUES (1, '[url=')";
	$modify_sql[]="INSERT INTO `{$DBPrefix}filters` (`category`, `name`) VALUES (1, '[href=')";
	
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
	
	return false;

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
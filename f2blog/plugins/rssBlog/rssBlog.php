<?php
/*
Plugin Name: rssBlog
Plugin URI: http://joesen.f2blog.com/read-504.html
Description: 博客联播,在自己Blog上聚合并显示朋友Blog的最新文章，这样方便自己及时了解朋友的消息。
Author: joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function rssBlog_install() {
	global $DMC,$DBPrefix;

	$arrPlugin['Name']="rssBlog";
	$arrPlugin['Desc']="博客联播";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="";
	
	$dbcharset = 'utf8';
	//建立rssBlog设定表
	$sql="CREATE TABLE `{$DBPrefix}rssBlog` (`id` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`blogTitle` VARCHAR( 50 ) NOT NULL ,`blogUrl` VARCHAR( 150 ) NOT NULL ,`rssUrl` VARCHAR( 200 ) NOT NULL ,`viewLimit` TINYINT( 2 ) NOT NULL DEFAULT '5', `orderNo` INT( 3 ) NOT NULL,`rssStatus` TINYINT( 1 ) NOT NULL DEFAULT '1', `redate` VARCHAR( 20 ) NOT NULL ) ENGINE = MYISAM ;";
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	$runsql=preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
	$DMC->query($runsql);
	$DMC->query("INSERT INTO {$DBPrefix}rssBlog (id,blogTitle,blogUrl,rssUrl,viewLimit,orderNo,redate) VALUES ('1', '天上的骆驼', 'http://joesen.f2blog.com', 'http://joesen.f2blog.com/rss.php',5,1,'".time()."')");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function rssBlog_unstall() {
	global $DMC,$DBPrefix;
	$DMC->query("DROP TABLE `{$DBPrefix}rssBlog`");
	$ActionMessage=unstall_plugins("rssBlog");
	return $ActionMessage;
}

#博客联播
function rssBlog($sidename,$sidetitle,$isInstall){
	global $DBPrefix,$DMC,$settingInfo,$arrSideModule,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div id="Side_rssBlog" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<iframe id="f2RssBlog" src="plugins/rssBlog/readrss.php" width="100%" height="200" frameborder=0 scrolling="no" allowTransparency="true" ></iframe>
  </div>
  <div class="Pfoot"></div>
</div>
<?
} #END博客联播

add_filter("rssBlog",'rssBlog',3);
?>
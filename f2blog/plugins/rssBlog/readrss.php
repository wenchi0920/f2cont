<?php
error_reporting(E_ERROR);
require_once('magpierss/rss_fetch.inc');
include_once("../../include/config.php");
include_once ("../../include/db.php");
include_once ("../../cache/cache_setting.php");

// 连结数据库
$DMC = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);
 
//参数设定
define('MAGPIE_DEBUG', 1);
define('MAGPIE_CACHE_ON', true); //是否开启RSS缓存
define('MAGPIE_CACHE_DIR', '../../cache'); // 缓存存放目录，默认放在cache目录
define('MAGPIE_CACHE_AGE', 60*60*12); //RSS缓存生成时间，单位为秒
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8'); //输出字体
define('MAGPIE_FETCH_TIME_OUT', 15); //远端连线超时时间，单位为秒
define('MAGPIE_USE_GZIP', false); //是否使用Gzip（注意有些服务器不支持）

/***********按一定长度截取字符串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
	if (function_exists('mb_substr')) {
		$more = (mb_strlen($text) > $limit) ? TRUE : FALSE;
		$text = mb_substr($text, 0, $limit, 'UTF-8');
		return $text;
	} elseif (function_exists('iconv_substr')) {
		$more = (iconv_strlen($text) > $limit) ? TRUE : FALSE;
		$text = iconv_substr($text, 0, $limit, 'UTF-8');
		//return array($text, $more);
		return $text;
	} else {
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
		if(func_num_args() >= 3) {   
			if (count($ar[0])>$limit) {
				$more = TRUE;
				$text = join("",array_slice($ar[0],0,$limit)); 
			} else {
				$more = FALSE;
				$text = join("",array_slice($ar[0],0,$limit)); 
			}
		} else {
			$more = FALSE;
			$text =  join("",array_slice($ar[0],0)); 
		}
		return $text;
	} 
}

function showRss($blogUrl,$blogTitle,$url,$viewLimit) {
	global $settingInfo;
	$rss = fetch_rss($url);
	
	if ($rss) {
		echo "<a href=".$rss->channel['link']." target=\"_blank\"><B>&nbsp;博客: ".$rss->channel['title']."</B></a><br />";
		$items=array_slice($rss->items,0,$viewLimit);
		foreach ($items as $item) {
			$href = $item['link'];
			$title = $item['title'];
			$title1=subString($title,0,$settingInfo['sidelogslength']);
			echo "<a class=\"sideA\" href='$href' title='$title' target=\"_blank\">$title1</a>";
		}

		echo "<br />";
	} else {
		echo "<a href=".$blogUrl." target=\"_blank\"><B>&nbsp;博客: ".$blogTitle." 连结超时！</B></a><br /><br />";
	}
}

//读取皮肤设置
//检测是否开启了skin switch
if ($settingInfo['skinSwitch']==0){//0表示开启了skin switch
	include_once("../../cache/cache_skinlist.php");

	if (!empty($_POST['skinSelect'])){
		$blogSkins=$_POST['skinSelect'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookiepath,$cookiedomain);
	}elseif (!empty($_COOKIE['blogSkins'])){
		$blogSkins=$_COOKIE['blogSkins'];
	}else{
		$blogSkins=$settingInfo['defaultSkin'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookiepath,$cookiedomain);
	}

	if (!file_exists("../../skins/$blogSkins/global.css")) $blogSkins=$settingInfo['defaultSkin'];
}else{
	$blogSkins=$settingInfo['defaultSkin'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="robots" content="all" />
<style type="text/css">
<!--
body{font-size:9pt; font-family: Tahoma;margin-left: 0px;margin-top: 0px;}
-->
</style>
<link rel="stylesheet" rev="stylesheet" href="../../skins/<?=$blogSkins?>/link.css" type="text/css" media="all" /><!--超链接样式表-->
</head>
<body>

<?
$query_sql="select blogTitle,blogUrl,rssUrl,viewLimit from {$DBPrefix}rssBlog order by orderNo,id";
$query_result=$DMC->query($query_sql);
while($fa = $DMC->fetchArray($query_result)){
	showRss($fa['blogUrl'],$fa['blogTitle'],$fa['rssUrl'],$fa['viewLimit']);
}

?>

<script type="text/javascript">
parent.document.all("f2RssBlog").style.height=document.body.scrollHeight;
</script>

</body>
</html>
<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');
//UBB插件
if (file_exists("./skins/".$blogSkins."/UBB")){
	$ubb_path="./skins/".$blogSkins."/UBB";
}else{
	$ubb_path="./editor/ubb";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="robots" content="all" />
<meta name="author" content="<?php echo $settingInfo['email']?>" />
<meta name="Copyright" content="<?php echo blogCopyright?>" />
<meta name="keywords" content="<?php echo blogKeywords.",".$settingInfo['blogKeyword'].$logTags?>" />
<meta name="description" content="<?php echo $settingInfo['name']?> - <?php echo $settingInfo['blogTitle']?>" />
<title><?php echo (!empty($borwseTitle))?$borwseTitle." - ":""?><?php echo $settingInfo['name']?></title>
<?php if (!empty($load) && $load=="read"){?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php?cateID=<?php echo $arr_array['cateId']?>" title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php?cateID=<?php echo $arr_array['cateId']?>"  title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Atom)"  />
<?php }else{?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php" title="<?php echo $settingInfo['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php" title="<?php echo $settingInfo['name']?>(Atom)" />
<?php }?>
<?php if (!empty($base_rewrite)){?><base href="<?php echo $base_rewrite?>" /><?php }?>
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/global.css" type="text/css" media="all" /><!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/layout.css" type="text/css" media="all" /><!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/typography.css" type="text/css" media="all" /><!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/link.css" type="text/css" media="all" /><!--超链接样式表-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "skins/$blogSkins/f2blog.css"?>" type="text/css" media="all" /><!--F2blog特有CSS-->
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" /><!--F2blog共用CSS-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "$ubb_path/editor.css"?>" type="text/css" media="all" /><!--UBB样式-->
<link rel="icon" href="<?php echo "attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo "attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<script type="text/javascript" src="include/common.js.php"></script>
<?php if (!empty($settingInfo['showAlertStyle'])){?><script type="text/javascript" src="editor/ubb/nicetitle.js"></script><?php }?>
<?php if ($settingInfo['ajaxstatus']!=""){?><script type="text/javascript" src="include/f2blog_ajax.js"></script><?php }?>
<?php  do_action("f2_head"); ?>
<?php if ($settingInfo['headcode']!="") echo str_replace("<br />","",dencode($settingInfo['headcode']));?>
</head>
<body>

<?php 
//装载在线人员
include_once(F2BLOG_ROOT."./cache/cache_online.php");

// 增加访问量
$curDate=gmdate('Y-m-d', time()+3600*$settingInfo['timezone']);
$userBrowse=$_SERVER['HTTP_USER_AGENT'];
$realip=getip();

if (!preg_match("/http/i",$userBrowse) && $realip!="") {
	$onlines = "\$onlinecache = array(\r\n";
	$onlineIP="\t'ip' => array(";
	$onlineTM="\t'times' => array(";
	$onlineCheck="";
	$online_count=0;
	$offline_count=0;
	foreach($onlinecache['ip'] as $key=>$value){
		//首先检查文件中已过时的IP
		if (time()-$onlinecache['times'][$key]<$settingInfo['onlineTime']){
			if ($onlinecache['ip'][$key]==$realip && $onlineCheck=="") { $onlineCheck="Y"; }
			$onlineIP.="'".$onlinecache['ip'][$key]."',";
			$onlineTM.="'".$onlinecache['times'][$key]."',";
			$online_count=$online_count+1;
		}else{
			$offline_count++;
		}
	}

	//新的访客
	if ($onlineCheck=="") {
		if (empty($cache_visits_yesterday)) {
			$ydate=strtotime($curDate);
			$yesterday=date("Y-m-d",mktime(0,0,0,date("m",$ydate),date("d",$ydate)-1,date("Y",$ydate)));
			$cache_visits_yesterday=getDailyStatisticsDay($yesterday);
		}
		if (empty($cache_visits_today)) $cache_visits_today=getDailyStatisticsDay($curDate);
		if (empty($cache_visits_total)) $cache_visits_total=getDailyStatisticsTotal();

		$onlineIP.="'".$realip."',";
		$onlineTM.="'".time()."',";

		$query   = $DMC->query("SELECT visitDate FROM ".$DBPrefix."dailystatistics WHERE visitDate='$curDate'");
		$num     = $DMC->numRows($query);
		if($num == 0) {
			$cache_visits_yesterday=$cache_visits_today;
			$cache_visits_today=1;
			$DMC->query("insert into ".$DBPrefix."dailystatistics (visitDate,visits) values ('$curDate','1')");
			
			include_once("include/cache.php");
			calendar_recache();
		} else {
			$cache_visits_today=$cache_visits_today+1;
			$DMC->query("UPDATE ".$DBPrefix."dailystatistics SET visits = visits+1 where visitDate='$curDate'");
		}
		$cache_visits_total++;
		$online_count=$online_count+1;
	}

	$onlineIP .= ")";
	$onlineTM .= ")";
	$onlines .=$onlineIP.",\n".$onlineTM.",\n);\r\n\r\n";

	//昨天\今天\总数
	$onlines .= "\$cache_visits_yesterday = $cache_visits_yesterday;\r\n\r\n";
	$onlines .= "\$cache_visits_today = $cache_visits_today;\r\n\r\n";
	$onlines .= "\$cache_visits_total = $cache_visits_total;\r\n\r\n";

	if ($onlineCheck=="" || $offline_count>0){
		include_once(F2BLOG_ROOT."./include/cache.php");
		writetocache('online',$onlines);
	}
}

//读取flash skin
if (!empty($getDefaultSkinInfo['UseFlash'])){
	if (file_exists(F2BLOG_ROOT."skins/$blogSkins/".$getDefaultSkinInfo['FlashPath'])){
		echo "<div id=\"FlashHead\" style=\"text-align:".$getDefaultSkinInfo['FlashAlign'].";top:".$getDefaultSkinInfo['FlashTop']."px;\"></div> \n";
		if ($getDefaultSkinInfo['FlashTransparent']!="0"){
			echo "<script type=\"text/javascript\">WriteHeadFlash('skins/$blogSkins/".$getDefaultSkinInfo['FlashPath']."','".$getDefaultSkinInfo['FlashWidth']."','".$getDefaultSkinInfo['FlashHeight']."',true)</script> \n";
		}else{
			echo "<script type=\"text/javascript\">WriteHeadFlash('skins/$blogSkins/".$getDefaultSkinInfo['FlashPath']."','".$getDefaultSkinInfo['FlashWidth']."','".$getDefaultSkinInfo['FlashHeight']."',false)</script> \n";
		}
	}
}
?>
<div id="container">
  <!--顶部-->
  <div id="header">
    <div id="blogname">
      <?php echo $settingInfo['name']?>
      <div id="blogTitle">
        <?php echo $settingInfo['blogTitle']?>
      </div>
    </div>
    <!--顶部菜单-->
    <div id="menu">
      <div id="Left"></div>
      <div id="Right"></div>
      <ul>
		<li class="menuL"></li>
		<?php include("cache/cache_logs_header.php")?>        
        <li class="menuR"></li>
      </ul>
    </div>
  </div>
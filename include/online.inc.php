<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');
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
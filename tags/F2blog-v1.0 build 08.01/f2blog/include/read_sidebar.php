<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "read_sidebar.php") {
    header("HTTP/1.0 404 Not Found");
}

//读取侧边栏
for ($i=0;$i<count($arrSideModule);$i++){	
	$sidename=$arrSideModule[$i]['name'];
	$sidetitle=replace_string($arrSideModule[$i]['modTitle']);
	$htmlcode=$arrSideModule[$i]['htmlCode'];
	$installDate=$arrSideModule[$i]['installDate'];
	$pluginPath=$arrSideModule[$i]['pluginPath'];
	$isInstall=$arrSideModule[$i]['isInstall'];
	
	//echo $htmlCode."<br>";
	if (ereg ("^side_.+()$",$htmlcode)){
		switch ($sidename) {
			case "category":
				side_category($sidename,$sidetitle,$isInstall); #类别1
				break;
			case "calendar":
				side_calendar($sidename,$sidetitle,$isInstall); #日历2
				break;
			case "guestbook":
				side_guestbook($sidename,$sidetitle,$isInstall); #最新留言3
				break;
			case "hotTags":
				side_hotTags($sidename,$sidetitle,$isInstall); #标签4
				break;
			case "recentlogs":
				side_recent_logs($sidename,$sidetitle,$isInstall); #日志5
				break;
			case "recentComments":
				side_recent_comment($sidename,$sidetitle,$isInstall); #评论6
				break;
			case "links":
				side_links($sidename,$sidetitle,$isInstall); #连接7
				break;
			case "archives":
				side_archive($sidename,$sidetitle,$isInstall); #归档8
				break;
			case "statistics":
				side_bloginfo($sidename,$sidetitle,$isInstall); #日志信息9
				break;
			case "aboutBlog":
				side_description($sidename,$sidetitle,$isInstall); #blog说明11
				break;
			case "search":
				side_search($sidename,$sidetitle,$isInstall); #搜索13
				break;
			case "skinSwitch":
				side_skin_switch($sidename,$sidetitle,$isInstall); #皮肤转换14
				break;
		}
	}else{
		if ($installDate>0){//表示为插件
			do_filter($sidename,$sidename,$sidetitle,$htmlcode,$isInstall);
		}else{
			//自定HTML代码
			side_other($sidename,$sidetitle,$htmlcode,$isInstall);
		}
	}
}
?>
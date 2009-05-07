<?php
/*
Plugin Name: createlogs
Plugin URI: http://korsen.f2blog.com
Description: 在侧边栏统计栏内显示建站到今的天数
Version: 1.0
Author: korsen
Author URI: http://korsen.f2blog.com
*/

// Install Plugin
function createlogs_install() {
	global $pluginName;
	$createdate=format_time("Y-m-d",filemtime("../include/config.php"));

	$arrPlugin['Name']="createlogs";  //Plugin name
	$arrPlugin['Desc']="createlogs";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['DefaultField']=array("createdate"); //Default Filed
	$arrPlugin['DefaultValue']=array("$createdate"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function createlogs_unstall() {
	$ActionMessage=unstall_plugins("createlogs");
	return $ActionMessage;
}
    
function createlogs() {
	include("cache/cache_modulesSetting.php");
	$createdate=$plugins_createlogs['createdate'];
	if (strpos($createdate,"-")>0){
		list($year,$month,$day)=explode("-",$createdate);
		if ($year!="" && $month!="" && $day!=""){
			$mktime_create=gmmktime(0,0,0,$month,$day,$year);
			$mktime_now=gmmktime(0,0,0,gmdate("m"),gmdate("d"),gmdate("Y"));
			$days=($mktime_now-$mktime_create)/(3600*24)+1;
			echo "<br />建站至今: $days 天";
		}else{
			echo "建站日期格式不对";
		}
	}else{
		echo "建站日期格式不对";
	}
}

add_filter('f2_stat', 'createlogs');
?>
<?php
/*
Plugin Name: nfo
Plugin URI: http://joesen.f2blog.com
Description: 此插件读取nfo文件转为图片(服务器须支持GD).
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function nfo_install() {
	global $settingInfo;

	//检查有无GD支持
	$gdVersion=gd_version();
	if($gdVersion=="0") {
		$ActionMessage="你的服务器不支持GD，不能使用此插件！请联系你的服务器供应商！";
	} else {
		$arrPlugin['Name']="nfo";  //Plugin name
		$arrPlugin['Desc']="NFO to Image";  //Plugin title
		$arrPlugin['Type']="Func";      //Plugin type
		$arrPlugin['Code']='';          //Plugin htmlcode
		$arrPlugin['Path']="";          //Plugin Path
		$arrPlugin['DefaultField']=array("bgcolor","txtcolor","imgtype"); //Default Filed
		$arrPlugin['DefaultValue']=array("#FFFFFF","#000000","png"); //Default value
		$ActionMessage=install_plugins($arrPlugin);
	}

	return $ActionMessage;
}

//Unstall Plugin
function nfo_unstall() {
	$ActionMessage=unstall_plugins("nfo");
	return $ActionMessage;
}
?>
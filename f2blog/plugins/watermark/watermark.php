<?php
/*
Plugin Name: watermark
Plugin URI: http://joesen.f2blog.com
Description: 此插件为图片附件加水印.
Author: Joesen
Version: 2.0
Author URI: http://joesen.f2blog.com
*/

function watermark_install() {
	global $settingInfo;

	//检查有无GD支持
	$gdVersion=gd_version();
	if($gdVersion=="0") {
		$ActionMessage="你的服务器不支持GD，不能使用此插件！请联系你的服务器供应商！";
	} else {
		$arrPlugin['Name']="watermark";  //Plugin name
		$arrPlugin['Desc']="水印";  //Plugin title
		$arrPlugin['Type']="Func";      //Plugin type
		$arrPlugin['Code']='';          //Plugin htmlcode
		$arrPlugin['Path']="";          //Plugin Path
		$arrPlugin['DefaultField']=array("wm_position","wm_image","wm_text","wm_font","wm_color","wm_transparence","wm_width","wm_height"); //Default Filed
		$arrPlugin['DefaultValue']=array("6","plugins/watermark/watermark.png",$settingInfo['blogUrl'],"5","#FF0000","85","200","200"); //Default value
		$ActionMessage=install_plugins($arrPlugin);
	}

	return $ActionMessage;
}

//Unstall Plugin
function watermark_unstall() {
	$ActionMessage=unstall_plugins("watermark");
	return $ActionMessage;
}
?>
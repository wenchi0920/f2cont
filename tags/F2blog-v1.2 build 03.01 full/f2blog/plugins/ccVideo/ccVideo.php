<?php
/*
Plugin Name: ccVideo
Plugin URI: http://korsen.f2blog.com
Description: CC视频联盟
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function ccVideo_install() {
	$arrPlugin['Name']="ccVideo";
	$arrPlugin['Desc']="CC视频";  
	$arrPlugin['Type']="Top";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="plugins/ccVideo/ccVideo.big.php";

	$arrPlugin['DefaultField']=array("ccuser","ccorder","ccstatus"); //Default Filed
	$arrPlugin['DefaultValue']=array("用户ID","1","0"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function ccVideo_unstall() {
	$ActionMessage=unstall_plugins("ccVideo");
	return $ActionMessage;
}

function do_ccVideo($Text,$post_id=""){
	global $strPlayMusic,$strOnlinePlay,$strOnlineStop;
	include(F2BLOG_ROOT."./cache/cache_modulesSetting.php");
	if (!empty($plugins_ccVideo) && is_numeric($plugins_ccVideo['ccuser'])){
		$ccstatus=$plugins_ccVideo['ccstatus'];
		
		if ($ccstatus==1){
			$Text=preg_replace('/\[cc\](.+?)\[\/cc\]/is','<embed src="http://union.bokecc.com/\\1" width="438" height="387" type="application/x-shockwave-flash"></embed>', $Text); 
		}else{
			$Text=preg_replace('/\[cc\](.+?)\[\/cc\]/ie','makemusic("http://union.bokecc.com/\\1|CC视频|438|387")', $Text); 
		}
	}

	return $Text;
}

add_filter('f2_content', 'do_ccVideo', 2);
?>
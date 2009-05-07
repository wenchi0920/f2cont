<?php
/*
Plugin Name: MyIcon
Version: 1.0
Plugin URI: http://www.cnblog.biz
Author: harry
Author URI: http://www.cnblog.biz
Description: 评论与留言时邮箱可以显示头像，需要去<a href="http://www.myicon.com.tw" target="_blank">http://www.myicon.com.tw申请头像</a>。
*/

// Install Plugin
function MyIcon_install() {
	$arrPlugin['Name']="MyIcon";  //Plugin name
	$arrPlugin['Desc']="可以让用户的邮箱显示头像，需要去<a href=http://www.myicon.com.tw target=_blank>申请头像</a>。";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$arrPlugin['DefaultField']=array("myIconsDefault","myIconsSize"); //Default Filed
	$arrPlugin['DefaultValue']=array("images/avatars/1.gif","80"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function MyIcon_unstall() {
	$ActionMessage=unstall_plugins("MyIcon");
	return $ActionMessage;
}

function MyEmailIcon($email) {
	global $myIcons,$settingInfo;

	if (!empty($email)){
		include(F2BLOG_ROOT."./cache/cache_modulesSetting.php");

		if (!empty($myIcons)){
			$plugins_MyIcon['myIconsDefault']=$myIcons;
		}else{
			if (empty($plugins_MyIcon['myIconsDefault'])) $plugins_MyIcon['myIconsDefault']="images/avatars/1.gif";
		}
		if (empty($plugins_MyIcon['myIconsSize'])) $plugins_MyIcon['myIconsSize']="80";

		if (strpos($plugins_MyIcon['myIconsDefault'],"://")<1) $plugins_MyIcon['myIconsDefault']=$settingInfo['blogUrl'].$plugins_MyIcon['myIconsDefault'];

		$iconurl = "http://myicon.com.tw/myicon.php?myicon_id=".md5($email)."&default=".urlencode($plugins_MyIcon['myIconsDefault'])."&size=".$plugins_MyIcon['myIconsSize'];
	}else if ($myIcons!=""){
		$iconurl = $myIcons;
	}else{
		$iconurl = "images/avatars/1.gif";
	}
	return $iconurl;
}
?>

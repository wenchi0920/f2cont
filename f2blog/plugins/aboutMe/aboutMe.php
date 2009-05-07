<?php
/*
Plugin Name: aboutMe
Plugin URI: http://korsen.f2blog.com
Description: 关于我
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function aboutMe_install() {
	$arrPlugin['Name']="aboutMe";
	$arrPlugin['Desc']="关于我";  
	$arrPlugin['Type']="Top";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="plugins/aboutMe/aboutMe.inc.php";
	$arrPlugin['DefaultField']=array(
		"站长昵称","头像地址","年 龄",
		"生 日","性 别","血 型",
		"星 座","地 址","个人说明",
		"兴趣爱好"
	); //Default Filed
	$arrPlugin['DefaultValue']=array(
		"未知","plugins/aboutMe/Myface.jpg","未知",
		"未知","未知","未知",
		"未知","未知","这家伙很懒什么也没留下",
		"这家伙很懒什么也没留下"
	); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function aboutMe_unstall() {
	$ActionMessage=unstall_plugins("aboutMe");
	return $ActionMessage;
}
?>
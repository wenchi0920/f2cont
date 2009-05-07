<?php
/*
Plugin Name: imagebox
Plugin URI: http://joesen.f2blog.com/index.php?load=read&id=166
Description: ImageBox相册
Author: Joesen & Kembo
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function imagebox_install() {
	$arrPlugin['Name']="imagebox";
	$arrPlugin['Desc']="相册";  
	$arrPlugin['Type']="Top";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="plugins/imagebox/imagebox.big.php";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function imagebox_unstall() {
	$ActionMessage=unstall_plugins("imagebox");
	return $ActionMessage;
}

?>

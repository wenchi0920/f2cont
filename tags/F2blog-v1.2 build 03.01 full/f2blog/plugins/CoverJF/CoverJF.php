<?php
/*
Plugin Name: CoverJF
Plugin URI: http://korsen.f2blog.com
Description: 繁简转换
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function CoverJF_install() {
	$arrPlugin['Name']="CoverJF";
	$arrPlugin['Desc']="繁体版";
	$arrPlugin['Type']="Top";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function CoverJF_unstall() {
	$ActionMessage=unstall_plugins("CoverJF");
	return $ActionMessage;
}

#欢迎介面
function CoverJF($topname,$toptitle){
?>
	<li><a class="menuA" id="<?php echo $topname?>" title="<?php echo $toptitle?>"><?php echo $toptitle?></a><script src="plugins/CoverJF/Std_StranJF.js" type="text/javascript"></script></li>
<?php
} #END欢迎介面

add_action('CoverJF','CoverJF',2);
?>
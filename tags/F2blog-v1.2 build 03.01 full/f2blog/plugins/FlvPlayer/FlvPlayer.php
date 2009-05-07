<?php
/*
Plugin Name: FlvPlayer
Plugin URI: http://joesen.f2blog.com/index.php?load=read&id=203
Description: Flv播放器
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function FlvPlayer_install() {
	$arrPlugin['Name']="FlvPlayer";
	$arrPlugin['Desc']="Flv播放器";  
	$arrPlugin['Type']="Func";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function FlvPlayer_unstall() {
	$ActionMessage=unstall_plugins("FlvPlayer");
	return $ActionMessage;
}

function FlvPlayer($code){
	$code=preg_replace("/<!--flvBegin-->(.+?)<!--flvEnd-->/ie", "makeFlv('\\1')", $code);
    return $code;
}

function makeFlv($code){
	global $strPlayMusic;
	$arrId=explode("|", $code);
	//$auto=$arrId[4];
	$width=$arrId[2];
	$height=$arrId[3];
	$url=$arrId[0];
	$flv_title=$arrId[1];
	//$auto==1?$auto="&autoPlay=true":$auto='';
	$auto='';
	
	$player="<br /><object style='width:{$width}px; height:{$height}px;' id='VideoPlayback' align='middle' type='application/x-shockwave-flash' data='plugins/FlvPlayer/flv.swf?videoUrl={$url}&thumbnailUrl=flv.jpg&playerMode=normal$auto'> ";
	$player.="<param name='allowScriptAccess' value='sameDomain' />";
	$player.="<param name='movie' value='plugins/FlvPlayer/flv.swf?videoUrl={$url}&thumbnailUrl=flv.jpg&playerMode=normal$auto'/>";
	$player.="<param name='quality' value='best' />";
	$player.="<param name='bgcolor' value='#ffffff' />";
	$player.="<param name='scale' value='noScale' />";
	$player.="<param name='wmode' value='window' />";
	$player.="<param name='salign' value='TL' /> </object>";

	$kkstr="<img src=\"plugins/FlvPlayer/flv.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$arrId[1];
	$kkstr.=$player;

	return $kkstr;
}

add_filter('f2_content','FlvPlayer');
?>
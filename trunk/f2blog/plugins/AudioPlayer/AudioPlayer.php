<?php
/*
Plugin Name: AudioPlayer
Description: Mp3Player
Author: Momo
Alter: Momo
Version: 1.0
*/

function AudioPlayer_install() {
	$arrPlugin['Name']="AudioPlayer";
	$arrPlugin['Desc']="Mp3Player";  
	$arrPlugin['Type']="Func";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function AudioPlayer_unstall() {
	$ActionMessage=unstall_plugins("AudioPlayer");
	return $ActionMessage;
}

function AudioPlayer($code){
	$code=preg_replace("/<!--audioBegin-->(.+?)<!--audioEnd-->/ie", "makeMp3('\\1')", $code);
    return $code;
}

function makeMp3($code){
	global $strPlayMusic;
	$arrId=explode("|", $code);
	//$auto=$arrId[4];
	$width=$arrId[2];
	$height=$arrId[3];
	$url=$arrId[0];
	$audio_title=$arrId[1];
	//$auto==1?$auto="&autoPlay=true":$auto='';
	
	$player="<br /><object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0' style='width:{$width}px; height:{$height}px;' id='fullscreen' align='middle'> ";
	$player.="<param name='allowScriptAccess' value='sameDomain' />";
	$player.="<param name='movie' value='plugins/AudioPlayer/audioplayer.swf'/>";
	$player.="<param name='quality' value='high' />";
	$player.="<param name='bgcolor' value='#ffffff' />";
	$player.="<param name='salign' value='TL' />";
	$player.="<param name=FlashVars value='soundFile={$url}'>";
	$player.="<embed src='plugins/AudioPlayer/audioplayer.swf' FlashVars='soundFile={$url}' quality='high' salign='tl' bgcolor='#ffffff' width='{$width}' height='{$height}' name='fullscreen' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' /> </object>";

	$kkstr="<img src=\"plugins/AudioPlayer/audio.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$arrId[1];
	$kkstr.=$player;

	return $kkstr;
}

add_filter('f2_content','AudioPlayer');
?>
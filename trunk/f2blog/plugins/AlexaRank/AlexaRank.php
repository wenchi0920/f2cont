<?php
/*
Plugin Name: AlexaRank
Plugin URI: http://joesen.f2blog.com
Description: this plugin allow you add the Alexa Rank into your blog.
Version: 1.2
Author: andot & Joesen
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function AlexaRank_install() {
	$arrPlugin['Name']="AlexaRank";  //Plugin name
	$arrPlugin['Desc']="AlexaRank";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function AlexaRank_unstall() {
	$ActionMessage=unstall_plugins("AlexaRank");
	return $ActionMessage;
}

function AlexaRank_css() {
	echo "<link rel=\"stylesheet\" href=\"plugins/AlexaRank/alexarank.css\" />\n";
}

function AlexaRank_js() {
	if ((!defined('PHPRPC_JS_CLIENT_LOADED')) || (PHPRPC_JS_CLIENT_LOADED == false)) {
	   echo "<script type=\"text/javascript\" src=\"plugins/AlexaRank/phprpc_client.js\"></script>\n";
	   define('PHPRPC_JS_CLIENT_LOADED', true);
	}
	echo "<script type=\"text/javascript\" src=\"plugins/AlexaRank/alexarank.js\"></script>\n";
}

function AlexaRank() {
	global $settingInfo;
	echo "<span id=\"alexa_container\">Alexa<a href=\"http://www.alexa.com/data/details/main?q=&amp;url=";
	echo urlencode($settingInfo['blogUrl']) . "\" style=\"display: inline; padding: 0; margin: 0\">";
	echo "<span id=\"alexa_bar\"><span id=\"alexa_border\"><span id=\"alexa_rank\"></span></span></span>\r\n";
	echo "</a></span>\r\n";
	echo "<script type=\"text/javascript\">AlexaRank();</script>\r\n";
}

add_action('f2_head', 'AlexaRank_css');
add_action('f2_head', 'AlexaRank_js');
add_action('f2_stat', 'AlexaRank');

?>
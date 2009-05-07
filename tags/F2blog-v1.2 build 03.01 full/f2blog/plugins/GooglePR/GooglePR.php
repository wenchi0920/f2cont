<?php
/*
Plugin Name: GooglePR
Plugin URI: http://joesen.f2blog.com
Description: this plugin allow you add the google PageRank into your blog.
Version: 1.2
Author: andot & Joesen
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function GooglePR_install() {
	global $pluginName;
	$arrPlugin['Name']="GooglePR";  //Plugin name
	$arrPlugin['Desc']="GooglePR";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function GooglePR_unstall() {
	$ActionMessage=unstall_plugins("GooglePR");
	return $ActionMessage;
}
    
function GooglePR_css() {
    echo "<link rel=\"stylesheet\" href=\"plugins/GooglePR/googlepr.css\" />\n";
}

function GooglePR_js() {
    if ((!defined('PHPRPC_JS_CLIENT_LOADED')) || (PHPRPC_JS_CLIENT_LOADED == false)) {
       echo "<script type=\"text/javascript\" src=\"plugins/GooglePR/phprpc_client.js\"></script>\n";
       define('PHPRPC_JS_CLIENT_LOADED', true);
    }
    echo "<script type=\"text/javascript\" src=\"plugins/GooglePR/googlepr.js\"></script>\n";
}

function GooglePR() {
    echo "<span id=\"googlepr_container\">PageRank";
    echo "<span id=\"googlepr_bar\"><span id=\"googlepr_border\"><span id=\"googlepr_rank\"></span></span></span>\r\n";
    echo "</span>\r\n";
    echo "<script type=\"text/javascript\">googlePR();</script>\r\n";
}

add_action('f2_head', 'GooglePR_css');
add_action('f2_head', 'GooglePR_js');
add_filter('f2_stat', 'GooglePR');
?>
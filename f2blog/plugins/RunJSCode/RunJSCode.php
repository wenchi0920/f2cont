<?php
/*
Plugin Name: RunJSCode
Plugin URI: http://joesen.f2blog.com/index.php?load=read&id=164
Description: 在线运行Javascript代码.
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function RunJSCode_install() {
	$arrPlugin['Name']="RunJSCode";  //Plugin name
	$arrPlugin['Desc']="在线运行Javascript代码";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function RunJSCode_unstall() {
	$ActionMessage=unstall_plugins("RunJSCode");
	return $ActionMessage;
}

function RunJSCode($code){
	$code=preg_replace('/&lt;RunJSCode&gt;(.+?)&lt;\/RunJSCode&gt;/ie', 'RunJSCodeConvert(\'\\1\')', $code);
	$code=str_replace("<p><RunJSCode>","",$code);
	$code=str_replace("</RunJSCode></p>","",$code);
	$code=str_replace("<RunJSCode>","",$code);
	$code=str_replace("</RunJSCode>","",$code);

    return $code;
}
 
function RunJSCodeConvert($code)
{
		$code=str_replace("<br />","\n",$code);
		$code=str_replace("</p> <p>","\r\n\r\n",$code);
		
		$default_id="";
		for ($index=0;$index<5;$index++){
			$default_id.=rand(0,9);
		}
		
		$textid="testcode".$default_id;
		$return="<textarea id=\"".$textid."\" style=\"height:150px;width:90%\" cols=\"1\" rows=\"1\" class=\"editTextarea\">".$code."</textarea>\n";
		$return.="<br /><input type=\"button\" value=\"运行代码\" class=\"userbutton\" onClick=\"runCode($textid)\">\n";
        $return.="&nbsp;<input type=\"button\" value=\"复制代码\" class=\"userbutton\" onClick=\"copyCode($textid)\">\n";
        $return.="&nbsp;<input type=\"button\" value=\"另存代码\" class=\"userbutton\" onClick=\"saveCode($textid)\">\n";
        $return.="&nbsp;&nbsp;<span style=\"color:#CC3333\"><b>提示：你可以先修改部分代码再运行</b></span><br /><br />\n";

    return $return;
}

function RunJSCode_js() {
    echo "<script type=\"text/javascript\" src=\"plugins/RunJSCode/code.js\"></script>\n";
}

add_filter('f2_content','RunJSCode');
add_action('f2_head', 'RunJSCode_js');
?>
<?php
include_once(substr(dirname(__FILE__), 0) . "/common.php");
$fieldCheck = array("ad_script");

function GoogleAdSense_setCode($arr) {	
	global $GoogleAdSense_plugin_config;
	$ad_js = readfromfile($GoogleAdSense_plugin_config["JSFile"]);
	$display_layout = <<<SETTING_HTML
   <table border="0" cellpadding="2" cellspacing="1">
      <tr>
        <td align="left" width="300">Ad-sense Script</td>        
      </tr>
      <tr>
        <td width="300"><textarea name="ad_script" rows="10" cols="40">$ad_js</textarea></td>
      </tr>
   </table>
SETTING_HTML;
	
	return $display_layout;
}

function GoogleAdSense_fieldCheck(){
	global $fieldCheck;
	$arr = $fieldCheck;
	return $arr;	
}

function GoogleAdSense_setSave($arr,$modId){
	global $GoogleAdSense_plugin_config;
	$ad_js = $arr["ad_script"];
	$err = false;
	
	if($ad_js && get_magic_quotes_gpc()){
		$ad_js = stripslashes($ad_js);
	}
	
	if(!is_writable($GoogleAdSense_plugin_config["JSFile"])){
		$err = true;
	} else {
		$f_handle = fopen($GoogleAdSense_plugin_config["JSFile"], "w");
		$err = !$f_handle?true:fwrite($f_handle, $ad_js)==false;
		fclose($f_handle);
	}
		
	$ActionMessage = $err?$GoogleAdSense_plugin_config["SettingError"]:"";
	return $ActionMessage;
}


?>	
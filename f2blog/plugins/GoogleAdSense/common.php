<?php
	$plugin_dir = substr(dirname(__FILE__), 0);
	$GoogleAdSense_plugin_config = array(
		"Name" => "GoogleAdSense",
		"JSFile" => $plugin_dir . "/ad_script.txt",
		"SettingError" => "<b><font color='red'>Please check ad_script.txt is writable.</font></b>"
		);

?>
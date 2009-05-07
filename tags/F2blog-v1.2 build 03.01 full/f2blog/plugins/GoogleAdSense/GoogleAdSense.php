<?php
/*
Plugin Name: GoogleAdSense
Plugin URI: http://forum.f2blog.com/forum-21-1.html
Description: Add Google AdSense to your blog.
Version: 0.1
Author: zach14c
Author URI: http://forum.f2blog.com/profile-uid-22.html
*/

include_once(substr(dirname(__FILE__), 0) . "/common.php");
// Install Plugin
function GoogleAdSense_install() {
	global $GoogleAdSense_plugin_config;
	$arrPlugin['Name'] = $GoogleAdSense_plugin_config["Name"]; 
	$arrPlugin['Desc'] = "Google Ad"; 
	$arrPlugin['Type'] = "Side";     
	$arrPlugin['Code'] = "";         
	$arrPlugin['Path'] = "";         
	$arrPlugin['DefaultField'] = array("dummy"); 
	$arrPlugin['DefaultValue'] = array("1"); 

	$ActionMessage = install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function GoogleAdSense_unstall() {
	global $GoogleAdSense_plugin_config;
	$ActionMessage = unstall_plugins($GoogleAdSense_plugin_config["Name"]);
	return $ActionMessage;
}
    
function GoogleAdSense($sidename, $sidetitle, $htmlcode, $isInstall) {
	global $GoogleAdSense_plugin_config;
	$ad_js = readfromfile($GoogleAdSense_plugin_config["JSFile"]);
?>	
<!-- Google Ad-Sense -->
<div class="sidepanel" id="Side_GoogleAd">
  <h4 class="Ptitle" style="cursor: pointer;"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="content_statistics" style="display:">
    <?php echo $ad_js?>
  </div> 
</div> 
<?php		
}
add_action('GoogleAdSense','GoogleAdSense',4);
?>
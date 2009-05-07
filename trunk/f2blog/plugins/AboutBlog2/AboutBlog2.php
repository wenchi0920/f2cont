<?php
/*
Plugin Name: AboutBlog2
Plugin URI: http://forum.f2blog.com/forum-21-1.html
Description: Show your blog logo and title without box.
Version: 0.1
Author: zach14c
Author URI: http://forum.f2blog.com/profile-uid-22.html
*/

// Install Plugin
function AboutBlog2_install() {
	$arrPlugin['Name'] = "AboutBlog2"; 
	$arrPlugin['Desc'] = "Show your blog logo and title without box."; 
	$arrPlugin['Type'] = "Side";     
	$arrPlugin['Code'] = "";         
	$arrPlugin['Path'] = "";         
	$arrPlugin['DefaultField'] = ""; 
	$arrPlugin['DefaultValue'] = ""; 

	$ActionMessage = install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function AboutBlog2_unstall() {
	global $GoogleAdSense_plugin_config;
	$ActionMessage = unstall_plugins("AboutBlog2");
	return $ActionMessage;
}
    
function AboutBlog2($sidename, $sidetitle, $htmlcode, $isInstall) {
	global $settingInfo;
	$logo_img = "./attachments/".$settingInfo['logo'];	
?>	
<!-- About Blog 2 -->
<div class="sidepanel" id="Side_AboutBlog">
  <div class="Pcontent" style="display:">
    <p align="center">
    <img src="<?php echo $logo_img?>" align="middle" alt="" /><br/>
    <?php echo $settingInfo['name']?><br/><?php echo $settingInfo['blogTitle']?></p>
  </div> 
</div> 
<?php		
}
add_action('AboutBlog2','AboutBlog2',4);
?>


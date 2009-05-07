<?php
/*
Plugin Name: blogHint
Plugin URI: http://joesen.f2blog.com/read-502.html
Description: 博客小贴士
Author: joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function blogHint_install() {
	$arrPlugin['Name']="blogHint";
	$arrPlugin['Desc']="博客小贴士";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function blogHint_unstall() {
	$ActionMessage=unstall_plugins("blogHint");
	return $ActionMessage;
}

#博客小贴士
function blogHint($sidename,$sidetitle,$isInstall){
	global $DBPrefix,$DMC,$settingInfo,$arrSideModule,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div id="Side_blogHint" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
		<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6&#44;0&#44;29&#44;0" height="230" width="200" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="plugins/blogHint/blogHint.swf" name="movie" /><param value="high" name="quality" /><param value="false" name="menu" /></object>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #END博客小贴士

add_filter("blogHint",'blogHint',3);
?>
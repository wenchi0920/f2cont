<?php
/*
Plugin Name: flashLink
Plugin URI: http://joesen.f2blog.com
Description: 此插件允许你用Flash显示友情链接 for F2Blog V1.1 or later.
Author: Joesen & Kembo
Version: 1.1
Author URI: http://joesen.f2blog.com
*/

function flashLink_install() {
	$arrPlugin['Name']="flashLink";  //Plugin name
	$arrPlugin['Desc']="Flash友情链接";  //Plugin title
	$arrPlugin['Type']="Side";      //Plugin type
	$arrPlugin['Code']=<<<HTML
		<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6&#44;0&#44;29&#44;0" height="210" width="168" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="plugins/flashLink/link.swf?xml_url=plugins/flashLink/link.xml" name="movie" /><param value="high" name="quality" /><param value="false" name="menu" /></object>		
HTML;
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['DefaultField']=array("logo","bgcolor","txtcolor","btbgcolor","btOverbgcolor","pagetextcolor"); //Default Filed
	$arrPlugin['DefaultValue']=array("plugins/flashLink/icon.gif","#CCFFFF","#3366FF","#FFFFCC","#FFCCFF","#33CC00"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	include_once("../plugins/flashLink/setting.php");
	flashLink_setUpdate();

	return $ActionMessage;
}

//Unstall Plugin
function flashLink_unstall() {
	$ActionMessage=unstall_plugins("flashLink");
	return $ActionMessage;
}

#flashLink
function flashLink($sidename,$sidetitle,$htmlcode,$isInstall){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--flashLink-->
<div id="Side_flashLink" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?php
	if ($htmlcode!=""){
		echo dencode($htmlcode);
	}else{
	?>
		<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6&#44;0&#44;29&#44;0" height="210" width="168" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="plugins/flashLink/link.swf?xml_url=plugins/flashLink/link.xml" name="movie" /><param value="high" name="quality" /><param value="false" name="menu" /></object>
	<?php }?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_action('flashLink','flashLink',4);
?>
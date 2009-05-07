<?php
/*
Plugin Name: flashDaily
Plugin URI: http://korsen.f2blog.com
Description: Flash Daily Statistics for F2Blog V1.1 or later.
Author: korsen & kembo
Version: 1.1
Author URI: http://korsen.f2blog.com
*/

function flashDaily_install() {
	$arrPlugin['Name']="flashDaily";
	$arrPlugin['Desc']="Flash Daily Statistics";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']=<<<HTML
		<object style="border:0px solid;" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="165" height="93.5"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="plugins/flashDaily/counter.swf?counter=7"/><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /></object>		
HTML;
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array("count_c","bg_bg","bg_line","bc_kado","bc_bg","bc_line","point","point_txt","v_grid","d_txt","point_line"); //Default Filed
	$arrPlugin['DefaultValue']=array("#333333","#FFFFFF","#999999","#999999","#F6F6F6","#F6F6F6","#B8CA35","#333333","#CCCCCC","#333333","#333333"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function flashDaily_unstall() {
	$ActionMessage=unstall_plugins("flashDaily");
	return $ActionMessage;
}

#FlashDailyStatistics
function flashDailyStatistics($sidename,$sidetitle,$htmlcode,$isInstall){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--FlashDailyStatistics-->
<div id="Side_flashDailyStatistics" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?php
	if ($htmlcode!=""){
		echo dencode($htmlcode);
	}else{
	?>
		<object style="border:0px solid;" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="165" height="93.5"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="plugins/flashDaily/counter.swf?counter=7"/><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /></object>
	<?php }?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #FlashDailyStatistics

add_action('flashDaily','flashDailyStatistics',4);
?>
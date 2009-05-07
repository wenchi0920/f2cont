<?php
/*
Plugin Name: FlashClock
Plugin URI: http://korsen.f2blog.com
Description: Flash Clock for F2Blog V1.1 or later.
Author: korsen & Pj
Version: 1.1
Author URI: http://korsen.f2blog.com
*/

function FlashClock_install() {
	$arrPlugin['Name']="FlashClock";
	$arrPlugin['Desc']="Flash Clock";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']=<<<HTML
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="130" height="130">
		<param name="movie" value="plugins/FlashClock/clock.swf">
		<param name="quality" value="high">
		<param name="wmode" value="transparent">
		<embed src="clock.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="130" height="130"></embed></object>
HTML;
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function FlashClock_unstall() {
	$ActionMessage=unstall_plugins("FlashClock");
	return $ActionMessage;
}

#FlashClockStatistics
function FlashClockStatistics($sidename,$sidetitle,$htmlcode,$isInstall){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--FlashClockStatistics-->
<div id="Side_FlashClockStatistics" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?php
	if ($htmlcode!=""){
		echo dencode($htmlcode);
	}else{
	?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="130" height="130">
		<param name="movie" value="plugins/FlashClock/clock.swf">
		<param name="quality" value="high">
		<param name="wmode" value="transparent">
		<embed src="clock.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="130" height="130"></embed></object>
	<?php }?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #FlashClockStatistics

add_action('FlashClock','FlashClockStatistics',4);
?>
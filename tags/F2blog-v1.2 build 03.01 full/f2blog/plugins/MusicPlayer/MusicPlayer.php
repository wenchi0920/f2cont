<?php
/*
Plugin Name: MusicPlayer
Plugin URI: http://www.maxup.net
Description: Music Player
Author: Jerry Chak
Version: 1.0
Author URI: http://www.maxup.net/read-7.html
*/

function MusicPlayer_install() {
	$arrPlugin['Name']="MusicPlayer";
	$arrPlugin['Desc']="Music Player";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array(
		"title1","creator1","location1",
		"title2","creator2","location2",
		"title3","creator3","location3"
	); //Default Filed
	$arrPlugin['DefaultValue']=array(
		"Far Away From Home","01","mp3/01.mp3",
		"","","",
		"","",""
	); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function MusicPlayer_unstall() {
	$ActionMessage=unstall_plugins("MusicPlayer");
	return $ActionMessage;
}

function MusicPlayer_js() {
    echo "<script type=\"text/javascript\" src=\"plugins/MusicPlayer/ufo.js\"></script>\n";
}

function MusicPlayer($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_MusicPlayer">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<script type="text/javascript">
	var FU = { 	movie:"plugins/MusicPlayer/Musicplayer.swf",width:"194",height:"150",majorversion:"7",build:"0",bgcolor:"#FFFFFF",
				flashvars:"file=plugins/MusicPlayer/Playerlist.xml&autostart=true&repeat=true&shuffle=false&lightcolor=0x0099CC&backcolor=0x000000&frontcolor=0xCCCCCC" };
	UFO.create(	FU, "<?php echo "content_$sidename"?>");
</script>
	</div>
  <div class="Pfoot"></div>
</div>

<?php
}

add_filter("MusicPlayer",'MusicPlayer',4);
add_action('f2_head', 'MusicPlayer_js');
?>
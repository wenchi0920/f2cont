<?php
/*
Plugin Name: onlinemusic
Plugin URI: http://korsen.f2blog.com
Description: 在线音乐
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function onlinemusic_install() {
	$arrPlugin['Name']="onlinemusic";
	$arrPlugin['Desc']="在线音乐";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function onlinemusic_unstall() {
	$ActionMessage=unstall_plugins("onlinemusic");
	return $ActionMessage;
}

#在线音乐
function onlinemusic($sidename,$sidetitle,$htmlcode,$isInstall){
	//读取音乐源
	if (function_exists(simplexml_load_file)){
		$xml = simplexml_load_file("plugins/onlinemusic/music.xml");
		
		$max=intval($xml->counter);
		if ($max>0){
			$random=rand(1,$max);
			$music_name=($xml->item[$random]->name);
			$music_path=$xml->item[$random]->path;
		}
	}else{
		//including khalid xml parser
		include_once "include/kxparse.php";
		//create the object
		$xmlnav=new kxparse("plugins/onlinemusic/music.xml");

		$max=intval($xmlnav->get_tag_text("music:counter","1:1"));
		if ($max>0){
			$random=rand(1,$max);
			$music_name=$xmlnav->get_tag_text("music:item:name","1:".$random.":1");
			$music_path=$xmlnav->get_tag_text("music:item:path","1:".$random.":1");
		}
	}
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--在线音乐-->
<div id="Side_OnlineMusic" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<p align="center"><?php echo $music_name?><br />
		<object classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6" id="WindowsMediaPlayer1" style="height: 47px; width: 170px">
			<param name="URL" ref="" value="plugins/onlinemusic/<?php echo $music_path?>" />
			<param name="rate" value="1" />
			<param name="balance" value="0" />
			<param name="currentPosition" value="0" />
			<param name="defaultFrame" value="" />
			<param name="playCount" value="1" />
			<param name="autoStart" value="-1" />
			<param name="currentMarker" value="0" />
			<param name="invokeURLs" value="-1" />
			<param name="baseURL" value="" />
			<param name="volume" value="50" />
			<param name="mute" value="0" />
			<param name="uiMode" value="mini" />
			<param name="stretchToFit" value="0" />
			<param name="windowlessVideo" value="0" />
			<param name="enabled" value="-1" />
			<param name="enableContextMenu" value="-1" />
			<param name="fullScreen" value="0" />
			<param name="SAMIStyle" value="" />
			<param name="SAMILang" value="" />
			<param name="SAMIFilename" value="" />
			<param name="captioningID" value="" />
			<param name="enableErrorDialogs" value="0" />
		</object>
	</p>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #END在线音乐

add_filter("onlinemusic",'onlinemusic',4);
?>
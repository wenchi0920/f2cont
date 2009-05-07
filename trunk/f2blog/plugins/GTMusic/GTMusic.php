<?php
/*
Plugin Name: GTMusic
Plugin URI: http://joesen.f2blog.com/read-462.html
Description: 防刷新音乐播放器
Author: 绿茶 & Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function GTMusic_install() {
	global $DMC,$DBPrefix;

	$arrPlugin['Name']="GTMusic";
	$arrPlugin['Desc']="GTMusic";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']='';
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']="";
	$arrPlugin['DefaultValue']="";

	//建表及插入默认数据
	$dbcharset = 'utf8';
	$curTime=time();
	
	//建立MusicSetting设定表
	if (!$DMC->query("SELECT id FROM ".$DBPrefix."musicsetting",'T')){
		$sql="CREATE TABLE `{$DBPrefix}musicsetting` (`id` INT( 1 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`BlogRoot` VARCHAR( 30 ) NOT NULL ,`view_player` TINYINT NOT NULL ,`set_autoplay` TINYINT NOT NULL ,`set_shuffle` TINYINT NOT NULL ,`set_loop` TINYINT NOT NULL ,`set_volume` INT NOT NULL ,`use_marquee` TINYINT NOT NULL ,`marquee_direction` TINYINT NOT NULL ,`marquee_scrolldelay` INT NOT NULL ) ENGINE = MYISAM $default_charset;";
		$DMC->query($sql);
		$DMC->query("INSERT INTO `{$DBPrefix}musicsetting` ( `id` , `BlogRoot` , `view_player` , `set_autoplay` , `set_shuffle` , `set_loop` , `set_volume` , `use_marquee` , `marquee_direction` , `marquee_scrolldelay` ) VALUES (1 , '天上的骆驼', '0', '0', '1', '1', '99', '1', '0', '50')");

		//建立MusicClass专辑表
		$sql="CREATE TABLE `{$DBPrefix}musicclass` (`ClassId` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`ClassName` VARCHAR( 20 ) NOT NULL ,`Commend` TINYINT( 1 ) NOT NULL ,`ClickNum` INT( 4 ) NOT NULL ) ENGINE = MYISAM $default_charset;";
		$DMC->query($sql);
		$DMC->query("INSERT INTO `{$DBPrefix}musicclass` ( `ClassId` , `ClassName` , `Commend` , `ClickNum` ) VALUES (1 , '流行音乐', '1', '0')");

		//建立音乐表
		$sql="CREATE TABLE `{$DBPrefix}music` (`MusicId` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`MusicName` VARCHAR( 50 ) NOT NULL ,`MusicSinger` VARCHAR( 20 ) NOT NULL ,`MusicUrl` VARCHAR( 250 ) NOT NULL ,`Commend` TINYINT( 1 ) NOT NULL ,`IsPlayList` TINYINT( 1 ) NOT NULL DEFAULT '1',`orderNo` TINYINT NOT NULL DEFAULT '0' ,`AppDate` VARCHAR( 20 ) NOT NULL ,`ClickNum` INT( 5 ) NOT NULL ,`DownNum` INT( 5 ) NOT NULL ,`ClassId` INT( 3 ) NOT NULL ) ENGINE = MYISAM $default_charset;";
		$DMC->query($sql);
		$DMC->query("INSERT INTO `{$DBPrefix}music` ( `MusicId` , `MusicName` , `MusicSinger` , `MusicUrl` , `Commend` , `IsPlayList` , `AppDate` , `ClickNum` , `DownNum` , `ClassId` ) VALUES (1 , '隐形的翅膀', '张韶涵', 'http://www.ynye.com/upocx/upfiles/2006428161927656.mp3', '5', '1', '$curTime', '0', '0', '1'), (2 , '美丽的神话', '成龙', 'http://www.027chem.cn/company/music/sh.mp3', '5', '1', '$curTime', '0', '0', '1')");
	}

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function GTMusic_unstall() {
	global $DMC,$DBPrefix;
	$DMC->query("DROP TABLE `{$DBPrefix}music`, `{$DBPrefix}musicclass`, `{$DBPrefix}musicsetting`");
	$ActionMessage=unstall_plugins("GTMusic");
	return $ActionMessage;
}

function GTMusic($sidename,$sidetitle,$htmlcode,$isInstall){
	global $DMC,$DBPrefix;

	$rsconfig = $DMC->fetchArray($DMC->query("select set_autoplay from {$DBPrefix}musicsetting where id='1'"));
	$set_autoplay=($rsconfig['set_autoplay']==1)?"true":"false";

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_GTMusic">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
		<center>
		<?php  if ($rsconfig['set_autoplay']!=1) { ?>
		<span id="playerbox">
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="168" height="22" title="GTMusic"> <param name="movie" value="plugins/GTMusic/open.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="plugins/GTMusic/open.swf" width="168" height="22" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed></object>
		</span>
		<?php  } ?>
		<span id="player">
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="168" height="22" title="GTMusic"> <param name="movie" value="plugins/GTMusic/GTMusic.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="plugins/GTMusic/GTMusic.swf" width="168" height="22" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed></object><br/>
			<span id="disp1" >GTMusic is loading...</span>
			<span id="disp2" class="time" style="padding-bottom:2px;font-size:9px; font-family:verdana;" nowrap></span>
		</span></center>
		<script type="text/javascript">
		<?php  if ($rsconfig['set_autoplay']==1) { ?>
			if(top==self){
				document.location="plugins/GTMusic/music.php"
			}
		<?php  } else { ?>
			if(top != self){
				playerbox.innerHTML=player.innerHTML;
			}
			player.innerHTML="";
		<?php  } ?>
			window.top.document.title=document.title;
		</script>
  <div class="Pfoot"></div>
  </div>
</div>
<?php
}

add_filter("GTMusic",'GTMusic',4);
?>
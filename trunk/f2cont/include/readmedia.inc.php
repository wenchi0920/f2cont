<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$id=$_GET['id'];
$media_id=$_GET['media'];

echo $media_id."+|*+|+*|+";

$dataInfo = $DMC->fetchArray($DMC->query("select name,fileWidth,fileHeight from ".$DBPrefix."attachments where id='$id'"));
if ($dataInfo) {
	//更新下载量
	$modify_sql="UPDATE ".$DBPrefix."attachments set downloads=downloads+1 WHERE id='$media_id'";
	$DMC->query($modify_sql);

	//更新附件Cache
	download_recache();
	attachments_recache();
	
	if (strpos($dataInfo['name'],"://")<1) {
		$strURL=$settingInfo['blogUrl']."attachments/".$dataInfo['name'];
	}else{
		$strURL=$dataInfo['name'];
	}

	$intWidth=($dataInfo['fileWidth']==0)?400:$dataInfo['fileWidth'];
	$intHeight=($dataInfo['fileHeight']==0)?300:$dataInfo['fileHeight'];
	
	$strType=strtolower(substr($dataInfo['name'],strrpos($dataInfo['name'],".")+1));
	$tmpstr="";

	switch($strType){
		case "swf":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$intWidth.'" height="'.$intHeight.'"><param name="movie" value="'.$strURL.'" /><param name="quality" value="high" /><param name="AllowScriptAccess" value="never" /></object>';
			break;
		case "flv":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object style="width:'.$intWidth.'px; height:'.$intHeight.'px;" id="VideoPlayback" align="middle" type="application/x-shockwave-flash" data="images/flv.swf?videoUrl='.$strURL.'&thumbnailUrl=flv.jpg&playerMode=normal"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="images/flv.swf?videoUrl='.$strURL.'&thumbnailUrl=flv.jpg&playerMode=normal"/><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="scale" value="noScale" /><param name="wmode" value="window" /><param name="salign" value="TL" /> </object>';
			break;

		case "wma":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" id="MediaPlayer" width="450" height="70"><param name=""howStatusBar" value="-1"><param name="AutoStart" value="False"><param name="Filename" value="'.$strURL.'"><param name="EnableContextMenu" value="0"></object>';
			break;
		case "mp3":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" id="MediaPlayer" width="450" height="70"><param name=""howStatusBar" value="-1"><param name="AutoStart" value="False"><param name="Filename" value="'.$strURL.'"><param name="Filename" value="'.$strURL.'"><param name="EnableContextMenu" value="0"></object>';
			break;
		case "wmv":
		case "asf":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,02,902" type="application/x-oleobject" standby="Loading..." width="'.$intWidth.'" height="'.$intHeight.'"><param name="FileName" VALUE="'.$strURL.'" /><param name="ShowStatusBar" value="-1" /><param name="AutoStart" value="true" /><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="'.$strURL.'" autostart="true" width="'.$intWidth.'" height="'.$intHeight.'" /></object>';
			break;
		case "rm":
		case "rmvb":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$intWidth.'" height="'.$intHeight.'"><param name="SRC" value="'.$strURL.'" /><param name="CONTROLS" VALUE="ImageWindow" /><param name="CONSOLE" value="one" /><param name="AUTOSTART" value="true" /><embed src="'.$strURL.'" nojava="true" controls="ImageWindow" console="one" width="'.$intWidth.'" height="'.$intHeight.'"></object>'.
			'<br/><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$intWidth.'" height="32" /><param name="CONTROLS" value="StatusBar" /><param name="AUTOSTART" value="true" /><param name="CONSOLE" value="one" /><embed src="'.$strURL.'" nojava="true" controls="StatusBar" console="one" width="'.$intWidth.'" height="24" /></object>'.'<br /><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$intWidth.'" height="32" /><param name="CONTROLS" value="ControlPanel" /><param name="AUTOSTART" value="true" /><param name="CONSOLE" value="one" /><embed src="'.$strURL.'" nojava="true" controls="ControlPanel" console="one" width="'.$intWidth.'" height="24" autostart="true" loop="false" /></object>';
			break;
		case "mpg":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:05589FA1-C356-11CE-BF01-00AA0055595A" id="ActiveMovie1" width="'.$intWidth.'" height="'.$intHeight.'"><param name="Appearance" value="0"><param name="AutoStart" value="-1"><param name="AllowChangeDisplayMode" value="-1"><param name="AllowHideDisplay" value="0"><param name="AllowHideControls" value="-1"><param name="AutoRewind" value="-1"><param name="Balance" value="0"><param name="CurrentPosition" value="0"><param name="DisplayBackColor" value="0"><param name="DisplayForeColor" value="16777215"><param name="DisplayMode" value="0"><param name="Enabled" value="-1"><param name="EnableContextMenu" value="0"><param name="EnablePositionControls" value="-1"><param name="EnableSelectionControls" value="0"><param name="EnableTracker" value="-1"><param name="Filename" value="'.$strURL.'" valuetype="ref"><param name="FullScreenMode" value="0"><param name="MovieWindowSize" value="0"><param name="PlayCount" value="1"><param name="Rate" value="1"><param name="SelectionStart" value="-1"><param name="SelectionEnd" value="-1"><param name="Volume" value="-480"></object>';
			break;
		case "avi":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object id="video" width="'.$intWidth.'" height="'.$intHeight.'" border="0" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"><param name="ShowDisplay" value="0" /><param name="ShowControls" value="1" /><param name="AutoStart" value="1" /><param name="AutoRewind" value="0" /><param name="PlayCount" value="0" /><param name="Appearance value="0 value=""" /><param name="BorderStyle value="0 value="" /><param name="MovieWindowHeight" value="240" /><param name="MovieWindowWidth" value="320" /><param name="FileName" value="'.$strURL.'" /></object>';
			break;
		case "divx":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="'.$intWidth.'" height="'.$intHeight.'" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab"><param name="src" value="'.$strURL.'" /><embed type="video/divx" src="'.$strURL.'" width="'.$intWidth.'" height="'.$intHeight.'" pluginspage="http://go.divx.com/plugin/download/"></embed></object>';
			break;
		case "ra":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" id="RAOCX" width="450" height="60"><param name="_ExtentX" value="6694"><param name="_ExtentY" value="1588"><param name="AUTOSTART" value="true"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="0"><param name="SRC" value="'.$strURL.'"><param name="CONTROLS" value="StatusBar,ControlPanel"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="0"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000"><embed src="'.$strURL.'" width="450" autostart="true" height="60"></embed></object>';
			break;
		case "qt":
			$tmpstr='<div style="height:6px;overflow:hidden"></div><embed src="'.$strURL.'" autoplay="true" loop="false" controller="true" playeveryframe="false" cache="false" scale="TOFIT" bgcolor="#000000" kioskmode="false" targetcache="false" pluginspage="http://www.apple.com/quicktime/" />';
	}

	echo $tmpstr;
}else{
	echo $strNoExits;
}
?>
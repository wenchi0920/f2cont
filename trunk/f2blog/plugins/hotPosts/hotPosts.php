<?php
/*
Plugin Name: hotPosts
Plugin URI: http://joesen.f2blog.com/read-503.html
Description: 热点日志
Author: joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function hotPosts_install() {
	$arrPlugin['Name']="hotPosts";
	$arrPlugin['Desc']="热点日志";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function hotPosts_unstall() {
	$ActionMessage=unstall_plugins("hotPosts");
	return $ActionMessage;
}

#热点日志
function hotPosts($sidename,$sidetitle,$isInstall){
	global $DBPrefix,$DMC,$settingInfo,$arrSideModule,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div id="Side_hotPosts" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?
	if ($settingInfo['rewrite']==0) $gourl="index.php?load=read&amp;id=";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/read-";
	if ($settingInfo['rewrite']==2) $gourl="read-";
	
	$i=0;
	$out_contents="";
	$result = $DMC->query("select a.postTime,b.nickname,a.author,a.id,a.logTitle from {$DBPrefix}logs as a left join {$DBPrefix}members as b on a.author=b.username where a.saveType='1' order by a.viewNums desc Limit 0,$settingInfo[sidelogsPage]");
	while ($my = $DMC->fetchArray($result)) {
		$author=($my['nickname']!="")?$my['nickname']:$my['author'];
		$content=str_replace("<br />","",subString($my['logTitle'],0,$settingInfo['sidelogslength']));

		$content=str_replace("{","!##_#_###_###_##!",$content);
		$content=str_replace("}","!##_###_###_##_#!",$content);
		$show_content=str_replace("[","\[",$settingInfo['sidelogsstyle']);
		$show_content=str_replace("]","\]",$show_content);
		$show_content=preg_replace("/({title})/is",$content,$show_content);
		$show_content=preg_replace("/({author})/is",$author,$show_content);
		$show_content=preg_replace("/{(.*?)}/ie","format_time('\\1',$my[postTime])",$show_content);
		$show_content=str_replace("\[","[",$show_content);
		$show_content=str_replace("\]","]",$show_content);
		$show_content=str_replace("!##_#_###_###_##!","{",$show_content);
		$show_content=str_replace("!##_###_###_##_#!","}",$show_content);

		$out_contents.="	<a class=\"sideA\" id=\"NewLog_Link_".$my['id']."\" title=\"".$author." $strSideBarAnd ".format_time("L",$my['postTime'])." $strHomePagePost \n".$my['logTitle']."\" href=\"$gourl".$my['id']."{$settingInfo['stype']}\">".$show_content."</a> \n";
		$i++;
	}
	echo $out_contents;
	?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #END热点日志

add_filter("hotPosts",'hotPosts',3);
?>
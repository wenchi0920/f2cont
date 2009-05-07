<?php
/*
Plugin Name: RecentTB
Plugin URI: http://10.1.0.101/f2blog/index.php?load=read&id=313
Description: 显示最新引用记录
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function RecentTB_install() {
	$arrPlugin['Name']="RecentTB";  //Plugin name
	$arrPlugin['Desc']="显示最新引用";  //Plugin title
	$arrPlugin['Type']="Side";      //Plugin type
	$arrPlugin['Code']="";			//Plugin Code
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);

	return $ActionMessage;
}

//Unstall Plugin
function RecentTB_unstall() {
	$ActionMessage=unstall_plugins("RecentTB");
	return $ActionMessage;
}

#RecentTB
function RecentTB($sidename,$sidetitle,$isInstall){
	global $DBPrefix,$DMC,$strSideBarAnd,$strHomePagePost;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--RecentTB-->
<div id="Side_RecentTB" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?php
	$tbsql="select * from ".$DBPrefix."trackbacks where isApp='1' order by postTime desc Limit 0,10";
	$tbrst=$DMC->query($tbsql);
	while ($my = $DMC->fetchArray($tbrst)) {
		$blogSite=str_replace("'","&#39;",$my['blogSite']);
		echo "<a class=\"sideA\" id=\"TB_Link\" title=\"".$blogSite." $strSideBarAnd ".format_time("L",$my['postTime'])." $strHomePagePost\n".$my['content']."\"  href=\"index.php?load=read&id=".$my['logId']."#tb".$my['id']."\">".$my['content']."</a> \n";
	}
	?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_action('RecentTB','RecentTB',3);
?>
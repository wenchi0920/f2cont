<?php
/*
Plugin Name: BaiDuKnow
Plugin URI: http://joesen.f2blog.com/read-486.html
Description: 百度知道
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function BaiDuKnow_install() {
	$arrPlugin['Name']="BaiDuKnow";
	$arrPlugin['Desc']="百度知道";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="&lt;script language=&quot;JavaScript&quot; type=&quot;text/JavaScript&quot; src=&quot;http://zhidao.baidu.com/q?ct=18&amp;cid=84&amp;tn=fcuqlclass&amp;pn=50&amp;lm=0&amp;rn=8&quot;&gt;&lt;/script&gt;";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function BaiDuKnow_unstall() {
	$ActionMessage=unstall_plugins("BaiDuKnow");
	return $ActionMessage;
}

function BaiDuKnow($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_BaiDuKnow">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
		<?php
		if ($htmlcode!=""){
			echo dencode($htmlcode);
		} else { 
		?>
		<script language="JavaScript" type="text/JavaScript" src="http://zhidao.baidu.com/q?ct=18&cid=84&tn=fcuqlclass&pn=50&lm=0&rn=8"></script>
		<?php }?>
   </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_filter("BaiDuKnow",'BaiDuKnow',4);
?>
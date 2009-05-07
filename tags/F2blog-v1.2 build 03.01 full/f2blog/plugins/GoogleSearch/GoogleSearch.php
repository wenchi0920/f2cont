<?php
/*
Plugin Name: GoogleSearch
Plugin URI: http://joesen.f2blog.com/read-484.html
Description: Google搜索
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function GoogleSearch_install() {
	$arrPlugin['Name']="GoogleSearch";
	$arrPlugin['Desc']="Google搜索";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="&lt;table align=&quot;center&quot;&gt;&lt;form method=&quot;get&quot; action=&quot;http://www.google.cn/custom&quot; target=&quot;google_window&quot;&gt;&lt;tr&gt;&lt;td nowrap=&quot;nowrap&quot; valign=&quot;top&quot; align=&quot;left&quot; height=&quot;32&quot;&gt;&lt;a href=&quot;http://www.google.com/&quot;&gt;&lt;img src=&quot;http://www.google.com/logos/Logo_25wht.gif&quot; border=&quot;0&quot; alt=&quot;Google&quot; align=&quot;middle&quot;&gt;&lt;/img&gt;&lt;/a&gt;&lt;br/&gt;&lt;label for=&quot;sbi&quot; style=&quot;display: none&quot;&gt;输入您的搜索字词&lt;/label&gt;&lt;input type=&quot;text&quot; name=&quot;q&quot; size=&quot;25&quot; maxlength=&quot;255&quot; value=&quot;&quot; id=&quot;sbi&quot; class=&quot;userpass&quot;&gt;&lt;/input&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td valign=&quot;top&quot; align=&quot;left&quot;&gt;&lt;label for=&quot;sbb&quot; style=&quot;display: none&quot;&gt;提交搜索表单&lt;/label&gt;&lt;input type=&quot;submit&quot; name=&quot;sa&quot; value=&quot;搜索&quot; id=&quot;sbb&quot; class=&quot;userbutton&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;client&quot; value=&quot;pub-1172684845739484&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;forid&quot; value=&quot;1&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;ie&quot; value=&quot;UTF-8&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;oe&quot; value=&quot;UTF-8&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;cof&quot; value=&quot;L:http://joesen.f2blog.com/images/logo.gif;S:http://joesen.f2blog.com;FORID:1&quot;&gt;&lt;/input&gt;&lt;input type=&quot;hidden&quot; name=&quot;hl&quot; value=&quot;zh-CN&quot;&gt;&lt;/input&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/form&gt;&lt;/table&gt;";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function GoogleSearch_unstall() {
	$ActionMessage=unstall_plugins("GoogleSearch");
	return $ActionMessage;
}

function GoogleSearch($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_GoogleSearch">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
		<?php
		if ($htmlcode!=""){
			echo dencode($htmlcode);
		} else { 
		?>
		<script language="JavaScript" type="text/JavaScript" src="http://zhidao.baidu.com/q?ct=18&cid=84&tn=fcuqlclass&pn=50&lm=0&rn=8"></script>
		<?
		}
		?>
   </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_filter("GoogleSearch",'GoogleSearch',4);
?>
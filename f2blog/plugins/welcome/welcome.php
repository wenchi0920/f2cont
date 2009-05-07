<?php
/*
Plugin Name: welcome
Plugin URI: http://korsen.f2blog.com
Description: 欢迎界面
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function welcome_install() {
	$arrPlugin['Name']="welcome";
	$arrPlugin['Desc']="欢迎界面";  
	$arrPlugin['Type']="Main";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="2";     //$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function welcome_unstall() {
	$ActionMessage=unstall_plugins("welcome");
	return $ActionMessage;
}

#欢迎介面
function WelcomeYou($mainname,$maintitle,$htmlcode){
?>
<!--欢迎介面-->
<div id="MainContent_<?php echo $mainname?>" class="content-width">
	<?php
	if ($htmlcode!=""){
		echo dencode($htmlcode);
	}else{
	?>
		<p style="LINE-HEIGHT: 140%" align="left">
		<strong>致Blogger :<a href="mailto:f2blog@f2blog.com">
		<img src="plugins/welcome/welcome.gif" alt="" border="0" align="right" style="WIDTH: 96px; HEIGHT: 96px" />
		</a></strong>
		<br /><br />
		感谢您使用 <strong><font color="#006600">F2Blog</font></strong> 。第一次使用，请先用进入<a href="admin/index.php" style="color:red"><span class="STYLE1">管理后台</span></a>建立你的日志分类，才能更好的使用f2blog。
		<br /><br />
		另外建议你进入后台设定<font color="#0000ff"><strong>界面管理-设置模块</strong></font>，可以开启和关闭一些模块，以适应你的喜好。
		<br /><br>F2blog开发团队
		</p>
		<hr />
	<?php }?>
</div>
<?php
} #END欢迎介面

add_filter("welcome",'WelcomeYou',4);
?>
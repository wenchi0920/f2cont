<?php
/*
Plugin Name: copyrights
Plugin URI: http://korsen.f2blog.com
Description: 免责声明
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function copyrights_install() {
	$arrPlugin['Name']="copyrights";
	$arrPlugin['Desc']="免责声明";  
	$arrPlugin['Type']="Main";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="3";     //$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function copyrights_unstall() {
	$ActionMessage=unstall_plugins("copyrights");
	return $ActionMessage;
}

#免责声明
function copyrightsYou($mainname,$maintitle,$htmlcode){
?>
<!--免责声明-->
<div id="MainContent_<?php echo $mainname?>" class="content-width">
	<div class="Content-top">
		<h1 class="ContentTitle"><a class="titleA">免责声明</a></h1>
	</div>
	<div class="Content-body">
		<?php
		if ($htmlcode!=""){
			echo dencode($htmlcode);
		}else{
		?>
			<font color="#339900">本博客立志于收集各类儿童教育资料及技术信息，便于本人和广大网友及家长查询检索，无论公司或个人认为本站存在侵权内容均可与本站联系，任何此类反馈信息一经查明属实后，将立即删除！</font>
		<?php }?>
    </div>	
</div>
<?php
} #END免责声明

add_filter("copyrights",'copyrightsYou',4);
?>
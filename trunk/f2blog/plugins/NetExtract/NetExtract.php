<?php
/*
Plugin Name: NetExtract
Plugin URI: http://joesen.f2blog.com/read-460.html
Description: 在日志下方增加一行发送到各网摘程序的链接
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function NetExtract_install() {
	$arrPlugin['Name']="NetExtract";
	$arrPlugin['Desc']="在日志下方增加一行发送到各网摘程序的链接";  
	$arrPlugin['Type']="Main";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="5";//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function NetExtract_unstall() {
	$ActionMessage=unstall_plugins("NetExtract");
	return $ActionMessage;
}

#加入网摘
function NetExtractYou($mainname,$maintitle,$htmlcode){
?>
<!--加入网摘-->
<div class="comment">
	<div class="commenttop">
		<img src="plugins/NetExtract/images/vivi.gif" alt="" style="margin:0px 4px -3px 0px"/><strong>&nbsp;加入网摘</strong>
	</div>
	<div class="commentcontent">
		<?php
		if ($htmlcode!=""){
			echo dencode($htmlcode);
		}else{
		?>
			<script type="text/javascript" src="plugins/NetExtract/NetExtract.js"></script>
		<?php }?>
    </div>	
</div>
<?php
} #END加入网摘

add_filter("NetExtract",'NetExtractYou',4);
?>
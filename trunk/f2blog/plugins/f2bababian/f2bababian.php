<?php
/*
Plugin Name: f2bababian
Plugin URI: http://korsen.f2blog.com
Description: 巴巴变相册 for F2Blog V1.1 or later.
Author: korsen
Version: 1.1
Author URI: http://korsen.f2blog.com
*/

function f2bababian_install() {
	$arrPlugin['Name']="f2bababian";
	$arrPlugin['Desc']="巴巴变相册";  
	$arrPlugin['Type']="Top";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="plugins/f2bababian/f2bababian.inc.php";
	$arrPlugin['DefaultField']=array("bbb_email","bbb_api_key","bbb_user_key","bbb_user_id","bbb_per_row","bbb_per_page","bbb_size","bbb_showimage"); //Default Filed
	$arrPlugin['DefaultValue']=array("","","","","4","12","100",""); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function f2bababian_unstall() {
	$ActionMessage=unstall_plugins("f2bababian");
	return $ActionMessage;
}

function do_bababian_js(){
	echo <<<HTML
	<script type="text/javascript">
	function open_exif(a){
		if (document.getElementById(a).style.display==""){
			document.getElementById(a).style.display="none";
		}else{
			document.getElementById(a).style.display="";
		}
	}

	function switch_image(a){
		document.getElementById("photopath").src=a;
	}
	</script>\n
HTML;
}

function do_bababian_css(){
	echo <<<HTML
	<style type="text/css">
		.bbb_show{width:100%; list-style-type: none;margin:0px;padding:0px; text-align:center}
		.bbb_show li{width:23%;border:0px #eee dotted;float:left;margin:0px;padding:0px;}
		.bbb_img {width:auto;}
		.bbb_img img{margin-top:20px;border:10px #eee solid;text-align:center;}
		.bbb_title{font-size:20px;font-weight:bold;margin-top:20px;text-align:center;}
		.bbb_tools{margin-top:10px;text-align:center;}
		.bbb_readimg {text-align:center;}
		.bbb_readimg img{margin-top:10px;margin-bottom:10px;border:10px #eee solid;text-align:center;}
		.bbb_subtitle{font-weight:bold;text-align:center;}
		.bbb_setimg{
			BACKGROUND: url(plugins/f2bababian/set_case.gif) no-repeat; PADDING-BOTTOM: 7px; VERTICAL-ALIGN: middle; WIDTH: 91px; PADDING-TOP: 8px; HEIGHT: 95px; TEXT-ALIGN: center;margin-top:20px;
		}
		.bbb_keyword {margin-top:10px;margin-bottom:10px;border:1px;}
	</style>\n
HTML;
}

add_action('f2_head', 'do_bababian_js');
add_action('f2_head', 'do_bababian_css');
?>
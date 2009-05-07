<?
/*
Plugin Name: lightbox2
Plugin URI: http://korsen.f2blog.com
Description: lightbox2 for f2blog v1.1 or later
Author: korsen
Version: 1.1
Author URI: http://korsen.f2blog.com
*/

function lightbox2_install() {
	$arrPlugin['Name']="lightbox2";
	$arrPlugin['Desc']="lightbox2";  
	$arrPlugin['Type']="Func";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function lightbox2_unstall() {
	$ActionMessage=unstall_plugins("lightbox2");
	return $ActionMessage;
}

function rplightbox2Tag($text) {
	$text=preg_replace('/<img (.*?) onclick=\"open_img\(&#39;([^\s]+?)&#39;\)\" (.*?)\/>/is','<a href="\\2" rel="lightbox[plants]" target="_blank"><img \\1 \\3/></a>',$text);
	$text = preg_replace('/(<a(.*?)href="([^"]*.)(bmp|gif|jpeg|jpg|png)"(.*?)><img)/ie','(strstr("\2\5","rel=") ? "\1" : "<a\2href=\"\3\4\"\5 rel=\"lightbox[plants]\"><img")',$text);
	return $text;
}

function addlightbox2css(){
	echo <<<HTML
	<link rel="stylesheet" href="plugins/lightbox2/css/lightbox.css" type="text/css" media="screen" />
	
	<script src="plugins/lightbox2/js/prototype.js" type="text/javascript"></script>
	<script src="plugins/lightbox2/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	<script src="plugins/lightbox2/js/lightbox.js" type="text/javascript"></script>
HTML;
}

add_action('f2_head', 'addlightbox2css');
add_filter('f2_content', 'rplightbox2Tag',1);
?>

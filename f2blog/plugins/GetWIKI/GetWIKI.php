<?php
/*
Plugin Name: GetWIKI
Version: 1.0
Plugin URI: http://joesen.f2blog.com/index.php?load=read&id=94
Author: Joesen
Author URI: http://joesen.f2blog.com
Description: 将Google检索到对指定术语的定义动态引入到博客文章中来.使用方法~GetWIKI(检索名)~
*/

// Install Plugin
function GetWIKI_install() {
	$arrPlugin['Name']="GetWIKI";  //Plugin name
	$arrPlugin['Desc']="将Google检索到对指定术语的定义动态引入到博客文章中来";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function GetWIKI_unstall() {
	$ActionMessage=unstall_plugins("GetWIKI");
	return $ActionMessage;
}

function GetWIKI($def) {	    
    $fp = fopen ("http://www.google.com/search?ie=utf8&oe=utf8&q=define:".$def, "r");
	if (!$fp) {
	   exit;
	}
	$contents ="";
	$article="";
	while (!feof ($fp)) {
	   $contents .= fread($fp, 8192);
   }

   if (eregi ("<ul type=\"disc\">(.*)</ul>", $contents, $out)) {
       $article = $out[1];
   }

   if (eregi ("<li>(.*)<li>", $contents, $out)) {
       $article = $out[1];
   }

	 $article=eregi_replace("<a(.*)</a>","",$article);
	 $article=strip_tags($article);
 
	fclose($fp);
	$article=trim($article);
	if($article!=""){
		$description=  "</br><span class=\"WIKIdesc\">(The definition of ".$def.' is from <a href="http://www.google.com">Google</a>)</span>';
		$article = "<div class=\"WIKI\"><img align=\"left\" src=\"plugins/GetWIKI/images/knowledge.gif\"><b>[".$def."]</b>".$article.$description."</div>";
	}

	return $article;
}

function GetWIKI_PR($text) {
    $text = preg_replace(
        "#\~GetWIKI\((\S*)\)\~#imseU",
        "GetWIKI('$1')",
        $text
    );

	return $text;
}

function GetWIKI_css() {
    echo "<link rel=\"stylesheet\" href=\"plugins/GetWIKI/GetWIKI.css\" />\n";
}

add_action('f2_head', 'GetWIKI_css');
add_filter('f2_content', 'GetWIKI_PR', 2);
?>

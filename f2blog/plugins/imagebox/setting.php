<?PHP
//setting HtmlCode
function imagebox_setCode($arr) {
	$string = "<iframe id=\"set\" name=\"set\" frameborder=0 width=\"100%\" height=\"0\" marginheight=0 marginwidth=0 scrolling=auto src=\"../plugins/imagebox/setting-info.php\"></iframe>";
	$string .= "<SCRIPT FOR=window EVENT=onload LANGUAGE=\"JScript\">\n";
	$string .= "document.all(\"set\").height=set.document.body.scrollHeight;\n";
	$string .= "</SCRIPT>\n";

	return $string;
}

// save setting
function imagebox_setSave($arr,$modId) {
	
}

?>
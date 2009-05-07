<?php
//setting HtmlCode
function createlogs_setCode($arr) {
	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\" width=\"100%\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">建站时间</td> \n";
	$string .= "    <td class=\"whitefont\"><input name=\"createdate\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$arr['createdate']."\"/> (日期格式：YYYY-MM-DD)</td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function createlogs_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	setPlugSet($modId,"createdate",$arr["createdate"]);
}
?>
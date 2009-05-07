<?php
//setting HtmlCode
function smilies_setCode($arr) {
	global $cfg_mouseover_color;
	$arr_keys=array_keys($arr);
	for ($i=0;$i<count($arr_keys);$i++){
		if (!intval($arr_keys[$i]) && $arr_keys[$i]!="0"){
			$new_keys[]=$arr_keys[$i];
		}
	}

	//print_r($new_keys);
	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td width=\"200px\" class=\"whitefont\">表情代号</td> \n";
	$string .= "    <td width=\"400px\" class=\"whitefont\">表情路径</td> \n";
	$string .= "    <td width=\"200px\" class=\"whitefont\" align=\"center\">预览</td> \n";
	$string .= "  </tr> \n";
	for ($i=0;$i<count($arr)/2;$i++){
		$num=$i;
		$file_name=strtolower($arr[$num]);
		$string .= "<tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td width=\"200px\"><input name=\"Field$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$new_keys[$i]."\"/></td> \n";
		$string .= "    <td width=\"400px\"><input name=\"Value$num\" class=\"textbox\" type=\"input\" size=\"40\" value=\"".$arr[$new_keys[$i]]."\" onchange=\"document.getElementById('img$num').src='../plugins/smilies/smilies/'+Value$num.value\"></td> \n";
		$string .= "    <td width=\"200px\" align=\"center\"><img src=\"../plugins/smilies/smilies/".$arr[$num]."\" border=\"0\" id=\"img$num\"></td> \n";
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td width=\"200px\"><input name=\"Field$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"\"/></td> \n";
	$string .= "    <td width=\"400px\"><input name=\"Value$num\" class=\"textbox\" type=\"input\" size=\"40\" value=\"\"/></td> \n";
	$string .= "    <td width=\"200px\">&nbsp;</td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function smilies_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	$sql="delete from ".$DBPrefix."modsetting where modId='$modId'";
	$DMC->query($sql);

	for($i=0;$i<count($arr);$i++) {
		setPlugSet($modId,$arr["Field$i"],$arr["Value$i"]);
	}

	//print_r($arr);
	
	return $ActionMessage;
}
?>
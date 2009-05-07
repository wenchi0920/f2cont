<?php
//setting HtmlCode
function aboutMe_setCode($arr) {
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
	$string .= "    <td width=\"300\" class=\"whitefont\">名称</td> \n";
	$string .= "    <td width=\"300\" class=\"whitefont\">内容</td> \n";
	$string .= "    <td width=\"300\" class=\"whitefont\" align=\"center\">预览</td> \n";
	$string .= "  </tr> \n";
	for ($i=0;$i<count($arr)/2;$i++){
		$num=$i;
		$file_name=strtolower($arr[$num]);
		$string .= "<tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td width=\"100px\"><input name=\"Field$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$new_keys[$i]."\"/></td> \n";
		$string .= "    <td width=\"500px\"><input name=\"Value$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"".$arr[$new_keys[$i]]."\"/></td> \n";
		if (strpos($file_name,".gif")>0 || strpos($file_name,".jpg")>0 || strpos($file_name,".jpeg")>0 || strpos($file_name,".png")>0){
			$string .= "    <td width=\"300px\" align=\"center\"><a href=\"../".$arr[$num]."\" target=\"_blank\"><img src=\"../".$arr[$num]."\" width=\"100px\" height=\"50px\" border=\"0\"></a></td> \n";
		}else{
			$string .= "    <td width=\"300px\">&nbsp;</td> \n";
		}
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td width=\"100px\"><input name=\"Field$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"\"/></td> \n";
	$string .= "    <td width=\"500px\"><input name=\"Value$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"\"/></td> \n";
	$string .= "    <td width=\"300px\">&nbsp;</td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function aboutMe_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	$sql="delete from ".$DBPrefix."modsetting where modId='$modId'";
	$DMC->query($sql);

	for($i=0;$i<count($arr)/2;$i++) {
		setPlugSet($modId,$arr["Field$i"],$arr["Value$i"]);
	}
}
?>
<?php
//setting HtmlCode
function SiteFocus_setCode($arr) {
	global $cfg_mouseover_color;

	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td width=\"200\" class=\"whitefont\">图片名称</td> \n";
	$string .= "    <td width=\"300\" class=\"whitefont\">图片位置</td> \n";
	$string .= "    <td width=\"300\" class=\"whitefont\">连接地址</td> \n";
	$string .= "	<td width=\"100\" class=\"whitefont\">图片预览</td> \n";
	$string .= "  </tr> \n";
	for ($i=0;$i<count($arr)/6;$i++){
		$num=$i+1;
		$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td width=\"200px\"><input name=\"imgtext$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['imgtext'.$num]."\"/></td> \n";
		$string .= "    <td width=\"300px\"><input name=\"imgurl$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['imgurl'.$num]."\"/></td> \n";
		$string .= "    <td width=\"300px\"><input name=\"imglink$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['imglink'.$num]."\"/></td> \n";
		$string .= "	<td width=\"100px\"><a href=\"../".$arr['imgurl'.$num]."\" target=\"_blank\"><img src=\"../".$arr['imgurl'.$num]."\" width=\"100px\" height=\"50px\" border=\"0\"></a></td> \n";
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td width=\"200px\"><input name=\"imgtext$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"\"/></td> \n";
	$string .= "    <td width=\"300px\"><input name=\"imgurl$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"\"/></td> \n";
	$string .= "    <td width=\"300px\"><input name=\"imglink$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"\"/></td> \n";
	$string .= "	<td width=\"100px\">&nbsp;</td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function SiteFocus_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	for($i=1;$i<=count($arr)/3;$i++) {
		if (!empty($arr["imgtext$i"])) {
			setPlugSet($modId,"imgtext$i",$arr["imgtext$i"]);
			setPlugSet($modId,"imgurl$i",$arr["imgurl$i"]);
			setPlugSet($modId,"imglink$i",$arr["imglink$i"]);
		}
	}
	
	//Check file visit access
	$xmlFile="../plugins/SiteFocus/site.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>site.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		$filecontent="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$filecontent.="<sitefocus>\n";
		for ($i=1;$i<=count($arr)/3;$i++){
			  if (!empty($arr["imgtext$i"])){	
				  $filecontent.="	<item>\n";
				  $filecontent.="		<imgtext>".$arr["imgtext$i"]."</imgtext>\n";
				  $filecontent.="		<imgurl>".$arr["imgurl$i"]."</imgurl>\n";
				  $filecontent.="		<imglink>".encode($arr["imglink$i"])."</imglink>\n";
				  $filecontent.="	</item>\n";
			  }
		}
		$filecontent.="</sitefocus>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}
?>
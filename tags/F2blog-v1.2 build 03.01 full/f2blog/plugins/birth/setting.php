<?php
//setting HtmlCode
function birth_setCode($arr) {
	global $cfg_mouseover_color;

	$path = "../plugins/birth";
	$string = "<script language=JavaScript src=\"../plugins/birth/picker.js\"></script> \n";
	$string .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td width=\"20%\" class=\"whitefont\">姓名</td> \n";
	$string .= "    <td width=\"15%\" class=\"whitefont\">出生日期(yyyy-mm-dd)</td> \n";
	$string .= "	<td width=\"20%\" class=\"whitefont\">文字颜色</td> \n";
	$string .= "	<td width=\"20%\" class=\"whitefont\"></td> \n";
	$string .= "  </tr> \n";
	for ($i=0,$max=count($arr)/6;$i<$max;$i++){
		$num=$i+1;
		$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td><input name=\"birthTitle$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$arr['birthTitle'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"birthDate$num\" class=\"textbox\" type=\"input\" size=\"10\"  maxlength=\"10\" value=\"".$arr['birthDate'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"textColor$num\" class=\"textbox\" type=\"text\" size=\"8\" value=\"".$arr['textColor'.$num]."\" onchange=\"document.getElementById('stextColor$num').style.background=textColor$num.value;\"/><a href=\"javascript:TCP.popup(document.forms['seekform'].elements['textColor$num'],document.getElementById('stextColor$num').style, 0)\"><img border=\"0\" src=\"$path/Rect.gif\"></a></td> \n";
		$string .=  "<td width=\"100\" id=\"stextColor$num\" bgcolor=\"".$arr['textColor'.$num]."\">&nbsp;</td> \n";
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td><input name=\"birthTitle$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"birthDate$num\" class=\"textbox\" type=\"input\" size=\"10\"  maxlength=\"10\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"textColor$num\" class=\"textbox\" type=\"text\" size=\"8\" value=\"\" onchange=\"document.getElementById('stextColor$num').style.background=textColor$num.value;\"/><a href=\"javascript:TCP.popup(document.forms['seekform'].elements['textColor$num'],document.getElementById('stextColor$num').style, 0)\"><img border=\"0\" src=\"$path/Rect.gif\"></a></td> \n";
	$string .=  "<td width=\"100\" id=\"stextColor$num\" bgcolor=\"\">&nbsp;</td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function birth_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	for($i=1;$i<=count($arr)/3;$i++) {
		setPlugSet($modId,"birthTitle$i",$arr["birthTitle$i"]);
		setPlugSet($modId,"birthDate$i",$arr["birthDate$i"]);
		setPlugSet($modId,"textColor$i",$arr["textColor$i"]);
	}
	
	//Check file visit access
	$xmlFile="../plugins/birth/birth.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>birth.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		$filecontent="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$filecontent.="<birth>\n";
		for ($i=0;$i<count($arr)/3;$i++){
			  $num=$i+1;
			  if ($arr["birthTitle$num"]){	
				  $filecontent.="	<item>\n";
				  $filecontent.="		<birthTitle>".$arr["birthTitle$num"]."</birthTitle>\n";
				  $filecontent.="		<birthDate>".$arr["birthDate$num"]."</birthDate>\n";
				  $filecontent.="		<textColor>".$arr["textColor$num"]."</textColor>\n";
				  $filecontent.="	</item>\n";
			  }
		}
		$filecontent.="</birth>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);
		$ActionMessage="";
	}
	
	return $ActionMessage;
}
?>
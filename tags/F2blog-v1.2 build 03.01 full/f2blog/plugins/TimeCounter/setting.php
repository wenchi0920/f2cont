<?php
//setting HtmlCode
function TimeCounter_setCode($arr) {
	global $cfg_mouseover_color;

	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td width=\"20%\" class=\"whitefont\">计时标题</td> \n";
	$string .= "    <td width=\"15%\" class=\"whitefont\">目标日期(yyyy-mm-dd)</td> \n";
	$string .= "    <td width=\"15%\" class=\"whitefont\">目标时间(hh:mm:ss)</td> \n";
	$string .= "	<td width=\"50%\" class=\"whitefont\">计时牌样式</td> \n";
	$string .= "  </tr> \n";
	for ($i=0,$max=count($arr)/8;$i<$max;$i++){
		$num=$i+1;
		$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td><input name=\"CounterTitle$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$arr['CounterTitle'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"TargetDate$num\" class=\"textbox\" type=\"input\" size=\"10\"  maxlength=\"10\" value=\"".$arr['TargetDate'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"TargetTime$num\" class=\"textbox\" type=\"input\" size=\"8\"  maxlength=\"8\" value=\"".$arr['TargetTime'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"CounterStyle$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"".$arr['CounterStyle'.$num]."\"/></td> \n";
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td><input name=\"CounterTitle$num\" class=\"textbox\" type=\"input\" size=\"20\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"TargetDate$num\" class=\"textbox\" type=\"input\" size=\"10\"  maxlength=\"10\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"TargetTime$num\" class=\"textbox\" type=\"input\" size=\"8\"  maxlength=\"8\" value=\"\"/></td> \n";
	$string .= "	<td><input name=\"CounterStyle$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"\"/></td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function TimeCounter_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	for($i=1;$i<=count($arr)/4;$i++) {
		setPlugSet($modId,"CounterTitle$i",$arr["CounterTitle$i"]);
		setPlugSet($modId,"TargetDate$i",$arr["TargetDate$i"]);
		setPlugSet($modId,"TargetTime$i",$arr["TargetTime$i"]);
		setPlugSet($modId,"CounterStyle$i",$arr["CounterStyle$i"]);
	}
	
	//Check file visit access
	$xmlFile="../plugins/TimeCounter/TimeCounter.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>TimeCounter.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		//Write XML
		$filecontent="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$filecontent.="<TimeCounter>\n";
		for ($i=0;$i<count($arr)/4;$i++){
			  $num=$i+1;
			  if ($arr["CounterTitle$num"]!=""){	
				  $filecontent.="	<item>\n";
				  $filecontent.="		<CounterTitle>".$arr["CounterTitle$num"]."</CounterTitle>\n";
				  $filecontent.="		<TargetDate>".$arr["TargetDate$num"]."</TargetDate>\n";
				  $filecontent.="		<TargetTime>".$arr["TargetTime$num"]."</TargetTime>\n";
				  $filecontent.="		<CounterStyle>".$arr["CounterStyle$num"]."</CounterStyle>\n";
				  $filecontent.="	</item>\n";
			  }
		}
		$filecontent.="</TimeCounter>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);
		$ActionMessage="";
	}
	
	return $ActionMessage;
}
?>
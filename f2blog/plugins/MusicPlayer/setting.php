<?php
//setting HtmlCode
function MusicPlayer_setCode($arr) {
	global $cfg_mouseover_color;

	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td width=\"25%\" class=\"whitefont\">歌曲名稱</td> \n";
	$string .= "    <td width=\"25%\" class=\"whitefont\">歌手名稱</td> \n";
	$string .= "    <td width=\"50%\" class=\"whitefont\">歌曲位置</td> \n";
	$string .= "  </tr> \n";
	for ($i=0,$max=count($arr)/6;$i<$max;$i++){
		$num=$i+1;
		$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
		$string .= "    <td><input name=\"title$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['title'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"creator$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['creator'.$num]."\"/></td> \n";
		$string .= "    <td><input name=\"location$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"".$arr['location'.$num]."\"/></td> \n";
		$string .= "  </tr> \n";
	}
	$num++;
	$string .= "  <tr class=\"subcontent-input\" onMouseOver=\"this.style.backgroundColor='$cfg_mouseover_color'\" onMouseOut=\"this.style.backgroundColor=''\"> \n";
	$string .= "    <td><input name=\"title$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"creator$num\" class=\"textbox\" type=\"input\" size=\"30\" value=\"\"/></td> \n";
	$string .= "    <td><input name=\"location$num\" class=\"textbox\" type=\"input\" size=\"80\" value=\"\"/></td> \n";
	$string .= "  </tr> \n";
    $string .= "</table> \n";

	return $string;
}

// save setting
function MusicPlayer_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	for($i=0;$i<count($arr);$i++) {
		setPlugSet($modId,"title$i",$arr["title$i"]);
		setPlugSet($modId,"creator$i",$arr["creator$i"]);
		setPlugSet($modId,"location$i",$arr["location$i"]);
	}
	
	//Check file visit access
	$xmlFile="../plugins/MusicPlayer/Playerlist.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>playerlist.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		//Write XML
		$filecontent="<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n";
		$filecontent.="<trackList>\n";
		for ($i=0;$i<count($arr);$i++){
			  $num=$i+1;
			  if ($arr["title$num"]!=""){	
				  $filecontent.="	<track>\n";
				  $filecontent.="		<title>".$arr["title$num"]."</title>\n";
				  $filecontent.="		<creator>".$arr["creator$num"]."</creator>\n";
				  $filecontent.="		<location>".$arr["location$num"]."</location>\n";
				  $filecontent.="	</track>\n";
			  }
		}
		$filecontent.="</trackList>\n";
		$filecontent.="</playlist>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);
		$ActionMessage="";
	}
	
	return $ActionMessage;
}
?>
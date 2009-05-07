<?php
$flashLink_fieldCheck=array("logo","bgcolor","txtcolor","btbgcolor","btOverbgcolor","pagetextcolor");

//setting HtmlCode
function flashLink_setCode($arr) {
	global $flashLink_fieldCheck;

	$chk=@implode(",",$flashLink_fieldCheck);
	$class[0]=(strpos(",".$chk,"logo")>0)?"input-titleblue":"";
	$class[1]=(strpos(",".$chk,"bgcolor")>0)?"input-titleblue":"";
	$class[2]=(strpos(",".$chk,"txtcolor")>0)?"input-titleblue":"";
	$class[3]=(strpos(",".$chk,"btbgcolor")>0)?"input-titleblue":"";
	$class[4]=(strpos(",".$chk,"btOverbgcolor")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"pagetextcolor")>0)?"input-titleblue":"";

	$path = "../plugins/flashLink";
	$string = <<<HTML
   <script language=JavaScript src="$path/picker.js"></script>
   <table border="0" cellpadding="2" cellspacing="1" style="margin:6px">
      <tr>
        <td align="right" width="200" class="$class[0]">小图片</td>
        <td width="300"><input name="logo" class="textbox" type="input" size="32" value="$arr[0]"/></td>
		<td width="100"><img src="../$arr[0]"></td>
      </tr>
      <tr>
        <td align="right" class="$class[1]">背景颜色</td>
        <td><input id="bgcolor" name="bgcolor" class="textbox" type="text" size="32" value="$arr[1]" onchange="document.getElementById('color1').style.background=bgcolor.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bgcolor'],document.getElementById('color1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		<td id="color1" bgcolor="$arr[1]">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="$class[2]">文字颜色</td>
        <td><input name="txtcolor" class="textbox" type="input" size="32" value="$arr[2]" onchange="document.getElementById('color2').style.background=txtcolor.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['txtcolor'],document.getElementById('color2').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		<td id="color2" bgcolor="$arr[2]">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="$class[3]">按钮背景颜色</td>
        <td><input name="btbgcolor" class="textbox" type="input" size="32" value="$arr[3]" onchange="document.getElementById('color3').style.background=btbgcolor.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['btbgcolor'],document.getElementById('color3').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		<td id="color3" bgcolor="$arr[3]">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="$class[4]">鼠标滑过按钮背景颜色</td>
        <td><input name="btOverbgcolor" class="textbox" type="input" size="32" value="$arr[4]" onchange="document.getElementById('color4').style.background=btOverbgcolor.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['btOverbgcolor'],document.getElementById('color4').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
	<td id="color4" bgcolor="$arr[4]">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="$class[5]">页面文字颜色</td>
        <td><input name="pagetextcolor" class="textbox" type="input" size="32" value="$arr[5]" onchange="document.getElementById('color5').style.background=pagetextcolor.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['pagetextcolor'],document.getElementById('color5').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		<td id="color5" bgcolor="$arr[5]">&nbsp;</td>
      </tr>
    </table>
HTML;

	return $string;
}

//Retun check field list
function flashLink_fieldCheck() {
	global $flashLink_fieldCheck;
	$arr=$flashLink_fieldCheck;
	return $arr;
}

// save setting
function flashLink_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	$fieldList=array("logo","bgcolor","txtcolor","btbgcolor","btOverbgcolor","pagetextcolor");
	for($i=0;$i<count($fieldList);$i++) {
		$name=$fieldList[$i];
		setPlugSet($modId,$name,$arr[$name]);
	}
	
	//Check file visit access
	$xmlFile="../plugins/flashLink/link.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>Link.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		//Write XML
		$links = array();

		$sql="select * from ".$DBPrefix."links where isSidebar=1 and isApp=1 order by orderNo";
		$result=$DMC->query($sql);
		while($fa = $DMC->fetchArray($result)){
			$links[] = array(
			  'name' => $fa['name'],
			  'blogUrl' => $fa['blogUrl'],
			);
		}
		
		$filecontent="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$filecontent.="<link";
		for($i=0;$i<count($fieldList);$i++) {
			$name=$fieldList[$i];
			$value=str_replace("#","0x",$arr[$name]);
			$filecontent.="	$name=\"$value\"";
		}
		$filecontent.=">\n";
		foreach($links as $link){
			$filecontent.="   <item url=\"".$link['blogUrl']."\">".$link['name']."</item>\n";
		}
		$filecontent.="</link>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);
		
	    $ActionMessage="";
	}
	
	return $ActionMessage;
}

//Update
function flashLink_setUpdate() {
	global $DBPrefix;
	
	$plugin="flashLink";
	$modId=getFieldValue($DBPrefix."modules","name='$plugin'","id");
	$arr=getModSet($plugin);

	$ActionMessage=flashLink_setSave($arr,$modId);
}

add_filter("f2_link",'flashLink_setUpdate');

?>
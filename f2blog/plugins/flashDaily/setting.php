<?php
$flashDaily_fieldCheck=array("count_c","bg_bg","bg_line","bc_kado","bc_bg","bc_line","point","point_txt","v_grid","d_txt","point_line");

//setting HtmlCode
function flashDaily_setCode($arr) {
	global $flashDaily_fieldCheck;

	$chk=@implode(",",$flashDaily_fieldCheck);
	$class[0]=(strpos(",".$chk,"count_c")>0)?"input-titleblue":"";
	$class[1]=(strpos(",".$chk,"bg_bg")>0)?"input-titleblue":"";
	$class[2]=(strpos(",".$chk,"bg_line")>0)?"input-titleblue":"";
	$class[3]=(strpos(",".$chk,"bc_kado")>0)?"input-titleblue":"";
	$class[4]=(strpos(",".$chk,"bc_bg")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"bc_line")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"point")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"point_txt")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"v_grid")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"d_txt")>0)?"input-titleblue":"";

	$path = "../plugins/flashDaily";
	$string = <<<HTML
	<script language=JavaScript src="../plugins/flashLink/picker.js"></script>
    <table border="0" cellpadding="2" cellspacing="1" style="margin:6px">
       <tr>
         <td width="200" align="right" class="$class[1]">垂直坐标数字颜色</td>
         <td width="300"><input name="count_c" class="textbox" type="text" size="32" value="$arr[0]" onchange="document.getElementById('count_c1').style.background=count_c.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['count_c'],document.getElementById('count_c1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td width="100" id="count_c1" bgcolor="$arr[0]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[2]">背景颜色</td>
         <td><input name="bg_bg" class="textbox" type="input" size="32" value="$arr[1]" onchange="document.getElementById('bg_bg1').style.background=bg_bg.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bg_bg'],document.getElementById('bg_bg1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="bg_bg1" bgcolor="$arr[1]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[3]">坐标线颜色</td>
         <td><input name="bg_line" class="textbox" type="input" size="32" value="$arr[2]" onchange="document.getElementById('bg_line1').style.background=bg_line.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bg_line'],document.getElementById('bg_line1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="bg_line1" bgcolor="$arr[2]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[4]">水平分隔线颜色</td>
         <td><input name="bc_kado" class="textbox" type="input" size="32" value="$arr[3]" onchange="document.getElementById('bc_kado1').style.background=bc_kado.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bc_kado'],document.getElementById('bc_kado1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="bc_kado1" bgcolor="$arr[3]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">水平分隔背景颜色</td>
         <td><input name="bc_bg" class="textbox" type="input" size="32" value="$arr[4]" onchange="document.getElementById('bc_bg1').style.background=bc_bg.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bc_bg'],document.getElementById('bc_bg1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="bc_bg1" bgcolor="$arr[4]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">垂直坐标点颜色</td>
         <td><input name="bc_line" class="textbox" type="input" size="32" value="$arr[5]" onchange="document.getElementById('bc_line1').style.background=bc_line.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['bc_line'],document.getElementById('bc_line1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="bc_line1" bgcolor="$arr[5]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">区域框中点的颜色</td>
         <td><input name="point" class="textbox" type="input" size="32" value="$arr[6]" onchange="document.getElementById('point1').style.background=point.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['point'],document.getElementById('point1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="point1" bgcolor="$arr[6]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">点上边字的颜色</td>
         <td><input name="point_txt" class="textbox" type="input" size="32" value="$arr[7]" onchange="document.getElementById('point_txt1').style.background=point_txt.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['point_txt'],document.getElementById('point_txt1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="point_txt1" bgcolor="$arr[7]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">垂直分隔线颜色</td>
         <td><input name="v_grid" class="textbox" type="input" size="32" value="$arr[8]" onchange="document.getElementById('v_grid1').style.background=v_grid.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['v_grid'],document.getElementById('v_grid1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="v_grid1" bgcolor="$arr[8]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">水平坐标数字颜色</td>
         <td><input name="d_txt" class="textbox" type="input" size="32" value="$arr[9]" onchange="document.getElementById('d_txt1').style.background=d_txt.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['d_txt'],document.getElementById('d_txt1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="d_txt1" bgcolor="$arr[9]">&nbsp;</td>
       </tr>
       <tr>
         <td align="right" class="$class[5]">点与点连接线颜色</td>
         <td><input name="point_line" class="textbox" type="input" size="32" value="$arr[9]" onchange="document.getElementById('point_line1').style.background=point_line.value;"/><a href="javascript:TCP.popup(document.forms['seekform'].elements['point_line'],document.getElementById('point_line1').style, 0)"><img border="0" src="$path/Rect.gif"></a></td>
		 <td id="point_line1" bgcolor="$arr[9]">&nbsp;</td>
       </tr>
     </table>
HTML;

	return $string;
}

//Retun check field list
function flashDaily_fieldCheck() {
	global $flashDaily_fieldCheck;
	$arr=$flashDaily_fieldCheck;
	return $arr;
}

// save setting
function flashDaily_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	$fieldList=array("count_c","bg_bg","bg_line","bc_kado","bc_bg","bc_line","point","point_txt","v_grid","d_txt","point_line");
	for($i=0;$i<count($fieldList);$i++) {
		$name=$fieldList[$i];
		setPlugSet($modId,$name,$arr[$name]);
		//echo $name."    ".$arr[$name]."<br />";
	}
	
	//Check file visit access
	$xmlFile="../plugins/flashDaily/color.xml";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($xmlFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>color.xml => Please change the CHMOD as 777.</font></b>";
	} else {
		$filecontent="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$filecontent.="<color ";
		for($i=0;$i<count($fieldList);$i++) {
			$name=$fieldList[$i];
			$value=str_replace("#","0x",$arr[$name]);
			$filecontent.="	$name=\"$value\"\n";
		}
		$filecontent.="/>\n";

		$fp=fopen($xmlFile,"wb");
		@fwrite($fp,$filecontent);
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}

?>
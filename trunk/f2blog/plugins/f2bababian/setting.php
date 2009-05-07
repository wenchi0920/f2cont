<?php
//setting HtmlCode
function f2bababian_setCode($arr) {
	global $cfg_mouseover_color;
	if (empty($arr['bbb_user_key'])){
		$input_class="input-titleblue";
	}else{
		$input_class="";
	}

	if (empty($arr['bbb_email'])) $arr['bbb_email']="";
	if (empty($arr['bbb_password'])) $arr['bbb_password']="";
	if (empty($arr['bbb_api_key'])) $arr['bbb_api_key']="";
	if (empty($arr['bbb_user_key'])) $arr['bbb_user_key']="";

	$string = <<<HTML
<table width="100%" border="0" cellpadding="0" cellspacing="5">
  <tr>
    <td width="50%" rowspan="2" valign="top">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr class="subcontent-title">
        <td colspan="3" align="center">如果你已注册巴巴变的账户请绑定，如果没有，[<a href="http://www.bababian.com/register.sl" target="_blank">单击此处注册！</a>]</td>
      </tr>
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td width="10px" class="subcontent-input">&nbsp;</td>
        <td width="200px" class="subcontent-input"><span class="$input_class">Email</span></td>
        <td width="700px" class="subcontent-input"><input id="bbb_email" name="bbb_email" type="text" size="40" value="$arr[bbb_email]"/></td>
      </tr>   
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td class="subcontent-input">&nbsp;</td>
        <td class="subcontent-input"><span class="$input_class">Password</span></td>
        <td class="subcontent-input"><input id="bbb_password" name="bbb_password" type="password" size="41" value=""/></td>
      </tr>  
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td class="subcontent-input">&nbsp;</td>
        <td class="subcontent-input"><span class="$input_class">API Key</span></td>
        <td class="subcontent-input"><input id="bbb_api_key" name="bbb_api_key" type="text" size="40" value="$arr[bbb_api_key]"/></td>
      </tr> 			
    </table>
	<input name="bbb_step" type="hidden" value="login"/>
HTML;

if ($arr['bbb_user_key']==""){
	$string .= <<<HTML
	</tr>
</table>	
HTML;
}

if ($arr['bbb_user_key']!=""){
	$selected75="";
	$selected100="";
	$selected240="";
	if ($arr['bbb_size']=="75") $selected75="selected";
	if ($arr['bbb_size']=="100") $selected100="selected";
	if ($arr['bbb_size']=="240") $selected240="selected";
	
	if (empty($arr['bbb_showimage'])) {
		$imageselected0="selected";
		$imageselected800="";
	}else{
		$imageselected0="";
		$imageselected800="selected";
	}

	$input_class="input-titleblue";

	$string .= <<<HTML
	<br />
    <table border="0" cellpadding="0" cellspacing="0">
      <tr class="subcontent-title">
        <td colspan="3" align="center">相关设定</td>
      </tr>
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td width="10px" class="subcontent-input">&nbsp;</td>
        <td width="300px" class="subcontent-input"><span class="$input_class">每行显示</span></td>
        <td width="700px" class="subcontent-input"><input id="bbb_per_row" name="bbb_per_row" type="text" size="10" value="$arr[bbb_per_row]"/> 张 (1至10否则报错) </td>
      </tr>   
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td class="subcontent-input">&nbsp;</td>
        <td class="subcontent-input"><span class="$input_class">每页显示</span></td>
        <td class="subcontent-input"><input id="bbb_per_page" name="bbb_per_page" type="text" size="10" value="$arr[bbb_per_page]"/> 张  (10至20否则报错)</td>
      </tr>  
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td class="subcontent-input">&nbsp;</td>
        <td class="subcontent-input"><span class="$input_class">略图大小</span></td>
        <td class="subcontent-input">
			<select name="bbb_size" id="bbb_size">
              <option value="75" $selected75>75 px</option>
              <option value="100" $selected100>100 px</option>
			  <option value="240" $selected240>240 px</option>
            </select> px (75,100,240)
		</td>
      </tr>  
      <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='$cfg_mouseover_color'" onMouseOut="this.style.backgroundColor=''">
		<td class="subcontent-input">&nbsp;</td>
        <td class="subcontent-input"><span class="$input_class">图片浏览大小</span></td>
        <td class="subcontent-input">
			<select name="bbb_showimage" id="bbb_showimage">
              <option value="800" $imageselected800>800*600</option>
              <option value="0" $imageselected0>原始大</option>
            </select> 针对大图
		</td>
      </tr>  		
    </table>
	<input name="bbb_step" type="hidden" value="setting"/>
	</td>
	<td width="50%" align="center" class="subcontent-title">文件上载 [<A href="../plugins/f2bababian/bbb_upload.php" target=up>点击刷新</A>]</td>
	</tr>
	<tr>
	<td>
	<IFRAME id=up border=0 name=up marginWidth=0 marginHeight=0 src="../plugins/f2bababian/bbb_upload.php" frameBorder=0 width="100%" height=300></IFRAME>
	</td>
	</tr>
</table>
HTML;
}
	return $string;
}

//Retun check field list
function f2bababian_fieldCheck() {
	global $arr;
	if (empty($arr['bbb_user_key'])){
		$arr=array("bbb_email","bbb_password","bbb_api_key");
	}else{
		$arr=array("bbb_per_row","bbb_per_page","bbb_size","bbb_showimage");
	}
	return $arr;
}

// save setting
function f2bababian_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	/*$sql="delete from ".$DBPrefix."modsetting where modId='$modId'";
	$DMC->query($sql);

	for($i=0;$i<count($arr);$i++) {
		setPlugSet($modId,$arr["Field$i"],$arr["Value$i"]);
	}*/
	$ActionMessage="";

	//注册用户
	if ($arr['bbb_step']=="login"){
		include ('../plugins/f2bababian/BabaBian.php');
		$GoBind=new BabaBian();	
		$bbb_email=$arr['bbb_email'];
		$bbb_password=$arr['bbb_password'];
		$bbb_api_key=$arr['bbb_api_key'];
		$GoBind->api_key=$bbb_api_key;
		if ($bbb_email!="" && $bbb_password!="" && $bbb_api_key!=""){
			//echo $bbb_email."===".$bbb_password."===".$bbb_api_key;
			if($GoBind->userbind($bbb_email,$bbb_password)) {		
				list($bbb_user_key, $bbb_user_id)=$GoBind->user_key_id;
				//echo "User Key:".$user_key."+++ $userID <br />";
				setPlugSet($modId,"bbb_email",$bbb_email);
				setPlugSet($modId,"bbb_api_key",$bbb_api_key);
				setPlugSet($modId,"bbb_user_key",$bbb_user_key);
				setPlugSet($modId,"bbb_user_id",$bbb_user_id);
			}else{
				$ActionMessage.=$GoBind->fault[0]."(".$GoBind->fault[1].")<br />"; //显示错误相关信息
			}
		}else{
			$ActionMessage="请输入完整巴巴账号，密码与api key。";
		}
	}

	//保存参数
	if ($arr['bbb_step']=="setting"){
		$bbb_per_row=$arr['bbb_per_row'];
		$bbb_per_page=$arr['bbb_per_page'];
		$bbb_size=$arr['bbb_size'];
		$bbb_showimage=$arr['bbb_showimage'];
		if ($bbb_per_row!="" && $bbb_per_page!="" && $bbb_size!="" && $bbb_showimage!=""){
			$check_info=true;
			if ($bbb_per_row<1 || $bbb_per_row>10){
				$ActionMessage="你输入的每行显示数不正确，必须为1~10。";
				$check_info=false;
			}
			if (($bbb_per_page<10 || $bbb_per_row>20) && $check_info==true){
				$ActionMessage="你输入的每页显示数不正确，必须为10~20。";
				$check_info=false;
			}

			if ($check_info==true){
				//echo $bbb_per_row."===".$bbb_per_page."===".$bbb_size."===".$bbb_showimage;
				setPlugSet($modId,"bbb_per_row",$bbb_per_row);
				setPlugSet($modId,"bbb_per_page",$bbb_per_page);
				setPlugSet($modId,"bbb_size",$bbb_size);
				setPlugSet($modId,"bbb_showimage",$bbb_showimage);
			}
		}else{
			$ActionMessage="你输入的设定值不正确！";
		}
	}
	//print_r($arr);
	
	return $ActionMessage;
}
?>
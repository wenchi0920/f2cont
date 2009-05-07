<?php
function Delicious_setCode($arr) {	
	$display_layout = <<<SETTING_HTML
   <table border="0" cellpadding="2" cellspacing="1">
      <tr>
        <td align="left" width="300">User Name</td>
      </tr>
      <tr>
        <td width="300"><input type="text" name="f_user" value="$arr[user]"></td>
      </tr>
   </table>
SETTING_HTML;
	
	return $display_layout;
}

function Delicious_fieldCheck(){
	$arr = array("f_user");
	return $arr;	
}

function Delicious_setSave($arr,$modId){
	global $DMC, $DBPrefix;
	setPlugSet($modId,"user",$arr["f_user"]);
}


?>	
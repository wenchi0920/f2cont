<?php
$vote_fieldCheck=array("voteWidth","voteHeight","voteTM");

//setting HtmlCode
function vote_setCode($arr) {
	global $vote_fieldCheck;
	
	$voteTM=$arr['voteTM'];
	$voteTM_0=($voteTM=="0")?"selected":"";
	$voteTM_1=($voteTM=="1")?"selected":"";

	$string = <<<HTML
   <table border="0" cellpadding="2" cellspacing="1" style="margin:6px">
          <tr>
            <td class="input-titleblue" align="right">显示宽度:</td>
            <td colspan=2><input type="text" name="voteWidth" size="3" class="textbox" value="$arr[voteWidth]"></td>
          </tr>
          <tr>
            <td class="input-titleblue" align="right">显示高度:</td>
            <td colspan=2><input type="text" name="voteHeight" size="3" class="textbox" value="$arr[voteHeight]"></td>
          </tr>
          <tr>
            <td class="input-titleblue" align="right">是否透明:</td>
            <td colspan=2 style="padding-top:10px"><select name="voteTM" class="textbox">
				<option value="0" $voteTM_0>透明</option>
                <option value="1" $voteTM_1>不透明</option>
              </select></td>
          </tr>
	</table>
HTML;

	return $string;
}

//Retun check field list
function vote_fieldCheck() {
	global $vote_fieldCheck;
	$arr=$vote_fieldCheck;
	return $arr;
}

// save setting
function vote_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	$fieldList=array("voteWidth","voteHeight","voteTM");
	$filecontent="";
	for($i=0;$i<count($fieldList);$i++) {
		$name=$fieldList[$i];
		$filecontent.=",".$arr[$name];
		setPlugSet($modId,$name,$arr[$name]);
	}
	$filecontent=substr($filecontent,1);
	
	//Check file visit access
	$configFile="../plugins/vote/vote.txt";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($configFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>vote.txt => Please change the CHMOD as 777.</font></b>";
	} else {
		$fp = @fopen($configFile, 'wbt');
		@fwrite($fp, $filecontent);
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}
?>
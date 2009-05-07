<?php

$nfo_fieldCheck=array("bgcolor","txtcolor","imgtype");

//setting HtmlCode
function nfo_setCode($arr) {
	global $nfo_fieldCheck;
	
	$chk=@implode(",",$nfo_fieldCheck);
	$class[0]=(strpos(",".$chk,"bgcolor")>0)?"input-titleblue":"";
	$class[1]=(strpos(",".$chk,"txtcolor")>0)?"input-titleblue":"";
	$class[2]=(strpos(",".$chk,"imgtype")>0)?"input-titleblue":"";

	$imgtype=$arr[imgtype];
	$imgtype_0=($imgtype=="gif")?"selected":"";
	$imgtype_1=($imgtype=="jpg")?"selected":"";
	$imgtype_2=($imgtype=="png")?"selected":"";

	$path = "../plugins/nfo";
	$string = <<<HTML
   <script language=JavaScript src="$path/picker.js"></script>
   <table border="0" cellpadding="2" cellspacing="1" style="margin:6px">
          <tr>
            <td class="$class[3]" align="right">背景颜色:</td>
            <td colspan=2><input type="text" name="bgcolor" size="15" class="textbox" value="$arr[bgcolor]"></td>
          </tr>
          <tr>
            <td class="$class[4]" align="right">文字颜色:</td>
            <td colspan=2><input type="text" name="txtcolor" size="15" class="textbox" value="$arr[txtcolor]"></td>
          </tr>
          <tr>
            <td class="$class[5]" align="right">转为图片类型:</td>
            <td colspan=2 style="padding-top:10px"><select name="imgtype" class="textbox">
				<option value="gif" $imgtype_0>gif</option>
                <option value="jpg" $imgtype_1>jpg</option>
                <option value="png" $imgtype_2>png</option>
              </select></td>
          </tr>
	</table>
HTML;

	return $string;
}

//Retun check field list
function nfo_fieldCheck() {
	global $nfo_fieldCheck;
	$arr=$nfo_fieldCheck;
	return $arr;
}

// save setting
function nfo_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	$fieldList=array("bgcolor","txtcolor","imgtype");
	for($i=0;$i<count($fieldList);$i++) {
		$name=$fieldList[$i];
		setPlugSet($modId,$name,$arr[$name]);
	}
	
	//Check file visit access
	$configFile="../plugins/nfo/nfo_config.php";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($configFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>nfo_config.php => Please change the CHMOD as 777.</font></b>";
	} else {
		$fp = @fopen($configFile, 'r');
		$filecontent = @fread($fp, @filesize($configFile));
		@fclose($fp);

		$filecontent = preg_replace("/[$]imgtype\s*\=\s*[\"'].*?[\"']/is", "\$imgtype = \"".$arr['imgtype']."\"", $filecontent);
		$filecontent = preg_replace("/[$]bgcolor\s*\=\s*[\"'].*?[\"']/is", "\$bgcolor = \"".$arr['bgcolor']."\"", $filecontent);
		$filecontent = preg_replace("/[$]txtcolor\s*\=\s*[\"'].*?[\"']/is", "\$txtcolor = \"".$arr['txtcolor']."\"", $filecontent);

		$fp = @fopen($configFile, 'wbt');
		@fwrite($fp, trim($filecontent));
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}

// 创建nfo to pic
function nfo($source){
	global $DMC, $DBPrefix;
	if(!empty($source) && file_exists($source)){
		$filext=substr($source,-3);
		if ($filext=="nfo") {
			include("../plugins/nfo/nfo_config.php");
			include("../plugins/nfo/class.NFOPiC.php");

			$pos=strrpos($source,"/");
			$savedir=substr($source,1,$pos);
			$savedir=F2BLOG_ROOT.str_replace("../","./",$savedir);
			$nfoname=substr($source,$pos+1,15);
			$pic_name=str_replace(".nfo",".".$imgtype,$nfoname);
			
			$nfopic = new NFOPiC($nfoname);
			$nfopic->setvar("server_save_dir",$savedir);
			$nfopic->setvar("nfo-dir",$savedir);
			$nfopic->setvar("fontcolor",$txtcolor);
			$nfopic->setvar("bgcolor",$bgcolor);
			$nfopic->setvar("pic_name",$pic_name);

			$nfopic->nfo2pic($imgtype);

			@unlink($source);

			//更新数据内容
			$targetname=str_replace("../attachments/","",$source);
			$att=getRecordValue($DBPrefix."attachments","name='$targetname'");
			$newname=str_replace(".nfo",".".$imgtype,strtolower($att['name']));
			$newtitle=str_replace(".nfo",".".$imgtype,strtolower($att['attTitle']));
			$gid=$att['id'];

			$imageAtt=@getimagesize($savedir.$pic_name);
			$fileWidth=$imageAtt[0];
			$fileHeight=$imageAtt[1];
			$fileSize=@filesize($savedir.$pic_name);

			$sql="update {$DBPrefix}attachments set name='$newname',attTitle='$newtitle',fileType='$imgtype',fileSize='$fileSize',fileHeight='$fileHeight',fileWidth='$fileWidth' where id='$gid'";
			$DMC->query($sql);
		}
	}
}

add_filter("f2_attach",'nfo');

?>
<?php
function gd_version() {	
if (function_exists('gd_info')) {
		$GDArray = gd_info(); 
		if ($GDArray['GD Version']) {
			$gd_version_number = $GDArray['GD Version'];
		} else {
			$gd_version_number = "Off";
		}
		unset($GDArray);
	} else {
		$gd_version_number = "Off";
	}
	return $gd_version_number;
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">Yes</font><br />';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">No</font><br />';
		if(!$output) {
			return $text;
		}
		echo $text;
	}
}

function runquery($sql) {
	global $dbcharset, $table_prefix, $DMC, $tablenum,$strSuccess,$strCreateTable;

	$sql = str_replace("\r", "\n", str_replace('f2blog_', $table_prefix, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE `(.+?)` \((.+?) ENGINE=MyISAM;*/is", "\\1", $query);
				echo $strCreateTable .$name." ... <font color=\"#0000EE\">$strSuccess</font><br />";
				$DMC->query(createtable($query, $dbcharset));
				$tablenum++;
			} else {
				$DMC->query($query);
			}
		}
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

/********** 替换UBB-VAR字符 **********/
function replace_string($str_content){
	if (strpos($str_content,"var")>0){
		$string=str_replace("[var]","",$str_content);
		$string=str_replace("[/var]","",$string);
		global $$string;
		$return=$$string;
	}else{
		$return=$str_content;
	}
	return $return;
}


//阅读文件
function readfromfile($file_name) {
	if (file_exists($file_name)) {
		if (PHP_VERSION >= "4.3.0") {
			return file_get_contents($file_name);
		} else {
			$filenum=fopen($file_name,"rb");
			$sizeofit=filesize($file_name);
			if ($sizeofit<=0) return '';
			@flock($filenum,LOCK_EX);
			$file_data=fread($filenum, $sizeofit);
			fclose($filenum);
			return $file_data;
		}
	} else {
		return '';
	}
}

/********** 取出Skin信息 **********/
function getSkinInfo($skindir){
	global $settingInfo;

	$arrSkin=array();
	$xmlfile=F2BLOG_ROOT."./skins/$skindir/skin.xml";
	
	if (file_exists($xmlfile)){
		$arrSkin['preview']=(file_exists($wdir."Preview.jpg"))?"../skins/$skindir/Preview.jpg":"../images/skin.jpg";
		$defSkin=$settingInfo['defaultSkin'];
		$arrSkin['defSkin']=($skindir==$defSkin)?"selectskin":"unselectskin";

		include_once(F2BLOG_ROOT."./include/xmlparse.inc.php");
		$arrSkinList=xmlArray($xmlfile);

		//增加一个皮肤来源
		if (!empty($arrSkinList['SkinSource']) && strtolower($arrSkinList['SkinSource'])=="f2blog"){
			$arrSkin['SkinSource']="f2blog";
		}else{
			$arrSkin['SkinSource']="pjblog";
		}
		$arrSkin['SkinName']=!empty($arrSkinList['SkinName'])?encode($arrSkinList['SkinName']):"";
		$arrSkin['pubDate']=!empty($arrSkinList['pubDate'])?$arrSkinList['pubDate']:"";
		$arrSkin['SkinDesigner']=!empty($arrSkinList['SkinDesigner'])?encode($arrSkinList['SkinDesigner']):"";
		$arrSkin['DesignerURL']=!empty($arrSkinList['DesignerURL'])?$arrSkinList['DesignerURL']:"";
		$arrSkin['DesignerMail']=!empty($arrSkinList['DesignerMail'])?$arrSkinList['DesignerMail']:"";

		if (!empty($arrSkinList['Flash'][0])) {
			$arrSkin['UseFlash']=$arrSkinList['Flash'][0]['UseFlash'];
			$arrSkin['FlashPath']=$arrSkinList['Flash'][0]['FlashPath'];
			$arrSkin['FlashWidth']=$arrSkinList['Flash'][0]['FlashWidth'];
			$arrSkin['FlashHeight']=$arrSkinList['Flash'][0]['FlashHeight'];
			$arrSkin['FlashAlign']=$arrSkinList['Flash'][0]['FlashAlign'];
			$arrSkin['FlashTop']=$arrSkinList['Flash'][0]['FlashTop'];
			$arrSkin['FlashTransparent']=$arrSkinList['Flash'][0]['FlashTransparent'];
		} else {
			$arrSkin['UseFlash']="";
			$arrSkin['FlashPath']="";
			$arrSkin['FlashWidth']="";
			$arrSkin['FlashHeight']="";
			$arrSkin['FlashAlign']="";
			$arrSkin['FlashTop']="";
			$arrSkin['FlashTransparent']="";
		}
	}
	return $arrSkin;
}

/***********按一定长度截取字符串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
	if (function_exists('mb_substr')) {
		$more = (mb_strlen($text) > $limit) ? TRUE : FALSE;
		$text = mb_substr($text, 0, $limit, 'UTF-8');
		return $text;
	} elseif (function_exists('iconv_substr')) {
		$more = (iconv_strlen($text) > $limit) ? TRUE : FALSE;
		$text = iconv_substr($text, 0, $limit, 'UTF-8');
		//return array($text, $more);
		return $text;
	} else {
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
		if(func_num_args() >= 3) {   
			if (count($ar[0])>$limit) {
				$more = TRUE;
				$text = join("",array_slice($ar[0],0,$limit)); 
			} else {
				$more = FALSE;
				$text = join("",array_slice($ar[0],0,$limit)); 
			}
		} else {
			$more = FALSE;
			$text =  join("",array_slice($ar[0],0)); 
		}
		return $text;
	} 
}

/********** 格式化字符串 **********/
function encode($string) {
	$string=trim($string);
	$string=str_replace("&","&amp;",$string);
	$string=str_replace("'","&#39;",$string);
	$string=str_replace("&amp;amp;","&amp;",$string);
	$string=str_replace("&amp;quot;","&quot;",$string);
	$string=str_replace("\"","&quot;",$string);
	$string=str_replace("&amp;lt;","&lt;",$string);
	$string=str_replace("<","&lt;",$string);
	$string=str_replace("&amp;gt;","&gt;",$string);
	$string=str_replace(">","&gt;",$string);
	$string=str_replace("&amp;nbsp;","&nbsp;",$string);

	$string=nl2br($string);
	return $string;
}

/********** 检查邮件 **********/
function check_email ($email){
	if ($email!=""){
		if (ereg ("^.+@.+\\..+$",$email)){
			return 1;
		} else {
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查用户名 **********/
function check_user ($username){
	if ($username==""){
		return 0;
	}else{
		if (preg_match("/[\s\'\"\\\]+/is",$username)){
			return 0;
		}elseif (strlen(str_replace("/[^\x00-\xff]/g", "**",$username))<3){		
			return 0;
		}else{
			return 1;
		}
	}
}

/********** 检查昵称 **********/
function check_nickname ($username){
	if ($username==""){
		return 0;
	}else{
		if (preg_match("/[\'\"\\\]+/is",$username)){
			return 0;
		}elseif (strlen(str_replace("/[^\x00-\xff]/g", "**",$username))<3){		
			return 0;
		}else{
			return 1;
		}
	}
}

/********** 检查密码 **********/
function check_password ($password){
	if ($password==""){
		return 0;
	}else{
		if (preg_match("/[\'\"\\\]+/",$password) || strlen($password)<5){
			return 0;
		}else {
			return 1;
		}
	}
}
?>
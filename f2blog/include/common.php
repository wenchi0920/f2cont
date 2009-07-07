<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();

/*@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Pragma: no-cache");*/

/********** 禁止直接访问该页面 **********/
if (basename($_SERVER['PHP_SELF']) == "common.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

//网志常量
$abspath=str_replace("include","",dirname(__FILE__));
define('ABSPATH', $abspath);
define("blogVersion","1.0 build 08.01");
define("blogUpdateDate","2006-08-01");
define("blogCopyright","CopyRight 2006 F2Blog.com All Rights Reserved.");
define("blogKeywords","f2blog,phpblog,blog,php,asp,designing,with,web,standards,xhtml,css,graphic,design,layout,usability,ccessibility,w3c,w3,w3cn,PuterJam,Harry,Korsen,Joesen,Terry,天上的骆驼,黑鹰之梦,一只耳,天上的駱駝,黑鷹之夢,一隻耳");

$f2_filter="";

/********** 程序开始运行时间 **********/
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];

/********** 设定程序的默认提交时间为格林时间 **********/
//date_default_timezone_set('UTC');

/********** 检查邮件 **********/
function check_email ($email){
	if ($email!=""){
		if (ereg ("^.+@.+\\..+$",$email)){
			return 1;
		}
		else {
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查数字 **********/
function check_number ($number){
	if ($number!=""){
		if (ereg ("^[0-9]+$",$number) || ereg ("^[0-9]+.[0-9]+$",$number)){
			return 1;
		}
		else {
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查用户名 **********/
function check_user ($username){
	if ($username!=""){
		if (ereg ("^[a-zA-Z0-9_]+$",$username)){
			return 1;
		}
		else {
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查密码 **********/
function check_password ($password){
	if ($password!=""){
		if (ereg("^.+$",$password) && strlen($password)>4){
			return 1;
		}
		else {
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查日期 **********/
function check_date($date){
	if ($date!=""){
		if (ereg("^[0-9]{4}-[0-1]?[0-9]-[0-3]?[0-9]$",$date)){
			$year=substr($date,0,strpos($date,"-"));
			$date=substr($date,strpos($date,"-")+1);
			$month=substr($date,0,strpos($date,"-"));
			$day=substr($date,strpos($date,"-")+1);
			if (checkdate($month,$day,$year)){
				return 1;
			} else {
				return 0;
			}
			
		}else{
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 检查邮件 **********/
function check_time($time){
	if ($time!=""){
		if (ereg("^[0-2]?[0-9]:[0-5]?[0-9]$",$time)){
			$hour=intval(substr($time,0,strpos($time,":")));
			$minute=intval(substr($time,strpos($time,":")+1));
			if ($hour>=0 && $hour<24 && $minute>=0 && $minute<60){
				return 1;
			} else {
				return 0;
			}	
		}else{
			return 0;
		}
	} else{
		return 1;
	}
}

/********** 获取客户端IP **********/
function getip() {
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$realip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

/********** 返回GD函数版本号 **********/
function gd_version() {	
	if (function_exists('gd_info')) {
		$GDArray = gd_info(); 
		if ($GDArray['GD Version']) {
			$gd_version_number = $GDArray['GD Version'];
		} else {
			$gd_version_number = 0;
		}
		unset($GDArray);
	} else {
		$gd_version_number = 0;
	}
	return $gd_version_number;
}

/********** 去除转义字符 **********/
function stripslashes_array(&$array) {
	while(@list($key,$var) = @each($array)) {
		if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
			if (is_string($var)) {
				$array[$key] = stripslashes($var);
			}
			if (is_array($var))  {
				$array[$key] = stripslashes_array($var);
			}
		}
	}
	return $array;
}

/********** 格式化时间 $type=L为长格式，否则为短格式 **********/
function format_time($type,$timestamp = ''){
	global $settingInfo;
	if ($timestamp!=""){
		$offset = $settingInfo['timezone'];
		$timeSystemFormat=$settingInfo['timeSystemFormat'];

		switch($type){
			case "L":
				$format=$timeSystemFormat;
				break;
			case "S":
				$format=substr($timeSystemFormat,0,5);
				break;
			case "F":
				$format="Y-m-d H:i";
				break;
			default:
				$format=$type;
				break;
		}
		//echo gmdate($format,$timestamp)."<br>";

		$return=gmdate($format,$timestamp+$offset*3600);
	}else{
		$return="";
	}
	return $return;
}

/********** 字符串转时间 **********/
function str_format_time($timestamp = ''){
	global $settingInfo;
	
	if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}/i", $timestamp)) {
		list($date,$time)=explode(" ",$timestamp);
		list($year,$month,$day)=explode("-",$date);
		list($hour,$minute)=explode(":",$time);
		//echo "$year,$month,$day,$hour,$minute<br>";
		$timestamp=gmmktime($hour,$minute,0,$month,$day,$year);
		
		if(PHP_VERSION>4){
			$offset = $settingInfo['timezone'];
			$timestamp=$timestamp-$offset*3600;
		}
	}else{
		$timestamp=time();
	}

	return $timestamp;
}


/********** 替换UBB-VAR字符 **********/
function replace_string($str_content){
	global $settingInfo;
	$curLanguage=$settingInfo['language'];
	require("language/".$curLanguage.".php");

	$arr_content=split("\[var\]|\[/var\]",$str_content);
	if (count($arr_content)<3) {
		$return=$str_content;
	} else {
		for ($index=0;$index<count($arr_content);$index++){
			$test=$arr_content[$index];
			//$test=substr($test,1);
			$test=$$test;
			$return.=$test;
		}
	}
	return $return;
}

/***********按一定长度截取字符串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
	if (function_exists('mb_substr')) {
		$more = (mb_strlen($text) > $limit) ? TRUE : FALSE;
		$text = mb_substr($text, 0, $limit, 'UTF-8');
		//return array($text, $more);
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
		//return array($text, $more);
		return $text;
	} 
}

/********** 取出Skin信息 **********/
function getSkinInfo($skindir,$basedir){
	global $settingInfo;

	$arrSkin="";
	$wdir="$basedir/skins/".$skindir."/";
	$xmlfile=$wdir."skin.xml";
	
	if (file_exists($xmlfile)){
		$arrSkin['preview']=(file_exists($wdir."Preview.jpg"))?$wdir."Preview.jpg":"$basedir/images/skin.jpg";
		$defSkin=$settingInfo['defaultSkin'];
		$arrSkin['defSkin']=($skindir==$defSkin)?"selectskin":"unselectskin";

		if (function_exists(simplexml_load_file)){
			$xml = simplexml_load_file($xmlfile);
			$arrSkin['SkinName']=$xml->SkinName;
			$arrSkin['pubDate']=$xml->pubDate;
			$arrSkin['SkinDesigner']=$xml->SkinDesigner;
			$arrSkin['DesignerURL']=$xml->DesignerURL;
			$arrSkin['DesignerMail']=$xml->DesignerMail;
			$arrSkin['UseFlash']=$xml->Flash[0]->UseFlash;
			$arrSkin['FlashPath']=$xml->Flash[0]->FlashPath;
			$arrSkin['FlashWidth']=$xml->Flash[0]->FlashWidth;
			$arrSkin['FlashHeight']=$xml->Flash[0]->FlashHeight;
			$arrSkin['FlashAlign']=$xml->Flash[0]->FlashAlign;
			$arrSkin['FlashTop']=$xml->Flash[0]->FlashTop;
			$arrSkin['FlashTransparent']=$xml->Flash[0]->FlashTransparent;
		}else{
			//including khalid xml parser
			include_once "kxparse.php";
			//create the object
			$xmlnav=new kxparse($xmlfile);

			$arrSkin['SkinName']=$xmlnav->get_tag_text("SkinSet:SkinName","1:1");
			$arrSkin['SkinDesigner']=$xmlnav->get_tag_text("SkinSet:SkinDesigner","1:1");
			$arrSkin['pubDate']=$xmlnav->get_tag_text("SkinSet:pubDate","1:1");
			$arrSkin['DesignerURL']=$xmlnav->get_tag_text("SkinSet:DesignerURL","1:1");
			$arrSkin['DesignerMail']=$xmlnav->get_tag_text("SkinSet:DesignerMail","1:1");
			$arrSkin['UseFlash']=$xmlnav->get_tag_text("SkinSet:Flash:UseFlash","1:1:1");
			$arrSkin['FlashPath']=$xmlnav->get_tag_text("SkinSet:Flash:FlashPath","1:1:1");
			$arrSkin['FlashWidth']=$xmlnav->get_tag_text("SkinSet:Flash:FlashWidth","1:1:1");
			$arrSkin['FlashHeight']=$xmlnav->get_tag_text("SkinSet:Flash:FlashHeight","1:1:1");
			$arrSkin['FlashAlign']=$xmlnav->get_tag_text("SkinSet:Flash:FlashAlign","1:1:1");
			$arrSkin['FlashTop']=$xmlnav->get_tag_text("SkinSet:Flash:FlashTop","1:1:1");
			$arrSkin['FlashTransparent']=$xmlnav->get_tag_text("SkinSet:Flash:FlashTransparent","1:1:1");
		}
	}
	return $arrSkin;
}

/********** 格式化字符串 **********/
function encode($string) {
	$string=trim($string);
	$string=stripSlashes("$string");

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

/********** 反格式化字符串针对TINYMCE**********/
function TinyMCE_dencode($string) {
	$string=str_replace("<br>","\n",$string);
	$string=str_replace("&amp;","&",$string);
	$string=str_replace("&lt;","<",$string);
	$string=str_replace("&gt;",">",$string);
	$string=str_replace("&quot;","\"",$string);

	$string=stripslashes($string);
	return $string;
}

/********** 反格式化字符串 **********/
function dencode($string) {
	//替换File
	$string=str_replace("&amp;","&",$string);
	$string=str_replace("&lt;","<",$string);
	$string=str_replace("&gt;",">",$string);
	$string=str_replace("&#39;","'",$string);
	$string=str_replace("&quot;","\"",$string);	
	$string=stripslashes($string);

	return $string;
}

function ubb($Text) {
  //$Text=htmlspecialchars($Text); 
  $Text=ereg_replace("\r\n","<br>",$Text); 
  $Text=ereg_replace("\r","<br>",$Text); 
  $Text=ereg_replace("&lt;br /&gt;","",$Text); 
  $Text=ereg_replace("<br />","\n",$Text); 
  //$Text=nl2br($Text); 
  $Text=preg_replace("/\\t/is","  ",$Text); 
  $Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text); 
  $Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text); 
  $Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text); 
  $Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text); 
  $Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text); 
  $Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text); 

  $Text=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<a href=\\1>\\1</a>",$Text); 
  $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"http://\\1\">http://\\1</a>",$Text); 
  $Text=preg_replace("/\[url=(http:\/\/.+?)\](.*)\[\/url\]/is","<a href=\\1>\\2</a>",$Text); 
  $Text=preg_replace("/\[url=(.+?)\](.*)\[\/url\]/is","<a href=http://\\1>\\2</a>",$Text); 

  $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text); 
  $Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text); 
  $Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text); 
  $Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text); 
  $Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text); 
  $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text); 
  $Text=preg_replace("/\[email\]([^\[]*)\[\/email\]/is","<a href=\"mailto: \\1\">\\1</a>",$Text); 
  $Text=preg_replace("/\[email=([^\[]*)\](.*)\[\/email\]/is","<a href=\"mailto: \\1\">\\1</a>",$Text); 
  $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text); 
  $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text); 
  $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text); 
  $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote><font size='1' face='Courier New'>quote:</font><hr>\\1<hr></blockquote>", $Text); 
  $Text=preg_replace("/\[code\](.+?)\[\/code\]/is","<blockquote><font size='1' face='Times New Roman'>code:</font><hr color='lightblue'><i>\\1</i><hr color='lightblue'></blockquote>", $Text); 
  $Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div style='text-align: left; color: darkgreen; margin-left: 5%'><br><br>--------------------------<br>\\1<br>--------------------------</div>", $Text); 

  //UBBCode
  $Text=do_filter("f2_ubbcode",$Text);

  return $Text; 
}

//Plugins function
function add_action($tag,$function, $accepted_args = 1) {
	global $f2_filter;

	add_filter($tag, $function, $accepted_args);
}

function add_filter($tag, $function, $accepted_args = 1) {
	global $f2_filter;

	if (isset($f2_filter[$tag])) {
		foreach($f2_filter[$tag] as $filter) {
			if ($filter['function'] == $function) {
				return true;
			}
		}
	}

	$f2_filter[$tag][] = array('function'=>$function, 'accepted_args'=>$accepted_args);
	return true;
}

function do_action($tag, $arg = '') {
	global $f2_filter;

	$extra_args = array_slice(func_get_args(), 2);
 	if ( is_array($arg) )
 		$args = array_merge($arg, $extra_args);
	else
		$args = array_merge(array($arg), $extra_args);

	if ( !isset($f2_filter[$tag]) ) {
		return;
	}
	
	foreach ($f2_filter[$tag] as $function) {
		$function_name = $function['function'];
		$accepted_args = $function['accepted_args'];

		if ( $accepted_args == 1 )
			$the_args = array($string);
		elseif ( $accepted_args > 1 )
			$the_args = array_slice($all_args, 0, $accepted_args);
		elseif ( $accepted_args == 0 )
			$the_args = NULL;
		else
			$the_args = $all_args;
		
		$string = call_user_func_array($function_name, $the_args);
	}
}

function do_filter($tag,$string) {
	global $f2_filter;

	$args = array_slice(func_get_args(), 2);

	if ( !isset($f2_filter[$tag]) ) {
		return $string;
	}
	
	foreach ($f2_filter[$tag] as $function) {
		$all_args = array_merge(array($string), $args);
		$function_name = $function['function'];
		$accepted_args = $function['accepted_args'];

		if ( $accepted_args == 1 )
			$the_args = array($string);
		elseif ( $accepted_args > 1 )
			$the_args = array_slice($all_args, 0, $accepted_args);
		elseif ( $accepted_args == 0 )
			$the_args = NULL;
		else
			$the_args = $all_args;
		
		$string = call_user_func_array($function_name, $the_args);
	}

	return $string;
}

function validCode($length){
	global $_SESSION;
    $str = ''; 
    for ($i = 0; $i < $length; $i++) { 
        $rand = rand(0,9); 
		$str .= $rand; 
    }
	$_SESSION['validate']=$str;
	return $str;
}
?>
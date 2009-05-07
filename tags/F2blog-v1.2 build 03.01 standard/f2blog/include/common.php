<?php 
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
header("Content-Type: text/html; charset=utf-8");

//程序开始运行时间
$mtime = explode(' ', microtime());
$starttime = $mtime[0] + $mtime[1];

//网志常量
define('IN_F2BLOG', TRUE);
define('F2BLOG_ROOT', substr(dirname(__FILE__), 0, -7));
define("blogVersion","1.2 build 03.01");
define("blogUpdateDate","2007-03-01");
define("blogCopyright","CopyRight 2006 F2Blog.com All Rights Reserved.");
define("blogKeywords","f2blog,phpblog,blog,php,asp,designing,with,web,standards,xhtml,css,graphic,design,layout,usability,ccessibility,w3c,w3,w3cn,PuterJam,Harry,Korsen,Joesen,Terry,天上的骆驼,蚁鹰,一只耳,天上的駱駝,蚁鹰,一隻耳");
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Pragma: no-cache");

if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}

$magic_quotes_gpc = get_magic_quotes_gpc();
extract(daddslashes($_COOKIE));
extract(daddslashes($_POST));
extract(daddslashes($_GET));
if(!$magic_quotes_gpc) {
	$_FILES = daddslashes($_FILES);
}


//对任何内容进行安全处理
$_GET=safe_convert($_GET);	
//$_COOKIE=safe_convert($_COOKIE);	
$_SERVER=safe_convert($_SERVER);
$_SERVER['PHP_SELF'] = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$PHP_SELF=$_SERVER['PHP_SELF'];

include_once(F2BLOG_ROOT."./include/global.inc.php");
include_once(F2BLOG_ROOT."./include/config.php");
include_once(F2BLOG_ROOT."./include/db.php");

//开始Session
if ($sessionpath!="") session_save_path($sessionpath);
session_cache_limiter("private, must-revalidate");
session_start();

//如果没有设定config.php文件，则安装。
if ($DBUser=="" || $DBPass=="" || $DBName==""){	
	if (file_exists(F2BLOG_ROOT."./install/install.php")) {
		if (strpos($_SERVER['PHP_SELF'],"/admin/")>0){
			header("Location: ../install/install.php");
		}else{
			header("Location: ./install/install.php");
		}
	}else{
		header("Content-Type: text/html; charset=utf-8");
		die ("F2blog isn't install success, the reason is: <br>1. Install file 'install/install.php' isn't exists. <br>2. Your mysql setting file 'include/config.php' isn't connect database!<br /><br>Please check up the reason.");
	}
}

//Connect Database
$DMC = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName,$DBNewlink);

//寻找update.php文件，如果找到就自动更新，更新完后删除该文件。
if (file_exists(F2BLOG_ROOT."./update.php")){
	include(F2BLOG_ROOT."./update.php");
	
	//删除update.php，不能删除将提示管理员删除。
	if (!@unlink(F2BLOG_ROOT."./update.php")){
		if ($_SESSION[rights]=="admin"){
			$ActionMessage="please delete update.php";
		}
	}
}

//调用配置文件，如果不存在，则建立它。
if (file_exists(F2BLOG_ROOT."./cache/cache_setting.php")){
	include_once(F2BLOG_ROOT."./cache/cache_setting.php");
	include_once(F2BLOG_ROOT."./cache/cache_modules.php");
}else{//重新建立Cache
	include_once(F2BLOG_ROOT."./include/cache.php");
	settings_recount();
	settings_recache();	
	include_once(F2BLOG_ROOT."./cache/cache_setting.php");
	include_once(F2BLOG_ROOT."./include/language/home/".basename($settingInfo['language']).".php");
	$settingInfo['stype'] = ($settingInfo['rewrite']>0) ? ".html" : "";
	modules_recache();
	include_once(F2BLOG_ROOT."./cache/cache_modules.php");	

	reAllCache();

	header("Location:".$_SERVER['PHP_SELF']);
}

//如果安装文件存在，则不能使用blog
if (file_exists(F2BLOG_ROOT."./install/install.php")) {
	header("Content-Type: text/html; charset=utf-8");
	die("WARN: Please DELETE or RENAME the installation file, install/install.php!");
}

//以下为插件机制
$f2_filter="";
foreach($arrPluginName as $key=>$value){
	$value=basename($value);
	$plugins_include=F2BLOG_ROOT."./plugins/$value/$value.php";
	if (file_exists($plugins_include)){
		include_once($plugins_include);
	}else{
		//插件不存在，则删除记录。
		if ($arr_result=$DMC->fetchArray($DMC->query("select id from ".$DBPrefix."modules where name='$value' and installDate>0"))){
			$DMC->query("delete from ".$DBPrefix."modsetting where modId='".$arr_result['id']."'");	
			$DMC->query("delete from ".$DBPrefix."modules where name='$value' and installDate>0");
			include_once(F2BLOG_ROOT."./include/cache.php");
			modules_recache();
			modulesSetting_recache();
		}
	}
}

//$settingInfo[stype]表示网址的扩展名,.html表示启用了静态连接
$settingInfo['stype'] = ($settingInfo['rewrite']>0) ? ".html" : "";

//默认消息为空
$ActionMessage="";

//取得用户cookie值
if (empty($_SESSION['prelink']) && !empty($_COOKIE['username']) && !empty($_COOKIE['password']) && !empty($_COOKIE['rights'])){
	$_COOKIE['password']=encode(urldecode($_COOKIE['password']));
	$_COOKIE['username']=encode($_COOKIE['username']);
	if (strpos(";".$_COOKIE['password'],"####")>0 && strlen($_COOKIE['password'])>4){
		$_COOKIE['password']=substr($_COOKIE['password'],4);
		if ($dataUser=$DMC->fetchArray($DMC->query("SELECT * FROM ".$DBPrefix."members WHERE username='".$_COOKIE['username']."' and hashKey='".md5($_COOKIE['password'])."'"))){
			$_SESSION['username']=$dataUser['username'];
			$_SESSION['password']=$dataUser['password'];
			$_SESSION['rights']=$dataUser['role'];
		}
	}
}

/********** 检查邮件 **********/
function check_email ($email){
	if ($email!=""){
		if (preg_match("/^.+@.+\\..+$/i",$email)){
			if (preg_match("/<|>|'|\"/i",$email)){
				return 0;
			}else{
				return 1;
			}
		} else {
			return 0;
		}
	} else{
		return 0;
	}
}

/********** 检查用户名 **********/
function check_user ($username){
	if ($username==""){
		return 0;
	}else{
		if (preg_match("/[\s\'\"<>\\\]+/is",$username)){
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
		if (preg_match("/[\'\"<>\\\]+/is",$username)){
			return 0;
		}elseif (strlen(str_replace("/[^\x00-\xff]/g", "**",$username))<3){		
			return 0;
		}else{
			return 1;
		}
	}
}

/********** 检查文件名 **********/
function check_fileName ($filename){
	if ($filename==""){
		return 0;
	}else{
		if (preg_match("/[\'\"<>\\\]+/is",$filename)){
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

/********** 检查时间 **********/
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
	global $_SERVER;
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	return $onlineipmatches[0] ? $onlineipmatches[0] : '';
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
	while(list($key,$var) = each($array)) {
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
		$return=gmdate($format,$timestamp+$offset*3600);
	}else{
		$return="";
	}
	return $return;
}

/********** 字符串转时间 **********/
function str_format_time($timestamp = ''){
	global $settingInfo;
	
	if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}/i", $timestamp)) {
		list($date,$time)=explode(" ",$timestamp);
		list($year,$month,$day)=explode("-",$date);
		list($hour,$minute)=explode(":",$time);
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

/***********按一定长度截取字符串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
	if (function_exists('mb_substr')) {
		$more = (mb_strlen($text,'UTF-8') > $limit) ? TRUE : FALSE;
		$text = mb_substr($text, 0, $limit, 'UTF-8');
		return $text;
	} elseif (function_exists('iconv_substr')) {
		$more = (iconv_strlen($text,'UTF-8') > $limit) ? TRUE : FALSE;
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

function htmlSubString($content,$maxlen=300){
	//把字符按HTML标签变成数组。
	$content = preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
	$wordrows=0;	//中英字数
	$outstr="";		//生成的字串
	$wordend=false;	//是否符合最大的长度
	$beginTags=0;	//除<img><br><hr>这些短标签外，其它计算开始标签，如<div*>
	$endTags=0;		//计算结尾标签，如</div>，如果$beginTags==$endTags表示标签数目相对称，可以退出循环。
	//print_r($content);
	foreach($content as $value){
		if (trim($value)=="") continue;	//如果该值为空，则继续下一个值

		if (strpos(";$value","<")>0){
			//如果与要载取的标签相同，则到处结束截取。
			if (trim($value)==$maxlen) {
				$wordend=true;
				continue;
			}

			if ($wordend==false){
				$outstr.=$value;
				if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!--([^>]+?)-->/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
					$beginTags++; //除img,br,hr外的标签都加1
				}
			}else if (preg_match("/<\/([^>]+?)>/is",$value,$matches)){
				$endTags++;
				$outstr.=$value;
				if ($beginTags==$endTags && $wordend==true) break;	//字已载完了，并且标签数相称，就可以退出循环。
			}else{
				if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!--([^>]+?)-->/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
					$beginTags++; //除img,br,hr外的标签都加1
					$outstr.=$value;
				}
			}
		}else{
			if (is_numeric($maxlen)){	//截取字数
				$curLength=getStringLength($value);
				$maxLength=$curLength+$wordrows;
				if ($wordend==false){
					if ($maxLength>$maxlen){	//总字数大于要截取的字数，要在该行要截取
						$outstr.=subString($value,0,$maxlen-$wordrows);
						$wordend=true;
					}else{
						$wordrows=$maxLength;
						$outstr.=$value;
					}
				}
			}else{
				if ($wordend==false) $outstr.=$value;
			}
		}
	}
	//循环替换掉多余的标签，如<p></p>这一类
	while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)){
		$outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
	}
	//把误换的标签换回来
	if (strpos(";".$outstr,"[html_")>0){
		$outstr=str_replace("[html_&lt;]","<",$outstr);
		$outstr=str_replace("[html_&gt;]",">",$outstr);
	}

	//echo htmlspecialchars($outstr);
	return $outstr;
}

//去掉多余的空标签
function strip_empty_html($matches){
	$arr_tags1=explode(" ",$matches[1]);
	if ($arr_tags1[0]==$matches[2]){	//如果前后标签相同，则替换为空。
		return "";
	}else{
		$matches[0]=str_replace("<","[html_&lt;]",$matches[0]);
		$matches[0]=str_replace(">","[html_&gt;]",$matches[0]);
		return $matches[0];
	}
}

//取得字符串的长度，包括中英文。
function getStringLength($text){
	if (function_exists('mb_substr')) {
		$length=mb_strlen($text,'UTF-8');
	} elseif (function_exists('iconv_substr')) {
		$length=iconv_strlen($text,'UTF-8');
	} else {
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
		$length=count($ar[0]);

	}
	return $length;
}

/********** 取出Skin信息 **********/
function getSkinInfo($skindir){
	global $settingInfo;

	$arrSkin="";
	$wdir=F2BLOG_ROOT."./skins/$skindir/";
	$xmlfile=$wdir."skin.xml";
	
	if (file_exists($xmlfile)){
		$arrSkin['preview']=(file_exists($wdir."Preview.jpg"))?"../skins/$skindir/Preview.jpg":"../images/skin.jpg";
		$defSkin=$settingInfo['defaultSkin'];
		$arrSkin['defSkin']=($skindir==$defSkin)?"selectskin":"unselectskin";

		include_once("xmlparse.inc.php");
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

/********** 格式化字符串 **********/
function ubblogencode($string) {
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
	$string=str_replace("&lt;!--","<!--",$string);
	$string=str_replace("--&gt;","-->",$string);
	
	return $string;
}

/********** 反格式化字符串 **********/
function dencode($string) {
	$string=str_replace("&amp;","&",$string);
	$string=str_replace("&lt;","<",$string);
	$string=str_replace("&gt;",">",$string);
	$string=str_replace("&#39;","'",$string);
	$string=str_replace("&quot;","\"",$string);	

	return $string;
}

function htmldecode($string){
	$string=dencode($string);				
	$string=str_replace("\n","",$string);
	$string=str_replace("\r","",$string);
	$string=str_replace("<br />","\n",$string);
	return $string;
}

function ubb($Text) {
	$Text = preg_replace("/\[code\](.+?)\[\/code\]/eis","ubbcode('\\1')", $Text);
	$Text = preg_replace("/\[quote\](.+?)\[\/quote\]/eis","ubbqoute('\\1')",$Text);

	$searcharray = array(
		"/\[u\](.+?)\[\/u]/is",
		"/\[i\](.+?)\[\/i]/is",
		"/\[b\](.+?)\[\/b]/is",
		"/\[list\](.+?)\[\/list]/is",
		"/\[\*\]/is",
		"/\[list=([aA1]?)\](.+?)\[\/list\]/is",
		"/\[font=([^\[]*)\](.+?)\[\/font\]/is",
		"/\[color=([#0-9a-z]{1,10})\](.+?)\[\/color\]/is",
		"/\[email=([^\[]*)\]([^\[]*)\[\/email\]/is",
	    "/\[email\]([^\[]*)\[\/email\]/is",
		"/\[size=(\d+)\](.+?)\[\/size\]/is",
		"/(\[align=)(left|center|right)(\])(.+?)(\[\/align\])/is",
		"/\[glow=(\d+)\,([0-9a-zA-Z]+?)\,(\d+)\](.+?)\[\/glow\]/is"
	);
	$replacearray = array(
		"<u>\\1</u>",
		"<i>\\1</i>",
		"<b>\\1</b>",
		"<ul>\\1</ul>",
		"<li>",
		"<ol type=\\1>\\2</ol>",
		"<font face='\\1'>\\2</font>",
		"<font color='\\1'>\\2</font>",
		"<a href='mailto:\\1'>\\2</a>",
		"<a href='mailto:\\1'>\\1</a>",
		"<font size='\\1'>\\2</font>",
		"<div align=\\2>\\4</div>",
		"<span style='width:\\1;filter:glow(color=\\2,strength=\\3)'>\\4</span>"
	);
	$Text = preg_replace($searcharray,$replacearray, $Text);

	$Text = preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>", $Text);
	$Text = preg_replace("/\[img width=(\d+),height=(\d+)\](.+?)\[\/img\]/ie","ubbimage('\\3','\\1','\\2')", $Text);

	if(strpos( $Text,'[/URL]')!==false || strpos( $Text,'[/url]')!==false){
		$searcharray = array(
			"/\[url=(https?|ftp|gopher|news|telnet|mms|rtsp)([^\[]*)\](.+?)\[\/url\]/eis",			
			"/\[url\]www\.([^\[]*)\[\/url\]/eis",
			"/\[url\](https?|ftp|gopher|news|telnet|mms|rtsp)([^\[]*)\[\/url\]/eis"
		);
		$replacearray = array(
			"cvurl('\\1','\\2','\\3')",
			"cvurl('\\1')",
			"cvurl('\\1','\\2')",
		);
		 $Text=preg_replace($searcharray,$replacearray, $Text);
	}

	$searcharray = array(
		"/\[fly\]([^\[]*)\[\/fly\]/is",
		"/\[move\]([^\[]*)\[\/move\]/is",
	);
	$replacearray = array(
		"<marquee width=90% behavior=alternate scrollamount=3>\\1</marquee>",
		"<marquee scrollamount=3>\\1</marquee>",
	);
	$Text=preg_replace($searcharray,$replacearray, $Text);

	$Text = preg_replace("/\[wmv\](.+?)\[\/wmv\]/is","<embed src=\\1 height=\"256\" width=\"314\" autostart=1></embed>", $Text);
	$Text = preg_replace("/\[rm\](.+?)\[\/rm\]/is","<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=241 id=Player width=316 VIEWASTEXT><param name=\"_ExtentX\" value=\"12726\"><param name=\"_ExtentY\" value=\"8520\"><param name=\"autostart\" value=\"0\"><param name=\"SHUFFLE\" value=\"0\"><param name=\"PREFETCH\" value=\"0\"><param name=\"NOLABELS\" value=\"0\"><param name=\"CONTROLS\" value=\"ImageWindow\"><param name=\"CONSOLE\" value=\"_master\"><param name=\"LOOP\" value=\"0\"><param name=\"NUMLOOP\" value=\"0\"><param name=\"center\" value=\"0\"><param name=\"MAINTAINASPECT\" value=\"\\1\"><param name=\"BACKGROUNDCOLOR\" value=\"#000000\"></object><br /><object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height=32 id=Player2 width=316 VIEWASTEXT><param name=\"_ExtentX\" value=\"18256\"><param name=\"_ExtentY\" value=\"794\"><param name=\"autostart\" value=\"1\"><param name=\"SHUFFLE\" value=\"0\"><param name=\"PREFETCH\" value=\"0\"><param name=\"NOLABELS\" value=\"0\"><param name=\"CONTROLS\" value=\"controlpanel\"><param name=\"CONSOLE\" value=\"_master\"><param name=\"LOOP\" value=\"0\"><param name=\"NUMLOOP\" value=\"0\"><param name=\"center\" value=\"0\"><param name=\"MAINTAINASPECT\" value=\"0\"><param name=\"BACKGROUNDCOLOR\" value=\"#000000\"><param name=\"SRC\" value=\"\\1\"></object>", $Text);
	$Text = preg_replace("/(\[flash=)(\d+?)(\,)(\d+?)(\])(.+?)(\[\/flash\])/is","<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\\2 height=\\4><PARAM NAME=MOVIE VALUE=\\6><PARAM NAME=PLAY VALUE=TRUE><PARAM NAME=LOOP VALUE=TRUE><PARAM NAME=QUALITY VALUE=HIGH></OBJECT><br />[<a target=_blank href=\\6>Full Screen</a>] ", $Text);

  //UBBCode
  $Text=do_filter("f2_ubbcode",$Text);
  $Text=preg_replace("/\\t/is","&nbsp;&nbsp;&nbsp;&nbsp;",$Text);

  return $Text; 
}

function ubbimage($image,$width,$height){
	$image1=str_replace("_f2s","",$image);
	return "<img src=\"".$image."\" style=\"cursor:pointer;\" onclick=\"open_img(&#39;".$image1."&#39;)\" width=\"$width\" height=\"$height\" alt=\"\" />";
}

function ubbqoute($quote){
	global $strLogTB;
	$returnquote="<div class=\"UBBPanel\">";
	$returnquote.="<div class=\"UBBTitle\"><img src=\"images/From.gif\" style=\"margin:0px 2px -3px 0px;\" border=\"0\" alt=\"\"/> $strLogTB:</div>";
	$returnquote.="<div class=\"UBBContent\">";
	$returnquote.="$quote";
	$returnquote.="</div></div>";
	return $returnquote;
}

function ubbcode($code){
	$default_id="";
	for ($index=0;$index<4;$index++){
		$default_id.=rand(0,9);
	}
	$code = str_replace("&nbsp;", "\t", $code);
	$code = str_replace("&quot;", '"', $code);
	$code = str_replace("&#39;", "'", $code);
	$code = str_replace("&gt;", ">", $code);
	$code = str_replace("&lt;", "<", $code);
	$code = str_replace("&amp;", "&", $code);
	$code = preg_replace("/<br\s*\/?>/i", "\n", $code);
	
	ob_start();
	highlight_string($code);
	$code = ob_get_contents();
	ob_end_clean();
	
	$returncode="<div class=\"UBBPanel\">";
	$returncode.="<div class=\"UBBTitle\"><img src=\"images/code.gif\" style=\"margin:0px 2px -3px 0px;\" border=\"0\" alt=\"\"/> CODE:</div>";
	$returncode.="<div class=\"UBBContent\">";
	$returncode.="<div class=quote id='code".$default_id."'>$code</div><div style=\"font-size:11px;margin-left:5px\"><a href=\"javascript:\" onclick=\"CopyText(document.getElementById('code".$default_id."'));\">[Copy to clipboard]</a></div>";
	$returncode.="</div></div>";
	//echo $returnncode;

	$returncode=preg_replace("/\\n/is","",$returncode);
	$returncode=preg_replace("/\\r/is","",$returncode);
	return $returncode;
}

function cvurl($http,$url='',$name=''){
	if(!$url){
		$url="<a href='http://www.$http' target=_blank>www.$http</a>";
	} elseif(!$name){
		$url="<a href='$http$url' target=_blank>$http$url</a>";
	} else{
		$url="<a href='$http$url' target=_blank>$name</a>";
	}
	return $url;
}

//Plugins function
function add_action($tag,$function, $accepted_args = 1) {
	global $f2_filter;

	add_filter($tag, $function, $accepted_args);
}

function add_filter($tag, $function, $accepted_args = 1) {
	global $f2_filter;

	if (isset($f2_filter[$tag]) && is_array($f2_filter[$tag])) {
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
	if (!isset($string)) $string="";	//harry add 2006-12-30

	$extra_args = array_slice(func_get_args(), 2);
 	if ( is_array($arg) )
 		$args = array_merge($arg, $extra_args);
	else
		$args = array_merge(array($arg), $extra_args);

	if ( !isset($f2_filter[$tag]) || !is_array($f2_filter[$tag])) {
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
	$stringBackup=$string;

	if ( !isset($f2_filter[$tag]) || !is_array($f2_filter[$tag])) {
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
		$string = ($string=="")?$stringBackup:$string;
	}

	return $string;
}

function validCode($length){
    $str = ''; 
    for ($i = 0; $i < $length; $i++) { 
        $rand = rand(0,9); 
		$str .= $rand; 
    }
	return $str;
}

function daddslashes($string, $force = 0) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

// 生成静态页面
function writetohtml($htmlname, $htmldata = '') {
	list($path,$name)=explode("/",$htmlname);
	$htmldir=F2BLOG_ROOT."./cache/html/$path/";
	if(!is_dir($htmldir)) {
		mkdir($htmldir, 0777);
	}
	$htmlfile = $htmldir.$name.'.php';
	if($fp = fopen($htmlfile, 'wbt')) {
		fwrite($fp, "<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>\r\n".$htmldata);
		fclose($fp);
	} else {
		echo "Can not write to html files, please check directory $htmldir.";
		exit;
	}
}

/********** 取得$table表中符合条件$where的整条记录值 **********/
function getRecordValue($table,$where){
	global $DMC, $DBPrefix;
	if ($table!="" && $where!=""){
		$dataInfo = $DMC->fetchArray($DMC->query("select * from $table where $where"));
		if ($dataInfo) {
			$return=$dataInfo;
		}else{
			$return="";
		}
	}else{
		$return="";
	}
	return $return;
}

// 根据标签热门程度，来定颜色
function getTagHot($count,$max,$min){
	$dist=$max/3;
	if($count==$min)
		return "#999";
	elseif($count==$max)
		return "#f60";
	elseif($count>=$min+($dist*2))
		return "#069";
	elseif($count>=$min+$dist)
		return "#690";
	else
		return "#09c";
}

//运行时间显示
function showProcessedTime($debug=""){
	global $starttime;
	$mtime = explode(' ', microtime());
	$totaltime = number_format(($mtime[0] + $mtime[1] - $starttime), 6);
	echo "$debug Processed in <b>".$totaltime."</b> second(s)";
	$mtime = explode(' ', microtime());
	$starttime = $mtime[0] + $mtime[1];
}

//阅读文件
function readfromfile($file_name) {
	if (file_exists($file_name)) {
		if (PHP_VERSION >= "4.3.0" && function_exists('file_get_contents')) {
			return file_get_contents($file_name);
		} else {
			$filenum=fopen($file_name,"rb");
			$sizeofit=filesize($file_name);
			if ($sizeofit<=0) return '';
			flock($filenum,LOCK_EX);
			$file_data=fread($filenum, $sizeofit);
			fclose($filenum);
			return $file_data;
		}
	} else {
		return '';
	}
}

//输出警告信息
function print_message($ActionMessage){
	if (!empty($ActionMessage)){
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$ActionMessage\");";
		echo "</script>";
	}
}

//导航页面
function NavigatorNextURL($url,$content){
	$out=<<<HTML
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<title>$content</title>
	<meta http-equiv="Refresh" content="1;URL=$url" />
	</head>

	<body>
		<span style='font-size:14px;color:blue'>$content</span>
	</body>
	</html>
HTML;
	return $out;
}

function safe_convert($array){
	if (is_array($array)) {
		foreach($array as $key=>$value){
			if(!is_array($value)){
				$array[$key]=htmlspecialchars($value, ENT_QUOTES);
			}else{
				safe_convert($value);
			}
		}
	} else $array=htmlspecialchars($array, ENT_QUOTES);
	return $array;
}

function getNumRows($nums_sql){
	global $DMC;
	$arrNumRows=$DMC->fetchArray($DMC->query($nums_sql));
	return $arrNumRows['numRows'];
}

/********** 格式化文件大小 **********/
function formatFileSize($file_size){
	if ($file_size<=0 || $file_size==""){
		$file_size_view="0 Byte";
	}else{
		if ($file_size<1024){
			$file_size_view="$file_size Byte";
		}else if ($file_size<1048576){
			$file_size_view=round($file_size/1024,2);
			$file_size_view="$file_size_view KB";
		}else{
			$file_size_view=round($file_size/1048576,2);
			$file_size_view="$file_size_view MB";
		}
	}
	return $file_size_view;
}

function convertFileType($exts = '') {
	switch ($exts) {
		case 'jpg':
			return 'image/pjpeg';
		break;
		case 'jpe':
			return 'image/pjpeg';
		break;
		case 'jpeg':
			return 'image/pjpeg';
		break;
		case 'pdf':
			return 'application/pdf';
		break;
		case 'gif':
			return 'image/gif';
		break;
		case 'bmp':
			return 'image/bmp';
		break;
		case 'png':
			return 'image/png';
		break;
		case 'rar':
			return 'x-rar-compressed';
		break;
		case 'txt':
			return 'text/plain';
		break;
		case 'swf':
			return 'application/x-shockwave-flash';
		break;
		case 'zip':
			return 'application/zip';
		break;
		case 'doc':
			return 'application/msword';
		break;
		default:
			return 'application/octet-stream';
		break;
	}
}
?>
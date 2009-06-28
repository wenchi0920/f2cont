<?php 
//装载主体页面内容
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$load=(empty($_GET['load']))?"":$_GET['load'];
if (preg_match("/http|:|\.|\/| |\\\/i",$load)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$borwseTitle="";
$logTags="";
$arr_array=array();
switch ($load){
	case "read": //增加本日志阅读量
		$id=$_GET['id'];

		if (empty($_COOKIE['readlog']) || (!empty($_COOKIE['readlog']) && strpos($_COOKIE['readlog'],";$id;")<1)){
			if (!empty($_COOKIE['readlog'])){
				$cookie_readlog=$_COOKIE['readlog']."$id;";
			}else{
				$cookie_readlog=";;$id;";
			}	
			setcookie("readlog", $cookie_readlog, time()+(1*24*3600),$cookiepath,$cookiedomain);
			$DMC->query("update ".$DBPrefix."logs set viewNums=viewNums+1 WHERE id='$id'");
		}

		//读取日志
		$saveType=(!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?"a.saveType>0":"(a.saveType=1 or a.saveType=2)";
		$sql="select a.*,b.name,b.cateIcons from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where $saveType and a.id='$id' order by a.postTime desc";
		if ($arr_array=$DMC->fetchArray($DMC->query($sql))){
			$borwseTitle=$arr_array['logTitle']." - ".$arr_array['name'];
			$logTags=($arr_array['tags']!="")?",".str_replace(";",",",$arr_array['tags']):"";
		}else{
			$borwseTitle="$strErrorNoExistsLog";
		}
		$load_file="include/read.inc.php";		
		break;
	case "applylink": //申请友情连接
		$load_file="include/applylink.inc.php";
		$borwseTitle=$strApplyLink;
		break;
	default:
		if (!empty($arrTopModule[$load])){//模块
			$borwseTitle=$arrTopModule[$load]['modTitle'];
			$load_file=$arrTopModule[$load]['pluginPath'];		
		}else{//默认装载页面
			if (isset($_GET['disType']) && ($_GET['disType']==1 || $_GET['disType']==0)) {
				$disType=$_GET['disType'];
				setcookie("disType", $disType, time()+(365*24*3600),$cookiepath,$cookiedomain);	
			}else if (isset($_COOKIE['disType']) && ($_COOKIE['disType']==1 || $_COOKIE['disType']==0)) {
				$disType=$_COOKIE['disType'];
			}else{
				$disType=$settingInfo['disType'];
			}			
			$load_file="include/content.inc.php";
			$load="";
		}
}

//留言本和评论时装载cache.php
if ($load=="guestbook" || $load=="read") include_once(F2BLOG_ROOT."./include/cache.php");

//装载会员表
if ($load=="" || $load=="read") include_once(F2BLOG_ROOT."./cache/cache_members.php");

if (!isset($base_rewrite)) $base_rewrite="";
?>
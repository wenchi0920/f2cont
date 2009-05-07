<?php  
//此文件为后台用来检测是否能正常静态页面导向的。
//不要删除该文件。否则后台无法检测此功能是否正常。
error_reporting(0);
session_start();
if (empty($_SESSION['rights']) || $_SESSION['rights']=="member"){
	die ('Access Denied.');
}

include_once("../cache/cache_setting.php");
include_once("../include/language/admin/".basename($settingInfo['language']).".php");

if ($_GET['test']=="php"){
	header("location:../rewrite.php/test-php.html");
	exit;
}

if ($_GET['test']=="rewrite"){
	if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']),"iis")>0){
		$SERVER_ROOT=str_replace(str_replace("/","\\",$_SERVER['PHP_SELF']),"",__FILE__);
		if (file_exists($SERVER_ROOT."./httpd.ini")){
			header("location:../test-rewrite.html");
			exit;
		}
	}else{
		if (file_exists("../.htaccess")){
			header("location:../test-rewrite.html");
			exit;
		}
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Test Result</title>
</head>

<body>
<font size="5" color="red">
<?php echo (strpos(strtolower($_SERVER['SERVER_SOFTWARE']),"iis")>0)?$strIISRewriteHelp:$strApacheRewriteHelp?>
</font>
</body>
</html>
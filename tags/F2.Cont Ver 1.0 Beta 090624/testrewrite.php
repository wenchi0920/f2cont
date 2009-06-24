<?php
//此文件为后台用来检测是否能正常静态页面导向的。
//不要删除该文件。否则后台无法检测此功能是否正常。
@error_reporting(0);

include_once("cache/cache_setting.php");
include_once("include/language/home/".basename($settingInfo['language']).".php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Test Result</title>
</head>

<body>
<font size="12px" color="red">
<?php
if ($_GET['test']=="php"){
	echo $strSettingRewriteResult1;
}

if ($_GET['test']=="rewrite"){
	echo $strSettingRewriteResult2;
}
?>
</font>
</body>
</html>
<?php
//读取多媒体路径
include_once("include/function.php");
include_once("include/cache.php");

//防止直接读取此页面。
$media_id=empty($_GET['id'])?0:intval($_GET['id']);
$urlself=$_SERVER['HTTP_HOST'];
$referer=empty($_SERVER['HTTP_REFERER'])?"":$_SERVER['HTTP_REFERER'];
if (strpos(";$referer",$urlself)<1 || $media_id<1) {
?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="UTF-8" />
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="refresh" content="3; url=index.php"> 
	<title><?php echo $strDownloadNoValid?></title>
	</head>
<?php 
	echo "<p align=center><font color=\"red\" size=\"5\"><b>$strDownloadNoValidInfo</b></font></p>";
	exit;
}

$dataInfo = $DMC->fetchArray($DMC->query("select name from ".$DBPrefix."attachments where id='$media_id'"));
if ($dataInfo) {
	//更新下载量
	$modify_sql="UPDATE ".$DBPrefix."attachments set downloads=downloads+1 WHERE id='$media_id'";
	$DMC->query($modify_sql);

	//更新附件Cache
	download_recache();
	attachments_recache();
	
	ob_end_clean();
	if (strpos($dataInfo['name'],"://")<1) $dataInfo['name']=$settingInfo['blogUrl']."attachments/".$dataInfo['name'];
	echo $dataInfo['name'];
	exit;
}else{
	echo $strNoExits;
	exit;
}
?>
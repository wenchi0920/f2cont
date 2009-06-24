<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();

//预览数据
$check_info=1;

//取得Form的Value
$cateId=trim($_POST['cateId']);
$logTitle=trim($_POST['logTitle']);
$logContent=trim($_POST['logContent']);
$quoteUrl=!empty($_POST['quoteUrl'])?$_POST['quoteUrl']:"";
$pubTimeType=trim($_POST['pubTimeType']);
$pubTime=trim($_POST['pubTime']);
$isComment=!empty($_POST['isComment'])?intval($_POST['isComment']):0;
$isTrackback=!empty($_POST['isTrackback'])?intval($_POST['isTrackback']):0;
$isTop=!empty($_POST['isTop'])?intval($_POST['isTop']):0;
$weather=trim($_POST['weather']);
$tags=trim($_POST['tags']);
$edittype=$_REQUEST['edittype'];

if ($_POST['addpassword']!="" && strlen($_POST['addpassword'])<15){
	$addpassword=md5($_POST['addpassword']);
}else{
	$addpassword=$_POST['addpassword'];
}
	
//检测输入内容为空
if ($logTitle=="" or $logContent=="" or $cateId==""){
	$ActionMessage=$strErrNull;
	$check_info=0;
}

if ($check_info==1){
	//$logContent=encode($logContent);
	$logTitle=encode($logTitle);
	$postTime=($pubTimeType=="now")?time():str_format_time($pubTime);
	$author=$_SESSION['username'];

	//如果是ubb编辑器
	if ($logsediter=="ubb"){
		if (empty($_POST['allowhtml'])){
			$logContent=ubblogencode($logContent);				
		}else{
			$logContent=str_replace("\r","",$logContent);
			$logContent=str_replace("\n","",$logContent);
			//$logContent=mysql_escape_string($logContent);
			$logContent=str_replace("'","&#39;",$logContent);
		}
	}else{
		//$logContent=mysql_escape_string($logContent);
		$logContent=str_replace("'","&#39;",$logContent);
	}

	//转换UBB标签
	if (strpos(";".$logContent,"[hideBegin]")>0) $logContent=preg_replace("/\[hideBegin\](.+?)\[hideEnd\]/is","<!--hideBegin-->\\1<!--hideEnd-->",$logContent);
	if (strpos(";".$logContent,"[fileBegin]")>0) $logContent=preg_replace("/\[fileBegin\](.+?)\[fileEnd\]/is","<!--fileBegin-->\\1<!--fileEnd-->",$logContent);		
	if (strpos(";".$logContent,"[flvBegin]")>0) $logContent=preg_replace("/\[flvBegin\](.+?)\[flvEnd\]/is","<!--flvBegin-->\\1<!--flvEnd-->",$logContent);		
	if (strpos(";".$logContent,"[musicBegin]")>0) $logContent=preg_replace("/\[musicBegin\](.+?)\[musicEnd\]/is","<!--musicBegin-->\\1<!--musicEnd-->",$logContent);	
	if (strpos(";".$logContent,"[mfileBegin]")>0) $logContent=preg_replace("/\[mfileBegin\](.+?)\[mfileEnd\]/is","<!--mfileBegin-->\\1<!--mfileEnd-->",$logContent);
	if (strpos(";".$logContent,"[galleryBegin]")>0) $logContent=preg_replace("/\[galleryBegin\](.+?)\[galleryEnd\]/is","<!--galleryBegin-->\\1<!--galleryEnd-->",$logContent);
	if (strpos($logContent,"[more]")>0) $logContent=str_replace("[more]","<!--more-->",$logContent);
	if (strpos($logContent,"[nextpage]")>0) $logContent=str_replace("[nextpage]","<!--nextpage-->",$logContent);

	$rsexits=getFieldValue($DBPrefix."logs","saveType='2'","id");
	if ($rsexits!=""){
		$edit_sql=($pubTimeType=="now")?"":",postTime='$postTime'";
		$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',author='$author',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',weather='$weather',saveType='2',tags='$tags',logsediter='$logsediter',password='$addpassword'$edit_sql where id='$rsexits'";
		$DMC->query($sql);
		$last_id=$rsexits;
	}else{
		$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$weather','2','$tags','$addpassword','$logsediter')";
		$DMC->query($sql);
		$last_id=$DMC->insertId();
	}
	header("location: ../index.php?load=read&id=$last_id");
} else {
	header("Content-Type: text/html; charset=utf-8");
	echo $ActionMessage;
}
?>
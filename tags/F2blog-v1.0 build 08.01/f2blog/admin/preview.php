<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//预览数据
$check_info=1;

//echo $_POST['logContent'];

//取得Form的Value
$cateId=trim($_POST['cateId']);
$oldCateId=trim($_POST['oldCateId']);
$logTitle=trim($_POST['logTitle']);
$logContent=trim($_POST['logContent']);
$author=trim($_POST['author']);
$quoteUrl=trim($_POST['quoteUrl']);
$pubTimeType=trim($_POST['pubTimeType']);
$pubTime=trim($_POST['pubTime']);
$isComment=trim($_POST['isComment']);
$isTrackback=trim($_POST['isTrackback']);
$isTop=trim($_POST['isTop']);
$weather=trim($_POST['weather']);
$saveType=trim($_POST['saveType']);
$tags=trim($_POST['tags']);
$oldTags=trim($_POST['oldTags']);
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

	$rsexits=getFieldValue($DBPrefix."logs","saveType='2'","id");
	if ($rsexits!=""){
		$edit_sql=($pubTimeType=="now")?"":",postTime='$postTime'";
		$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',author='$author',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',weather='$weather',saveType='2',tags='$tags',password='$addpassword'$edit_sql where id='$rsexits'";
		$DMC->query($sql);
		$last_id=$rsexits;
	}else{
		$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$weather','2','$tags','$addpassword')";
		$DMC->query($sql);
		$last_id=$DMC->insertId();
	}
	header("location: ../index.php?load=read&id=$last_id");
} else {
	@header("Content-Type: text/html; charset=utf-8");
	echo $ActionMessage;
}
?>
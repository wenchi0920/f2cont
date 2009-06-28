<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$logId=empty($_GET['logId'])?"":$_GET['logId'];

$isTrackback=getFieldValue($DBPrefix."logs","id='$logId'","isTrackback");

if (!filter_ip(getip()) or $isTrackback==0) { //为禁止IP时，不给看引用地址
	echo $strTrackbackSessionError;
}else{
	//取得随机数
	$tb_extra=tb_extra(10);
	$tbDate=time();

	//写入数据库
	$sql="insert into ".$DBPrefix."tbsession(extra,tbDate,logId) values('$tb_extra','$tbDate','$logId')";
	$DMC->query($sql);

	//返回内容
	$home_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"f2blog_ajax.php"));
	echo $home_url."trackback.php?tbID=$logId&extra=$tb_extra";
}
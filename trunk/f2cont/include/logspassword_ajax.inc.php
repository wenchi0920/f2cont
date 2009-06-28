<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$logId=intval($_POST['logId']);

if ($_POST['logpassword']!=""){
	if ($_SESSION['logpassword']!=""){
		$_SESSION['logpassword']=$_SESSION['logpassword'].";".md5(encode($_POST['logpassword']));
	}else{
		$_SESSION['logpassword']=md5(encode($_POST['logpassword']));
	}		
}

//Log content
$sql="select id,password,logContent,logsediter from ".$DBPrefix."logs where saveType>0 and id='$logId' order by postTime desc";
$fa=$DMC->fetchArray($DMC->query($sql));
if ((strpos(";".$_SESSION['logpassword'],$fa['password'])>0)){
	echo "logcontent_".$logId."+|*+|+*|+";
	if ($settingInfo['isHtmlPage']==1 && file_exists(F2BLOG_ROOT."./cache/html/".$fa['id'].".html")) {
		include(F2BLOG_ROOT."./cache/html/".$fa['id'].".html");
	}else{
		$content=formatBlogContent($fa['logContent'],1,$fa['id']);
		if ($fa['logsediter']=="ubb") $content=nl2br($content);
		echo $content;
	}
}else{
	echo $strLogsReadPasswordError;
}
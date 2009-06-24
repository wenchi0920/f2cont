<?php  
header("Content-Type: text/html; charset=utf-8");
include_once("include/function.php");
include_once(F2BLOG_ROOT."./cache/cache_members.php");

//下载文件
if ($settingInfo['showDown']==1){
	$sql="select id,logTitle,logContent,author,postTime,password from ".$DBPrefix."logs where id='".$_GET['id']."' and saveType='1'";
	if ($fa=$DMC->fetchArray($DMC->query($sql))){
		if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
			$content=$strLogPasswordHelp;
		}else{
			$content=formatBlogContent($fa['logContent'],1,$fa['id']);
			$author=($memberscache[$fa['author']]!="")?$memberscache[$fa['author']]:$fa['author'];
		}

		$body="$strSearchTitle: ".$fa['logTitle']."\n";
		$body.="$strAuthor: ".$author."\n";
		$body.="$strLogDate: ".format_time("Y-m-d H:i:s",$fa['postTime'])."\n";
		$body.="$strLogRead: ".$settingInfo['blogUrl']."index.php?load=read&id=".$fa['id']."\n";
		$body.="---------------------------------------------------------------------------\n";
		$body.=dencode($content);
		$body=str_replace("&nbsp;"," ",$body);
		$body=str_replace("<p>","\n",$body);
		$body=str_replace("<br />","\n",$body);
		$body=str_replace("<br>","\n",$body);
		$body=str_replace("<br/>","\n",$body);
		$body=strip_tags($body);

		$filename=strip_tags(dencode($fa['logTitle']));
		/*if (function_exists('mb_convert_encoding'))	{
			$filename=mb_convert_encoding($filename,'GBK','UTF-8');
		}elseif (function_exists('iconv')) {
			$filename=iconv('UTF-8','GBK',$filename);
		}else{
			$filename=$id;
		}*/
		$filename = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')>0) ? urlencode($filename) : $filename;

		header("Content-type: application/zip");
		header("Content-disposition: attachment; filename=$filename.doc");
		echo $body;
		exit;
	}else{
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$strErrorNoExistsLog\");\n";
		echo "location.href=\"index.php\";\n";
		echo "</script>\n";
	}
}else{
	echo "<script language=\"Javascript\"> \n";
	echo "alert(\"$strLogsDownloadError\");\n";
	echo "location.href=\"index.php\";\n";
	echo "</script>\n";
}
?>
<?php  
header("Content-Type: text/html; charset=utf-8");
include_once("include/function.php");
include_once(F2BLOG_ROOT."./cache/cache_members.php");

//下载文件
if ($settingInfo['showPrint']==1){
	$sql="select id,logTitle,logContent,postTime,author,password from ".$DBPrefix."logs where id='".$_GET['id']."' and saveType='1'";
	if ($fa=$DMC->fetchArray($DMC->query($sql))){
		if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
			$content=$strLogPasswordHelp;
		}else{
			$content=formatBlogContent($fa['logContent'],1,$fa['id']);
			if (!empty($fa['logsediter']) && $fa['logsediter']=="ubb") $content=nl2br($content);
		}
		$author=($memberscache[$fa['author']]!="")?$memberscache[$fa['author']]:$fa['author'];

		echo "<font size=\"5\"><b>{$fa['logTitle']}</b></font><br /><br />\n";
		echo "<font size=\"2\">$strAuthor: ".$author." &nbsp;\n";
		echo "$strLogDate: ".format_time("Y-m-d H:i:s",$fa['postTime'])." &nbsp;\n";
		echo "$strLogRead: ".$settingInfo['blogUrl']."index.php?load=read&id=".$fa['id']."</font>\n";
		echo "<hr style=\"color:#CCCCCC;height:1px;\"/>\n\n";
		echo $content;
	}else{
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$strErrorNoExistsLog\");\n";
		echo "window.close();\n";
		echo "</script>\n";
	}
}else{
	echo "<script language=\"Javascript\"> \n";
	echo "alert(\"$strLogsDownloadError\");\n";
	echo "window.close();\n";
	echo "</script>\n";
}
?>
<?php 
include("../include/function.php");

if (empty($_SESSION['rights']) || $_SESSION['rights']=="member"){
	die ('Access Denied.');
}

# 禁止直接访问该页面
if ($_GET['arrayhtmlid']=="") {
	die ('Access Denied.');
}

if ($_GET['arrayhtmlid']!="all"){
	$array_logid=explode(",",$_GET['arrayhtmlid']);
}else{
	include("../cache/cache_logsTitle.php");
	$array_logid=array_keys($logsTitlecache);
}

if (empty($_GET['index'])) $_GET['index']=1;
if ($_GET['index']>count($array_logid)) $_GET['index']=count($array_logid);
if (empty($_GET['edittype'])) $_GET['edittype']="";

if ($settingInfo['rewrite']==0) $gourl="index.php?load=read&id=";
if ($settingInfo['rewrite']==1) $gourl="rewrite.php/read-";
if ($settingInfo['rewrite']==2) $gourl="read-";

if (count($array_logid)>0){
	$html_id=$array_logid[$_GET['index']-1];
	$query_sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where a.id='$html_id' order by a.isTop desc,a.postTime desc";
	if ($fa=$DMC->fetchArray($DMC->query($query_sql))){
		$html_path=format_time("Ym",$fa['postTime']);

		//如果有首页文件，则删除
		if (file_exists(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id']."_index.php")) @unlink(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id']."_index.php");

		$content=$fa['logContent'];			
		$content_index="";

		if (empty($fa['password'])){//加密日志不成生静态文件。
			//生成首页文件
			if (strpos($content,"<!--more-->")>0){
				$content_index=htmlSubString($content,"<!--more-->");
				$content_index=formatBlogContent($content_index,0,$fa['id'],1);
				if ($fa['logsediter']=="ubb") $content_index=nl2br($content_index);
				$content_index.="<p><a class=\"more\" href=\"$gourl".$fa['id'].$settingInfo['stype']."\">[$strContentAll]</a></p> \n";
			}else{
				if ($fa['autoSplit']>0){
					$textlength=getStringLength(strip_tags($content));
					if ($textlength>$fa['autoSplit']){
						$content_index=htmlSubString($content,$fa['autoSplit']);
						$content_index=formatBlogContent($content_index,0,$fa['id'],1);
						if ($fa['logsediter']=="ubb") $content_index=nl2br($content_index);
						$content_index.="<p><a class=\"more\" href=\"$gourl".$fa['id'].$settingInfo['stype']."\">[$strContentAll]</a></p> \n";
					}else{
						$content_index=htmlSubString($content,$fa['autoSplit']);
						$content_index=formatBlogContent($content_index,0,$fa['id'],1);
						if ($fa['logsediter']=="ubb") $content_index=nl2br($content_index);
					}
				}
			}

			//生成阅读日志
			$content=formatBlogContent($content,0,$fa['id'],1);
			if ($fa['logsediter']=="ubb") $content=nl2br($content);


			//生成静态页面
			if ($content_index!=""){
				writetohtml($html_path."/".$fa['id']."_index", $content_index);
			}
			writetohtml($html_path."/".$fa['id'], $content);
		}
	}
	
	$next_index=$_GET['index']+1;
	header("Content-Type: text/html; charset=utf-8");	
	if ($next_index<count($array_logid)+1){
		
		$url="create_html.inc.php?edittype=".$_GET['edittype']."&arrayhtmlid=".$_GET['arrayhtmlid']."&index=$next_index";
		$content="$strLogsCreateHtmlAlert1".$_GET['index']."/".count($array_logid)."$strLogsCreateHtmlAlert2";
		echo NavigatorNextURL($url,$content);		
	}else{
		if ($_GET['edittype']=="front"){
			$redirect_url="../".$gourl.$_GET['arrayhtmlid'].$settingInfo['stype'];
		}else{
			$redirect_url="logs.php";
			if (count($array_logid)>1){
				echo "<span style='font-size:14px;color:blue'>".count($array_logid)." $strLogsCreateHtmlAlert3</span>";	
				sleep(1);
			}
		}		
		echo "<script language='javascript'>"; 
		echo "window.location='".$redirect_url."';"; 
		echo "</script>";
	}
}
?>
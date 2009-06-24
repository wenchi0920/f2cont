<?php 
include_once("include/function.php");

$saveType=(!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?"(a.saveType=1 or a.saveType=3)":"a.saveType=1";
if (!empty($_GET['cateID']) && is_numeric($_GET['cateID'])){
	$find_sql="select id from ".$DBPrefix."categories where parent='$seekcate' or id='$seekcate'";
	$find_result=$DMC->query($find_sql);
	$str="";
	while($fa=$DMC->fetchArray($find_result)) {
		$str.=" or a.cateId='".$fa['id']."'";
	}
	$find=" and (".substr($str,4).")";

	$sql="select a.logsediter,a.password,saveType,a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid,c.nickname from {$DBPrefix}logs as a inner join {$DBPrefix}categories as b on a.cateId=b.id left join {$DBPrefix}members as c on a.author=c.username where $saveType $find order by a.postTime desc limit 0,".$settingInfo['newRss'];
}else{
	$sql="select a.logsediter,a.password,saveType,a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid,c.nickname from {$DBPrefix}logs as a inner join {$DBPrefix}categories as b on a.cateId=b.id left join {$DBPrefix}members as c on a.author=c.username where $saveType order by a.postTime desc limit 0,".$settingInfo['newRss'];
}
$query_result=$DMC->query($sql);
$arr_array=$DMC->fetchQueryAll($query_result);

$home_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"rss.php"));
//$home_url=$settingInfo['blogUrl'];

if ($settingInfo['rewrite']==0) {
	$gourl=$home_url."index.php?load=read&amp;id=";
	$cgourl=$home_url."index.php?job=category&amp;seekname=";
}
if ($settingInfo['rewrite']==1) {
	$gourl=$home_url."rewrite.php/read-";
	$cgourl=$home_url."rewrite.php/category-";
}
if ($settingInfo['rewrite']==2) {
	$gourl=$home_url."read-";
	$cgourl=$home_url."category-";
}

ob_end_clean();
header('Content-Type: text/xml; charset=utf-8');
echo "<?php xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title type="html"><![CDATA[<?php echo str_replace("&","&amp;",$settingInfo['name']);?>]]></title>
<subtitle type="html"><![CDATA[<?php echo $settingInfo['blogTitle']?>]]></subtitle>
<id><?php echo $home_url?></id> 
<link rel="alternate" type="text/html" href="<?php echo $home_url?>" /> 
<link rel="self" type="application/atom+xml" href="<?php echo $home_url?>atom.php" /> 
<generator uri="http://www.f2blog.com/" version="<?php echo blogVersion; ?>">F2Blog</generator> 
<updated><?php
if (count($arr_array)!=0) {
	echo format_time("Y-m-d H:i:s",$arr_array[0]['postTime']);
} else {
	echo format_time("Y-m-d H:i:s",time());
}
?></updated> 
<?php 
foreach($arr_array as $key=>$fa){
	if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
		$content="<img src=\"".$home_url."images/icon_lock.gif\" alt=\"\" /> $strLogPasswordHelp \n";
	}else{
		$content=$fa['logContent'];
		$rssLength=($settingInfo['rssLength']<1)?500:$settingInfo['rssLength'];
		if ($settingInfo['rssContentType']=="1") $content=htmlSubString($content,$rssLength);
		$content=formatBlogContent($content,0,$fa['cid']);
		$content=str_replace("attachments/",$home_url."attachments/",$content);
		$content=preg_replace("/src=\"([^http].*?)\"/is","src=\"$home_url\\1\"",$content);
		$content=str_replace("download.php",$home_url."download.php",$content);
		if ($fa['logsediter']=="ubb") $content=nl2br($content);
	}
	$author=empty($fa['nickname'])?$fa['author']:$fa['nickname'];
?>
<entry>
  <title type="html"><![CDATA[<?php echo $fa['logTitle']?><?php echo ($fa['saveType']==3)?" [$strHidLog]":""?>]]></title>
  <author>
	 <name><?php echo $author?></name>
	 <uri><?php echo $gourl.$fa['cid'].$settingInfo['stype']?></uri>
	 <email><?php echo $settingInfo['email']; ?></email>
  </author>
  <category term="" scheme="<?php echo $cgourl.$fa['cateId'].$settingInfo['stype']?>" label="<?php echo $fa['name']; ?>" /> 
  <updated><?php echo format_time("Y-m-d H:i:s",$fa['postTime']); ?></updated>
  <published><?php echo format_time("Y-m-d H:i:s",$fa['postTime']); ?></published>
  <content type='text'><![CDATA[<?php echo $content?>]]></content>
  <link rel="alternate" type="text/html" href="<?php echo $gourl.$fa['cid'].$settingInfo['stype']?>" /> 
  <id><?php echo $gourl.$fa['cid'].$settingInfo['stype']?></id> 
</entry>	
<?php }?>
</feed>
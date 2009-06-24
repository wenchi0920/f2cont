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

if ($settingInfo['rewrite']==0) $gourl=$home_url."index.php?load=read&amp;id=";
if ($settingInfo['rewrite']==1) $gourl=$home_url."rewrite.php/read-";
if ($settingInfo['rewrite']==2) $gourl=$home_url."read-";

ob_end_clean();
header('Content-Type: text/xml; charset=utf-8');
echo "<?php xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
?>
<rss version="2.0">
<channel>
<title><![CDATA[<?php echo str_replace("&","&amp;",$settingInfo['name']);?>]]></title>
<link><?php echo $home_url?></link>
<description><![CDATA[<?php echo $settingInfo['blogTitle']?>]]></description>
<language>utf-8</language>
<copyright><![CDATA[<?php echo blogCopyright?>]]></copyright>
<webMaster><![CDATA[<?php echo $settingInfo['email']?>]]></webMaster>
<generator><?php echo "F2blog ".blogVersion?></generator> 
<image>
	<title><?php echo str_replace("&","&amp;",$settingInfo['name']);?></title> 
	<url><?php echo $home_url."attachments/".$settingInfo['logo']?></url> 
	<link><?php echo $home_url?></link> 
	<description><?php echo str_replace("&","&amp;",$settingInfo['name']);?></description> 
</image>
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
<item>
	<link><?php echo $gourl.$fa['cid'].$settingInfo['stype']?></link>
	<title><![CDATA[<?php echo $fa['logTitle']?><?php echo ($fa['saveType']==3)?" [$strHidLog]":""?>]]></title>
	<author><?php echo $author?></author>
	<category><![CDATA[<?php echo $fa['name']?>]]></category>
	<pubDate><?php echo format_time("L",$fa['postTime'])?></pubDate>
	<guid><?php echo $gourl.$fa['cid'].$settingInfo['stype']?></guid>	
	<description><![CDATA[<?php echo $content?>]]></description>
</item>
<?php }?>
</channel>
</rss>
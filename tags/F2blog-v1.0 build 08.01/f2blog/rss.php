<?
include_once("include/function.php");

$site_home="http://".$_SERVER['SERVER_NAME'].substr($PHP_SELF,0,strpos($PHP_SELF,"/rss.php"))."/";

//$settinginfo['newRss']显示篇数 $settinginfo['rssContentType']内容全部还是部分发送。

if ($_GET['cateID']!=""){
	$seekcate=$_GET['cateID'];
	$find_sql="select id from ".$DBPrefix."categories where parent='$seekcate' or id='$seekcate'";
	$find_result=$DMF->query($find_sql);
	$str="";
	while($fa=$DMF->fetchArray($find_result)) {
		$str.=" or a.cateId='".$fa['id']."'";
	}
	$find.=" and (".substr($str,4).")";


	$sql="select a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where a.saveType=1 $find order by a.postTime desc limit 0,".$settingInfo['newRss'];
}else{
	$sql="select a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where a.saveType=1 order by a.postTime desc limit 0,".$settingInfo['newRss'];
}
//echo $sql;
$query_result=$DMF->query($sql);
$arr_array=$DMF->fetchQueryAll($query_result);

header('Content-Type: text/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
?>
<rss version="2.0">
<channel>
<title><![CDATA[<?=$settingInfo['name']?>]]></title>
<link><?=$site_home?></link>
<description><![CDATA[<?=$settingInfo['blogTitle']?>]]></description>
<language>utf-8</language>
<copyright><![CDATA[<?=blogCopyright?>]]></copyright>
<webMaster><![CDATA[<?=$settingInfo['email']?>]]></webMaster>
<generator><?="F2blog ".blogVersion?></generator> 
<image>
	<title><?=$settingInfo['name']?></title> 
	<url><?=$site_home."attachments/".$settingInfo['logo']?></url> 
	<link><?=$site_home?></link> 
	<description><?=$settingInfo['name']?></description> 
</image>
<?
for ($k=0;$k<count($arr_array);$k++){	
	$content=str_replace("attachments/",$site_home."attachments/",formatBlogContent($arr_array[$k]['logContent']));
	$content=str_replace("images/",$site_home."images/",$content);
	$content=str_replace("download.php",$site_home."download.php",$content);
?>
<item>
	<link><?=$site_home."index.php?load=read&amp;id=".$arr_array[$k]['cid']?></link>
	<title><![CDATA[<?=$arr_array[$k]['logTitle']?>]]></title>
	<author><?=$arr_array[$k]['author']?></author>
	<category><![CDATA[<?=$arr_array[$k]['name']?>]]></category>
	<pubDate><?=format_time("L",$arr_array[$k]['postTime'])?></pubDate>
	<guid><?=$site_home."index.php?load=read&amp;id=".$arr_array[$k]['cid']?></guid>	
	<description><![CDATA[<?=$content?>]]></description>
</item>
<?}?>
</channel>
</rss>
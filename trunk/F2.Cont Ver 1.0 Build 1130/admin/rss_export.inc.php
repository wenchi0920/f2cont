<?php 
include("../include/function.php");

if (empty($_SESSION['rights']) || $_SESSION['rights']=="member"){
	die ('Access Denied.');
}

//保存参数
$action=$_GET['action'];

$home_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"admin"));

if ($action=="save"){
	$isgzip=$_POST['isgzip'];
	$filext=".xml";
	$recordNum=(empty($_POST['recordNum']))?80:$_POST['recordNum'];
	$rssCate=$_POST['rssCate'];
	$backup="../backup/".$_POST['backup'];

	//开始备份
	if ($rssCate!=""){
		$find_sql="select id from ".$DBPrefix."categories where parent='$rssCate' or id='$rssCate'";
		$find_result=$DMC->query($find_sql);
		$str="";
		while($fa=$DMC->fetchArray($find_result)) {
			$str.=" or a.cateId='".$fa['id']."'";
		}
		$find.=" and (".substr($str,4).")";

		$sql="select a.logsediter,a.password,a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where (a.saveType=1 or a.saveType=3) $find order by a.postTime desc";
	}else{
		$sql="select a.logsediter,a.password,a.cateId,a.logTitle,a.logContent,a.author,a.postTime,b.name,a.id as cid from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where a.saveType=1 or a.saveType=3 order by a.postTime desc";
	}
	
	$rssbody="";
	$rows=0;
	$p=1;
	$error="";
	$result = $DMC->query($sql);
	while ($row = $DMC->fetchArray($result)) {
		$content=formatBlogContent($row['logContent'],0,$row['cid']);
		if ($row['logsediter']=="ubb") $content=nl2br($content);	
		
		$rssbody.="<item>\r\n";
		$rssbody.="	<link>".$home_url."index.php?load=read&amp;id=".$row['cid']."</link>\r\n";
		$rssbody.="	<title><![CDATA[".$row['logTitle']."]]></title>\r\n";
		$rssbody.="	<author>".$row['author']."</author>\r\n";
		$rssbody.="	<category><![CDATA[".$row['name']."]]></category>\r\n";
		$rssbody.="	<pubDate>".format_time("L",$row['postTime'])."</pubDate>\r\n";
		$rssbody.="	<guid>".$home_url."index.php?load=read&amp;id=".$row['cid']."</guid>\r\n";
		$rssbody.="	<description><![CDATA[".$content."]]></description>\r\n";
		$rssbody.="</item>\r\n";
		
		$rows++;
		if($rows>=$recordNum){
			$filename=$backup.("_v".$p.$filext);
			$dump=backup_rss($rssbody);
			if ($isgzip==0) {
				$msg=write_file($dump,$filename);  //写入分卷文件
				$ext="";
			} else {
				$msg=gzwrite_file($dump,$filename);  //写入分卷压缩文件
				$ext=".gz";
			}
			if($msg=="") {
				$ActionMessage.="RSS$strDataBackupUnit$p:  <a href='../backup/$filename$ext'>$filename$ext</a><br>";
				$p++;
				$rssbody="";
				$rows=0;
			} else {
				$ActionMessage.="$msg<br>";
				$error="error";
			}
		}
	}

	//写入全部文件
	if ($rssbody!="" and $error==""){
		if($p>1) {
			$filename=$backup.("_v".$p.$filext);
		} else {
			$filename=$backup.$filext;
		}
		
		$dump=backup_rss($rssbody);
		if ($isgzip==0) {
			$msg=write_file($dump,$filename);  //写入分卷文件
		} else {
			$msg=gzwrite_file($dump,$filename);  //写入分卷压缩文件
			$ext=".gz";
		}
		$message="RSS{$strDataBackupUnit}$p:  <a href='../backup/$filename$ext'>$filename$ext</a><br>";

		if ($msg==""){
			$ActionMessage.=$message;
			$ActionMessage=$strRssExportDemo.$ActionMessage;
		}else{
			$ActionMessage.="$strDataBackupBad";	
		}
		$_SESSION['rssMessage'] = $ActionMessage;
		echo "<script language='javascript'>"; 
		echo "window.location='rss_export.php';"; 
		echo "</script>";
	}
}

function backup_rss($rssbody) {
	global $settingInfo,$home_url;

	$dump="<?php xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
	$dump.="<rss version=\"2.0\">\r\n";
	$dump.="<channel>\r\n";
	$dump.="<title><![CDATA[".str_replace("&","&amp;",$settingInfo['name'])."]]></title>\r\n";
	$dump.="<link>".$home_url."</link>\r\n";
	$dump.="<description><![CDATA[".$settingInfo['blogTitle']."]]></description>\r\n";
	$dump.="<language>utf-8</language>\r\n";
	$dump.="<copyright><![CDATA[".blogCopyright."]]></copyright>\r\n";
	$dump.="<webMaster><![CDATA[".$settingInfo['email']."]]></webMaster>\r\n";
	$dump.="<generator>F2blog ".blogVersion."</generator> \r\n";
	$dump.="<image>\r\n";
	$dump.="	<title>".str_replace("&","&amp;",$settingInfo['name'])."</title> \r\n";
	$dump.="	<url>".$home_url."attachments/".$settingInfo['logo']."</url> \r\n";
	$dump.="	<link>".$home_url."</link> \r\n";
	$dump.="	<description>".str_replace("&","&amp;",$settingInfo['name'])."</description> \r\n";
	$dump.="</image>\r\n";
	$dump.=$rssbody;
	$dump.="</channel>\r\n";
	$dump.="</rss>\r\n";

	return $dump;
}

function gzwrite_file ($gzcontent,$gzfilename) {
	global $strDataBackupBad;

	if (is_dir("../backup/")){
		$gzfilename.='.gz';
		$gzfp=gzopen($gzfilename, 'wb9');
		gzwrite($gzfp, $gzcontent);
		gzclose($gzfp);
		$ActionMessage="";
	}else{
		$ActionMessage="$strDataBackupBad";	
	}

	return $ActionMessage;
}

function write_file($sql,$filename) {
	global $strDataBackupBad,$strDataBackupSuccess;

	if (is_dir("../backup/")){
		if (!$fp = fopen($filename, 'ab')) {
			$ActionMessage="$strDataBackupBad";	
		} else {
			fwrite($fp,$sql);
			fclose($fp);
			$ActionMessage="";
		}
	}else{
		$ActionMessage="$strDataBackupBad";	
	}

	return $ActionMessage;
}
?>
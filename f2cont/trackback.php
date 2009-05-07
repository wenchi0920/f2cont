<?php  
	include_once ("include/function.php");
	include_once ("include/cache.php");

	$id=$_GET['tbID'];
	$extra=$_GET['extra'];
	$id=(integer)$id;
	if ($id<0) tb_xml_error ("Invalid ID.");
	$tid=$id;
	unset($id);
	//检查语言是否为UTF8
	$charset_convert=0;
	$charset=strtolower($_SERVER['HTTP_ACCEPT_CHARSET']);
	if ($charset && !strstr($charset, 'utf-8')) {
		if (strstr($charset, 'gb') || strstr($charset, 'big5')) {
			 tb_xml_error ("Your trackback uses a charset other than UTF-8.");
		}
	}

	//使用Ajax技术
	if (strpos(";$settingInfo[ajaxstatus];","T")>0){	
		$result=$DMC->query("SELECT * FROM ".$DBPrefix."tbsession WHERE logId='$tid' and extra='$extra' and tbDate+1800>'".time()."' ");
		$numRows=$DMC->numRows($result);
		if ($numRows<=0) { //没有查到相对应验证码
			tb_xml_error ("Invalid ID or Verifying failed."); 
		}
	}

	$result=$DMC->query("SELECT * FROM ".$DBPrefix."logs WHERE id='$tid' and saveType='1'");
	$numRows=$DMC->numRows($result);
	if ($numRows<=0) { 
		tb_xml_error ("Invalid ID or the ID refers to a locked entry."); 
	} else {
		$my=$DMC->fetchArray($result);
	}

	//检验认证码
	if (strpos(";$settingInfo[ajaxstatus];","T")<1){
		$tb_extra=substr(md5($my['id'].$my['postTime']),0,6);
		if ($tb_extra!=$extra) {
			tb_xml_error ("Verifying failed.");
		}
	}
	
	$title=htmlspecialchars($_REQUEST['title'], ENT_QUOTES);
	$excerpt=htmlspecialchars($_REQUEST['excerpt'], ENT_QUOTES);
	$url=htmlspecialchars($_REQUEST['url'], ENT_QUOTES);
	$blog_name=htmlspecialchars($_REQUEST['blog_name'], ENT_QUOTES);

	if ($url=="") {
		tb_xml_error ("Invalid URL.");
	}

	if ($excerpt=="") {
		tb_xml_error ("We require all Trackbacks to provide an excerption.");
	} else {
		if(strlen($excerpt)>300) {
			$excerpt=subString($excerpt,0,300)." ...";
		}
	}

	//检查过滤
	if (!filter_ip($userdetail['ip'])) {
		tb_xml_error ("Your IP address is banned from sending trackbacks.");
	}
	
	$weburl=str_replace("http://","",$url);
	$weburl=str_replace("https://","",$weburl);
	$posurl=strpos($weburl,"/");
	if ($posurl>=1) {
		$weburl=substr($weburl,0,$posurl);
	}

	if (!filter_url($weburl)) {
		tb_xml_error ("Your Web address is banned from sending trackbacks.");
	}

	if (replace_filter($excerpt) || replace_filter($title) || replace_filter($blog_name)) {
		tb_xml_error ("The trackback content contains some words that are not welcomed on our site. You may edit your post and send it again. Sorry for the inconvenience.");
	}
	
	//同一个网址，在10分钟这内发送不给通过
    $trytb=$DMC->numRows($DMC->query("SELECT * FROM ".$DBPrefix."trackbacks WHERE blogUrl like '%$weburl%' AND postTime+600>='".time()."'"));
    if ($trytb > 0) {
		tb_xml_error ("Same Blog try to send after 10 minutes!");
    } 

    $trytb=$DMC->numRows($DMC->query("SELECT * FROM ".$DBPrefix."trackbacks WHERE ip='".getip()."' AND postTime+30>='".time()."'"));
    if ($trytb > 0) {
		tb_xml_error ("Same IP try to send after 30 seconds !");
    } 

	$spam=$settingInfo['isTbApp']; // 0为开启审核

	if ($spam==1 or strpos($url,";".$settingInfo['ttSiteList'])>=1) {
		$isApp=1;  //直接前臺顯示，不用審核
	} else {
		$isApp=0;
	}
	
	fopen_url($url);
	$sql="INSERT INTO ".$DBPrefix."trackbacks (logId,tbTitle,blogSite,blogUrl,content,postTime,ip,isApp) VALUES ('$tid','$title','$blog_name','$url','$excerpt',".time().",'".getip()."','$isApp')";
	$DMC->query($sql);

	//使用Ajax技术
	if (strpos(";$settingInfo[ajaxstatus];","T")>0){	
		//删除验证信息
		$sql="delete FROM ".$DBPrefix."tbsession WHERE logId='$tid' and extra='$extra'";
		$DMC->query($sql);
	}

	if ($isApp==1) {
		settings_recount("trackbacks");
		settings_recache();
		$DMC->query("UPDATE ".$DBPrefix."logs SET quoteNums=quoteNums+1 WHERE id='$tid'");
	}

	tb_xml_success();

function tb_xml_error($error) {
	header("Content-type:application/xml");
	echo "<?php xml version=\"1.0\" ?>";
	print "<response><error>1</error><message>{$error}</message></response>";
	exit;
}

function tb_xml_success() {
	header("Content-type:application/xml");
	echo "<?php xml version=\"1.0\" ?>";
	print "<response><error>0</error></response>";
	exit;
}

function fopen_url($url) {
	$file_content="";
	$surl=parse_url($url);
	if ($surl['port']=='') $surl['port']=80;
	$fp = fsockopen($surl['host'], $surl['port'], $errno, $errstr, 8);
	if ($fp) {
		$out = "GET {$surl['path']}".($surl['query'] ? '?'.$surl['query'] : '')." HTTP/1.1\r\n";
		$out .= "Host: {$surl['host']}\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)) {
			$file_content .= fgets($fp, 128);
		}
		fclose($fp);
	}

	return $file_content;
}
?>
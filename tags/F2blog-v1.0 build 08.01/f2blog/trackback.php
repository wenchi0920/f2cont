<?PHP
	include_once ("include/function.php");

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
	

	$result=$DMF->query("SELECT * FROM ".$DBPrefix."logs WHERE id='$tid' and saveType='1'");
	$numRows=$DMF->numRows($result);
	if ($numRows<=0) { 
		tb_xml_error ("Invalid ID or the ID refers to a locked entry."); 
	} else {
		$my=$DMF->fetchArray($result);
	}

	//检验认证码
	$tb_extra=tb_extra($my['id'], $my['postTime']);
	if ($tb_extra!=$extra) {
		tb_xml_error ("Verifying failed.");
	}
	
	$title=$_REQUEST['title'];
	$excerpt=$_REQUEST['excerpt'];
	$url=$_REQUEST['url'];
	$blog_name=$_REQUEST['blog_name'];

	if ($url=="") {
		tb_xml_error ("Invalid URL.");
	}

	if ($excerpt=="") {
		tb_xml_error ("We require all Trackbacks to provide an excerption.");
	} else {
		if(strlen($excerpt)>100) {
			$excerpt=substr($excerpt,100)." ...";
		}
		
		$excerpt=encode($excerpt);
	}

	//检查过滤
	if (!filter_ip($userdetail['ip'])) {
		tb_xml_error ("Your IP address is banned from sending trackbacks.");
	}

	if (replace_filter($excerpt) || replace_filter($title) || replace_filter($blog_name)) {
		tb_xml_error ("The trackback content contains some words that are not welcomed on our site. You may edit your post and send it again. Sorry for the inconvenience.");
	}

    $trytb=$DMF->numRows($DMF->query("SELECT * FROM ".$DBPrefix."trackbacks WHERE ip='".getip()."' AND postTime+30>='".time()."'"));
    if ($trytb > 0) {
		tb_xml_error ("Error.");
    } 

	$spam=$settingInfo['isTbApp']; // 1为开启审核

	if ($spam==0 or strpos($url,";".$settingInfo['ttSiteList'])>=1) {
		$isApp=1;
	} else {
		$isApp=0;
	}
	
	@fopen_url($url);
	$sql="INSERT INTO ".$DBPrefix."trackbacks (logId,tbTitle,blogSite,blogUrl,content,postTime,ip,isApp) VALUES ('$tid',\"$title\",\"$blog_name\",\"$url\",\"$excerpt\",".time().",'".getip()."','$isApp')";
	$DMF->query($sql);

	if ($isApp==1) {
		add_bloginfo("tbNums","adding",1);
		$DMF->query("UPDATE ".$DBPrefix."logs SET quoteNums=quoteNums+1 WHERE id='$tid'");
		settings_recache();
	}

	tb_xml_success();

function tb_xml_error($error) {
	header("Content-type:application/xml");
	echo "<?xml version=\"1.0\" ?>";
	print "<response><error>1</error><message>{$error}</message></response>";
	exit;
}

function tb_xml_success() {
	header("Content-type:application/xml");
	echo "<?xml version=\"1.0\" ?>";
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
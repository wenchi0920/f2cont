<?php  	
include_once("include/function.php");
include_once("include/cache.php");

//下载文件
$id=$_GET['id'];
if (is_numeric($id)){
	$attInfo=getRecordValue($DBPrefix."attachments"," id='$id' or name like '%$id'");
	if ($attInfo) {
		$file_path=$attInfo['name'];
		$filename=$attInfo['attTitle'];
		$filetime=$attInfo['postTime'];
		$fileType=$attInfo['fileType'];
		
		//UTF8中文名在Firefox下显示正常，但在IE下会出现乱码。
		$filename = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')>0) ? urlencode($filename) : $filename;

		//非法盗连
		if ($settingInfo['downcode']==1){
			$urlself=$_SERVER['HTTP_HOST'];
			$referer=empty($_SERVER['HTTP_REFERER'])?"":$_SERVER['HTTP_REFERER'];
			if (strpos(";$referer",$urlself)<1) {
			?>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta http-equiv="Content-Language" content="UTF-8" />
			<meta http-equiv="pragma" content="no-cache">
			<meta http-equiv="refresh" content="3; url=index.php?load=read&amp;id=<?php echo $attInfo['logId']?>"> 
			<title><?php echo $strDownloadNoValid?></title>
			</head>
			<?php 
				echo "<p align=center><font color=\"red\" size=\"5\"><b>$strDownloadNoValidInfo</b></font></p>";
				exit;
			}
		}

		//更新下载量
		$modify_sql="UPDATE ".$DBPrefix."attachments set downloads=downloads+1 WHERE id='$id'";
		$DMC->query($modify_sql);

		//更新附件Cache
		download_recache();
		attachments_recache();

		//网址直接输出地址
		if (strpos($file_path,"://")>0) {
			ob_end_clean();
			header("location:$file_path");
			exit;
		}else{
			$file_path="attachments/".$file_path;
			//读取文件内容
			if (file_exists($file_path)){		
				//读取内容
				$temp_buffer=readfromfile($file_path);
				ob_end_clean();
				header('Cache-control: max-age=31536000');
				header('Expires: '.gmdate('D, d M Y H:i:s', $filetime + 31536000).' GMT');
				header('Content-Encoding: none');

				if(preg_match("/^image\/.+/", $fileType)) {
					header('Content-Disposition: inline; filename='.$filename);
				} else {
					header('Content-Disposition: attachment; filename='.$filename);
				}
				header("Content-type: $fileType");

				echo $temp_buffer;
				exit;
			}
		} 
	}
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="refresh" content="3; url=index.php?load=read&amp;id=<?php echo $attInfo['logId']?>"> 
<title><?php echo $strDownloadNoValid?></title>
</head>
<script type="text/javascript">
alert("<?php echo $strFileNoExists?>");
location.href="index.php";
</script>

<? 	
	include_once("include/function.php");



	//下载文件
	$id=$_GET['id'];
	$attInfo=getRecordValue($DBPrefix."attachments"," id='$id' or name like '%$id'");
	if ($attInfo) {
		$file_name=$attInfo['name'];
		$file_path="attachments/".$file_name;
		$filename=$attInfo['attTitle'];
		$filename=mb_convert_encoding($filename,'GBK','UTF-8');

		//非法盗连
		$urlself=$_SERVER['HTTP_HOST'];
		$referer=$_SERVER['HTTP_REFERER'];
		if (strpos($referer,$urlself)<1) {
		?>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="UTF-8" />
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="refresh" content="3; url=index.php?load=read&id=<?=$attInfo['logId']?>"> 
		<title><?=$strDownloadNoValid?></title>
		</head>
		<?
			echo "<p align=center><font color=red size=5><b>$strDownloadNoValidInfo</b></font></p>";
			exit;
		}

		//读取文件内容
		if (file_exists($file_path)){
			$file_handle=fopen($file_path,"r");
			$file_size=filesize($file_path);
			$temp_buffer=fread($file_handle,$file_size);
			fclose($file_handle);
			
			//更新下载量
			$modify_sql="UPDATE ".$DBPrefix."attachments set downloads=downloads+1 WHERE id='$id'";
			$DMF->query($modify_sql);

			header("Content-type: application/zip");
			header("Content-disposition: attachment; filename=$filename");
			echo $temp_buffer;
			exit;
		} else {
			echo "<script language=\"Javascript\"> \n";
			echo "alert(\"$strFileNoExists\");";
			echo "</script>";
		}
	}else{
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$strFileNoExists\");";
		echo "</script>";
	}

?>
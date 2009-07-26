<?php 
require_once("function.php");

check_login();

$mark_id=intval($_GET['mark_id']);
$editorcode=$_GET['editorcode'];

$editor_language_title="插入远程文件";
$editor_language_height="高";
$editor_language_width="宽";
$editor_language_widthheight="尺寸";
$editor_language_size="大小(可选)";
$editor_language_homepage="远程网址";
$editor_language_remark="文件介绍";
$editor_language_submit="插入";
$editor_language_blank="不能为空";
$editor_language_error="错误的附件名称，不允许是php,asp,cig,asp等程序文件";
$editor_language_numerror="高/宽/大小必须输入数字";

if ($settingInfo['language']=="zh_tw"){
	$editor_language_title="插入遠程文件";
	$editor_language_height="高";
	$editor_language_width="寬";
	$editor_language_widthheight="尺寸";
	$editor_language_size="大小(可選)";
	$editor_language_homepage="遠程網址";
	$editor_language_remark="文件介紹";
	$editor_language_submit="插入";
	$editor_language_blank="不能為空";
	$editor_language_error="錯誤的附件名稱，不允許是php,asp,cgi,asp等程式文件";
	$editor_language_numerror="高/寬/大小必須輸入數字";

}elseif ($settingInfo['language']=="en"){
	$editor_language_title="Insert into remote";
	$editor_language_height="Height";
	$editor_language_width="Width";
	$editor_language_widthheight="Width/Height";
	$editor_language_size="Size";
	$editor_language_homepage="Remote Address";
	$editor_language_remark="File Remark";
	$editor_language_submit="Insert";
	$editor_language_blank=" isn't empty!";
	$editor_language_error="attachment is error, it isn't is php,asp,cgi,asp file";
	$editor_language_numerror="Height/Width/Size must be number!";

}

//保存到attachments表中
if (!empty($_GET['action']) && $_GET['action']=="save"){
	$remotepath=$_POST['remotepath'];
	$remoteremark=htmlspecialchars($_POST['remoteremark'], ENT_QUOTES);
	$remotewidth=$_POST['remotewidth'];
	$remoteheight=$_POST['remoteheight'];
	$remotesize=$_POST['remotesize'];
	
	$checkinfo=1;
	$file_type=getFileType($remotepath);
	if (strpos(";php|phtml|php3|jsp|exe|dll|asp|aspx|asa|cgi|fcgi|pl",$file_type)>0 || strpos($file_type,"?")>0){
		$ActionMessage=$editor_language_error;
		$checkinfo=0;
	}

	if ($checkinfo==1 && !empty($remotewidth) && !is_numeric($remotewidth)){
		$ActionMessage=$editor_language_numerror;
		$checkinfo=0;
	}

	if ($checkinfo==1 && !empty($remoteheight) && !is_numeric($remoteheight)){
		$ActionMessage=$editor_language_numerror;
		$checkinfo=0;
	}

	if ($checkinfo==1 && !empty($remotesize) && !is_numeric($remotesize)){
		$ActionMessage=$editor_language_numerror;
		$checkinfo=0;
	}
	
	//保存到数据库
	if ($checkinfo==1){
		$remoteremark=$remoteremark.".".$file_type;
		$remote_type=convertFileType($file_type);
		if (!empty($remotesize)) $remotesize=$remotesize*1000;

		//写进数据库
		$rsexits=getFieldValue($DBPrefix."attachments","name='".$remotepath."' and fileType='".$remote_type."' and logId=''","name");
		if ($rsexits==""){

			$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime) VALUES ('$remotepath','$remoteremark','$remote_type','$remotesize','$remotewidth','$remoteheight','".time()."')";			
			$DMC->query($sql);

			//返回文件区
			echo "<script language=javascript> \n";
			echo " parent.location.href='attach.php?editorcode=$editorcode&mark_id=$mark_id';\n";		
			echo " parent.reload;\n";
			echo " parent.TB_remove();\n";
			echo "</script> \n";
		}else{
			$ActionMessage=$strDataExists;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $editor_language_title;?></title>
<style type="text/css">
<!--
body {
	font-size: 12px;
}
-->
</style>
<script type="text/javascript">
	function onclick_update(form) {
		if (document.form1.remoteremark.value=="") {
			alert("<?php echo $editor_language_remark.$editor_language_blank;?>");
			document.form1.remoteremark.focus();
			return false;
		}

		if (document.form1.remotepath.value=="" || document.form1.remotepath.value=="http://") {
			alert("<?php echo $editor_language_homepage.$editor_language_blank;?>");
			document.form1.remotepath.focus();
			return false;
		}

		if (document.form1.remotewidth.value=="" && (new RegExp("\\.(gif|jpe?g|png|swf)$", "gi").exec(document.form1.remotepath.value))) {
			alert("<?php echo $editor_language_width.$editor_language_blank;?>");
			document.form1.remotewidth.focus();
			return false;
		}

		if (document.form1.remoteheight.value=="" && (new RegExp("\\.(gif|jpe?g|png|swf)$", "gi").exec(document.form1.remotepath.value))) {
			alert("<?php echo $editor_language_height.$editor_language_blank;?>");
			document.form1.remoteheight.focus();
			return false;
		}

		if (new RegExp("\\.(php|jsp|asp|pl|cgi)", "gi").exec(document.form1.remotepath.value)) {
			alert("<?php echo $editor_language_error;?>");
			document.form1.remotepath.focus();
			return false;
		}

		document.form1.save.disabled = true;
		document.form1.action = "<?php echo "$PHP_SELF?editorcode=$editorcode&mark_id=$mark_id&action=save"?>";
		document.form1.submit();		
	}

	//建立一个图像对象
	var ImgObj=new Image();	
	function getImageSize(imgpath){
		if (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(imgpath)){
			ImgObj.src=imgpath;
			//alert(ImgObj.readyState);
			//if(ImgObj.readyState!="complete") {//如果图像是未加载完成进行循环检测
			//	setTimeout(getImageSize(imgpath),500);
			//	return false;
			//}
			document.form1.remotewidth.value=ImgObj.width;
			document.form1.remoteheight.value=ImgObj.height;
			if (ImgObj.fileSize>0){
				document.form1.remotesize.value=Math.round(ImgObj.fileSize/1024*100)/100;
			}else{
				document.form1.remotesize.value=0;
			}
		}
	}

	//ImgObj.onerror=function(){alert('\n图片格式不正确或者图片已损坏!');}

	<?php 
	if (!empty($ActionMessage)){
		echo "alert('$ActionMessage')";
	}
	?>
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="480" border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="90" nowrap><?php echo $editor_language_remark;?></td>
      <td width="390">
		<input type="text" name="remoteremark" value="" size="50" style="width:350px;">
	  </td>
    </tr>
    <tr>
      <td width="90"><?php echo $editor_language_homepage;?></td>
      <td width="390">
		<input type="text" name="remotepath" value="http://" size="50" style="width:350px;" onchange="getImageSize(this.value)" />
	  </td>
    </tr>
    <tr>
      <td width="90"><?php echo $editor_language_widthheight;?></td>
      <td width="390">
		<?php echo $editor_language_width;?> <input type="text" name="remotewidth" size="4" style="width:50px;">
		<?php echo $editor_language_height;?> <input type="text" name="remoteheight" size="4" style="width:50px;"> &nbsp;
		<?php echo $editor_language_size;?> <input type="text" name="remotesize" size="4" style="width:50px;"> KB &nbsp;
		<input type="button" name="save" id="save" value="<?php echo $editor_language_submit;?>" onClick="Javascript:onclick_update(this.form)"/>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>
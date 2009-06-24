<?php
//语言包
include_once("../../cache/cache_setting.php");

$editor_language_title="插入音乐文件";
$editor_language_height="高";
$editor_language_width="宽";
$editor_language_widthheight="尺寸";
$editor_language_size="大小";
$editor_language_homepage="音乐网址";
$editor_language_remark="音乐介绍";
$editor_language_submit="插入";

if ($settingInfo['language']=="zh_tw"){
	$editor_language_title="插入音樂文件";
	$editor_language_height="高";
	$editor_language_width="寬";
	$editor_language_widthheight="尺寸";
	$editor_language_size="大小";
	$editor_language_homepage="音樂網址";
	$editor_language_remark="音樂介紹";
	$editor_language_submit="插入";
}elseif ($settingInfo['language']=="en"){
	$editor_language_title="Insert into music";
	$editor_language_height="Height";
	$editor_language_width="Width";
	$editor_language_widthheight="Width/Height";
	$editor_language_size="Size";
	$editor_language_homepage="Music Address";
	$editor_language_remark="Music Remark";
	$editor_language_submit="Insert";
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
	function insert_musiccode() {
		var code='[musicBegin]';
		if (document.form1.musicpath.value!="") {
			code = code + document.form1.musicpath.value + '|';
		}else{
			code = code + '<?php echo $editor_language_homepage;?>' + '|';
		}
		if (document.form1.musicremark.value!="") {
			code = code + document.form1.musicremark.value + '|';
		}else{
			code = code + '<?php echo $editor_language_remark;?>' + '|';
		}
		if (document.form1.musicwidth.value!="") {
			code = code + document.form1.musicwidth.value + '|';
		}else{
			code = code + '<?php echo $editor_language_width;?>' + '|';
		}
		if (document.form1.musicheight.value!="") {
			code = code + document.form1.musicheight.value + '|';
		}else{
			code = code + '<?php echo $editor_language_height;?>'
		}

		code = code + '[musicEnd]';
		
		parent.parent.AddText(code);
		parent.TB_remove();
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="480" border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="100" nowrap><?php echo $editor_language_remark;?></td>
      <td width="300">
		<input type="text" name="musicremark" value="" size="50">
	  </td>
    </tr>
    <tr>
      <td width="100"><?php echo $editor_language_homepage;?></td>
      <td width="300">
		<input type="text" name="musicpath" value="http://" size="50">
	  </td>
    </tr>
    <tr>
      <td width="100"><?php echo $editor_language_widthheight;?></td>
      <td width="300">
		<?php echo $editor_language_width;?> <input type="text" name="musicwidth" size="4">
		<?php echo $editor_language_height;?> <input type="text" name="musicheight" size="4"> &nbsp;&nbsp;
		<input type="button" name="Submit" value="<?php echo $editor_language_submit;?>" onclick="insert_musiccode()"/>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flv Insert</title>
<style type="text/css">
<!--
body {
	font-size: 12px;
}
-->
</style>
<script type="text/javascript">
	function insert_flvcode() {
		var code='[flvBegin]';
		if (document.form1.flvpath.value!="") {
			code = code + document.form1.flvpath.value + '|';
		}else{
			code = code + 'Flv文件网址' + '|';
		}
		if (document.form1.flvremark.value!="") {
			code = code + document.form1.flvremark.value + '|';
		}else{
			code = code + 'Flv文件介绍' + '|';
		}
		if (document.form1.flvwidth.value!="") {
			code = code + document.form1.flvwidth.value + '|';
		}else{
			code = code + '宽' + '|';
		}
		if (document.form1.flvheight.value!="") {
			code = code + document.form1.flvheight.value + '|';
		}else{
			code = code + '高'
		}
		code = code + '[flvEnd]';
		
		parent.parent.AddText(code);
		parent.TB_remove();
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="480" border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="100" nowrap>Flv文件介绍</td>
      <td width="300">
		<input type="text" name="flvremark" value="" size="50">
	  </td>
    </tr>
    <tr>
      <td width="100">Flv文件网址</td>
      <td width="300">
		<input type="text" name="flvpath" value="http://" size="50">
	  </td>
    </tr>
    <tr>
      <td width="100">尺寸</td>
      <td width="300">
		宽 <input type="text" name="flvwidth" size="4"> &nbsp;&nbsp;
		高 <input type="text" name="flvheight" size="4"> &nbsp;&nbsp;<input type="button" name="Submit" value="提交" onclick="insert_flvcode()"/>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cool Code</title>
<style type="text/css">
<!--
body {
	font-size: 12px;
}
-->
</style>
<script type="text/javascript">
	function insert_coolcode() {
		var htmlcode = convert_html(form1.codehtml.value);

		var code='&lt;coolcode lang="';
		code = code + form1.lang.value + '"';
		if (form1.linenum.checked==true) code = code + 'linenum="off"';
		if (form1.down.value!="") code = code + 'download="' + form1.down.value + '"';
		code = code + '&gt;\n' + htmlcode + '&lt;/coolcode&gt;';
		
		self.opener.parent.AddText(code);
		self.close();
	}

	function convert_html(html){		
		html = html.replace(/</g,'&lt;');
		html = html.replace(/>/g,'&gt;');
		html = html.replace(/\n/g, '<br />');
		return html;
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="480" border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="51" nowrap>语言</td>
      <td width="401">
		<select name="lang">
			<option value="php">php</option>
			<option value="html">html</option>
			<option value="java script">java script</option>
			<option value="css">css</option>
			<option value="sql">sql</option>
			<option value="xml">xml</option>
			<option value="java">java</option>
			<option value="mysql">mysql</option>
			<option value="perl">perl</option>
			<option value="python">python</option>
			<option value="ruby">ruby</option>
			<option value="cpp">cpp</option>
			<option value="diff">diff</option>
			<option value="actionscript">actionscript</option>
        </select>
	  </td>
    </tr>
    <tr>
      <td width="51">行号</td>
      <td width="401">
		<input type="checkbox" name="linenum" value="off">不显示行号（默认为显示行号）
	  </td>
    </tr>
    <tr>
      <td width="51">文件名</td>
      <td width="401">
		<input type="text" name="down">（下载文件名，格式：文件名.扩展名）可选项
	  </td>
    </tr>
    <tr>
      <td valign="top">代码</td>
      <td><textarea name="codehtml" cols="50" rows="12"></textarea></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><input type="button" name="Submit" value="提交" onclick="insert_coolcode()"/> <input type="reset" name="Submit2" value="重置" /></td>
    </tr>
  </table>
</form>
</body>
</html>

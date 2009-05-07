<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cool Code</title>
<style type="text/css">
<!--
body {
	font-size: 12px;
	margin-top: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script type="text/javascript">
	function insert_coolcode() {
		var htmlcode = convert_html(document.form1.codehtml.value);

		var code='&lt;coolcode lang="';
		code = code + document.form1.lang.value + '"';
		if (document.form1.linenum.checked==true) code = code + 'linenum="off"';
		if (document.form1.down.value!="") code = code + 'download="' + document.form1.down.value + '"';
		code = code + '&gt;\n' + htmlcode + '&lt;/coolcode&gt;';
		
		parent.parent.AddText(code);
		parent.TB_remove();
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
  <table width="450" border="0" cellpadding="0" cellspacing="3">
    <tr>
      <td width="190" nowrap>语言:
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
      <td width="186" nowrap="nowrap">文件名:
      <input name="down" type="text" size="12" /></td>
      <td width="116" nowrap="nowrap"><input type="checkbox" name="linenum" value="off" />
不显示行号</td>
    </tr>
    
    <tr>
      <td colspan="3" valign="top" nowrap="nowrap"><p>
        <textarea name="codehtml" style="width:450px; height:50px;"></textarea>
        <input type="button" name="Submit" value="提交" onclick="insert_coolcode()"/>
      </p>        </td>
    </tr>
  </table>
</form>
</body>
</html>

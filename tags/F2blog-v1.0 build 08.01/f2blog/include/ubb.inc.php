<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "ubb.inc.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

if (file_exists("skins/".$blogSkins."/UBB")){
	$ubb_path="skins/".$blogSkins."/UBB";
}else{
	$ubb_path="editor/ubb";
}
?>
<script language="javascript" type="text/javascript" src="editor/ubb/UBBCode.js"></script>
<script language="javascript" type="text/javascript" src="editor/ubb/UBBCode_help.js"></script>
<link rel="stylesheet" rev="stylesheet" href="<?=$ubb_path?>/editor.css" type="text/css" media="all" />
<!--UBB编辑器代码-->
<div id="editorbody">
  <div id="editorHead">
    <div class="editorTools">
      <div class="Toolsbar">
        <ul class="ToolsUL">
          <li><a id="f_bold" href="javascript:UBB_bold();" title="<?=$strUBBBlod?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/bold.gif" border="0" alt="<?=$strUBBBlod?>" /></a></li>
          <li><a id="f_italic" href="javascript:UBB_italic();" title="<?=$strUBBItalic?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/italic.gif" border="0" alt="<?=$strUBBItalic?>" /></a></li>
          <li><a id="f_underline" href="javascript:UBB_underline();" title="<?=$strUBBUnderline?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/underline.gif" border="0" alt="<?=$strUBBUnderline?>" /></a></li>
        </ul>
      </div>
      <div class="Toolsbar">
        <ul class="ToolsUL">
          <li><a id="f_image" href="javascript:UBB_image();" title="<?=$strUBBImage?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/image.gif" border="0" alt="<?=$strUBBImage?>" /></a></li>
          <li><a id="f_link" href="javascript:UBB_link();" title="<?=$strUBBHomepage?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/link.gif" border="0" alt="<?=$strUBBHomepage?>" /></a></li>
          <li><a id="f_mail" href="javascript:UBB_mail();" title="<?=$strUBBEmail?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/mail.gif" border="0" alt="<?=$strUBBEmail?>" /></a></li>
          <li><a id="f_quote" href="javascript:UBB_quote();" title="<?=$strUBBQuote?>" class="Toolsbutton"><img src="<?=$ubb_path?>/Icons/quote.gif" border="0" alt="<?=$strUBBQuote?>" /></a></li>
        </ul>
      </div>
      <div style="clear: both;display: block;height:1px;overflow:hidden"></div>
    </div>
  </div>
  <div class="editorContent">
    <textarea name="message" onkeydown="quickpost(event)" class="editTextarea" style="height:150px;;" cols="1" rows="1" accesskey="R"><?=($_POST['message'])?$_POST['message']:$message?>
</textarea>
  </div>
</div>
<script language="javascript" type="text/javascript">initUBB("message")</script>

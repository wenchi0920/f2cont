<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>
<script language="javascript" type="text/javascript" src="editor/ubb/UBBCode.js"></script>
<script language="javascript" type="text/javascript" src="editor/ubb/UBBCode_help_<?php echo $settingInfo['language']?>.js"></script>
<?php 
include(F2BLOG_ROOT."/cache/cache_modulesSetting.php");
if (!empty($plugins_smilies) && is_array($plugins_smilies)){
?>
<!--UBB编辑器代码-->
<div id="UBBSmiliesPanel" class="UBBSmiliesPanel">
  <table cellspacing="2" cellpadding="0">
	<?php 
	$i=0;
	foreach($plugins_smilies as $key=>$value){
		$smilery_image="<img src=\"plugins/smilies/smilies/$value\" border=\"0\" alt=\"\" />";
		if ($i==0) echo ($key==0)?"<tr>\n":"</tr><tr>\n";
		echo "<td><a href=\"javascript:AddSmiley('[$key]')\" class=\"Smilie\" title=\"[$key]\">$smilery_image</a></td>\n";
		$i++;
		if ($i>7) $i=0;
	}
	if ($i>0) echo "</tr>\n";
	?>
  </table>
</div>
<?php }//END表情符号?>
<?php if ($settingInfo['showUBB']==1 || !empty($_POST['message']) || !empty($message)){?>
<div id="editorbody">
  <div id="editorHead">
    <div class="editorTools">
      <div class="Toolsbar">
        <ul class="ToolsUL">
          <li><a id="f_bold" href="javascript:UBB_bold();" title="<?php echo $strUBBBlod?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/bold.gif" border="0" alt="<?php echo $strUBBBlod?>" /></a></li>
          <li><a id="f_italic" href="javascript:UBB_italic();" title="<?php echo $strUBBItalic?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/italic.gif" border="0" alt="<?php echo $strUBBItalic?>" /></a></li>
          <li><a id="f_underline" href="javascript:UBB_underline();" title="<?php echo $strUBBUnderline?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/underline.gif" border="0" alt="<?php echo $strUBBUnderline?>" /></a></li>
        </ul>
      </div>
      <div class="Toolsbar">
        <ul class="ToolsUL">
          <li><a id="f_image" href="javascript:UBB_image();" title="<?php echo $strUBBImage?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/image.gif" border="0" alt="<?php echo $strUBBImage?>" /></a></li>
          <li><a id="f_link" href="javascript:UBB_link();" title="<?php echo $strUBBHomepage?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/link.gif" border="0" alt="<?php echo $strUBBHomepage?>" /></a></li>
          <li><a id="f_mail" href="javascript:UBB_mail();" title="<?php echo $strUBBEmail?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/mail.gif" border="0" alt="<?php echo $strUBBEmail?>" /></a></li>
          <li><a id="f_quote" href="javascript:UBB_quote();" title="<?php echo $strUBBQuote?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/quote.gif" border="0" alt="<?php echo $strUBBQuote?>" /></a></li>
		  <?php if (!empty($smilery_image)){?>
          <li><a id="f_smiley" href="javascript:UBB_smiley();" title="<?php echo $strUBBSmilery?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/smiley.gif" border="0" alt="<?php echo $strUBBSmilery?>" /></a></li>
		  <?php }?>
        </ul>
      </div>
      <div style="clear: both;display: block;height:1px;overflow:hidden"></div>
    </div>
  </div>
<?php }else{?>
<textarea id="editMask" class="editTextarea" style="width:99%;height:100px" onfocus="showUBB('Message')"></textarea>
<div id="editorbody" style="display:none">
   <div id="editorHead"></div>
   <script>var ubbTools='<div class="editorTools"><div class="Toolsbar"><ul class="ToolsUL"><li><a id="f_bold" href="javascript:UBB_bold();" title="<?php echo $strUBBBlod?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/bold.gif" border="0" alt="<?php echo $strUBBBlod?>" /></a></li><li><a id="f_italic" href="javascript:UBB_italic();" title="<?php echo $strUBBItalic?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/italic.gif" border="0" alt="<?php echo $strUBBItalic?>" /></a></li><li><a id="f_underline" href="javascript:UBB_underline();" title="<?php echo $strUBBUnderline?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/underline.gif" border="0" alt="<?php echo $strUBBUnderline?>" /></a></li></ul></div><div class="Toolsbar"><ul class="ToolsUL"><li><a id="f_image" href="javascript:UBB_image();" title="<?php echo $strUBBImage?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/image.gif" border="0" alt="<?php echo $strUBBImage?>" /></a></li><li><a id="f_link" href="javascript:UBB_link();" title="<?php echo $strUBBHomepage?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/link.gif" border="0" alt="<?php echo $strUBBHomepage?>" /></a></li><li><a id="f_mail" href="javascript:UBB_mail();" title="<?php echo $strUBBEmail?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/mail.gif" border="0" alt="<?php echo $strUBBEmail?>" /></a></li><li><a id="f_quote" href="javascript:UBB_quote();" title="<?php echo $strUBBQuote?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/quote.gif" border="0" alt="<?php echo $strUBBQuote?>" /></a></li><?php if (!empty($smilery_image)){?><li><a id="f_smiley" href="javascript:UBB_smiley();" title="<?php echo $strUBBSmilery?>" class="Toolsbutton"><img src="<?php echo $ubb_path?>/Icons/smiley.gif" border="0" alt="<?php echo $strUBBSmilery?>" /></a></li><?php }?></ul></div><div style="clear: both;display: block;height:1px;overflow:hidden"></div></div>'</script>
<?php }?>
  <div class="editorContent">
	<?php
		if (!empty($_POST['message'])) $message=$_POST['message'];
		if (empty($message)) $message="";
	?>
    <textarea name="message" onkeydown="quickpost(event)" class="editTextarea" style="height:150px;" cols="1" rows="1" accesskey="R"><?php echo $message?></textarea>
  </div>
</div>
<script language="javascript" type="text/javascript">initUBB("message")</script>

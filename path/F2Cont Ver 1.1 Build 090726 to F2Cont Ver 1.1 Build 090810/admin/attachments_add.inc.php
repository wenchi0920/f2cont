<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
if (empty($editorcode)) require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$edit_url&action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <div id="content">

	<div class="contenttitle"><?php echo $title?> <br /><span style="font-size:12px">(<?php echo $strAttachmentRemark?>,<?php echo $strAttType."<font color=red>".$settingInfo['attachType']."</font>";?>)</font></div>
	<br>
	<div class="subcontent">
	  <?php if ($ActionMessage!="") { ?>
	  <br>
	  <fieldset>
	  <legend>
	  <?php echo $strErrorInfo?>
	  </legend>
	  <div align="center">
		<table border="0" cellpadding="2" cellspacing="1">
		  <tr>
			<td><span class="alertinfo">
			  <?php echo $ActionMessage?>
			  </span></td>
		  </tr>
		</table>
	  </div>
	  </fieldset>
	  <br>
	  <?php  } ?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<?php for ($i=0;$i<10;$i++){?>
		<tr class="subcontent-input">
		  <td width="5%" nowrap>
			<?php echo $strPluginDesc?>:
		  </td>
		  <td width="30%">
			<span id="attachReamrk<?php echo $i;?>"><input type="text" name="remoteremark[]" id="remoteremark" value="" class="textbox" size="40"></span>
		  </td>
		  <td width="5%" nowrap>
			<input name="btnupload" id="btnupload<?php echo $i;?>" class="btn" type="button" value="<?php echo $strAttachmentUpload?>" onClick="document.getElementById('attachGrp<?php echo $i;?>').innerHTML='<input name=myfile[] id=myfile<?php echo $i;?> class=filebox type=file size=50>';document.getElementById('attachReamrk<?php echo $i;?>').innerHTML='<input name=myremark[] id=myremark class=textbox type=text size=40>';document.getElementById('btnupload<?php echo $i;?>').style.display='none';document.getElementById('btnremote<?php echo $i;?>').style.display='';document.seekform.myfile<?php echo $i;?>.focus();">
			<input name="btnremote" id="btnremote<?php echo $i;?>" class="btn" type="button" value="<?php echo $strAttachmentRemote?>" onClick="document.getElementById('attachGrp<?php echo $i;?>').innerHTML='<input type=text name=remotepath[] id=remotepath class=textbox size=60>';document.getElementById('attachReamrk<?php echo $i;?>').innerHTML='<input name=remoteremark[] id=remoteremark class=textbox type=text size=40>';document.getElementById('btnupload<?php echo $i;?>').style.display='';document.getElementById('btnremote<?php echo $i;?>').style.display='none';document.seekform.myfile<?php echo $i;?>.focus();" style="display:none">
			<?//php echo $strAttachmentsUrl?> 			
		  </td>
		  <td width="60%">
			<span id="attachGrp<?php echo $i;?>"><input type="text" name="remotepath[]" id="remotepath" value="" class="textbox" size="60"></span>
		  </td>
		</tr>
		<?php }?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
	  <?php if ($editorcode!=""){?>
      &nbsp;&nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strAttachmentReturn?>" onclick="opener.location.href='<?php echo "attach.php?editorcode=$editorcode&mark_id=$mark_id"?>';opener.reload;window.close();">
	  <?php }?>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php if (empty($editorcode)) dofoot(); ?>

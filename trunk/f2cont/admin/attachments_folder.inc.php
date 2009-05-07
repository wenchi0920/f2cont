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
	form.action = "<?php echo "$edit_url&action=savefolder"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <div id="content">

	<div class="contenttitle"><?php echo $title?></div>
	<br>
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
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
		<tr class="subcontent-input">
		  <td width="5%" nowrap>
			<?php echo $strAttachmentUpFolder.":&nbsp;&nbsp;".substr($basedir,3)?>
		  </td>
		  <td width="10%" nowrap>&nbsp;</td>
		  <td width="5%" nowrap>
			<?php echo $title?>
		  </td>
		  <td width="60%">
			<INPUT TYPE="text" NAME="myfolder" value="" class="textbox" size="40">
		  </td>
		</tr>
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

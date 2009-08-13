<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
if (empty($editorcode)) require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	if (isNull(form.fileTitle, '<?php echo $strErrNull?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$edit_url&action=savefile"?>";
	form.submit();
}

function onclick_upload(){
	document.getElementById('attachGrp').innerHTML='<input name="myfile" id="myfile" class="filebox" type="file" size="60">';
	document.getElementById('btngroups').style.display='none';
	document.getElementById('btnreset').style.display='';
	document.getElementById('fileSize').style.display='none';
	document.getElementById('fileWidth').style.display='none';
	document.getElementById('fileHeight').style.display='none';
	document.seekform.myfile.focus();
}

function onclick_return(){
	document.getElementById('attachGrp').innerHTML='<INPUT TYPE="text" NAME="fileName" class="textbox" size="60">';
	document.getElementById('btngroups').style.display='';
	document.getElementById('btnreset').style.display='none';
	document.getElementById('fileSize').style.display='';
	document.getElementById('fileWidth').style.display='';
	document.getElementById('fileHeight').style.display='';
	document.seekform.fileName.value=document.seekform.oldfile.value;
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
		  <td width="10%" nowrap>
			<?php echo $strAttachmentsOrderNo?>
		  </td>
		  <td width="90%">
			<INPUT TYPE="text" NAME="file_id" value="<?php echo $file_id?>" class="textbox" readonly size="60">
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strAttachmentsName?>
		  </td>
		  <td width="90%">
			<span id="attachGrp"><INPUT TYPE="text" NAME="fileName" value="<?php echo $fileName?>" class="textbox" size="60"></span>
			<input name="btngroups" id="btngroups" class="btn" type="button" id="save" value="<?php echo $strAttachmentUpload?>" onClick="onclick_upload();">
			<input name="btnreset" id="btnreset" class="btn" type="button" id="save" value="<?php echo $strReturn?>" onClick="onclick_return()" style="display:none">
			<INPUT TYPE="hidden" NAME="oldfile" value="<?php echo $fileName?>">
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strAttachmentsRemark?>
		  </td>
		  <td width="90%">
			<INPUT TYPE="text" NAME="fileTitle" value="<?php echo $fileTitle?>" class="textbox" size="60">
		  </td>
		</tr>
		<tr class="subcontent-input" id="fileSize">
		  <td width="10%" nowrap>
			<?php echo $strAttachmentsSize?>
		  </td>
		  <td width="90%">
			<INPUT TYPE="text" NAME="fileSize" value="<?php echo $fileSize?>" class="textbox" size="60"> Byte
		  </td>
		</tr>
		<tr class="subcontent-input" id="fileWidth">
		  <td width="10%" nowrap>
			<?php echo $strAttachmentWidth?>
		  </td>
		  <td width="90%">
			<INPUT TYPE="text" NAME="fileWidth" value="<?php echo $fileWidth?>" class="textbox" size="60">
		  </td>
		</tr>
		<tr class="subcontent-input" id="fileHeight">
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strAttachmentHeight?>
		  </td>
		  <td width="90%">
			<INPUT TYPE="text" NAME="fileHeight" value="<?php echo $fileHeight?>" class="textbox" size="60">
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
	</div>

  </div>
</form>
<?php if (empty($editorcode)) dofoot(); ?>

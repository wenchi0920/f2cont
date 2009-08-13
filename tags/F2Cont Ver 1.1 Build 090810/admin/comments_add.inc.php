<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form,subaction) {
	if (isNull(form.logContent, '<?php echo $strErrNull?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	if (subaction=="edit"){
		form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=save"?>";
	}else{
		<?php if (isset($logId)){?>
		form.action = "<?php echo "$edit_url&mark_id=$mark_id&logId=$logId&action=reply"?>";
		<?php }else{?>
		form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=reply"?>";
		<?php }?>
	}
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $title?></div>
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
		<?php if (!empty($reply_content)){?>
		<tr class="subcontent-input">
		  <td width="100%">
			<?php echo $reply_content?>
		  </td>
		</tr>  
		<?php }?>
		<tr class="subcontent-input">
		  <td width="100%" nowrap class="input-titleblue">			
			<?php include("ubb.inc.php")?>
			<textarea name="logContent" id="logContent" cols="10" rows="15" style="overflow:auto;font-size:10pt;width:100%;"  onkeydown="quickpost(event)" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" tabindex="2"><?php echo !empty($logContent)?$logContent:""?></textarea>
		  </td>
		</tr>                
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form,'<?php echo $action?>')">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

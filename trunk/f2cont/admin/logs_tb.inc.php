<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	if (isNull(form.quoteUrl, '<?php echo $strErrNull?>')) return false;
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$edit_url&action=sendtb&mark_id=$mark_id"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
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
		  <td nowrap>
			<?php echo $strQuoteUrl?>
			&nbsp;<span style="color:#999">
			<?php echo $strQuoteAlt?>
			</span> </td>
		</tr>
		<tr>
		  <td>
			<textarea name="quoteUrl" rows="5" cols="80" style="width: 100%"><?php echo empty($quoteUrl)?"":$quoteUrl; ?></textarea>
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
<?php  dofoot(); ?>

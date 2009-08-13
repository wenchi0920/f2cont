<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	if (isNull(form.name, '<?php echo $strErrNull?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=save"?>";
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
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strlinkgroupName?>
		  </td>
		  <td width="90%">
			<input name="name" id="name" class="textbox" type="TEXT" size=50 maxlength=20 value="<?php echo isset($name)?$name:""?>">
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strLinkIsSidebar?>
		  </td>
		  <td width="90%" style="padding-top:7px">
			<input type="radio" name="isSidebar" value="1" <?php echo (!isset($isSidebar) || $isSidebar!="0")?"checked":""?>> <?php echo $strYes?> 
			<input type="radio" name="isSidebar" value="0" <?php echo (isset($isSidebar) && $isSidebar=="0")?"checked":""?>> <?php echo $strNo?>
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

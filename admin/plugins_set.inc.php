<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>

<script style="javascript">
<!--
function onclick_update(form) {
	<?php  for ($y=0;$y<count($fieldCheck);$y++) { ?>
		if (isNull(form.<?php echo $fieldCheck[$y]?>, '<?php echo $strErrNull?>')) return false;
	<?php }?>

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$PHP_SELF?plugin=$plugin&action=setSave"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <div id="content">

	<div class="contenttitle"><?php echo $title?></div>
	<br>
	<?php  if ($ActionMessage!="") { ?>
	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
	  <tr>
		<td>
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
		</td>
	  </tr>
	</table>
	<?php  } ?>
	<div class="subcontent">
	<?php echo $setCode?>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo $PHP_SELF?>'">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$edit_url&action=saveorder"?>";
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
		<tr class="subcontent-title">
		  <td width="20%">
			<?php echo $strLinksName?>
		  </td>
		  <td width="80%">
			<?php echo $strLinksLinkUrl?>
		  </td>
		</tr>
	  </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="tbCondition">
		<?php for ($i=0;$i<count($arr_parent);$i++){?>
		<tr onClick="SelectRow(event)">
		  <td class="subcontent-td" width="20%">
			<input type="hidden" name="arrid[]" value="<?php echo $arr_parent[$i]['id']?>">
			<?php echo $arr_parent[$i]['name']?>
		  </td>
		  <td class="subcontent-td" width="80%">
			<a href="<?php echo $arr_parent[$i]['blogUrl']?>" target="_blank"><?php echo $arr_parent[$i]['blogUrl']?></a>
		  </td>
		</tr>
		<?php }?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="moveup" class="btn" type="button" id="moveup" value="<?php echo $strMoveUp?>" onClick="Move(-1,2)" disabled>
	  &nbsp;
	  <input name="movedown" class="btn" type="button" id="movedown" value="<?php echo $strMoveDown?>" onClick="Move(1,2)" disabled>
	  &nbsp;<font color=red>(<?php echo $strMoveDemo?>)</font>&nbsp;&nbsp;
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

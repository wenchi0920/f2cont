<?php 
@set_time_limit(0);

require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strRssExport;

//输出头部信息
dohead($strRssExport,"");
require('admin_menu.php');

if (!function_exists("gzopen")) { 
	$isgzip="0";
	$gzopen="0";
	$strBackupOption=$strBackupOption3;
} else {
	$isgzip="1";
	$gzopen="1";
	$strBackupOption=$strBackupOption1;
}
$ActionMessage=!empty($_SESSION['rssMessage'])?$_SESSION['rssMessage']:"";
?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.backup, '<?php echo $strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?php echo "rss_export.inc.php?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $strRssExport?></div>
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
		  <td class="subcontent-td" align="right" nowrap>
			<?php echo $strCategory?>
		  </td>
		  <td class="subcontent-td">
			&nbsp;&nbsp;<?php  category_select("rssCate","","class=\"searchbox\"",""); ?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td" align="right" nowrap>
			<?php echo $strDataBackupName?>
		  </td>
		  <td class="subcontent-td">
			&nbsp;&nbsp;<input name="backup" type="text" size="45" maxlength="30" value="<?php echo format_time("Y-m-d",time())."-".md5(time())?>">
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td" align="right" nowrap>
			<?php echo $strBackupFileFormat?>
		  </td>
		  <td class="subcontent-td">
			&nbsp;<input type=radio name='isgzip' value="1" <?php echo ($isgzip==1)?"checked":""?> <?php echo ($gzopen=="0")?"disabled":""?>>&nbsp;<?php echo $strBackupOption?>&nbsp;&nbsp;
			<input type=radio name='isgzip' value="0" <?php echo ($isgzip==0)?"checked":""?>>&nbsp;<?php echo $strBackupOption2?>
			&nbsp;&nbsp;&nbsp;
			<?php echo $strRssExportOption1?>&nbsp;<input name="filesize" type="text" size="2" maxlength="3" value="80"> <?php echo $strRssExportOption2?>
		  </td>
		</tr>

	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strRssExport?>" onClick="Javascript:onclick_update(this.form)">
	</div>
  </div>
</form>
<?php  $_SESSION['rssMessage']=""; 
dofoot(); ?>
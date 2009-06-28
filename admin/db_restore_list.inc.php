<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $strRestore?></div>
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
	  <br />
	  <fieldset>
	  <legend><?php echo $strErrorInfo?></legend>
	  <div <?php echo (!is_array($_SESSION['array_errorsql']))?"align=\"center\"":""?>><font color="red"><?php echo $ActionMessage?></font>
	  <?php 
	  //显示错误代码
	  if (is_array($_SESSION['array_errorsql'])){
		 echo "<font color=\"blue\">".count($_SESSION['array_errorsql'])." </font><font color=red>$strDataRestoreError</font>";
		 foreach($_SESSION['array_errorsql'] as $error=>$query){
			echo "<br /> <font color=\"red\">$strDataRestoreBadReason :</font> ".htmlspecialchars($error);
			echo "<br /> <font color=\"red\">$strDataRestoreBadSql :</font> ".htmlspecialchars($query)." <br>";
		 }
		 $_SESSION['array_errorsql']="";
	  }
	  ?>
	  </div>
	  </fieldset>
	  <br />
	  <?php  } ?>
	  <?php if ($action==""){$_SESSION['array_errorsql']="";//清空错误query缓存?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="5%" class="whitefont">&nbsp;</td>
		  <td width="36%" class="whitefont"><?php echo $strAttachmentsName?></td>
		  <td width="10%" class="whitefont" align="center"><?php echo $strBackupFileFormat?></td>
		  <td width="10%" class="whitefont" align="center"><?php echo $strDataRestoreTotUnit?></td>
		  <td width="9%" class="whitefont"><?php echo $strAttachmentsSize?></td>
		  <td width="14%" class="whitefont"><?php echo $strAttachmentsDate?></td>
		  <td width="16%" class="whitefont" align="center"><?php echo $strDownFile?></td>
		</tr>
		<?php foreach ($f2data_file as $key=>$value){?>
		<tr class="subcontent-input">
		  <td class="subcontent-td" align="center">
			<input type="radio" name="restorefile" value="<?php echo "{$value}|{$file_count[$value]}"?>" <?php echo ($key==0)?"checked=\"checked\"":""?> <?php echo (strpos($value,".zip")>0 && !function_exists('gzopen'))?"disabled=\"disabled\"":""?>/>&nbsp;
		  </td>
		  <td class="subcontent-td"><?php echo $value?></td>
		  <td class="subcontent-td" align="center"><?php echo (strpos($value,".zip")>0)?$strBackupOption4:$strBackupOption2?></td>
		  <td class="subcontent-td" align="center"><?php echo $file_count[$value]?></td>
		  <td class="subcontent-td"><?php echo formatFileSize($file_size[$value])?></td>
		  <td class="subcontent-td"><?php echo format_time("L",$file_time[$value])?></td>
		  <td class="subcontent-td" align="center">
		  <?php 
		  if($file_count[$value]>1) {
			  echo "<a href=\"$PHP_SELF?action=downlist&filename={$value}&amp;filecount={$file_count[$value]}\">$strDownFile</a>";
		  } else {
			  echo "<a href=\"$data_path/$value\"><img src=\"../images/download.gif\" border=\"0\" alt=\"\"/></a>";
		  }
		  ?>
		  </td>
		</tr>
		<?php }?>
	  </table>			  
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strDataRestoreBegin?>" onclick="ConfirmDataOperation('<?php echo "$PHP_SELF?action=save"?>','<?php echo $strDataRestoreConfirm?>');" <?php echo (count($f2data_file)>0)?"":"disabled=\disabled\""?>/>
	  &nbsp;
	  <input name="del" class="btn" type="button" id="del" value="<?php echo $strDelete?>" onclick="ConfirmDataOperation('<?php echo "$PHP_SELF?action=delete"?>','<?php echo $strConfirmInfo?>');" <?php echo (count($f2data_file)>0)?"":"disabled=\disabled\""?>/>
	</div>
	<?php }?>
  </div>
</form>
<?php  dofoot(); ?>
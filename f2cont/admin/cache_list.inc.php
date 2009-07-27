<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
include('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $title?>
	</div>
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
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="5%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="20%" nowrap class="whitefont">
			<?php echo $strCacheName?>
		  </td>
		  <td width="25%" nowrap class="whitefont">
			<?php echo $strCacheModifyTime?>
		  </td>
		  <td width="24%" nowrap class="whitefont">
			<?php echo $strCacheSize?>
		  </td>
		</tr>
		<?php for ($i=0;$i<count($cachedb);$i++) {?>
		<tr class="<?php echo ($i%2==0)?"table_color1":"table_color2"?>" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $cachedb[$i]['name']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $cachedb[$i]['desc']?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $cachedb[$i]['mtime']?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $cachedb[$i]['size']?>
		  </td>
		</tr>
		<?php  }//end for?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="update" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strUpdate?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url?action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

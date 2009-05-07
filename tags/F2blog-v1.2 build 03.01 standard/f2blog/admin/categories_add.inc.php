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
		  <td width="10%" nowrap>
			<?php echo $strCategoryReside?>
		  </td>
		  <td width="90%">
			<?php get_category_parent("parent",$parent,"style=\"width:280px;font-size:12px;\"")?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strCategoryName?>
		  </td>
		  <td width="90%">
			<input name="name" id="name" class="textbox" type="TEXT" size=50 maxlength=20 value="<?php echo isset($name)?$name:""?>">
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strCategoryIcons?>
		  </td>
		  <td width="90%">
			<?php echo $strCategoryImagesHelp?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" class="input-titleblue">&nbsp;</td>
		  <td width="90%">
			<?php
			$categoryIcons=array();
			$handle=opendir("../images/icons"); 
			while ($file = readdir($handle)){
				if ($file!="." && $file!=".."){
					list($file_name,$file_type)=explode(".",$file);
					if (is_numeric($file_name) && strtolower($file_type)=="gif"){
						$categoryIcons[$file_name] = $file;
					}
				}
			} 
			closedir($handle);
			$maxrows=ceil(count($categoryIcons)/6);
			$i=0;
			foreach($categoryIcons as $key=>$value){
				if ($i>$maxrows) {
					$i=0;
					echo "<br />";
				}

				$checked=($value==$cateIcons)?"checked":"";
				echo "<INPUT TYPE=\"radio\" NAME=\"cateIcons\" value=\"$key\" $checked> <img src=\"../images/icons/$value\" align=\"absMiddle\" alt=\"$value\" width=\"16\" height=\"16\"> &nbsp;&nbsp;";
				$i++;
			}
			?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap>
			<?php echo $strCategoryDescription?>
		  </td>
		  <td width="90%">
			<input name="cateTitle" id="cateTitle" class="textbox" type="TEXT" size=50 maxlength=100 value="<?php echo !empty($cateTitle)?$cateTitle:""?>">
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap>
			<?php echo $strCategoryUrl?>
		  </td>
		  <td width="90%">
			<input name="url" id="url" class="textbox" type="TEXT" size=50 maxlength=100 value="<?php echo !empty($url)?$url:""?>">
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

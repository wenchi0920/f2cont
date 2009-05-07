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
	if (isNull(form.modTitle, '<?php echo $strErrNull?>')) return false;

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
		  <td width="145" align="left" class="input-titleblue">
			<?php echo $strModType?>
		  </td>
		  <td>
			<select name="disType" onchange="show_tools(this.value)">
			  <option value="1" <?php  if ($disType=="1") { echo "selected"; }?>>
			  <?php echo $strModTypeArr[1]?>
			  </option>
			  <option value="2" <?php  if ($disType=="2") { echo "selected"; }?>>
			  <?php echo $strModTypeArr[2]?>
			  </option>
			  <option value="3" <?php  if ($disType=="3") { echo "selected"; }?>>
			  <?php echo $strModTypeArr[3]?>
			  </option>
			</select>
			<input type="hidden" name="oldDisType" value="<?php echo !empty($oldDisType)?$oldDisType:""; ?>">
		  </td>
		</tr>
		<tr>
		  <td width="145" align="left" class="input-titleblue">
			<?php echo $strModName?>
		  </td>
		  <td>
			<input name="name" class="<?php echo ($mark_id!="")?"readonly":"textbox"?>" type="TEXT" size=50 maxlength=20 value="<?php echo !empty($name)?$name:""; ?>" <?php echo ($mark_id!="")?"readonly":""?>>
		  </td>
		</tr>
		<tr>
		  <td width="145" align="left" class="input-titleblue">
			<?php echo $strModTitle?>
		  </td>
		  <td>
			<input name="modTitle" class="textbox" type="TEXT" size=50 maxlength=50 value="<?php echo !empty($modTitle)?$modTitle:""; ?>">
		  </td>
		</tr>
		<tr>
		  <td width="145" align="left" class="input-titleblue">
			<?php echo $strModuleShowHidden?>
		  </td>
		  <td align="left">
			<INPUT TYPE="radio" NAME="isHidden" value="" <?php  if ($isHidden==0) { echo "checked"; }?>>
			<?php echo $strShow?>
			<INPUT TYPE="radio" NAME="isHidden" value="1" <?php  if ($isHidden>0) { echo "checked"; }?>>
			<?php echo $strHidden?>                    
		  </td>
		</tr>
		<!--模块为内容时显示-->
		<tr id="mod_content" style="display:<?php echo ($disType=="3")?"":"none"?>">
		  <td width="145" align="left">
			<?php echo $strModIndexOnly?>
		  </td>
		  <td align="left">
			<SELECT NAME="indexOnly">
				<?php
				$indexOnly=empty($indexOnly)?"":$indexOnly;
				for ($i=0;$i<count($strModuleContentShow);$i++){
					$selected=($indexOnly==$i)?" selected":"";
					echo "<option value=\"$i\"$selected>$strModuleContentShow[$i]</option> \n";
				}
				?>
			</SELECT>
		  </td>
		</tr>
		<!--模块为侧边栏时显示-->
		<tr id="mod_sidebar1" style="display:<?php echo ($disType=="2")?"":"none"?>">
		  <td width="145" align="left" class="input-titleblue">
			<?php echo $strModuleSideShowHidden?>
		  </td>
		  <td align="left">
			<INPUT TYPE="radio" NAME="isInstall" value="" <?php  if ($isInstall==0) { echo "checked"; }?>>
			<?php echo $strShow?>
			<INPUT TYPE="radio" NAME="isInstall" value="1" <?php  if ($isInstall>0) { echo "checked"; }?>>
			<?php echo $strHidden?>
		  </td>
		</tr>
		<tr id="mod_sidebar5" style="display:<?php echo ($disType!="1")?"":"none"?>">
		  <td width="145" align="left" class="input-titleblue">
			  <?php echo $strSidebarViewPosition; ?>
		  </td>
		  <td>
			<INPUT TYPE="radio" NAME="indexOnly3" value="1" <?php  if ($indexOnly3==1) { echo "checked"; }?>>
			<?php echo $strYes?>
			<INPUT TYPE="radio" NAME="indexOnly3" value="0" <?php  if ($indexOnly3!=1) { echo "checked"; }?>>
			<?php echo $strNo."&nbsp;&nbsp;(".$strSidebarViewPosition1.")"; ?>
		  </td>
		</tr>
		<!--模块为侧边栏或内容栏时显示-->
		<tr id="mod_sidebar2" style="display:<?php echo ($disType!="1")?"":"none"?>">
		  <td width="145" align="left" style="padding-top: 10px;" valign="top">
			  <?php echo $strHtmlCode?>
		  </td>
		  <td align="left">
			<textarea name="htmlCode" cols="65" rows="13"><?php echo !empty($htmlCode)?$htmlCode:""; ?></textarea>
			<br /><?php echo $strCodeHelp?>
		  </td>
		</tr>
		<!--模块为顶部时显示-->
		<tr id="mod_top2" style="display:<?php echo ($disType=="1")?"":"none"?>">
		  <td width="145" align="left" class="input-titleblue">
			  <?php echo $strSidebarOpenWindow; ?>
		  </td>
		  <td>
			<INPUT TYPE="radio" NAME="indexOnly2" value="1" <?php  if ($indexOnly2==1) { echo "checked"; }?>>
			<?php echo $strYes?>
			<INPUT TYPE="radio" NAME="indexOnly2" value="0" <?php  if ($indexOnly2!=1) { echo "checked"; }?>>
			<?php echo $strNo?>
		  </td>
		</tr>
		<tr id="mod_top" style="display:<?php echo ($disType=="1")?"":"none"?>">
		  <td width="145" align="left" class="input-titleblue">
			  <?php echo $strLinkAdd?>
		  </td>
		  <td>
			<input name="pluginPath" class="textbox" type="TEXT" size=50 maxlength=60 value="<?php echo !empty($pluginPath)?$pluginPath:""; ?>">
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

<?php 
# 禁止直接访问该页面
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {
	if (isNull(form.name, '<?php echo $strErrNull?>')) return false;
	if (isNull(form.blogUrl, '<?php echo $strErrNull?>')) return false;
	if (isNull(form.lnkGrpId, '<?php echo $strErrNull?>')) return false;

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
		<tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue"><?php echo $strLinksName?></td>
		  <td width="90%">
			<input name="name" id="name" class="textbox" type="TEXT" size=50 maxlength=20 value="<?php echo isset($name)?$name:""?>">
		  </td>
		</tr>
		<tr>
		  <td nowrap class="input-titleblue"><?php echo $strLinksLinkUrl?></td>
		  <td>
			<input name="blogUrl" id="blogUrl" class="textbox" type="TEXT" size=50 value="<?php echo isset($blogUrl)?$blogUrl:""?>">
		  </td>
		</tr>
		<tr>
		  <td nowrap class="input-title"><?php echo $strLinkLogo?></td>
		  <td>
			<input name="blogLogo" id="blogLogo" class="textbox" type="TEXT" size=50 value="<?php echo isset($blogLogo)?$blogLogo:""?>">
			<?php echo "&nbsp;&nbsp;".$strLinkLogoDesc?>
		  </td>
		</tr>
		<tr>
		  <td nowrap class="input-titleblue"><?php echo $strlinkgroupTitle?></td>
		  <td style="padding-top:7px">
			<span id="linkgroups"><?php linkgroup_select("lnkGrpId","$lnkGrpId","class=\"searchbox\"");?></span>
			<input name="btngroups" id="btngroups" class="btn" type="button" value="<?php echo $strlinkgroupTitleAdd?>" onClick="document.getElementById('linkgroups').innerHTML='<input name=lnkGrpId class=textbox type=text size=50>';document.getElementById('btngroups').style.display='none';document.getElementById('btnrgroups').style.display='';document.seekform.lnkGrpId.focus();"> 
			<input name="btnrgroups" id="btnrgroups" class="btn" type="button" value="<?php echo $strReturn?>" onClick="document.getElementById('linkgroups').innerHTML=document.seekform.groupbak.value;document.getElementById('btnrgroups').style.display='none';document.getElementById('btngroups').style.display='';document.seekform.lnkGrpId.focus();"  style="display:none"> 
			<input name="groupbak" id="groupbak" type="hidden" value="<?php ob_start();linkgroup_select("lnkGrpId","$lnkGrpId","class=\"searchbox\"");$contents=ob_get_contents();ob_end_clean();echo str_replace('"','',$contents);?>">
		  </td>
		</tr>
		<tr>
		  <td nowrap class="input-titleblue"><?php echo $strLinkIsSidebar?></td>
		  <td style="padding-top:7px">
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

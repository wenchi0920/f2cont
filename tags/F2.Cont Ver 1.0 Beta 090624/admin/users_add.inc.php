<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	<?php if ($action=="add"){?>
		if (strlen(form.addusername.value)<3){
			alert('<?php echo $strUserLengMax?>');
			form.addusername.focus();
			return false;
		}
		if (/[\s\'\"\\]/.test(form.addusername.value)){
			alert('<?php echo $strUserAlert?>');
			form.addusername.focus();
			return false;
		}
		if (form.addpassword.value.length<5 || /[\'\"\\]/.test(form.addpassword.value)){
			alert('<?php echo $strPasswordAlert?>');
			form.addpassword.focus();
			return false;
		}
	<?php }else{?>
		if (form.addpassword.value.length>0 && (form.addpassword.value.length<5 || /[\'\"\\]/.test(form.addpassword.value))){
			alert('<?php echo $strPasswordAlert?>');
			form.addpassword.focus();
			return false;
		}
	<?php }?>	

	if (form.addpassword.value!=form.password_con.value){
		alert('<?php echo $strErrPassword?>');
		form.addpassword.focus();
		return false;
	}

	if (strlen(form.nickname.value)<3 && form.nickname.value.length>0){
		alert('<?php echo $strNickLengMax?>');
		form.nickname.focus();
		return false;
	}

	if (!(/^[\-\.\w]+@[\-\w]+(\.\w+)+$/.test(form.email.value)) && form.email.value.length>0){
		alert('<?php echo $strErrEmail?>');
		form.email.focus();
		return false;
	}
	
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
	<br />
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
	  <br />
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
	  <br />
	  <?php  } ?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-input">
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strLoginUserID?>
		  </td>
		  <td width="90%">
			<?php  if ($mark_id=="") { ?>
			<input name="addusername" class="textbox" type="text" size="30" maxlength="20" value="<?php echo $addusername?>">
			&nbsp;&nbsp;<span style="color:#CC0033"><?php echo $strUserHelp?></span>
			<?php  } else {
			echo "<b>$addusername</b>";
		   }
		?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue"><?php echo $strLoginPassword?></td>
		  <td width="90%">
			<input name="addpassword" class="textbox" type="password" size="30" maxlength="20" value="">
			&nbsp;&nbsp;<span style="color:#CC0033"><?php echo $strPasswordHelp?></span> </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue"><?php echo $strConfigPassword?></td>
		  <td width="90%">
			<input name="password_con" class="textbox" type="password" size="30" maxlength="20" value="">
			&nbsp;&nbsp;<span style="color:#CC0033"><?php echo $strPassAlert?></span> </td>
		</tr>
		<tr>
		  <td width="10%" nowrap><?php echo $strNickName?></td>
		  <td width="90%">
			<input name="nickname" class="textbox" type="text" size="30" maxlength="20" value="<?php echo $nickname?>">
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap><?php echo $strEmail?></td>
		  <td width="90%">
			<input name="email" class="textbox" type="TEXT" size="30" maxlength=100 value="<?php echo $email?>">
			<input name="isHiddenEmail" type="checkbox" value="1" <?php  if ($isHiddenEmail=="1") { echo "checked"; }?>/>
			<?php echo $strHiddenEmail?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap><?php echo $strUserBlog?></td>
		  <td width="90%">
			<input name="homePage" class="textbox" type="TEXT" size="30" maxlength=100 value="<?php echo $homePage?>">
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap><?php echo $strRole?></td>
		  <td width="90%" style="padding-top:5px">
			<select name="role" class="searchbox">
			<option value='author' <?php echo ($role=="author" or $role=="")?"selected":""?>><?php echo $strRoleAuthor?></option>
			<option value='editor' <?php echo ($role=="editor")?"selected":""?>><?php echo $strRoleEditor?></option> 
			<option value='admin' <?php echo ($role=="admin")?"selected":""?>><?php echo $strAdmin?></option> 
			<option value='member' <?php echo ($role=="member")?"selected":""?>><?php echo $strMember?></option> 
			</select>
		  </td>
		</tr>
	  </table>
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
	  &nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'"/>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

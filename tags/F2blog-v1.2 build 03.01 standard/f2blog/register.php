<?php 
include_once("include/function.php");

//必须在本站操作
$server_session_id=md5("register".session_id());
if (!empty($_GET['action']) && $_GET['action']=="save" && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

include_once("header.php");

include_once(F2BLOG_ROOT."./include/cache.php");

if ($settingInfo['isRegister']==1 && empty($_SESSION['username'])){//为１时表示关闭注册
?>
   <br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?php echo $strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?php echo $settingInfo[registerClose]?><br /><a href="index.php"><?php echo $strErrorBack?></a></div>
	  </div>
	</div>
  </div><br /><br />
<?php }else{?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	<?php  if (empty($_SESSION['username'])){ ?>
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
	<?php  } else { ?>
		if (form.oldpassword.value.length<5 || /[\'\"\\]/.test(form.oldpassword.value)){
			alert('<?php echo $strOldPasswordDesc?>');
			form.oldpassword.focus();
			return false;
		}
	<?php  } ?>

	if (form.addpassword.value!=form.password_con.value){
		alert('<?php echo $strErrPassword?>');
		form.password_con.focus();
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

	<?php if ($settingInfo['uservalid']==1){?>
	if (!(/[0-9]{5,6}/.test(form.validate.value))){
		alert('<?php echo $strLoginValidateError?>');
		form.validate.focus();
		return false;
	}
	<?php  } ?>

	form.save.disabled = true;
	form.rest.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<?php 
if (!empty($_GET['action']) && $_GET['action']=="save"){
	$check_info=1;
	if (empty($_SESSION['username'])) {
		if (check_user($_POST['addusername'])==0) {
			$ActionMessage=$strUserAlert;
			$check_info=0;
			$addusername="";
		}

		if ($check_info==1 && check_password($_POST['addpassword'])==0) {
			$ActionMessage=$strPasswordAlert;
			$check_info=0;
		}
	}else{
		if ($check_info==1 && $_POST['addpassword']!="" && check_password($_POST['addpassword'])==0) {
			$ActionMessage=$strPasswordAlert;
			$check_info=0;
		}
	}

	if ($check_info==1 && (preg_match("/<|>|'|\"/i",$_POST['homePage']) || preg_match("/<|>|'|\"/i",$_POST['email']) || preg_match("/<|>|'|\"/i",$_POST['nickname']))) {
		$ActionMessage=$strErrorCharacter;
		$check_info=0;
		$homePage="";
		$email="";
		$nickname="";
	}

	//检查两次密码是否相同
	if ($check_info==1 && $_POST['addpassword']!=$_POST['password_con']) {
		$ActionMessage=$strErrPassword;
		$check_info=0;
	}

	if ($check_info==1 && $_POST['email']!="" && check_email($_POST['email'])==0) {
		$ActionMessage=$strErrEmail;
		$check_info=0;
	}

	//检测昵称
	if ($check_info==1 && $_POST['nickname']!="" && check_nickname($_POST['nickname'])==0) {
		$ActionMessage=$strNickLengMax;
		$check_info=0;
	}

	if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
	if ($check_info==1 && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['uservalid']==1){
		$ActionMessage=$strLoginValidateError;
		$check_info=0;
	}

	//过滤特别的一些用户名
	if ($check_info==1) {
		include_once("cache/cache_filters.php");
		$deUserArray=array("administrator","webmaster","username","user","supervisor","admin","guest","test",$settingInfo['master']);
		if (!empty($filtercache2) && is_array($filtercache2)) $deUserArray=array_merge($deUserArray,$filtercache2);
		if (in_array($_POST['addusername'],$deUserArray) or in_array($_POST['nickname'],$deUserArray)){
			$ActionMessage=$strRegisterInvaild;
			$check_info=0;
		}
	}

	if ($check_info==0) {
		$action=($_SESSION['username']!="")?"edit":"add";
	}

	if ($check_info==1){
		if (!empty($_POST['addusername'])) $_POST['addusername']=safe_convert(strip_tags($_POST['addusername']));
		if (!empty($_POST['addpassword'])) $_POST['addpassword']=safe_convert($_POST['addpassword']);
		if (!empty($_POST['password_con'])) $_POST['password_con']=safe_convert($_POST['password_con']);
		if (!empty($_POST['email'])) $_POST['email']=safe_convert(strip_tags($_POST['email']));
		if (!empty($_POST['nickname'])) $_POST['nickname']=safe_convert(strip_tags($_POST['nickname']));
		if (!empty($_POST['oldpassword'])) $_POST['oldpassword']=safe_convert($_POST['oldpassword']);
		if (!empty($_POST['homePage'])) $_POST['homePage']=safe_convert(strip_tags($_POST['homePage']));

		if (!empty($_SESSION['username'])){//编辑
			$rsexits=getFieldValue($DBPrefix."members","username='{$_SESSION['username']}' and password=md5('".$_POST['oldpassword']."')","id");
			if ($rsexits=="") {
				$ActionMessage="$strOldPasswordDesc1";
			} else {
				//检测昵称
				if ($_POST['nickname']!=""){
					$nickrsexits=getFieldValue($DBPrefix."members","username='{$_POST['nickname']}' or nickname='{$_POST['nickname']}'","username");
					$check_info=($nickrsexits!="" && $nickrsexits!=$_SESSION['username'])?0:1;
				}
				
				if ($check_info==0){
					$ActionMessage="$strCurUserExists";
				}else{
					if ($_POST['addpassword']!="") { $passSql=",password=md5('".$_POST['addpassword']."')"; }

					$sql="update {$DBPrefix}members set nickname='{$_POST[nickname]}',email='{$_POST['email']}',isHiddenEmail='".$_POST['isHiddenEmail']."',homePage='{$_POST['homePage']}'".$passSql." where username='{$_SESSION['username']}'";
					$DMC->query($sql);
					$ActionMessage="$strRegSucc1";
				}
			}
		}else{//新增
			//检测昵称
			if ($_POST['nickname']!=""){
				$nickrsexits=getFieldValue($DBPrefix."members","username='{$_POST['nickname']}' or nickname='{$_POST['nickname']}'","username");
				$check_info=($nickrsexits!="")?0:1;
			}
			//检测用户名
			if ($check_info==1){
				$userexits=getFieldValue($DBPrefix."members","username='{$_POST['addusername']}' or nickname='{$_POST['addusername']}'","username");
				$check_info=($userexits!="")?0:1;
			}
				
			if ($check_info==0){
				$ActionMessage=($nickrsexits!="")?"$strCurUserExists":"$strRegisterInvaild";
			}else{
				$sql="INSERT INTO ".$DBPrefix."members(username,password,email,isHiddenEmail,homePage,lastVisitTime,lastVisitIP,regIp,hashKey,role,nickname) VALUES ('{$_POST['addusername']}',md5('".$_POST['addpassword']."'),'{$_POST['email']}','{$_POST['isHiddenEmail']			}','{$_POST['homePage']}','".time()."','".getip()."','".getip()."','','member','{$_POST['nickname']}')";
				$DMC->query($sql);
				$ActionMessage2="$strRegSucc";
			}
		}
		members_recache();
		settings_recount("members");
		settings_recache();
	}
}

if (!empty($_SESSION['username']) && $_SESSION['username']!="") {
	$dataInfo = $DMC->fetchArray($DMC->query("select nickname,email,isHiddenEmail,homePage from {$DBPrefix}members where username='{$_SESSION['username']}'"));
	$nickname=$dataInfo['nickname'];
	$email=$dataInfo['email'];
	$isHiddenEmail=$dataInfo['isHiddenEmail'];
	$homePage=$dataInfo['homePage'];
}

?>

<!--内容-->
<div id="Tbody">
<br /><br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:550px">
      <div id="MsgHead"><?php echo $strUserInfoShow?></div>
      <div id="MsgBody">
		  <?php 
			if (!empty($ActionMessage2)) {
				echo "<div class=\"MessageText\"><b>$ActionMessage2</div>";
			} else {
				printMessage($ActionMessage);
		  ?>
			  <table width="100%" cellpadding="0" cellspacing="0">
				  <form action="" method="post" name="seekform">
				  <tr>
					<td align="right"><strong><?php echo $strLoginUserID?>:</strong></td>
					<td align="left" style="padding:3px;">
						<?php  if (!empty($_SESSION['username'])) { echo $_SESSION['username']; } else { ?>
						<input name="addusername" type="text" size="30" class="userpass" maxlength="20" value="<?php echo empty($addusername)?"":$addusername; ?>"/><font color="#FF0000">&nbsp;*</font> <?php echo $strUserHelp?>
						<?php  } ?>
					</td>
				  </tr>
				  <?php  if (!empty($_SESSION['username'])) { ?>
				  <tr>
					<td align="right"><strong><?php echo $strLoginPassword?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="oldpassword" type="password" size="18" class="userpass" maxlength="20"/><font color="#FF0000">&nbsp;*</font> <?php echo $strOldPasswordDesc?>
					</td>
				  </tr>
				  <?php  } ?>
				  <tr>
					<td align="right"><strong><?php echo $strLoginPassword?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="addpassword" type="password" size="30" class="userpass" maxlength="20"/><?php  if (empty($_SESSION['username'])) { ?><font color="#FF0000">&nbsp;*</font><?php  }?> <?php echo $strPasswordHelp?>
					</td>
				  </tr>
				  <tr>
					<td align="right"><strong><?php echo $strConfigPassword?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="password_con" type="password" size="30" class="userpass" maxlength="20"/><?php  if (empty($_SESSION['username'])) { ?><font color="#FF0000">&nbsp;*</font><?php }?> <?php echo $strPassAlert?>
					</td>
				  </tr>
				  <tr>
					<td align="right"><strong><?php echo $strNickName?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="nickname" type="text" size="30" class="userpass" maxlength="30" value="<?php echo empty($nickname)?"":$nickname; ?>"/>
					</td>
				  </tr>
				  <tr>
					<td align="right"><strong><?php echo $strEmail?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="email" type="text" size="30" class="userpass" maxlength="255" value="<?php echo empty($email)?"":$email; ?>"/> <input id="isHiddenEmail" name="isHiddenEmail" type="checkbox" value="1" <?php  if (empty($isHiddenEmail) or $isHiddenEmail=="1") { echo "checked=\"checked\""; }?>/> <label for="hiddenEmail"><?php echo $strHiddenEmail?></label>
					</td>
				  </tr>
				  <tr>
					<td align="right"><strong><?php echo $strUserBlog?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="homePage" type="text" size="30" class="userpass" maxlength="255" value="<?php echo empty($homePage)?"":$homePage; ?>"/>
					</td>
				  </tr>
				  <?php if ($settingInfo['uservalid']==1){?>
				  <tr>
					<td align="right"><strong><?php echo $strLoginValidate?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="validate" type="text" size="30" class="userpass" maxlength="10"/>
					   <?php if (function_exists('imagecreate')){?>
							<img src="include/image_firefox.inc.php" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
					   <?php 
						}else{
							echo $_SESSION['backValidate']=validCode(6);
						}
					   ?>	
						<font color="#FF0000">*</font> <?php echo $strGuestBookInputValid?>
					</td>
				  </tr>
				  <?php  } ?>
				<tr><td colspan="2" align="center" style="padding:3px;">
					<input name="save" class="userbutton" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)">
					<input name="rest" type="reset" class="userbutton" value="<?php echo $strReset?>"/>
					<input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
				</td></tr>
				</form>
			  </table>
		  <?php  } ?>
		</div>
	  </div>
	</div>
<br /><br />
</div>
<?php }?>
<?php include_once("footer.php")?>
<?php 
include_once("include/function.php");

include_once("header.php");

if ($settingInfo['loginStatus']==1){//为１时表示关闭登入
?>
   <br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?php echo $strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?php echo $strLoginStatusClose?><br /><a href="index.php"><?php echo $strErrorBack?></a></div>
	  </div>
	</div>
  </div><br /><br />
<?php }else{?>
<script type="text/javascript">
<!--
function onclick_update() {	
	if (strlen(document.seekform.username.value)<3){
		alert('<?php echo $strUserLengMax?>');
		document.seekform.username.focus();
		return false;
	}
	if (/[\s\'\"\\]/.test(document.seekform.username.value)){
		alert('<?php echo $strUserAlert?>');
		document.seekform.username.focus();
		return false;
	}
	if (document.seekform.password.value.length<5 || /[\'\"\\]/.test(document.seekform.password.value)){
		alert('<?php echo $strPasswordAlert?>');
		document.seekform.password.focus();
		return false;
	}

	<?php if ($settingInfo['uservalid']==1){?>
	if (!(/[0-9]{5,6}/.test(document.seekform.validate.value))){
		alert('<?php echo $strLoginValidateError?>');
		document.seekform.validate.focus();
		return false;
	}
	<?php }?>
}
-->
</script>
<?php 
if ($action=="logout"){
	setcookie("username","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("password","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("rights","",time()+86400*365,$cookiepath,$cookiedomain);
	$_SESSION['username']="";
	$_SESSION['password']="";
	$_SESSION['rights']="";	
	unset($_SESSION);
	session_destroy();
	header("Location: index.php");
}

if ($_GET['action']=="login"){
	$check_info=1;
	if (!empty($_POST['username'])) $_POST['username']=safe_convert(strip_tags($_POST['username']));
	if (!empty($_POST['password'])) $_POST['password']=safe_convert($_POST['password']);
	if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);

	if (check_user($_POST['username'])==0) {
		$ActionMessage=$strUserLengMax;
		$check_info=0;
	}

	if ($check_info==1 && check_password($_POST['password'])==0) {
		$ActionMessage=$strPasswordAlert;
		$check_info=0;
	}	

	if ($check_info==1 && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['uservalid']==1){
		$ActionMessage=$strLoginValidateError;
		$check_info=0;
	}

	if ($check_info==1){
		$username=$_POST['username'];
		$password=$_POST['password'];
		$sql="SELECT role,password FROM {$DBPrefix}members WHERE username='{$username}' and password=md5('".$password."')";
		$userInfo = $DMC->fetchArray($DMC->query($sql));

		if ($userInfo) {
			$_SESSION['username'] = $username;
			$_SESSION['password'] = md5($password);
			$_SESSION['rights']   = $userInfo['role'];
			
			if ($_POST['chksave']=="save"){
				$session_id=session_id();
				setcookie("username",$username,time()+86400*365,$cookiepath,$cookiedomain);
				setcookie("password","####".$session_id,time()+86400*365,$cookiepath,$cookiedomain);
				setcookie("rights",$userInfo['role'],time()+86400*365,$cookiepath,$cookiedomain);
				$sql="update ".$DBPrefix."members set hashKey='".md5($session_id)."' where username='$username'";
				$DMC->query($sql);
			}else{
				setcookie("username","",time()+86400*365,$cookiepath,$cookiedomain);
				setcookie("password","",time()+86400*365,$cookiepath,$cookiedomain);
				setcookie("rights","",time()+86400*365,$cookiepath,$cookiedomain);
				$DMC->query("update ".$DBPrefix."members set hashKey='' where username='$username'");
			}
			header("Location: index.php");
		} else {
			$ActionMessage=$strLoginErrUserPWD;				
		}		
	}
}
?>

<!--内容-->
<div id="Tbody">
   <br /><br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:550px">
      <div id="MsgHead"><?php echo $strUserLoginWindows?></div>
      <div id="MsgBody">
		<?php printMessage($ActionMessage);?>
		<form action="login.php?action=login" method="post" name="seekform" onsubmit="return onclick_update()">
		  <table width="100%" cellpadding="0" cellspacing="0">	 
			  <tr>
				<td align="right" width="40%"><strong><?php echo $strLoginUserID?></strong>:</td>
				<td align="left" style="padding:3px;"><input name="username" type="text" size="18" class="userpass" maxlength="20"/></td>
			  </tr>
			  <tr>
				<td align="right" width="40%"><strong><?php echo $strLoginPassword?></strong>:</td>
				<td align="left" style="padding:3px;"><input name="password" type="password" size="18" maxlength="20" class="userpass"/></td>
			  </tr>
			  <?php if ($settingInfo['uservalid']==1){
				if (function_exists('imagecreate')){
					$vcode="<img src=\"include/image_firefox.inc.php\" alt=\"$strGuestBookValidImage\" align=\"middle\"/>";
				}else{
					$vcode=validCode(6);
					$_SESSION['backValidate']=$vcode;
				}
			  ?>
			  <tr>
				<td align="right" width="40%"><strong><?php echo $strLoginValidate?></strong>:</td>
				<td align="left" style="padding:3px;"><input name="validate" type="text" size="5" class="userpass" maxlength="10"/> <?php echo $vcode?></td>
			  </tr>
			  <?php }?>
			  <tr>
				<td colspan="2" align="center" style="padding:3px;"><input name="chksave" type="checkbox" value="save"/><?php echo $strSaveMyLogin?></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center" style="padding:3px;">
					<input name="save" type="submit" value="<?php echo $strLoginSubmit?>" class="userbutton"/>
					<?php if ($settingInfo['isRegister']==0){//为１时表示关闭注册?>
					<input name="reg" type="button" value="<?php echo $strUserRegister?>" class="userbutton" onclick="location='register.php'"/>
					<?php }?>
				</td>
			  </tr>        
		  </table>
		</form>
		</div>
	  </div>
	</div>
	<br /><br />
</div>
<?php }?>
<?php include_once("footer.php")?>
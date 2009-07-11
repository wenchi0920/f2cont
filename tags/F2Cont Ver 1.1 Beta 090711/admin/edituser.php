<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("edituser".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=0;
$mtitle=$strControlPanel;

$title="$strUserTitleEdit";

//保存参数
$action=$_GET['action'];

//保存数据
if ($action=="save"){
	$check_info=1;
	if ($_POST['addpassword']!="" && check_password($_POST['addpassword'])==0) {
		$ActionMessage=$strPasswordAlert;
		$check_info=0;
	}

	//检查两次密码是否相同
	if ($check_info==1 && $_POST['addpassword']!=$_POST['password_con']) {
		$ActionMessage=$strErrPassword;
		$check_info=0;
	}

	//检测昵称
	if ($check_info==1 && $_POST['nickname']!="" && check_nickname($_POST['nickname'])==0) {
		$ActionMessage=$strNickLengMax;
		$check_info=0;
	}

	if ($check_info==1 && $_POST['email']!="" && check_email($_POST['email'])==0) {
		$ActionMessage=$strErrEmail;
		$check_info=0;
	}
	
	if ($check_info==1){
		if (strlen($_POST['addpassword'])>0){
			$passSql=",password=md5('".encode($_POST['addpassword'])."')";
		}else{
			$passSql="";
		}
		$sql="update ".$DBPrefix."members set lastVisitTime='".time()."',lastVisitIP='".getip()."',email='".encode($_POST['email'])."',nickname='".encode($_POST['nickname'])."',homePage='".encode($_POST['homePage'])."',isHiddenEmail='".encode($_POST['isHiddenEmail'])."'".$passSql." where username='".encode($_SESSION['username'])."'";
		$DMC->query($sql);

		members_recache();
	}
}

$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."members where username='".$_SESSION['username']."'"));
if ($dataInfo) {
	$nickname=$dataInfo['nickname'];
	$email=$dataInfo['email'];
	$isHiddenEmail=$dataInfo['isHiddenEmail'];
	$homePage=$dataInfo['homePage'];
}

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	if (form.addpassword.value.length>0 && (form.addpassword.value.length<5 || /[\'\"\\]/.test(form.addpassword.value))){
		alert('<?php echo $strPasswordAlert?>');
		form.addpassword.focus();
		return false;
	}

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
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
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
		  <td width="90%"> <b><?php echo $_SESSION['username'];?></b> </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strLoginPassword?>
		  </td>
		  <td width="90%">
			<input name="addpassword" class="textbox" type="password" size="30" maxlength="20" value="">
			&nbsp;&nbsp;<span style="color:#CC0033">
			<?php echo $strPasswordHelp?>
			</span> </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strConfigPassword?>
		  </td>
		  <td width="90%">
			<input name="password_con" class="textbox" type="password" size="30" maxlength="20" value="">
			&nbsp;&nbsp;<span style="color:#CC0033">
			<?php echo $strPassAlert?>
			</span> </td>
		</tr>
		<tr>
		  <td width="10%" nowrap>
			<?php echo $strNickName?>
		  </td>
		  <td width="90%">
			<input name="nickname" class="textbox" type="text" size="30" maxlength="20" value="<?php echo $nickname?>">
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap>
			<?php echo $strEmail?>
		  </td>
		  <td width="90%">
			<input name="email" class="textbox" type="TEXT" size="30" maxlength=100 value="<?php echo $email?>">
			<input name="isHiddenEmail" type="checkbox" value="1" <?php  if ($isHiddenEmail=="1") { echo "checked"; }?>/>
			<?php echo $strHiddenEmail?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap>
			<?php echo $strUserBlog?>
		  </td>
		  <td width="90%">
			<input name="homePage" class="textbox" type="TEXT" size="30" maxlength=100 value="<?php echo $homePage?>">
		  </td>
		</tr>
	  </table>
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

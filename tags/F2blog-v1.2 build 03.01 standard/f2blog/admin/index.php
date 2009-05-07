<?php 
require_once("function.php");
$action=empty($_GET['action'])?"":$_GET['action'];

if (!empty($_POST['username'])) $_POST['username']=safe_convert($_POST['username']);
if (!empty($_POST['password'])) $_POST['password']=safe_convert($_POST['password']);
if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
$ActionMessage="";


if ($action=="logout"){
	setcookie("username","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("password","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("rights","",time()+86400*365,$cookiepath,$cookiedomain);
	$_SESSION['username']="";
	$_SESSION['password']="";
	$_SESSION['rights']="";	
	unset($_SESSION);
	session_destroy();
	header("Location: ../index.php");
}

if (!empty($_SESSION['rights'])){
	if (in_array($_SESSION['rights'],array("admin","editor","author")) && !empty($_SESSION['username']) && !empty($_SESSION['password'])) {
			if (strpos(";{$_SESSION['prelink']}","index.php")<1 && strpos(";{$_SESSION['prelink']}",$home_url)>0 && $_SESSION['prelink']!=""){
				header("Location: ".$_SESSION['prelink']."");
			}else{
				header("Location: control.php");
			}
	}else{
		header("Location: ../index.php");
	}
}

if ($action=="login"){
	$check_info=1;
	//检测用户名是否合法
	if (check_user($_POST['username'])==0) {
		$ActionMessage=$strUserLengMax;
		$check_info=0;
	}
	//检测密码是否合法
	if ($check_info==1 && check_password($_POST['password'])==0) {
		$ActionMessage=$strPasswordAlert;
		$check_info=0;
	}	
	//检测验证码
	if ($check_info==1 && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['loginvalid']==1){
		$ActionMessage=$strLoginValidateError;
		$check_info=0;
	}

	if ($check_info==1){
		$username=$_POST['username'];
		$password=$_POST['password'];
		if ($userInfo=check_user_pw($username,$password)) {
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
			
			
			if (strpos(";{$_SESSION['prelink']}","index.php")<1 && strpos(";{$_SESSION['prelink']}",$home_url)>0 && $_SESSION['prelink']!=""){
				header("Location: ".$_SESSION['prelink']."");
			}else{
				header("Location: control.php");
			}
		} else {
			$ActionMessage=$strLoginErrUserPWD;				
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<title><?php echo $strLoginTitle;?></title>
<link rel="stylesheet" href="themes/<?php echo $settingInfo['adminstyle'];?>/style.css" type="text/css" />
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript">
function on_focus(){
	document.seekform.username.focus();
}

function onclick_update() {	
	if (strlen(document.seekform.username.value)<3){
		alert('<?php echo $strUserLengMax;?>');
		document.seekform.username.focus();
		return false;
	}
	if (/[\s\'\"\\]/.test(document.seekform.username.value)){
		alert('<?php echo $strUserAlert;?>');
		document.seekform.username.focus();
		return false;
	}
	if (document.seekform.password.value.length<5 || /[\'\"\\]/.test(document.seekform.password.value)){
		alert('<?php echo $strPasswordAlert;?>');
		document.seekform.password.focus();
		return false;
	}

	<?php if ($settingInfo['loginvalid']==1){?>
	if (!(/[0-9]{5,6}/.test(document.seekform.validate.value))){
		alert('<?php echo $strLoginValidateError;?>');
		document.seekform.validate.focus();
		return false;
	}
	<?php }?>
}
</script>
</head>

<body onload="on_focus()">
<?php  print_message($ActionMessage);?>

<div id="login">
	<form name="seekform" method="post" action="index.php?action=login" onsubmit="return onclick_update()">
	<h1><a href="../"><img src="themes/<?php echo $settingInfo['adminstyle'];?>/logo.gif" border="0" alt=""/></a></h1>

	<p><label><?php echo $strLoginUserID;?> :<br /><input name="username" type="text" value="<?php echo (!empty($_POST['username']) && $_POST['username']!="")?$_POST['username']:"";?>" size="30" tabindex="1" /></label></p>
	<p><label><?php echo $strLoginPassword;?> :<br /> <input name="password" type="password" size="30" tabindex="2" /></label></p>
	<?php if ($settingInfo['loginvalid']==1){?>
	<p><label><?php echo $strLoginValidate;?> :<br /> <input name="validate" type="text" size="20" tabindex="3" />
		<?php if (function_exists('imagecreate')){?>
			<img src="../include/image_firefox.inc.php" alt="<?php echo $strGuestBookValidImage;?>" align="middle" />
	    <?php 
		}else{
			echo $_SESSION['backValidate']=validCode(6);
		}
	    ?>	
	</label></p>
	<?php }?>
	<p>
		<label for="chksave"><input type="checkbox" id="chksave" name="chksave" value="save" tabindex="4" /><?php echo $strSaveMyLogin?></label>
		<input type="submit" name="submit" id="submit" value="<?php echo $strLoginSubmit;?>" tabindex="5" class="btn" />
		<input type="reset" name="reset" value="<?php echo $strReset;?>" tabindex="6" class="btn" />
	</p>
	</form>
</div>
<?php  dofoot(); ?>
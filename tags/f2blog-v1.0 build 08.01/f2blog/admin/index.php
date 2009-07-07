<?
$PATH="./";
include("$PATH/function.php");

$action=$_GET['action'];

if ($_COOKIE['username']!="" && $_COOKIE['password']!="" && $_COOKIE['rights']!=""){
	$_SESSION['username']=$_COOKIE['username'];
	$_SESSION['password']=$_COOKIE['password'];
	$_SESSION['rights']=$_COOKIE['rights'];
	//echo $_SESSION['username'].$_SESSION['password'].$_SESSION['rights'];
}

if ($_SESSION['rights']=="admin" && $_SESSION['username']!="" && $_SESSION['password']!="") {
	header("Location: control.php");
}
//echo $_SESSION['prelink'];
if ($action=="logout"){
	setcookie("username","",time()+86400*365,$cookie_path);
	setcookie("password","",time()+86400*365,$cookie_path);
	setcookie("rights","",time()+86400*365,$cookie_path);
	$_SESSION['username']="";
	$_SESSION['password']="";
	$_SESSION['rights']="";	
	unset($_SESSION);
	session_destroy();
	header("Location: ../index.php");
}

if ($action=="login"){
	$username=addslashes(trim($_POST['username']));
	$password=addslashes(trim($_POST['password']));
	$validate=addslashes(trim($_POST['validate']));

	if ($username=="" || $password=="" || $validate==""){
		$ActionMessage=$strLoginErrComplete;
	} else {
		if (strtolower($validate)!=strtolower($_SESSION['validate'])){
			$ActionMessage=$strLoginValidateError;
		}else{
			if (!check_user($username) || !check_password($password)){
				$ActionMessage=$strLoginInvail;
			} else {
				if ($userInfo=check_user_pw($username,$password)) {
					$nickname = empty($userinfo['nickname']) ? $username : $userinfo['nickname'];
					$_SESSION['username'] = $username;
					$_SESSION['password'] = md5($password);
					$_SESSION['rights']   = "admin";
					
					//保存登入信息
					//echo $_POST['chksave'];
					if ($_POST['chksave']=="save"){
						$session_id=session_id();
						setcookie("username",$username,time()+86400*365,$cookie_path);
						setcookie("password","####".$session_id,time()+86400*365,$cookie_path);
						setcookie("rights","admin",time()+86400*365,$cookie_path);
						$sql="update ".$DBPrefix."members set hashKey='".md5($session_id)."' where username='$username'";
						$DMC->query($sql);
					}else{
						setcookie("username","",time()+86400*365,$cookie_path);
						setcookie("password","",time()+86400*365,$cookie_path);
						setcookie("rights","",time()+86400*365,$cookie_path);
						$DMC->query("update ".$DBPrefix."members set hashKey='' where username='$username'");
					}

					if ($_POST['window_name']=="main"){
						if ($_SESSION['prelink']!="" && strpos(";".$_SESSION['prelink'],"control.php")<1 && 			strpos(";".$_SESSION['prelink'],"index.php")<1){
							header("Location: ".$_SESSION['prelink']."");
						}else{
							header("Location: info.php");
						}
					}else{
						header("Location: control.php");
					}
				} else {
					$ActionMessage=$strLoginErrUserPWD;				
				}
			}
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$strLoginTitle?></title>
<style type="text/css">
<!--
body {margin-top: 0px;}
.style4 {color: #333333}
.style5 {font-family: Tahoma; font-size: 9pt;}
a:link {
	color: #FF3300;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF3300;
}
a:hover {
	text-decoration: underline;
	color: #FF3300;
}
a:active {
	text-decoration: none;
	color: #FF3300;
}
.style6 {font-family: Tahoma; font-size: 9pt; color: #333333; }
.style7 {font-family: Tahoma; font-size: 9pt; color: #333333; width:180px;}
.style8 {font-family: Tahoma; font-size: 9pt; color: #333333; width:113px;}
-->
</style>
<script language="Javascript">
function onfocus(){
	document.seekform.username.focus();
    document.seekform.window_name.value=window.name;
}

function onclick_reset(){
	document.seekform.username.value="";
	document.seekform.password.value="";
	document.seekform.validate.value="";
}
</script>
</head>

<body onload=onfocus()>
<?print_message($ActionMessage);?>
<form name="seekform" method="post" action="<?=$_SERVER['PHP_SELF']."?action=login"?>" AutoComplete="OFF">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right"><img src="images/logon/left-top.gif" width="234" height="132"></td>
    <td width="360" height="132"><img src="images/logon/top.gif" width="360" height="132"></td>
    <td align="left"><img src="images/logon/right-top.gif" width="234" height="132"></td>
  </tr>
  <tr>
    <td width="245" height="180" align="right" valign="middle"><img src="images/logon/left.gif" width="245" height="180"></td>
    <td width="360" height="180" align="center" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="360" height="14" valign="top"><img src="images/logon/center-top.gif" width="360" height="14"></td>
      </tr>
      <tr>
        <td height="152" valign="top" background="center.gif"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" valign="middle"><img src="images/logon/logo.jpg" width="195" height="52"></td>
          </tr>
          <tr>
            <td align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100" align="right" valign="middle" class="style6"><?echo $strLoginUserID;?> :</td>
                <td width="10" height="25">&nbsp;</td>
                <td align="left" valign="middle" ><input name="username" type="text" value="<?=($_POST['username'])?$_POST['username']:""?>" size="30" class="style7"></td>
              </tr>
              <tr>
                <td width="100" align="right" valign="middle" class="style6"><?=$strLoginPassword;?> :</td>
                <td width="10" height="25">&nbsp;</td>
                <td align="left" valign="middle"><input name="password" type="password" size="30" class="style7"></td>
              </tr>
			  <tr>
                <td width="100" align="right" valign="middle" class="style6"><?=$strLoginValidate;?> :</td>
                <td width="10" height="25">&nbsp;</td>
                <td align="left" valign="middle"><input name="validate" type="text" size="30" class="style8">
				   <?if (function_exists(imagecreate)){?>
						<img src="../include/image_firefox.inc.php" alt="<?=$strGuestBookValidImage?>" align="absmiddle"/>
				   <?
					}else{
						echo validCode(6);
					}
				   ?>
				</td>
              </tr>
              <tr>
                <td width="100" align="right">&nbsp;</td>
                <td width="10" height="25">&nbsp;</td>
                <td>
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
				    <td width="<?=($settingInfo['language']=="en")?"200":"330"?>" class="style6" align="left"><label for="chksave"><INPUT TYPE="checkbox" id="chksave" NAME="chksave" value="save"><?=$strSaveMyLogin?></label></td>
					<td width="10">&nbsp;</td>
                    <td width="29" valign="center"><input type="image" name="submit" src="images/logon/b1_<?=$settingInfo['language']?>.gif" width="29" height="17"></td>  
					<td width="1">&nbsp;</td>
                    <td width="29" valign="center"><img src="images/logon/b2_<?=$settingInfo['language']?>.gif" width="29" height="17" onclick="onclick_reset()" style="cursor: pointer"></td>
                    <td width="180">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="14" valign="bottom"><img src="images/logon/center-bottom.gif" width="360" height="14"></td>
      </tr>
    </table></td>
    <td width="245" height="180"><img src="images/logon/right.gif" width="245" height="180"></td>
  </tr>
  <tr>
    <td align="right" valign="top"><img src="images/logon/left-bottom.gif" width="238" height="182"></td>
    <td width="360" height="190" align="center" valign="top" background="images/logon/bottom.gif"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="center" class="style4"><span class="style5"><? dofoot(); ?></span></div></td>
      </tr>
    </table></td>
    <td align="left" valign="top"><img src="images/logon/right-bottom.gif" width="238" height="182"></td>
  </tr>
</table>
<input type="hidden" name="window_name" value="">
</form>
</body>
</html>
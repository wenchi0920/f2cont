<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

$title="$strUserTitleEdit";

//保存参数
$action=$_GET['action'];

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if (strlen(trim($_POST['password']))>0 && strlen(trim($_POST['password']))<6) {
		$ActionMessage=$strLoginPassword.$strUserAlert;
		$check_info=0;
	}

	if (trim($_POST['nickname'])=="" or trim($_POST['email'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	//检查两次密码是否相同
	if ($check_info==1 && (trim($_POST['password'])!=trim($_POST['password_con']))) {
		$ActionMessage=$strErrPassword;
		$check_info=0;
	}

	//检查昵称的长度
	if ($check_info==1 && strlen(trim($_POST['nickname']))<3) {
		$ActionMessage=$strNickName.$strNikeAlert;
		$check_info=0;
	}

	//检查邮件
	if ($check_info==1 && check_email($_POST['email'])==0) {
		$ActionMessage=$strErrEmail;
		$check_info=0;
	}
	
	if ($check_info==1){
		if (trim($_POST['password'])){
			$_SESSION['password']=md5(trim($_POST['password']));		
			$passSql=",password=md5('".trim($_POST['password'])."')";
		}
		$sql="update ".$DBPrefix."members set lastVisitTime='".time()."',lastVisitIP='".getip()."',email='".$_POST['email']."',nickname='".$_POST['nickname']."',homePage='".$_POST['homePage']."'".$passSql." where username='".$_SESSION['username']."'";
		$DMC->query($sql);
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
?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (form.password.value!=form.password_con.value){
		alert('<?=$strErrPassword?>');
		form.password.focus();
		return false;
	}

	if (isNull(form.nickname, '<?=$strErrNull?>')) return false;
	if (isNull(form.email, '<?=$strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?="$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">
    <div class="box">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="6" height="20"><img src="images/main/content_lt.gif" width="6" height="21"></td>
          <td height="21" background="images/main/content_top.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rt.gif" width="6" height="21"></td>
        </tr>
        <tr>
          <td width="6" background="images/main/content_left.gif">&nbsp;</td>
          <td bgcolor="#FFFFFF" >
            <div class="contenttitle"><img src="images/content/edituser.gif" width="12" height="11">
              <?=$title?>
            </div>
            <br>
            <div class="subcontent">
              <? if ($ActionMessage!="") { ?>
              <br>
              <fieldset>
              <legend>
              <?=$strErrorInfo?>
              </legend>
              <div align="center">
                <table border="0" cellpadding="2" cellspacing="1">
                  <tr>
                    <td><span class="alertinfo">
                      <?=$ActionMessage?>
                      </span></td>
                  </tr>
                </table>
              </div>
              </fieldset>
              <br>
              <? } ?>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-input">
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strLoginUserID?>
                  </td>
                  <td width="90%"> <b><?echo $_SESSION['username'];?></b> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strLoginPassword?>
                  </td>
                  <td width="90%">
                    <input name="password" class="textbox" type="password" size="30" maxlength="20" value="">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strLoginPassword.$strUserAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strConfigPassword?>
                  </td>
                  <td width="90%">
                    <input name="password_con" class="textbox" type="password" size="30" maxlength="20" value="">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strPassAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strNickName?>
                  </td>
                  <td width="90%">
                    <input name="nickname" class="textbox" type="text" size="30" maxlength="20" value="<?=$nickname?>">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strNickName.$strNikeAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strEmail?>
                  </td>
                  <td width="90%">
                    <input name="email" class="textbox" type="TEXT" size="30" maxlength=100 value="<?=$email?>">
                    <input name="isHiddenEmail" type="checkbox" value="1" <? if ($isHiddenEmail=="1") { echo "checked"; }?>/>
                    <?=$strHiddenEmail?>
                  </td>
                </tr>
                <tr>
                  <td width="10%" nowrap>
                    <?=$strUserBlog?>
                  </td>
                  <td width="90%">
                    <input name="homePage" class="textbox" type="TEXT" size="30" maxlength=100 value="<?=$homePage?>">
                  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strSave?>" onClick="Javascript:onclick_update(this.form)">
            </div>
          </td>
          <td width="6" background="images/main/content_right.gif">&nbsp;</td>
        </tr>
        <tr>
          <td width="6" height="20"><img src="images/main/content_lb.gif" width="6" height="20"></td>
          <td height="20" background="images/main/content_bottom.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rb.gif" width="6" height="20"></td>
        </tr>
      </table>
    </div>
  </div>
</form>
<? dofoot(); ?>

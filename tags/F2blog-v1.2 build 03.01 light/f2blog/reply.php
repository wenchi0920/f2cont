<?php 
include_once("include/function.php");
include_once("include/cache.php");

//过滤IP
if (!filter_ip(getip())){
    header("HTTP/1.0 404 Not Found");
	exit;
}

$postid=isset($_REQUEST['postid'])?intval($_REQUEST['postid']):"";
$id=isset($_REQUEST['id'])?intval($_REQUEST['id']):"";
$check_info=false;

//判断是否正常进入该页
if ($_GET['load']=="read" && is_numeric($postid) && is_numeric($id) && is_numeric($_GET['page'])){//评论
	$Title="$strCommentsReplyTitle";
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
}else if ($_GET['load']=="guestbook" && is_numeric($postid) && is_numeric($_GET['page'])){//留言
	$Title="$strGuestBookReplyTitle";
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
}else{
	header("HTTP/1.0 404 Not Found");
	exit;
}

//读取验证码的图片
$validate_image="include/image_firefox.inc.php";

//保存留言内容
if (!empty($_GET['action']) && $_GET['action']=="save"){
	$check_info=true;
	if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		if (empty($_SESSION['rights']) && empty($_POST['username'])) {
			$ActionMessage="$strGuestBookBlankError";
			$check_info=false;
		}
		//检测验证码
		if ($check_info && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['isValidateCode']==1){
			$ActionMessage=$strGuestBookValidError;
			$check_info=false;
		}
		//过滤名称与IP
		if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
			$ActionMessage=$strGuestBookFilter.$filter_name;
			$check_info=false;
		}
		//字数是否超过了$settingInfo['commLength']
		if ($check_info && strlen($_POST['message'])>$settingInfo['commLength']){
			$ActionMessage=str_replace("1",$settingInfo['commLength'],$strCommentsLengthError);
			$check_info=false;
		}
		//检测是否在规定的时候内发言
		if (!empty($_SESSION['replytime']) && $_SESSION['replytime']>time()-$settingInfo['commTimerout']){
			$ActionMessage=$strUserCommentTime;
			$check_info=false;
		}
	}

	if ($check_info && empty($_POST['message'])){
		$ActionMessage="$strGuestBookBlankError";
		$check_info=false;
	}

	//檢查用戶在此處登錄
	if ($check_info && !empty($_POST['username'])) {
		if (!empty($_POST['username'])) $_POST['username']=safe_convert($_POST['username']);
		if (!empty($_POST['replypassword'])) $_POST['replypassword']=safe_convert($_POST['replypassword']);

		$sql="SELECT username,role,password FROM {$DBPrefix}members WHERE username='{$_POST['username']}' or nickname='{$_POST['username']}'";
		$userInfo = $DMC->fetchArray($DMC->query($sql));

		if ($userInfo || $settingInfo['master']==$_POST['username']) {
			if (md5($_POST['replypassword'])!=$userInfo['password']) {
				$ActionMessage=$strLoginErrUserPWD;
				$check_info=false;
			} else {
				if ($settingInfo['loginStatus']==0){
					$_SESSION['username'] = $userInfo['username'];
					$_SESSION['password'] = md5($_POST['replypassword']);
					$_SESSION['rights']   = $userInfo['role'];
				}
				$_POST['username'] = $userInfo['username'];
			}
		}
	}

	if ($check_info){
		$_POST['isSecret']=(isset($_POST['isSecret']))?$_POST['isSecret']:0;
		$author=(isset($_POST['username']))?$_POST['username']:$_SESSION['username'];
		$replypassword=(isset($_POST['replypassword']))?md5($_POST['replypassword']):"";
		$_POST['homepage']=(isset($_POST['homepage']))?$_POST['homepage']:"";
		$_POST['email']=(isset($_POST['email']))?$_POST['email']:"";
		$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";

		if ($_GET['load']=="read"){//评论
			$sql="insert into ".$DBPrefix."comments(author,password,logId,homepage,email,face,ip,content,postTime,isSecret,parent) values('$author','$replypassword','".$id."','".encode($_POST['homepage'])."','".encode($_POST['email'])."','".substr(encode($_POST['bookface']),4)."','".getip()."','".encode($_POST['message'])."','".time()."','".encode($_POST['isSecret'])."','$postid')";
		}else{
			$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent,face) values('$author','$replypassword','".encode($_POST['homepage'])."','".encode($_POST['email'])."','".getip()."','".encode($_POST['message'])."','".time()."','".encode($_POST['isSecret'])."','$postid','".substr(encode($_POST['bookface']),4)."')";
		}
		//echo $sql;
		$DMC->query($sql);
	
		//保存时间
		$_SESSION['replytime']=time();
	
		//更新cache
		if ($_GET['load']=="read"){//评论
			//更新LOGS评论数量
			settings_recount("comments");
			settings_recache();
			$DMC->query("UPDATE ".$DBPrefix."logs SET commNums=commNums+1 WHERE id='$id'");

			//更新cache
			recentComments_recache();
			logs_sidebar_recache($arrSideModule);
		}else{
			settings_recount("guestbook");
			settings_recache();
			recentGbooks_recache();
			logs_sidebar_recache($arrSideModule);
		}

		//不使用Ajax技术
		if (strpos(";$settingInfo[ajaxstatus];","G")<1){
			$load=$_GET['load'];
			$page=$_GET['page'];
			echo "<script language=javascript> \n";
			if ($_GET['load']=="read"){
				if ($settingInfo['rewrite']==0) $gourl="index.php?load=$load&id=$id&page=$page";
				if ($settingInfo['rewrite']==1) $gourl="rewrite.php/$load-$id-$page";
				if ($settingInfo['rewrite']==2) $gourl="$load-$id-$page";
				echo " opener.location.href='$gourl{$settingInfo['stype']}';\n";
				echo " opener.reload;\n";
			}else{
				if ($settingInfo['rewrite']==0) $gourl="index.php?load=$load&page=$page";
				if ($settingInfo['rewrite']==1) $gourl="rewrite.php/$load-$page";
				if ($settingInfo['rewrite']==2) $gourl="$load-$page";
				echo " opener.location.href='$gourl{$settingInfo['stype']}';\n";
				echo " opener.reload;\n";
			}
			echo " window.close();\n";
			echo "</script> \n";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<title><?php echo $Title?></title>
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" /><!--F2blog共用CSS-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/global.css" type="text/css" media="all" /><!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/layout.css" type="text/css" media="all" /><!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/typography.css" type="text/css" media="all" /><!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/link.css" type="text/css" media="all" /><!--超链接样式表-->
<?php 
if (file_exists(F2BLOG_ROOT."/skins/".$blogSkins."/UBB")){
	$ubb_path="skins/".$blogSkins."/UBB";
}else{
	$ubb_path="editor/ubb";
}
echo "<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"$ubb_path/editor.css\" type=\"text/css\" /><!--UBB样式-->\n";
?>
<script type="text/javascript" src="include/common.js.php"></script>
<script type="text/javascript" src="include/f2blog_ajax.js"></script>
<script type="text/javascript">
<!--
<?php 
if ($check_info==true && strpos(";$settingInfo[ajaxstatus];","G")>0){
	if ($_GET['load']=="read"){
		echo "f2_ajax_newwin_page(\"f2blog_ajax.php?ajax_display=comment_page&load=".$_GET['load']."&id=$id&page=".$_GET['page']."\") \n";
	}else{
		echo "f2_ajax_newwin_page(\"f2blog_ajax.php?ajax_display=gbook_page&load=".$_GET['load']."&page=".$_GET['page']."\") \n";
	}
}
?>
function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

function onclick_update(form) {
	<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		if (empty($_SESSION['rights'])) {
	?>
		if (strlen(form.username.value)<3){
			alert('<?php echo $strNickLengMax?>');
			form.username.focus();
			return false;
		}
		if (/[\'\"\\]/.test(form.username.value)){
			alert('<?php echo $strNickNameAlert?>');
			form.username.focus();
			return false;
		}
		<?php  } 
		if ($settingInfo['isValidateCode']==1){?>
			if (!(/[0-9]{5,6}/.test(form.validate.value))){
				alert('<?php echo $strGuestBookInputValid?>');
				form.validate.focus();
				return false;
			}
		<?php }
	}?>
	if (isNull(form.message, '<?php echo $strGuestBookInputContent?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$posturl&action=save"?>";
	form.submit();
}


function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?php 
if (!empty($ActionMessage)){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
</head>
<body>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?php echo $Title?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
      <table width="100%" cellpadding="0" cellspacing="0">
		<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
			if (empty($_SESSION['rights'])) {
		?>
			<tr>
			  <td width="18%" align="right"><strong><?php echo $strGuestBookName?></strong></td>
			  <td width="29%" align="left" style="padding:3px;">
				<input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?php echo (!empty($_POST['username']))?$_POST['username']:""?>"/>
			  </td>
			  <td width="18%" align="right" style="padding:3px;"><strong>
				<?php echo $strGuestBookPassword?>
			  </strong></td>
			  <td width="35%" align="left" style="padding:3px;">
				<input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?php echo (!empty($_POST['replypassword']))?$_POST['replypassword']:""?>"/>
			  </td>
			</tr>
			<?php  }?>
			<tr>
			  <td width="18%" align="right"><strong>
				<?php echo $strGuestBookHomepage?>
			  </strong></td>
			  <td width="29%" align="left" style="padding:3px;">
				<input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?php echo !empty($_POST['homepage'])?$_POST['homepage']:""?>"/>    
			  </td>
			  <td width="18%" align="right" style="padding:3px;"><strong>
				<?php echo $strGuestBookEmail?>
			  </strong></td>
			  <td width="35%" align="left" style="padding:3px;">
				<input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?php echo !empty($_POST['email'])?$_POST['email']:""?>"/>
			  </td>
			</tr>   
			<?php if ($settingInfo['isValidateCode']==1){?>
			<tr>
			  <td width="18%" align="right"><strong>
				<?php echo $strGuestBookValid?>
			  </strong></td>
			  <td width="29%" align="left" style="padding:3px;">
				<input name="validate" type="text" size="5" class="userpass" maxlength="10"/>
			   <?php if (function_exists('imagecreate')){?>
					<img src="<?php echo $validate_image?>" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
			   <?php 
				}else{
					echo $_SESSION['backValidate']=validCode(6);
				}
			   ?>	
			  </td>
			  <td width="18%" align="right" style="padding:3px;"><strong>
				<?php echo $strGuestBookOption?>
			  </strong></td>
			  <td width="35%" align="left" style="padding:3px;">
				<label for="label5">
				<input name="isSecret" type="checkbox" id="label5" value="1" <?php echo (!empty($_POST['isSecret']) && $_POST['isSecret']=="1")?"checked=\"checked\"":""?>/>
				<?php echo $strGuestBookOptionHidden?>
				</label>
			  </td>
			</tr>		
			<?php }else{?>
			<tr>
			  <td width="18%" align="right"><strong>
				<?php echo $strGuestBookOption?>
			  </strong></td>
			  <td width="29%" align="left" style="padding:3px;">
				<label for="label5">
				<input name="isSecret" type="checkbox" id="label5" value="1" <?php echo (!empty($_POST['isSecret']))?"checked=\"checked\"":""?>/>
				<?php echo $strGuestBookOptionHidden?>
				</label>		  
			  </td>
			  <td width="18%" align="right" style="padding:3px;">&nbsp; </td>
			  <td width="35%" align="left" style="padding:3px;">&nbsp; </td>
			</tr>
			<?php }?>
		<?php }?>
		<?php if ($settingInfo['gbface']==1){?>
		<tr>
		  <td align="right" width="18%" valign="top"><strong><?php echo $strGuestBookFace?></strong></td>
		  <td colspan="3" style="padding:2px;" align="left">
			   <input type="hidden" name="bookface" value="face1"/>
			   <a href="javascript:void(0)" class="CFace" id="face1" onclick="selectFace('face1')"><img src="images/avatars/1.gif" alt="" border="0" width="45px" height="45px" /></a>
			   <?php for ($face=2;$face<8;$face++){?>
				<a href="javascript:void(0)" class="LFace" id="face<?php echo $face?>" onclick="selectFace('face<?php echo $face?>')"><img src="images/avatars/<?php echo $face?>.gif" alt="" border="0" width="45px" height="45px" /></a>
			   <?php }?>
		  </td></tr>
		<tr>
		<?php }?>
        <tr>
          <td width="18%" align="right" valign="top" style="padding-top:6px;"><strong><?php echo $strGuestBookContent?></strong><br />
          </td>
          <td colspan="3" style="padding:2px;">
			<?php include("include/ubb.inc.php")?>
          </td>
        </tr>
		<tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?php echo $strGuestBookSubmit?>" onclick="Javascript:onclick_update(this.form)"/>
            <input name="reback" type="reset" class="userbutton" value="<?php echo $strGuestBookClose?>" onclick="window.close()"/>
			<input name="postid" type="hidden" value="<?php echo $postid?>"/>
			<?php if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?php echo $id?>"/>
			<?php }?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
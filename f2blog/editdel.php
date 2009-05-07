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

if (empty($_GET['action'])) $_GET['action']="";
//判断是否正常进入该页
if ($_GET['load']=="read" && is_numeric($postid) && is_numeric($id) && is_numeric($_GET['page'])){//评论
	$Title=$strCommentsEditTitle;
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
	$op_table=$DBPrefix."comments";
	if (isset($_POST['homepage'])){
		$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";
		$op_update=",homepage='".encode($_POST['homepage'])."',email='".encode($_POST['email'])."',face='".substr(encode($_POST['bookface']),4)."'";
	}else{
		$op_update="";
	}
}else if ($_GET['load']=="guestbook" && is_numeric($postid) && is_numeric($_GET['page'])){//留言
	$Title=$strGuestBookEditTitle;
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
	$op_table=$DBPrefix."guestbook";
	if (isset($_POST['homepage'])){
		$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";
		$op_update=",homepage='".encode($_POST['homepage'])."',email='".encode($_POST['email'])."',face='".substr(encode($_POST['bookface']),4)."'";
	}else{
		$op_update="";
	}
}else{
	header("HTTP/1.0 404 Not Found");
	exit;
}

//读取验证码的图片
$validate_image="include/image_firefox.inc.php";

//检查访问权限
if (!empty($_POST['action'])) $_GET['action']=$_POST['action'];
if (!empty($_GET['action']) && ($_GET['action']=="next" || $_GET['action']=="save")){
	if (!empty($_POST['username'])) $_POST['username']=safe_convert($_POST['username']);
	if (!empty($_POST['replypassword'])) $_POST['replypassword']=safe_convert($_POST['replypassword']);

	if (empty($_POST['replypassword']) && empty($_SESSION['rights'])){
		$ActionMessage=$strGuestBookPasswordError;
		$_GET['action']="error";
	}else{
		if (!empty($_SESSION['rights']) && ($_SESSION['rights']=="member" or $_SESSION['rights']=="author")){
			$sql="select * from $op_table where id='$postid' and author='{$_SESSION['username']}'";
		} elseif (!empty($_SESSION['rights']) && ($_SESSION['rights']=="admin" or $_SESSION['rights']=="editor")){
			$sql="select * from $op_table where id='$postid'";
		}else{
			$sql="select * from $op_table where id='".$postid."' and password='".md5($_POST['replypassword'])."'";
		}

		//如果是会员及管理员等，无需检测密码。
		$result=$DMC->query($sql);
		if (!$arr_result=$DMC->fetchArray($result)){
			$ActionMessage=$strGuestBookRightsError;
			$_GET['action']="error";
		}else{
			//编辑，读出原记录
			if ($_POST['chkaction']=="edit"){
				$username=$arr_result['author'];
				$homepage=$arr_result['homepage'];
				$email=$arr_result['email'];

				$message=str_replace("<br />","",dencode($arr_result['content']));
				$postTime=$arr_result['postTime'];
				$isSecret=$arr_result['isSecret'];
				$old_password=$arr_result['password'];
			}

			//删除记录
			if ($_POST['chkaction']=="del"){
				$del_id=0;
				if ($arr_result['parent']=="0"){//删除的是主项目，其子项目也要清除
					$sql="select * from $op_table where parent='".$arr_result['id']."'";
					$arr_result=$DMC->fetchQueryAll($DMC->query($sql));
					foreach($arr_result as $key=>$value){
						$sql="delete from $op_table where id='".$value['id']."'";
						$DMC->query($sql);
						//echo $sql."<br />";
						$del_id++;
					}
				}
				$sql="delete from $op_table where id='".$postid."'";
				$DMC->query($sql);
				//echo $sql."<br />";
				//exit;
				$del_id++;

				//更新cache
				if ($_GET['load']=="read"){//评论
					//更新LOGS评论数量
					settings_recount("comments");
					settings_recache();

					$DMC->query("UPDATE ".$DBPrefix."logs SET commNums=commNums-$del_id WHERE id='$id'");

					//更新cache
					recentComments_recache();
					logs_sidebar_recache($arrSideModule);
				}else{
					settings_recount("guestbook");
					settings_recache();
					recentGbooks_recache();
					logs_sidebar_recache($arrSideModule);
				}

				$check_info=true;

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
	}
}

//保存留言内容
if (!empty($_GET['action']) && $_GET['action']=="save"){
	if ($_POST['chkaction']=="edit"){
		$check_info=true;
		if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
			if (empty($_SESSION['rights']) && empty($_POST['username'])) {
				$ActionMessage="$strGuestBookBlankError";
				$check_info=false;
			}
			//检测验证码
			if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
			if ($check_info && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['isValidateCode']==1){
				$ActionMessage=$strGuestBookValidError;
				$check_info=false;
			}
			//字数是否超过了$settingInfo['commLength']
			if ($check_info && strlen($_POST['message'])>$settingInfo['commLength']){
				$ActionMessage=str_replace("1",$settingInfo['commLength'],$strCommentsLengthError);
				$check_info=false;
			}
			//过滤名称与IP
			if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
				$ActionMessage=$strGuestBookFilter.$filter_name;
				$check_info=false;
			}
			/*/检测是否在规定的时候内发言
			if ($_SESSION['replytime'] && $_SESSION['replytime']>time()-$settingInfo['commTimerout']){
				$ActionMessage=$strUserCommentTime;
				$check_info=false;
			}*/
		}

		if ($check_info && $_POST['message']==""){
			$ActionMessage="$strGuestBookBlankError";
			$check_info=false;
		}

		if ($check_info){
			$author=(!empty($_POST['username']))?$_POST['username']:$_SESSION['username'];
			$replypassword=(!empty($_POST['replypassword']))?md5($_POST['replypassword']):$old_password;
			$_POST['isSecret']=(isset($_POST['isSecret']))?intval($_POST['isSecret']):0;
			
			$sql="update $op_table set password='$replypassword',ip='".getip()."',content='".encode($_POST['message'])."',isSecret='".encode($_POST['isSecret'])."'$op_update where id='".$postid."'";
			//echo $sql;
			$DMC->query($sql);
			//exit;

			//更新cache
			if ($_GET['load']=="read"){//评论
				recentComments_recache();
				logs_sidebar_recache($arrSideModule);
			}else{
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

function onclick_update(form,action) {
	<?php if (($_GET['action']==""  || $_GET['action']=="error") && empty($_SESSION['rights'])){?> 
		if (isNull(form.replypassword, '<?php echo $strGuestBookInputPassword?>')) return false;
	<?php }else if (($_GET['action']=="next" || $_GET['action']=="save") && (empty($_SESSION['rights']) or $_SESSION['rights']=="member")){?>
		<?php  if (empty($_SESSION['rights'])) { ?>
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
		<?php }?>
		if (isNull(form.message, '<?php echo $strGuestBookInputContent?>')) return false;
	<?php }?>

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$posturl&action="?>"+action;
	form.submit();
}

function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?php if ((empty($_GET['action']) || $_GET['action']=="error") && (!empty($_SESSION['rights']) && $_SESSION['rights']!="admin")){?> 
	function setfocus(){
		document.frm.replypassword.focus();
	}
<?php }?>

<?php 
if (!empty($ActionMessage)){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
</head>
<?php if ((empty($_GET['action']) || $_GET['action']=="error") && (!empty($_SESSION['rights']) && $_SESSION['rights']!="admin")){?> 
<body onload="setfocus()">
<?php }else{?>
<body>
<?php }?>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?php echo $Title?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
	   <?php if (empty($_GET['action']) || $_GET['action']=="error"){?> 	  
	   <table width="100%" cellpadding="6" cellspacing="6">
        <tr>
          <td align="right" width="80">&nbsp;</td>
          <td align="left" style="padding:3px;">
            <input name="chkaction" type="radio" value="edit" class="userpass" checked/> <?php echo $strEdit?>
			&nbsp;&nbsp;&nbsp;
			<input name="chkaction" type="radio" value="del" class="userpass" /> <?php echo $strDelete?>
          </td>
        </tr>
		<?php if (empty($_SESSION['rights'])){?>
        <tr>
          <td align="right" width="80"><strong><?php echo $strGuestBookPassword?></strong></td>
          <td align="left" style="padding:3px;">
            <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?php echo (!empty($_SESSION['replypassword']))?$_SESSION['replypassword']:""?>"/>
          </td>
        </tr>
		<?php }?>
        <tr>
		  <td align="right" width="80">&nbsp;</td>
          <td align="left" style="padding:3px;">
            <input name="save" type="submit" class="userbutton" value="<?php echo $strGuestBookNext?>"/>
            <input name="reback" type="reset" class="userbutton" value="<?php echo $strGuestBookClose?>" onclick="window.close()"/>
			<input name="action" type="hidden" value="next"/>
			<input name="postid" type="hidden" value="<?php echo $postid?>"/>
			<?php if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?php echo $id?>"/>
			<?php }?>
          </td>
        </tr>
      </table>
	  <?php }else{?>
      <table width="100%" cellpadding="0" cellspacing="0">
		<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		  if (empty($_SESSION['rights'])) {
		?>
        <tr>
          <td align="right" width="17%"><strong><?php echo $strGuestBookName?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?php echo $username?>" />
          </td>
          <td width="17%" align="right" style="padding:3px;"><strong>
            <?php echo $strGuestBookPassword?>
          </strong></td>
		  <td width="32%" align="left" style="padding:3px;">
		    <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?php echo $_POST['replypassword']?>"/>
		  </td>
        </tr>
		<?php  }		
		if (empty($_SESSION['rights'])){//留言?>
        <tr>
          <td align="right" width="17%"><strong>
            <?php echo $strGuestBookHomepage?>
          </strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?php echo $homepage?>"/>
          </td>
          <td width="17%" align="right" style="padding:3px;"><strong>
            <?php echo $strGuestBookEmail?>
          </strong></td>
          <td width="32%" align="left" style="padding:3px;">
            <input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?php echo $email?>"/>
          </td>
        </tr>	
		<?php }//留言?>		
		<?php if ($settingInfo['isValidateCode']==1){?>
        <tr>
          <td align="right" width="17%"><strong><?php echo $strGuestBookValid?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
          <input name="validate" type="text" size="5" class="userpass" maxlength="10"/>
		   <?php if (function_exists('imagecreate')){?>
				<img src="<?php echo $validate_image?>" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
		   <?php }else{
				echo $_SESSION['backValidate']=validCode(6);
			 }
		   ?>
		  </td>
          <td width="17%" align="right" style="padding:3px;"><strong>
          <?php echo $strGuestBookOption?>
          </strong></td>
          <td width="32%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?php echo ($isSecret)?"checked=\"checked\"":""?>/>
            <?php echo $strGuestBookOptionHidden?>
            </label>
		  </td>
        </tr>   
		<?php }else{?>
        <tr>
          <td align="right" width="17%"><strong><?php echo $strGuestBookOption?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?php echo ($isSecret)?"checked=\"checked\"":""?>/>
            <?php echo $strGuestBookOptionHidden?>
            </label>
		  </td>
          <td width="17%" align="right" style="padding:3px;">&nbsp;</td>
          <td width="32%" align="left" style="padding:3px;">&nbsp;</td>
        </tr>  
		<?php }//valid?>
		<?php }//admin?>
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
          <td align="right" width="17%" valign="top"><strong><?php echo $strGuestBookContent?></strong><br />
          </td>
          <td colspan="3" style="padding:2px;">
           <?php include("include/ubb.inc.php")?>
          </td>
        </tr>
        <tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?php echo $strGuestBookSubmit?>" onclick="Javascript:onclick_update(this.form,'save')"/>
            <input name="reback" type="reset" class="userbutton" value="<?php echo $strGuestBookClose?>" onclick="window.close()"/>
			<input name="postid" type="hidden" value="<?php echo $postid?>"/>
			<?php if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?php echo $id?>"/>
			<?php }?>
			<input name="chkaction" type="hidden" value="<?php echo $_POST['chkaction']?>"/>
          </td>
        </tr>
      </table>
	  <?php }?>
    </form>
  </div>
</div>
</body>
</html>
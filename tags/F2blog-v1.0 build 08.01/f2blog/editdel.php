<?
include_once("include/function.php");

//过滤IP
if (!filter_ip(getip())){
    header("HTTP/1.0 404 Not Found");
	exit;
}

$postid=$_REQUEST['postid'];
$id=$_REQUEST['id'];

//判断是否正常进入该页
if ($_GET['load']=="read" && $postid!="" && $id!=""){//评论
	$Title=$strCommentsEditTitle;
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
	$op_table=$DBPrefix."comments";

}else if ($_GET['load']=="guestbook" && $postid!=""){//留言
	$Title=$strGuestBookEditTitle;
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];
	$op_table=$DBPrefix."guestbook";
	$op_update=",homepage='".encode($_POST['homepage'])."',email='".encode($_POST['email'])."'";

}else{//非法进入
    header("HTTP/1.0 404 Not Found");
	exit;
}

//读取验证码的图片
$validate_image="include/image_firefox.inc.php";

//检查访问权限
if ($_POST['action']) $_GET['action']=$_POST['action'];
if ($_GET['action']=="next" || $_GET['action']=="save"){
	//echo $_POST['replypassword']."<br>".$postid;
	if ($_POST['replypassword']=="" && $_SESSION['rights']!="admin"){
		$ActionMessage=$strGuestBookPasswordError;
		$_GET['action']="error";
	}else{
		if ($_SESSION['rights']=="admin"){ //如果是管理员，无需检测密码。
			$sql="select * from $op_table where id='".$postid."'";
		}else{
			$sql="select * from $op_table where id='".$postid."' and password='".md5($_POST['replypassword'])."'";
		}
		$result=$DMF->query($sql);
		if (!$arr_result=$DMF->fetchArray($result)){
			$ActionMessage=$strGuestBookRightsError;
			$_GET['action']="error";
		}else{
			//编辑，读出原记录
			if ($_POST['chkaction']=="edit"){
				$username=$arr_result['author'];
				if ($_GET['load']=="guestbook"){//留言
				$homepage=$arr_result['homepage'];
				$email=$arr_result['email'];
				}
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
					$result=$DMF->query($sql);
					while ($sub_result=$DMF->fetchArray($result)){
						$sql="delete from $op_table where id='".$sub_result['id']."'";
						$DMF->query($sql);
						//echo $sql."<br>";
						$del_id++;
					}
				}
				$sql="delete from $op_table where id='".$postid."'";
				$DMF->query($sql);
				//echo $sql."<br>";
				//exit;
				$del_id++;

				//更新cache
				if ($_GET['load']=="read"){//评论
					recentComments_recache();
					//更新LOGS评论数量
					total_comments($id,"-".$del_id);
				}else{
					recentGbooks_recache();
				}

				echo "<script language=javascript> \n";
				if ($_GET['load']=="read"){
					echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."&id=$id';\n";
				}else{
					echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."';\n";
				}
				echo " window.close();\n";
				echo "</script> \n";
			}
		}
	}
}

//保存留言内容
if ($_GET['action']=="save"){
	if ($_POST['chkaction']=="edit"){
		$check_info=true;
		if ($_SESSION['rights']!="admin"){
			//检测是否输入完全
			if ($_POST['username']=="" || $_POST['message']=="" || ($settingInfo['isValidateCode']==1 && $_POST['validate']=="")){
				$ActionMessage="$strGuestBookBlankError";
				$check_info=false;
			}
			//检测验证码
			if ($check_info && $_POST['validate']!=$_SESSION['validate'] && $settingInfo['isValidateCode']==1){
				$ActionMessage=$strGuestBookValidError;
				$check_info=false;
			}
			//过滤名称与IP
			if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
				$ActionMessage=$strGuestBookFilter.$filter_name;
				$check_info=false;
			}
		}

		if ($check_info){
			$author=($_POST['username'])?$_POST['username']:$_SESSION['username'];
			$replypassword=($_POST['replypassword'])?md5($_POST['replypassword']):$old_password;
					
			$sql="update $op_table set author='$author',password='$replypassword',ip='".getip()."',content=\"".encode($_POST['message'])."\",postTime='".time()."',isSecret='".$_POST['isSecret']."'$op_update where id='".$postid."'";
			//echo $sql;
			$DMF->query($sql);
			//exit;

			//更新cache
			if ($_GET['load']=="read"){//评论
				recentComments_recache();		
			}else{
				recentGbooks_recache();
			}

			echo "<script language=javascript> \n";
			if ($_GET['load']=="read"){
				echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."&id=$id';\n";
			}else{
				echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."';\n";
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
<meta name="robots" content="all" />
<meta name="author" content="<?=$blogEmail?>,<?=$blogMaster?>" />
<meta name="Copyright" content="<?=$blogCopyright?>" />
<meta name="keywords" content="<?=$blogKeywords?>" />
<meta name="description" content="<?=$blogName?> - <?=$blogTitle?>" />
<title>
<?=$Title?>
</title>
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" />
<!--F2blog共用CSS-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/global.css" type="text/css" media="all" />
<!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/layout.css" type="text/css" media="all" />
<!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/typography.css" type="text/css" media="all" />
<!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/link.css" type="text/css" media="all" />
<!--超链接样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/UBB/editor.css" type="text/css" media="all" />
<!--UBB编辑器代码-->
<script type="text/javascript" src="include/common.js"></script>
<script style="javascript">
<!--
function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

function onclick_update(form,action) {
	<?if (($_GET['action']==""  || $_GET['action']=="error") && $_SESSION['rights']!="admin"){?> 
		if (isNull(form.replypassword, '<?=$strGuestBookInputPassword?>')) return false;
	<?}else if (($_GET['action']=="next" || $_GET['action']=="save") && $_SESSION['rights']!="admin"){?>
		if (isNull(form.username, '<?=$strGuestBookInputName?>')) return false;
		<?if ($settingInfo['isValidateCode']==1 && $_SESSION['rights']!="admin"){?>
			if (isNull(form.validate, '<?=$strGuestBookInputValid?>')) return false;
		<?}?>
		if (isNull(form.message, '<?=$strGuestBookInputContent?>')) return false;
	<?}?>

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$posturl&action="?>"+action;
	form.submit();
}

function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?if (($_GET['action']==""  || $_GET['action']=="error") && $_SESSION['rights']!="admin"){?> 
	function setfocus(){
		document.frm.replypassword.focus();
	}
<?}?>

<?
if ($ActionMessage){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
</head>
<?if (($_GET['action']==""  || $_GET['action']=="error") && $_SESSION['rights']!="admin"){?> 
<body onload="setfocus()">
<?}else{?>
<body>
<?}?>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?=$Title?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
	   <?if ($_GET['action']=="" || $_GET['action']=="error"){?> 	  
	   <table width="100%" cellpadding="6" cellspacing="6">
        <tr>
          <td align="right" width="80">&nbsp;</td>
          <td align="left" style="padding:3px;">
            <input name="chkaction" type="radio" value="edit" class="userpass" checked/> <?=$strEdit?>
			&nbsp;&nbsp;&nbsp;
			<input name="chkaction" type="radio" value="del" class="userpass" /> <?=$strDelete?>
          </td>
        </tr>
		<?if ($_SESSION['rights']!="admin"){?>
        <tr>
          <td align="right" width="80"><strong><?=$strGuestBookPassword?></strong></td>
          <td align="left" style="padding:3px;">
            <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?=$_SESSION['replypassword']?>"/>
          </td>
        </tr>
		<?}?>
        <tr>
		  <td align="right" width="80">&nbsp;</td>
          <td align="left" style="padding:3px;">
            <input name="save" type="submit" class="userbutton" value="<?=$strGuestBookNext?>"/>
            <input name="reback" type="reset" class="userbutton" value="<?=$strGuestBookClose?>" onclick="window.close()"/>
			<input name="action" type="hidden" value="next"/>
			<input name="postid" type="hidden" value="<?=$postid?>"/>
			<?if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?=$id?>"/>
			<?}?>
          </td>
        </tr>
      </table>
	  <?}else{?>
      <table width="100%" cellpadding="0" cellspacing="0">
		<?if ($_SESSION['rights']!="admin"){?>
        <tr>
          <td align="right" width="17%"><strong><?=$strGuestBookName?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?=$username?>" />
          </td>
          <td width="17%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookPassword?>
          </strong></td>
		  <td width="32%" align="left" style="padding:3px;">
		    <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?=$_POST['replypassword']?>"/>
		  </td>
        </tr>
		<?if ($_GET['load']=="guestbook"){//留言?>
        <tr>
          <td align="right" width="17%"><strong>
            <?=$strGuestBookHomepage?>
          </strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?=$homepage?>"/>
          </td>
          <td width="17%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookEmail?>
          </strong></td>
          <td width="32%" align="left" style="padding:3px;">
            <input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?=$email?>"/>
          </td>
        </tr>	
		<?}//留言?>		
		<?if ($settingInfo['isValidateCode']==1){?>
        <tr>
          <td align="right" width="17%"><strong><?=$strGuestBookValid?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
          <input name="validate" type="text" size="5" class="userpass" maxlength="10"/>
		   <?if (function_exists(imagecreate)){?>
				<img src="<?=$validate_image?>" alt="<?=$strGuestBookValidImage?>" align="absmiddle"/>
		   <?
			}else{
				echo validCode(6);
			}
		   ?>	         
          <td width="17%" align="right" style="padding:3px;"><strong>
          <?=$strGuestBookOption?>
          </strong></td>
          <td width="32%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($isSecret)?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>
		  </td>
        </tr>   
		<?}else{?>
        <tr>
          <td align="right" width="17%"><strong><?=$strGuestBookOption?></strong></td>
          <td width="34%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($isSecret)?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>
		  </td>
          <td width="17%" align="right" style="padding:3px;">&nbsp;</td>
          <td width="32%" align="left" style="padding:3px;">&nbsp;</td>
        </tr>  
		<?}//valid?>
		<?}//admin?>
        <tr>
          <td align="right" width="17%" valign="top"><strong><?=$strGuestBookContent?></strong><br/>
          </td>
          <td colspan="3" style="padding:2px;">
           <?include("include/ubb.inc.php")?>
          </td>
        </tr>
        <tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?=$strGuestBookSubmit?>" onClick="Javascript:onclick_update(this.form,'save')"/>
            <input name="reback" type="reset" class="userbutton" value="<?=$strGuestBookClose?>" onclick="window.close()"/>
			<input name="postid" type="hidden" value="<?=$postid?>"/>
			<?if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?=$id?>"/>
			<?}?>
			<input name="chkaction" type="hidden" value="<?=$_POST['chkaction']?>"/>
          </td>
        </tr>
      </table>
	  <?}?>
    </form>
  </div>
</div>
</body>
</html>
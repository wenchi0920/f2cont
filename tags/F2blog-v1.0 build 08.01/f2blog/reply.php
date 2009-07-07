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
	$Title="$strCommentsReplyTitle";
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];

}else if ($_GET['load']=="guestbook" && $postid!=""){//留言
	$Title="$strGuestBookReplyTitle";
	$posturl="$PHP_SELF?load=".$_GET['load']."&page=".$_GET['page'];

}else{//非法进入
    header("HTTP/1.0 404 Not Found");
	exit;
}

//读取验证码的图片
$validate_image="include/image_firefox.inc.php";


//保存留言内容
if ($_GET['action']=="save"){
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
		$replypassword=($_POST['replypassword'])?md5($_POST['replypassword']):"";
		if ($_GET['load']=="read"){//评论
			$sql="insert into ".$DBPrefix."comments(author,password,logId,ip,content,postTime,isSecret,parent) values('$author','$replypassword','".$id."','".getip()."',\"".encode($_POST['message'])."\",'".time()."','".$_POST['isSecret']."','$postid')";
		}else{
			$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent) values('$author','$replypassword','".encode($_POST['homepage'])."','".encode($_POST['email'])."','".getip()."',\"".encode($_POST['message'])."\",'".time()."','".$_POST['isSecret']."','".$_POST['postid']."')";
		}
		//echo $sql;
		$DMF->query($sql);
			
		//更新cache
		if ($_GET['load']=="read"){//评论
			recentComments_recache();
			//更新LOGS评论数量
			total_comments($id,1);
		}else{
			recentGbooks_recache();
		}

		echo "<script language=javascript> \n";
		if ($_GET['load']=="read"){
			echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."&id=$id#comm_top';\n";
		}else{
			echo " opener.location.href='index.php?load=".$_GET['load']."&page=".$_GET['page']."';\n";
		}
		echo " window.close();\n";
		echo "</script> \n";
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

function onclick_update(form) {
	<?if ($_SESSION['rights']!="admin"){?>
		if (isNull(form.username, '<?=$strGuestBookInputName?>')) return false;
		<?if ($settingInfo['isValidateCode']==1){?>
			if (isNull(form.validate, '<?=$strGuestBookInputValid?>')) return false;
		<?}?>
	<?}?>
	if (isNull(form.message, '<?=$strGuestBookInputContent?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$posturl&action=save"?>";
	form.submit();
}


function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?
if ($ActionMessage){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
</head>
<body>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?=$Title?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
      <table width="100%" cellpadding="0" cellspacing="0">
		<?if ($_SESSION['rights']!="admin"){?>
        <tr>
          <td width="18%" align="right"><strong><?=$strGuestBookName?></strong></td>
          <td width="29%" align="left" style="padding:3px;">
            <input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?=$_POST['username']?>"/>
          </td>
          <td width="18%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookPassword?>
          </strong></td>
		  <td width="35%" align="left" style="padding:3px;">
		    <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?=$_POST['replypassword']?>"/>
		  </td>
        </tr>
		<?if ($_GET['load']=="guestbook"){//留言?>
        <tr>
          <td width="18%" align="right"><strong>
            <?=$strGuestBookHomepage?>
          </strong></td>
          <td width="29%" align="left" style="padding:3px;">
            <input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?=$_POST['homepage']?>"/>    
		  </td>
          <td width="18%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookEmail?>
          </strong></td>
          <td width="35%" align="left" style="padding:3px;">
            <input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?=$_POST['email']?>"/>
          </td>
	    </tr>   
		<?}//留言?>
		<?if ($settingInfo['isValidateCode']==1){?>
        <tr>
          <td width="18%" align="right"><strong>
            <?=$strGuestBookValid?>
          </strong></td>
          <td width="29%" align="left" style="padding:3px;">
            <input name="validate" type="text" size="5" class="userpass" maxlength="10"/>
		   <?if (function_exists(imagecreate)){?>
				<img src="<?=$validate_image?>" alt="<?=$strGuestBookValidImage?>" align="absmiddle"/>
		   <?
			}else{
				echo validCode(6);
			}
		   ?>	
		  </td>
          <td width="18%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookOption?>
          </strong></td>
          <td width="35%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($_POST['isSecret']=="1")?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>
		  </td>
        </tr>		
		<?}else{?>
        <tr>
          <td width="18%" align="right"><strong>
            <?=$strGuestBookOption?>
          </strong></td>
          <td width="29%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($_POST['isSecret']=="1")?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>		  
		  </td>
          <td width="18%" align="right" style="padding:3px;">&nbsp; </td>
          <td width="35%" align="left" style="padding:3px;">&nbsp; </td>
        </tr>
		<?}?>
		<?}//admin?>
        <tr>
          <td width="18%" align="right" valign="top"><strong><?=$strGuestBookContent?></strong><br/>
          </td>
          <td colspan="3" style="padding:2px;">
			<?include("include/ubb.inc.php")?>
          </td>
        </tr>
		<tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?=$strGuestBookSubmit?>" onClick="Javascript:onclick_update(this.form)"/>
            <input name="reback" type="reset" class="userbutton" value="<?=$strGuestBookClose?>" onclick="window.close()"/>
			<input name="postid" type="hidden" value="<?=$postid?>"/>
			<?if ($_GET['load']=="read"){?>
			<input name="id" type="hidden" value="<?=$id?>"/>
			<?}?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
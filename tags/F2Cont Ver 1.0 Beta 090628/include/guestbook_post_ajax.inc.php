<?php 
if (!defined('IN_F2CONT')) die ('Access Denied.');

$check_info=true;
$ActionMessage="";
$isSpam=false;

$allow_reply=filter_ip(getip());

if (!$allow_reply){
	$check_info=false;
	$ActionMessage="Didn't reply!";
}

if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
	//检测昵称是否合法
	if (empty($_SESSION['rights']) && check_nickname($_POST['username'])==0){
		$ActionMessage=$strNickLengMax;
		$check_info=false;
	}
	//检测验证码
	if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
	if ($check_info && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['isValidateCode']==1){
		$ActionMessage=$strGuestBookValidError;
		$check_info=false;
	}else{
		$_SESSION['backValidate'] = "";	//把验证码清除
	}

	//过滤名称与IP
	if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
		//$ActionMessage=$strGuestBookFilter;
		$ActionMessage=$strGuestBookFilter.$filter_name;
		$check_info=false;
		$isSpam=true;
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

/*
$style_list[]="預設=>default";
$style_list[]="刪除留言=>delete";
$style_list[]="不顯示留言=>close";
$style_list[]="隱藏留言=>hidden";
$settingInfo['spamfilter']	//	預設
*/
/* spam 過濾器強化	*/
//include(F2BLOG_ROOT."./include/guestbook.lib.php");
switch (trim($settingInfo['spamfilter'])){
	//	不新增留言
	case "delete":
		//$intSpamFiler=0;
		if ($check_info && !$isSpam) guestBookPost(0,0);
	break;
	
	//	新增留言，但不顯示 加入 spam 記號
	case "close":
		if ($isSpam==1) {
			guestBookPost(1,0);
			$ActionMessage="";
		}
		elseif ($check_info){
			guestBookPost(0,0);
		}
	break;
	
	//	新增留言，顯示為隱藏 加入 spam 記號
	case "hidden":
	case "default":
	default:
		
		if ($isSpam==1) {
			guestBookPost(1,0);
			$ActionMessage="";
		}
		elseif ($check_info){
			guestBookPost(0,0);
		}
		
	break;
}

/*
if ($check_info || intval($intSpamFiler)==1){
	$parent=0;
	$_POST['isSecret']=(!empty($_POST['isSecret']))?$_POST['isSecret']:0;
	$author=(!empty($_POST['username']))?$_POST['username']:$_SESSION['username'];
	$replypassword=(!empty($_POST['replypassword']))?md5($_POST['replypassword']):"";
	if (!empty($_POST['homepage'])) {
		if (strpos(";".$_POST['homepage'],"http://")<1) {
			$homepage="http://".$_POST['homepage'];
		} else {
			$homepage=$_POST['homepage'];
		}
	} else {
		$homepage="";
	}
	
	$email=(!empty($_POST['email']))?$_POST['email']:"";
	$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";

	$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent,face,isSpam) values('$author','$replypassword','".encode($homepage)."','".encode($email)."','".getip()."','".encode($_POST['message'])."','".time()."','".max(intval($intIsSecret),intval($_POST['isSecret']))."','$parent','".substr(encode($_POST['bookface']),4)."','".$intSpamFiler."')";
	//echo $sql;
	$DMC->query($sql);
			
	//更新cache
	settings_recount("guestbook");
	settings_recache();
	recentGbooks_recache();
	logs_sidebar_recache($arrSideModule);
		
	//保存时间
	$_SESSION['replytime']=time();
}
*/

echo $ActionMessage;

function guestBookPost($intSpamFiler,$intIsSecret){
	global $DMC,$DBPrefix,$arrSideModule;
	$parent=0;
	$_POST['isSecret']=(!empty($_POST['isSecret']))?$_POST['isSecret']:0;
	$author=(!empty($_POST['username']))?$_POST['username']:$_SESSION['username'];
	$replypassword=(!empty($_POST['replypassword']))?md5($_POST['replypassword']):"";
	if (!empty($_POST['homepage'])) {
		if (strpos(";".$_POST['homepage'],"http://")<1) {
			$homepage="http://".$_POST['homepage'];
		} else {
			$homepage=$_POST['homepage'];
		}
	} else {
		$homepage="";
	}
	
	$email=(!empty($_POST['email']))?$_POST['email']:"";
	$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";

	$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent,face,isSpam) values('$author','$replypassword','".encode($homepage)."','".encode($email)."','".getip()."','".encode($_POST['message'])."','".time()."','".max(intval($intIsSecret),intval($_POST['isSecret']))."','$parent','".substr(encode($_POST['bookface']),4)."','".$intSpamFiler."')";
	//echo $sql;
	$DMC->query($sql);
			
	//更新cache
	settings_recount("guestbook");
	settings_recache();
	recentGbooks_recache();
	logs_sidebar_recache($arrSideModule);
		
	//保存时间
	$_SESSION['replytime']=time();	
}

?>
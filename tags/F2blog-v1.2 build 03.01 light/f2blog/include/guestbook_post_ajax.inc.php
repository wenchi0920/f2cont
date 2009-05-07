<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$check_info=true;
$ActionMessage="";

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
	$parent=0;
	$_POST['isSecret']=(!empty($_POST['isSecret']))?$_POST['isSecret']:0;
	$author=(!empty($_POST['username']))?$_POST['username']:$_SESSION['username'];
	$replypassword=(!empty($_POST['replypassword']))?md5($_POST['replypassword']):"";
	$homepage=(!empty($_POST['homepage']))?$_POST['homepage']:"";
	$email=(!empty($_POST['email']))?$_POST['email']:"";
	$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";

	$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent,face) values('$author','$replypassword','".encode($homepage)."','".encode($email)."','".getip()."','".encode($_POST['message'])."','".time()."','".encode($_POST['isSecret'])."','$parent','".substr(encode($_POST['bookface']),4)."')";
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

echo $ActionMessage;
?>
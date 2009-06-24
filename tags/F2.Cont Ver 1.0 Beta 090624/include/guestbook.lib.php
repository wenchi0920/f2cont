<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');


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
<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("users".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=4;
$mtitle=$strUserMang;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if ($mark_id=="") {
		if (check_user($_POST['addusername'])==0) {
			$ActionMessage=$strUserAlert;
			$check_info=0;
		}

		if ($check_info==1 && check_password($_POST['addpassword'])==0) {
			$ActionMessage=$strPasswordAlert;
			$check_info=0;
		}	
	}else{
		if ($_POST['addpassword']!="" && check_password($_POST['addpassword'])==0) {
			$ActionMessage=$strPasswordAlert;
			$check_info=0;
		}
	}
	//检查两次密码是否相同
	if ($check_info==1 && $_POST['addpassword']!=$_POST['password_con']) {
		$ActionMessage=$strErrPassword;
		$check_info=0;
	}

	if ($check_info==1 && $_POST['email']!="" && check_email($_POST['email'])==0) {
		$ActionMessage=$strErrEmail;
		$check_info=0;
	}

	//检测昵称
	if ($check_info==1 && $_POST['nickname']!="" && check_nickname($_POST['nickname'])==0) {
		$ActionMessage=$strNickLengMax;
		$check_info=0;
	}

	if ($check_info==1){
		$nickrsexits="";
		$_POST['isHiddenEmail']=empty($_POST['isHiddenEmail'])?0:1;

		if ($mark_id!=""){//编辑
			//检测昵称
			if ($_POST['nickname']!=""){
				$nickrsexits=getFieldValue($DBPrefix."members","username='".encode($_POST['nickname'])."' or nickname='".encode($_POST['nickname'])."'","id");
				$check_info=($nickrsexits!="" && $nickrsexits!=$mark_id)?0:1;
			}
			
			if ($check_info==0){
				$ActionMessage="$strCurUserExists";
			}else{
				if ($_POST['addpassword']!="") {
					$passSql=",password=md5('".encode($_POST['addpassword'])."')"; 
				}else{
					$passSql="";
				}

				$sql="update ".$DBPrefix."members set email='".encode($_POST['email'])."',nickname='".encode($_POST['nickname'])."',homePage='".encode($_POST['homePage'])."',isHiddenEmail='".encode($_POST['isHiddenEmail'])."',role='".encode($_POST['role'])."'".$passSql." where id='$mark_id'";
				$DMC->query($sql);
			}
		}else{//新增
			//检测昵称
			if ($_POST['nickname']!=""){
				$nickrsexits=getFieldValue($DBPrefix."members","username='".encode($_POST['nickname'])."' or nickname='".encode($_POST['nickname'])."'","id");
				$check_info=($nickrsexits!="")?0:1;
			}
			//检测用户名
			if ($check_info==1){
				$userexits=getFieldValue($DBPrefix."members","username='".encode($_POST['addusername'])."' or nickname='".$_POST['addusername']."'","id");
				$check_info=($userexits!="")?0:1;
			}
				
			if ($check_info==0){
				$ActionMessage=($nickrsexits!="")?"$strCurUserExists":"$strRegisterInvaild";
			}else{
				$sql="INSERT INTO ".$DBPrefix."members(username,password,nickname,email,isHiddenEmail,homePage,lastVisitTime,lastVisitIP,regIp,hashKey,role) VALUES ('".encode($_POST['addusername'])."',md5('".encode($_POST['addpassword'])."'),'".encode($_POST['nickname'])."','".encode($_POST['email'])."','".encode($_POST['isHiddenEmail'])."','".encode($_POST['homePage'])."','".time()."','".getip()."','','','".encode($_POST['role'])."')";
				$DMC->query($sql);

				settings_recount("members");
				settings_recache();				
			}
		}
		members_recache();
	}

	if ($check_info==0) {
		$action=($mark_id!="")?"edit":"add";
	}
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
	}
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."members where $stritem";
		$DMC->query($sql);

		settings_recount("members");
		settings_recache();
		members_recache();
	}
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接

if ($action=="add"){
	//新增信息类别。
	$title="$strUserTitleAdd";
	$mtitle=$strUserAdd;
	
	$addusername=isset($_POST['addusername'])?$_POST['addusername']:"";
	$nickname=isset($_POST['nickname'])?$_POST['nickname']:"";
	$email=isset($_POST['email'])?$_POST['email']:"";
	$isHiddenEmail=empty($_POST['isHiddenEmail'])?"1":$_POST['isHiddenEmail'];
	$homePage=empty($_POST['homePage'])?"":$_POST['homePage'];
	$role=empty($_POST['role'])?"member":$_POST['role'];

	include("users_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strUserTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."members where id='$mark_id'"));
	if ($dataInfo) {
		$addusername=$dataInfo['username'];
		$nickname=$dataInfo['nickname'];
		$email=$dataInfo['email'];
		$isHiddenEmail=$dataInfo['isHiddenEmail'];
		$homePage=$dataInfo['homePage'];
		$role=$dataInfo['role'];

		include("users_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{
	//查找和浏览
	$title="$strUser";
	$find="";
	if ($order==""){$order="lastVisitTime";}
	if ($seekname!=""){$find=" where (username like '%$seekname%' or nickname like '%$seekname%')";}

	$sql="select * from ".$DBPrefix."members $find order by $order";
	$nums_sql="select count(id) as numRows from ".$DBPrefix."members $find";
	$total_num=getNumRows($nums_sql);

	include("users_list.inc.php");
}
?>
<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//echo $_SESSION['username']."+++".$_SESSION['password'];

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=$_REQUEST['seekname'];
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if ($mark_id=="") {
		if (trim($_POST['addusername'])=="" or trim($_POST['addpassword'])=="" or trim($_POST['password_con'])=="" or trim($_POST['nickname'])=="" or trim($_POST['email'])==""){
			$ActionMessage=$strErrNull;
			$check_info=0;
		}
	
		//检查输入的用户名是否合法
		if ($check_info==1 && check_user($_POST['addusername'])==0) {
			$ActionMessage=$strUserInVail;
			$check_info=0;
		}

		//检查用户名的长度
		if ($check_info==1 && strlen(trim($_POST['addusername']))<6) {
			$ActionMessage=$strLoginUserID.$strUserAlert;
			$check_info=0;
		}

		//检查密码的长度
		if ($check_info==1 && strlen(trim($_POST['addpassword']))<6) {
			$ActionMessage=$strLoginPassword.$strUserAlert;
			$check_info=0;
		}
	} else {
		//检测输入内容
		if (strlen(trim($_POST['addpassword']))>0 && strlen(trim($_POST['addpassword']))<6) {
			$ActionMessage=$strLoginPassword.$strUserAlert;
			$check_info=0;
		}

		if ($check_info==1 && (trim($_POST['nickname'])=="" or trim($_POST['email'])=="")){
			$ActionMessage=$strErrNull;
			$check_info=0;
		}
	}

	//检查两次密码是否相同
	if ($check_info==1 && (trim($_POST['addpassword'])!=trim($_POST['password_con']))) {
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
	
	if ($check_info==0) {
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."members","username='".$_POST['addusername']."' or nickname='".$_POST['nickname']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				if (trim($_POST['addpassword'])){
					$passSql=",password=md5('".trim($_POST['addpassword'])."')";
				}

				$sql="update ".$DBPrefix."members set lastVisitTime='".time()."',lastVisitIP='".getip()."',email='".$_POST['email']."',nickname='".$_POST['nickname']."',homePage='".$_POST['homePage']."'".$passSql." where id='$mark_id'";
				$DMC->query($sql);
			}
		}else{//新增
			$rsexits=getFieldValue($DBPrefix."members","username='".$_POST['addusername']."' or nickname='".$_POST['nickname']."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$sql="INSERT INTO ".$DBPrefix."members(username,password,nickname,email,isHiddenEmail,homePage,lastVisitTime,lastVisitIP) VALUES ('".$_POST['addusername']."',md5('".$_POST['passwrod']."'),'".$_POST['nickname']."','".$_POST['email']."','".$_POST['isHiddenEmail']."','".$_POST['homePage']."','".time()."','".getip()."')";
				$DMC->query($sql);
			}
		}
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
	
	$addusername=$_POST['addusername'];
	$nickname=$_POST['nickname'];
	$email=$_POST['email'];
	$isHiddenEmail=($_POST['isHiddenEmail']=="")?"1":$_POST['isHiddenEmail'];
	$homePage=$_POST['homePage'];

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

		include("users_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{
	//查找和浏览
	$title="$strUser";

	if ($order==""){$order="lastVisitTime";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (username like '%$seekname%' or nickname like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."members where $find order by $order";
	} else {
		$sql="select * from ".$DBPrefix."members order by $order";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("users_list.inc.php");
}
?>
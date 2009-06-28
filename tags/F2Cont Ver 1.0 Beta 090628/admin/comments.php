<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("comments".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=2;
$mtitle=$strCommentBrowse;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];
$logId=isset($_GET['logId'])?$_GET['logId']:"";
$showmethod=($_GET['showmethod'])?$_GET['showmethod']:"parent";
$showmode=($_GET['showmode'])?$_GET['showmode']:"open";

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if (trim($_POST['logContent'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		$content=encode($_POST['logContent']);
		$sql="update ".$DBPrefix."comments set content='$content' where id='".$mark_id."'";
		$DMC->query($sql);
		$action="";
			
		settings_recount("comments");
		settings_recache();
		recentComments_recache();
		logs_sidebar_recache($arrSideModule);
	}
}

//回复资料
if ($action=="reply"){
	$check_info=1;
	//检测输入内容
	if (trim($_POST['logContent'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		$content=encode($_POST['logContent']);
		//新增
		$rsexits=getFieldValue($DBPrefix."comments","content='$content' and author='".$_SESSION['username']."' and parent='$mark_id' and logId='$logId'","id");
		if ($rsexits!=""){
			$logContent=str_replace("<br />","",dencode($content));
			$ActionMessage="$strDataExists";
			$action="add";
		}else{
			$sql="insert into ".$DBPrefix."comments(logId,author,password,ip,content,postTime,isSecret,parent) values('$logId','".$_SESSION['username']."','','".getip()."','$content','".time()."','0','$mark_id')";
			$DMC->query($sql);
			//echo $sql;
			$action="";
			
			settings_recount("comments");
			settings_recache();
			$DMC->query("UPDATE ".$DBPrefix."logs SET commNums=commNums+1 WHERE id='$logId'");
			recentComments_recache();
			logs_sidebar_recache($arrSideModule);
		}
	}
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$otype=($_POST['operation']=="show")?"adding":"minus";
	$nums=0;
	for ($i=0;$i<count($itemlist);$i++){
		$my=getRecordValue($DBPrefix."comments"," id='$itemlist[$i]'");
		$logId=$my['logId'];
		$isSecret=$my['isSecret'];
		if ($_POST['operation']=="delete" or ($_POST['operation']=="show" and $isSecret==1) or ($_POST['operation']=="hidden" and $isSecret==0) ) {
			update_num($DBPrefix."logs","commNums"," id='$logId'",$otype,1);
			$nums=$nums+1;

			if ($stritem!=""){
				$stritem.=" or id='$itemlist[$i]'";
			}else{
				$stritem.="id='$itemlist[$i]'";
			}

			//删除的是主留言，其子留言也删除
			if($_POST['operation']=="delete"){
				$stritem.=" or parent='$itemlist[$i]'";
			}
		}
	}

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."comments where $stritem";
		$DMC->query($sql);
		settings_recount("comments");
		settings_recache();
		recentComments_recache();
		logs_sidebar_recache($arrSideModule);
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."comments set isSecret='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."comments set isSecret='0' where $stritem";
		$DMC->query($sql);
	}

	//更新cache
	settings_recount("comments");
	settings_recache();
	recentComments_recache();
	logs_sidebar_recache($arrSideModule);

	$action="";
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&order=$order";	//查找用链接
$order_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$showmode_url="$PHP_SELF?showmethod=$showmethod&order=$order&page=$page";	//展开／折叠链接
$showmethod_url="$PHP_SELF?showmode=$showmode&order=$order&page=$page";	//展开／折叠链接

if ($action=="add" && $mark_id!=""){
	//新增信息类别。
	$title="$strCommentsReplyTitle";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."comments where id='$mark_id'"));
	if ($dataInfo) {
		$reply_content=$dataInfo['author']." $strSideBarGuestBook:<br /><br />".ubb($dataInfo['content']);
	}else{
		$reply_content="";
	}

	include("comments_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strCommentBrowse - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."comments where id='$mark_id'"));
	if ($dataInfo) {
		$logContent=str_replace("<br />","",dencode($dataInfo['content']));
		
		//原内容
		$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."comments where id='".$dataInfo['parent']."'"));
		if ($dataInfo) {
			$reply_content=$dataInfo['author']." $strSideBarGuestBook:<br /><br />".ubb($dataInfo['content']);
		}else{
			$reply_content="";
		}

		include("comments_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{	//查找和浏览
	$title="$strCommentBrowse";

	if ($order==""){$order="postTime";}

	//Find condition
	if ($showmethod=="parent"){
		$find=" and parent='0'";
	}else{
		$find="";
	}
	if ($seekname!=""){$find.=" and (content like '%$seekname%' or ip like '%$seekname%')";}
	
	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."comments where $find order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."comments where $find";		
	} else {
		$sql="select * from ".$DBPrefix."comments order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."comments";	
	}
	
	$total_num=getNumRows($nums_sql);
	include("comments_list.inc.php");
}
?>
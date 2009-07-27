<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("guestbook".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=2;
$mtitle=$strGuestBookBrowse;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];
$showmethod=($_GET['showmethod'])?$_GET['showmethod']:"parent";
$showmode=($_GET['showmode'])?$_GET['showmode']:"open";

//��������
if ($action=="save"){
	$check_info=1;
	//�����������
	if (trim($_POST['logContent'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		$content=encode($_POST['logContent']);
		$sql="update ".$DBPrefix."guestbook set content='$content' where id='".$mark_id."'";
		$DMC->query($sql);
		$action="";
		
		settings_recount("guestbook");
		settings_recache();
		recentGbooks_recache();
		logs_sidebar_recache($arrSideModule);
	}
}

//�ظ�����
if ($action=="reply"){
	$check_info=1;
	//�����������
	if (trim($_POST['logContent'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		$content=encode($_POST['logContent']);
		//����
		$rsexits=getFieldValue($DBPrefix."guestbook","content='$content' and author='".$_SESSION['username']."' and parent='$mark_id'","id");
		if ($rsexits!=""){
			$logContent=str_replace("<br />","",dencode($content));
			$ActionMessage="$strDataExists";
			$action="add";
		}else{
			$sql="insert into ".$DBPrefix."guestbook(author,homepage,email,password,ip,content,postTime,isSecret,parent) values('".$_SESSION['username']."','','','','".getip()."','$content','".time()."','0','$mark_id')";
			$DMC->query($sql);
			//echo $DMC->error();
			$action="";
			
			settings_recount("guestbook");
			settings_recache();
			recentGbooks_recache();
			logs_sidebar_recache($arrSideModule);
		}
	}
}

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$otype=($_POST['operation']=="show")?"adding":"minus";
	$nums=0;
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
		//ɾ�����������ԣ���������Ҳɾ��
		if($_POST['operation']=="delete"){
			$stritem.=" or parent='$itemlist[$i]'";
		}
	}

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."guestbook where $stritem";
		$DMC->query($sql);

		settings_recount("guestbook");
		settings_recache();
		recentGbooks_recache();
		logs_sidebar_recache($arrSideModule);
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."guestbook set isSecret='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."guestbook set isSecret='0' where $stritem";
		$DMC->query($sql);
	}

	//����cache
	settings_recount("guestbook");
	settings_recache();
	recentGbooks_recache();
	logs_sidebar_recache($arrSideModule);

	$action="";
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&order=$order";	//����������
$order_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname";	//�������õ�����
$page_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?showmethod=$showmethod&showmode=$showmode&seekname=$seekname&order=$order&page=$page";	//�༭����������
$showmode_url="$PHP_SELF?showmethod=$showmethod&order=$order&page=$page";	//չ�����۵�����
$showmethod_url="$PHP_SELF?showmode=$showmode&order=$order&page=$page";	//չ�����۵�����

if ($action=="add" && $mark_id!=""){
	//������Ϣ���
	$title="$strGuestBookReplyTitle";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."guestbook where id='$mark_id'"));
	if ($dataInfo) {
		$reply_content=$dataInfo['author']." $strSideBarGuestBook:<br /><br />".ubb($dataInfo['content']);
	}else{
		$reply_content="";
	}

	include("comments_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strGuestBookBrowse - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."guestbook where id='$mark_id'"));
	if ($dataInfo) {
		$logContent=str_replace("<br />","",dencode($dataInfo['content']));

		//ԭ����
		$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."guestbook where id='".$dataInfo['parent']."'"));
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
}else{	//���Һ����
	$title="$strGuestBookBrowse";

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
		$sql="select * from ".$DBPrefix."guestbook where $find order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."guestbook where $find";
	} else {
		$sql="select * from ".$DBPrefix."guestbook order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."guestbook";
	}

	$total_num=getNumRows($nums_sql);
	include("guestbooks_list.inc.php");
}
?>
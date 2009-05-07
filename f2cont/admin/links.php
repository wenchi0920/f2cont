<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("links".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=3;
$mtitle=$strLinkManagement;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//��������
if ($action=="save"){
	$check_info=1;
	$lnkName=trim($_POST['name']);
	$lnkUrl=trim($_POST['blogUrl']);
	$lnkLogo=trim($_POST['blogLogo']);
	$isSidebar=$_POST['isSidebar'];
	$lnkGrpId=$_POST['lnkGrpId'];

	//�����������
	if ($lnkName=="" or $lnkUrl=="" or $lnkGrpId==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if (!is_numeric($lnkGrpId)){//��������
			$rsexits=getFieldValue($DBPrefix."linkgroup","name='".encode($lnkGrpId)."'","id");
			if ($rsexits!=""){
				$lnkGrpId=$rsexits;				
			}else{
				$sql="INSERT INTO ".$DBPrefix."linkgroup(name,isSidebar) VALUES ('".encode($lnkGrpId)."','1')";
				$DMC->query($sql);
				$lnkGrpId=$DMC->insertId();
			}
		}

		if ($mark_id!=""){//�༭
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($lnkName)."' and blogUrl='".encode($lnkUrl)."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."links set name='".encode($lnkName)."',blogUrl='".encode($lnkUrl)."',blogLogo='".encode($lnkLogo)."',isSidebar='$isSidebar',lnkGrpId='$lnkGrpId',isApp='1' where id='$mark_id'";
				$DMC->query($sql);
				$action="";
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($lnkName)."' and blogUrl='".encode($lnkUrl)."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderno=getFieldValue($DBPrefix."links"," order by orderNo desc","orderNo");
				if ($orderno<1){
					$orderno=1;
				}else{
					$orderno++;
				}
				$sql="INSERT INTO ".$DBPrefix."links(name,blogUrl,orderNo,blogLogo,isSidebar,lnkGrpId,isApp) VALUES ('".encode($lnkName)."','".encode($lnkUrl)."','$orderno','".encode($lnkLogo)."','$isSidebar','$lnkGrpId','1')";
				$DMC->query($sql);
				$action="";
			}
		}

		if ($action=="") {
			do_action("f2_link");
			links_recache();
			logs_sidebar_recache($arrSideModule);
		}
	}
}

//��������
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."links set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	do_action("f2_link");
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

//����������Ϊ���༭��ɾ����
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
	
	//ɾ��
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."links where $stritem";
		$DMC->query($sql);
	}

	//�ƶ�
	if($_POST['operation']=="move" and $stritem!=""){
		$sql="update ".$DBPrefix."links set lnkGrpId='".$_POST['move_group']."' where $stritem";
		$DMC->query($sql);
	}

	//���������
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isSidebar='0' where $stritem";
		$DMC->query($sql);
	}

	//�������ʾ
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isSidebar='1' where $stritem";
		$DMC->query($sql);
	}
	do_action("f2_link");
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

if ($action=="all" or $action==""){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//����������
$order_url="$PHP_SELF?seekname=$seekname";	//�������õ�����
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//�༭����������

if ($action=="add"){
	//������Ϣ���
	$title="$strLinksTitleAdd";
	$mtitle=$strLinksAdd;
	$lnkGrpId="";

	include("links_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strLinksTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."links where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$blogUrl=$dataInfo['blogUrl'];
		$blogLogo=$dataInfo['blogLogo'];
		$isSidebar=$dataInfo['isSidebar'];
		$isApp=$dataInfo['isApp'];
		$lnkGrpId=$dataInfo['lnkGrpId'];

		include("links_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else if ($action=="order"){
	//�������˳��
	$title="$strLinksExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."links where lnkGrpId='$seekname' order by orderNo"));
	if ($arr_parent) {
		include("links_order.inc.php");	
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else{
	//���Һ����
	$title="$strLinksTitle";

	if ($order==""){$order="a.id desc";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (a.lnkGrpId='$seekname')"; $order="a.orderNo"; }

	//�ѷ���IDΪ���ĸ�Ϊ��
	$DMC->query("update ".$DBPrefix."links set lnkGrpId='1' where lnkGrpId='0'");

	$sql="select a.*,b.name as groupName from ".$DBPrefix."links as a inner join ".$DBPrefix."linkgroup as b on a.lnkGrpId=b.id where isApp='1' $find order by $order";
	
	$nums_sql="select count(id) as numRows from ".$DBPrefix."links as a where isApp='1' $find";
	
	$total_num=getNumRows($nums_sql);
	include("links_list.inc.php");
}
?>
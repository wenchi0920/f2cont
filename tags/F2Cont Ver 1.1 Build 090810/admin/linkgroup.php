<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("linkgroup".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=3;
$mtitle=$strlinkgroupTitle;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//��������
if ($action=="save"){
	$check_info=1;
	//�����������
	if (trim($_POST['name'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if ($mark_id!=""){//�༭
			$rsexits=getFieldValue($DBPrefix."linkgroup","name='".encode($_POST['name'])."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				//�����˘˻`
				$isupdate=getFieldValue($DBPrefix."linkgroup","id='$mark_id'","isSidebar");

				$sql="update ".$DBPrefix."linkgroup set name='".encode($_POST['name'])."',isSidebar='".$_POST['isSidebar']."' where id='$mark_id'";
				$DMC->query($sql);
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."linkgroup","name='".encode($_POST['name'])."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderNo=getFieldValue($DBPrefix."linkgroup","order by orderNo desc","orderNo") + 1;
				$sql="INSERT INTO ".$DBPrefix."linkgroup(name,isSidebar,orderNo) VALUES ('".encode($_POST['name'])."','".$_POST['isSidebar']."','".$orderNo."')";
				$DMC->query($sql);
			}
		}

		links_recache();
		logs_sidebar_recache($arrSideModule);
	}
}

//��������
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."linkgroup set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$stritem2="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
			$stritem2.=" or lnkGrpId='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
			$stritem2.="lnkGrpId='$itemlist[$i]'";
		}
	}
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."linkgroup where $stritem";
		$DMC->query($sql);

		//ɾ�������ڵ���������
		$sql="delete from ".$DBPrefix."links where $stritem2";
		$DMC->query($sql);
	}

	//���������
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."linkgroup set isSidebar='0' where $stritem";
		$DMC->query($sql);
	}

	//�������ʾ
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."linkgroup set isSidebar='1' where $stritem";
		$DMC->query($sql);
	}

	//����Cache
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//����������
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//�༭����������
$order_url="$PHP_SELF?seekname=$seekname";	//�������õ�����

if ($action=="add"){
	//������Ϣ���
	$title="$strlinkgroupTitleAdd";

	include("linkgroup_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strlinkgroupTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."linkgroup where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$isSidebar=$dataInfo['isSidebar'];

		include("linkgroup_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else if ($action=="order"){
	//�������˳��
	$title="$strLinksExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."linkgroup order by orderNo"));
	if ($arr_parent) {
		include("linkgroup_order.inc.php");	
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else{
	//���Һ����
	$title="$strlinkgroupTitle";

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (name like '%$seekname%')";}
	if ($order=="") $order="orderNo";

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."linkgroup where $find order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."linkgroup where $find";
	} else {
		$sql="select * from ".$DBPrefix."linkgroup order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."linkgroup";
	}
	
	$total_num=getNumRows($nums_sql);
	include("linkgroup_list.inc.php");
}
?>
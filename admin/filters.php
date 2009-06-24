<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("filter".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=6;
$mtitle=$strFilter;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$seekcategory=isset($_REQUEST['seekcategory'])?$_REQUEST['seekcategory']:"";
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
			$rsexits=getFieldValue($DBPrefix."filters","name='".encode($_POST['name'])."' and category='".$_POST['category']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."filters set name='".encode($_POST['name'])."',category='".$_POST['category']."' where id='$mark_id'";
				$DMC->query($sql);
				$action="";
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."filters","name='".encode($_POST['name'])."' and category='".$_POST['category']."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$sql="INSERT INTO ".$DBPrefix."filters(name,category) VALUES ('".encode($_POST['name'])."','".$_POST['category']."')";
				$DMC->query($sql);
				$action="";
			}
		}

		if ($action=="") {
			filters_recache();
		}
	}
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
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."filters where $stritem";
		$DMC->query($sql);
		filters_recache();
	}
}

if ($action=="all"){
	$seekname="";
	$seekcategory="";
}


$seek_url="$PHP_SELF?order=$order";	//����������
$order_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory";	//�������õ�����
$page_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory&order=$order&page=$page";	//�༭����������

if ($action=="add"){
	//������Ϣ���
	$title="$strFiltersTitleAdd";
	$category=1;

	include("filters_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strFiltersTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."filters where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$category=$dataInfo['category'];

		include("filters_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{
	//���Һ����
	$title="$strFiltersTitle";

	if ($order==""){$order="id";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (name like '%$seekname%')";}
	if ($seekcategory!=""){$find.=" and (category='$seekcategory')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."filters where $find order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."filters where $find";
	} else {
		$sql="select * from ".$DBPrefix."filters order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."filters";
	}

	$total_num=getNumRows($nums_sql);
	include("filters_list.inc.php");
}
?>
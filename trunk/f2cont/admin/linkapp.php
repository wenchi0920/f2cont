<?php 
require_once("function.php");

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=3;
$mtitle=$strLinksApp;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);

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
	
	//�ܾ����루ɾ����
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."links where $stritem";
		$DMC->query($sql);
	}

	//��ӵ�ĳ��
	if($_POST['operation']=="move" and $stritem!=""){
		$orderno=getFieldValue($DBPrefix."links"," lnkGrpId='".$_POST['move_group']."' order by orderNo desc","orderNo");
		if ($orderno<1){
			$orderno=1;
		}else{
			$orderno++;
		}

		$sql="update ".$DBPrefix."links set lnkGrpId='".$_POST['move_group']."',isApp='1',isSidebar='1',orderno='$orderno' where $stritem";
		$DMC->query($sql);
	}

	//��ӵ�ĳ�鲢Ϊ�ı�����
	if($_POST['operation']=="movetext" and $stritem!=""){
		$orderno=getFieldValue($DBPrefix."links"," lnkGrpId='".$_POST['move_group2']."' order by orderNo desc","orderNo");
		if ($orderno<1){
			$orderno=1;
		}else{
			$orderno++;
		}

		$sql="update ".$DBPrefix."links set lnkGrpId='".$_POST['move_group2']."',isApp='1',blogLogo='',isSidebar='1',orderno='$orderno' where $stritem";
		$DMC->query($sql);
	}

	if ($_POST['operation']=="move" || $_POST['operation']=="movetext"){
		do_action("f2_link");
		links_recache();
		logs_sidebar_recache($arrSideModule);
	}
}

$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//�༭����������
$order_url="$PHP_SELF?seekname=$seekname";	//�������õ�����

//���Һ����
$title="$strLinksApp";

if ($order==""){$order="id desc";}
$sql="select * from ".$DBPrefix."links where isApp='0' order by $order";
$nums_sql="select count(id) as numRows from ".$DBPrefix."links where isApp='0'";
	
$total_num=getNumRows($nums_sql);
include("linkapp_list.inc.php");
?>
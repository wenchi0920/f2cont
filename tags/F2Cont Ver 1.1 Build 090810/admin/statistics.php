<?php 
require_once("function.php");

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=6;
$mtitle=$strStatistics;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$visits=isset($_GET['visits'])?$_GET['visits']:"";

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or visitDate like '$itemlist[$i]%'";
		}else{
			$stritem.="visitDate like '$itemlist[$i]%'";
		}
	}
	
	//ɾ��
	if($_POST['operation']=="delete" and $stritem!=""){
		$result=$DMC->query("select sum(visits) as sum_total from ".$DBPrefix."dailystatistics where $stritem");
		if ($arr_result=$DMC->fetchArray($result)){
			$update="UPDATE ".$DBPrefix."setting set settValue=settValue+".$arr_result['sum_total']." where settName='visitNums'";
			$DMC->query($update);
			settings_recache();
		}

		$sql="delete from ".$DBPrefix."dailystatistics where $stritem";
		$DMC->query($sql);
	}
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order&visits=$visits";	//����������
$order_url="$PHP_SELF?seekname=$seekname&visits=$visits";	//�������õ�����
$page_url="$PHP_SELF?seekname=$seekname&order=$order&visits=$visits";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page&visits=$visits";	//�༭����������


//���Һ����
$title="$strStatisticsTitle";

if ($order==""){$order="visitDate";}

//Find condition
$find="";
if ($seekname!=""){$find.=" and (visitDate like '%$seekname%')";}
if ($visits==""){
	$field="visits,visitDate";
	$groupvalue="";
}
if ($visits=="month"){
	$field="sum(visits) as visits,DATE_FORMAT(visitDate,'%Y-%m') as visitDate";
	$groupvalue="group by DATE_FORMAT(visitDate,'%Y-%m')";
}
if ($visits=="year"){
	$field="sum(visits) as visits,DATE_FORMAT(visitDate,'%Y') as visitDate";
	$groupvalue="group by DATE_FORMAT(visitDate,'%Y')";
}

if ($find!=""){
	$find=substr($find,5);
	$sql="select $field from ".$DBPrefix."dailystatistics where $find $groupvalue order by $order desc";
	$nums_sql="select id from ".$DBPrefix."dailystatistics where $find $groupvalue";
} else {
	$sql="select $field from ".$DBPrefix."dailystatistics $groupvalue order by $order desc";
	$nums_sql="select id from ".$DBPrefix."dailystatistics $groupvalue";
}

$total_num=$DMC->numRows($DMC->query($nums_sql));
include("statistics_list.inc.php");
?>
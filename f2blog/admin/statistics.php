<?
$PATH="./";
include("$PATH/function.php");

// ��֤�û��Ƿ��ڵ�½״̬
check_login();

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=$_REQUEST['seekname'];
$visits=$_GET['visits'];

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
} else {
	$sql="select $field from ".$DBPrefix."dailystatistics $groupvalue order by $order desc";
}

$total_num=$DMC->numRows($DMC->query($sql));
include("statistics_list.inc.php");
?>
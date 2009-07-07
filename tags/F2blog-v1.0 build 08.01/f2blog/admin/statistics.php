<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=$_REQUEST['seekname'];
$visits=$_GET['visits'];

//其它操作行为：编辑、删除等
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
	
	//删除
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."dailystatistics where $stritem";
		$DMC->query($sql);
	}
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order&visits=$visits";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname&visits=$visits";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order&visits=$visits";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page&visits=$visits";	//编辑或新增链接


//查找和浏览
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
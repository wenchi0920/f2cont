<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=2;
$mtitle=$strTrackbackBrowse;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['id'];

if ($action=="deltb"){
	$my=getRecordValue($DBPrefix."trackbacks"," id='$mark_id'");
	$logId=$my['logId'];
	update_num($DBPrefix."logs","quoteNums"," id='$logId'","minus",1);

	//加入刪除的引用網址到過濾列表
	addFilterUrl($my['blogUrl']);
	filters_recache();

	$sql="delete from ".$DBPrefix."trackbacks where id='$mark_id'";
	$DMC->query($sql);

	settings_recount("trackbacks");
	settings_recache();

	header("Location:../index.php?load=read&id=$logId"); 
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$otype=($_POST['operation']=="show")?"adding":"minus";
	$nums=0;
	for ($i=0;$i<count($itemlist);$i++){
		$my=getRecordValue($DBPrefix."trackbacks"," id='$itemlist[$i]'");
		$logId=$my['logId'];
		$isApp=$my['isApp'];
		if ($_POST['operation']=="delete" or ($_POST['operation']=="show" and $isApp==0) or ($_POST['operation']=="hidden" and $isApp==1) ) {
			if ($_POST['operation']!="delete" or ($_POST['operation']=="delete" and $my['isApp']==1)) {
				update_num($DBPrefix."logs","quoteNums"," id='$logId'",$otype,1);
			}
			$nums=$nums+1;

			if ($stritem!=""){
				$stritem.=" or id='$itemlist[$i]'";
			}else{
				$stritem.="id='$itemlist[$i]'";
			}

			if ($_POST['operation']=="delete"){
				//加入刪除的引用網址到過濾列表
				addFilterUrl($my['blogUrl']);
				filters_recache();
			}
		}
	}

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."trackbacks where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."trackbacks set isApp='0' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."trackbacks set isApp='1' where $stritem";
		$DMC->query($sql);
	}

	settings_recount("trackbacks");
	settings_recache();
	$action="";
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$showmode_url="$PHP_SELF?order=$order&page=$page";	//展开／折叠链接

if ($action=="" or $action=="find" or $action=="all"){
	//查找和浏览
	$title="$strTrackbacksTitle";

	if ($order==""){$order="a.postTime";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (tbTitle like '%$seekname%')";}
	
	if ($find!=""){
		$find=substr($find,5);
		$sql="select *,a.id as id,b.id as cid,b.logTitle from ".$DBPrefix."trackbacks as a inner join ".$DBPrefix."logs as b on a.logId=b.id where $find order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."trackbacks where $find";
	} else {
		$sql="select *,a.id as id,b.id as cid,b.logTitle from ".$DBPrefix."trackbacks as a inner join ".$DBPrefix."logs as b on a.logId=b.id order by $order desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."trackbacks";
	}

	$total_num=getNumRows($nums_sql);
	include("trackback_list.inc.php");
}
?>
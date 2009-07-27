<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=3;
$mtitle=$strLinksApp;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);

//其它操作行为：编辑、删除等
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
	
	//拒绝申请（删除）
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."links where $stritem";
		$DMC->query($sql);
	}

	//添加到某组
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

	//添加到某组并为文本链接
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

$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接

//查找和浏览
$title="$strLinksApp";

if ($order==""){$order="id desc";}
$sql="select * from ".$DBPrefix."links where isApp='0' order by $order";
$nums_sql="select count(id) as numRows from ".$DBPrefix."links where isApp='0'";
	
$total_num=getNumRows($nums_sql);
include("linkapp_list.inc.php");
?>
<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("filter".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=6;
$mtitle=$strFilter;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$seekcategory=isset($_REQUEST['seekcategory'])?$_REQUEST['seekcategory']:"";
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if (trim($_POST['name'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."filters","name='".encode($_POST['name'])."' and category='".$_POST['category']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."filters set name='".encode($_POST['name'])."',category='".$_POST['category']."' where id='$mark_id'";
				$DMC->query($sql);
				$action="";
			}
		}else{//新增
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


$seek_url="$PHP_SELF?order=$order";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&seekcategory=$seekcategory&order=$order&page=$page";	//编辑或新增链接

if ($action=="add"){
	//新增信息类别。
	$title="$strFiltersTitleAdd";
	$category=1;

	include("filters_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
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
	//查找和浏览
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
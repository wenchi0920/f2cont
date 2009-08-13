<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("linkgroup".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=3;
$mtitle=$strlinkgroupTitle;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
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
			$rsexits=getFieldValue($DBPrefix."linkgroup","name='".encode($_POST['name'])."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				//更新了標籤
				$isupdate=getFieldValue($DBPrefix."linkgroup","id='$mark_id'","isSidebar");

				$sql="update ".$DBPrefix."linkgroup set name='".encode($_POST['name'])."',isSidebar='".$_POST['isSidebar']."' where id='$mark_id'";
				$DMC->query($sql);
			}
		}else{//新增
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

//保存排序
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."linkgroup set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

//其它操作行为：编辑、删除等
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

		//删除此组内的所有链接
		$sql="delete from ".$DBPrefix."links where $stritem2";
		$DMC->query($sql);
	}

	//侧边栏隐藏
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."linkgroup set isSidebar='0' where $stritem";
		$DMC->query($sql);
	}

	//侧边栏显示
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."linkgroup set isSidebar='1' where $stritem";
		$DMC->query($sql);
	}

	//更新Cache
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接

if ($action=="add"){
	//新增信息类别。
	$title="$strlinkgroupTitleAdd";

	include("linkgroup_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
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
	//调整类别顺序
	$title="$strLinksExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."linkgroup order by orderNo"));
	if ($arr_parent) {
		include("linkgroup_order.inc.php");	
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else{
	//查找和浏览
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
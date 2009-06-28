<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("links".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=3;
$mtitle=$strLinkManagement;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;
	$lnkName=trim($_POST['name']);
	$lnkUrl=trim($_POST['blogUrl']);
	$lnkLogo=trim($_POST['blogLogo']);
	$isSidebar=$_POST['isSidebar'];
	$lnkGrpId=$_POST['lnkGrpId'];

	//检测输入内容
	if ($lnkName=="" or $lnkUrl=="" or $lnkGrpId==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if (!is_numeric($lnkGrpId)){//新增分组
			$rsexits=getFieldValue($DBPrefix."linkgroup","name='".encode($lnkGrpId)."'","id");
			if ($rsexits!=""){
				$lnkGrpId=$rsexits;				
			}else{
				$sql="INSERT INTO ".$DBPrefix."linkgroup(name,isSidebar) VALUES ('".encode($lnkGrpId)."','1')";
				$DMC->query($sql);
				$lnkGrpId=$DMC->insertId();
			}
		}

		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($lnkName)."' and blogUrl='".encode($lnkUrl)."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."links set name='".encode($lnkName)."',blogUrl='".encode($lnkUrl)."',blogLogo='".encode($lnkLogo)."',isSidebar='$isSidebar',lnkGrpId='$lnkGrpId',isApp='1' where id='$mark_id'";
				$DMC->query($sql);
				$action="";
			}
		}else{//新增
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($lnkName)."' and blogUrl='".encode($lnkUrl)."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderno=getFieldValue($DBPrefix."links"," order by orderNo desc","orderNo");
				if ($orderno<1){
					$orderno=1;
				}else{
					$orderno++;
				}
				$sql="INSERT INTO ".$DBPrefix."links(name,blogUrl,orderNo,blogLogo,isSidebar,lnkGrpId,isApp) VALUES ('".encode($lnkName)."','".encode($lnkUrl)."','$orderno','".encode($lnkLogo)."','$isSidebar','$lnkGrpId','1')";
				$DMC->query($sql);
				$action="";
			}
		}

		if ($action=="") {
			do_action("f2_link");
			links_recache();
			logs_sidebar_recache($arrSideModule);
		}
	}
}

//保存排序
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."links set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	do_action("f2_link");
	links_recache();
	logs_sidebar_recache($arrSideModule);
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
	
	//删除
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."links where $stritem";
		$DMC->query($sql);
	}

	//移动
	if($_POST['operation']=="move" and $stritem!=""){
		$sql="update ".$DBPrefix."links set lnkGrpId='".$_POST['move_group']."' where $stritem";
		$DMC->query($sql);
	}

	//侧边栏隐藏
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isSidebar='0' where $stritem";
		$DMC->query($sql);
	}

	//侧边栏显示
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isSidebar='1' where $stritem";
		$DMC->query($sql);
	}
	do_action("f2_link");
	links_recache();
	logs_sidebar_recache($arrSideModule);
}

if ($action=="all" or $action==""){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接

if ($action=="add"){
	//新增信息类别。
	$title="$strLinksTitleAdd";
	$mtitle=$strLinksAdd;
	$lnkGrpId="";

	include("links_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strLinksTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."links where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$blogUrl=$dataInfo['blogUrl'];
		$blogLogo=$dataInfo['blogLogo'];
		$isSidebar=$dataInfo['isSidebar'];
		$isApp=$dataInfo['isApp'];
		$lnkGrpId=$dataInfo['lnkGrpId'];

		include("links_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else if ($action=="order"){
	//调整类别顺序
	$title="$strLinksExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."links where lnkGrpId='$seekname' order by orderNo"));
	if ($arr_parent) {
		include("links_order.inc.php");	
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else{
	//查找和浏览
	$title="$strLinksTitle";

	if ($order==""){$order="a.id desc";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (a.lnkGrpId='$seekname')"; $order="a.orderNo"; }

	//把分组ID为０的改为１
	$DMC->query("update ".$DBPrefix."links set lnkGrpId='1' where lnkGrpId='0'");

	$sql="select a.*,b.name as groupName from ".$DBPrefix."links as a inner join ".$DBPrefix."linkgroup as b on a.lnkGrpId=b.id where isApp='1' $find order by $order";
	
	$nums_sql="select count(id) as numRows from ".$DBPrefix."links as a where isApp='1' $find";
	
	$total_num=getNumRows($nums_sql);
	include("links_list.inc.php");
}
?>
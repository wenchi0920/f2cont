<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("categories".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=1;
$mtitle=$strCategory;

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
			$rsexits=getFieldValue($DBPrefix."categories","name='".encode($_POST['name'])."' and parent='".$_POST['parent']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."categories set parent='".$_POST['parent']."',name='".encode($_POST['name'])."',cateTitle='".encode($_POST['cateTitle'])."',outLinkUrl='".encode($_POST['url'])."',cateIcons='".$_POST['cateIcons']."' where id='$mark_id'";
				$DMC->query($sql);

				//更新cache
				categories_recache();
				logs_sidebar_recache($arrSideModule);
			}
		}else{//新增
			$rsexits=getFieldValue($DBPrefix."categories","name='".encode($_POST['name'])."' and parent='".$_POST['parent']."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderno=getFieldValue($DBPrefix."categories","parent='".$_POST['parent']."' order by orderNo desc","orderNo");
				if ($orderno<1){
					$orderno=1;
				}else{
					$orderno++;
				}
				$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden,cateIcons) VALUES ('".$_POST['parent']."','".encode($_POST['name'])."','$orderno','".encode($_POST['cateTitle'])."','".encode($_POST['url'])."','0','0','".$_POST['cateIcons']."')";
				$DMC->query($sql);
				
				//更新cache
				categories_recache();
				settings_recount("categories");
				settings_recache();
				logs_sidebar_recache($arrSideModule);
			}
		}
	}
}

//保存排序
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."categories set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	//更新cache
	categories_recache();
	logs_sidebar_recache($arrSideModule);
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$strlogsitem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		//如果移动主类别到子类别，则其原有子类别也移动相应的子类别下。
		if($_POST['operation']=="move" && $_POST['parent']>0 || $_POST['operation']=="delete"){
			$dataInfo = $DMC->fetchQueryAll($DMC->query("SELECT id FROM ".$DBPrefix."categories WHERE parent='".$itemlist[$i]."'"));
			for($j=0;$j<count($dataInfo);$j++){
				if ($stritem!=""){
					$stritem.=" or id='".$dataInfo[$j]['id']."'";
					$strlogsitem.=" or cateId='".$dataInfo[$j]['id']."'";
				}else{
					$stritem.="id='".$dataInfo[$j]['id']."'";
					$strlogsitem.="cateId='".$dataInfo[$j]['id']."'";
				}
			}
		}
		
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
			$strlogsitem.=" or cateId='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
			$strlogsitem.="cateId='$itemlist[$i]'";
		}
	}
	
	//删除类别与日志
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."categories where $stritem";
		$DMC->query($sql);

		$sql="delete from ".$DBPrefix."logs where $strlogsitem";
		$DMC->query($sql);

		settings_recount("categories");
		settings_recache();
	}


	//隐藏
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."categories set isHidden='1' where $stritem";
		$DMC->query($sql);
	}

	//显示
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."categories set isHidden='0' where $stritem";
		$DMC->query($sql);
	}

	//移动类别
	if($_POST['operation']=="move" and $stritem!=""){		
		$sql="update ".$DBPrefix."categories set parent='".$_POST['parent']."' where $stritem";
		$DMC->query($sql);
	}
	//echo $sql;

	//更新cache
	categories_recache();
	logs_sidebar_recache($arrSideModule);
}

if ($action=="all"){
	$seekname="";
}


$seek_url="$PHP_SELF?showmode=".$_GET['showmode']."&order=$order";	//查找用链接
$order_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$showmode_url="$PHP_SELF?order=$order&page=$page";	//展开／折叠链接

if ($action=="add"){
	//新增信息类别。
	$title="$strCategoryTitleAdd";
	$cateIcons=1;

	include("categories_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strCategoryTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."categories where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$cateTitle=$dataInfo['cateTitle'];
		$parent=$dataInfo['parent'];
		$url=$dataInfo['outLinkUrl'];
		$cateIcons=$dataInfo['cateIcons'];

		include("categories_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else if ($action=="order"){
	//调整类别顺序
	$title="$strCategoryExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."categories where parent='$mark_id' and isHidden=0 order by orderNo"));
	if ($arr_parent) {
		include("categories_order.inc.php");	
	}else{
		$error_message=$strCategoryExchangeNoData;
		include("error_web.php");
	}
}else{
	//查找和浏览
	$title="$strCategoryTitle";

	if ($order==""){$order="orderNo";}

	//把没有类别图片的改为１
	$DMC->query("update ".$DBPrefix."categories set cateIcons='1' where cateIcons='0'");

	//Find condition
	$find=" and parent='0'";
	if ($seekname!=""){$find.=" and (cateTitle like '%$seekname%' or name like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."categories where $find order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."categories where $find";
	} else {
		$sql="select * from ".$DBPrefix."categories order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."categories";
	}

	$total_num=getNumRows($nums_sql);
	include("categories_list.inc.php");
}
?>
<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("modules".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=5;
$mtitle=$strModuleSetting;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;

	//取得Form的Value
	$name=$_POST['name'];
	$modTitle=encode($_POST['modTitle']);
	$disType=trim($_POST['disType']);
	$oldDisType=trim($_POST['oldDisType']);
	$isHidden=(trim($_POST['isHidden']))?trim($_POST['isHidden']):0;
	$htmlCode=encode($_POST['htmlCode']);
	$pluginPath=encode($_POST['pluginPath']);

	$indexOnly=0;
	$installFolder="";
	switch($disType) {
		case 1:
			$indexOnly=$_POST['indexOnly2'];
			break;
		case 2:
			$indexOnly=$_POST['indexOnly3'];
			break;
		case 3:
			$indexOnly=$_POST['indexOnly'];
			break;
	}

	//检测输入内容为空
	if ($name=="" or $modTitle==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	//检查输入的模块名称是否合法
	if ($check_info==1 && check_user($name)==0) {
		$ActionMessage=$strUserInVail;
		$check_info=0;
	}

	if ($check_info==1 && $disType==1 && $pluginPath=="") {
		$ActionMessage=$strModTopAlert;
		$check_info=0;
	}

	if ($check_info==1){
		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."modules","name='$name' and disType='$disType'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderSQL="";
				if ($disType!=$oldDisType) {
					$orderno=getFieldValue($DBPrefix."modules","disType='$disType' and id!='$mark_id' order by orderNo desc","orderNo");
					if ($orderno<1){
						$orderno=1;
					}else{
						$orderno++;
					}
					$orderSQL=",orderNo='$orderno'";
				}

				$sql="update ".$DBPrefix."modules set name='$name',modTitle='$modTitle',disType='$disType',isHidden='$isHidden',indexOnly='$indexOnly',htmlCode='$htmlCode',pluginPath='$pluginPath',isInstall='$isInstall'$orderSQL where id='$mark_id'";
				$DMC->query($sql);

				$ActionMessage=$strSaveSuccess;
				$action="";
			}
		}else{//新增
			$rsexits=getFieldValue($DBPrefix."modules","name='$name' and disType='$disType'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$orderno=getFieldValue($DBPrefix."modules","disType='$disType' order by orderNo desc","orderNo");
				if ($orderno<1){
					$orderno=1;
				}else{
					$orderno++;
				}
				$sql="INSERT INTO ".$DBPrefix."modules(name,modTitle,disType,isHidden,indexOnly,orderNo,htmlCode,pluginPath,isInstall) VALUES ('$name','$modTitle','$disType','$isHidden','$indexOnly','$orderno','$htmlCode','$pluginPath','$isInstall')";
				$DMC->query($sql);				

				$ActionMessage=$strSaveSuccess;
				$action="";
			}
		}

		if ($action=="") {
			modules_recache();
		}
	} else {
		$action="add";
	}
}

//保存排序
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."modules set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}

	$ActionMessage=$strSaveSuccess;
	modules_recache();
}

//删除
if ($action=="delete"){
	$sql="delete from ".$DBPrefix."modules where id='".$mark_id."'";
	$DMC->query($sql);

	$ActionMessage=$strDeleteSuccess;
	modules_recache();
}

//其它操作行为：隐藏／显示、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$setupdate=false;
	for ($i=0;$i<count($itemlist);$i++){
		if ($_POST['operation']=="ishidden" || $_POST['operation']=="isshow"){
			$settValue=($_POST['operation']=="ishidden")?1:0;
			$catename=getFieldValue($DBPrefix."modules","id='$itemlist[$i]'","name");
			$catename=($catename=="userPanel")?"isRegister":$catename;
			if ($catename=="skinSwitch"){
				$sql="update ".$DBPrefix."setting set settValue='$settValue' where settName='$catename'";
				$DMC->query($sql);
				$setupdate=true;
			}			
		}
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
	}

	//模块隐藏
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isHidden='1' where $stritem";
		$DMC->query($sql);
	}

	//模块显示
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isHidden='0' where $stritem";
		$DMC->query($sql);
	}

	//侧边栏显示
	if($_POST['operation']=="isInstallshow" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isInstall='0' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//侧边栏隐藏
	if($_POST['operation']=="isinstallhidden" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isInstall='1' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//侧边栏仅显示在首页
	if($_POST['operation']=="isIndexOnly" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set indexOnly='1' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//侧边栏显示在首页及阅读页面。
	if($_POST['operation']=="isIndexNo" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set indexOnly='0' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	modules_recache();
	if ($setupdate==true) settings_recache();
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
	$title="$strModuleTitleAdd";
	if (empty($disType)) $disType=1;
	if (!isset($isHidden)) $isHidden=0;
	if (!isset($isInstall)) $isInstall=0;
	if (!isset($indexOnly2)) $indexOnly2=0;
	if (!isset($indexOnly3)) $indexOnly3=0;
	if (!isset($cateId)) $cateId=0;

	include("modules_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strModuleTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."modules where id='$mark_id'"));
	if ($dataInfo) {
		$name=dencode($dataInfo['name']);
		$modTitle=dencode($dataInfo['modTitle']);
		$disType=$dataInfo['disType'];
		$oldDisType=$dataInfo['disType'];
		$isHidden=$dataInfo['isHidden'];
		$indexOnly=$dataInfo['indexOnly'];
		$orderNo=$dataInfo['orderNo'];
		$htmlCode=$dataInfo['htmlCode'];
		$pluginPath=$dataInfo['pluginPath'];
		$isInstall=$dataInfo['isInstall'];
		$installFolder=$dataInfo['installFolder'];
		$cateId=$dataInfo['cateId'];
		$indexOnly2=$indexOnly;
		$indexOnly3=$indexOnly;

		$htmlCode=str_replace("&lt;","<",$htmlCode);
		$htmlCode=str_replace("&gt;",">",$htmlCode);
		$htmlCode=str_replace("&#39;","'",$htmlCode);
		$htmlCode=str_replace("&quot;","\"",$htmlCode);	
		$htmlCode=str_replace("<br />","",$htmlCode);

		include("modules_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else if ($action=="order"){
	//调整类别顺序
	$title="$strCategoryExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."modules where disType='$mark_id' and isHidden=0 order by orderNo"));
	if ($arr_parent) {
		include("modules_order.inc.php");	
	}else{
		$error_message=$strCategoryExchangeNoData;
		include("error_web.php");
	}
}else{
	//查找和浏览
	$title="$strModuleTitle";

	if ($order==""){$order="orderNo";}

	//Find condition
	$find=" and disType='0' and id!='88'";
	if ($seekname!=""){$find.=" and (modTitle like '%$seekname%' or name like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."modules where $find order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."modules where $find";
	} else {
		$sql="select * from ".$DBPrefix."modules order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."modules";
	}

	$total_num=getNumRows($nums_sql);
	include("modules_list.inc.php");
}
?>
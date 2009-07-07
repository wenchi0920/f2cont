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
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;

	//取得Form的Value
	$name=trim($_POST['name']);
	$modTitle=trim($_POST['modTitle']);
	$disType=trim($_POST['disType']);
	$oldDisType=trim($_POST['oldDisType']);
	$isHidden=trim($_POST['isHidden']);
	$indexOnly=trim($_POST['indexOnly']);
	$orderNo=trim($_POST['orderNo']);
	$htmlCode=trim($_POST['htmlCode']);
	$pluginPath=trim($_POST['pluginPath']);
	$isInstall=trim($_POST['isInstall']);
	
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

	/*if ($check_info==1 && $disType==2) {
		$ActionMessage=$strModSideAlert;
		$check_info=0;
	}*/

	if ($check_info==1 && $disType!=3 && $indexOnly==1) {
		$ActionMessage=$strModIndexOnlyAlert;
		$check_info=0;
	}

	if ($check_info==1){
		$htmlCode=encode($htmlCode);
		$name=encode($name);
		$modTitle=encode($modTitle);
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

				$sql="update ".$DBPrefix."modules set name='$name',modTitle=\"$modTitle\",disType='$disType',isHidden='$isHidden',indexOnly='$indexOnly',htmlCode=\"$htmlCode\",pluginPath='$pluginPath',isInstall='$isInstall'$orderSQL where id='$mark_id'";
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
				$sql="INSERT INTO ".$DBPrefix."modules(name,modTitle,disType,isHidden,indexOnly,orderNo,htmlCode,pluginPath,isInstall) VALUES ('$name',\"$modTitle\",'$disType','$isHidden','$indexOnly','$orderno',\"$htmlCode\",'$pluginPath','$isInstall')";
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
		$sql="update ".$DBPrefix."modules set orderNo='".$_POST['orderNo'][$i]."' where id='".$_POST['arrid'][$i]."'";
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
	$strlogsitem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
			$strlogsitem.=" or cateId='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
			$strlogsitem.="CateId='$itemlist[$i]'";
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

	modules_recache();
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
		$htmlCode=str_replace("<br />","",dencode($dataInfo['htmlCode']));
		$pluginPath=$dataInfo['pluginPath'];
		$isInstall=$dataInfo['isInstall'];

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
	} else {
		$sql="select * from ".$DBPrefix."modules order by $order";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("modules_list.inc.php");
}
?>
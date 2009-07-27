<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("modules".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=5;
$mtitle=$strModuleSetting;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//��������
if ($action=="save"){
	$check_info=1;

	//ȡ��Form��Value
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

	//�����������Ϊ��
	if ($name=="" or $modTitle==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	//��������ģ�������Ƿ�Ϸ�
	if ($check_info==1 && check_user($name)==0) {
		$ActionMessage=$strUserInVail;
		$check_info=0;
	}

	if ($check_info==1 && $disType==1 && $pluginPath=="") {
		$ActionMessage=$strModTopAlert;
		$check_info=0;
	}

	if ($check_info==1){
		if ($mark_id!=""){//�༭
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
		}else{//����
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

//��������
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."modules set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}

	$ActionMessage=$strSaveSuccess;
	modules_recache();
}

//ɾ��
if ($action=="delete"){
	$sql="delete from ".$DBPrefix."modules where id='".$mark_id."'";
	$DMC->query($sql);

	$ActionMessage=$strDeleteSuccess;
	modules_recache();
}

//����������Ϊ�����أ���ʾ��ɾ����
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

	//ģ������
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isHidden='1' where $stritem";
		$DMC->query($sql);
	}

	//ģ����ʾ
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isHidden='0' where $stritem";
		$DMC->query($sql);
	}

	//�������ʾ
	if($_POST['operation']=="isInstallshow" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isInstall='0' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//���������
	if($_POST['operation']=="isinstallhidden" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set isInstall='1' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//���������ʾ����ҳ
	if($_POST['operation']=="isIndexOnly" and $stritem!=""){
		$sql="update ".$DBPrefix."modules set indexOnly='1' where ($stritem) and disType='2'";
		$DMC->query($sql);
	}

	//�������ʾ����ҳ���Ķ�ҳ�档
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


$seek_url="$PHP_SELF?showmode=".$_GET['showmode']."&order=$order";	//����������
$order_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname";	//�������õ�����
$page_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&order=$order&page=$page";	//�༭����������
$showmode_url="$PHP_SELF?order=$order&page=$page";	//չ�����۵�����

if ($action=="add"){
	//������Ϣ���
	$title="$strModuleTitleAdd";
	if (empty($disType)) $disType=1;
	if (!isset($isHidden)) $isHidden=0;
	if (!isset($isInstall)) $isInstall=0;
	if (!isset($indexOnly2)) $indexOnly2=0;
	if (!isset($indexOnly3)) $indexOnly3=0;
	if (!isset($cateId)) $cateId=0;

	include("modules_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
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
	//�������˳��
	$title="$strCategoryExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."modules where disType='$mark_id' and isHidden=0 order by orderNo"));
	if ($arr_parent) {
		include("modules_order.inc.php");	
	}else{
		$error_message=$strCategoryExchangeNoData;
		include("error_web.php");
	}
}else{
	//���Һ����
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
<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("categories".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=1;
$mtitle=$strCategory;

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['mark_id'];

//��������
if ($action=="save"){
	$check_info=1;
	//�����������
	if (trim($_POST['name'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	if ($check_info==1){
		if ($mark_id!=""){//�༭
			$rsexits=getFieldValue($DBPrefix."categories","name='".encode($_POST['name'])."' and parent='".$_POST['parent']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."categories set parent='".$_POST['parent']."',name='".encode($_POST['name'])."',cateTitle='".encode($_POST['cateTitle'])."',outLinkUrl='".encode($_POST['url'])."',cateIcons='".$_POST['cateIcons']."' where id='$mark_id'";
				$DMC->query($sql);

				//����cache
				categories_recache();
				logs_sidebar_recache($arrSideModule);
			}
		}else{//����
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
				
				//����cache
				categories_recache();
				settings_recount("categories");
				settings_recache();
				logs_sidebar_recache($arrSideModule);
			}
		}
	}
}

//��������
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."categories set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	//����cache
	categories_recache();
	logs_sidebar_recache($arrSideModule);
}

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$strlogsitem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		//����ƶ���������������ԭ�������Ҳ�ƶ���Ӧ��������¡�
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
	
	//ɾ���������־
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."categories where $stritem";
		$DMC->query($sql);

		$sql="delete from ".$DBPrefix."logs where $strlogsitem";
		$DMC->query($sql);

		settings_recount("categories");
		settings_recache();
	}


	//����
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."categories set isHidden='1' where $stritem";
		$DMC->query($sql);
	}

	//��ʾ
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."categories set isHidden='0' where $stritem";
		$DMC->query($sql);
	}

	//�ƶ����
	if($_POST['operation']=="move" and $stritem!=""){		
		$sql="update ".$DBPrefix."categories set parent='".$_POST['parent']."' where $stritem";
		$DMC->query($sql);
	}
	//echo $sql;

	//����cache
	categories_recache();
	logs_sidebar_recache($arrSideModule);
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
	$title="$strCategoryTitleAdd";
	$cateIcons=1;

	include("categories_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
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
	//�������˳��
	$title="$strCategoryExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."categories where parent='$mark_id' and isHidden=0 order by orderNo"));
	if ($arr_parent) {
		include("categories_order.inc.php");	
	}else{
		$error_message=$strCategoryExchangeNoData;
		include("error_web.php");
	}
}else{
	//���Һ����
	$title="$strCategoryTitle";

	if ($order==""){$order="orderNo";}

	//��û�����ͼƬ�ĸ�Ϊ��
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
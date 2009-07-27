<?php 
require_once("function.php");

//�����ڱ�վ����
$server_session_id=md5("keywords".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// ��֤�û��Ƿ��ڵ�½״̬
check_login();
$parentM=6;
$mtitle=$strKeyword;

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

	if ($check_info==1 && trim($_POST['linkUrl'])==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
	}

	//�������ͼƬ�ĳߴ�
	if ($check_info==1 && !check_image_type($_FILES["linkImage"]["name"])) {
		$check_info=0;
		$action=($mark_id!="")?"edit":"add";
		$ActionMessage=$strCategoryImage.$strImgTypeMemo;
	}
	
	if ($check_info==1 && $_FILES["linkImage"]["name"]!="") {
		$arrISize=get_image_size($_FILES["linkImage"]["tmp_name"]);
		if ($arrISize[1]>16 && $arrISize[3]>16) {
			$check_info=0;
			$ActionMessage=$strCategoryImageError;
			$action=($mark_id!="")?"edit":"add";
		}else{
			//�ϴ�ͼƬ
			$linkImage=upload_file($_FILES["linkImage"]["tmp_name"],$_FILES["linkImage"]["name"],"../attachments");
		}
	}else{
		$linkImage="";
	}


	if ($check_info==1){
		if ($mark_id!=""){//�༭
			$rsexits=getFieldValue($DBPrefix."keywords","keyword='".encode($_POST['name'])."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				if ($linkImage!=""){
					$sql="update ".$DBPrefix."keywords set keyword='".encode($_POST['name'])."',linkUrl='".encode($_POST['linkUrl'])."',linkImage='".encode($linkImage)."' where id='$mark_id'";
				}else{
					$sql="update ".$DBPrefix."keywords set keyword='".encode($_POST['name'])."',linkUrl='".encode($_POST['linkUrl'])."' where id='$mark_id'";
				}
				$DMC->query($sql);
				$action="";
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."keywords","keyword='".encode($_POST['name'])."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$sql="INSERT INTO ".$DBPrefix."keywords(keyword,linkUrl,linkImage) VALUES('".encode($_POST['name'])."','".encode($_POST['linkUrl'])."','".encode($linkImage)."')";
				$DMC->query($sql);
				$action="";
			}
		}

		if ($action=="") {
			keywords_recache();
		}
	}
}

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		//�h���ļ�
		delAttachments($DBPrefix."keywords",$itemlist[$i],"linkImage");

		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
	}
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."keywords where $stritem";
		$DMC->query($sql);
		keywords_recache();
	}
}

if ($action=="all"){
	$seekname="";
}


$seek_url="$PHP_SELF?order=$order";	//����������
$order_url="$PHP_SELF?seekname=$seekname";	//�������õ�����
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//�༭����������

if ($action=="add"){
	//������Ϣ���
	$title="$strKeywordsTitleAdd";

	include("keywords_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strKeywordsTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."keywords where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['keyword'];
		$linkUrl=$dataInfo['linkUrl'];
		$linkImage=$dataInfo['linkImage'];

		include("keywords_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{
	//���Һ����
	$title="$strKeywordsTitle";

	if ($order==""){$order="id";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (name like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."keywords where $find order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."keywords where $find";
	} else {
		$sql="select * from ".$DBPrefix."keywords order by $order";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."keywords";
	}

	$total_num=getNumRows($nums_sql);
	include("keywords_list.inc.php");
}
?>
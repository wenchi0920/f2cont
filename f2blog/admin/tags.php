<?
$PATH="./";
include("$PATH/function.php");

// ��֤�û��Ƿ��ڵ�½״̬
check_login();

//�������
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=$_REQUEST['seekname'];
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
			$rsexits=getFieldValue($DBPrefix."tags","name='".encode($_POST['name'])."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				//�����˘˻`
				$isupdate=getFieldValue($DBPrefix."tags","id='$mark_id'","name");
				if ($isupdate!=encode($_POST['name'])){
					updateLogsTags($isupdate,encode($_POST['name']));
					
					$sql="update ".$DBPrefix."tags set name='".encode($_POST['name'])."' where id='$mark_id'";
					$DMC->query($sql);

					//����Cache
					hottags_recache();
				}
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."tags","name='".encode($_POST['name'])."'","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$sql="INSERT INTO ".$DBPrefix."tags(name) VALUES ('".encode($_POST['name'])."')";
				$DMC->query($sql);
				
				add_bloginfo("tagNums","adding",1);
				//����Cache
				hottags_recache();
				statistics_recache();
			}
		}
	}
}

//����������Ϊ���༭��ɾ����
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
		$sql="delete from ".$DBPrefix."tags where $stritem";
		$DMC->query($sql);
	}

	add_bloginfo("tagNums","minus",count($itemlist));
	statistics_recache();
	//����Cache
	hottags_recache();
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
	$title="$strTagsTitleAdd";

	include("tags_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strTagsTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."tags where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];

		include("tags_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else{
	//���Һ����
	$title="$strTagsTitle";

	if ($order==""){$order="id";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (name like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."tags where $find order by $order";
	} else {
		$sql="select * from ".$DBPrefix."tags order by $order";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("tags_list.inc.php");
}
?>
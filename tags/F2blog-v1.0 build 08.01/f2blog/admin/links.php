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
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($_POST['name'])."' and blogUrl='".$_POST['blogUrl']."'","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="edit";
			}else{
				$sql="update ".$DBPrefix."links set name='".encode($_POST['name'])."',blogUrl='".$_POST['blogUrl']."' where id='$mark_id'";
				$DMC->query($sql);
				$action="";
			}
		}else{//����
			$rsexits=getFieldValue($DBPrefix."links","name='".encode($_POST['name'])."' and blogUrl='".$_POST['blogUrl']."'","id");
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
				$sql="INSERT INTO ".$DBPrefix."links(name,blogUrl,orderNo) VALUES ('".encode($_POST['name'])."','".$_POST['blogUrl']."','$orderno')";
				$DMC->query($sql);
				$action="";
			}
		}

		if ($action=="") {
			do_action("f2_link");
			links_recache();
		}
	}
}


//��������
if ($action=="saveorder"){
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update ".$DBPrefix."links set orderNo='".$_POST['orderNo'][$i]."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}
	links_recache();
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
	
	//ɾ��
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."links where $stritem";
		$DMC->query($sql);
	}

	//����
	if($_POST['operation']=="ishidden" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isHidden='1' where $stritem";
		$DMC->query($sql);
	}

	//��ʾ
	if($_POST['operation']=="isshow" and $stritem!=""){
		$sql="update ".$DBPrefix."links set isHidden='0' where $stritem";
		$DMC->query($sql);
	}
	links_recache();
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
	$title="$strLinksTitleAdd";

	include("links_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//�༭��Ϣ���
	$title="$strLinksTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."links where id='$mark_id'"));
	if ($dataInfo) {
		$name=$dataInfo['name'];
		$blogUrl=$dataInfo['blogUrl'];

		include("links_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}	
}else if ($action=="order"){
	//�������˳��
	$title="$strLinksExchage";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."links order by orderNo"));
	if ($arr_parent) {
		include("links_order.inc.php");	
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else{
	//���Һ����
	$title="$strLinksTitle";

	if ($order==""){$order="orderNo";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (name like '%$seekname%')";}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."links where $find order by $order";
	} else {
		$sql="select * from ".$DBPrefix."links order by $order";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("links_list.inc.php");
}
?>
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
$mark_id=$_GET['id'];

if ($action=="deltb"){
	$my=getRecordValue($DBPrefix."comments"," id='$mark_id'");
	$logId=$my['logId'];
	//add_bloginfo("tbNums","minus",1);
	//update_num($DBPrefix."logs","quoteNums"," id='$logId'","minus",1);

	$sql="delete from ".$DBPrefix."comments where id='$mark_id'";
	$DMC->query($sql);
	header("Location:../index.php?load=read&id=$logId"); 
}

//����������Ϊ���༭��ɾ����
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$otype=($_POST['operation']=="show")?"adding":"minus";
	$nums=0;
	for ($i=0;$i<count($itemlist);$i++){
		$my=getRecordValue($DBPrefix."comments"," id='$itemlist[$i]'");
		$logId=$my['logId'];
		$isSecret=$my['isSecret'];
		if ($_POST['operation']=="delete" or ($_POST['operation']=="show" and $isSecret==1) or ($_POST['operation']=="hidden" and $isSecret==0) ) {
			update_num($DBPrefix."logs","commNums"," id='$logId'",$otype,1);
			$nums=$nums+1;

			if ($stritem!=""){
				$stritem.=" or id='$itemlist[$i]'";
			}else{
				$stritem.="id='$itemlist[$i]'";
			}

			//ɾ�����������ԣ���������Ҳɾ��
			if($_POST['operation']=="delete"){
				$stritem.=" or parent='$itemlist[$i]'";
			}
		}
	}

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."comments where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."comments set isSecret='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."comments set isSecret='0' where $stritem";
		$DMC->query($sql);
	}

	//����cache
	recentComments_recache();

	$action="";
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//����������
$order_url="$PHP_SELF?seekname=$seekname";	//�������õ�����
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//ҳ�浼������
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//�༭����������
$showmode_url="$PHP_SELF?order=$order&page=$page";	//չ�����۵�����

if ($action=="" or $action=="find" or $action=="all"){
	//���Һ����
	$title="$strCommentBrowse";

	if ($order==""){$order="postTime";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (tbTitle like '%$seekname%')";}
	
	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."comments where $find order by $order desc";
	} else {
		$sql="select * from ".$DBPrefix."comments order by $order desc";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("comments_list.inc.php");
}
?>
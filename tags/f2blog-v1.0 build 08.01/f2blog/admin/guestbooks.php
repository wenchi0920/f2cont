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
	$my=getRecordValue($DBPrefix."guestbook"," id='$mark_id'");
	$logId=$my['logId'];
	//add_bloginfo("tbNums","minus",1);
	update_num($DBPrefix."logs","quoteNums"," id='$logId'","minus",1);

	$sql="delete from ".$DBPrefix."guestbook where id='$mark_id'";
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

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."guestbook where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."guestbook set isSecret='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."guestbook set isSecret='0' where $stritem";
		$DMC->query($sql);
	}

	//����cache
	recentGbooks_recache();

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
	$title="$strGuestBookBrowse";

	if ($order==""){$order="postTime";}

	//Find condition
	$find=" and parent='0'";
	if ($seekname!=""){$find.=" and (content like '%$seekname%' or ip like '%$seekname%' or content like '%$seekname%')";}
	
	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."guestbook where $find order by $order desc";
	} else {
		$sql="select * from ".$DBPrefix."guestbook order by $order desc";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("guestbooks_list.inc.php");
}
?>
<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$mark_id=$_GET['id'];

if ($action=="deltb"){
	$my=getRecordValue($DBPrefix."trackbacks"," id='$mark_id'");
	$logId=$my['logId'];
	//add_bloginfo("tbNums","minus",1);
	update_num($DBPrefix."logs","quoteNums"," id='$logId'","minus",1);

	$sql="delete from ".$DBPrefix."trackbacks where id='$mark_id'";
	$DMC->query($sql);
	header("Location:../index.php?load=read&id=$logId"); 
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$otype=($_POST['operation']=="show")?"adding":"minus";
	$nums=0;
	for ($i=0;$i<count($itemlist);$i++){
		$my=getRecordValue($DBPrefix."trackbacks"," id='$itemlist[$i]'");
		$logId=$my['logId'];
		$isApp=$my['isApp'];
		if ($_POST['operation']=="delete" or ($_POST['operation']=="show" and $isApp==0) or ($_POST['operation']=="hidden" and $isApp==1) ) {
			update_num($DBPrefix."logs","quoteNums"," id='$logId'",$otype,1);
			$nums=$nums+1;

			if ($stritem!=""){
				$stritem.=" or id='$itemlist[$i]'";
			}else{
				$stritem.="id='$itemlist[$i]'";
			}
		}
	}

	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from ".$DBPrefix."trackbacks where $stritem";
		$DMC->query($sql);
		//add_bloginfo("tbNums",$otype,count($itemlist));
	}

	if($_POST['operation']=="hidden" and $stritem!=""){
		$sql="update ".$DBPrefix."trackbacks set isApp='0' where $stritem";
		$DMC->query($sql);
		//add_bloginfo("tbNums",$otype,$nums);
	}

	if($_POST['operation']=="show" and $stritem!=""){
		$sql="update ".$DBPrefix."trackbacks set isApp='1' where $stritem";
		$DMC->query($sql);
		//add_bloginfo("tbNums",$otype,$nums);
	}

	//settings_recache();
	$action="";
}

if ($action=="all"){
	$seekname="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$order_url="$PHP_SELF?seekname=$seekname";	//排序栏用的链接
$page_url="$PHP_SELF?seekname=$seekname&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?seekname=$seekname&order=$order&page=$page";	//编辑或新增链接
$showmode_url="$PHP_SELF?order=$order&page=$page";	//展开／折叠链接

if ($action=="" or $action=="find" or $action=="all"){
	//查找和浏览
	$title="$strTrackbacksTitle";

	if ($order==""){$order="postTime";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (tbTitle like '%$seekname%')";}
	
	if ($find!=""){
		$find=substr($find,5);
		$sql="select * from ".$DBPrefix."trackbacks where $find order by $order desc";
	} else {
		$sql="select * from ".$DBPrefix."trackbacks order by $order desc";
	}

	$total_num=$DMC->numRows($DMC->query($sql));
	include("trackback_list.inc.php");
}
?>
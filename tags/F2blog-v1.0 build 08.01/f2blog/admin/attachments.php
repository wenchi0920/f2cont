<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];
$mark_id=$_GET['mark_id'];
$seekname=$_REQUEST['seekname'];
$seektype=$_REQUEST['seektype'];
$basedir = (isset($_GET['basedir']))?$_GET['basedir']:"../attachments/"; 
if (strrpos($basedir,"/")!=strlen($basedir)-1) $basedir.="/";
//echo $basedir;

//保存数据
if ($action=="save"){
	$check_info=1;
	//检测输入内容
	if ($_FILES['myfile']==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action="add";
	}

	//检测允许上传类型
	if ($check_info==1 && !checkFileType($_FILES["myfile"]["name"])){		
		$ActionMessage=$strAttachmentsError;
		$check_info=0;
		$action="add";
	}
	
	if ($check_info==1 && $_FILES["myfile"]["name"]!="") {
		//上传
		$attachment=upload_file($_FILES["myfile"]["tmp_name"],$_FILES["myfile"]["name"],$basedir);
		do_filter("f2_attach",$basedir."/".$attachment);
	}
}

//保存修改文件名
if ($action=="savefile"){
	$attach_id=$_POST['attach_id'];
	$new_file=$_POST['attach_name'].substr($attach_id,strrpos($attach_id,"."));
	$sql="update ".$DBPrefix."attachments set attTitle='".$new_file."' where name like '%".$attach_id."'";
	$DMC->query($sql);
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		//echo $basedir.$itemlist[$i];
		if (is_file($basedir.$itemlist[$i])){
			@unlink($basedir.$itemlist[$i]);
		}
		if (is_dir($basedir.$itemlist[$i])){
			//echo $basedir.$itemlist[$i];
			if (!@rmdir($basedir.$itemlist[$i])){
				$ActionMessage="$strAttachmentFolderError";
			}
		}
	}
}

if ($action=="all"){
	$seekname="";
	$seektype="";
}

$seek_url="$PHP_SELF?order=$order";	//查找用链接
$edit_url="$PHP_SELF?basedir=".urlencode($basedir)."&seekname=$seekname&seektype=$seektype";	//编辑或新增链接

if ($action=="add"){
	//新增。
	$title="$strAttachmentsTitleAdd";

	include("attachments_add.inc.php");	
}else if ($action=="edit" && $_GET['file_id']!=""){
	//修改文件名。
	$title="$strAttachmentsTitleEdit";
	$file_id=$_GET['file_id'];
	$file_title=getFieldValue($DBPrefix."attachments","where name like '%".$file_id."'","attTitle");
	if ($file_title!=""){$file_title=substr($file_title,0,strrpos($file_title,"."));}

	include("attachments_edit.inc.php");	
}else{
	//查找和浏览
	$title="$strAttachmentsTitle";

    $handle=opendir("$basedir"); 
    while ($file = readdir($handle)){ 
        if(is_file($basedir.$file)){			
			$filetype=getFileType($file);
			if ($seektype!=""){
				if (strpos(";$seektype",$filetype)>0)	$filelist[] = $file; 
			}else if ($seekname!=""){
				if (strpos(";$file",$seekname)>0) $filelist[] = $file; 
			}else{
				$filelist[] = $file; 
			}
		}
		
		if (is_dir($basedir.$file) && $file!="." && $file!=".."){
			$folderlist[]=$file;
		}
    } 
    closedir($handle); 
	if (count($filelist)>0){sort($filelist);}
	if (count($folderlist)>0){sort($folderlist);}

	include("attachments_list.inc.php");
}
?>
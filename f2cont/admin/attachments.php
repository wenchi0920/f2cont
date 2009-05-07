<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("attachments".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strAttachment;

//保存参数
$action=$_GET['action'];
$mark_id=intval($_GET['mark_id']);
$seekname=encode($_REQUEST['seekname']);
$seekcate=isset($_REQUEST['seekcate'])?encode($_REQUEST['seekcate']):"";
$seektype=isset($_REQUEST['seektype'])?$_REQUEST['seektype']:"";
$editorcode=isset($_GET['editorcode'])?$_GET['editorcode']:"";
$basedir = (isset($_GET['basedir']))?$_GET['basedir']:"../attachments/"; 
if (!preg_match("/^(..\/attachments\/)/i",$basedir)) $basedir="../attachments/";
if (strrpos($basedir,"/")!=strlen($basedir)-1) $basedir.="/";
$basedir=safe_convert($basedir);

//操作目录或数据库
if (empty($_GET['job'])){
	$job="folder";
}else{
	$job=$_GET['job'];
}

//保存数据
if ($action=="save"){
	$check_info=1;
	$Error_Message="";
	$OK_Message="";

	//检测输入内容
	if (empty($_FILES['myfile']) && empty($_POST['remotepath'])){
		$ActionMessage=$strErrNull;
		$check_info=0;
		$action="add";
	}

	//上传附件
	if ($check_info==1 && !empty($_FILES['myfile'])){
		$arrFileName=$_FILES['myfile']["name"];
		$arrFileSize=$_FILES['myfile']["size"];
		$arrTempName=$_FILES['myfile']["tmp_name"];
		$arrFileType=$_FILES['myfile']["type"];

		foreach($arrFileName as $key=>$value){
			$check_info=1;
			//检测允许上传类型
			if (!empty($value)){
				if (!checkFileType($arrFileName[$key])){		
					$Error_Message="$Error_Message \\n".$arrFileName[$key]." ... ".$strAttachmentsError;
					$check_info=0;
					$action="add";
				}
				
				if ($check_info==1 && $arrFileName[$key]!="") {
					//上传
					$attachment=upload_file($arrTempName[$key],$arrFileName[$key],$basedir);
					if ($attachment=="") {
						$Error_Message="$Error_Message<br />".$strAttachmentsError;
						$action="add";
					}else{
						do_filter("f2_attach",$basedir."/".$attachment);
						
						$filename=str_replace("../attachments/","",$basedir.$attachment);
						if($imageAtt=getimagesize("../attachments/$filename")){
							$fileWidth=$imageAtt[0];
							$fileHeight=$imageAtt[1];
						}else{
							$fileWidth=0;
							$fileHeight=0;
						}
						$fileType=getFileType($arrFileName[$key]);

						//写进数据库
						$myremark[$key]=($myremark[$key]=="")?basename($arrFileName[$key]):encode($myremark[$key]).".".$fileType;
						$rsexits=getFieldValue($DBPrefix."attachments","attTitle='".$myremark[$key]."' and fileType='".$arrFileType[$key]."' and fileSize='".$arrFileSize[$key]."' and logId='0'","name");
						if ($rsexits==""){
							$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime) VALUES ('$filename','$myremark[$key]','$arrFileType[$key]','$arrFileSize[$key]','$fileWidth','$fileHeight','".time()."')";			
							$DMC->query($sql);
							//echo $sql."<br>";
							$OK_Message=$OK_Message."\\n".$arrFileName[$key]." ... OK";
						}else{
							$OK_Message=$OK_Message."\\n".$arrFileName[$key]." ... $strDataExists";
						}
					}
				}
			}
		}
	}

	//保存网址
	if ($check_info==1 && !empty($_POST['remotepath'])){
		foreach($_POST['remotepath'] as $key=>$value){
			//写进数据库
			if (!empty($value)){
				$fileType=getFileType($value);
				$fileremark=($_POST['remoteremark'][$key]=="")?basename($value):encode($_POST['remoteremark'][$key]).".".$fileType;
				$fileType=convertFileType($fileType);

				if($imageAtt=@getimagesize($value)){
					$fileWidth=$imageAtt[0];
					$fileHeight=$imageAtt[1];
				}else{
					$fileWidth=0;
					$fileHeight=0;
				}		

				$rsexits=getFieldValue($DBPrefix."attachments","name='".$value."' and fileType='".$fileType."' and logId='0'","name");
				if ($rsexits==""){
					$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime,logId) VALUES ('$value','$fileremark','$fileType','0','$fileWidth','$fileHeight','".time()."','0')";			
					$DMC->query($sql);
					//echo $sql."<br>";
					$OK_Message=$OK_Message."\\n".$fileremark." ... OK";
				}else{
					$OK_Message=$OK_Message."\\n".$fileremark." ... $strDataExists";
				}
			}
		}
	}

	if ($check_info==1){
		//重新加载页面
		if (empty($Error_Message) && $editorcode!=""){
			echo "<script language=javascript> \n";
			echo " opener.location.href='attach.php?editorcode=$editorcode&mark_id=$mark_id';\n";
			echo " opener.reload;\n";
			echo " window.close();\n";
			echo "</script> \n";
		}else{
			$ActionMessage=$Error_Message.$OK_Message;
		}
	}
}

//保存修改文件名
if ($action=="savefile" && !empty($_POST['file_id'])){
	$file_id=intval($_POST['file_id']);
	//更新附件
	if (!empty($_FILES['myfile'])){
		$check_info=1;
		$arrFileName=$_FILES['myfile']["name"];
		$arrFileSize=$_FILES['myfile']["size"];
		$arrTempName=$_FILES['myfile']["tmp_name"];
		$arrFileType=$_FILES['myfile']["type"];

		$fileTitle=encode($_POST['fileTitle']);

		if (!checkFileType($arrFileName)){		
			$ActionMessage=$strAttachmentsError;
			$check_info=0;
			$action="edit";
		}
		
		if ($check_info==1 && $arrFileName!="") {
			//上传
			$attachment=upload_file($arrTempName,$arrFileName,$basedir);
			if ($attachment=="") {
				$ActionMessage=$strAttachmentsError;
				$action="edit";
			}else{
				do_filter("f2_attach",$basedir."/".$attachment);
				
				$filename=str_replace("../attachments/","",$basedir.$attachment);
				if($imageAtt=getimagesize("../attachments/$filename")){
					$fileWidth=$imageAtt[0];
					$fileHeight=$imageAtt[1];
				}else{
					$fileWidth=0;
					$fileHeight=0;
				}
				$fileType=getFileType($arrFileName);
			
				$new_file=$fileTitle.".".$fileType;

				$sql="update ".$DBPrefix."attachments set name='$filename',attTitle='$new_file',fileType='$arrFileType',fileSize='$arrFileSize',fileWidth='$fileWidth',fileHeight='$fileHeight',postTime='".time()."' where id='".$file_id."'";		
				$DMC->query($sql);
				//echo $sql;
			}
		}
	}else{
		//修改文件信息
		$fileName=encode($_POST['fileName']);
		$fileWidth=intval($_POST['fileWidth']);
		$fileHeight=intval($_POST['fileHeight']);
		$fileSize=intval($_POST['fileSize']);
		$fileTitle=encode($_POST['fileTitle']);

		$fileType=getFileType($fileName);
		$fileTitle=$fileTitle.".".$fileType;
		$fileType=convertFileType($fileType);

		$sql="update ".$DBPrefix."attachments set name='$fileName',attTitle='$fileTitle',fileWidth='$fileWidth',fileHeight='$fileHeight',fileSize='$fileSize',fileType='$fileType',postTime='".time()."' where id='".$file_id."'";
		$DMC->query($sql);
		//echo $sql;
	}
}

//保存文件夹
if ($action=="savefolder"){
	$foldername=$_POST['myfolder'];
	if (check_fileName($foldername)){
		if (!check_dir($basedir.$foldername)){
			$ActionMessage="$strAttachmentNoFolder";
			$action="addfolder";
		}
	}else{
		$ActionMessage="$strAttachmentErrorFolder";
		$action="addfolder";
	}
}

//其它操作行为：编辑、删除等
if ($action=="operation"){
	$stritem="";
	$itemlist=empty($_POST['itemlist'])?array():$_POST['itemlist'];

	if($_POST['operation']=="delete"){
		$Error_Message="";
		for ($i=0;$i<count($itemlist);$i++){
			if (strpos(";".$itemlist[$i],"://")>0){
				//删除记录
				$delete_sql="delete from ".$DBPrefix."attachments where name='".$itemlist[$i]."'";
				$DMC->query($delete_sql);
			}else{
				$filename=$basedir.$itemlist[$i];
				$fileName=str_replace("../attachments/","",$filename);

				//删除记录
				$delete_sql="delete from ".$DBPrefix."attachments where name='".$fileName."'";
				$DMC->query($delete_sql);

				if (@is_file($basedir.$itemlist[$i])){
					@unlink($filename);
				}
					
				if (@is_dir($basedir.$itemlist[$i])){
					//echo $basedir.$itemlist[$i];
					if (!@rmdir($basedir.$itemlist[$i])){
						$ActionMessage=$ActionMessage."\\n".$basedir.$itemlist[$i]."$strAttachmentFolderError";
					}
				}
			}
		}
	}

	if($_POST['operation']=="insert"){
		//写进数据库
		$check_info=1;
		$Error_Message="";
		$OK_Message="";
		for ($i=0;$i<count($itemlist);$i++){
			if (strpos(";".$itemlist[$i],"://")>0){
				$rsexits=getFieldValue($DBPrefix."attachments","name='".$itemlist[$i]."' and logId='0'","attTitle");
				if ($rsexits==""){
					if ($arr_result=$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."attachments where name='".$itemlist[$i]."'"))){					
						$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime,logId) VALUES ('".$arr_result['name']."','".$arr_result['attTitle']."','".$arr_result['fileType']."','".$arr_result['fileSize']."','".$arr_result['fileWidth']."','".$arr_result['fileHeight']."','".time()."',0)";			
						$DMC->query($sql);
						$OK_Message=$OK_Message."\\n".basename($itemlist[$i])." ... OK";
					}else{
						$Error_Message=$Error_Message."\\n".$rsexits." ... ".$strNoExits;
						$check_info=0;
					}
				}else{
					$Error_Message=$Error_Message."\\n".$rsexits." ... ".$strDataExists;
					$check_info=0;
				}
			}else{
				if (@is_file($basedir.$itemlist[$i])){
					$filename=$basedir.$itemlist[$i];
					$fileName=str_replace("../attachments/","",$filename);
					$fileTitle=basename($filename);
					$fileType=getFileType($fileName);
					$fileSize=filesize($filename);

					if($imageAtt=@getimagesize($filename)){
						$fileWidth=$imageAtt[0];
						$fileHeight=$imageAtt[1];
					}else{
						$fileWidth=0;
						$fileHeight=0;
					}

					$rsexits=getFieldValue($DBPrefix."attachments","name='".$fileName."' and logId='0'","attTitle");
					if ($rsexits==""){
						$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime,logId) VALUES ('$fileName','$fileTitle','$fileType','$fileSize','$fileWidth','$fileHeight','".time()."',0)";			
						$DMC->query($sql);
						$OK_Message=$OK_Message."\\n".$fileTitle." ... OK";
					}else{
						$Error_Message=$Error_Message."\\n".$rsexits." ... ".$strDataExists;
						$check_info=0;
					}
				}else{
					$Error_Message=$Error_Message."\\n".$basedir.$itemlist[$i]." ... ".$strNoExits;
					$check_info=0;
				}
			}
		}
		
		//重新加载页面
		if ($check_info==1 && $Error_Message=="" && $editorcode!=""){
			echo "<script language=javascript> \n";
			echo " opener.location.href='attach.php?editorcode=$editorcode&mark_id=$mark_id';\n";
			echo " opener.reload;\n";
			echo " window.close();\n";
			echo "</script> \n";
		}else{
			$ActionMessage=$Error_Message.$OK_Message;
		}
	}

	//更改所有域名
	if($_POST['operation']=="change"){
		if (empty($_POST['oldName']) || empty($_POST['newName'])){
			$ActionMessage=$strAttachmentChangeAlert;
		}else{
			$oldName=encode($_POST['oldName']);
			$newName=encode($_POST['newName']);
			$sql="update ".$DBPrefix."attachments set name=replace(name,'$oldName','$newName') where name like '%$oldName%'";
			$DMC->query($sql);
		}
	}
}

if ($action=="all"){
	$seekname="";
	$seektype="";
	$seekcate="";
}

$seek_url="$PHP_SELF?job=$job&editorcode=$editorcode&mark_id=$mark_id";	//查找用链接
$edit_url="$PHP_SELF?job=$job&basedir=".urlencode($basedir)."&editorcode=$editorcode&mark_id=$mark_id&seekname=$seekname&seektype=$seektype&seekcate=$seekcate";	//编辑或新增链接
$job_url="$PHP_SELF?editorcode=$editorcode&mark_id=$mark_id";	//查找用链接

if ($action=="add"){
	//新增。
	$title="$strAttachmentsTitleAdd";

	include("attachments_add.inc.php");	
}else if ($action=="edit" && !empty($_GET['file_id'])){
	//修改文件名。
	$title="$strAttachmentsTitleEdit";
	$file_id=encode($_GET['file_id']);

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."attachments where id='$file_id'"));
	if ($dataInfo) {
		$fileTitle=$dataInfo['attTitle'];
		$fileName=$dataInfo['name'];
		$fileSize=$dataInfo['fileSize'];
		$fileWidth=$dataInfo['fileWidth'];
		$fileHeight=$dataInfo['fileHeight'];

		if (strpos($fileTitle,".")>0){$fileTitle=substr($fileTitle,0,strrpos($fileTitle,"."));}

		include("attachments_edit.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}		
}else if ($action=="addfolder"){
	//修改文件名。
	$title="$strAttachmentFolder";

	include("attachments_folder.inc.php");		
}else{
	//列表
	if ($job=="db"){	//按数据库列表
		//修改文件名。
		$title="$strAttachmentDatabase";

		if (empty($order)){$order="a.postTime";}
		if (empty($page)) {$page=1;}

		$seek_url="$PHP_SELF?job=$job&editorcode=$editorcode&mark_id=$mark_id&order=$order";	//查找用链接
		$order_url="$PHP_SELF?job=$job&editorcode=$editorcode&mark_id=$mark_id&seekname=$seekname&seektype=$seektype&seekcate=$seekcate";	//排序栏用的链接
		$page_url="$PHP_SELF?job=$job&editorcode=$editorcode&mark_id=$mark_id&seekname=$seekname&seektype=$seektype&seekcate=$seekcate&order=$order";	//页面导航链接
		$edit_url="$PHP_SELF?job=$job&editorcode=$editorcode&mark_id=$mark_id&seekname=$seekname&seektype=$seektype&seekcate=$seekcate&order=$order&page=$page";	//编辑或新增链接

		//Find condition
		$find="";
		if ($seektype!=""){
			$arrFileType=explode(",",$seektype);
			$type="";
			foreach($arrFileType as $value){
				if ($type==""){
					$type="attTitle like '%.$value'";
				}else{
					$type.=" or attTitle like '%.$value'";
				}
			}
			$find.=" and ($type)";
		}

		if ($seekname!=""){$find.=" and (a.id like '%$seekname%' or a.logId like '%$seekname%' or attTitle like '%$seekname%' or name like '%$seekname%' or fileType like '%$seekname%')";}

		if ($seekcate!=""){$find.=" and b.cateId='$seekcate'";}

		if ($find!=""){
			$find=substr($find,5);
			$sql="select a.*,b.logTitle from ".$DBPrefix."attachments as a left join ".$DBPrefix."logs as b on a.logId=b.id where $find order by $order desc";
			$nums_sql="select count(a.id) as numRows from ".$DBPrefix."attachments as a left join ".$DBPrefix."logs as b on a.logId=b.id where b.cateId='$seekcate' and $find";
		} else {
			$sql="select a.*,b.logTitle from ".$DBPrefix."attachments as a left join ".$DBPrefix."logs as b on a.logId=b.id order by $order desc";
			$nums_sql="select count(a.id) as numRows from ".$DBPrefix."attachments as a";
		}
		$total_num=getNumRows($nums_sql);

		include("attachments_dblist.inc.php");	
	}else{
		//查找和浏览
		$title="$strAttachmentDisk";

		$handle=opendir("$basedir"); 
		$folderlist=array();
		$filelist=array();
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
}
?>
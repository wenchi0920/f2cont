<?PHP
$pluginUrl = "bilder/";
$folderFile = "config.txt";
$picList = "bilder.txt";

$head=<<<HTML
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="pragma" content="no-cache"> 
<meta http-equiv="expires" content="wed, 26 Feb 1997 08:21:57 GMT"> 
<meta name="robots" content="all" />
<meta name="author" content="joesenhong@yahoo.com.cn" />
</head>
HTML;
echo $head;

$job=$_REQUEST['job'];

if(empty($job)){
	$job = "default";
}
if($job == "default"){
	//文件夹显示模块
	$folderContent = file_get_contents($pluginUrl.$folderFile);
	$folderArray = explode("&", $folderContent);
	$folderSize = getValue($folderArray[1]);
	$foldersString = getValue($folderArray[2]);
	$folders = explode("|", $foldersString);
	$folderComb = createFolderList($folders, "");
	
	//文件显示模块
	$cfolder = $_POST["folder_select"];
	if(!$cfolder){
		$cfolder = $folders[0];
	}
	$picListInfotxt = $pluginUrl.$cfolder."/".$picList;
	$picListContent = file_get_contents($picListInfotxt);
	$picListContentArray = explode("&", $picListContent);
	$picNum = getValue($picListContentArray[1]);
	$picsString = getValue($picListContentArray[2]);
	$pics = explode("|", $picsString);
	$picCfolderList = createFolderList($folders, $cfolder);
	
	$piclistShow = "<input type='hidden' name='current_folder' id='current_folder' value='$cfolder'>";
	$piclistShow .= "<input type='hidden' name='pic_number' id='pic_number' value='$picNum'>";
	for ($i = 0; $i < $picNum; $i ++){
		$piclistShow .= "<tr class='sect'>";
		$picname = $pics[$i];
		$fullfileurl = $pluginUrl.$cfolder."/".$picname;
		$infotime = filectime($fullfileurl);
		$infosize = round(filesize($fullfileurl)/1024);
		$timeString = date("y-M-d H:m:s", $infotime);
		$piclistShow .= "<td><input type='checkbox' name='checkbox_pic$i' id='checkbox_pic$i' value='$picname'/></td>";
		$piclistShow .= "<td><a href='$pluginUrl$cfolder/$picname' target='blank'>$picname</a></td>";
		$piclistShow .= "<td>添加时间：$timeString, 图像大小：$infosize K</td>";
		$piclistShow .= "<td>&nbsp;</td>";
		$piclistShow .= "</tr>";
	}
	
	$plugin_return=<<<eot
<script lang=""javascript">
function doChecDisable(){
	var radio = document.getElementById("radio_folder1");
	var text = document.getElementById("new_foldername");
	if(radio.checked){
		text.disabled = false;
	}
	else{
		text.value = "";
		text.disabled = true;
	}
}
function check_all(){
	var ck = document.getElementById("checkbox_selectallpic");
	if(ck.checked){
		var i = 0;
		while(document.getElementById("checkbox_pic" + i)){
			document.getElementById("checkbox_pic" + i).checked = true;
			i ++;
		}
	}
	else{
		var i = 0;
		while(document.getElementById("checkbox_pic" + i)){
			document.getElementById("checkbox_pic" + i).checked = false;
			i ++;
		}
	}
}

function del_confirm(){
	var del = document.getElementById("radio_folder2");
	if(del.checked){
		if(confirm("您执行的是彻底删除文件夹操作，确定要删除吗?")){
			return true;
		} 
		else{
			return false;
		}
	}
	else{
		if(!document.getElementById("new_foldername").value){
			alert("更改后的名称不能为空");
			return false;
		}
		var myReg = /^[_a-zA-Z0-9-]+$/;
		if(!myReg.test(document.getElementById("new_foldername").value))
		{
			alert("文件夹名称不能包含中文等特殊字符！");
			return false; 
		}
	}
	return true;
}

function check_addfolder()
{
	var text = document.getElementById("new_folder");
	if(text.value == ""){
		alert("文件夹名称不能为空！");
		return false;
	}
	var myReg = /^[_a-zA-Z0-9-]+$/;
	if(!myReg.test(text.value))
	{
		alert("文件夹不能能包含中文等特殊字符！");
		return false; 
	}
	return true;
}

function check_addfile(){
	var file = document.getElementById("file");
	if(!file.value){
		alert("上传文件不能为空！");
		return false;
	}
	return true;
}
</script>
<link rel="stylesheet" type="text/css" href="../../admin/images/css/style.css">
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class='tablewidth'>
  <tr>
    <td colspan="4" class="sectstart">文件夹信息</td>
  </tr>
  <form name="editfolder" id="editfolder" action="setting-info.php?job=editfolder" method="POST">
  <tr class='sect'>
    <td width="30%">将文件夹 ： $folderComb</td>
    <td width="30%">更名为 <input name="new_foldername" type="text" id="new_foldername" size="15" maxlength="50" /> <input type="radio" name="radio_folder" id="radio_folder1" value="rename_radio" onclick="doChecDisable()" checked="checked"/></td>
	<td width="10%">删&nbsp;除？<input type="radio" name="radio_folder" id="radio_folder2" value="delete_radio" onclick="doChecDisable()"/></td>
	<td><input type="submit" name="Submit" class="btn" value="执行" onclick="return del_confirm()"/></td>
  </tr>
  </form>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <form name="addfolder" id="addfolder" action="setting-info.php?job=addfolder" method="POST" onsubmit="return check_addfolder()">
  <tr class='sect'>
    <td colspan="2">新建一个文件夹 ： <input name="new_folder" type="text" id="new_folder" size="15" maxlength="50" /></td>
    <td><input type="submit" name="Submit" class="btn" value="新建"/></td>
	<td>&nbsp;</td>
  </tr>
  </form>
</table>
<br/>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td colspan="4" class="sectstart">相册图像信息</td>
  </tr>
  <form name="form_select_folder" id="form_select_folder" action="setting-info.php?go=imagebox" method="POST">
  <tr class='sect'>
    <td>当前相册($picNum 张照片)：</td>
    <td>$picCfolderList <input type="submit" name="Submit" class="btn" value="相册跳转"/></td>
	<td></td>
	<td></td>
  </tr>
  </form>
  <tr class='sect'>
    <td width="15%"><input type="checkbox" name="checkbox_selectallpic" id="checkbox_selectallpic" value="checkbox_selectallpic" onclick="check_all()"/>全选</td>
    <td width="30%">图像</td>
	<td width="45%">信息</td>
	<td>&nbsp;</td>
  </tr>
  <form name="del_info" id="del_info" action="setting-info.php?job=delinfo" method="POST" onsubmit="">
  	$piclistShow
  <tr class='sect'>
    <td width="20%">所选项</td>
    <td width="30%"><select name="select_deltype"><option value="delete_info" selected="selected">删除信息</option><option value="delete_all">删除信息及文件</option></select></td>
	<td width="40%"><input type="submit" name="Submit" class="btn" value="确定"/></td>
	<td>&nbsp;</td>
  </tr>
  </form>
</table>

<br/>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td colspan="3" class="sectstart">上传新相片[文件名请不要用中文]</td>
  </tr>
  <form name="addfile" id="addfile" action="setting-info.php?job=addfile" enctype="multipart/form-data" method="POST" onsubmit="return check_addfile()">
  <tr class='sect'>
    <td width="50%">选择文件 <input type="file" name="file" id="file"/></td>
    <td width="30%">存放文件夹 $folderComb</td>
	<td width="20%"><input type="submit" name="Submit" class="btn" value="上传"/></td>
  </tr>
</table>
eot;

}

if($job == "editfolder"){
	$folder = $_POST["folder_select"];
	$newname = $_POST["new_foldername"];
	$fulldir = $pluginUrl.$folder;
	if(empty($newname)){
		//删除 
		$dirContent = scan_dir($fulldir);
		if(count($dirContent) == 3){
			unlink($fulldir."/".$picList);
		}
		$result = rmdir($fulldir);
		$operation = "删除";
 
		if($result != 0){
			$fileContent = file_get_contents($pluginUrl.$folderFile);
			//&anz_shootings=2&ordner=shooting1|shooting2
			$fileContentArray = explode("&", $fileContent);
			$folderNum = getValue($fileContentArray[1]);
			$folderNum --;
			$folderNumString = "&anz_shootings=".$folderNum;
			$folderArray = explode("|", getValue($fileContentArray[2]));
			$folderString = "&ordner=";
			$folderStringValue = "";
			for($i = 0; $i < count($folderArray); $i ++){
				if($folderArray[$i] != $folder){
					$folderStringValue .= "|".$folderArray[$i];
				}
			}
			$folderStringValue = substr($folderStringValue, 1);
			writetofile($pluginUrl.$folderFile, $folderNumString.$folderString.$folderStringValue);
			$infomation = "文件夹删除成功";
		}
		else{
			$infomation = "文件夹删除失败，请确认文件夹权限正确并且为空";
		}
	}
	else{
		//更名
		$result = rename($fulldir, $pluginUrl.$newname);
		$operation = "更名";
		if($result){
			$fileContent = file_get_contents($pluginUrl.$folderFile);
			//&anz_shootings=2&ordner=shooting1|shooting2
			$foldercontent = str_replace($folder, $newname, $fileContent);
			writetofile($pluginUrl.$folderFile, $foldercontent);
			$infomation = "文件夹更名成功";
		}
		else {
			$infomation = "文件夹更名失败";
		}
	}
	$plugin_return=<<<eot
<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td class="sectstart">$operation 文件夹$folder</td>
  </tr>
  <td align="center" height="100"><a href="setting-info.php?go=imagebox">$infomation ，点击这边返回</a></td>
</table>
eot;
}
if($job == "addfolder"){
	$newfolder = $_POST["new_folder"];
	$infomation = createdir($pluginUrl.$newfolder);
	if($infomation == 1){
		$fileContent = file_get_contents($pluginUrl.$folderFile);
		$fileContentArray = explode("&", $fileContent);
		$folderNum = getValue($fileContentArray[1]);
		$folderNum ++;
		//&anz_shootings=2&ordner=shooting1|shooting2
		$folderNumString .= "anz_shootings=".$folderNum;
		$fileContent = str_replace($fileContentArray[1], $folderNumString, $fileContent);
		$fileContent .= "|".$newfolder;
		writetofile($pluginUrl.$folderFile, $fileContent);
		$defaultString = "&anz_bilder=0&fotos=";
		writetofile($pluginUrl.$newfolder."/".$picList, $defaultString);
		$infomation = "建立文件夹成功";
	}
	elseif ($infomation == 2){
		$infomation = "建立文件夹失败";
	}
	elseif ($infomation == 9){
		$infomation = "这个文件夹已经存在";
	}
	$plugin_return=<<<eot
<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td class="sectstart">添加文件夹：$folderNum</td>
  </tr>
  <td align="center" height="100"><a href="setting-info.php?go=imagebox">$infomation ，点击这边返回</a></td>
</table>
eot;
}
if($job == "addfile"){
	$file=$_FILES['file']['tmp_name'];
	$file_name=$_FILES['file']['name'];
	$file_type = $_FILES["file"]['type'];
	
	$storeFolder = $pluginUrl.$_POST["folder_select"];
	if($file_type != "image/pjpeg" && $file_type != "image/jpeg"){
		$infomation = "上传文件类型".$file_type."不是jpg或jpg的格式不标准";
	}
	else{
		$file_name = getNewName($storeFolder."/", $file_name);
		$storeFilename = $storeFolder."/".$file_name;
		
		if(! @move_uploaded_file($file, $storeFolder."/".$file_name)){
			$infomation = "上传文件失败";
		}
		else{
			addPicInfo($pluginUrl.$_POST["folder_select"], $file_name);
			$infomation = "上传文件成功";
		}
	}
	$plugin_return=<<<eot
<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td class="sectstart">上传图片文件$file_type</td>
  </tr>
  <td align="center" height="100"><a href="setting-info.php?go=imagebox">$infomation ，点击这边返回</a></td>
</table>
eot;
}
if($job == "delinfo"){
	$folder = $_POST["current_folder"];
	$number = $_POST["pic_number"];
	$deltype = $_POST["select_deltype"];
	$content = file_get_contents($pluginUrl.$folder."/".$picList);
	//$infomation .= $pluginUrl.$folder."/".$picList;
	
	if($deltype == "delete_info"){
		for ($i = 0; $i < $number; $i ++){
			$filename = $_POST["checkbox_pic".$i];
			if($filename){
				$content = delPicInfo($content, $filename);
			}
		}
	}
	else {
		for ($i = 0; $i < $number; $i ++){
			$filename = $_POST["checkbox_pic".$i];
			if($filename){
				unlink($pluginUrl.$folder."/".$filename);
				$content = delPicInfo($content, $filename);
			}
		}
	}
	writetofile($pluginUrl.$folder."/".$picList, $content);
	
	$plugin_return=<<<eot
<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0" class='tablewidth'>
  <tr>
    <td class="sectstart">删除图片信息$folder</td>
  </tr>
  <td align="center" height="100"><a href="setting-info.php?go=imagebox">删除图片信息成功，点击这边返回</a></td>
</table>
eot;
}

echo $plugin_return;

//function
function getValue($string){
	$stringArray = explode("=", $string);
	return $stringArray[1];
}

function createPicstxt($folderUrl){
	if(is_dir($folderUrl)){
		$content = "&anz_bilder=0";
		writetofile($folderUrl, $content);
	}
}

function createdir($dirUrl){
	if(file_exists($dirUrl)){
		return 9;
	}
	else{
		$success = mkdir($dirUrl, 0777);
 
		chmod($dirUrl, 0777);
		
		if($success){
			return 1;
		}
		else{
			return 2;
		}
	}
}

function createFolderList($folders, $selected){
	$list = "<select name='folder_select'>";
	for ($i = 0; $i < count($folders); $i ++){
		$folder = $folders[$i];
		if($folder == $selected){
			$list .= "<option value='$folder' selected>$folder</option>";
		}
		else{
			$list .= "<option value='$folder'>$folder</option>";
		}
	}
	$list .= "</select>";
	return $list;
}

function getNewName($folder, $fileName){
	$tempArray = explode(".", $fileName);
	$fileType = $tempArray[count($tempArray) - 1];
	$fileNameNoType = "";
	for($i = 0; $i < count($tempArray) - 1; $i ++){
		$fileNameNoType .= $tempArray[$i];
		if($i != count($tempArray) - 2){
			$fileNameNoType .= ".";
		}
	}
	if(file_exists($folder.$fileName)){
		$seq = 1;
		while(file_exists($folder.$fileName)){
			$fileName = $fileNameNoType."_".$seq.".".$fileType;
			$seq ++;
		}
	}
	return $fileName;
}

function addPicInfo($folder, $filename){
	//&anz_bilder=5&fotos=bild6.jpg|bild7.jpg|bild8.jpg|bild9.jpg|bild10.jpg
	global $pluginUrl,$picList;
	$fileContent = file_get_contents($folder."/".$picList);
	$fileContentArray = explode("&", $fileContent);
	$picNum = getValue($fileContentArray[1]);
	$picNum ++;
	$picNumString = "&anz_bilder=".$picNum;
	if($picNum == 1){
		$picListString = $fileContentArray[2].$filename;
	}
	else{
		$picListString = $fileContentArray[2]."|".$filename;
	}
	writetofile($folder."/".$picList, $picNumString."&".$picListString);
}

function delPicInfo($fullinfo, $filename){
	$tempArray = explode("&", $fullinfo);
	$number = getValue($tempArray[1]);
	$number --;
	$numberString = "&anz_bilder=".$number;
	$files = explode("|", getValue($tempArray[2]));
	$newString = "";
	for ($i = 0; $i < count($files); $i ++){
		if($files[$i] != $filename){
			$newString .= "|".$files[$i];
		}
	}
	$newString = substr($newString, 1);
	$newString = "&fotos=".$newString;
	return $numberString.$newString;
}

function scan_dir($dir){
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh))) {
		$files[] = $filename;
	}
	return $files;
}

function writetofile ($filename, $data) { //File Writing
	$filenum=@fopen($filename,"w");
	if (!$filenum) {
		return false;
	}
	flock($filenum,LOCK_EX);
	$file_data=fwrite($filenum,$data);
	fclose($filenum);
	return true;
}

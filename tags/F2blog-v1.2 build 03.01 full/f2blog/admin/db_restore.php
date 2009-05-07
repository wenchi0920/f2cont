<?php 
@set_time_limit(0);
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strRestore;

//保存参数
$action=$_GET['action'];

//数据目录
$data_path="../backup";

function restore_file($datefile){//备用
	global $strDataRestoreNoFile,$DMC,$strDataRestoreBad,$strDataRestoreSuccess,$data_path,$_SESSION;
	$fname = $data_path."/".$datefile;
	if (strpos($fname,".zip")>0){
		$sqls=gzfile($fname);
	}else{
		$sqls=file($fname);
	}
	//print_r($sqls);
	$query="";
	$lastchar=false;
	foreach($sqls as $sql){
		$sql=trim($sql);
		$sql=str_replace("\r","",$sql);
		$sql=str_replace("\n","",$sql);

		if (substr($sql,0,2)!="--" && $sql!=""){
			if ($lastchar==true && preg_match("/^[DROP TABLE|CREATE TABLE|INSERT INTO]/",$sql)){
				if (mysql_get_server_info()<4.1){
					$query=str_replace("ENGINE=MyISAM DEFAULT CHARSET=utf8","TYPE=MyISAM",$query);
				}else{
					$query=str_replace("TYPE=MyISAM","ENGINE=MyISAM DEFAULT CHARSET=utf8",$query);
				}
				$DMC->query($query,"T");
				if ($DMC->error()){
					$_SESSION['array_errorsql'][$DMC->error()]=$query;
				}
				$query="";
				$lastchar=false;
			}
			$query.=$sql;
			if (substr($sql,strlen($sql)-1)==";") $lastchar=true;
		}
	}
	if ($query!="" && $lastchar==true){
		//运行最后一个query;
		$DMC->query($query,"T");
		if ($DMC->error()){
			$_SESSION['array_errorsql'][$DMC->error()]=$query;
		}
	}

	//返回结果
	if (is_array($_SESSION['array_errorsql'])){
		$ActionMessage="$datefile ==> ".$strDataRestoreBad;
	}else{
		$ActionMessage="$datefile ==> ".$strDataRestoreSuccess;
	}
	return $ActionMessage;
}

function restore_file1($datefile) {
	global $strDataRestoreNoFile,$DMC,$strDataRestoreBad,$strDataRestoreSuccess,$data_path,$_SESSION;
	$filename = $data_path."/".$datefile;

	if (!file_exists($filename)) {
        $ActionMessage="$strDataRestoreNoFile";
    } else{
        $filesize       = filesize($filename);
        $file_position  = isset($HTTP_GET_VARS['pos']) ? $HTTP_GET_VARS['pos'] : 0;
        $errors         = isset($HTTP_GET_VARS['ignore_errors']) ? 0 : 1;

        $buffer = '';
        $inside_quote = 0;
        $quote_inside = '';
        $started_query = 0;

        $data_buffer = '';
        $last_char = "\n";

		if (strpos($filename,".zip")>0){
		   $fp = gzopen($filename, 'rb');
		   gzseek($fp,$file_position);

			while ((!gzeof($fp) || strlen($buffer))) {
				do {
					// Deals with the length of the buffer
					if (!strlen($buffer)) {
						$buffer .= gzread ($fp,1024);
					}

					// Fiddle around with the buffers
					$current_char = $buffer[0];
					$buffer = substr($buffer, 1);

					if ($started_query) {
						$data_buffer .= $current_char;
					} elseif (preg_match("/[A-Za-z]/i",$current_char) && $last_char == "\n") {
						$started_query = 1;
						$data_buffer = $current_char;
					}else  {
						$last_char = $current_char;
					}
				} while (!$started_query && (!gzeof($fp) || strlen($buffer)));

				if ($inside_quote && $current_char == $quote_inside && $last_char != '\\') {
					$inside_quote = 0;
				} 
				elseif ($current_char == '\\' && $last_char == '\\'){
					$current_char = '';	
				} 
				elseif (!$inside_quote && ($current_char == '"' || $current_char == '`' || $current_char == '\'')){
					$inside_quote = 1;
					$quote_inside = $current_char;
				} elseif (!$inside_quote && $current_char == ';') {
					if (strpos($data_buffer,"TABLE IF EXISTS")>0){
						$name=substr($data_buffer,21,strlen($data_buffer)-22);
					}
					if (mysql_get_server_info()<4.1){
						$data_buffer=str_replace("ENGINE=MyISAM DEFAULT CHARSET=utf8","TYPE=MyISAM",$data_buffer);
					}else{
						$data_buffer=str_replace("TYPE=MyISAM","ENGINE=MyISAM DEFAULT CHARSET=utf8",$data_buffer);
					}

					$DMC->query($data_buffer,"T");
					if ($DMC->error()){
						$_SESSION['array_errorsql'][$DMC->error()]=$data_buffer;
					}

					$data_buffer = '';
					$last_char = "\n";
					$started_query = 0;
				}
				$last_char = $current_char;
			}
			gzclose($fp);
		} else {
			$fp = fopen($filename,'rb');
			fseek($fp,$file_position);

			while ((!feof($fp) || strlen($buffer))) {
				do {
					// Deals with the length of the buffer
					if (!strlen($buffer)) {
						$buffer .= fread ($fp,1024);
					}

					// Fiddle around with the buffers
					$current_char = $buffer[0];
					$buffer = substr($buffer, 1);

					if ($started_query) {
						$data_buffer .= $current_char;
					} elseif (preg_match("/[A-Za-z]/i",$current_char) && $last_char == "\n") {
						$started_query = 1;
						$data_buffer = $current_char;
					}else  {
						$last_char = $current_char;
					}
				} while (!$started_query && (!feof($fp) || strlen($buffer)));

				if ($inside_quote && $current_char == $quote_inside && $last_char != '\\') {
					$inside_quote = 0;
				} 
				elseif ($current_char == '\\' && $last_char == '\\'){
					$current_char = '';	
				} 
				elseif (!$inside_quote && ($current_char == '"' || $current_char == '`' || $current_char == '\'')){
					$inside_quote = 1;
					$quote_inside = $current_char;
				} elseif (!$inside_quote && $current_char == ';') {
					if (strpos($data_buffer,"TABLE IF EXISTS")>0){
						$name=substr($data_buffer,21,strlen($data_buffer)-22);
					}
					$data_buffer=preg_replace('/AUTO_INCREMENT=([0-9]+?) /is', '',$data_buffer);
					if (mysql_get_server_info()<4.1){
						$data_buffer=str_replace("ENGINE=MyISAM DEFAULT CHARSET=utf8","TYPE=MyISAM",$data_buffer);
					}else{
						$data_buffer=str_replace("TYPE=MyISAM","ENGINE=MyISAM DEFAULT CHARSET=utf8",$data_buffer);
					}

					$DMC->query($data_buffer,"T");
					if ($DMC->error()){
						$_SESSION['array_errorsql'][$DMC->error()]=$data_buffer;
					}

					$data_buffer = '';
					$last_char = "\n";
					$started_query = 0;
				}
				$last_char = $current_char;
			}

			fclose($fp);
		}
	}
	
	if (is_array($_SESSION['array_errorsql'])){
		$ActionMessage="$datefile ==> ".$strDataRestoreBad;
	}else{
		$ActionMessage="$datefile ==> ".$strDataRestoreSuccess;
	}
	return $ActionMessage;
}

//恢复后，重新生成cache文件。
if (!empty($_GET['update']) && $_GET['update']=="ok"){
	reAllCache();	
	$ActionMessage=urldecode($_GET['msg']);
	$action="ok";
}

if ($action=="save"){
	//恢复第1卷
	list($filename,$filecount)=explode("|",$_REQUEST['restorefile']);

	//有分卷
	if ($filecount>1){	
		$curr_page=(!empty($_GET['curr_page']))?$_GET['curr_page']:1;
		$next_page=$curr_page+1;
		$curr_source=str_replace(".sql","_v{$curr_page}.sql",$filename);
		$next_source=str_replace(".sql","_v{$next_page}.sql",$filename);
		//echo $curr_source."==".$next_source;
		//恢复数据
		$ActionMessage=restore_file($curr_source);
			
		//是否有下一卷
		if (file_exists($data_path."/".$next_source)) {
			header("Content-Type: text/html; charset=utf-8");
			$url="db_restore.php?action=save&amp;curr_page=$next_page&amp;restorefile={$filename}|{$filecount}";
			$content="{$strDataRestoreAlert1}<font color=\"red\"> ".$next_page." / ".$filecount."</font>".$strDataRestoreAlert2;
			echo NavigatorNextURL($url,$content);
			exit;
		}
	}else{
		//恢复单个文件
		$ActionMessage=restore_file($filename);
	}

	if (!empty($ActionMessage)){
		//如果是F2blog v1.0数据，则进行升级数据，v1.0的categories没有cateIcons这个字段。
		$DMC->query("select cateIcons from {$DBPrefix}categories limit 0,1","T");
		if ($DMC->error()){
			include("f211to12.inc.php");
		}
		//如果是F2blog v1.1 beta 11.11，则增加setting设定值。
		if (!$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='treeCategory'"))){
			//增加属性项目
			$arr_setting['applylink']="1";
			$arr_setting['disSearch']="0";
			$arr_setting['disTop']="0";
			$arr_setting['gbface']="1";
			$arr_setting['disAttach']="1";
			$arr_setting['forum_user']="";
			$arr_setting['loginStatus']="1";
			$arr_setting['disTags']='1';
			$arr_setting['fastEditor']='0';
			$arr_setting['allowTrackback']='1';
			$arr_setting['allowComment']='1';
			$arr_setting['showKeyword']='0';
			$arr_setting['autoUrl']='0';
			$arr_setting['showPrint']='1';
			$arr_setting['showDown']='1';
			$arr_setting['showMail']='0';
			$arr_setting['linkshow']='0';
			$arr_setting['showUBB']='0';
			$arr_setting['linklogo']='images/logo.gif';
			$arr_setting['adminstyle']='default';
			$arr_setting['footcode']='';
			$arr_setting['linkmarquee']='0';
			//2007-02-01
			$arr_setting['currFormatDate']='Y-m-d H:i';
			$arr_setting['listFormatDate']='m-d';
			$arr_setting['showAlertStyle']='0';
			$arr_setting['weatherStatus']='1';
			$arr_setting['treeCategory']='0';

			foreach($arr_setting as $key=>$value){
				if (!$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='$key'"))){
					$DMC->query("insert into ".$DBPrefix."setting values('$key','$value','0')");
				}
			}

			//新增粘贴插件
			if (!$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='editPluginName' and settValue like '%paste%'"))){
				$DMC->query("update ".$DBPrefix."setting set settValue=concat('paste,',settValue) where settName='editPluginName'");
				$DMC->query("update ".$DBPrefix."setting set settValue=replace(settValue,',justifyleft,justifycenter',',pastetext,pasteword,separator,justifyleft,justifycenter,justifyright') where settName='editPluginButton1'");
			}
		}

		$DMC->query("select homepage from {$DBPrefix}comments limit 0,1","T");
		if ($DMC->error()){
			//更改表结构(2007-02-01）
			$DMC->query("ALTER TABLE `{$DBPrefix}setting` CHANGE `settValue` `settValue` text NOT NULL");
			$DMC->query("ALTER TABLE `{$DBPrefix}comments` ADD `homepage` varchar(100) NOT NULL default ''");
			$DMC->query("ALTER TABLE `{$DBPrefix}comments` ADD `email` varchar(100) NOT NULL default ''");
			$DMC->query("ALTER TABLE `{$DBPrefix}comments` ADD `face` varchar(30) NOT NULL default ''");
		}

		//清除属性(2007-02-01）
		if ($DMC->fetchArray($DMC->query("select * from ".$DBPrefix."setting where settName='disTop'"))){
			$DMC->query("delete from {$DBPrefix}setting where settName='disTop' or settName='calendarmonth'");
		}

		//重新生成Cache
		settings_recount();
		settings_recache();	
		$settingInfo['stype'] = ($settingInfo['rewrite']>0) ? ".html" : "";
		modules_recache();

		header("Location: db_restore.php?update=ok&msg=".urlencode($ActionMessage));
	}
}

if($action=="delete") {
	list($filename,$filecount)=explode("|",$_POST['restorefile']);	
	//有分卷
	if ($filecount>1){
		for($i=1;$i<=$filecount;$i++){
			$delname=str_replace(".sql","_v".$i.".sql",$filename);
			if(file_exists($data_path."/".$delname)) @unlink($data_path."/".$delname);
		}
	}else{
		if(file_exists($data_path."/".$filename)) @unlink($data_path."/".$filename);
	}

	$ActionMessage.="$strDeleteSuccess";
	$action="";
}

if ($action=="downlist"){
	//调整类别顺序
	$filename=$_GET['filename'];
	$filecount=$_GET['filecount'];

	$title="$strDownFile - $filename";
	include("db_restore_downlist.inc.php");	
}else if ($action=="" or $action=="ok"){
	//查找和浏览
	$title="$strDataRestoreTitle";

	//读取备份文件
	$f2data_file=array();
	$file_count=array();
	$file_size=array();
	$file_time=array();
	$data_path="../backup";
	$handle=opendir($data_path);
	while (false !== ($file = readdir($handle))) {	
		if (strpos($file,".sql")>0){
			$file_type=substr($file,strpos($file,".sql"));
			//分卷
			if (strpos($file,"_v")>0){
				$filename=substr($file,0,strpos($file,"_v")).$file_type;			
				if (empty($file_count[$filename])) $file_count[$filename]="";
				if (empty($file_size[$filename])) $file_size[$filename]=0;
				$file_count[$filename]++;
				$file_size[$filename]=$file_size[$filename]+filesize($data_path."/".$file);
				$file_time[$filename]=filemtime($data_path."/".$file);
				if (!in_array($filename,$f2data_file)) $f2data_file[]=$filename;
			}else{
				$f2data_file[]=$file;
				$file_count[$file]=1;						
				$file_size[$file]=filesize($data_path."/".$file);
				$file_time[$file]=filemtime($data_path."/".$file);
			}
		}			
	}
	closedir($handle);

	include("db_restore_list.inc.php");
}
?>
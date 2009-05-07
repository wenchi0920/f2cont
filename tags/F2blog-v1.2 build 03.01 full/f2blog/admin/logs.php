<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("logs".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();

if ($_SESSION['rights']!=""){
	if ($_SESSION['rights']!="admin" and $_SESSION['rights']!="editor" and $_SESSION['rights']!="author") {
		header("Location: ../index.php");
		exit;
	}
}

$parentM=2;
$mtitle=$strLogBrowse;

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=encode($_REQUEST['seekname']);
$seekcate=empty($_REQUEST['seekcate'])?"":$_REQUEST['seekcate'];
$seektags=empty($_REQUEST['seektags'])?"":$_REQUEST['seektags'];
$seektype=!isset($_REQUEST['seektype'])?"":$_REQUEST['seektype'];
$mark_id=$_GET['mark_id'];
$edittype=empty($_REQUEST['edittype'])?"":$_REQUEST['edittype'];

//前台管理行为
if ($action=="manage"){
	$manage=empty($_GET['manage'])?"":$_GET['manage'];
	
	//编辑
	if($manage=="edit" && $mark_id!=""){
		$edittype="front";
		$action="edit";
	}
	
	//删除
	if($manage=="delete" && $mark_id!=""){
		$action="delete";
	}

	//公开1，草稿0，隐私3
	if(($manage=="publish" or $manage=="draft" or $manage=="private") && $mark_id!=""){
		if ($manage=="publish"){
			$value=1;
			$type="adding";
		}elseif($manage=="private"){
			$value=3;
			$type="minus";
		}else{
			$value=0;
			$type="minus";
			$action="";
		}

		$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
		$cateId=$dataInfo['cateId'];
		$name="";
		if ($dataInfo['tags']!="") {
			$tags=explode(";",$dataInfo['tags']);
			for ($j=0;$j<count($tags);$j++) {
				$name.=" or name='".$tags[$j]."'";
			}
			$name=($name=="")?"":substr($name,4);
		}
		
		if (($dataInfo['saveType']==1 && $value!=1) || ($dataInfo['saveType']!=1 && $value==1)){
			update_cateCount($cateId,$type,1);
		}

		if ($name!="") {
			update_num($DBPrefix."tags","logNums"," $name",$type,1);
		}

		$sql="update {$DBPrefix}logs set saveType='$value' where id='$mark_id'";
		$DMC->query($sql);

		settings_recount("logs");
		//更新Cache
		hottags_recache();
		categories_recache();
		settings_recache();
		recentLogs_recache();
		archives_recache();
		calendar_recache();
		logsTitle_recache();
		logs_sidebar_recache($arrSideModule);
	}

	//取消置顶0,展开置顶1,折叠置顶2
	if(($manage=="notop" or $manage=="topopen" or $manage=="topclose") and $mark_id!=""){
		if ($manage=="notop"){
			$value=0;
		}elseif($manage=="topopen"){
			$value=1;
		}else{
			$value=2;
		}

		$sql="update {$DBPrefix}logs set isTop='$value' where id='$mark_id'";
		$DMC->query($sql);
	}

	//允许评论
	if($manage=="ctshow" and $mark_id!=""){
		$sql="update {$DBPrefix}logs set isComment='1' where id='$mark_id'";
		$DMC->query($sql);
	}

	//禁止评论
	if($manage=="cthidden" and $mark_id!=""){
		$sql="update {$DBPrefix}logs set isComment='0' where id='$mark_id'";
		$DMC->query($sql);
	}

	//允许引用
	if($manage=="tbshow" and $mark_id!=""){
		$sql="update {$DBPrefix}logs set isTrackback='1' where id='$mark_id'";
		$DMC->query($sql);
	}

	//禁止引用
	if($manage=="tbhidden" and $mark_id!=""){
		$sql="update {$DBPrefix}logs set isTrackback='0' where id='$mark_id'";
		$DMC->query($sql);
	}

	//清空评论
	if($manage=="ctempty" && $mark_id!=""){
		$sql="delete from {$DBPrefix}comments where logId='$mark_id'";
		$DMC->query($sql);
		$sql="update {$DBPrefix}logs set commNums=0 where id='$mark_id'";
		$DMC->query($sql);

		//更新Cache
		settings_recount("comments");
		settings_recache();
		recentComments_recache();
		logs_sidebar_recache($arrSideModule);
	}

	//清空引用
	if($manage=="tbempty" && $mark_id!=""){
		$sql="delete from {$DBPrefix}trackbacks where logId='$mark_id'";
		$DMC->query($sql);
		$sql="update {$DBPrefix}logs set quoteNums=0 where id='$mark_id'";
		$DMC->query($sql);

		//更新Cache
		settings_recount("trackbacks");
		settings_recache();
	}
	
	//页面转向
	if($manage!="edit" && $manage!="delete" && $manage!="draft"){
		if ($manage=="notop" or $manage=="topopen" or $manage=="topclose") {
			header("Location: ../index.php"); 
		} else {
			if ($settingInfo['rewrite']==0) $gourl="../index.php?load=read&id=$mark_id";
			if ($settingInfo['rewrite']==1) $gourl="../rewrite.php/read-$mark_id";
			if ($settingInfo['rewrite']==2) $gourl="../read-$mark_id";
			header("Location: $gourl".$settingInfo['stype']); 
		}
	}

}


//保存数据
if ($action=="save"){
	$check_info=1;
	
	//取得Form的Value
	$cateId=trim($_POST['cateId']);
	$oldCateId=trim($_POST['oldCateId']);
	$logTitle=trim($_POST['logTitle']);
	$logContent=trim($_POST['logContent']);
	$quoteUrl=!empty($_POST['quoteUrl'])?$_POST['quoteUrl']:"";
	$pubTimeType=trim($_POST['pubTimeType']);
	$pubTime=trim($_POST['pubTime']);
	$isComment=!empty($_POST['isComment'])?intval($_POST['isComment']):0;
	$isTrackback=!empty($_POST['isTrackback'])?intval($_POST['isTrackback']):0;
	$isTop=!empty($_POST['isTop'])?intval($_POST['isTop']):0;
	$weather=trim($_POST['weather']);
	$saveType=intval($_POST['saveType']);
	$tags=trim($_POST['tags']);
	$oldTags=trim($_POST['oldTags']);
	$autoSplit=trim($_POST['autoSplit']);
	$logsediter=$_POST['logsediter'];

	if ($_POST['addpassword']!="" && strlen($_POST['addpassword'])!=32){
		$addpassword=md5(encode($_POST['addpassword']));
	}else{
		$addpassword=encode($_POST['addpassword']);
	}
	
	//检测输入内容为空
	if (empty($logTitle) or empty($logContent) or empty($cateId)){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		if (!is_numeric($cateId)){//新增分类
			$cateId=encode($cateId);
			$rsexits=getFieldValue($DBPrefix."categories","name='".$cateId."' and parent='0'","id");
			if ($rsexits!=""){
				$cateId=$rsexits;				
			}else{
				$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden,cateIcons) VALUES ('0','".$cateId."','0','".$cateId."','','0','0','1')";
				$DMC->query($sql);
				$cateId=$DMC->insertId();
			}
		}

		//$logContent=encode($logContent);
		$logTitle=encode($logTitle);
		$postTime=($pubTimeType=="now")?time():str_format_time($pubTime);

		//已存在的Tags
		$exist_tags=tag_list("A");
		
		$logs_tags=array();
		$oldlogs_tags=array();

		if ($tags!="" || $oldTags!="") {
			$tags_array=explode(';', $tags);
			$tags_array_all=array_unique($tags_array);
			$tags="";
			
			foreach($tags_array_all as $value){
				if ($value!=""){
					$tags.=($tags=="")?encode(stripslashes($value)):";".encode(stripslashes($value));
					$logs_tags[]=encode(stripslashes($value));
				}
			}
			
			//处理旧标签
			$oldTags_array=explode(';', $oldTags);
			$oldTags_array_all=array_unique($oldTags_array);

			foreach($oldTags_array_all as $value){
				if ($value!=""){
					$oldTags.=($oldTags=="")?encode(stripslashes($value)):";".encode(stripslashes($value));
					$oldlogs_tags[]=encode(stripslashes($value));
				}
			}
		}

		//如果是ubb编辑器
		if ($logsediter=="ubb"){
			if (empty($_POST['allowhtml'])){
				$logContent=ubblogencode($logContent);				
			}else{
				$logContent=str_replace("\r","",$logContent);
				$logContent=str_replace("\n","",$logContent);
				//$logContent=mysql_escape_string($logContent);
				$logContent=str_replace("'","&#39;",$logContent);
			}
		}else{
			//$logContent=mysql_escape_string($logContent);
			$logContent=str_replace("'","&#39;",$logContent);
		}

		//转换UBB标签
		if (strpos(";".$logContent,"[hideBegin]")>0) $logContent=preg_replace("/\[hideBegin\](.+?)\[hideEnd\]/is","<!--hideBegin-->\\1<!--hideEnd-->",$logContent);
		if (strpos(";".$logContent,"[fileBegin]")>0) $logContent=preg_replace("/\[fileBegin\](.+?)\[fileEnd\]/is","<!--fileBegin-->\\1<!--fileEnd-->",$logContent);		
		if (strpos(";".$logContent,"[flvBegin]")>0) $logContent=preg_replace("/\[flvBegin\](.+?)\[flvEnd\]/is","<!--flvBegin-->\\1<!--flvEnd-->",$logContent);		
		if (strpos(";".$logContent,"[musicBegin]")>0) $logContent=preg_replace("/\[musicBegin\](.+?)\[musicEnd\]/is","<!--musicBegin-->\\1<!--musicEnd-->",$logContent);	
		if (strpos(";".$logContent,"[mfileBegin]")>0) $logContent=preg_replace("/\[mfileBegin\](.+?)\[mfileEnd\]/is","<!--mfileBegin-->\\1<!--mfileEnd-->",$logContent);
		if (strpos(";".$logContent,"[galleryBegin]")>0) $logContent=preg_replace("/\[galleryBegin\](.+?)\[galleryEnd\]/is","<!--galleryBegin-->\\1<!--galleryEnd-->",$logContent);
		if (strpos($logContent,"[more]")>0) $logContent=str_replace("[more]","<!--more-->",$logContent);
		if (strpos($logContent,"[nextpage]")>0) $logContent=str_replace("[nextpage]","<!--nextpage-->",$logContent);

		//如果日志包含了特殊标签，则不自载截取
		if (strpos(";".$logContent,"<!--more-->")>0) $autoSplit=0;
		if (strpos(";".$logContent,"<!--nextpage-->")>0) $autoSplit=0;
		if (strpos(";".$logContent,"<!--hideBegin-->")>0) $autoSplit=0;
		if (strpos(";".$logContent,"<!--galleryBegin-->")>0) $autoSplit=0;
		if (strpos(";".$logContent,"<!--fileBegin-->")>0) $autoSplit=0;
		if (strpos(";".$logContent,"<!--mfileBegin-->")>0) $autoSplit=0;

		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."logs","logTitle='$logTitle' and cateId='$cateId' and saveType!=2","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$edit_sql=($pubTimeType=="now")?"":",postTime='$postTime'";
				$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',weather='$weather',saveType='$saveType',tags='$tags',logsediter='$logsediter',password='$addpassword',autoSplit='$autoSplit'$edit_sql where id='$mark_id'";
				$DMC->query($sql);

				$ActionMessage=$strSaveSuccess;
				$action="";
				$last_id=$mark_id;
			}
		}else{
			$rsexits=getFieldValue($DBPrefix."logs","logTitle='$logTitle' and cateId='$cateId' and saveType!=2","id");
			if ($rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$author=$_SESSION['username'];

				$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter,autoSplit) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$weather','$saveType','$tags','$addpassword','$logsediter','$autoSplit')";
				$DMC->query($sql);
				$last_id=$DMC->insertId();
				$mark_id=$last_id;

				//Send Trackback
				if ($quoteUrl!="") {
					header("Content-Type: text/html; charset=utf-8");
					$pingurl=explode(";",$quoteUrl);
					$logurl=$settingInfo['blogUrl']."index.php?load=read&id=$mark_id";
					foreach ($pingurl as $durl) {
						$result=send_trackback($durl, $logTitle, $logContent,$logurl);
					}
				}

				$ActionMessage=$strSaveSuccess;
				$action="";
			}
		}
		
		//增加，编辑成功，Tags
		if ($tags!=$oldTags) {
			if (count($exist_tags)>0 && $exist_tags[0]!=""){
				$newtags=array_diff($logs_tags, $exist_tags);
				$newtags=array_values($newtags); 
				$addtags=array_diff($logs_tags, $oldlogs_tags);

				if (count($addtags)>0){
					$addtags_query="'".implode("', '", $addtags)."'";
					$modify_sql="UPDATE ".$DBPrefix."tags set logNums=logNums+1 WHERE name in(".$addtags_query.")";
					$DMC->query($modify_sql);
				}
				$subtags=array_diff($oldlogs_tags,$logs_tags);
				if (count($subtags)>0){
					$subtags_query="'".implode("', '", $subtags)."'";
					$modify_sql="UPDATE ".$DBPrefix."tags set logNums=logNums-1 WHERE name in(".$subtags_query.")";
					$DMC->query($modify_sql);
				}
			}else{
				$newtags=$logs_tags;
			}			

			for ($m=0; $m<count($newtags); $m++) {
				if ($newtags[$m]!="") {
					$itags=$newtags[$m];
					if (check_record($DBPrefix."tags"," name='$itags'")) {
						$add_sql="INSERT INTO ".$DBPrefix."tags (name,logNums) VALUES ('".$itags."',1)";
						$DMC->query($add_sql);						
					}
				}
			}
			settings_recount("tags");
		}

		if($action=="") {
			//增加，编辑成功，类别
			if ($cateId!=$oldCateId and $saveType==1) {
				if ($oldCateId=="") { //新增日志时
					update_cateCount($cateId,"adding",1);
					settings_recount("logs");
				} else {
					update_cateCount($oldCateId,"minus",1); //减少原来类别数量
					update_cateCount($cateId,"adding",1);//增加新类别数量
				}
			}
	
			//更新未链接的附件
			if (!empty($_COOKIE['f2_attachments'])){
				$arr_attachid=explode("|",$_COOKIE['f2_attachments']);
				$arr_attachid=array_unique($arr_attachid);
				foreach($arr_attachid as $value){
					$modify_sql="UPDATE ".$DBPrefix."attachments set logId='$last_id' WHERE id='$value'";
					$DMC->query($modify_sql);
				}
				setcookie('f2_attachments','');
			}

			//更新Cache
			hottags_recache();
			categories_recache();
			recentLogs_recache();
			archives_recache();
			calendar_recache();
			attachments_recache();
			logsTitle_recache();
			settings_recache();
			logs_sidebar_recache($arrSideModule);

			//Clear unsaved draft now
			if (file_exists("../cache/cache_autosave.php")){
				@unlink("../cache/cache_autosave.php");
			}
			
			//生成静态页面
			if ($settingInfo['isHtmlPage']==1){
				echo "<script language='javascript'>"; 
				echo "window.location='create_html.inc.php?edittype=$edittype&arrayhtmlid=$last_id';"; 
				echo "</script>";
			}else{
				if ($edittype=="front") {
					if ($settingInfo['rewrite']==0) $gourl="../index.php?load=read&id=$mark_id";
					if ($settingInfo['rewrite']==1) $gourl="../rewrite.php/read-$mark_id";
					if ($settingInfo['rewrite']==2) $gourl="../read-$mark_id";
					header("Location: $gourl".$settingInfo['stype']); 
				}
			}
		}
	} else {
		$action="add";
	}
}

//单条删除
if($action=="delete"){
	$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
	$cateId=$dataInfo['cateId'];
	$name="";
	if ($dataInfo['tags']!="") {
		$tags=explode(";",$dataInfo['tags']);
		for ($i=0;$i<count($tags);$i++) {
			$name.=" or name='".$tags[$i]."'";
		}
		$name=($name=="")?"":substr($name,4);
	}

	update_cateCount($cateId,"minus",1);
	if ($name!="") {
		update_num($DBPrefix."tags","logNums"," $name","minus",1);
	}

	$sql="delete from ".$DBPrefix."logs where id='$mark_id'";
	$DMC->query($sql);
	
	//删除关联的评论和引用
	$sql="delete from ".$DBPrefix."comments where logId='$mark_id'";
	$DMC->query($sql);
	$sql="delete from ".$DBPrefix."trackbacks where logId='$mark_id'";
	$DMC->query($sql);
	
	//删除关联的附件
	/*$sql="select * from ".$DBPrefix."attachments where logId='$mark_id'";
	$result=$DMC->query($sql);
	while($my=$DMC->fetchArray($result)) {
		@unlink("../attachments/".$my['name']);
	}*/
	$sql="delete from ".$DBPrefix."attachments where logId='$mark_id'";
	$DMC->query($sql);
	
	settings_recount("logs");

	//更新Cache
	hottags_recache();
	categories_recache();
	settings_recache();
	recentLogs_recache();
	recentComments_recache();
	archives_recache();
	calendar_recache();
	attachments_recache();
	logsTitle_recache();
	logs_sidebar_recache($arrSideModule);

	//删除静态文件
	if (file_exists(F2BLOG_ROOT."./cache/html/{$mark_id}.html")){
		@unlink(F2BLOG_ROOT."./cache/html/{$mark_id}.html");
	}
	if (file_exists(F2BLOG_ROOT."./cache/html/{$mark_id}_index.html")){
		@unlink(F2BLOG_ROOT."./cache/html/{$mark_id}_index.html");
	}

	header("Location:../index.php"); 
}

//其它操作行为：隐藏／显示、删除等
if ($action=="operation"){
	$stritem="";
	$strlogsitem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
	}

	//静态页面
	if($_POST['operation']=="create_html"){
		$array_html_id=implode(",",$_POST['itemlist']);
		echo "<script language='javascript'>"; 
		echo "window.location='create_html.inc.php?arrayhtmlid=$array_html_id';"; 
		echo "</script>";
	}

	//公开1，草稿0，隐私3
	if(($_POST['operation']=="publish" or $_POST['operation']=="draft" or $_POST['operation']=="private") and $stritem!=""){
		if ($_POST['operation']=="publish"){
			$value=1;
			$type="adding";
		}elseif($_POST['operation']=="private"){
			$value=3;
			$type="minus";
		}else{
			$value=0;
			$type="minus";
		}

		for ($i=0;$i<count($itemlist);$i++){
			$mark_id=$itemlist[$i];
			$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
			$cateId=$dataInfo['cateId'];
			$name="";
			if ($dataInfo['tags']!="") {
				$tags=explode(";",$dataInfo['tags']);
				for ($j=0;$j<count($tags);$j++) {
					$name.=" or name='".$tags[$j]."'";
				}
				$name=($name=="")?"":substr($name,4);
			}
			
			if (($dataInfo['saveType']==1 && $value!=1) || ($dataInfo['saveType']!=1 && $value==1)){
				update_cateCount($cateId,$type,1);
			}

			if ($name!="") {
				update_num($DBPrefix."tags","logNums"," $name",$type,1);
			}
		}

		$sql="update ".$DBPrefix."logs set saveType='$value' where $stritem";
		$DMC->query($sql);

		settings_recount("logs");

		//更新Cache
		hottags_recache();
		categories_recache();
		settings_recache();
		recentLogs_recache();
		archives_recache();
		calendar_recache();
		logsTitle_recache();
		logs_sidebar_recache($arrSideModule);
	}

	//edit date
	if($_POST['operation']=="editdate" and $stritem!=""){
		if ($_POST['pubTime']!="") {
			$pubtime=str_format_time($_POST['pubTime']);
			$sql="update ".$DBPrefix."logs set postTime='$pubtime' where $stritem";
			$DMC->query($sql);
		}
	}

	//置顶展開
	if($_POST['operation']=="topopen" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTop='1' where $stritem";
		$DMC->query($sql);
	}

	//置顶隱藏
	if($_POST['operation']=="topclose" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTop='2' where $stritem";
		$DMC->query($sql);
	}

	//取消置顶
	if($_POST['operation']=="notop" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTop='0' where $stritem";
		$DMC->query($sql);
	}

	//允许评论
	if($_POST['operation']=="ctshow" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isComment='1' where $stritem";
		$DMC->query($sql);
	}

	//禁止评论
	if($_POST['operation']=="cthidden" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isComment='0' where $stritem";
		$DMC->query($sql);
	}

	//允许引用
	if($_POST['operation']=="tbshow" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTrackback='1' where $stritem";
		$DMC->query($sql);
	}

	//禁止引用
	if($_POST['operation']=="tbhidden" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTrackback='0' where $stritem";
		$DMC->query($sql);
	}

	//加密日志
	if($_POST['operation']=="addpassword" and $stritem!=""){
		if ($_POST['addpassword']==""){
			$sql="update ".$DBPrefix."logs set password='' where $stritem";
			$DMC->query($sql);
		}else{
			$sql="update ".$DBPrefix."logs set password='".md5(encode($_POST['addpassword']))."' where $stritem";
			$DMC->query($sql);
		}
	}

	//加贴标签
	if($_POST['operation']=="settags" and $stritem!=""){
		$_POST['settags']=encode(stripslashes($_POST['settags']));
		for ($i=0;$i<count($itemlist);$i++){
			$mark_id=$itemlist[$i];
			$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
			if ($dataInfo['tags']!=""){
				if (strpos(";".$dataInfo['tags'],$_POST['settags'])>0){
					$tagsname=$dataInfo['tags'];
				}else{
					$tagsname=$dataInfo['tags'].";".$_POST['settags'];
				}
			}else{
				$tagsname=$_POST['settags'];
			}

			$sql="update ".$DBPrefix."logs set tags='$tagsname' where id='$mark_id'";
			$DMC->query($sql);
		}
		update_num($DBPrefix."tags","logNums"," name='".$_POST['settags']."'","adding",count($itemlist));

		//更新cache
		hottags_recache();
		logs_sidebar_recache($arrSideModule);
	}

	//移动日志
	if($_POST['operation']=="logmove" and $stritem!=""){
		if ($_POST['move_category']==""){
			$ActionMessage="$strLogCategoryMoveError";
		}else{
			for ($i=0;$i<count($itemlist);$i++){
				$mark_id=$itemlist[$i];
				$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
				$cateId=$dataInfo['cateId'];

				update_cateCount($cateId,"minus",1);
								
			}
			$sql="update ".$DBPrefix."logs set cateId='".$_POST['move_category']."' where $stritem";
			$DMC->query($sql);
			
			update_cateCount($_POST['move_category'],"adding",count($itemlist));
		}

		//更新Cache
		categories_recache();
		recentLogs_recache();
		logsTitle_recache();
		logs_sidebar_recache($arrSideModule);
	}

	//自动截取
	if($_POST['operation']=="addautoSplit" and $stritem!=""){
		$autoSplit=$_POST['addautoSplit'];

		for ($i=0;$i<count($itemlist);$i++){
			$mark_id=$itemlist[$i];
			$logContent=getFieldValue($DBPrefix."logs"," id='$mark_id'","logContent");

			//如果日志包含了特殊标签，则不自载截取
			if (strpos(";".$logContent,"<!--more-->")>0) $autoSplit=0;
			if (strpos(";".$logContent,"<!--nextpage-->")>0) $autoSplit=0;
			if (strpos(";".$logContent,"<!--hideBegin-->")>0) $autoSplit=0;
			if (strpos(";".$logContent,"<!--galleryBegin-->")>0) $autoSplit=0;
			if (strpos(";".$logContent,"<!--fileBegin-->")>0) $autoSplit=0;
			if (strpos(";".$logContent,"<!--mfileBegin-->")>0) $autoSplit=0;

			$sql="update ".$DBPrefix."logs set autoSplit='$autoSplit' where id='$mark_id'";
			$DMC->query($sql);
		}

		//生成静态页面
		if ($settingInfo['isHtmlPage']==1){
			$array_html_id=implode(",",$_POST['itemlist']);
			echo "<script language='javascript'>"; 
			echo "window.location='create_html.inc.php?arrayhtmlid=$array_html_id';"; 
			echo "</script>";
		}
	}
	
	//删除
	if($_POST['operation']=="delete" and $stritem!=""){
		for ($i=0;$i<count($itemlist);$i++){
			$mark_id=$itemlist[$i];
			$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
			$cateId=$dataInfo['cateId'];
			$name="";
			if ($dataInfo['tags']!="") {
				$tags=explode(";",$dataInfo['tags']);
				for ($j=0;$j<count($tags);$j++) {
					$name.=" or name='".$tags[$j]."'";
				}
				$name=($name=="")?"":substr($name,4);
			}

			update_cateCount($cateId,"minus",1);
			if ($name!="") {
				update_num($DBPrefix."tags","logNums"," $name","minus",1);
			}

			//删除关联的附件
			$sql="select * from ".$DBPrefix."attachments where logId='$mark_id'";
			$result=$DMC->query($sql);
			while($my=$DMC->fetchArray($result)) {
				@unlink("../attachments/".$my['name']);
			}
			$sql="delete from ".$DBPrefix."attachments where logId='$mark_id'";
			$DMC->query($sql);

			//删除静态文件
			if (file_exists(F2BLOG_ROOT."./cache/html/{$mark_id}.php")){
				@unlink(F2BLOG_ROOT."./cache/html/{$mark_id}.php");
			}
			if (file_exists(F2BLOG_ROOT."./cache/html/{$mark_id}_index.php")){
				@unlink(F2BLOG_ROOT."./cache/html/{$mark_id}_index.php");
			}
		}

		$sql="delete from ".$DBPrefix."logs where $stritem";
		$DMC->query($sql);

		//删除关联的评论和引用
		$stritem1=str_replace("id=","logId=",$stritem);
		$sql="delete from ".$DBPrefix."comments where $stritem1";
		$DMC->query($sql);
		$sql="delete from ".$DBPrefix."trackbacks where $stritem1";
		$DMC->query($sql);

		settings_recount("logs");

		//更新Cache
		hottags_recache();
		categories_recache();
		settings_recache();
		recentLogs_recache();
		recentComments_recache();
		archives_recache();
		calendar_recache();
		attachments_recache();
		logsTitle_recache();
		logs_sidebar_recache($arrSideModule);
	}
}

//引用传送
if ($action=="sendtb"){
	$mark_id=$_GET['mark_id'];
	$quoteUrl=$_POST['quoteUrl'];
	$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
	header("Content-Type: text/html; charset=utf-8");
	$pingurl=explode(";",$quoteUrl);
	$logurl=$settingInfo['blogUrl']."index.php?load=read&id=$mark_id";
	foreach ($pingurl as $durl) {
		$result=send_trackback($durl, $dataInfo['logTitle'], $dataInfo['logContent'],$logurl);
		//echo $ActionMessage.=$durl." : ".$result."\n";
	}
	
	if ($result=="ok") {
		$quoteUrl=($dataInfo['quoteUrl']=="")?$quoteUrl:$dataInfo['quoteUrl'].";".$quoteUrl;
		$modify_sql="UPDATE ".$DBPrefix."logs set quoteUrl='$quoteUrl' WHERE id='$mark_id'";
		$DMC->query($modify_sql);

		$ActionMessage="$strSendTbSucc";
		$action="";
	} else {
		$ActionMessage="$strSendTbError".$result;
		$action="trackback";
	}
}

if ($action=="all"){
	$seekname="";
	$seekcate="";
	$seektags="";
	$seektype="";
}

$_GET['load']=isset($_GET['load'])?$_GET['load']:"";
$seek_url="$PHP_SELF?edittype=$edittype&showmode=".$_GET['showmode']."&order=$order&seektype=$seektype";	//查找用链接
$order_url="$PHP_SELF?edittype=$edittype&showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags&seektype=$seektype";	//排序栏用的链接
$page_url="$PHP_SELF?edittype=$edittype&showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags&order=$order&seektype=$seektype";	//页面导航链接
$edit_url="$PHP_SELF?edittype=$edittype&showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags&seektype=$seektype&order=$order&page=$page&load=".$_GET['load'];	//编辑或新增链接
$showmode_url="$PHP_SELF?edittype=$edittype&order=$order&page=$page&seektype=$seektype";	//展开／折叠链接

//取得自动保存的值
$convert_code=false;
if (($action=="add" || $action=="edit") && $settingInfo['autoSave']=="1"){
	//读取自动存档
	if (file_exists("../cache/cache_autosave.php")){
		include("../cache/cache_autosave.php");
		if ($autosavecontent['logContent']!="" || $autosavecontent['logTitle']!="") {
			$draftid=$autosavecontent['idforsave'];
			if ($_GET['load']=="save"){				
				if (($draftid!="" && $mark_id==$draftid) || ($action=="add" && $draftid=="")) {			
					$logTitle=$autosavecontent['logTitle'];
					//$logContent=str_replace("&","&amp;",$autosavecontent['logContent']);
					$logContent=$autosavecontent['logContent'];
					$logsediter=$autosavecontent['editor'];
				}
			}else if ($_GET['load']=="clear"){
				//Clear unsaved draft now
				if (file_exists("../cache/cache_autosave.php")){
					@unlink("../cache/cache_autosave.php");
				}
			}else{
				header("Content-Type: text/html; charset=utf-8");
			?>
				<script language="Javascript">
					choise=confirm("<?php echo $strLogLoadAutoSave?>");
					if (choise==true) {
						<?php if ($draftid==""){?>						
						window.location.href="<?php echo "$edit_url&action=add&mark_id=$draftid&load=save"?>";
						<?php }else{?>
						window.location.href="<?php echo "$edit_url&action=edit&mark_id=$draftid&load=save"?>";
						<?php }?>
					}else{
						window.location.href="<?php echo "$edit_url&action=$action&mark_id=$mark_id&load=clear"?>";
					}
				</script>		
			<?php 
			}
		}
	}

	//转换编辑器
	if (!empty($_POST['logsediter'])){
		$logsediter=$_POST['logsediter'];
	}
}

if ($action=="add"){
	//新增信息类别。
	$title="$strLogTitleAdd";
	$mtitle=$strLogNew;

	if (empty($_POST['logsediter']) && empty($logsediter)) $logsediter=$settingInfo['defaultedits'];
	$saveType=!isset($saveType)?1:$saveType;
	$cateId=!isset($cateId)?1:$cateId;
	$weather=!isset($weather)?1:$weather;
	$isTop=!isset($isTop)?0:$isTop;
	$pubTimeType=!isset($pubTimeType)?"now":$pubTimeType;
	$logTitle=!isset($logTitle)?"":$logTitle;
	$tags=!isset($tags)?"":$tags;
	$isComment=!isset($isComment)?"":$isComment;
	$autoSplit=!isset($autoSplit)?"":$autoSplit;
	$isTrackback=!isset($isTrackback)?"":$isTrackback;
	$addpassword=!isset($addpassword)?"":$addpassword;
	$logContent=empty($logContent)?"":$logContent;
	$oldTags="";
	$oldCateId="";

	include("logs_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strLogTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."logs where id='$mark_id'"));
	if ($dataInfo) {
		$cateId=$dataInfo['cateId'];
		$oldCateId=$dataInfo['cateId'];
		$logTitle=empty($logTitle)?$dataInfo['logTitle']:$logTitle;
		$logContent=empty($logContent)?$dataInfo['logContent']:$logContent;
		$author=$dataInfo['author'];
		$quoteUrl=$dataInfo['quoteUrl'];
		$isComment=$dataInfo['isComment'];
		$isTrackback=$dataInfo['isTrackback'];
		$isTop=$dataInfo['isTop'];
		$weather=$dataInfo['weather'];
		$saveType=$dataInfo['saveType'];
		$tags=$dataInfo['tags'];
		$oldTags=$dataInfo['tags'];
		$addpassword=$dataInfo['password'];
		$autoSplit=$dataInfo['autoSplit'];
		if (empty($_POST['logsediter']) && empty($logsediter)) $logsediter=$dataInfo['logsediter'];

		$pubTimeType=!isset($pubTimeType)?"now":$pubTimeType;

		include("logs_add.inc.php");
	}else{
		$error_message=$strNoExits;
		include("error_web.php");
	}
}else if ($action=="trackback"){
	//调整类别顺序
	$title="$strSendTb";

	$arr_parent = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."logs where id='$mark_id'"));
	if ($arr_parent) {
		include("logs_tb.inc.php");	
	}else{
		$error_message=$strCategoryExchangeNoData;
		include("error_web.php");
	}
}else{
	//查找和浏览
	$title="$strLogTitle";

	if ($order==""){$order="a.postTime";}

	//Find condition
	$find="";
	if ($seekname!=""){$find.=" and (a.author like '%$seekname%' or a.id='$seekname' or a.logTitle like '%$seekname%' or a.tags like '%$seekname%' or a.logContent like '%$seekname%')";}
	if ($seektags!=""){$find.=" and (a.tags like '%$seektags%')";}
	if ($seektype!=""){$find.=" and (a.saveType='$seektype')";}
	if ($_SESSION['rights']=="author") {$find.=" and (a.author='{$_SESSION['username']}')";}
	
	if ($seekcate!=""){
		$find_sql="select id from ".$DBPrefix."categories where parent='$seekcate' or id='$seekcate'";
		$find_result=$DMC->query($find_sql);
		$str="";
		while($fa=$DMC->fetchArray($find_result)) {
			$str.=" or a.cateId='".$fa['id']."'";
		}
		$find.=" and (".substr($str,4).")";
	}

	$sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where saveType!=2 $find order by $order desc";
	$nums_sql="select count(a.id) as numRows from ".$DBPrefix."logs as a where saveType!=2 $find";

	$total_num=getNumRows($nums_sql);
	include("logs_list.inc.php");
}
?>
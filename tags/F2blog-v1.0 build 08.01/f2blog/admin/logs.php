<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];
$order=$_GET['order'];
$page=$_GET['page'];
$seekname=$_REQUEST['seekname'];
$seekcate=$_REQUEST['seekcate'];
$seektags=$_REQUEST['seektags'];
$mark_id=$_GET['mark_id'];

//保存数据
if ($action=="save"){
	$check_info=1;
	
	//echo $_POST['logContent'];

	//取得Form的Value
	$cateId=trim($_POST['cateId']);
	$oldCateId=trim($_POST['oldCateId']);
	$logTitle=trim($_POST['logTitle']);
	$logContent=trim($_POST['logContent']);
	$author=trim($_POST['author']);
	$quoteUrl=trim($_POST['quoteUrl']);
	$pubTimeType=trim($_POST['pubTimeType']);
	$pubTime=trim($_POST['pubTime']);
	$isComment=trim($_POST['isComment']);
	$isTrackback=trim($_POST['isTrackback']);
	$isTop=trim($_POST['isTop']);
	$weather=trim($_POST['weather']);
	$saveType=trim($_POST['saveType']);
	$tags=trim($_POST['tags']);
	$oldTags=trim($_POST['oldTags']);
	$edittype=$_REQUEST['edittype'];

	if ($_POST['addpassword']!="" && strlen($_POST['addpassword'])<15){
		$addpassword=md5($_POST['addpassword']);
	}else{
		$addpassword=$_POST['addpassword'];
	}
	
	//检测输入内容为空
	if ($logTitle=="" or $logContent=="" or $cateId==""){
		$ActionMessage=$strErrNull;
		$check_info=0;
	}

	if ($check_info==1){
		//$logContent=encode($logContent);
		$logTitle=encode($logTitle);
		$postTime=($pubTimeType=="now")?time():str_format_time($pubTime);
		$author=$_SESSION['username'];

		//已存在的Tags
		$exist_tags=tag_list("A");

		if ($tags!="") {
			$tags_array=@explode(';', trim($tags));
			$tags_array_all=array_unique($tags_array);
			$tags=@implode(';', $tags_array_all);
			$tags_array_all=@explode(';', $tags);
		}

		if ($mark_id!=""){//编辑
			$rsexits=getFieldValue($DBPrefix."logs","logTitle='$logTitle' and cateId='$cateId' and saveType!=2","id");
			if ($rsexits!=$mark_id && $rsexits!=""){
				$ActionMessage="$strDataExists";
				$action="add";
			}else{
				$edit_sql=($pubTimeType=="now")?"":",postTime='$postTime'";
				$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',author='$author',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',weather='$weather',saveType='$saveType',tags='$tags',password='$addpassword'$edit_sql where id='$mark_id'";
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
				$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$weather','$saveType','$tags','$addpassword')";
				$DMC->query($sql);
				
				//Send Trackback
				if ($quoteUrl!="") {
					@header("Content-Type: text/html; charset=utf-8");
					$pingurl=explode(";",$quoteUrl);
					foreach ($pingurl as $durl) {
						$result=@send_trackback ($durl, $logTitle, $logContent);
					}
				}

				$ActionMessage=$strSaveSuccess;
				$action="";
				$last_id=$DMC->insertId();
			}
		}
		
		//增加，编辑成功，Tags
		if ($tags!=$oldTags) {
			if (count($exist_tags)>0 && $exist_tags[0]!=""){
				$newtags=array_diff($tags_array_all, $exist_tags);
				$newtags=array_values($newtags); 
				$modifytags=array_diff($tags_array_all, $newtags);
				$modifytags_query="'".implode("', '", $modifytags)."'";
				
				$modify_sql="UPDATE ".$DBPrefix."tags set logNums=logNums+1 WHERE name in(".$modifytags_query.")";
				$DMC->query($modify_sql);
			}else{
				$newtags=$tags_array_all;
			}			

			for ($m=0; $m<count($newtags); $m++) {
				if ($newtags[$m]!="") {
					$itags=encode($newtags[$m]);
					if (check_record($DBPrefix."tags"," name='$itags'")) {
						$add_sql="INSERT INTO ".$DBPrefix."tags (name,logNums) VALUES ('".$itags."',1)";
						$DMC->query($add_sql);
						add_bloginfo("tagNums","adding",1);
					}
				}
			}
		}

		if($action=="") {	
			//增加，编辑成功，类别
			if ($cateId!=$oldCateId) {
				if ($oldCateId=="") { //新增日志时
					update_cateCount($cateId,"adding",1);
					add_bloginfo("logNums","adding",1);
				} else {
					update_cateCount($oldCateId,"minus",1); //减少原来类别数量
					update_cateCount($cateId,"adding",1);//增加新类别数量
				}
			}
	
			//更新未链接的附件
			$modify_sql="UPDATE ".$DBPrefix."attachments set logId='$last_id' WHERE logId=0";
			$DMC->query($modify_sql);

			//更新Cache
			hottags_recache();
			categories_recache();
			statistics_recache();
			recentLogs_recache();
			archives_recache();
			calendar_recache();
		}

		if ($edittype=="front") {
			header("Location:../index.php?load=read&id=$mark_id"); 
		}
	} else {
		$action="add";
	}
}

//单条删除
if($_REQUEST['action']=="delete"){
	$mark_id=$_GET['id'];
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
	$sql="select * from ".$DBPrefix."attachments where logId='$mark_id'";
	$result=$DMC->query($sql);
	while($my=$DMC->fetchArray($result)) {
		@unlink("../attachments/".$my['name']);
	}
	$sql="delete from ".$DBPrefix."attachments where logId='$mark_id'";
	$DMC->query($sql);
	
	add_bloginfo("logNums","minus",count($itemlist));

	//更新Cache
	hottags_recache();
	categories_recache();
	settings_recache();
	recentLogs_recache();
	recentComments_recache();
	archives_recache();
	calendar_recache();

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

	//公开与不公开
	if(($_POST['operation']=="publish" or $_POST['operation']=="nopublish") and $stritem!=""){
		$type=($_POST['operation']=="publish")?"adding":"minus";
		$value=($_POST['operation']=="publish")?1:0;

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

			update_cateCount($cateId,$type,1);
			if ($name!="") {
				update_num($DBPrefix."tags","logNums"," $name",$type,1);
			}
		}

		$sql="update ".$DBPrefix."logs set saveType='$value' where $stritem";
		$DMC->query($sql);

		add_bloginfo("logNums",$type,count($itemlist));

		//更新Cache
		hottags_recache();
		categories_recache();
		settings_recache();
		recentLogs_recache();
		archives_recache();
		calendar_recache();
	}

	//置顶
	if($_POST['operation']=="top" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTop='1' where $stritem";
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
			$sql="update ".$DBPrefix."logs set password='".md5($_POST['addpassword'])."' where $stritem";
			$DMC->query($sql);
		}
	}

	//加贴标签
	if($_POST['operation']=="settags" and $stritem!=""){
		for ($i=0;$i<count($itemlist);$i++){
			$mark_id=$itemlist[$i];
			$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");

			$name="";
			if ($dataInfo['tags']!="") {
				$tags=explode(";",$dataInfo['tags']);
				for ($j=0;$j<count($tags);$j++) {
					$name.=" or name='".$tags[$j]."'";
				}
				$name=($name=="")?"":substr($name,4);
			}

			if ($name!="") {
				update_num($DBPrefix."tags","logNums"," $name","minus",1);
			}
		}
		$sql="update ".$DBPrefix."logs set tags='".$_POST['settags']."' where $stritem";
		$DMC->query($sql);
		//echo $sql;
		update_num($DBPrefix."tags","logNums"," name='".$_POST['settags']."'","adding",count($itemlist));

		//更新cache
		hottags_recache();
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
				for ($i=0;$i<count($tags);$i++) {
					$name.=" or name='".$tags[$i]."'";
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
		}

		$sql="delete from ".$DBPrefix."logs where $stritem";
		$DMC->query($sql);

		//删除关联的评论和引用
		$stritem1=str_replace("id=","logId=",$stritem);
		$sql="delete from ".$DBPrefix."comments where $stritem1";
		$DMC->query($sql);
		$sql="delete from ".$DBPrefix."trackbacks where $stritem1";
		$DMC->query($sql);

		add_bloginfo("logNums","minus",count($itemlist));

		//更新Cache
		hottags_recache();
		categories_recache();
		settings_recache();
		recentLogs_recache();
		recentComments_recache();
		archives_recache();
		calendar_recache();
	}
}

//引用传送
if ($action=="sendtb"){
	$mark_id=$_GET['mark_id'];
	$quoteUrl=$_POST['quoteUrl'];
	$dataInfo=getRecordValue($DBPrefix."logs"," id='$mark_id'");
	@header("Content-Type: text/html; charset=utf-8");
	$pingurl=explode(";",$quoteUrl);
	foreach ($pingurl as $durl) {
		$result=send_trackback($durl, $dataInfo['logTitle'], $dataInfo['logContent']);
		//echo $ActionMessage.=$durl." : ".$result."\n";
	}

	$quoteUrl=($dataInfo['quoteUrl']=="")?$quoteUrl:$dataInfo['quoteUrl'].";".$quoteUrl;
	$modify_sql="UPDATE ".$DBPrefix."logs set quoteUrl='$quoteUrl' WHERE id='$mark_id'";
	$DMC->query($modify_sql);

	$ActionMessage="$strSendTbSucc";
	$action="";
}

if ($action=="all"){
	$seekname="";
	$seekcate="";
	$seektags="";
}

$seek_url="$PHP_SELF?showmode=".$_GET['showmode']."&order=$order";	//查找用链接
$order_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags";	//排序栏用的链接
$page_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags&order=$order";	//页面导航链接
$edit_url="$PHP_SELF?showmode=".$_GET['showmode']."&seekname=$seekname&seekcate=$seekcate&seektags=$seektags&order=$order&page=$page";	//编辑或新增链接
$showmode_url="$PHP_SELF?order=$order&page=$page";	//展开／折叠链接

if ($action=="add"){
	//新增信息类别。
	$title="$strLogTitleAdd";
	include("logs_add.inc.php");
}else if ($action=="edit" && $mark_id!=""){
	//编辑信息类别。
	$title="$strLogTitleEdit - $strRecordID: $mark_id";

	$dataInfo = $DMC->fetchArray($DMC->query("select * from ".$DBPrefix."logs where id='$mark_id'"));
	if ($dataInfo) {
		$cateId=$dataInfo['cateId'];
		$oldCateId=$dataInfo['cateId'];
		$logTitle=dencode($dataInfo['logTitle']);
		$logContent=str_replace("&","&amp;",$dataInfo['logContent']);
		$author=$dataInfo['author'];
		$quoteUrl=$dataInfo['quoteUrl'];
		$isComment=$dataInfo['isComment'];
		$isTrackback=$dataInfo['isTrackback'];
		$isTop=$dataInfo['isTop'];
		$weather=$dataInfo['weather'];
		$saveType=$dataInfo['saveType'];
		$tags=$dataInfo['tags'];
		$oldTags=$dataInfo['tags'];
		$edittype=$_REQUEST['edittype'];
		$addpassword=$dataInfo['password'];

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
	if ($seekname!=""){$find.=" and (a.logTitle like '%$seekname%' or a.tags like '%$seekname%' or a.logContent like '%$seekname%')";}
	if ($seektags!=""){$find.=" and (a.tags like '%$seektags%')";}
	if ($seekcate!=""){
		$find_sql="select id from ".$DBPrefix."categories where parent='$seekcate' or id='$seekcate'";
		$find_result=$DMC->query($find_sql);
		$str="";
		while($fa=$DMC->fetchArray($find_result)) {
			$str.=" or a.cateId='".$fa['id']."'";
		}
		$find.=" and (".substr($str,4).")";
	}

	if ($find!=""){
		$find=substr($find,5);
		$sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where saveType!=2 and $find order by $order desc";
	} else {
		$sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where saveType!=2 order by $order desc";
	}
	//echo $sql;
	$total_num=$DMC->numRows($DMC->query($sql));
	include("logs_list.inc.php");
}
?>
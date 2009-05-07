<?php
error_reporting(E_ERROR & ~E_NOTICE);

$rootPath=substr(dirname(__FILE__), 0, -15);
$curPath=$rootPath."./plugins/GTMusic/";
include_once($rootPath."./admin/function.php");

check_login(); //检查是否登录
$curStyle=$settingInfo['adminstyle'];
$action=$_GET['action'];
$page=$_GET['page'];
$mark_id=$_GET['mark_id'];

$seekname=encode($_REQUEST['seekname']);
$seekdisc=empty($_REQUEST['seekdisc'])?"":intval($_REQUEST['seekdisc']);

$page_url="$PHP_SELF?seekname=$seekname&seekdisc=$seekdisc";	//页面导航链接
$edit_url="$PHP_SELF?page=$page&seekname=$seekname&seekdisc=$seekdisc";	//编辑或新增链接

function disc_select($field_name,$field_value,$style){
	global $DMC, $DBPrefix;

	echo "<select name=\"$field_name\" $style>\n";
	echo "<option value=\"\" >-- 专辑 --</option>\n";

	$query_sql="SELECT ClassId,ClassName FROM ".$DBPrefix."musicclass order by ClassId";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		$selected=($field_value==$arr_parent[$i]['ClassId'])?"selected":"";
		echo "<option value=\"".$arr_parent[$i]['ClassId']."\" $selected>".$arr_parent[$i]['ClassName']."</option>\n";
	}
	echo "</select>\n";
}

function playlistfile() {
	global $DMC, $DBPrefix,$curPath;

	$configFile=$curPath."./conn/player.HTM";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($configFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>conn/player.HTM => Please change the CHMOD as 777.</font></b>";
	} else {
		$rsconfig = $DMC->fetchArray($DMC->query("select * from {$DBPrefix}musicsetting where id='1'"));
		$set_autoplay=($rsconfig['set_autoplay']==1)?"true":"false";
		$set_shuffle=($rsconfig['set_shuffle']==1)?"true":"false";
		$set_loop=($rsconfig['set_loop']==1)?"true":"false";

		$filecontent="";
		//$filecontent.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
		$filecontent.="<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"utf-8\">\n";
		$filecontent.="<head>\n";
		$filecontent.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		$filecontent.="<meta http-equiv=\"Content-Language\" content=\"utf-8\" />\n";
		$filecontent.="<meta name=\"robots\" content=\"all\" />\n";
		$filecontent.="<meta name=\"author\" content=\"greenteacn[a]gmail.com,绿茶|joesenhong[a]yahoo.com.cn,骆驼\" />\n";
		$filecontent.="<meta name=\"Copyright\" content=\"F2Blog CopyRight 2006\" />\n";
		$filecontent.="<meta name=\"keywords\" content=\"f2blog,phpblog,blog,php,xhtml,css,design,w3c,w3cn,Joesen,天上的骆驼\" />\n";
		$filecontent.="<meta name=\"description\" content=\"My F2Blog - Free &amp; Freedom Blog\" />\n";
		$filecontent.="<title>".$rsconfig['BlogRoot']."-GTMusic 绿茶馆儿-火焠鳥-F2Blog专用版</title>\n";
		$filecontent.="<link rel=\"stylesheet\" type=\"text/css\" href=\"exobud.css\">\n";
		$filecontent.="<script language=\"JavaScript\" src=\"exobud.js\"></script>\n";
		$filecontent.="<script language=\"JavaScript\" type=\"text/javascript\">\n";
		$filecontent.="<!--\n";
		$filecontent.="if ((top==self) && (top.location.href.charAt(0)=='h')) {top.location.href=\"music.php\";}\n";
		$filecontent.="var blnAutoStart = true;\n";	
		$filecontent.="var blnRndPlay = ".$set_shuffle.";\n";	//随机播放
		$filecontent.="var blnStatusBar = false;\n";
		$filecontent.="var blnShowVolCtrl = true;\n";
		$filecontent.="var blnShowPlist = true;\n";
		$filecontent.="var blnLoopTrk = ".$set_loop.";\n";	//循环播放
		
		$query_sql="SELECT MusicName,MusicUrl FROM {$DBPrefix}music where IsPlayList='1' order by orderNo";
		$query_result=$DMC->query($query_sql);
		while($fa = $DMC->fetchArray($query_result)){
			$filecontent.="mkList(\"".$fa['MusicUrl']."\",\"".$fa['MusicName']."\",\"GT\");\n";
		}

		$filecontent.="//-->\n";
		$filecontent.="</script>\n";
		$filecontent.="<script language=\"JScript\" for=\"Exobud\" event=\"playStateChange(ns)\">evtPSChg(ns);</script>\n";
		$filecontent.="<script language=\"JScript\" for=\"Exobud\" event=\"error()\">evtWmpError();</script>\n";
		$filecontent.="<script language=\"JScript\" for=\"Exobud\" event=\"Buffering(bf)\">evtWmpBuff(bf);</script>\n";
		$filecontent.="</head>\n";
		$filecontent.="<body onload=\"initExobud()\">\n";
		$filecontent.="<object classid=\"CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6\" type=\"application/x-oleobject\" width=\"12\" height=\"12\" id=\"Exobud\">\n";
		$filecontent.="<param name=\"autoStart\" value=\"true\">\n";
		$filecontent.="<param name=\"balance\" value=\"0\">\n";
		$filecontent.="<param name=\"currentPosition\" value=\"0\">\n";
		$filecontent.="<param name=\"currentMarker\" value=\"0\">\n";
		$filecontent.="<param name=\"enableContextMenu\" value=\"false\">\n";
		$filecontent.="<param name=\"enableErrorDialogs\" value=\"false\">\n";
		$filecontent.="<param name=\"enabled\" value=\"true\">\n";
		$filecontent.="<param name=\"fullScreen\" value=\"false\">\n";
		$filecontent.="<param name=\"invokeURLs\" value=\"false\">\n";
		$filecontent.="<param name=\"mute\" value=\"false\">\n";
		$filecontent.="<param name=\"playCount\" value=\"1\">\n";
		$filecontent.="<param name=\"rate\" value=\"1\">\n";
		$filecontent.="<param name=\"uiMode\" value=\"none\">\n";
		$filecontent.="<param name=\"volume\" value=\"".$rsconfig['set_volume']."\">\n";	//音量
		$filecontent.="</object>\n";
		$filecontent.="<span id=\"disp1\" class=\"title\"></span>\n";
		$filecontent.="<span id=\"disp2\" class=\"time\"></span>\n";
		$filecontent.="</body>\n";
		$filecontent.="</html>\n";

		$fp = @fopen($configFile, 'wbt');
		@fwrite($fp, $filecontent);
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}

//保存数据
if ($action=="save"){
	$curTime=time();
	if ($mark_id!=""){//编辑
		//检测歌曲是否重复
		if ($_POST['MusicName']!=""){
			$nickrsexits=getFieldValue($DBPrefix."music","MusicName='".encode($_POST['MusicName'])."'","MusicId");
			$check_info=($nickrsexits!="" && $nickrsexits!=$mark_id)?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此歌曲已存在!";
		}else{
			$sql="update {$DBPrefix}music set MusicName='".encode($_POST['MusicName'])."',MusicSinger='".encode($_POST['MusicSinger'])."',MusicUrl='".encode($_POST['MusicUrl'])."',Commend='{$_POST['Commend']}',AppDate='$curTime',ClassId='{$_POST['disc']}' where MusicId='$mark_id'";
			$DMC->query($sql);
		}
	}else{//新增
		if ($_POST['MusicName']!=""){
			$nickrsexits=getFieldValue($DBPrefix."music","MusicName='".encode($_POST['MusicName'])."'","MusicId");
			$check_info=($nickrsexits!="")?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此歌曲已存在!";
		}else{
			$sql="INSERT INTO {$DBPrefix}music(MusicName,MusicSinger,MusicUrl,Commend,AppDate,ClassId) VALUES ('".encode($_POST['MusicName'])."','".encode($_POST['MusicSinger'])."','".encode($_POST['MusicUrl'])."','{$_POST['Commend']}','$curTime','{$_POST['disc']}')";
			$DMC->query($sql);
		}
	}

	if ($check_info==0) {
		$action=($mark_id!="")?"edit":"add";
	} else {
		$action="";
	}
}

//参数设置保存
if ($action=="settingsave") {
	$sql="update {$DBPrefix}musicsetting set set_autoplay='{$_POST['set_autoplay']}',set_shuffle='{$_POST['set_shuffle']}',set_loop='{$_POST['set_loop']}',set_volume='{$_POST['set_volume']}' where id='1'";
	$DMC->query($sql);

	$ActionMessage=playlistfile();
	$action="setting";
}

//专辑删除(同时删除此专辑下的所有歌曲)
if ($action=="discdel"){
	$sql="delete from {$DBPrefix}musicclass where ClassId='{$_GET['ClassId']}'";
	$DMC->query($sql);

	$sql="delete from {$DBPrefix}music where ClassId='{$_GET['ClassId']}'";
	$DMC->query($sql);

	$ActionMessage=playlistfile();
	$action="musicdisc";
}

//专辑的编辑保存／增加保存
if ($action=="discsave" or $action=="discadd"){
	if ($action=="discsave"){//编辑
		$index=$_GET['index'];
		$ClassName=$arr_ClassName[$index-1];
		$Commend=$arr_Commend[$index-1];

		if ($ClassName!=""){
			$nickrsexits=getFieldValue($DBPrefix."musicclass","ClassName='".encode($ClassName)."'","ClassId");
			$check_info=($nickrsexits!="" && $nickrsexits!=$mark_id)?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此专辑已存在!";
		}else{
			$sql="update {$DBPrefix}musicclass set ClassName='".encode($ClassName)."',Commend='{$Commend}' where ClassId='{$_GET['ClassId']}'";
			$DMC->query($sql);
		}
	}else{//新增
		if ($_POST['add_ClassName']!=""){
			$nickrsexits=getFieldValue($DBPrefix."musicclass","ClassName='".encode($_POST['add_ClassName'])."'","ClassId");
			$check_info=($nickrsexits!="")?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此专辑已存在!";
		}else{
			$sql="INSERT INTO {$DBPrefix}musicclass(ClassName,Commend) VALUES ('".encode($_POST['add_ClassName'])."','{$_POST['add_Commend']}')";
			$DMC->query($sql);
		}

		$add_ClassName="";
		$add_Commend="";
	}

	$action="musicdisc";
}

//播放列表保存排序
if ($action=="saveorder") {
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update {$DBPrefix}music set orderNo='".($i+1)."' where MusicId='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}

	$ActionMessage=playlistfile();
	$action="order";
}

//其它操作行为：删除，增加／移除到播放列表
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or MusicId='$itemlist[$i]'";
		}else{
			$stritem.="MusicId='$itemlist[$i]'";
		}
	}
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from {$DBPrefix}music where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="addplaylist" and $stritem!=""){
		$sql="update {$DBPrefix}music set IsPlayList='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="moveplaylist" and $stritem!=""){
		$sql="update {$DBPrefix}music set IsPlayList='0' where $stritem";
		$DMC->query($sql);
	}

	$ActionMessage=playlistfile();
	$action="";
}

if ($action=="") {
	$seekname="";
	$seekdisc="";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo "../../admin/themes/".$curStyle."/style.css"?>" type="text/css" />
<script type="text/javascript" src="../../admin/js/lib.js"></script>
</head>
<body style="background: #FFFFFF;">

<form action="" method="post" name="seekform">
	<?php
		$class1=($action=="setting")?"color:#cc0000":"";
		$class2=($action=="" or $action=="find" or $action=="edit")?"color:#cc0000":"";
		$class3=($action=="add")?"color:#cc0000":"";
		$class4=($action=="musicdisc")?"color:#cc0000":"";
		$class5=($action=="order")?"color:#cc0000":"";
	?>

	<div style="color: #6c6c6c; font-size: 11pt; font-weight:bole; margin-top: 8px; padding: 2px; border: solid 1px #c6c6c6;">&nbsp;
		<a href="<?php echo $page_url?>&action=setting" style="<?php echo $class1?>">参数设置</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=" style="<?php echo $class2?>">歌曲管理</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=add" style="<?php echo $class3?>">增加歌曲</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=musicdisc" style="<?php echo $class4?>">专辑管理</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=order" style="<?php echo $class5?>">播放列表排序</a>
	</div>

	<!--显示歌曲管理-->
	<?php if ($action=="" or $action=="find") {
		$find="";
		$find1="";
		if ($seekname!=""){$find.=" and (a.MusicName like '%$seekname%' or a.MusicSinger like '%$seekname%')";}
		if ($seekdisc!=""){$find.=" and (a.ClassId='$seekdisc')";}
		if ($find!="") {
			$find=" where ".substr($find,5);
			$find1=str_replace("a.","",$find);
		}

		$sql="select a.*,b.ClassName from {$DBPrefix}music as a left join {$DBPrefix}musicclass as b on a.ClassId=b.ClassId $find order by a.MusicId desc";
		$nums_sql="select count(MusicId) as numRows from {$DBPrefix}music $find1";
		$total_num=getNumRows($nums_sql);
	?>
		<div class="searchtool">
		  <?php echo $strBlueFind?>
		  &nbsp;
		  <input type="text" name="seekname" size="5" value="<?php echo $seekname?>">
		  &nbsp;
		  <?php disc_select("seekdisc",$seekdisc,"class=\"searchbox\""); ?>
		  &nbsp;
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $page_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $page_url?>','')">
		  &nbsp;|&nbsp;
		  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1" checked>
		  <?php echo $strDelete?>
		  |
		  <input type="radio" name="operation" value="addplaylist" onclick="Javascript:this.form.opmethod.value=1">
		  添加歌曲到播放列表
		  <input type="radio" name="operation" value="moveplaylist" onclick="Javascript:this.form.opmethod.value=1">
		  从播放列表中移除
		  <input name="opselect" type="hidden" value="">
		  <input name="opmethod" type="hidden" value="1">
		  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">

		  <div class="page">
			<?php view_page($page_url); ?>
		  </div>
		</div>

		<div class="subcontent">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="10%" nowrap class="whitefont">
			  <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>"><?php echo $strSelectCancelAll?>
			  </td>
			  <td width="22%" nowrap class="whitefont">歌曲名称</td>
			  <td width="15%" nowrap class="whitefont">所属专辑</td>
			  <td width="8%" nowrap class="whitefont">歌手</td>
			  <td width="10%" nowrap class="whitefont" align="center">播放列表</td>
			  <td width="10%" nowrap class="whitefont">推荐级别</td>
			  <td width="25%" nowrap class="whitefont">增加时间</td>
			</tr>
			<?php
			if ($page<1) { $page=1; }
			$start_record=($page-1)*$settingInfo['adminPageSize'];

			$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
			$query_result=$DMC->query($query_sql);
			$index=0;
			while($fa = $DMC->fetchArray($query_result)){
				$index++;

				$existsPL=($fa['IsPlayList']=="1")?"<font color=blue>Y</font>":"&nbsp;";
			?>
			<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			  <td nowrap class="subcontent-td" align="center">
				<INPUT type=checkbox value="<?php echo $fa['MusicId']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">&nbsp;
				<a href="<?php echo "$edit_url&mark_id=".$fa['MusicId']."&action=edit"?>"><img src="../../admin/themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" alt="<?php echo "$strEdit"?>" border="0" /></a>&nbsp;
				<a href="<?php echo $fa['MusicUrl']?>"><img src="images/disk.gif" alt="下载" border="0"  /></a>
			  </td>
			  <td nowrap class="subcontent-td"><?php echo $fa['MusicName']?></td>
			  <td nowrap class="subcontent-td"><?php echo $fa['ClassName']?>&nbsp;</td>
			  <td nowrap class="subcontent-td"><?php echo $fa['MusicSinger']?>&nbsp;</td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $existsPL?></td>
			  <td nowrap class="subcontent-td"><img src="images/level<?php echo $fa['Commend']?>.gif" /></td>
			  <td nowrap class="subcontent-td"><?php echo format_time("L",$fa['AppDate'])?></td>
			</tr>
			<?php } ?>
		  </table>
		</div>

	<?php } ?>
	<!--结束显示歌曲管理-->

	
	<!--增加／编辑歌曲-->
	<?php if ($action=="add" or ($action=="edit" && is_numeric($mark_id))) { 
		if ($action=="add") {
			$MusicName=empty($_POST['MusicName'])?"":$_POST['MusicName'];
			$MusicSinger=empty($_POST['MusicSinger'])?"":$_POST['MusicSinger'];
			$MusicUrl=empty($_POST['MusicUrl'])?"":$_POST['MusicUrl'];
			$Commend=empty($_POST['Commend'])?"":$_POST['Commend'];
			$IsPlayList=empty($_POST['IsPlayList'])?"":$_POST['IsPlayList'];
			$disc=empty($_POST['ClassId'])?"":$_POST['ClassId'];
		} else {
			$dataInfo = $DMC->fetchArray($DMC->query("select * from {$DBPrefix}music where MusicId='$mark_id'"));
			if ($dataInfo) {
				$MusicName=$dataInfo['MusicName'];
				$MusicSinger=$dataInfo['MusicSinger'];
				$MusicUrl=$dataInfo['MusicUrl'];
				$Commend=$dataInfo['Commend'];
				$IsPlayList=$dataInfo['IsPlayList'];
				$disc=$dataInfo['ClassId'];
			}
		}
	?>

		<script type="text/javascript">
		<!--
		function onclick_update(form) {	
			if (form.MusicName.value==""){
				alert('歌曲名称不能为空');
				form.MusicName.focus();
				return false;
			}

			if (form.MusicUrl.value==""){
				alert('歌曲网址不能为空');
				form.MusicUrl.focus();
				return false;
			}
			
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=save"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php if ($ActionMessage!="") { ?>
		  <br />
		  <fieldset>
		  <legend>
		  <?php echo $strErrorInfo?>
		  </legend>
		  <div align="center">
			<table border="0" cellpadding="2" cellspacing="1">
			  <tr>
				<td><span class="alertinfo">
				  <?php echo $ActionMessage?>
				  </span></td>
			  </tr>
			</table>
		  </div>
		  </fieldset>
		  <br />
		  <?php } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">歌曲名称</td>
				  <td>
					<input name="MusicName" type="text" class="textbox" size="20" value="<?php echo $MusicName?>" maxlength="20">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td>歌手</td>
				  <td>
					<input name="MusicSinger" type="text" class="textbox" size="20" value="<?php echo $MusicSinger?>" maxlength="20">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td>所属专辑</td>
				  <td>
					<?php disc_select("disc",$disc,"class=\"searchbox\""); ?>
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td>推荐级别</td>
				  <td>
					  <select name="Commend">
						  <option value="1"<?php if ($Commend=="1" or $Commend=="") echo " selected"; ?>>一级</option>
						  <option value="2"<?php if ($Commend=="2") echo " selected";?>>二级</option>
						  <option value="3"<?php if ($Commend=="3") echo " selected";?>>三级</option>
						  <option value="4"<?php if ($Commend=="4") echo " selected";?>>四级</option>
						  <option value="5"<?php if ($Commend=="5") echo " selected";?>>五级</option>
					  </select>
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">歌曲地址</td>
				  <td>
					<input name="MusicUrl" type="text" class="textbox" size="50" value="<?php echo $MusicUrl?>" maxlength="250"> 请输入完整网址
				  </td>
			   </tr>
		  </table>
		</div>
		<br />
		<div>
		  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'"/>
		</div>
	<?php } ?>
	<!--结束增加／编辑歌曲-->


	<!--编辑 参数设置-->
	<?php if ($action=="setting") { 
		$dataInfo = $DMC->fetchArray($DMC->query("select * from {$DBPrefix}musicsetting where id='1'"));
		if ($dataInfo) {
			$BlogRoot=$dataInfo['BlogRoot'];
			$view_player=$dataInfo['view_player'];
			$set_autoplay=$dataInfo['set_autoplay'];
			$set_shuffle=$dataInfo['set_shuffle'];
			$set_loop=$dataInfo['set_loop'];
			$set_volume=$dataInfo['set_volume'];
			$use_marquee=$dataInfo['use_marquee'];
			$marquee_direction=$dataInfo['marquee_direction'];
			$marquee_scrolldelay=$dataInfo['marquee_scrolldelay'];
		}
	?>
		<script type="text/javascript">
		<!--
		function onclick_update(form) {	
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&action=settingsave"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php if ($ActionMessage!="") { ?>
		  <br />
		  <fieldset>
		  <legend>
		  <?php echo $strErrorInfo?>
		  </legend>
		  <div align="center">
			<table border="0" cellpadding="2" cellspacing="1">
			  <tr>
				<td><span class="alertinfo">
				  <?php echo $ActionMessage?>
				  </span></td>
			  </tr>
			</table>
		  </div>
		  </fieldset>
		  <br />
		  <?php } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr class="subcontent-input">
				  <td width="10%" nowrap class="input-titleblue">是否自动播放</td>
				  <td width="90%" style="padding-top:7px">
					<input name="set_autoplay" type="radio" value="1" <?php if ($set_autoplay=="1") { echo "checked"; }?>>是
					<input name="set_autoplay" type="radio" value="0" <?php if ($set_autoplay=="0" or $set_autoplay=="") { echo "checked"; }?>>否
				  </td>
			   </tr>
			  <tr class="subcontent-input">
				  <td width="10%" nowrap class="input-titleblue">是否随机播放</td>
				  <td width="90%" style="padding-top:7px">
					<input name="set_shuffle" type="radio" value="1" <?php if ($set_shuffle=="1" or $set_shuffle=="") { echo "checked"; }?>>是
					<input name="set_shuffle" type="radio" value="0" <?php if ($set_shuffle=="0") { echo "checked"; }?>>否
				  </td>
			   </tr>
			  <tr class="subcontent-input">
				  <td width="10%" nowrap class="input-titleblue">是否循环播放</td>
				  <td width="90%" style="padding-top:7px">
					<input name="set_loop" type="radio" value="1" <?php if ($set_loop=="1" or $set_loop=="") { echo "checked"; }?>>是
					<input name="set_loop" type="radio" value="0" <?php if ($set_loop=="0") { echo "checked"; }?>>否
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
					<td nowrap>默认音量</td>
					<td>
						<input name="set_volume" type="text" class="textbox" maxlength="2" value="<?php echo $set_volume?>">
					</td>
				</tr>
		  </table>
		</div>
		<br />
		<div>
		  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'"/>
		</div>
	<?php } ?>
	<!--结束编辑参数设置-->


	<!--专辑管理-->
	<?php if ($action=="musicdisc") { 
		$sql="select * from {$DBPrefix}musicclass order by ClassId";
		$nums_sql="select count(ClassId) as numRows from {$DBPrefix}musicclass";
		$total_num=getNumRows($nums_sql);
	?>
		<script type="text/javascript">
		<!--
		function onclick_update(form) {	
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=save"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php if ($ActionMessage!="") { ?>
		  <br />
		  <fieldset>
		  <legend>
		  <?php echo $strErrorInfo?>
		  </legend>
		  <div align="center">
			<table border="0" cellpadding="2" cellspacing="1">
			  <tr>
				<td><span class="alertinfo">
				  <?php echo $ActionMessage?>
				  </span></td>
			  </tr>
			</table>
		  </div>
		  </fieldset>
		  <br />
		  <?php } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="20%" nowrap class="whitefont">专辑名称</td>
			  <td width="10%" nowrap class="whitefont">推荐级别</td>
			  <td width="70%" nowrap class="whitefont">&nbsp;</td>
			</tr>
			<?php   		
			$index=0;
			if ($page<1) { $page=1; }
			$start_record=($page-1)*$settingInfo['adminPageSize'];

			$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
			$query_result=$DMC->query($query_sql);
			while($fa = $DMC->fetchArray($query_result)){
				$index++;
				$ClassName=$fa['ClassName'];
				$Commend=$fa['Commend'];
				$ClassId=$fa['ClassId'];
			?>
			  <tr onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
				  <td><input name="arr_ClassName[]" type="text" class="textbox" size="20" value="<?php echo $ClassName?>" maxlength="20"></td>
				  <td>
					  <select name="arr_Commend[]" class="searchbox">
						  <option value="1"<?php if ($Commend=="1" or $Commend=="") echo " selected"; ?>>一级</option>
						  <option value="2"<?php if ($Commend=="2") echo " selected";?>>二级</option>
						  <option value="3"<?php if ($Commend=="3") echo " selected";?>>三级</option>
						  <option value="4"<?php if ($Commend=="4") echo " selected";?>>四级</option>
						  <option value="5"<?php if ($Commend=="5") echo " selected";?>>五级</option>
					  </select>
				  </td>
				  <td>
					<input class='btn' type="button" name="edit<?php echo $fa['ClassId']?>" value="<?php echo $strSave;?>" onclick="<?php echo "confirm_submit('$page_url&ClassId=$ClassId&index=$index','discsave')";?>">
				 
					<input class='btn' type="button" name="del<?php echo $fa['ClassId']?>" value="<?php echo $strDelete;?>" onclick="<?php echo "ConfirmForm('$page_url&ClassId=$ClassId&action=discdel','删除专辑同时删除此专辑下的所有歌曲吗？')";?>">&nbsp;<font color='red'>(删除专辑会同时删除此专辑下的所有歌曲)</font>
				  
				  </td>
			   </tr>
			<?php  } ?>
			  <tr>
				  <td><input name="add_ClassName" type="text" class="textbox" size="20" value="<?php echo empty($add_ClassName)?"":$add_ClassName; ?>" maxlength="20"></td>
				  <?php $add_Commend=empty($add_Commend)?"":$add_Commend; ?>
				  <td>
					  <select name="add_Commend" class="searchbox">
						  <option value="1"<?php  if ($add_Commend=="1" or $add_Commend=="") echo " selected"; ?>>一级</option>
						  <option value="2"<?php  if ($add_Commend=="2") echo " selected";?>>二级</option>
						  <option value="3"<?php  if ($add_Commend=="3") echo " selected";?>>三级</option>
						  <option value="4"<?php  if ($add_Commend=="4") echo " selected";?>>四级</option>
						  <option value="5"<?php  if ($add_Commend=="5") echo " selected";?>>五级</option>
					  </select>
				  </td>
				  <td><input class='btn' type="button" name="<?php echo $fa['ClassId']?>" value="<?php echo $strSave;?>" onclick="<?php echo "confirm_submit('$page_url','discadd')";?>"></td>
			   </tr>
		  </table>
		</div>
	<?php  } ?>
	<!--结束专辑管理-->

	<!--播放列表排序-->
	<?php  if ($action=="order") { 
		$sql="select * from {$DBPrefix}music where IsPlayList='1' order by orderNo";
		$result=$DMC->query($sql);
	?>
		<script style="javascript">
		<!--
		function onclick_update(form) {
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&action=saveorder"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php  if ($ActionMessage!="") { ?>
		  <br />
		  <fieldset>
		  <legend>
		  <?php echo $strErrorInfo?>
		  </legend>
		  <div align="center">
			<table border="0" cellpadding="2" cellspacing="1">
			  <tr>
				<td><span class="alertinfo">
				  <?php echo $ActionMessage?>
				  </span></td>
			  </tr>
			</table>
		  </div>
		  </fieldset>
		  <br />
		  <?php  } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="20%">&nbsp;歌曲名称(歌手)</td>
			  <td width="80%">歌曲地址</td>
			</tr>
		  </table>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="tbCondition">
			<?php  while($fa = $DMC->fetchArray($result)){ ?>
			<tr onClick="SelectRow(event)">
			  <td class="subcontent-td" width="20%">
				<input type="hidden" name="arrid[]" value="<?php echo $fa['MusicId']?>">
				<?php echo $fa['MusicName']." (".$fa['MusicSinger'].")"?>
			  </td>
			  <td class="subcontent-td" width="80%">
				<?php echo $fa['MusicUrl']?>
			  </td>
			</tr>
			<?php  }?>
		  </table>
		</div>

		<br>
		<div class="searchbox">
		  <input name="moveup" class="btn" type="button" id="moveup" value="<?php echo $strMoveUp?>" onClick="Move(-1,2)" disabled>
		  &nbsp;
		  <input name="movedown" class="btn" type="button" id="movedown" value="<?php echo $strMoveDown?>" onClick="Move(1,2)" disabled>
		  &nbsp;<font color=red>(<?php echo $strMoveDemo?>)</font>&nbsp;&nbsp;
		  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
		</div>
	  </div>
	<?php  } ?>
	<!--结束播放列表排序-->

</form>

<script type="text/javascript">
parent.document.all("advPlugin").style.height=document.body.scrollHeight;
</script>

</body>
</html>
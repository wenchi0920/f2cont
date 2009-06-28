<?php 
require_once("function.php");

//必须在本站操作
$server_session_id=md5("setting".session_id());
if (($_GET['action']=="save" || $_GET['action']=="restore") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=0;

if (empty($_GET['set'])) $_GET['set']=1;

if ($_GET['set']==1){
	$mtitle=$strSettingBlogs;
}else if ($_GET['set']==2){
	$mtitle=$strSettingSideBar;
}else if ($_GET['set']==3){
	$mtitle=$strSettingContent;
}else if ($_GET['set']==4){
	$mtitle=$strSettingSideCalendarSet;
}else{
	$mtitle=$strOtherSetting;
}

//输出头部信息
$title=$strGeneralSetting;
dohead($title,"");
require('admin_menu.php');

$action=$_GET['action'];

//恢复默认值
if ($action=="restore"){
	//下列值不将更新。
	$blog_desc=$settingInfo['blogTitle'];
	$blog_url=$settingInfo['blogUrl'];
	$admin_email=$settingInfo['email'];
	$language=$settingInfo['language'];
	$admin_master=$settingInfo['master'];
	$blog_name=$settingInfo['name'];
	$skinSwitch=$settingInfo['skinSwitch'];

	include("setting_default.php");
	//删除原数据
	$DMC->query("TRUNCATE TABLE ".$DBPrefix."setting");
	foreach($arr_setting as $value){
		$insert_data="insert into ".$DBPrefix."setting values".$value;
		$DMC->query($insert_data);
	}
	
	$DMC->query("update ".$DBPrefix."setting set settValue='$skinSwitch' where settName='skinSwitch'");

	settings_recount();
	settings_recache();
	header("location: setting.php?set={$_GET[set]}&update=ok");
}

//更改配置文件后，重新生成cache文件。
if (isset($_GET['update']) && $_GET['update']=="ok"){
	categories_recache();
	hottags_recache();
	archives_recache();
	links_recache();
	statistics_recache();
	recentLogs_recache();
	recentGbooks_recache();
	recentComments_recache();
	calendar_recache();
	modules_recache();

	$ActionMessage=$strSaveSuccess;
}

//删除图片
if (isset($_GET['delete']) && $_GET['delete']!=""){
	if (file_exists(F2BLOG_ROOT."attachments/{$_GET['delete']}")){
		@unlink(F2BLOG_ROOT."attachments/{$_GET['delete']}");
	}
	$DMC->query("update ".$DBPrefix."setting set settValue='' where settName='{$_GET['delete']}'") or die(mysql_error());
	settings_recache();
	header("location: setting.php");
}

//保存
$err=0;
if ($action=="save"){
	$checkinfo=true;
	
	$logo_file=$_FILES["logo"]["tmp_name"];
	$favicon_file=$_FILES["favicon"]["tmp_name"];

	//检测Logo & Favicon的尺寸
	if ($checkinfo && !check_image_type($_FILES["logo"]["name"])) {
		$checkinfo=false;
		$ActionMessage=$strLogo.$strImgTypeMemo;
	}

	if ($checkinfo && !check_image_type($_FILES["favicon"]["name"])) {
		$checkinfo=false;
		$ActionMessage=$strFavicon.$strImgTypeMemo;
	}
	
	//Save
	if ($checkinfo) {
		//上传Logo && Favicon文件
		if ($_FILES["logo"]["name"]!="") {
			$logo=upload_file($logo_file,$_FILES["logo"]["name"],"../attachments");
		}

		if ($_FILES["favicon"]["name"]!="") {
			$favicon=upload_file($favicon_file,$_FILES["favicon"]["name"],"../attachments");
		}
		
		//特殊处理
		/*$_POST['name']=encode($_POST['name']);
		$_POST['master']=encode($_POST['master']);
		$_POST['blogTitle']=encode($_POST['blogTitle']);
		$_POST['tbSiteList']=encode($_POST['tbSiteList']);
		$_POST['headcode']=encode($_POST['headcode']);
		$_POST['footcode']=encode($_POST['footcode']);		
		$_POST['registerClose']=encode($_POST['registerClose']);
		$_POST['closeReason']=encode($_POST['closeReason']);*/
		$_POST['ajaxstatus']=(count($_POST['ajaxstatus'])>0)?implode(",",$_POST['ajaxstatus']):"";
		$_POST['gcalendar']=str_replace("\r\n","",$_POST['gcalendar']);
		$_POST['ncalendar']=str_replace("\r\n","",$_POST['ncalendar']);
		$_POST['attachType']=preg_replace("/php|asp|jsp|js/is","",$_POST['attachType']);
		
		if (substr($_POST['blogUrl'],strlen($_POST['blogUrl'])-1,1)!="/"){$_POST['blogUrl']=$_POST['blogUrl']."/";}

		if ($_POST['isRegister']==0) $_POST['loginStatus']=0; //开启了用户注册，则登入也自动开启。
		if ($_POST['isRegister']==0 || $_POST['loginStatus']==0){
			$update_sql="UPDATE ".$DBPrefix."modules set isHidden='0' where name='userPanel'";
			$DMC->query($update_sql);
		}else{
			$update_sql="UPDATE ".$DBPrefix."modules set isHidden='1' where name='userPanel'";
			$DMC->query($update_sql);
		}

		//如果rewrite=2，开启了remodule，自动在cache目录下生成.htaccess文件。
		if ($_POST['rewrite']==2){
			//取得根目录
			$string_nav=substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"admin/setting.php"));

			$data=<<<eot
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteBase $string_nav
	RewriteRule ^([0-9]+)-([0-9]+)\.html$ index.php\?page=$1&disType=$2
	RewriteRule ^([0-9]+)\.html$ index.php\?page=$1
	RewriteRule ^(tags|guestbook|links|read|archives|f2bababian)\.html$ index.php\?load=$1
	RewriteRule ^read-([0-9]+)-([0-9]+)\.html$ index.php\?load=read&id=$1&page=$2
	RewriteRule ^read-([0-9]+)\.html$ index.php\?load=read&id=$1
	RewriteRule ^guestbook-([0-9]+)\.html$ index.php\?load=guestbook&page=$1
	RewriteRule ^(searchTitle|searchContent|searchAll|category|calendar|archives|tags)-(.+)-([0-9]+)-([0-9]+)\.html$ index.php\?job=$1&seekname=$2&page=$3&disType=$4
	RewriteRule ^(searchTitle|searchContent|searchAll|category|calendar|archives|tags)-(.+)-([0-9]+)\.html$ index.php\?job=$1&seekname=$2&page=$3
	RewriteRule ^(searchTitle|searchContent|searchAll|category|calendar|archives|tags)-(.+)\.html$ index.php\?job=$1&seekname=$2
	RewriteRule ^f2bababian.html$ index.php?load=f2bababian
	RewriteRule ^f2bababian-([a-z]+).html$ index.php?load=f2bababian&bbbphoto=$1
	RewriteRule ^f2bababian-([a-z]+)-([0-9]+).html$ index.php?load=f2bababian&bbbphoto=$1&page=$2
	RewriteRule ^f2bababian-([a-z]+)-([0-9]+)-([0-9A-Za-z]+).html$ index.php?load=f2bababian&bbbphoto=$1&page=$2&did=$3
	RewriteRule ^f2bababian-([a-z]+)-set-([0-9]+)-([0-9A-Za-z]+).html$ index.php?load=f2bababian&bbbphoto=$1&page=$2&setid=$3
	RewriteRule ^f2bababian-([a-z]+)-set-([0-9A-Za-z]+)-([0-9]+).html$ index.php?load=f2bababian&bbbphoto=$1&page=$3&setid=$2
	RewriteRule ^f2bababian-([a-z]+)-set-([0-9A-Za-z]+)-([0-9]+)-([0-9A-Za-z]+).html$ index.php?load=f2bababian&bbbphoto=$1&setid=$2&page=$3&did=$4
	RewriteRule ^test-(.+)\.html$ testrewrite.php\?test=$1
</IfModule>
eot;

			$fp = fopen(F2BLOG_ROOT."./cache/.htaccess", 'wbt');
			fwrite($fp, $data);
			fclose($fp);
		}
	
		//取得所有的字段值和字段名。
		$setInfoResult = $DMC->query("SELECT settName,settValue FROM ".$DBPrefix."setting where settAuto=0 and settName!='editPluginName' and settName!='editPluginButton1' and settName!='editPluginButton2' and settName!='editPluginButton3' ");
		$arr_result=$DMC->fetchQueryAll($setInfoResult);
		foreach($arr_result as $value){
			$postValue=encode($_POST[$value['settName']]);
			if ($postValue!=$value['settValue'] && $value['settName']!="logo" && $value['settName']!="favicon"){
				$update_sql="UPDATE ".$DBPrefix."setting set settValue='".$postValue."' where settName='$value[settName]'";
				//echo $update_sql."<br />";
				$DMC->query($update_sql);				
			}
			if ($value['settName']=="logo" && $logo!="") {
				$update_sql="UPDATE ".$DBPrefix."setting set settValue='".encode($logo)."' where settName='logo'";
				//echo $update_sql."<br />";
				$DMC->query($update_sql);
			}
			if ($value['settName']=="favicon" && $favicon!="") {
				$update_sql="UPDATE ".$DBPrefix."setting set settValue='".encode($favicon)."' where settName='favicon'";
				//echo $update_sql."<br />";
				$DMC->query($update_sql);
			}
		}	
		
		if ($update_sql!=""){
			settings_recache();
			header("location: setting.php?set={$_GET['set']}&update=ok");
		}

		$ActionMessage=$strSaveSuccess;
	}
}

//取得数据库中的值，用于检测该参数是否设定了值。
unset($setInfoResult);
$setDataResult=$DMC->fetchQueryAll($DMC->query("SELECT settName,settValue FROM ".$DBPrefix."setting"));
foreach($setDataResult as $key=>$value){
	$setInfoResult[$value['settName']]=$value['settValue'];
}

//调用参数包
include("setting_vars.inc.php");
?>
<form action="" method="post" enctype="multipart/form-data" name="seekform">
  <div id="content">

	  <?php if ($ActionMessage!="") { ?>
	  <div class="contenttitle"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_alert.gif" width="12" height="11">
		<?php echo $strErrorInfo?>: <font color="#FF0000"><?php echo $ActionMessage?></font>
	  </div>
	  <br />
	  <?php }?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="setting1" style="display:<?php echo ($_GET['set']==1)?"":"none"?>">
		<?php 
		foreach($SectionBlog as $value){
			echo $value;
		}
		?>
	  </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="setting2" style="display:<?php echo ($_GET['set']==2)?"":"none"?>">
		<?php 
		foreach($SectionSidebar as $value){
			echo $value;
		}
		?>
	  </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="setting2" style="display:<?php echo ($_GET['set']==3)?"":"none"?>">
		<?php 
		foreach($SectionContent as $value){
			echo $value;
		}
		?>
	  </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="setting4" style="display:<?php echo ($_GET['set']==4)?"":"none"?>">
		<?php 
		foreach($SectionCalendar as $value){
			echo $value;
		}
		?>
	  </table>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="setting3" style="display:<?php echo ($_GET['set']==5)?"":"none"?>">
		<?php 
		foreach($SectionOther as $value){
			echo $value;
		}
		?>
	  </table>
	  <div class="bottombar-onebtn">
		<input class="btn" type="submit" name="Submit" value=" <?php echo $strSaveSetting?> " onclick="javascript:seekform.action='<?php echo $_SERVER['PHP_SELF']?>?set=<?php echo $_GET['set']?>&action=save'">
		<input class="btn" type="button" name="restore" value=" <?php echo $strDefaultSetting?> " onclick="javascript:ConfirmForm('<?php echo "$PHP_SELF?action=restore"?>','<?php echo $strDefaultSettingConfirm?>')">
		<input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	  </div>

  </div>
</form>
<?php  dofoot(); ?>

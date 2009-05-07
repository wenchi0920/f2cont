<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');
?>
<div id="menuhead">
	<div id="logo">&nbsp;<img src="themes/<?php echo $settingInfo['adminstyle']?>/logo1.gif" alt=""></div>
	<div id="welcome"><?php echo $strWelcomeUser?><strong><?php echo $_SESSION['username']?></strong>.</div>
	<div id="toolsbar"><p> [<a href="index.php?action=logout" title="<?php echo $strLogout?>"><?php echo $strLogout?></a>]  [<a href="edituser.php"><?php echo $strModifyInfo?></a>]  [<a href="../index.php"><?php echo $strReturn_Index?></a>] </p></div>
</div>

<ul id="adminmenu">
<?php  
$menuTitle=array($strGentralMang,$strCategoryTag,$strLogManagement,$strLinkManagement,$strUserMang,$strSkinManagement,$strAdvancedManagement,$strDataManagement);

if ($DMC->fetchArray($DMC->query("select * from ".$DBPrefix."links where isApp='0'"))){
	$menulink=array("control.php","categories.php","logs.php","linkapp.php","users.php","skins.php","filters.php","cache.php");
}else{
	$menulink=array("control.php","categories.php","logs.php","links.php","users.php","skins.php","filters.php","cache.php");
}
$submenuTitle[0]=array($strControlPanel,$strSettingBlogs,$strSettingSideBar,$strSettingContent,$strSettingSideCalendarSet,$strOtherSetting,$strServerInfo,$strCheckUpdate,$strForumData,$strAboutF2Blog);
$submenuLink[0]=array("control.php","setting.php?set=1","setting.php?set=2","setting.php?set=3","setting.php?set=4","setting.php?set=5","sysinfo.php","checkupdate.php","myforum.php","about.php");
$submenuTitle[1]=array($strCategory,$strTag);
$submenuLink[1]=array("categories.php","tags.php");
$submenuTitle[2]=array($strLogBrowse,$strLogNew,$strCommentBrowse,$strTrackbackBrowse,$strGuestBookBrowse);
$submenuLink[2]=array("logs.php","logs.php?action=add","comments.php","trackback.php","guestbooks.php");
$submenuTitle[3]=array($strLinkManagement,$strLinksAdd,$strlinkgroupTitle,$strLinksApp);
$submenuLink[3]=array("links.php","links.php?action=add","linkgroup.php","linkapp.php");
$submenuTitle[4]=array($strUserMang,$strUserAdd,$strLogsEditAuthor);
$submenuLink[4]=array("users.php","users.php?action=add","editauthor.php");
$submenuTitle[5]=array($strSkinSetting,$strModuleSetting,$strPluginSetting);
$submenuLink[5]=array("skins.php","modules.php","plugins.php");
$submenuTitle[6]=array($strFilter,$strKeyword,$strEditorPlugins,$strStatistics);
$submenuLink[6]=array("filters.php","keywords.php","editor_plugins.php","statistics.php");
$submenuTitle[7]=array($strCache,$strAttachment,$strBackup,$strRestore,$strOptimize,$strRssExport,$strRssImport);
$submenuLink[7]=array("cache.php","attachments.php","db_backup.php","db_restore.php","db_tools.php","rss_export.php","rss_import.php");

//角色权限
$mainRoleAdmin=array("1","1","1","1","1","1","1","1");
$mainRoleEditor=array("1","1","1","1","0","0","1","0");
$mainRoleAuthor=array("1","0","1","0","0","0","0","0");
$subRoleAdmin=array("1,1,1,1,1,1,1,1,1,1","1,1","1,1,1,1,1","1,1,1,1","1,1,1","1,1,1","1,1,1,1","1,1,1,1,1,1,1");
$subRoleEditor=array("1,0,0,0,0,0,0,1,1,1","1,1","1,1,1,1,1","1,1,1,1","0,0,0","0,0,0","1,1,1,0","0,0,0,0,0,0,0");
$subRoleAuthor=array("1,0,0,0,0,0,0,1,1,1","0,0","1,1,0,0,0","0,0,0,0","0,0,0","0,0,0","0,0,0,0","0,0,0,0,0,0,0");


if ($_SESSION['rights']=="admin") {
	$curMainRole=$mainRoleAdmin;
	$curSubRole=$subRoleAdmin;
} else if ($_SESSION['rights']=="editor") {
	$curMainRole=$mainRoleEditor;
	$curSubRole=$subRoleEditor;
} else if ($_SESSION['rights']=="author") {
	$curMainRole=$mainRoleAuthor;
	$curSubRole=$subRoleAuthor;
}

foreach ($menuTitle as $index => $item) {
	if ($_SESSION['rights']=="admin" or $curMainRole[$index]=="1") {
		$class=($parentM==$index)?' class="current"':'';
		echo "\n\t<li><a href='{$menulink[$index]}'$class>{$item}</a></li>";
	}
}
?>
</ul>

<ul id="submenu">
<?php
$curSubRoleT=explode(",",$curSubRole[$parentM]);
foreach ($submenuTitle[$parentM] as $index => $item){
	if ($mtitle==$item) { $parentS=$curSubRoleT[$index]; }
	if (isset($curSubRoleT[$index]) && $curSubRoleT[$index]=="1") {
		$class=($mtitle==$item)?' class="current"':'';
		echo "\n\t<li><a href='{$submenuLink[$parentM][$index]}'$class>{$item}</a></li>";
	}
}
?>
</ul>

<?php  //检查有无权限
	if ($_SESSION['rights']!="admin" and ($curMainRole[$parentM]=="0" or $parentS=="0")) {
		$error_message=$strNoAccessVisits;
?>		
	<form action="" method="post" name="seekform">
	  <div id="content">

		<div class="contenttitle"><?php echo $strErrorInfo?></div>
		<br />
		<div class="subcontent">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-input">
			  <td><span class="alertinfo">
				<?php echo $error_message?>
				</span></td>
			</tr>
		  </table>
		</div>
	  </div>
	</form>
	<?php dofoot(); ?>
	<?php exit; }?>
<?php 
$PATH="../";
include("./function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strControlPanel;
$title=$strControlPanel;
dohead($title,"");
require('admin_menu.php');

//检测是否存在需要审核的连接
$apply_count=0;
if ($arr_result=$DMC->fetchArray($DMC->query("select count(id) as total from ".$DBPrefix."links where isApp='0'"))) {
	if ($arr_result['total']>0){
		$apply_count="<font color=\"red\">".$arr_result['total']."</font>";
	}
}

//检测是否存在需要审核的引用
$tb_count=0;
if ($arr_result=$DMC->fetchArray($DMC->query("select count(id) as total from ".$DBPrefix."trackbacks where isApp='0'"))) {
	if ($arr_result['total']>0){
		$tb_count="<font color=\"red\">".$arr_result['total']."</font>";
	}
}
?>
<body>

<div id="content">
    <div class="contenttitle"><?php echo $title?></div><br>
		<table width="98%"><tr>
		<td width="2%">
		<td width="30%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td colspan="2" class="rightbox"><strong><?php echo $strQuickStart?></strong></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='logs.php?action=add'><?php echo $strLogNew?></a></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='logs.php'><?php echo $strLogBrowse?></a></td>
			   </tr>
			   <tr>
				 <td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='trackback.php'><?php echo $strTrackbackBrowse?></a> (<?php echo $tb_count." ".$strNoApply?>)</td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='linkapp.php'><?php echo $strLinksApp?></a> (<?php echo $apply_count." ".$strNoApply?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='plugins.php'><?php echo $strPluginSetting?></a></td>
			   </tr>
			   <tr>
				 <td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='cache.php'><?php echo $strCache?></a></td>
			   </tr>
			   <tr>
				 <td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='attachments.php'><?php echo $strAttachment?></a></td>
			   </tr>
			</table>
		</td>
		<td width="3%">
		<td width="60%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td colspan="2" class="rightbox"><strong><?php echo $strF2BlogLinks?></strong></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2blog.com/' target='_blank'>F2Blog.com</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc1?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://forum.f2blog.com/' target='_blank'>F2Blog Forum</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc2?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2blog.com/faqs.php' target='_blank'>F2Blog FAQ</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc3?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2blog.com/plugins.php' target='_blank'>F2Blog Plugins</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc4?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2blog.com/skins.php' target='_blank'>F2Blog Skins</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc5?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://rss.f2blog.com/' target='_blank'>F2Blog RSS</a></td>
			   </tr>
			   <tr>
				<td colspan="2" class="rightbox"><br /><a href="checkupdate.php"><strong><?php echo $strCheckUpdate?>F2BLOG</strong></a></td>
			   </tr>
			</table>
		</td>
		</table>

</div>

<?php  dofoot(); ?>
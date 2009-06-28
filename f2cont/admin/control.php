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
				<td class="rightbox"><?php echo $strSpamTrash?>&nbsp;<a href="comments.php">(<?php echo $strCommentBrowse;?>)</a> &nbsp;<a href="guestbooks.php">(<?php echo $strGuestBookBrowse;?>)</a>  </td>
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
				<td colspan="2" class="rightbox"><strong><?php echo $strF2ContLinks?></strong></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2cont.com/' target='_blank'>F2Cont.com</a> &nbsp;&nbsp;(<?php echo $strF2ContLinkDesc1?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://bbs.f2cont.com/' target='_blank'>F2Cont Forum</a> &nbsp;&nbsp;(<?php echo $strF2ContLinkDesc2?>)</td>
			   </tr>
			   <!--
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2cont.com/faqs.php' target='_blank'>F2Cont FAQ</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc3?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2cont.com/plugins.php' target='_blank'>F2Cont Plugins</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc4?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://www.f2cont.com/skins.php' target='_blank'>F2Cont Skins</a> &nbsp;&nbsp;(<?php echo $strF2LinkDesc5?>)</td>
			   </tr>
			   <tr>
				 <td class="leftbox">&nbsp;</td>
				<td class="rightbox"><a href='http://rss.f2cont.com/' target='_blank'>F2Cont RSS</a></td>
			   </tr>
			   -->
			   <tr>
				<td colspan="2" class="rightbox"><br /><a href="checkupdate.php"><strong><?php echo $strCheckUpdate?>F2Cont</strong></a></td>
			   </tr>
			</table>
		</td>
		</table>

</div>

<?php  dofoot(); ?>
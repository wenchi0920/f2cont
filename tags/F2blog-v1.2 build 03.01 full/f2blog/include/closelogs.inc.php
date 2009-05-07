<?php 
//关闭blog显示页面
if (!defined('IN_F2BLOG')) die ('Access Denied.');

header("Content-Type: text/html; charset=utf-8");
echo "<table width=\"70%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" height=\"100px\"><tr><td>\n";
echo "<fieldset> \n";
echo "	<legend>CLOSE MY BLOG &nbsp;</legend> \n";
echo "	<div align=\"center\"> \n";
echo	$settingInfo['closeReason'];
echo "	</div> \n";
echo "	</fieldset>	 \n";
echo "</td></tr></table> \n";
exit;
?>
<?php
//F2Blog modules cache file
$arrTopModule = array(
	'home' => array(
		'modTitle' => '首頁',
		'pluginPath' => 'index.php',
		'indexOnly' => '0',
		),
	'tags' => array(
		'modTitle' => '標籤',
		'pluginPath' => 'include/tags.inc.php',
		'indexOnly' => '0',
		),
	'guestbook' => array(
		'modTitle' => '留言板',
		'pluginPath' => 'include/guestbook.inc.php',
		'indexOnly' => '0',
		),
	'論壇' => array(
		'modTitle' => '論壇',
		'pluginPath' => 'http://bbs.f2cont.com/index.html',
		'indexOnly' => '1',
		),
	'下載' => array(
		'modTitle' => '下載',
		'pluginPath' => 'http://code.google.com/p/f2cont/downloads/list',
		'indexOnly' => '1',
		),
	'links' => array(
		'modTitle' => '連結',
		'pluginPath' => 'include/links.inc.php',
		'indexOnly' => '0',
		),
	'archives' => array(
		'modTitle' => '歸檔',
		'pluginPath' => 'include/archives.inc.php',
		'indexOnly' => '0',
		),
	'rss' => array(
		'modTitle' => 'RSS',
		'pluginPath' => 'rss.php',
		'indexOnly' => '0',
		),

);

$arrSideModule = array(
	'userPanel' => array(
		'modTitle' => '用戶面板',
		'htmlCode' => 'side_userPanel()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'category' => array(
		'modTitle' => '類別',
		'htmlCode' => 'side_category()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'calendar' => array(
		'modTitle' => '日曆',
		'htmlCode' => 'side_calendar()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'hotTags' => array(
		'modTitle' => '熱門標籤',
		'htmlCode' => 'side_hotTags()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'recentlogs' => array(
		'modTitle' => '最新網誌',
		'htmlCode' => 'side_recentlogs()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'recentComments' => array(
		'modTitle' => '最新評論',
		'htmlCode' => 'side_recentComments()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'guestbook' => array(
		'modTitle' => '最新留言',
		'htmlCode' => 'side_guestbook()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'archives' => array(
		'modTitle' => '歸檔',
		'htmlCode' => 'side_archives()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'links' => array(
		'modTitle' => '連結',
		'htmlCode' => 'side_links()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),
	'statistics' => array(
		'modTitle' => '統計',
		'htmlCode' => 'side_statistics()',
		'isInstall' => '0',
		'indexOnly' => '0',
		),

);

$arrMainModule = array(

);

$arrFuncModule = array(
	'pagepost' => array(
		'modTitle' => 'Ajax分页',
		'installDate' => '1228623159',
		),
	'eMule' => array(
		'modTitle' => '将eMule[电驴]链接的发布到博客文章中来',
		'installDate' => '1241365125',
		),

);

$arrPluginName = array(
	'0' => 'pagepost',
	'1' => 'eMule',
);

?>
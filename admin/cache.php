<?php  
require_once("function.php");

//必须在本站操作
$server_session_id=md5("cache".session_id());
$_GET['action']=empty($_GET['action'])?"":$_GET['action'];
if ($_GET['action']=="operation" && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strCache;

// 保存参数
$action=$_GET['action'];

$cacheArr = array(
	'setting'	  => $strGeneralSetting,
	'members'	  => $strNickName,
	'category'  => $strCategoryTitle,
	'calendar'  => $strCalendar,
	'statistics'  => $strStatistics,
	'hotTags'     => $strHotTags,
	'archives' => $strArchives,
	'links'    => $strLinksTitle,
	'recentlogs'  => $strRecentLogs,
	'recentComments'  => $strRecentComments,
	'guestbook'  => $strRecentGBook,
	'logsTitle' => $strPubLog,
	'attachments' => $strAttachment,
	'skinlist' => $strSkinList,
	'modulesSetting' => $strModuleSetting,
	'modules'	  => $strMouduleInfo,
	'filters'  => $strFiltersTitle,
	'keywords'  => $strKeyword,
);

//更新Cache
if ($action=="operation" and $_POST['operation']=="update"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	$str="";
	for ($i=0;$i<count($itemlist);$i++){
		$curCache=$itemlist[$i];
		switch($curCache) {
			case 'setting':
				settings_recount();
				settings_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'members':
				members_recache();				
				$str.=",".$cacheArr[$curCache];
				break;
			case 'category':
				categories_recount();
				categories_recache();				
				$str.=",".$cacheArr[$curCache];
				break;
			case 'calendar':
				calendar_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'statistics':
				statistics_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'hotTags':
				hottags_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'modules':
				modules_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'archives':
				archives_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'links':
				links_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'filters':
				filters_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'keywords':
				keywords_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'recentlogs':
				recentLogs_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'recentComments':
				recentComments_recache();
				totalComments_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'guestbook':
				recentGbooks_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'logsTitle':
				logsTitle_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'modulesSetting':
				modulesSetting_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'attachments':
				download_recache();
				attachments_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'skinlist':
				skinlist_recache();
				$str.=",".$cacheArr[$curCache];
				break;
		}
	}
	$ActionMessage=substr($str,1)." $strCacheTitle$strUpdate$strSuccess";
	$action="";
}

$edit_url="$PHP_SELF";	//查找连接

if ($action=="") {
	// 查找
	$title="$strCacheTitle";

	$cachedb = array();
	foreach ($cacheArr AS $name => $desc)	{
		$filepath = F2BLOG_ROOT."cache/cache_".$name.'.php';
		if(is_file($filepath)) {
			$cachefile['name'] = $name;
			$cachefile['desc'] = $desc;
			$cachefile['size'] = formatFileSize(filesize($filepath));
			$cachefile['mtime'] = format_time("L",filemtime($filepath));
			$cachedb[] = $cachefile;
		}else{
			$cachefile['name'] = $name;
			$cachefile['desc'] = $desc;
			$cachefile['size'] = "&nbsp;";
			$cachefile['mtime'] = "&nbsp;";
			$cachedb[] = $cachefile;
		}
	}
	unset($cachefile);

	include("cache_list.inc.php");
}
?>
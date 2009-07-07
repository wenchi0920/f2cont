<?php
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

// 保存参数
$action=$_GET['action'];
$mark_id=$_GET['mark_id'];
$basedir = (isset($_GET['basedir']))?$_GET['basedir']:"../cache/"; 
if (strrpos($basedir,"/")!=strlen($basedir)-1) $basedir.="/";

$cacheArr = array(
	'setting'	  => $strGeneralSetting,
	'categories'  => $strCategoryTitle,
	'calendar'  => $strCalendar,
	'statistics'  => $strStatistics,
	'hottags'     => $strHotTags,
	'modules'	  => $strMouduleInfo,
	'archives' => $strArchives,
	'links'    => $strLinksTitle,
	'filters'  => $strFiltersTitle,
	'keywords'  => $strKeyword,
	'recentLogs'  => $strRecentLogs,
	'recentComments'  => $strRecentComments,
	'recentGbooks'  => $strRecentGBook
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
				settings_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'categories':
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
			case 'hottags':
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
			case 'recentLogs':
				recentLogs_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'recentComments':
				recentComments_recache();
				$str.=",".$cacheArr[$curCache];
				break;
			case 'recentGbooks':
				recentGbooks_recache();
				$str.=",".$cacheArr[$curCache];
				break;
		}
	}
	$ActionMessage=substr($str,1)." $strCacheTitle$strUpdate$strSuccess";
	$action="";
}

$edit_url="$PHP_SELF?order=$order";	//查找连接

if ($action=="") {
	// 查找
	$title="$strCacheTitle";

	$cachedb = array();
	foreach ($cacheArr AS $name => $desc)	{
		$filepath = $basedir."cache_".$name.'.php';
		if(is_file($filepath)) {
			$cachefile['name'] = $name;
			$cachefile['desc'] = $desc;
			$cachefile['size'] = formatFileSize(filesize($filepath));
			$cachefile['mtime'] = format_time("L",filemtime($filepath));
			$cachedb[] = $cachefile;
		}
	}
	unset($cachefile);

	include("cache_list.inc.php");
}
?>
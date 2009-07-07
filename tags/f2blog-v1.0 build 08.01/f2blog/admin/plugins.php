<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];

//保存数据
if ($action=="save"){
	$operation=$_GET['operation'];
	$plugin=$_GET['plugin'];
	$pfile=$_GET['pfile'];

	if($operation=="active") {
		include_once(ABSPATH."plugins/$plugin/$pfile");
		$ActionMessage=call_user_func($plugin."_install");
		if($ActionMessage=="") {
			$ActionMessage="$strf2Plugins <b>$plugin</b> $strActive$strSuccess!";
			modules_recache();
		} else {
			$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
		}
	} else {
		include_once(ABSPATH."plugins/$plugin/$pfile");
		
		$ActionMessage=call_user_func($plugin."_unstall");
		if($ActionMessage=="") {
			$ActionMessage="$strf2Plugins <b>$plugin</b> $strUnActive$strSuccess!";
			modules_recache();
		} else {
			$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
		}
	}

	$action="";
}

if($action=="setSave") {
	$plugin=$_GET['plugin'];
	$title="$strf2Plugins <b>$plugin</b> $strPluginSetting";
	include_once(ABSPATH."plugins/$plugin/setting.php");
	
	$modId=getFieldValue($DBPrefix."modules","name='$plugin'","id");
	$ActionMessage=call_user_func_array($plugin."_setSave",array($_POST,$modId));
	if($ActionMessage=="") {
		$ActionMessage="$strf2Plugins <b>$plugin</b> $strPluginSetting$strSuccess!";
	} else {
		$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
	}
	
	//Redirect setting
	$action="set";
}

if ($action=="set"){
	$plugin=$_GET['plugin'];
	$title="$strf2Plugins <b>$plugin</b> $strPluginSetting";
	$arr=getModSet($plugin);

	include_once("../plugins/$plugin/setting.php");
	$setCode=call_user_func($plugin."_setCode",$arr);
	if (function_exists($plugin."_fieldCheck")) {
		$fieldCheck=call_user_func($plugin."_fieldCheck");
	} else {
		$fieldCheck=array();
	}

	include("plugins_set.inc.php");
}

if ($action==""){
	//查找和浏览
	$title=($action=="install_list")?$strPluginSetting:$strPluginInstall;
    $plugins = get_plugins("../plugins/");

	//取得已安装的插件
	include("../cache/cache_modules.php");
	$actPlugins="";	
	for($i=0;$i<count($arrFuncModule);$i++) {
		if($arrFuncModule[$i]['installDate']!=0) {
			$actPlugins.=",".$arrFuncModule[$i]['name'];
		}
	}
	for($i=0;$i<count($arrMainModule);$i++) {
		if($arrMainModule[$i]['installDate']!=0) {
			$actPlugins.=",".$arrMainModule[$i]['name'];
		}
	}
	for($i=0;$i<count($arrSideModule);$i++) {
		if($arrSideModule[$i]['installDate']!=0) {
			$actPlugins.=",".$arrSideModule[$i]['name'];
		}
	}
	for($i=0;$i<count($arrTopModule);$i++) {
		if($arrTopModule[$i]['installDate']!=0) {
			$actPlugins.=",".$arrTopModule[$i]['name'];
		}
	}

	include("plugins_list.inc.php");
}
?>
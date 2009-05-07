<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=5;
$mtitle=$strPluginSetting;

//保存参数
$action=$_GET['action'];

//保存数据
if ($action=="save"){
	$operation=$_GET['operation'];
	$plugin=basename($_GET['plugin']);
	$pfile=basename($_GET['pfile']);

	if($operation=="active") {
		include_once(F2BLOG_ROOT."plugins/$plugin/$pfile");
		$ActionMessage=call_user_func($plugin."_install");
		if($ActionMessage=="") {
			$ActionMessage="$strf2Plugins <b>$plugin</b> $strActive$strSuccess!";
			modules_recache();
			modulesSetting_recache();
		} else {
			$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
		}
	} else {
		include_once(F2BLOG_ROOT."plugins/$plugin/$pfile");
		
		$ActionMessage=call_user_func($plugin."_unstall");
		if($ActionMessage=="") {
			$ActionMessage="$strf2Plugins <b>$plugin</b> $strUnActive$strSuccess!";
			modules_recache();
			modulesSetting_recache();
		} else {
			$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
		}
	}

	$action="";
}

if($action=="setSave") {
	$plugin=basename($_GET['plugin']);
	$title="$strf2Plugins <b>$plugin</b> $strPluginSetting";
	include_once(F2BLOG_ROOT."plugins/$plugin/setting.php");
	
	$modId=getFieldValue($DBPrefix."modules","name='$plugin'","id");
	$ActionMessage=call_user_func_array($plugin."_setSave",array($_POST,$modId));
	if($ActionMessage=="") {
		$ActionMessage="$strf2Plugins <b>$plugin</b> $strPluginSetting$strSuccess!";
		modulesSetting_recache();
	} else {
		$ActionMessage="$strf2Plugins <b>$plugin: </b>".$ActionMessage;
	}
	
	//Redirect setting
	$action="set";
}

if ($action=="set"){
	$plugin=basename($_GET['plugin']);
	$title="$strf2Plugins $plugin $strPluginSetting";
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

if ($action=="advset"){
	$plugin=basename($_GET['plugin']);
	$title="$strf2Plugins $plugin $strPluginSettingAdvEdit";
	
	$adv="../plugins/$plugin/advanced.php";
	include("plugins_adv.inc.php");
}

if ($action==""){
	//查找和浏览
	$title=($action=="install_list")?$strPluginSetting:$strPluginInstall;
    $plugins = get_plugins("../plugins/");

	//取得已安装的插件
	include("../cache/cache_modules.php");

	$actPlugins=",".implode(",",$arrPluginName).",";

	include("plugins_list.inc.php");
}
?>
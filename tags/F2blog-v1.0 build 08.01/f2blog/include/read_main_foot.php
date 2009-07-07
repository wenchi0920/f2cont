<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "read_main.php") {
    header("HTTP/1.0 404 Not Found");
}

//读取內容插件或模組
for ($i=0;$i<count($arrMainModule);$i++){	
	$mainname=$arrMainModule[$i]['name'];
	$maintitle=replace_string($arrMainModule[$i]['modTitle']);
	$htmlcode=$arrMainModule[$i]['htmlCode'];
	$indexOnly=$arrMainModule[$i]['indexOnly'];
	$installDate=$arrMainModule[$i]['installDate'];
	$pluginPath=$arrMainModule[$i]['pluginPath'];
	
	//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");
	if ($indexOnly==1){//所有内容尾部
		if ($installDate>0){//表示为插件
			do_filter($mainname,$mainname,$maintitle,$htmlcode);
		}else{
			main_module($mainname,$maintitle,$htmlcode);
		}
	}
	if ($indexOnly==3 && $load==""){//首页内容尾部
		if ($installDate>0){//表示为插件
			do_filter($mainname,$mainname,$maintitle,$htmlcode);
		}else{
			main_module($mainname,$maintitle,$htmlcode);
		}
	}
}
?>

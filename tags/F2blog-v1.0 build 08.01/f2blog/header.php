<?
/**
======================================================
	顶部页面: Korsen Zhang (korsenzhang@gmail.com)
	更新时间: 2006-5-23
======================================================
*/

/** 禁止直接访问该页面 */
if (basename($_SERVER['PHP_SELF']) == "header.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta http-equiv="pragma" content="no-cache"> 
<meta http-equiv="expires" content="wed, 26 Feb 1997 08:21:57 GMT"> 
<meta name="robots" content="all" />
<meta name="author" content="<?=$settingInfo['email']?>" />
<meta name="Copyright" content="<?=blogCopyright?>" />
<meta name="keywords" content="<?=blogKeywords?>" />
<meta name="description" content="<?=$settingInfo['name']?> - <?=$settingInfo['blogTitle']?>" />
<title><?=$settingInfo['name']?><?=($borwseTitle!="")?" - ".$borwseTitle:""?></title>

<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/global.css" type="text/css" media="all" />
<!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/layout.css" type="text/css" media="all" />
<!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/typography.css" type="text/css" media="all" />
<!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/link.css" type="text/css" media="all" />
<!--超链接样式表-->
<link rel="stylesheet" rev="stylesheet" href="editor/plugins/insertcode/insertcode.css" type="text/css" media="all" />
<!--Code高亮代码-->
<?if (file_exists("skins/$blogSkins/f2blog.css")){?>
<link rel="stylesheet" rev="stylesheet" href="skins/<?=$blogSkins?>/f2blog.css" type="text/css" media="all" />
<!--F2blog特有CSS-->
<?}?>
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" />
<!--F2blog共用CSS-->
<link title="rss" href="rss.php" type="application/rss+xml" rel="alternate"/>
<link rel="icon" href="<?="attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?="attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<script type="text/javascript" src="include/common.js"></script>

<!--F2blog Plugins-->
<? do_action("f2_head"); ?>
</head>
<body>
<?
//取得皮肤的信息
$getDefaultSkinInfo=getSkinInfo($blogSkins,".");

//读取flash skin
if ($getDefaultSkinInfo['UseFlash']!="0" && $getDefaultSkinInfo['FlashPath']!="" && $getDefaultSkinInfo['FlashWidth']!="" && $getDefaultSkinInfo['FlashHeight']!="" && $getDefaultSkinInfo['FlashAlign']!="" && $getDefaultSkinInfo['FlashTop']!=""){
	if (file_exists("skins/$blogSkins/".$getDefaultSkinInfo['FlashPath'])){
		echo "<div id=\"FlashHead\" style=\"text-align:".$getDefaultSkinInfo['FlashAlign'].";top:".$getDefaultSkinInfo['FlashTop']."px;\"></div> \n";
		if ($getDefaultSkinInfo['FlashTransparent']!="0"){
			echo "<script type=\"text/javascript\">WriteHeadFlash('skins/$blogSkins/".$getDefaultSkinInfo['FlashPath']."','".$getDefaultSkinInfo['FlashWidth']."','".$getDefaultSkinInfo['FlashHeight']."',true)</script> \n";
		}else{
			echo "<script type=\"text/javascript\">WriteHeadFlash('skins/$blogSkins/".$getDefaultSkinInfo['FlashPath']."','".$getDefaultSkinInfo['FlashWidth']."','".$getDefaultSkinInfo['FlashHeight']."',false)</script> \n";
		}
	}
}
?>
<div id="container">
  <!--顶部-->
  <div id="header">
    <div id="blogname">
      <?=$settingInfo['name']?>
      <div id="blogTitle">
        <?=$settingInfo['blogTitle']?>
      </div>
    </div>
    <!--顶部菜单-->
    <div id="menu">
      <div id="Left"></div>
      <div id="Right"></div>
      <ul>
		<li class="menuL"></li>
		<?
		//读取顶部栏
		for ($i=0;$i<count($arrTopModule);$i++){	
			$topid=$arrTopModule[$i]['id'];
			$topname=$arrTopModule[$i]['name'];
			$toptitle=replace_string($arrTopModule[$i]['modTitle']);
			$htmlcode=$arrTopModule[$i]['htmlCode'];
			$pluginPath=$arrTopModule[$i]['pluginPath'];
			$installDate=$arrTopModule[$i]['installDate'];
			
			if ($pluginPath=="#"){
				echo "<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\">$toptitle</a>".str_replace("&quot;","\"",dencode($htmlcode))."</li> \n";
			}else{
				if (strpos($pluginPath,".inc.php")>0 || strpos($pluginPath,".big.php")>0){
					echo "<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"index.php?load=$topname\">$toptitle</a></li> \n";
				}else{
					if ($installDate>0){//表示为插件
						do_filter($topname,$topname,$toptitle);
					}else{
						echo "<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"$pluginPath\">$toptitle</a></li> \n";
					}
				}
			}
		}
		?>        
        <li class="menuR"></li>
      </ul>
    </div>
  </div>
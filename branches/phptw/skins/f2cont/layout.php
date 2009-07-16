<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

if (!defined(SKIN_ROOT)) define(SKIN_ROOT,dirname(__FILE__));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="robots" content="all" />
<meta name="author" content="<?php echo $settingInfo['email']?>" />
<meta name="Copyright" content="<?php echo blogCopyright?>" />
<meta name="keywords" content="<?php echo blogKeywords.",".$settingInfo['blogKeyword'].$logTags?>" />
<meta name="description" content="<?php echo $settingInfo['name']?> - <?php echo $settingInfo['blogTitle']?>" />
<title><?php echo (!empty($borwseTitle))?$borwseTitle." - ":""?><?php echo $settingInfo['name']?></title>
<link rel="alternate" type="application/rss+xml" href="http://blog.phptw.idv.tw/rss.php" title="墮落程式(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="http://blog.phptw.idv.tw/atom.php" title="墮落程式(Atom)" />
<?php if (!empty($load) && $load=="read"){?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php?cateID=<?php echo $arr_array['cateId']?>" title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php?cateID=<?php echo $arr_array['cateId']?>"  title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Atom)"  />
<?php }else{?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php" title="<?php echo $settingInfo['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php" title="<?php echo $settingInfo['name']?>(Atom)" />
<?php }?>
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/global.css" type="text/css" media="all" />
<!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/layout.css" type="text/css" media="all" />
<!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/typography.css" type="text/css" media="all" />
<!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/link.css" type="text/css" media="all" />
<!--超链接样式表-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "skins/$blogSkins/f2blog.css"?>" type="text/css" media="all" />
<!--F2blog特有CSS-->
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" />
<!--F2blog共用CSS-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "$ubb_path/editor.css"?>" type="text/css" media="all" />
<!--UBB样式-->
<link rel="icon" href="<?php echo "attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo "attachments/".$settingInfo['favicon']?>" type="image/x-icon" />
<script type="text/javascript" src="include/common.js.php"></script>
<?php if (!empty($settingInfo['showAlertStyle'])){?>
<script type="text/javascript" src="editor/ubb/nicetitle.js"></script>
<?php }?>
<?php if ($settingInfo['ajaxstatus']!=""){?>
<script type="text/javascript" src="include/f2blog_ajax.js"></script>
<?php }?>
<?php  do_action("f2_head"); ?>
<?php if ($settingInfo['headcode']!="") echo str_replace("<br />","",dencode($settingInfo['headcode']));?>
</head>
<body>
<div id="container">
    <!--顶部-->
    <div id="header">
        <div id="blogname"><?php echo $settingInfo['name']?>
            <div id="blogTitle"><?php echo $settingInfo['blogTitle']?></div>
        </div>
        <!--顶部菜单-->
        <div id="menu">
            <div id="Left"></div>
            <div id="Right"></div>
            <ul>
                <li class="menuL"></li>
                <?
                $output="";
                foreach($arrTopModule as $key=>$value){
                	$topname=(is_int($key))?$value['name']:$key;
                	$toptitle=$value['modTitle'];
                	$htmlcode=$value['htmlCode'];
                	$pluginPath=$value['pluginPath'];
                	$installDate=empty($value['installDate'])?"":$value['installDate'];
                	$indexOnly=$value['indexOnly'];

                	if (in_array($topname,array("tags","guestbook","f2bababian","links","archives")) && $settingInfo['rewrite']>0){
                		if ($settingInfo['rewrite']==1) $gourl="rewrite.php/".$topname.$settingInfo['stype'];
                		if ($settingInfo['rewrite']==2) $gourl=$topname.$settingInfo['stype'];
                	}else{
                		$gourl="index.php?load=$topname";
                	}

                	if (strpos($pluginPath,".inc.php")>0 || strpos($pluginPath,".big.php")>0){
                		$output.="<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"{$gourl}\">$toptitle</a></li> \n";
                	}else{
                		if ($installDate>0){//表示为插件
                			ob_start();
                			echo '<?php do_filter("'.$topname.'","'.$topname.'","',$toptitle.'");'."?>\n";
                			$output.=ob_get_contents();
                			ob_end_clean();
                		}else{
                			$pluginPath=str_replace("&","&amp;",$pluginPath);
                			$target=($indexOnly==1)?" target=\"_blank\"":"";
                			$output.="<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"$pluginPath\"$target>$toptitle</a></li> \n";
                		}
                	}
                }
                echo $output;
				?>
                <li class="menuR"></li>
            </ul>
        </div>
    </div>
    <!--内容-->
    <div id="Tbody">
        <!--正文-->
        <div id="mainContent">
            <div id="innermainContent">
                <div id="mainContent-topimg"></div>
                <div id="Content_BlogNews" class="content-width"> </div>
                <?php 
                //include_once("include/read_main.php")


                //读取內容插件或模組
                foreach($arrMainModule as $key=>$value){
                	$mainname=$key;
                	$maintitle=$value['modTitle'];
                	$indexOnly=$value['indexOnly'];
                	$installDate=empty($value['installDate'])?"":$value['installDate'];
                	$htmlcode=$value['htmlCode'];

                	//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");
                	if ($indexOnly==0){//所有内容头部
                		if ($installDate>0){//表示为插件
                			do_filter($mainname,$mainname,$maintitle,$htmlcode);
                		}else{
                			main_module($mainname,$maintitle,$htmlcode);
                		}
                	}
                	if ($indexOnly==2 && $load==""){//首页内容头部
                		if ($installDate>0){//表示为插件
                			do_filter($mainname,$mainname,$maintitle,$htmlcode);
                		}else{
                			main_module($mainname,$maintitle,$htmlcode);
                		}
                	}
                }

                ?>
                
                
                <!--主体部分-->
                <?php include_once(F2BLOG_ROOT."./$load_file")?>
                
                
                <?php 
                //include_once("include/read_main_foot.php")

                //读取內容插件或模組
                foreach($arrMainModule as $key=>$value){
                	$mainname=$key;
                	$maintitle=$value['modTitle'];
                	$indexOnly=$value['indexOnly'];
                	$installDate=empty($value['installDate'])?"":$value['installDate'];
                	$htmlcode=$value['htmlCode'];

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
                <div id="mainContent-bottomimg"></div>
            </div>
        </div>
        <!--处理侧边栏-->
        <div id="sidebar">
            <div id="innersidebar">
                <div id="sidebar-topimg">
                    <!--工具条顶部图象-->
                </div>
                <!--侧边栏显示内容-->
                <?php 
                if ($load=="") {
                	//include_once("cache/cache_logs_sidebar.php");


                	ob_start();
                	$arrReadModule=array();

                	foreach($arrSideModule as $key=>$value){
                		$indexOnly=$value['indexOnly'];

                		if ($indexOnly==0) {
                			$arrReadModule[$key]=$value;
                		}
                		echo createSideFunc($value,$key);
                	}
                	$contents=str_replace("\r\n","",ob_get_contents());
                	ob_end_clean();
                	writetocache('logs_sidebar',$contents,"html");

                	ob_start();
                	foreach($arrReadModule as $key=>$value){
                		echo createSideFunc($value,$key);
                	}
                	$contents=str_replace("\r\n","",ob_get_contents());
                	ob_end_clean();



                } else {
                	//include_once("cache/cache_logs_readsidebar.php");


                	ob_start();
                	$arrReadModule=array();

                	foreach($arrSideModule as $key=>$value){
                		$indexOnly=$value['indexOnly'];

                		if ($indexOnly==0) {
                			$arrReadModule[$key]=$value;
                		}
                		echo createSideFunc($value,$key);
                	}
                	$contents=str_replace("\r\n","",ob_get_contents());
                	ob_end_clean();
                	writetocache('logs_sidebar',$contents,"html");

                	ob_start();
                	foreach($arrReadModule as $key=>$value){
                		echo createSideFunc($value,$key);
                	}
                	$contents=str_replace("\r\n","",ob_get_contents());
                	ob_end_clean();



                }
				?>
                <div id="sidebar-bottomimg"></div>
            </div>
        </div>
        <div style="clear: both;height:1px;overflow:hidden;margin-top:-1px;"></div>
    </div>
    <!--底部-->
    <div id="foot">
        <p> <strong><a href="mailto:<?php echo $settingInfo['email']?>"><?php echo $settingInfo['master']?></a></strong> 's blog
            Powered By <a href="http://www.f2cont.com" target="_blank"><strong>F2blog<?php echo blogVersion?></strong></a> CopyRight 2006 <?php echo (date("Y")!="2006" && date("Y")>2006)?" - ".date("Y"):""?> <a href="http://validator.w3.org/check/referer" target="_blank">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/validator-uri.html" target="_blank">CSS</a> | <a href="archives/index.php" target="_blank">Archiver</a> | <a href="googlesitemap.php" target="_blank">Sitemap</a> </p>
        <p style="font-size:11px;"> <a href="<?php echo $getDefaultSkinInfo['DesignerURL']?>" target="_blank"><strong><?php echo $getDefaultSkinInfo['SkinName']?></strong></a> 程序維護 By <a href="http://blog.phptw.idv.tw" target="_blank"><strong>墮落程式</strong></a> Design by <a href="mailto:<?php echo $getDefaultSkinInfo['DesignerMail']?>"><?php echo $getDefaultSkinInfo['SkinDesigner']?></a> Skin from <?php echo $getDefaultSkinInfo['SkinSource']?>
            <?php 
            /*if ($settingInfo['about']!="") {
            echo "<a href=\"http://www.miibeian.gov.cn\" target=\"_blank\">".$settingInfo['about']."</a>";
            }*/

            if ($settingInfo['footcode']!=""){
            	echo htmldecode($settingInfo['footcode']);
            }

            if ($settingInfo['isProgramRun']==1) {
            	$mtime = explode(' ', microtime());
            	$totaltime = number_format(($mtime[0] + $mtime[1] - $starttime), 6);
            	if ($settingInfo['gzipstatus']==1) {
            		$gzipstatus="Gzip enabled";
            	}else{
            		$gzipstatus="";
            	}
            	echo "<br /> Processed in <b>".$totaltime."</b> second(s), <b>".$DMC->querycount."</b> queries, $gzipstatus<br />\n";
            }
		?>
        </p>
    </div>
</div>
<?php  do_action("f2_footer"); ?>
<link type="text/css" rel="stylesheet" href="SyntaxHighlighter/css/SyntaxHighlighter.css">
</link>
<script language="javascript" src="SyntaxHighlighter/js/shCore.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushCSharp.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushXml.js"></script>
<script language="javascript">
dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/js/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('code');
</script>
</body>
</html>

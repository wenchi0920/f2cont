<?php include_once("../include/function.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="robots" content="all" />
<meta name="author" content="<?php echo $settingInfo['email']?>" />
<meta name="Copyright" content="<?php echo blogCopyright?>" />
<meta name="keywords" content="<?php echo blogKeywords.",".$settingInfo['blogKeyword'].$logTags?>" />
<meta name="description" content="<?php echo $settingInfo['name']?>?>" />
<title>
<?php echo $settingInfo['master']."'s blog".$strArchives?>
</title>
<link rel="stylesheet" rev="stylesheet" href="../skins/<?php echo $blogSkins?>/link.css" type="text/css" media="all" />
<!--超链接样式表-->
<style type="text/css">
	body {
		margin: 20px;
		font-family: "Tahoma", "Verdana";
		font-size: 12px;
		line-height: 150%;
	}
	li {
		font: 12px "Tahoma", "Verdana";
		line-height: 180%;
	}
	div {
		font: 12px "Tahoma", "Verdana";
	}
	ul {
		margin: 10px;
		list-style-type: none
	}
	.comminfo {
		color:#CCCCCC;
	}
</style>
</head>
<body>
<!--内容-->
<div id="Tbody">
  <div id="container">
    <?php 
	//导航
	if ($settingInfo['rewrite']==0) {
		$readlogs_url="../index.php?load=read&amp;id=";
		$archives_url="../index.php?load=archives";
	}
	if ($settingInfo['rewrite']==1) {
		$readlogs_url="../rewrite.php/read-";
		$archives_url="../rewrite.php/archives".$settingInfo['stype'];
	}
	if ($settingInfo['rewrite']==2) {
		$readlogs_url="../read-";
		$archives_url="../archives".$settingInfo['stype'];
	}
	?>
    <div>
      <div style="float:right"><b><a href="../"><?php echo $strHomePageTitle?></a></b></div>
	  <b><a href="../index.php"><?php echo $settingInfo['master']?>'s blog</a> &raquo; <a href="<?php echo $archives_url?>"><?php echo $strArchivesMonth?></a></b>
	</div>
    <hr />
	<?php 
	$query_sql="select id,logTitle,postTime,commNums,viewNums from ".$DBPrefix."logs where saveType=1 order by postTime desc";
	$query_result=$DMC->query($query_sql);
	$arr_array=$DMC->fetchQueryAll($query_result);
	$lastmonth="";
	foreach($arr_array as $key=>$article){
		list($year,$month,$day) = explode(",",format_time("Y,m,d",$article['postTime']));

		$currmonth="$year $strYear $month $strMonth";
		if ($lastmonth!=$currmonth && $key==0) {
			echo "<h4>$currmonth</h4><ul>\n";
			$lastmonth=$currmonth;
		}
		if ($lastmonth!=$currmonth && $key>0) {
			echo "</ul><h4>$currmonth</h4><ul>\n";
			$lastmonth=$currmonth;
		}
		echo "<li>$month/$day : <a href=\"".$readlogs_url.$article['id'].$settingInfo['stype']."\">".dencode($article['logTitle'])."</a>  <span class=\"comminfo\">($strLogRead:{$article['viewNums']} $strLogComm:{$article['commNums']})</span></li> \n";
	}
	?>
  </div>
  <!--底部-->
  <hr />
  <div id="foot">
    <p> <strong><a href="mailto:<?php echo $settingInfo['email']?>">
      <?php echo $settingInfo['master']?>
      </a></strong> 's blog
      Powered By <a href="http://www.f2blog.com" target="_blank"><strong>F2blog v
      <?php echo blogVersion?>
      </strong></a> CopyRight 2006
      <?php echo (date("Y")!="2006" && date("Y")>2006)?" - ".date("Y"):""?>
    </p>
  </div>
</div>
</body>
</html>

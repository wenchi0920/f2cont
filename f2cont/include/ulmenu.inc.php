<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

if ($settingInfo['rewrite']==0){
	$gourl="index.php?job=category&amp;seekname=";
}
if ($settingInfo['rewrite']==1){
	$gourl="rewrite.php/category-";
}
if ($settingInfo['rewrite']==2){
	$gourl="category-";
}

if ($private_count>0){
	echo "<?php if (!empty(\$_SESSION['rights']) && \$_SESSION['rights']==\"admin\"){?>\n";
	echo "<img src=\"images/icons/19.gif\" align=\"top\" alt=\"\"/> <a href=\"index.php?job=private&amp;seekname=private\">".$strPrivateLog."</a>(".$private_count.") &nbsp;<span class=\"rss\"><a href='rss.php?cateID=private'>[RSS]</a></span><br /> \n";
	echo "<?php }?>\n";
}
foreach($arr_parent as $i=>$value){
	$cate_url=($value['outLinkUrl'])?$value['outLinkUrl']:$gourl.$value['id'].$settingInfo['stype'];

	echo "<a class=\"sideA\" href=\"$cate_url\">".$value['name']." (".$value['cateCount'].")</a>\n";
	for ($j=0;$j<count($arr_sub[$i]);$j++){
		$cate_url=($arr_sub[$i][$j]['outLinkUrl'])?$arr_sub[$i][$j]['outLinkUrl']:$gourl.$arr_sub[$i][$j]['id'].$settingInfo['stype'];

		echo "<a class=\"sideA\" style=\"margin-left:18px;\" href=\"$cate_url\">".$arr_sub[$i][$j]['name']." (".$arr_sub[$i][$j]['cateCount'].")</a> \n";
	}
}
?>
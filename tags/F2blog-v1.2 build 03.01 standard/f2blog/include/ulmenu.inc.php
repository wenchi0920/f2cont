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

	echo "<img src=\"images/icons/".$value['cateIcons'].".gif\" align=\"top\" alt=\"\"/> <a href=\"$cate_url\">".$value['name']."</a>(".$value['cateCount'].") &nbsp;<span class=\"rss\"><a href='rss.php?cateID=".$value['id']."'>[RSS]</a></span><br />\n";
	for ($j=0;$j<count($arr_sub[$i]);$j++){
		$cate_url=($arr_sub[$i][$j]['outLinkUrl'])?$arr_sub[$i][$j]['outLinkUrl']:$gourl.$arr_sub[$i][$j]['id'].$settingInfo['stype'];

		echo "<img src=\"images/icons/".$arr_sub[$i][$j]['cateIcons'].".gif\" align=\"top\" style=\"margin-left:18px;\" alt=\"\"/> <a href=\"$cate_url\">".$arr_sub[$i][$j]['name']."</a>(".$arr_sub[$i][$j]['cateCount'].") &nbsp;<span class=\"rss\"><a href='rss.php?cateID=".$arr_sub[$i][$j]['id']."'>[RSS]</a></span><br /> \n";
	}
}
?>
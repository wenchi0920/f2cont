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
?>
<script type="text/javascript">
	function openCategory(category) {
		var oLevel1 = document.getElementById("category_" + category);
		var oImg = oLevel1.getElementsByTagName("img")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case "isleaf":
				return true;
			case "closed":
				oImg.src = "<?php echo "$settingInfo[categoryImgPath]/tab_opened.gif"?>";
				showLayer("category_" + category + "_children");
				expanded = true;
				return true;
			case "opened":
				oImg.src = "<?php echo "$settingInfo[categoryImgPath]/tab_closed.gif"?>";
				hideLayer("category_" + category + "_children");
				expanded = false;
				return true;
		}
		return false;
	}
	
	function showLayer(id) {
		document.getElementById(id).style.display = "block";
		return true;
	}
	
	function hideLayer(id) {
		document.getElementById(id).style.display = "none";
		return true;
	}	
</script>

<div id="treeComponent">
  <div id="category_0" style="line-height: 100%">
    <div style="display:inline;"><img src="<?php echo "$settingInfo[categoryImgPath]/tab_top.gif"?>" width="16" align="top" alt=""/></div>
    <div style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;">
      <a href='index.php'><?php echo $strAllCategory?> (<?php echo "<?php echo (!empty(\$_SESSION['rights']) && \$_SESSION['rights']==\"admin\")?\"".($sum_total+$private_count)."\":\"".$sum_total."\"?>\n"?>)</a> <span class="rss"><a href='rss.php'>[RSS]</a></span></div>
  </div>
<?php 
//隐私日志
if ($private_count>0){
  echo "<?php if (!empty(\$_SESSION['rights']) && \$_SESSION['rights']==\"admin\"){?>\n";
?>
  <div id="category_private" style="line-height: 100%">
    <div style="display:inline; background-image: url('<?php echo $settingInfo['categoryImgPath']?>/navi_back_noactive.gif')"><a class="click"><img src="<?php echo $settingInfo['categoryImgPath']?>/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="">
      <div id="text_7" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='index.php?job=private&amp;seekname=private'><?php echo $strPrivateLog?>(<?php echo $private_count?>)</a> <span class="rss"><a href='rss.php?cateID=private'>[RSS]</a></span></div>
    </div>
  </div>
<?php 
  echo "<?php }?>\n";
}
?>
<?php 
$treeOption=($settingInfo['treeCategory']==0)?"tab_closed.gif":"tab_opened.gif";
foreach($arr_parent as $i=>$value){
	if (!empty($arr_sub[$i][0]) && $arr_sub[$i][0]!=""){		
?>
  <div id="category_<?php echo $i+1?>" style="line-height: 100%">
    <div style="display:inline;background-image: url('<?php echo ($i<count($arr_parent)-1)?"$settingInfo[categoryImgPath]/navi_back_noactive.gif":"$settingInfo[categoryImgPath]/navi_back_noactive_end.gif"?>')"><a class="click" onclick="openCategory('<?php echo $i+1?>')"><img src="<?php echo "$settingInfo[categoryImgPath]/$treeOption"?>" align="top" alt=""/></a></div>
    <div style="display:inline;" title="<?php echo $value['cateTitle']?>">
        <div id="text_<?php echo $i+1?>" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='<?php echo ($value['outLinkUrl'])?$value['outLinkUrl']:$gourl.$value['id'].$settingInfo['stype']?>'><?php echo $value['name']?>
		(<?php echo $value['cateCount']?>)</a> <span class="rss"><a href='rss.php?cateID=<?php echo $value['id']?>'>[RSS]</a></span></div>
    </div>
  </div>
  <?php }else{?>
  <div id="category_category_<?php echo $i+1?>" style="line-height: 100%">
    <div style="display:inline; background-image: url('<?php echo ($i<count($arr_parent)-1)?"$settingInfo[categoryImgPath]/navi_back_noactive.gif":"$settingInfo[categoryImgPath]/navi_back_noactive_end.gif"?>')"><a class="click"><img src="<?php echo "$settingInfo[categoryImgPath]/tab_isleaf.gif"?>" align="top" alt=""/></a></div>
    <div style="display:inline;" title="<?php echo $value['cateTitle']?>">
      <div id="text_<?php echo $i+1?>" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='<?php echo ($value['outLinkUrl'])?$value['outLinkUrl']:$gourl.$value['id'].$settingInfo['stype']?>'><?php echo $value['name']?>
	  (<?php echo $value['cateCount']?>)</a> <span class="rss"><a href='rss.php?cateID=<?php echo $value['id']?>'>[RSS]</a></span></div>
    </div>
  </div>
  <?php }?>
  <?php if (count($arr_sub[$i])>0){?>
  <div id="category_<?php echo $i+1?>_children" style="display:<?php echo ($settingInfo['treeCategory']==0)?"none":""; ?>">
    <?php for ($j=0;$j<count($arr_sub[$i]);$j++){?>
    <div id="subcategory_<?php echo $i.$j?>" style="line-height: 100%">
      <div style="display:inline;"><img src="<?php echo ($i<count($arr_parent)-1)?"$settingInfo[categoryImgPath]/navi_back_active.gif":"$settingInfo[categoryImgPath]/navi_back_noactive_end.gif"?>" width="17" align="top" alt=""/><?php if ($j==count($arr_sub[$i])-1){?><img src="<?php echo "$settingInfo[categoryImgPath]/tab_treed_end.gif"?>" width="22" align="top" alt=""/><?php }else{?><img src="<?php echo "$settingInfo[categoryImgPath]/tab_treed.gif"?>" width="22" align="top" alt=""/><?php }?></div>
      <div style="display:inline;" title="<?php echo $arr_sub[$i][$j]['cateTitle']?>">
        <div id="text_<?php echo $i.$j?>" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='<?php echo ($arr_sub[$i][$j]['outLinkUrl'])?$arr_sub[$i][$j]['outLinkUrl']:$gourl.$arr_sub[$i][$j]['id'].$settingInfo['stype']?>'><?php echo $arr_sub[$i][$j]['name']?>
		(<?php echo $arr_sub[$i][$j]['cateCount']?>)</a> <span class="rss"><a href='rss.php?cateID=<?php echo $arr_sub[$i][$j]['id']?>'>[RSS]</a></span></div>
      </div>
    </div>
    <?php  }//end sub ?>
  </div>
<?php }}//end parent?>
</div>

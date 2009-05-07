<?php if($GoBind->photogetUserTagList($bbb_user_id)) {?>
<hr />
<div class="bbb_keyword">
  <?php
	  foreach($GoBind->usertag_list as $keyname=>$values){									  			  
		echo "<a href=\"http://www.bababian.com/searchme/tag/".urlencode($values['tag'])."/1/$bbb_user_id\" target=\"_blank\">".$values['tag']." (".$values['count'].")</a> &nbsp;&nbsp;\n";		
	  }
	  ?>
</div>
<?php		
}else{	
	echo("<hr />获取关键字失败! ");
	echo($GoBind->fault[0]."<br />");
	echo($GoBind->fault[1]."<br />"); //显示错误相关信息		
}
?>
<br />
<iframe src="plugins/f2bababian/photo_search.php?blogSkins=<?php echo $blogSkins?>" width="100%" height="300px"></iframe>
<div align="right" style="height:52px; width:100%; overflow:hidden;margin-top:40px;"><a href="http://www.bababian.com" target="_blank"><img border="0" src="http://www.bababian.com/51ly/bbb.gif" /></a></div>

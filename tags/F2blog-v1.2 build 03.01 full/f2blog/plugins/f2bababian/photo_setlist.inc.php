<?php
if ($settingInfo['rewrite']==0) {
	$gourl="index.php?load=$load&amp;bbbphoto=$bbbphoto";
	$pageurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;page=$page&amp;setid=";
}
if ($settingInfo['rewrite']==1) {
	$gourl="rewrite.php/$load-$bbbphoto";
	$pageurl="rewrite.php/$load-$bbbphoto-set-$page-";
}
if ($settingInfo['rewrite']==2) {
	$gourl="$load-$bbbphoto";
	$pageurl="$load-$bbbphoto-set-$page-";
}

if($GoBind->photogetSetList($bbb_user_id,$page,$bbb_per_page)) {	
	$per_page=$bbb_per_page;
	$total_num=$GoBind->photo_total;
?>
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:left">
			<?php pageBar($gourl); ?>
		  </div>
		</div>
<?php
	$j=0;
	echo "<ul class=\"bbb_show\"> \n";
	foreach($GoBind->set_list as $key=>$value){
		$j++;				
		if ($j>$bbb_per_row){
			echo "</ul> \n <ul class=\"bbb_show\">\n";
			$j=1;
		}
		?>
		  <li class="bbb_pho">
				<div class="bbb_setimg"> <a href="<?php echo $pageurl.$value["setid"].$settingInfo['stype']?>" title="上载于<?php echo $value["date"]?>&nbsp;<?php echo $value["settitle"]?>"> <img src="<?php echo $value["photo"]?>" border="0" class="photoBorder" alt="<?php echo $value["settitle"]?>"/> </a></div>
				<div class="bbb_subtitle"><?php echo $value['settitle']?>(<?php echo $value['count']?>)</div>
		  </li>
		<?php
	}
	echo "</ul> \n";	 
	?>
	<ul class="bbb_show"></ul>
	<div style="text-align:center;clear:both;" align="center">当前显示[<?php echo count($GoBind->set_list)?>]个专辑，共[<?php echo $GoBind->photo_total?>]个专辑</div>
	<div>
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:right">
			<?php pageBar($gourl); ?>
		  </div>
		</div>
		<div align="right" style="height:52px; width:100%; overflow:hidden;margin-top:40px;"><a href="http://www.bababian.com" target="_blank"><img border="0" src="http://www.bababian.com/51ly/bbb.gif" alt=""/></a></div>
	</div>
<?php		
}else{	
	echo("<hr />获取专辑失败! ");
	echo($GoBind->fault[0]."<br />");
	echo($GoBind->fault[1]."<br />"); //显示错误相关信息		
}
?>
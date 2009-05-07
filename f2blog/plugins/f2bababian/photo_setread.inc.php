<?php
if ($settingInfo['rewrite']==0) {
	$gourl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;setid={$_GET['setid']}";
	$pageurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;setid={$_GET['setid']}&amp;page=$page&amp;did=";
}
if ($settingInfo['rewrite']==1) {
	$gourl="rewrite.php/$load-$bbbphoto-set-{$_GET['setid']}";
	$pageurl="rewrite.php/$load-$bbbphoto-set-{$_GET['setid']}-$page-";
}
if ($settingInfo['rewrite']==2) {
	$gourl="$load-$bbbphoto-set-{$_GET['setid']}";
	$pageurl="$load-$bbbphoto-set-{$_GET['setid']}-$page-";
}

if($GoBind->photogetSetPhotoList($_GET['setid'],$page,$bbb_per_page)) {	
//if($GoBind->photogetSetPhotoList("3EC67F9B05341EC65200A7E63D191918DS","1","10")) {	
	$per_page=$bbb_per_page;
	$total_num=$GoBind->photo_total;
	
	//专辑说明
?>
	<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
	  <div class="page" style="float:left">
		<? pageBar($gourl);?>
	  </div>
	</div>
	<div class="comment" style="margin-bottom:20px">
		<div class="commenttop"> <img src="plugins/f2bababian/images/note.gif" border="0" style="margin:0px 1px -3px 0px"/>&nbsp;<b><?php echo $GoBind->setphoto_list_info['settitle']?> (<?php echo $GoBind->setphoto_list_info['view']?>)</b></div>
		<div class="commentcontent">
			<?php echo $GoBind->setphoto_list_info['description']?>
		</div>
	</div>
<?php
	$arr_did=array();
	$j=0;
	echo "<ul class=\"bbb_show\"> \n";
	foreach($GoBind->setphoto_list as $key=>$value){
		$arr_did[]=$value['did'];
		$j++;				
		if ($j>$bbb_per_row){
			echo "</ul> \n <ul class=\"bbb_show\">\n";
			$j=1;
		}
		//转换图片显示
		if ($bbb_size!="75") $value["src"]=str_replace("_75","_$bbb_size",$value["src"]);
		?>
		  <li class="bbb_pho">
			<div class="bbb_img"> <a href="<?php echo $pageurl.$value["did"].$settingInfo['stype']?>" title="上载于<?php echo $value["date"]?>&nbsp;<?php echo $value["title"]?>"> <img src="<?php echo $value["src"]?>" border="0" class="photoBorder" alt="<?php echo $value["title"]?>"/> </a></div>
		  </li>
		<?php
	}
	$_SESSION['session_photo_list']=$arr_did;
	echo "</ul> \n";		 
	?>
	<ul class="bbb_show"></ul>
	<div style="text-align:center;clear:both;" align="center">当前显示[<?php echo count($GoBind->setphoto_list)?>]幅图，共[<?php echo $GoBind->photo_total?>]幅图</div>
	<div>
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:right">
			<? pageBar($gourl);?>
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
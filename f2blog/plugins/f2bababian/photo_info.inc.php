<?php
if ($settingInfo['rewrite']==0) {
	$gourl="index.php?load=$load&amp;bbbphoto=$bbbphoto";
	if (!empty($_GET['setid'])){
		$pageurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;page=$page&amp;setid={$_GET['setid']}&amp;did=";
		$listurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;page=$page&amp;setid={$_GET['setid']}";
	}else{
		$pageurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;page=$page&amp;did=";
		$listurl="index.php?load=$load&amp;bbbphoto=$bbbphoto&amp;page=$page";
	}
}
if ($settingInfo['rewrite']==1) {
	$gourl="rewrite.php/$load-$bbbphoto";
	if (!empty($_GET['setid'])){
		$pageurl="rewrite.php/$load-$bbbphoto-set-{$_GET['setid']}-$page-";
		$listurl="rewrite.php/$load-$bbbphoto-set-{$_GET['setid']}-$page".$settingInfo['stype'];
	}else{
		$pageurl="rewrite.php/$load-$bbbphoto-$page-";
		$listurl="rewrite.php/$load-$bbbphoto-$page".$settingInfo['stype'];
	}
}
if ($settingInfo['rewrite']==2) {
	$gourl="$load-$bbbphoto";
	if (!empty($_GET['setid'])){
		$pageurl="$load-$bbbphoto-set-{$_GET['setid']}-$page-";
		$listurl="$load-$bbbphoto-set-{$_GET['setid']}-$page".$settingInfo['stype'];
	}else{
		$pageurl="$load-$bbbphoto-$page-";
		$listurl="$load-$bbbphoto-$page".$settingInfo['stype'];
	}
}

if($GoBind->photogetPhotoInfo($_GET['did'],$page,$bbb_per_page)) {
//if($GoBind->photogetPhotoInfo("AF84A4FA341D014B2935A88F69B842EADT",$page,$bbb_per_page)) {
	//取得上下一张图片DID
	if ($_SESSION['session_photo_list']!=""){
		$cur_position=array_search($_GET['did'],$_SESSION['session_photo_list']);
		if ($cur_position>1 && $_SESSION['session_photo_list'][$cur_position-1]!=""){				
			$previous_images="$pageurl".$_SESSION['session_photo_list'][$cur_position-1].$settingInfo['stype'];
		}
		if ($_SESSION['session_photo_list'][$cur_position+1]!=""){				
			$next_images="$pageurl".$_SESSION['session_photo_list'][$cur_position+1].$settingInfo['stype'];
		}
	}

	$arr_exif=$GoBind->photo_info["exif"];
	$photo=$GoBind->photo_info["src"];
	$photo1=str_replace("_500","_75",$photo);
	$photo2=str_replace("_500","_100",$photo);
	$photo3=str_replace("_500","_240",$photo);
	$photo4=str_replace("_500","_800",$photo);
	$photo5=str_replace("_500","",$photo);
?>
	<div class="bbb_title"> <?php echo $GoBind->photo_info['title']?></div>
		<div class="bbb_tools">
			<a href="Javascript:switch_image('<?php echo $photo1?>')">图标(75)</a> &nbsp;
			<a href="Javascript:switch_image('<?php echo $photo2?>')">较小(100)</a> &nbsp;
			<a href="Javascript:switch_image('<?php echo $photo3?>')">极小(240)</a> &nbsp;
			<a href="Javascript:switch_image('<?php echo $photo?>')">适中(500)</a> &nbsp;
			<a href="Javascript:switch_image('<?php echo $photo4?>')">较大(800)</a> &nbsp;
			<a href="Javascript:switch_image('<?php echo $photo5?>')">原始尺寸</a> &nbsp;
			<?php if (!empty($previous_images)){?><a href="<?php echo $previous_images?>">上一张</a> &nbsp;<?php }?>
			<a href="<?php echo $listurl?>">列表</a> &nbsp;
			<?php if ($next_images){?><a href="<?php echo $next_images?>">下一张</a> &nbsp;<?php }?>
		</div>
		<div class="bbb_readimg"><span class="bbb_subtitle">(上传于: <?php echo $GoBind->photo_info["date"]?>)</span> <br /> <img id="photopath" src="<?php echo $photo?>" border="0" class="photoBorder" alt="<?php echo $GoBind->photo_info["title"]?>"/></div>
		<?php if ($GoBind->photo_info["description"]){?>
		<div class="comment" style="margin-bottom:20px">
			<div class="commenttop"> <img src="plugins/f2bababian/images/note.gif" border="0" style="margin:0px 1px -3px 0px" alt=""/>&nbsp;<b>照片说明</b></div>
			<div class="commentcontent"><?php echo $GoBind->photo_info["description"]?></div>
		</div>
		<?php }?>
<?php
	//图片exif信息
	if ($arr_exif=$GoBind->photo_info["exif"]){
?>
		  <div class="comment" style="margin-bottom:20px">
			<div class="commenttop"> <img src="plugins/f2bababian/images/exif.gif" border="0" style="margin:0px 1px -3px 0px" alt=""/>&nbsp;<b><span style="cursor:pointer;" onclick="Javascript:open_exif('exif')">照片Exif信息 (单击展开查看）</span></b></div>
			<div class="commentcontent">
				<TABLE width="100%" cellSpacing="0" id="exif" style="display:none;" class="Separated">
				  <TR>
					<TD width="6%" class="Separated">&nbsp;</TD>
					<TD width="40%" class="Separated"><B>相机型号:</B></TD>
					<TD width="54%" class="Separated"><?php echo $arr_exif['camera']?></TD>
				  </TR>
				  <TR>
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>快门速度:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['exposure']?></TD>
				  </TR>
				  <TR>
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>光圈:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['aperture']?></TD>
				  </TR>
				  <TR>
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>焦距:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['focalLength']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>ISO速度:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['isoSpeed']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>曝光补偿:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['exposureBias']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>闪光灯模式:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['orientation']?></TD>
				  </TR>
				  <TR height="25">
					<TD width="6%" class="Separated">&nbsp;</TD>
					<TD width="40%" class="Separated"><B>方位:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['flash']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>水平分辨率:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['xResolution']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>垂直分辨率:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['yResolution']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>拍摄时间:</B></TD>
					<TD class="Separated"><?php echo $arr_exif['dateAndTime']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>日期与时间(原始):</B></TD>
					<TD class="Separated"><?php echo $arr_exif['dateAndTimeOriginal']?></TD>
				  </TR>
				  <TR height="25">
					<TD class="Separated">&nbsp;</TD>
					<TD class="Separated"><B>日期与时间(处理):</B></TD>
					<TD class="Separated"><?php echo $arr_exif['dateAndTimeDigitized']?></TD>
				  </TR>
				</table>
			</div>
		  </div>
<?php
	}

	//引用信息
?>
	<div class="comment" style="margin-bottom:20px">
		<div class="commenttop"> <img src="images/From.gif" border="0" alt="" style="margin:0px 1px -3px 0px"/>&nbsp;<b>照片引用通告地址</b></div>
		<div class="commentcontent">
			<input onclick="this.select();" style="width:100%" class="userpass" type="text" value="http://www.bababian.com/ptb/<?php echo $_GET['did']?>/UTF-8">
			引用时请将最后一位参数（默认为UTF-8）修改为所使用博客的编码格式，否则会出现乱码。
		</div>
	</div>
<?php

	//读取关键字信息
	if (count($GoBind->photo_info_keyword)>0){
?>
		  <div class="comment" style="margin-bottom:20px">
			<div class="commenttop"> <img src="images/keyword.gif" border="0" alt=""/>&nbsp;<b>关键字</b></div>
			<div class="commentcontent">
			  <?php
			  foreach($GoBind->photo_info_keyword as $keyname=>$values){									  			  
				echo "<a href=\"http://www.bababian.com/searchme/tag/".urlencode($values)."/1/$bbb_user_id\" target=\"_blank\">$values</a> &nbsp;&nbsp;\n";		
			  }
			  ?>
			</div>
		  </div>
<?php
	}

	//读取评论信息				
	$per_page=$bbb_per_page;
	$total_num=$GoBind->reply_total;
	if ($total_num>0){
		$pageurl=$pageurl.$_GET['did'].$settingInfo['stype'];
		foreach($GoBind->photo_info_reply as $key=>$value){
?>
		  <div class="comment" style="margin-bottom:20px">
			<div class="commenttop"> <img src="images/icon_quote.gif" border="0" style="margin:0px 1px -3px 0px" alt=""/><b><a href="http://www.bababian.com/photo/<?php echo $value['userID']?>" target="_blank"><?php echo $value['username']?></a></b> <span class="commentinfo">[<?php echo $value[date]?>]</span></div>
			<div class="commentcontent"><img src="<?php echo $value['icon']?>" align="middle" alt=""/>&nbsp;&nbsp;&nbsp;<?php echo $value['content']?></div>
		  </div>	
		  <?php }?>
		  <div>
			  <div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
				  <div class="page" style="float:right">
					<?php pageBar($gourl); ?>
				  </div>
			  </div>			  
		  </div>
<?php
	}//结束评论
?>
	<div align="right" style="height:52px; width:100%; overflow:hidden;margin-top:40px;"><a href="http://www.bababian.com" target="_blank"><img border="0" src="http://www.bababian.com/51ly/bbb.gif" alt=""/></a></div>
<?php
}else{		
	echo("<hr />获取一张照片的详细信息失败!");
	echo("错误代号: ".$GoBind->fault[1]."<br />");
	echo("错误说明: ".$GoBind->fault[0]."<br />");	
}
?>
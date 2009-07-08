
/*
Plugin Name: BlogNews
Plugin URI: http://blog.phptw.idv.tw/read-100.html
Description: 跑馬燈
Author: wenchi
Version: 1.0
Author URI: http://blog.phptw.idv.tw
*/

INSTALL


1.
執行　資料庫語法
ALTER TABLE `f2blog_logs` ADD `isTopNews` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `isTop` ;


2.
開啟 admin/log.php

找到
$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',weather='$weather',saveType='$saveType',tags='$tags',logsediter='$logsediter',password='$addpassword',autoSplit='$autoSplit'$edit_sql where id='$mark_id'";
將他取代成
$sql="update ".$DBPrefix."logs set cateId='$cateId',logTitle='$logTitle',logContent='$logContent',isComment='$isComment',isTrackback='$isTrackback',isTop='$isTop',isTopNews='$isTopNews',weather='$weather',saveType='$saveType',tags='$tags',logsediter='$logsediter',password='$addpassword',autoSplit='$autoSplit'$edit_sql where id='$mark_id'";


3.
開啟 admin/log.php

找到
$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter,autoSplit) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$weather','$saveType','$tags','$addpassword','$logsediter','$autoSplit')";
將他取代成
$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,isTopNews,weather,saveType,tags,password,logsediter,autoSplit) VALUES ('$cateId','$logTitle','$logContent','$author','$quoteUrl','$postTime','$isComment','$isTrackback','$isTop','$isTopNews','$weather','$saveType','$tags','$addpassword','$logsediter','$autoSplit')";



4.
開啟 admin/log.php

找到
	//禁止引用
	if($_POST['operation']=="tbhidden" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTrackback='0' where $stritem";
		$DMC->query($sql);
	}
將他取代成
	//禁止引用
	if($_POST['operation']=="tbhidden" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTrackback='0' where $stritem";
		$DMC->query($sql);
	}
	
	//	START ADD from WenChi  @plugins BlogNews
	if($_POST['operation']=="setTopNews" and $stritem!=""){
		$sql="update ".$DBPrefix."logs set isTopNews='".intval($_POST['isTopNews'])."' where $stritem";
		$DMC->query($sql);
	}	
	//	END ADD from WenChi  @plugins BlogNews


5.
開啟 admin/log.php

找到
	if ($dataInfo) {
		$cateId=$dataInfo['cateId'];
		$oldCateId=$dataInfo['cateId'];
		$logTitle=empty($logTitle)?$dataInfo['logTitle']:$logTitle;
		$logContent=empty($logContent)?$dataInfo['logContent']:$logContent;
		$author=$dataInfo['author'];
		$quoteUrl=$dataInfo['quoteUrl'];
		$isComment=$dataInfo['isComment'];
		$isTrackback=$dataInfo['isTrackback'];
		$isTop=$dataInfo['isTop'];
		$weather=$dataInfo['weather'];
		$saveType=$dataInfo['saveType'];
		$tags=$dataInfo['tags'];
		$oldTags=$dataInfo['tags'];
		$addpassword=$dataInfo['password'];
		$autoSplit=$dataInfo['autoSplit'];

將他取代成
	if ($dataInfo) {
		$cateId=$dataInfo['cateId'];
		$oldCateId=$dataInfo['cateId'];
		$logTitle=empty($logTitle)?$dataInfo['logTitle']:$logTitle;
		$logContent=empty($logContent)?$dataInfo['logContent']:$logContent;
		$author=$dataInfo['author'];
		$quoteUrl=$dataInfo['quoteUrl'];
		$isComment=$dataInfo['isComment'];
		$isTrackback=$dataInfo['isTrackback'];
		$isTop=$dataInfo['isTop'];
		$weather=$dataInfo['weather'];
		$saveType=$dataInfo['saveType'];
		$tags=$dataInfo['tags'];
		$oldTags=$dataInfo['tags'];
		$addpassword=$dataInfo['password'];
		$autoSplit=$dataInfo['autoSplit'];
		
		//	SATRT ADD From WenChi
		$isTopNews=$dataInfo['isTopNews'];
		//	END ADD From WenChi


6.
開啟 admin/logs_add.inc.php

找到
		<tr>
		  <td nowrap>
			<?php echo $strPareams?>
		  </td>
		  <td colspan="3">
		    <input type="radio" name="saveType" value="1" <?php echo ($saveType==1)?"checked=\"checked\"":""?>>  <?php echo $strPubLog?>
			<input type="radio" name="saveType" value="0" <?php echo ($saveType==0)?"checked=\"checked\"":""?>>  <?php echo $strDraft?>		    
		    <input type="radio" name="saveType" value="3" <?php echo ($saveType==3)?"checked=\"checked\"":""?>>  <?php echo $strHidLog?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="isTop" value="1" <?php  if ($isTop=="1") { echo "checked"; }?>>
		    <?php echo $strLogTopOpen?>
		    <input type="radio" name="isTop" value="2" <?php  if ($isTop=="2") { echo "checked"; }?>>
		    <?php echo $strLogTopClose?>
		    <input type="radio" name="isTop" value="0" <?php  if ($isTop=="0") { echo "checked"; }?>>
		    <?php echo $strCTopTitle?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $strAutoSplitLogs?>
			<INPUT TYPE="text" NAME="autoSplit" class="textbox" size="5" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; " value="<?php echo ($autoSplit=="")?$settingInfo['autoSplit']:$autoSplit?>"> <?php echo $strSettingUnitsWords?>
		  </td>
		</tr>

將他取代成

		<tr>
		  <td nowrap>
			<?php echo $strPareams?>
		  </td>
		  <td colspan="3">
		    <input type="radio" name="saveType" value="1" <?php echo ($saveType==1)?"checked=\"checked\"":""?>>  <?php echo $strPubLog?>
			<input type="radio" name="saveType" value="0" <?php echo ($saveType==0)?"checked=\"checked\"":""?>>  <?php echo $strDraft?>		    
		    <input type="radio" name="saveType" value="3" <?php echo ($saveType==3)?"checked=\"checked\"":""?>>  <?php echo $strHidLog?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="isTop" value="1" <?php  if ($isTop=="1") { echo "checked"; }?>>
		    <?php echo $strLogTopOpen?>
		    <input type="radio" name="isTop" value="2" <?php  if ($isTop=="2") { echo "checked"; }?>>
		    <?php echo $strLogTopClose?>
		    <input type="radio" name="isTop" value="0" <?php  if ($isTop=="0") { echo "checked"; }?>>
		    <?php echo $strCTopTitle?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $strAutoSplitLogs?>
			<INPUT TYPE="text" NAME="autoSplit" class="textbox" size="5" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; " value="<?php echo ($autoSplit=="")?$settingInfo['autoSplit']:$autoSplit?>"> <?php echo $strSettingUnitsWords?>
		  </td>
		</tr>
		
		<!-- SATRT ADD From WenChi @plugins BlogNews -->
		  <td nowrap>
			<?php echo $strTopNews?>
		  </td>
		  <td colspan="3">
		    <input type="radio" name="isTopNews" value="1" <?php echo ($isTopNews==1)?"checked=\"checked\"":""?>>  <?php echo $strTopNewsAllow?>
			<input type="radio" name="isTopNews" value="0" <?php echo ($isTopNews==0)?"checked=\"checked\"":""?>>  <?php echo $strTopNewsDeny?>		    
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  </td>
		</tr>
		<!-- END ADD Frm WenChi @plugins BlogNews -->


7.
開啟 admin/logs_list.inc.php

找到

		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.saveType\">$strStatus</a>";
			if ($order=="a.saveType"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>

將他取代成

		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.saveType\">$strStatus</a>";
			if ($order=="a.saveType"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <!-- START ADD From WenChi -->
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isTopNews\">$strTopNewsTitle</a>";
			if ($order=="a.isTopNews"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <!-- END ADD From WenChi -->


8.
開啟 admin/logs_list.inc.php

找到
		if ($fa['saveType']==0){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/draft.gif' alt='$strDraftLog' title='$strDraftLog'>";
		}elseif($fa['saveType']==3){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/lock.gif' alt='$strHiddenLog' title='$strHiddenLog'>";
		}else{
			$imgHidden="&nbsp;";
		}

將他取代成

		if ($fa['saveType']==0){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/draft.gif' alt='$strDraftLog' title='$strDraftLog'>";
		}elseif($fa['saveType']==3){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/lock.gif' alt='$strHiddenLog' title='$strHiddenLog'>";
		}else{
			$imgHidden="&nbsp;";
		}
		//	START ADD From WenChi
		if ($fa['isTopNews']==1){
			$imgTopNews="<img src='themes/{$settingInfo['adminstyle']}/add_top.gif' alt='$strTopNewsLog' title='$strTopNewsLog'>";
		}else{
			$imgTopNews="&nbsp;";
		}
		//	END ADD From WenChi


		
9.
開啟 admin/logs_list.inc.php

找到
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgHidden?>
		  </td>

將他取代成

		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgHidden?>
		  </td>
		  <!-- START ADD From WenChi -->
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgTopNews?>
		  </td>
		  <!-- END ADD From WenChi -->

10.
開啟 admin/logs_list.inc.php

找到
	  <?php if ($settingInfo['isHtmlPage']==1){?>
	  <input type="radio" name="operation" value="create_html" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogsCreateHtmlFoot?>
	  |
	  <?php }?>

將他取代成
	  <?php if ($settingInfo['isHtmlPage']==1){?>
	  <input type="radio" name="operation" value="create_html" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogsCreateHtmlFoot?>
	  |
	  <?php }?>
	  
	  <!-- SATRT ADD From WenChi @plugins BlogNews -->
	  <br>
	  <?echo $strTopNews;?>
	  <input type="radio" name="operation" value="setTopNews" onclick="Javascript:this.form.opmethod.value=1">
	  <select name="isTopNews" onchange="seekform.elements['operation'][17].checked=true;"">
	  <option value="" selected><?echo $strTopNewsDisable;?></option>
	  <option value="1"><?echo $strTopNewsAllow;?></option>
	  <option value="0"><?echo $strTopNewsDeny;?></option>
	  </select>
	  |
	  <!-- END ADD Frm WenChi @plugins BlogNews -->

11.
開啟 language/admin/zh_tw.php
和   language/admin/zh_cn.php

加入
//	START ADD from WenChi  @plugins BlogNews
$strTopNews="跑馬燈消息";
$strTopNewsDisable="跑馬燈狀態";
$strTopNewsAllow="啟用";
$strTopNewsDeny="停用";

$strTopNewsTitle="跑馬燈";
$strTopNewsLog="跑馬燈";


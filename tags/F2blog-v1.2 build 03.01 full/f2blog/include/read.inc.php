<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//阅读加密日志
if (!empty($_POST['logpassword'])){
	if ($_SESSION['logpassword']!=""){
		$_SESSION['logpassword']=$_SESSION['logpassword'].";".md5(encode($_POST['logpassword']));
	}else{
		$_SESSION['logpassword']=md5(encode($_POST['logpassword']));
	}		
}

if ($settingInfo['rewrite']==0) {
	$gourl="index.php?load=$load&amp;id=$id";
	$cateurl="index.php?job=category&amp;seekname=";
	$relurl="index.php?load=$load&amp;id=";
}
if ($settingInfo['rewrite']==1) {
	$gourl="rewrite.php/$load-$id";
	$cateurl="rewrite.php/category-";
	$relurl="rewrite.php/$load-";
}
if ($settingInfo['rewrite']==2) {
	$gourl="$load-$id";
	$cateurl="category-";
	$relurl="$load-";
}

//检测日志是否存在。
if ($borwseTitle==$strErrorNoExistsLog) {
?>
   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?php echo $strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?php echo $strErrorNoExistsLog?><br /><a href="index.php"><?php echo $strErrorBack?></a></div>
	  </div>
	</div>
  </div><br /><br />
<?php  } else {
	$fa=$arr_array;

	$strFeadLogs=$strFeadAll.$fa['name'].$strLogss;
	$strViewLogs=$strView.$fa['name'].$strLogss;

	//使用Ajax技术
	if (!filter_ip(getip()) or $fa['isTrackback']==0 or $settingInfo['allowTrackback']==0) { //为禁止IP时，不给看引用地址
		$tb_allow="";
	} else {
		if (strpos(";$settingInfo[ajaxstatus];","T")>0){
			$tb_allow="$strTrackbackSession";
		}else{
			$tb_allow="NoAjax";
			$extra=substr(md5($id.$fa['postTime']),0,6);
			$tb_url=$settingInfo['blogUrl']."trackback.php?tbID=$id&amp;extra=$extra";
		}
	}

	$postTime=$fa['postTime'];

	//读取上下分页
	if ($settingInfo['readpagebar']==1){
		include("cache/cache_logsTitle.php");
		$arrLogsKey=array_keys($logsTitlecache);
		$current_id=array_search($id,$arrLogsKey);
		$previd=empty($arrLogsKey[$current_id+1])?0:$arrLogsKey[$current_id+1];
		$nextid=($current_id>1 && !empty($arrLogsKey[$current_id-1]))?$arrLogsKey[$current_id-1]:0;
		if ($previd>0) {
			$prevtitle="$strPrevLog$strHomeLog: ".$logsTitlecache[$previd];
			if ($settingInfo['rewrite']==0) $prevpagebar="index.php?load=read&amp;id=$previd";
			if ($settingInfo['rewrite']==1) $prevpagebar="rewrite.php/read-$previd{$settingInfo['stype']}";
			if ($settingInfo['rewrite']==2) $prevpagebar="read-$previd{$settingInfo['stype']}";
		}
		if ($nextid>0) {
			$nexttitle="$strNextLog$strHomeLog: ".$logsTitlecache[$nextid];
			if ($settingInfo['rewrite']==0) $nextpagebar="index.php?load=read&amp;id=$nextid";
			if ($settingInfo['rewrite']==1) $nextpagebar="rewrite.php/read-$nextid{$settingInfo['stype']}";
			if ($settingInfo['rewrite']==2) $nextpagebar="read-$nextid{$settingInfo['stype']}";
		}
	}

	$weather=($fa['weather']=="")?"sunny":strtolower($fa['weather']);
	$tags=$fa['tags'];
	$commNums=$fa['commNums'];
	$quoteNums=$fa['quoteNums'];
	$author=(!empty($memberscache[$fa['author']]))?$memberscache[$fa['author']]:$fa['author'];
?>
<div class="content-width"><a name="body" accesskey="B" href="#body"></a>
	<div class="pageContent">
	  <div style="float:right;"> 
		<a href="rss.php?cateID=<?php echo $fa['cateId']?>" target="_blank" title="<?php echo $strFeadLogs?>"><img border="0" src="images/rss.png" alt="<?php echo $strFeadLogs?>" style="margin-bottom:-1px" align="absMiddle"/> <?php echo $strFead?></a>
		<?php 
		if ($settingInfo['readpagebar']==1){
			if ($previd==""){
				echo " | <img border=\"0\" src=\"images/Cprev1.gif\" alt=\"\" />$strPrevLog | \n";
			}else{
				echo " | <a href=\"$prevpagebar\" title=\"$prevtitle\"><img border=\"0\" src=\"images/Cprev.gif\" alt=\"$strPrevLog\" />$strPrevLog</a> | \n";
			} 
			echo "<a href=\"javascript:history.back(1)\" title=\"$strReturn\"><img border=\"0\" src=\"images/arrow_left1.gif\" alt=\"$strReturn\" align=\"absMiddle\" /> $strReturn</a> | \n";
			if ($nextid==""){
				echo "<img border=\"0\" src=\"images/Cnext1.gif\" alt=\"\"/>$strNextLog \n";
			} else {
				echo "<a href=\"$nextpagebar\" title=\"$nexttitle\"><img border=\"0\" src=\"images/Cnext.gif\" alt=\"$strNextLog\" />$strNextLog</a> \n";
			}
		}
		?>
	  </div>

	  <img src="images/icons/<?php echo $fa['cateIcons']?>.gif" style="margin:0px 2px -4px 0px" alt=""/>
	  <strong><a href="<?php echo $cateurl.$fa['cateId'].$settingInfo['stype']?>" title="<?php echo $strViewLogs?>"><?php echo $fa['name']?></a></strong>
	</div>

	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><strong><?php echo $fa['logTitle']?></strong><?php echo ($fa['saveType']==3)?" [$strHidLog]":""?></h1>
		<h2 class="ContentAuthor"><?php echo $strAuthor.": ".$author."&nbsp;&nbsp;".$strLogDate.": ".format_time($settingInfo['currFormatDate'],$fa['postTime'])?></h2>
	  </div>
	  <div class="Content-Info">
		<div class="InfoOther">
			<?php echo $strFontSize?><a href="javascript:SetFont('logcontent_<?php echo $fa['id']?>','12px')"><?php echo $strSmall?></a> <a href="javascript:SetFont('logcontent_<?php echo $fa['id']?>','14px')"><?php echo $strMiddle?></a> <a href="javascript:SetFont('logcontent_<?php echo $fa['id']?>','16px')"><?php echo $strLarge?></a>
		</div>
		<div class="InfoAuthor">
			<?php if ($settingInfo['weatherStatus']==1) { ?>
			<img src="images/weather/hn2_<?php echo $weather?>.gif" style="margin:0px 2px -6px 0px" alt=""/><img src="images/weather/hn2_t_<?php echo $weather?>.gif" alt=""/>
			<?php } ?> 
		</div>
	  </div>

	  <div class="Content-body" id="logcontent_<?php echo $fa['id']?>" style="word-break:break-all; table-layout: fixed;">
		<?php 
		if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
			echo "<img src=\"images/icon_lock.gif\" alt=\"\" /> $strLogPasswordHelp \n";
		?>
			<form name="passForm" action="" method="post" style="margin:0px;">
				<INPUT TYPE="password" NAME="logpassword" size="10" maxlength="20" class="userpass">
				<INPUT TYPE="button" name="submitlogspass" value="<?php echo $strConfirm?>" class="userbutton" onclick="Javascript:readlogspassword(this.form,'<?php echo $strGuestBookPasswordError?>','<?php echo $fa['id']?>','<?php echo (strpos(";$settingInfo[ajaxstatus]","P")>0)?"ajax":"no"?>')">
			</form>
		<?php 
		}else{
			$html_path=format_time("Ym",$fa['postTime']);
			if (empty($fa['password']) && $settingInfo['isHtmlPage']==1 && file_exists(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id'].".php")) {
				include(F2BLOG_ROOT."./cache/cache_download.php");
				include(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id'].".php");
			}else{
				$content=formatBlogContent($fa['logContent'],1,$fa['id']);
				if ($fa['logsediter']=="ubb") $content=nl2br($content);
				echo $content;
			}
		}
		?>
	  </div>
	  
	  <?php if ($tags!="" || $tb_allow!=""){?>
	  <div class="Content-body">
		<?php if ($tb_allow!=""){?>
		<img src="images/icon_trackback.gif" style="margin:0px 2px -4px 0px" alt=""/><strong><?php echo $strQuoteAddress?></strong> 
			<?php 
			//使用Ajax技术
			if (strpos(";$settingInfo[ajaxstatus]","T")>0){
			?>
				<span style="cursor: pointer;" id="gettbUrl" onclick="f2_ajax_tbsession('f2blog_ajax.php?ajax_display=trackback_session&amp;logId=<?php echo $fa['id']?>')" title="<?php echo $strTrackbackSession?>"><?php echo $strTrackbackSession?></span>
				<span style="cursor: pointer;display:none" id="tbURL" onclick="CopyText(document.all.tbURL)" title="<?php echo $strCopyLink?>"></span><br />
			<?php }?>
			<?php 
			//不使用Ajax技术
			if (strpos(";$settingInfo[ajaxstatus];","T")<1){
			?>
				<span style="cursor: pointer;" id="tbURL" onclick="CopyText(document.all.tbURL)" title="<?php echo $strCopyLink?>"><?php echo $tb_url?></span><br />
			<?php }?>
		<?php }?>
		<?php if ($tags!=""){?>
		<img src="images/tag.gif" style="margin:4px 2px -4px 0px" alt=""/><strong><?php echo $strTag?>:</strong> 
		<?php echo tagList($tags)?> <br />
		<?php }?>
	  </div>
	  <?php }?>

	  <div class="Content-bottom">
		<div class="ContentBLeft"></div>
		<div class="ContentBRight"></div>
		<a href="<?php echo $gourl.$settingInfo['stype']?>#comm_top"><?php echo $strLogComm.": <span id=\"commNums\">".$fa['commNums']."</span>"?></a> |
		<?php echo $strLogTB.": ".$fa['quoteNums']?> |
		<?php echo $strLogRead.": ".$fa['viewNums']?> 
	    <?php if ($settingInfo['showPrint']==1){?>| <a href="logsprint.php?id=<?php echo $fa['id']?>" target="_blank"><?php echo $strLogsPrinter?></a><?php }?>
	    <?php if ($settingInfo['showDown']==1){?>| <a href="logsdown.php?id=<?php echo $fa['id']?>"><?php echo $strLogsDownload?></a><?php }?>
	    <?php if ($settingInfo['showMail']==1){?>| <a href="sendmail.php?id=<?php echo $fa['id']?>"><?php echo $strLogsSendMail?></a><?php }?>	
		
		<?php if (!empty($_SESSION['rights']) && $_SESSION['rights']!="member" && ($_SESSION['rights']=="admin" || $fa['author']==$_SESSION['username'])){ ?> | 
		    <select name="action" id="action" onchange="if(this.options[this.selectedIndex].value != '') { 	window.location=('<?php echo $base_rewrite;?>admin/logs.php?mark_id=<?php echo $fa['id']?>&amp;action=manage&amp;manage='+this.options[this.selectedIndex].value);}">
			<option value="" selected><?php echo $strHomePageAdmin; ?></option>
			<option value="edit"><?php echo $strEdit; ?></option>
			<option value="">---------</option>
			<option value="delete"><?php echo $strDelete; ?></option>
			<option value="">---------</option>
			<option value="publish"><?php echo $strLogPublic; ?></option>
			<option value="private"><?php echo $strHidLog; ?></option>
			<option value="draft"><?php echo $strLogDraft; ?></option>
			<option value="">---------</option>
			<option value="notop"><?php echo $strLogNoTop; ?></option>
			<option value="topopen"><?php echo $strLogTopOpen; ?></option>
			<option value="topclose"><?php echo $strLogTopClose; ?></option>
			<option value="">---------</option>
			<option value="cthidden"><?php echo $strCommentHidden; ?></option>
			<option value="ctshow"><?php echo $strCommentShow; ?></option>
			<option value="tbhidden"><?php echo $strTrackBackHidden; ?></option>
			<option value="tbshow"><?php echo $strTrackBackShow; ?></option>
			<option value="">---------</option>
			<option value="ctempty"><?php echo $strLogEmptyComment; ?></option>
			<option value="tbempty"><?php echo $strLogEmptyTB; ?></option>
			</select>
		<?php  } ?>

	  </div>
	</div>
	<?php 
	//读取內容插件或模組
	foreach($arrMainModule as $key=>$value){
		$mainname=$key;
		$maintitle=$value['modTitle'];
		$indexOnly=$value['indexOnly'];
		$installDate=empty($value['installDate'])?"":$value['installDate'];
		$htmlcode=$value['htmlCode'];
			
		//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");
		if ($indexOnly==5){//首页日志尾部
			if ($installDate>0){//表示为插件
				do_filter($mainname,$mainname,$maintitle,$htmlcode);
			}else{
				main_module($mainname,$maintitle,$htmlcode);
			}
		}
	}
	
	if ($settingInfo['isLinkTagLog']==1 and $tags!="") {
		$arrTags=explode(";",$tags);
		$tagsql="";
		foreach($arrTags as $key=>$value){
			$tagsql.=" or concat(';',tags,';') like '%;".$value.";%'";
		}
		$tagsql="(".substr($tagsql,4).") and ";

		$tag_sql="select * from ".$DBPrefix."logs where $tagsql saveType='1' and id!='$id' order by postTime desc limit 0,".$settingInfo['linkTagLog']."";
		$tag_result=$DMC->query($tag_sql);
		$tag_nums=$DMC->numRows($tag_result);
		if ($tag_nums>0) {
		?>
		  <div class="comment">
			<div class="commenttop">
				<img src="images/tag.gif" alt="" style="margin:0px 4px -3px 0px"/><strong><?php echo $strIsLinkTagLog?></strong> 
			</div>
			<div class="commentcontent">
			<?php  while($tagfa = $DMC->fetchArray($tag_result)){ ?>
				<a href="<?php echo $relurl.$tagfa['id'].$settingInfo['stype']?>">
				<?php echo $tagfa['logTitle']?> (<?php echo format_time($settingInfo['currFormatDate'],$tagfa['postTime'])?>)</a>  <br />
			<?php  } ?>
			</div>
		   </div>
		<?php  } 
		}?>
	</div>
	<a name="comm_top" href="comm_top" accesskey="C"></a>
	<?php include("replylogs.inc.php")?>
<?php  } ?>
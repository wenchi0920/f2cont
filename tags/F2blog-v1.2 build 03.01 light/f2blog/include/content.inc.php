<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

if (!empty($_POST['logpassword'])){
	if ($_SESSION['logpassword']!=""){
		$_SESSION['logpassword']=$_SESSION['logpassword'].";".md5(encode($_POST['logpassword']));
	}else{
		$_SESSION['logpassword']=md5(encode($_POST['logpassword']));
	}		
}

$job=(empty($_REQUEST['job']))?"":$_REQUEST['job'];
$_POST['seekname']=(empty($_POST['seekname']))?"":$_POST['seekname'];
$_GET['seekname']=(empty($_GET['seekname']))?"":$_GET['seekname'];
$seekname=($_POST['seekname']!="")?$_POST['seekname']:$_GET['seekname'];

$_GET['page']=(isset($_GET['page']))?intval($_GET['page']):1;
$page=$_GET['page'];

//分页
$per_page=($disType==0)?$settingInfo['perPageNormal']:$settingInfo['perPageList'];

//导航
if ($settingInfo['rewrite']==0) {
	$gourl=($job!="")?"index.php?job=$job&amp;seekname=".urlencode($seekname):"index.php";
	$category_url="index.php?job=category&amp;seekname=";
	$readlogs_url="index.php?load=read&amp;id=";
	$distype_url0=(strpos($gourl,"?")>0)?$gourl."&disType=0":$gourl."?disType=0";
	$distype_url1=(strpos($gourl,"?")>0)?$gourl."&disType=1":$gourl."?disType=1";
}
if ($settingInfo['rewrite']==1) {	
	$gourl=($job!="")?"rewrite.php/$job-".urlencode($seekname):"rewrite.php/";
	$category_url="rewrite.php/category-";
	$readlogs_url="rewrite.php/read-";
	$distype_url0=($gourl!="rewrite.php/")?$gourl."-1-0".$settingInfo['stype']:$gourl."1-0".$settingInfo['stype'];
	$distype_url1=($gourl!="rewrite.php/")?$gourl."-1-1".$settingInfo['stype']:$gourl."1-1".$settingInfo['stype'];
}
if ($settingInfo['rewrite']==2) {
	$gourl=($job!="")?$job."-".urlencode($seekname):"";
	$category_url="category-";
	$readlogs_url="read-";
	$distype_url0=($gourl!="")?$gourl."-1-0".$settingInfo['stype']:"1-0".$settingInfo['stype'];
	$distype_url1=($gourl!="")?$gourl."-1-1".$settingInfo['stype']:"1-1".$settingInfo['stype'];
}

if ($page<1){$page=1;}
$start_record=($page-1)*$per_page;
	
$sql_find=searchSQL($job,$seekname);
$saveType=(!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?"(a.saveType=1 or a.saveType=3)":"a.saveType=1";

$sql="select a.*,b.name,b.cateIcons from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where $saveType $sql_find order by a.isTop desc,a.postTime desc";
$nums_sql="select count(a.id) as numRows from ".$DBPrefix."logs as a where $saveType $sql_find";
$total_num=getNumRows($nums_sql);

$query_sql=$sql." Limit $start_record,$per_page";
$query_result=$DMC->query($query_sql);
?>
<!--工具栏-->
<div id="Content_ContentList" class="content-width">
	<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
	  <div class="page" style="FLOAT: left">
		<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="T") pageBar("$gourl"); ?>
	  </div>
	  <?php echo $strBrowseType?>:
	  <a href="<?php echo $distype_url0?>"><?php echo $strNormal?></a> | 
	  <a href="<?php echo $distype_url1?>"><?php echo $strList?></a>
	</div>
	<!--显示-->
	<?php 
	$arr_array=$DMC->fetchQueryAll($query_result);
	foreach($arr_array as $key=>$fa){
		if ($disType==0) { //显示摘要
			$author=(!empty($memberscache[$fa['author']]))?$memberscache[$fa['author']]:$fa['author'];
		?>
			<div class="Content">
			  <div class="Content-top">
				<div class="ContentLeft"></div>
				<div class="ContentRight"></div>
				<?php echo ($fa['isTop']>0)?"<div class=\"BttnE\" onclick=\"OpenClose(this,'log_".$key."')\"></div>":""?>
				<h1 class="ContentTitle"><img src="images/icons/<?php echo $fa['cateIcons']?>.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>"> <?php echo $fa['logTitle']?><?php echo ($fa['saveType']==3)?" [$strHidLog]":""?></a> 
				</h1>
				<h2 class="ContentAuthor">
				  <?php echo $strAuthor.":".$author."&nbsp;".$strLogDate.":".format_time($settingInfo['currFormatDate'],$fa['postTime'])?>
				</h2>
			  </div>
			  <div id="log_<?php echo $key?>" <?php echo ($fa['isTop']==2)?"style=\"display:none\"":""?>>
				<div class="Content-body" id="logcontent_<?php echo $fa['id']?>" style="word-break:break-all; table-layout: fixed;">
					<?php 
					if (empty($_SESSION['logpassword'])) $_SESSION['logpassword']="";
					if (empty($_SESSION['rights'])) $_SESSION['rights']="";
					if (empty($fa['password'])) $fa['password']="";
					if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
						echo "<img src=\"images/icon_lock.gif\" alt=\"\"/> $strLogPasswordHelp \n";
					?>
					<form name="passForm" action="" method="post" style="margin:0px;">
						<input type="password" name="logpassword" size="10" maxlength="20" class="userpass">
						<input type="button" name="submitlogspass" value="<?php echo $strConfirm?>" class="userbutton" onclick="Javascript:readlogspassword(this.form,'<?php echo $strGuestBookPasswordError?>','<?php echo $fa['id']?>','<?php echo (strpos(";$settingInfo[ajaxstatus]","P")>0)?"ajax":"no"?>')">
					</form>
					<?php 
					}else{
						$html_path=format_time("Ym",$fa['postTime']);
						if (empty($fa['password']) && $settingInfo['isHtmlPage']==1 && file_exists(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id'].".php")){
							include(F2BLOG_ROOT."./cache/cache_download.php");
							if (file_exists(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id']."_index.php")) {
								include(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id']."_index.php");
							}else{
								include(F2BLOG_ROOT."./cache/html/$html_path/".$fa['id'].".php");
							}
						}else{
							$content=$fa['logContent'];							
							if (strpos($content,"<!--more-->")>0){
								$content=htmlSubString($content,"<!--more-->");
								$content=formatBlogContent($content,0,$fa['id']);
								if ($fa['logsediter']=="ubb") $content=nl2br($content);
								echo $content;
								echo "<p><a class=\"more\" href=\"$readlogs_url".$fa['id'].$settingInfo['stype']."\">[$strContentAll]</a></p> \n";
							}else{								
								if ($fa['autoSplit']>0){
									$textlength=getStringLength(strip_tags($content));
									if ($textlength>$fa['autoSplit']){
										$content=htmlSubString($content,$fa['autoSplit']);
										$content=formatBlogContent($content,0,$fa['id']);
										if ($fa['logsediter']=="ubb") $content=nl2br($content);
										echo $content;
										echo "<p><a class=\"more\" href=\"$readlogs_url".$fa['id'].$settingInfo['stype']."\">[$strContentAll]</a></p> \n";
									}else{
										$content=formatBlogContent($content,0,$fa['id']);
										if ($fa['logsediter']=="ubb") $content=nl2br($content);
										echo $content;
									}
								}else{
									$content=formatBlogContent($content,0,$fa['id']);
									if ($fa['logsediter']=="ubb") $content=nl2br($content);
									echo $content;
								}
							}
						}
					}
					
					if ($fa['tags']!="" && $settingInfo['disTags']=="1"){
						echo "<div style=\"clear:both;margin-top:10px;\"><strong>$strTag: </strong> ".tagList($fa['tags'])."</div>";
					}
					?>			
				</div>
				<div class="Content-bottom">
				  <div class="ContentBLeft"></div>
				  <div class="ContentBRight"></div>
					  <?php echo $strAttachmentsType?>:
					  <a href="<?php echo $category_url.$fa['cateId'].$settingInfo['stype']?>"><?php echo $fa['name']?></a>
					  | <a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>#tb_top"><?php echo $strLogTB.": ".$fa['quoteNums']?></a>
					  | <a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>#comm_top"><?php echo $strLogComm.": ".$fa['commNums']?></a>			  
					  | <a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>"><?php echo $strLogRead.": ".$fa['viewNums']?></a>
					  <?php if ($settingInfo['showPrint']==1){?>| <a href="logsprint.php?id=<?php echo $fa['id']?>" target="_blank"><?php echo $strLogsPrinter?></a><?php }?>
					  <?php if ($settingInfo['showDown']==1){?>| <a href="logsdown.php?id=<?php echo $fa['id']?>"><?php echo $strLogsDownload?></a><?php }?>
					  <?php if ($settingInfo['showMail']==1){?>| <a href="sendmail.php?id=<?php echo $fa['id']?>"><?php echo $strLogsSendMail?></a><?php }?>	  
					  <?php if (!empty($_SESSION['rights']) && $_SESSION['rights']!="member" && ($_SESSION['rights']=="admin" || $fa['author']==$_SESSION['username'])){ ?>
						<select name="action" id="action" onchange="if(this.options[this.selectedIndex].value != '') { 	window.location=('admin/logs.php?mark_id=<?php echo $fa['id']?>&amp;action=manage&amp;manage='+this.options[this.selectedIndex].value);}">
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
						<option value="tbshow"><?php echo $strTrackBackHidden; ?></option>
						<option value="tbhidden"><?php echo $strTrackBackShow; ?></option>
						<option value="">---------</option>
						<option value="ctempty"><?php echo $strLogEmptyComment; ?></option>
						<option value="tbempty"><?php echo $strLogEmptyTB; ?></option>
						</select>
					<?php }?>
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
					if ($indexOnly==4){//首页日志尾部
						if ($installDate>0){//表示为插件
							do_filter($mainname,$mainname,$maintitle,$htmlcode);
						}else{
							main_module($mainname,$maintitle,$htmlcode);
						}
					}
				}
				?>
			  </div>
			</div>
		<?php  } else { ?>
			<div class="Content-body" style="text-align:Left">
			  <table cellpadding="2" cellspacing="2" width="100%">
				<tr>
				  <td valign="top">
					<a href="<?php echo $category_url.$fa['cateId'].$settingInfo['stype']?>" >
					<img src="images/icons/<?php echo $fa['cateIcons']?>.gif" border="0" alt="<?php echo $strView." ".$fa['name'].$strLogss?>" style="margin:0px 2px -3px 0px"/>[<?php echo $fa['name']?>]</a> 
					<a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>" title="<?php echo $strAuthor.":".$fa['author']."&nbsp;".$strLogDate.":".format_time($settingInfo['currFormatDate'],$fa['postTime'])?>">
					<?php echo $fa['logTitle']?><?php echo ($fa['isTop']==1)?"&nbsp;<img src=\"images/icon_top.gif\" border=\"0\" align=\"middle\" alt=\"\"/>":""?></a> 
					(<?php echo format_time($settingInfo['listFormatDate'],$fa['postTime']);?>)
				  </td>
				  <td valign="top" width="60"><nobr>
					<a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>#comm_top" title="<?php echo $strLogComm?>"><?php echo $fa['commNums']?></a> | 
					<a href="<?php echo $readlogs_url.$fa['id'].$settingInfo['stype']?>#tb_top" title="<?php echo $strLogTB?>"><?php echo $fa['quoteNums']?></a> | 
					<span title="<?php echo $strLogRead?>"><?php echo $fa['viewNums']?></span>
				  </nobr></td>
				</tr>
			  </table>
			</div>
		<?php  }
	}?>
	<div class="pageContent">
	  <div class="page" style="float:right">
		<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="B") pageBar("$gourl"); ?>
	  </div>
	</div>
</div>
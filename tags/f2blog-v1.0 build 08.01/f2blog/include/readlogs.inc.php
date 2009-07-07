<?
if ($_POST['logpassword']!=""){
	if ($_SESSION['logpassword']!=""){
		$_SESSION['logpassword']=$_SESSION['logpassword'].";".md5($_POST['logpassword']);
	}else{
		$_SESSION['logpassword']=md5($_POST['logpassword']);
	}		
}

$id=$_GET['id'];
$seekname=$_GET['seekname'];
$job=$_GET['job'];

$gourl="$PHP_SELF?seekname=$seekname&job=$job";

//Log content
$sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id";
$sql.=" where (a.saveType=1 or a.saveType=2) and a.id='$id' order by a.postTime desc";
$result=$DMF->query($sql);
$numRows=$DMF->numRows($result);

if ($numRows==0) {
?>

   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?=$strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?=$strErrorNoExistsLog?><br/><a href="index.php"><?=$strErrorBack?></a></div>
	  </div>
	</div>
  </div><br/><br/>

<? } else {
$arr_array=$DMF->fetchQueryAll($result);
$fa=$arr_array[0];

$strFeadLogs=$strFeadAll.$fa['name'].$strLogss;
$strViewLogs=$strView.$fa['name'].$strLogss;
$extra=tb_extra($id,$fa['postTime']);
if (!filter_ip(getip()) or $fa['isTrackback']==0) { //为禁止IP时，不给看引用地址
	$tb_url="";
} else {
	$tb_url=$settingInfo['blogUrl']."trackback.php?tbID=$id&extra=$extra";
}
$postTime=$fa['postTime'];

$searchSql=searchSQL($job,$seekname);
$prevsql="SELECT id,logTitle FROM ".$DBPrefix."logs WHERE postTime < '".$postTime."' and saveType=1 $searchSql ORDER BY postTime DESC LIMIT 1";
$prevLog=$DMF->fetchArray($DMF->query($prevsql));
$previd=$prevLog['id'];
$prevtitle="$strPrevLog$strHomeLog: ".$prevLog['logTitle'];

$nextsql="SELECT id,logTitle FROM ".$DBPrefix."logs WHERE postTime > '".$postTime."' and saveType=1 $searchSql ORDER BY postTime ASC LIMIT 1";
$nextLog=$DMF->fetchArray($DMF->query($nextsql));
$nextid=$nextLog['id'];
$nexttitle="$strNextLog$strHomeLog: ".$nextLog['logTitle'];

$weather=($fa['weather']=="")?"sunny":strtolower($fa['weather']);
$tags=$fa['tags'];
?>
<div id="Content_ContentList" class="content-width"><a name="body" accesskey="B" href="#body"></a>
	<div class="pageContent">
	  <div style="float:right;width:180px !important;width:auto"> 
		<a href="rss.php?cateID=<?=$fa['cateId']?>" target="_blank" title="<?=$strFeadLogs?>"><img border="0" src="images/rss.png" alt="<?=$strFeadLogs?>" style="margin-bottom:-1px"/><?=$strFead?></a> | 
		<? if ($previd=="") { ?>
			<img border="0" src="images/Cprev1.gif" /><?=$strPrevLog?>
		<? } else { ?>
			<a href="<?="$gourl&id=$previd&load=read"?>" title="<?=$prevtitle?>"><img border="0" src="images/Cprev.gif" /><?=$strPrevLog?></a>
		<? } ?>
		| 
		<? if ($nextid=="") { ?>
			<img border="0" src="images/Cnext1.gif" /><?=$strNextLog?>
		<? } else { ?>
			<a href="<?="$gourl&id=$nextid&load=read"?>" title="<?=$nexttitle?>"><img border="0" src="images/Cnext.gif" /><?=$strNextLog?></a>
		<? } ?>
	  </div>

	  <img src="images/category.gif" style="margin:0px 2px -4px 0px" alt=""/> <strong><a href="<?="index.php?job=category&seekname=".$fa['cateId']?>" title="<?=$strViewLogs?>"><?=$fa['name']?></a></strong>
	</div>

	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><strong><?=$fa['logTitle']?></strong></h1>
		<h2 class="ContentAuthor"><?=$strLogDate.":".format_time("L",$fa['postTime'])?></h2>
	  </div>

	  <div class="Content-Info">
		<div class="InfoOther">
			<?=$strFontSize?><a href="javascript:SetFont('12px')"><?=$strSmall?></a> <a href="javascript:SetFont('14px')"><?=$strMiddle?></a> <a href="javascript:SetFont('16px')"><?=$strLarge?></a>
		</div>
		<div class="InfoAuthor">
			<img src="images/weather/hn2_<?=$weather?>.gif" style="margin:0px 2px -6px 0px" alt=""/><img src="images/weather/hn2_t_<?=$weather?>.gif" alt=""/>
		</div>
	  </div>

	  <div id="logPanel" class="Content-body">
		<?
		if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
			echo "<img src=\"images/icon_lock.gif\"> $strLogPasswordHelp \n";
		?>
			<form name="passForm" action="<?="index.php?load=$load&page=$page&id=$id&job=$job"?>" method="post" style="margin:0px;">
				<INPUT TYPE="password" NAME="logpassword" size="10" class="userpass">
				<INPUT TYPE="submit" name="submit" value="<?=$strConfirm?>" class="userbutton">
			</form>
		<?
		}else{
			$content=formatBlogContent($fa['logContent'],1,$fa['id']);
			echo $content;
		}
		?>
	  </div>
	  
	  <?if ($tags!="" || $tb_url!=""){?>
	  <div class="Content-body">
		<?if ($tb_url!=""){?>
		<img src="images/icon_trackback.gif" style="margin:0px 2px -4px 0px" alt=""/><strong><?=$strQuoteAddress?></strong> 
		<span style="cursor: pointer;" id="tbURL" onclick="CopyText(document.all.tbURL)" title="<?=$strCopyLink?>"><?=$tb_url?></span><br/>
		<?}?>
		<?if ($tags!=""){?>
		<img src="images/tag.gif" style="margin:4px 2px -4px 0px" alt=""/><strong><?=$strTag?>:</strong> 
		<?=tagList($tags)?> <br/>
		<?}?>
	  </div>
	  <?}?>

	  <div class="Content-bottom">
		<div class="ContentBLeft"></div>
		<div class="ContentBRight"></div>
		<a href="#comm_top"><?=$strLogComm.": ".$fa['commNums']?></a> |
		<?=$strLogTB.": ".$fa['quoteNums']?> |
		<?=$strLogRead.": ".$fa['viewNums']?> 
		<?if ($_SESSION['rights']=="admin"){ ?> | 
		  <a href="admin/logs.php?action=edit&edittype=front&mark_id=<?=$fa['id']?>"><?=$strEdit?></a> | 
	      <a href="admin/logs.php?action=delete&id=<?=$fa['id']?>" onclick="if (!window.confirm('<?=$strDeleteLog?>')) {return false}"><?=$strDelete?></a>
		<? } ?>
	  </div>
	</div>
	<?
	//读取內容插件或模組
	for ($i=0;$i<count($arrMainModule);$i++){	
		$mainname=$arrMainModule[$i]['name'];
		$maintitle=replace_string($arrMainModule[$i]['modTitle']);
		$htmlcode=$arrMainModule[$i]['htmlCode'];
		$indexOnly=$arrMainModule[$i]['indexOnly'];
		$installDate=$arrMainModule[$i]['installDate'];
		$pluginPath=$arrMainModule[$i]['pluginPath'];
					
		//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");
		if ($indexOnly==5){//读取日志尾部
			if ($installDate>0){//表示为插件
				do_filter($mainname,$mainname,$maintitle,$htmlcode);
			}else{
				main_module($mainname,$maintitle,$htmlcode);
			}
		}
	}

	if ($settingInfo['isLinkTagLog']==1 and $tags!="") {
	//取得关联Tag的文章
	$arrTags=explode(";",$tags);
	$tagsql="";
	for ($y=0;$y<count($arrTags);$y++) {
		$tagsql.=" or concat(';',tags,';') like '%;".$arrTags[$y].";%'";
	}
	$tagsql="(".substr($tagsql,4).") and ";

	$tag_sql="select * from ".$DBPrefix."logs where $tagsql saveType='1' and id!='$id' order by postTime desc limit 0,".$settingInfo['linkTagLog']."";
	$tag_result=$DMF->query($tag_sql);
	?>
	  <div class="comment">
	    <div class="commenttop">
			<img src="images/tag.gif" alt="" style="margin:0px 4px -3px 0px"/><strong><?=$strIsLinkTagLog?></strong> 
		</div>
	    <div class="commentcontent">
		<? while($tagfa = $DMF->fetchArray($tag_result)){ ?>
			<a href="index.php?load=read&id=<?=$tagfa['id']?>">
			<?=$tagfa['logTitle']?> (<?=format_time("L",$tagfa['postTime'])?>)</a>  <br>
		<? } ?>
		</div>
	   </div>
	<? } ?>
	</div>
	<a name="comm_top" href="comm_top" accesskey="C"></a>
	<?include("replylogs.inc.php")?>
<? } ?>
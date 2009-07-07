<?
/** 禁止直接访问该页面 */
if (basename($_SERVER['PHP_SELF']) == "content.inc.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

	if ($_POST['logpassword']!=""){
		if ($_SESSION['logpassword']!=""){
			$_SESSION['logpassword']=$_SESSION['logpassword'].";".md5($_POST['logpassword']);
		}else{
			$_SESSION['logpassword']=md5($_POST['logpassword']);
		}		
	}

	$job=$_REQUEST['job'];
	$seekname=($_POST['seekname']!="")?$_POST['seekname']:$_GET['seekname'];
	$order=$_GET['order'];
	$page=$_GET['page'];

	$per_page=($disType==0)?$settingInfo['perPageNormal']:$settingInfo['perPageList'];
	$gourl="$PHP_SELF?seekname=$seekname&page=$page&job=$job";

	if ($page<1){$page=1;}
	$start_record=($page-1)*$per_page;

	$sql="select a.*,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where a.saveType=1";
	$sql.=searchSQL($job,$seekname);
	$sql.=" order by a.isTop desc,a.postTime desc";
	$total_num=$DMF->numRows($DMF->query($sql));

	$query_sql=$sql." Limit $start_record,$per_page";
	$query_result=$DMF->query($query_sql);
?>
<!--工具栏-->
<div id="Content_ContentList" class="content-width">
<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right"><span 
style="FLOAT: left"></span>
  <div class="page" style="FLOAT: left">
    <? pageBar($gourl); ?>
  </div>
  <?=$strBrowseType?>
  : <a href="<?=$gourl."&disType=0"?>">
  <?=$strNormal?>
  </a> | <a href="<?=$gourl."&disType=1"?>">
  <?=$strList?>
  </a> </div>
<!--显示-->
<?
$arr_array=$DMF->fetchQueryAll($query_result);
for ($k=0;$k<count($arr_array);$k++){
	$fa=$arr_array[$k];
	$index++;
	if ($fa['isTop']){
		$topimage="&nbsp;<img src=\"images/icon_top.gif\" border=\"0\">";
	}else{
		$topimage="";
	}
	if ($disType==0) { //显示摘要
	?>
		<div class="Content">
		  <div class="Content-top">
			<div class="ContentLeft"></div>
			<div class="ContentRight"></div>
			<h1 class="ContentTitle"><a class="titleA" href="index.php?load=read&id=<?=$fa['id']?>">
			  <?=$fa['logTitle'].$topimage?>
			  </a></h1>
			<h2 class="ContentAuthor">
			  <?//=$strAuthor.":".$fa['author']."&nbsp;".$strLogDate.":".format_time("L",$fa['postTime'])?>
			  <?=$strLogDate.":".format_time("L",$fa['postTime'])?>
			</h2>
		  </div>
		  <div id="log_<?=$index?>">
			<div class="Content-body">
				<?
				if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
					echo "<img src=\"images/icon_lock.gif\"> $strLogPasswordHelp \n";
				?>
				<form name="passForm" action="<?="index.php?load=$load&page=$page&job=$job"?>" method="post" style="margin:0px;">
					<INPUT TYPE="password" NAME="logpassword" size="10" class="userpass">
					<INPUT TYPE="submit" name="submit" value="<?=$strConfirm?>" class="userbutton">
				</form>
				<?
				}else{
					$content=formatBlogContent($fa['logContent'],0,$fa['id']);
					if (strpos($content,"<!--more-->")>0){
						$content=substr($content,0,strpos($content,"<!--more-->"));
						echo $content;
						echo "<p>&nbsp;</p> \n";
						echo "<p><a class=\"more\" href=\"index.php?load=read&id=".$fa['id']."\">[$strContentAll]</a></p> \n";
					}else{
						echo $content;
					}
				}
				?>			
			</div>
			<div class="Content-bottom">
			  <div class="ContentBLeft"></div>
			  <div class="ContentBRight"></div>
				  <?=$strAttachmentsType?>
				  :<a href="index.php?job=category&seekname=<?=$fa['cateId']?>">
				  <?=$fa['name']?>
				  </a> | <a href="index.php?load=read&id=<?=$fa['id']?>#comm_top">
				  <?=$strLogComm.": ".$fa['commNums']?>
				  </a> |
				  <?=$strLogTB.": ".$fa['quoteNums']?>
				  |
				  <?="<a href=\"index.php?load=read&id=".$fa['id']."\">$strLogRead: ".$fa['viewNums']."</a>"?>
				  
				  <?if ($_SESSION['rights']=="admin"){ ?>
					 | <a href="admin/logs.php?action=edit&edittype=front&mark_id=<?=$fa['id']?>"><?=$strEdit?></a>
					 | <a href="admin/logs.php?action=delete&id=<?=$fa['id']?>" onclick="if (!window.confirm('<?=$strDeleteLog?>')) {return false}"><?=$strDelete?></a>
				  <? } ?>
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
	<? } else { ?>
		<div class="Content-body" style="text-align:Left">
		  <table cellpadding="2" cellspacing="2" width="100%">
			<tr>
			  <td valign="top">
				<a href="index.php?job=category&seekname=<?=$fa['cateId']?>" >
				<img border="0" alt="<?=$strView." ".$fa['name'].$strLogss?>" src="images/category.gif" style="margin:0px 2px -3px 0px"/></a> 
				<a href="index.php?load=read&id=<?=$fa['id']?>" title="<?=$strAuthor.":".$fa['author']."&nbsp;".$strLogDate.":".format_time("L",$fa['postTime'])?>">
				<?=$fa['logTitle'].$topimage?></a> 
			  </td>
			  <td valign="top" width="60"><nobr>
				<a href="index.php?load=read&id=<?=$fa['id']?>#comm_top" title="<?=$strLogComm?>"><?=$fa['commNums']?></a> | 
				<span title="<?=$strLogTB?>"><?=$fa['quoteNums']?></span> | 
				<span title="<?=$strLogRead?>"><?=$fa['viewNums']?></span>
			  </nobr></td>
			</tr>
		  </table>
		</div>
	<? }
}?>
<div class="pageContent">
  <div class="page" style="float:right">
      <? pageBar($gourl); ?>
  </div>
</div>
</div>
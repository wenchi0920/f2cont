<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "sidebar.inc.php") {
    header("HTTP/1.0 404 Not Found");
}

#最新留言
function side_guestbook($sidename,$sidetitle,$isInstall){
	global $strSideBarGuestBook,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--最新留言-->
<div class="sidepanel" id="Side_GuestBook">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<div id="GuestBook_Body">
	<?
	require("cache/cache_recentGbooks.php");
	for ($i=0;$i<count($recentgbookscache);$i++){
		echo "<a class=\"sideA\" id=\"GuestBook_Link\" title=\"".$recentgbookscache[$i]['author']." $strSideBarAnd ".format_time("L",$recentgbookscache[$i]['postTime'])." $strSideBarGuestBook\n".$recentgbookscache[$i]['content']."\"  href=\"index.php?load=guestbook#book".$recentgbookscache[$i]['id']."\">".$recentgbookscache[$i]['content']."</a> \n";
	}
	?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#最新留言?>
<?
#最新评论
function side_recent_comment($sidename,$sidetitle,$isInstall){
	global $strSideBarComments,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--最新评论-->
<div class="sidepanel" id="Side_Comment">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
    <div class="commentTable" id="Comment_Body">
	  	<?
		require("cache/cache_recentComments.php");
		for ($i=0;$i<count($recentcommentscache);$i++){
			echo "<a class=\"sideA\" id=\"Comment_Link\" title=\"".$recentcommentscache[$i]['author']." $strSideBarAnd ".format_time("L",$recentcommentscache[$i]['postTime'])." $strSideBarComments\n".$recentcommentscache[$i]['content']."\"  href=\"index.php?load=read&id=".$recentcommentscache[$i]['logId']."#book".$recentcommentscache[$i]['id']."\">".$recentcommentscache[$i]['content']."</a> \n";
		}
		?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 最新评论?>
<?
#最新发表文章
function side_recent_logs($sidename,$sidetitle,$isInstall){
	global $strSideBarLogs,$strSideBarAnd;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--最新发表文章-->
<div id="Side_NewLog" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
  	<div id="NewLog_Body">
	<?
	require("cache/cache_recentLogs.php");
	for ($i=0;$i<count($recentlogscache);$i++){
		echo "<a class=\"sideA\" id=\"NewLog_Link\" title=\"".$recentlogscache[$i]['author']." $strSideBarAnd ".format_time("L",$recentlogscache[$i]['postTime'])." $strSideBarLogs\n".$recentlogscache[$i]['logTitle']."\"  href=\"index.php?load=read&id=".$recentlogscache[$i]['id']."\">".$recentlogscache[$i]['logTitle']."</a> \n";
	}
	?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 最新发表文章?>
<?
#归档
function side_archive($sidename,$sidetitle,$isInstall){
	global $strYear,$strMonth;
	require("cache/cache_archives.php");
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--归档-->
<div class="sidepanel" id="Side_Archive">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<div id="Archive_Body">		
	  <?if (count($archivescache)>10){?>
	  <marquee direction=up scrollamount="2" style="width:100%;height:150px" onMouseOver=this.stop() onMouseOut=this.start()>
      <?}?>
	  <?
 	    for ($i=0;$i<count($archivescache);$i++){
			list($logYear,$logMonth)=explode(",",$archivescache[$i]['month']);
			$logName="$logYear $strYear $logMonth $strMonth";
			echo "<a class=\"sideA\" id=\"Archive_Link\" href=\"".$_SERVER['PHP_SELF']."?job=archives&seekname=".$logYear."|".$logMonth."\">".$logName." [".$archivescache[$i]['logNums']."]</a> \n";
		}
	  ?>
	  <?if (count($archivescache)>10){?></marquee><?}?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 归档?>
<?
#友情链接
function side_links($sidename,$sidetitle,$isInstall){
	require("cache/cache_links.php");
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--友情链接-->
<div class="sidepanel" id="Side_Links">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
    <div class="LinkTable" id="Link_Body">
	  <?if (count($linkscache)>10){?>
	  <marquee direction=up scrollamount="2" style="width:100%;height:150px" onMouseOver=this.stop() onMouseOut=this.start()>
      <?}?>
	  <?		
		for ($i=0;$i<count($linkscache);$i++){
			echo "<a class=\"sideA\" id=\"Link_Link\" href=\"".$linkscache[$i]['blogUrl']."\" target=\"_blank\">".$linkscache[$i]['name']."</a> \n";
		}
	  ?>
	  <?if (count($linkscache)>10){?></marquee><?}?>
    </div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 友情链接?>
<?
#搜索
function side_search($sidename,$sidetitle,$isInstall){
	global $strFind,$strSearchErr;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--搜索-->
<div class="sidepanel" id="Side_Search">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
    <div id="Search_Body">
	<form style="MARGIN: 0px" 
onsubmit="if (this.seekname.value.length<3) {alert('<?=$strSearchErr?>');this.seekname.focus();return false}" 
action="index.php">
      <?=$strKeyword?>
      <input class="userpass" name="seekname" />
      <input name="job" value="search" type="hidden" />
      <input name="submit" type="submit" class="userbutton" value="<?=$strFind?>" />
    </form>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 搜索?>
<?
#日历
function side_calendar($sidename,$sidetitle,$isInstall){
	global $mmonth,$mday,$strYear,$strMonth,$mholiday1,$holiday1,$arrWeek,$strDayLogs,$settingInfo;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--日历-->
<div class="sidepanel" id="Side_Calendar">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
    <div id="Calendar_Body">
	  <?require("calander.inc.php");?>
    </div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 日历?>
<?
#访问信息
function side_bloginfo($sidename,$sidetitle,$isInstall){
	global $DMF,$DBPrefix,$strStatisticsVisitsTotal,$strStatisticsVisitsToday,$strStatisticsVisitsYesterday,$online_count,$strOnline,$curDate;
	//总量
	$sql="select sum(visits) as v_total from ".$DBPrefix."dailystatistics";
	$arr_result=$DMF->fetchArray($DMF->query($sql));
	$v_total=$arr_result['v_total'];
	//今天
	$sql="select sum(visits) as t_total from ".$DBPrefix."dailystatistics where visitDate='".$curDate."'";
	$arr_result=$DMF->fetchArray($DMF->query($sql));
	$t_total=$arr_result['t_total'];	
	//昨天
	$ydate=@strtotime($curDate);
	$yesterday=date("Y-m-d",mktime(0,0,0,date("m",$ydate),date("d",$ydate)-1,date("Y",$ydate)));
	$sql="select sum(visits) as y_total from ".$DBPrefix."dailystatistics where visitDate='".$yesterday."'";
	$arr_result=$DMF->fetchArray($DMF->query($sql));
	$y_total=$arr_result['y_total'];

	if ($v_total<1){$v_total=0;}
	if ($t_total<1){$t_total=0;}
	if ($y_total<1){$y_total=0;}
	
	$strOnline=($strOnline=="")?"Online":$strOnline;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--访问情况-->
<div class="sidepanel" id="Side_BlogInfo">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<div id="BlogInfo_Body">
	<?
	echo "$strStatisticsVisitsToday:".$t_total.", ";
	echo "$strStatisticsVisitsYesterday:".$y_total." <br>";
	echo "$strStatisticsVisitsTotal:".$v_total.", ";
	echo "$strOnline ".$online_count;

	do_action("f2_stat");
	?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 访问情况?>
<?
#日志说明
function side_description($sidename,$sidetitle,$isInstall){
	global $settingInfo;
	if (file_exists("./attachments/".$settingInfo['logo']) && $settingInfo['logo']!=""){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--说明-->
<div class="sidepanel" id="Side_Description">
  <div class="Pcontent">
	<div class="Description_Body">
	<p align="center">
	<?="<img src=\"./attachments/".$settingInfo['logo']."\" align=\"center\" />"?>
	<?="<br>".$settingInfo['name']."<br>".$settingInfo['blogTitle'];?>
	</p>
	</div>
  </div>
  <div class=Pfoot></div>
</div>
<?}}#END 说明?>
<?
#皮肤转换
function side_skin_switch($sidename,$sidetitle,$isInstall){
	global $blogSkins,$load;
	//列出Skins目录
	if ($action=="") {
		$handle=opendir("./skins/"); 
		while (false !== ($file = readdir($handle))) {			
			if(is_dir("./skins/$file") && file_exists("./skins/$file/skin.xml")){
				$dirlist[] = $file;
			}			
		} 
		closedir($handle); 
	}
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
	//print_r($dirlist);
?>
<!--皮肤转换-->
<div class="sidepanel" id="Side_SkinSwitch">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<div id="SkinSwitch_Body">
    <form name="skinForm" action="" method="post" style="margin:0px;">
      <input type="hidden" name="theme" value="-1">
      <select name="skinSelect" onchange="if (this.value!='0') {document.forms['skinForm'].theme.value=this.value;document.forms['skinForm'].submit();}">
		<?
		for ($i=0;$i<count($dirlist);$i++){
			$selected=($dirlist[$i]==$blogSkins)?"selected":"";
			echo "<option value=\"$dirlist[$i]\" $selected>$dirlist[$i]</option>\n";
	    }
	    ?>
      </select>
    </form>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 皮肤转换?>
<?
#类别
function side_category($sidename,$sidetitle,$isInstall){
	global $strAllCategory,$cfg_category_path;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--类别-->
<div class="sidepanel" id="Side_Category">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
    <div id="Category_Body">
	<?require("treemenu.inc.php")?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 说明?>
<?
#标签
function side_hotTags($sidename,$sidetitle,$isInstall){
	global $strTagsCount,$PHP_SELF;
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--标签-->
<div class="sidepanel" id="Side_HotTags">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
	<div id="HotTags_Body">
		<?
		list($maxTag,$minTag)=getTagRange();
		require("cache/cache_hottags.php");
		for ($i=0;$i<count($tagscache);$i++){
			$curColor=getTagHot($tagscache[$i]['logNums'],$maxTag,$minTag);
			$strDayLog=$strTagsCount.": ".$tagscache[$i]['logNums'];

			echo "<a href=\"$PHP_SELF?job=tag&seekname=".$tagscache[$i]['name']."\" style=\"color:$curColor\" title=\"$strDayLog\">".$tagscache[$i]['name']."</a> \n";
		}
		?>
	</div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END 标签?>
<?
#其它Side
function side_other($sidename,$sidetitle,$htmlcode,$isInstall){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--其它Side-->
<div id="Side_OtherSide" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?="content_$sidename"?>')"><?=$sidetitle?></h4>
  <div class="Pcontent" id="<?="content_$sidename"?>" style="display:<?=$display?>">
  	<div id="Other_Body"><?=dencode($htmlcode)?></div>
  </div>
  <div class="Pfoot"></div>
</div>
<?}#END Other?>
<?php 
if (isset($_COOKIE["content_BlogInfo"])){
	$display=$_COOKIE["content_BlogInfo"];
}else{
 	$display="";
}
?>
<!--統計-->
<div class="sidepanel" id="Side_BlogInfo">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_BlogInfo')">統計</h4>
  <div class="Pcontent" id="content_BlogInfo" style="display:<?php echo $display?>">
	<div class="BlogInfoTable" id="BlogInfo_Body">
今日訪問: <?php echo $cache_visits_today?><br />
昨日訪問: <?php echo $cache_visits_yesterday?><br />
總訪問量: <?php echo $cache_visits_total+$settingInfo['visitNums']?><br />
在線人數: <?php echo $online_count?><br />
日誌數量: <?php echo $settingInfo['setlogs']?><br />
評論數量: <?php echo $settingInfo['setcomments']?><br />
引用數量: <?php echo $settingInfo['settrackbacks']?><br />
註冊用戶: <?php echo $settingInfo['setmembers']?><br />
留言數量: <?php echo $settingInfo['setguestbook']?><?php do_action("f2_stat");?>	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 

<?php if (isset($_COOKIE["content_User"])){	$display=$_COOKIE["content_User"];}else{ 	$display="";}?><!--用戶面板--><div class="sidepanel" id="Side_User">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_User')">用戶面板</h4>  <div class="Pcontent" id="content_User" style="display:<?php echo $display?>">	<div class="UserTable" id="User_Body"><?php  if (!empty($_SESSION['username']) && $_SESSION['username']!="") { ?> 
<a href="register.php" class="sideA">修改用戶資料</a> 
<a href="login.php?action=logout" class="sideA">登出管理</a> 
<?php  } else { ?> 
<?php  if ($settingInfo['loginStatus']==0) { ?> 
<a href="login.php" class="sideA">登錄</a> 
<?php  } ?> 
<?php  if ($settingInfo['isRegister']==0) { ?> 
<a href="register.php" class="sideA">用戶註冊</a> 
<?php  } ?> 
<?php  } ?> 
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_Category"])){	$display=$_COOKIE["content_Category"];}else{ 	$display="";}?><!--類別--><div class="sidepanel" id="Side_Category">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Category')">類別</h4>  <div class="Pcontent" id="content_Category" style="display:<?php echo $display?>">	<div class="CategoryTable" id="Category_Body"><script type="text/javascript">
	function openCategory(category) {
		var oLevel1 = document.getElementById("category_" + category);
		var oImg = oLevel1.getElementsByTagName("img")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case "isleaf":
				return true;
			case "closed":
				oImg.src = "images/tree/base/tab_opened.gif";
				showLayer("category_" + category + "_children");
				expanded = true;
				return true;
			case "opened":
				oImg.src = "images/tree/base/tab_closed.gif";
				hideLayer("category_" + category + "_children");
				expanded = false;
				return true;
		}
		return false;
	}
	
	function showLayer(id) {
		document.getElementById(id).style.display = "block";
		return true;
	}
	
	function hideLayer(id) {
		document.getElementById(id).style.display = "none";
		return true;
	}	
</script>

<div id="treeComponent">
  <div id="category_0" style="line-height: 100%">
    <div style="display:inline;"><img src="images/tree/base/tab_top.gif" width="16" align="top" alt=""/></div>
    <div style="display:inline; vertical-align:middle; margin-left:3px; padding-left:3px; cursor:pointer;">
      <a href='index.php'>所有分類 (<?php echo (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?"40":"40"?>
)</a> <span class="rss"><a href='rss.php'>[RSS]</a></span></div>
  </div>
  <div id="category_1" style="line-height: 100%">
    <div style="display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click" onclick="openCategory('1')"><img src="images/tree/base/tab_opened.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="程序发布 - f2cont程序更新">
        <div id="text_1" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='category-1.html'>程序发布		(6)</a> <span class="rss"><a href='rss.php?cateID=1'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_1_children" style="display:">
        <div id="subcategory_00" style="line-height: 100%">
      <div style="display:inline;"><img src="images/tree/base/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/base/tab_treed_end.gif" width="22" align="top" alt=""/></div>
      <div style="display:inline;" title="修正补丁 - 问题的发现与修正">
        <div id="text_00" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-11.html'>修正补丁		(4)</a> <span class="rss"><a href='rss.php?cateID=11'>[RSS]</a></span></div>
      </div>
    </div>
      </div>
  <div id="category_category_2" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="模板专栏 - Skins for F2">
      <div id="text_2" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-2.html'>模板专栏	  (12)</a> <span class="rss"><a href='rss.php?cateID=2'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_category_3" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="转换程式 - 转换现有blog使用F2这套程序">
      <div id="text_3" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-9.html'>转换程式	  (0)</a> <span class="rss"><a href='rss.php?cateID=9'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_category_4" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="插件专栏 - Plugins for F2">
      <div id="text_4" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-3.html'>插件专栏	  (8)</a> <span class="rss"><a href='rss.php?cateID=3'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_category_5" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="程式增强 - 完善功能的各式Hack">
      <div id="text_5" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-7.html'>程式增强	  (3)</a> <span class="rss"><a href='rss.php?cateID=7'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_category_6" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="开发日志 - F2cont 小组程序开发notes">
      <div id="text_6" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-5.html'>开发日志	  (3)</a> <span class="rss"><a href='rss.php?cateID=5'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_category_7" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="疑问解答 - 官方解答及程序说明，帮助文档之类">
      <div id="text_7" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-8.html'>疑问解答	  (1)</a> <span class="rss"><a href='rss.php?cateID=8'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_8" style="line-height: 100%">
    <div style="display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')"><a class="click" onclick="openCategory('8')"><img src="images/tree/base/tab_opened.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="关于 - Free &amp; Freedom">
        <div id="text_8" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='category-4.html'>关于F2		(7)</a> <span class="rss"><a href='rss.php?cateID=4'>[RSS]</a></span></div>
    </div>
  </div>
      <div id="category_8_children" style="display:">
        <div id="subcategory_70" style="line-height: 100%">
      <div style="display:inline;"><img src="images/tree/base/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/base/tab_treed_end.gif" width="22" align="top" alt=""/></div>
      <div style="display:inline;" title="F2cont团队 - 让F2的理念继续下去">
        <div id="text_70" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-6.html'>f2cont		(3)</a> <span class="rss"><a href='rss.php?cateID=6'>[RSS]</a></span></div>
      </div>
    </div>
      </div>
  <div id="category_category_9" style="line-height: 100%">
    <div style="display:inline; background-image: url('images/tree/base/navi_back_noactive_end.gif')"><a class="click"><img src="images/tree/base/tab_isleaf.gif" align="top" alt=""/></a></div>
    <div style="display:inline;" title="网络资讯 - 了解前沿动态，不做井底之蛙">
      <div id="text_9" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-10.html'>外部资讯	  (0)</a> <span class="rss"><a href='rss.php?cateID=10'>[RSS]</a></span></div>
    </div>
  </div>
    </div>
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_Calendar"])){	$display=$_COOKIE["content_Calendar"];}else{ 	$display="";}?><!--日曆--><div class="sidepanel" id="Side_Calendar">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Calendar')">日曆</h4>  <div class="Pcontent" id="content_Calendar" style="display:<?php echo $display?>">	<div class="CalendarTable" id="Calendar_Body"><?php 
if (!empty($job) && $job=="calendar" && $seekname!=gmdate('Ym', time()+3600*$settingInfo['timezone'])){
	if ($settingInfo['showcalendar']==1){
		include("include/ncalendar.inc.php");
	}else{
		include("include/calendar.inc.php");
	}
}else{
	echo readfromfile(F2BLOG_ROOT."./cache/cache_calendar.php");
}?>
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_HotTags"])){	$display=$_COOKIE["content_HotTags"];}else{ 	$display="";}?><!--熱門標籤--><div class="sidepanel" id="Side_HotTags">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_HotTags')">熱門標籤</h4>  <div class="Pcontent" id="content_HotTags" style="display:<?php echo $display?>">	<div class="HotTagsTable" id="HotTags_Body">	<a href="tags-%E6%A8%A1%E6%9D%BF.html" style="color:#f60" title="網誌數量: 12">模板</a> 
	<a href="tags-Skin.html" style="color:#f60" title="網誌數量: 12">Skin</a> 
	<a href="tags-F2cont.html" style="color:#690" title="網誌數量: 7">F2cont</a> 
	<a href="tags-Plugin.html" style="color:#690" title="網誌數量: 6">Plugin</a> 
	<a href="tags-%E6%8F%92%E4%BB%B6.html" style="color:#690" title="網誌數量: 6">插件</a> 
	<a href="tags-TinyMCE.html" style="color:#09c" title="網誌數量: 4">TinyMCE</a> 
	<a href="tags-%E5%A2%9E%E5%BC%BA.html" style="color:#09c" title="網誌數量: 3">增强</a> 
	<a href="tags-%E5%8F%91%E5%B8%83.html" style="color:#09c" title="網誌數量: 3">发布</a> 
	<a href="tags-%E7%A8%8B%E5%BA%8F.html" style="color:#09c" title="網誌數量: 3">程序</a> 
	<a href="tags-%E5%8F%91%E5%B1%95%E5%8E%86%E5%8F%B2.html" style="color:#09c" title="網誌數量: 3">发展历史</a> 
	<a href="tags-%E7%BB%84%E5%91%98.html" style="color:#09c" title="網誌數量: 2">组员</a> 
	<a href="tags-%E5%9B%A2%E9%98%9F.html" style="color:#09c" title="網誌數量: 2">团队</a> 
	<a href="tags-F2blog.html" style="color:#09c" title="網誌數量: 2">F2blog</a> 
	<a href="tags-%E6%B5%8B%E8%AF%95.html" style="color:#999" title="網誌數量: 1">测试</a> 
	<a href="tags-%E7%BC%96%E8%BE%91%E5%99%A8.html" style="color:#999" title="網誌數量: 1">编辑器</a> 
	<a href="tags-%E6%A0%87%E9%A2%98%E6%A0%8F.html" style="color:#999" title="網誌數量: 1">标题栏</a> 
	<a href="tags-%E5%B0%8F%E6%97%A5%E5%8E%86.html" style="color:#999" title="網誌數量: 1">小日历</a> 
	<a href="tags-%E8%AF%84%E8%AE%BA.html" style="color:#999" title="網誌數量: 1">评论</a> 
	<a href="tags-%E7%95%99%E8%A8%80.html" style="color:#999" title="網誌數量: 1">留言</a> 
	<a href="tags-ver1.0.html" style="color:#999" title="網誌數量: 1">ver1.0</a> 
	<a href="tags-Hack.html" style="color:#999" title="網誌數量: 1">Hack</a> 
	<a href="tags-%E5%88%86%E9%A1%B5.html" style="color:#999" title="網誌數量: 1">分页</a> 
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_NewLogForPJBlog"])){	$display=$_COOKIE["content_NewLogForPJBlog"];}else{ 	$display="";}?><!--最新網誌--><div class="sidepanel" id="Side_NewLogForPJBlog">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_NewLogForPJBlog')">最新網誌</h4>  <div class="Pcontent" id="content_NewLogForPJBlog" style="display:<?php echo $display?>">	<div class="NewLogForPJBlogTable" id="NewLogForPJBlog_Body">	<a class="sideA" id="NewLog_Link_41" title="堕落程式 於 2009-05-08 11:59 發表 感謝各位的支持" href="read-41.html">堕落程式: 感謝各位的支持</a> 
	<a class="sideA" id="NewLog_Link_40" title="堕落程式 於 2009-05-04 16:54 發表 F2 優化" href="read-40.html">堕落程式: F2 優化</a> 
	<a class="sideA" id="NewLog_Link_39" title="堕落程式 於 2009-05-04 12:14 發表 F2 招聘 人員" href="read-39.html">堕落程式: F2 招聘 人員</a> 
	<a class="sideA" id="NewLog_Link_38" title="堕落程式 於 2009-05-04 12:08 發表 F2將由程序員接手" href="read-38.html">堕落程式: F2將由程序員接手</a> 
	<a class="sideA" id="NewLog_Link_37" title="风之逸 於 2009-03-24 14:02 發表 F2的终点还是起点" href="read-37.html">风之逸: F2的终点还是起点</a> 
	<a class="sideA" id="NewLog_Link_36" title="实验小白鼠 於 2009-02-28 17:20 發表 MG相册 1.31 升级版 by 蘿莉 寶兒" href="read-36.html">实验小白鼠: MG相册 1.31 升级</a> 
	<a class="sideA" id="NewLog_Link_35" title="实验小白鼠 於 2008-12-17 15:48 發表 F2blog.Cont RSS 修正补丁 by Yuki" href="read-35.html">实验小白鼠: F2blog.Cont </a> 
	<a class="sideA" id="NewLog_Link_34" title="Enny 於 2008-12-10 22:06 發表 【模板】狗狗物語" href="read-34.html">Enny: 【模板】狗狗物語</a> 
	<a class="sideA" id="NewLog_Link_33" title="风之逸 於 2008-12-07 12:38 發表 文章分页 - 插件支持 - 功能修复" href="read-33.html">风之逸: 文章分页 - 插件支持 </a> 
	<a class="sideA" id="NewLog_Link_32" title="风之逸 於 2008-12-06 22:47 發表 分页测试" href="read-32.html">风之逸: 分页测试</a> 
	<a class="sideA" id="NewLog_Link_31" title="实验小白鼠 於 2008-12-06 00:14 發表 〖插件〗编辑区右键菜单 for F2blog TinyMCE编辑器" href="read-31.html">实验小白鼠: 〖插件〗编辑区右键菜单 </a> 
	<a class="sideA" id="NewLog_Link_30" title="实验小白鼠 於 2008-12-05 17:01 發表 「编辑器」F2blog 外挂编辑器 TinyMCE 2.1.3" href="read-30.html">实验小白鼠: 「编辑器」F2blog </a> 
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_Comment"])){	$display=$_COOKIE["content_Comment"];}else{ 	$display="";}?><!--最新評論--><div class="sidepanel" id="Side_Comment">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Comment')">最新評論</h4>  <div class="Pcontent" id="content_Comment" style="display:<?php echo $display?>">	<div class="CommentTable" id="Comment_Body">	<a class="sideA" id="comments_Link_204" title="sun007002 於 2009-05-09 01:02 發表評論 以前我也是F2blog的使用者，其实F2真的不错，不管是在功能上还是在速度上，算是集合了当今许多类型博客的有点。就是宣传少了一些，知道的人不多，大家的关注不够罢了。希望F2在将来会有好的发展吧。"  href="read-41.html#book204">sun007002: 以前我也是F2blog的</a> 	<a class="sideA" id="comments_Link_203" title="一片空白 於 2009-05-08 20:20 發表評論 留言版吧，感覺蠻陽春的，尤其是回覆的留言，與一般留言希望能有較明顯的差異。"  href="read-38.html#book203">一片空白: 留言版吧，感覺蠻陽春的，</a> 	<a class="sideA" id="comments_Link_202" title="一片空白 於 2009-05-08 19:46 發表評論 眼前已經漸漸浮起F2閃亮的未來....華人的驕傲...大家加油^^"  href="read-41.html#book202">一片空白: 眼前已經漸漸浮起F2閃亮</a> 	<a class="sideA" id="comments_Link_201" title="Yuki 於 2009-05-08 11:22 發表評論 堕落程式太客氣了~~ 事實上我腦中只是浮現當時f2blog的官網...XD當時我就是這樣被騙進來了~~哈哈官方論壇我覺得很棒咧~~如果有的話真的是太棒了~會有很多魔人改了很多plugin提供上來~~期待ing"  href="read-39.html#book201">Yuki: 堕落程式太客氣了~~ 
	<a class="sideA" id="Link_2" title="SaBlog" href="http://www.sablog.net/" target="_blank">SaBlog</a> 
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_Links1"])){	$display=$_COOKIE["content_Links1"];}else{ 	$display="";}?><!--F2.Cont小组--><div class="sidepanel" id="Side_Links1">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Links1')">F2.Cont小组</h4>  <div class="Pcontent" id="content_Links1" style="display:<?php echo $display?>">	<div class="Links1Table" id="Links1_Body">	<a class="sideA" id="Link_4" title="堕落程式" href="http://blog.phptw.idv.tw/" target="_blank">堕落程式</a> 
	<a class="sideA" id="Link_5" title="真空实验室" href="http://blog.tgb.net.cn/" target="_blank">真空实验室</a> 
	<a class="sideA" id="Link_3" title="逸林轩" href="http://www.rainboww.net/" target="_blank">逸林轩</a> 
	<a class="sideA" id="Link_6" title="九里河畔" href="http://www.xpboy.com/" target="_blank">九里河畔</a> 
	<a class="sideA" id="Link_8" title="伊氏部落格" href="http://www.eqq.us/" target="_blank">伊氏部落格</a> 
	<a class="sideA" id="Link_9" title="TinyLog" href="http://www.tinylog.org/" target="_blank">TinyLog</a> 
	<a class="sideA" id="Link_7" title="桔絲9" href="http://jas9.com/" target="_blank">桔絲9</a> 
	</div>   </div>   <div class="Pfoot"></div> </div> <?php if (isset($_COOKIE["content_BlogInfo"])){	$display=$_COOKIE["content_BlogInfo"];}else{ 	$display="";}?><!--統計--><div class="sidepanel" id="Side_BlogInfo">  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_BlogInfo')">統計</h4>  <div class="Pcontent" id="content_BlogInfo" style="display:<?php echo $display?>">	<div class="BlogInfoTable" id="BlogInfo_Body">今日訪問: <?php echo $cache_visits_today?><br />
昨日訪問: <?php echo $cache_visits_yesterday?><br />
總訪問量: <?php echo $cache_visits_total+$settingInfo['visitNums']?><br />
在線人數: <?php echo $online_count?><br />
日誌數量: <?php echo $settingInfo['setlogs']?><br />
評論數量: <?php echo $settingInfo['setcomments']?><br />
引用數量: <?php echo $settingInfo['settrackbacks']?><br />
註冊用戶: <?php echo $settingInfo['setmembers']?><br />
留言數量: <?php echo $settingInfo['setguestbook']?><?php do_action("f2_stat");?>	</div>   </div>   <div class="Pfoot"></div> </div> 
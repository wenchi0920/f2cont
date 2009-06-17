<?php 
if (isset($_COOKIE["content_Category"])){
	$display=$_COOKIE["content_Category"];
}else{
 	$display="";
}
?>
<!--類別-->
<div class="sidepanel" id="Side_Category">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Category')">類別</h4>
  <div class="Pcontent" id="content_Category" style="display:<?php echo $display?>">
	<div class="CategoryTable" id="Category_Body">
<script type="text/javascript">
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
	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 

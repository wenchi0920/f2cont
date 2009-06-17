<?php 
if (isset($_COOKIE["content_HotTags"])){
	$display=$_COOKIE["content_HotTags"];
}else{
 	$display="";
}
?>
<!--熱門標籤-->
<div class="sidepanel" id="Side_HotTags">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_HotTags')">熱門標籤</h4>
  <div class="Pcontent" id="content_HotTags" style="display:<?php echo $display?>">
	<div class="HotTagsTable" id="HotTags_Body">
	<a href="tags-%E6%A8%A1%E6%9D%BF.html" style="color:#f60" title="網誌數量: 12">模板</a> 
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
	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 

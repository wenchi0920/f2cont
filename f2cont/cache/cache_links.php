<?php 
if (isset($_COOKIE["content_Links"])){
	$display=$_COOKIE["content_Links"];
}else{
 	$display="";
}
?>
<!--程式链接-->
<div class="sidepanel" id="Side_Links">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Links')">程式链接</h4>
  <div class="Pcontent" id="content_Links" style="display:<?php echo $display?>">
	<div class="LinksTable" id="Links_Body">
	<a class="sideA" id="Link_1" title="F2Cont" href="http://www.f2cont.com/" target="_blank">F2Cont</a> 
	<a class="sideA" id="Link_2" title="SaBlog" href="http://www.sablog.net/" target="_blank">SaBlog</a> 
	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 
<?php 
if (isset($_COOKIE["content_Links1"])){
	$display=$_COOKIE["content_Links1"];
}else{
 	$display="";
}
?>
<!--F2.Cont小组-->
<div class="sidepanel" id="Side_Links1">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Links1')">F2.Cont小组</h4>
  <div class="Pcontent" id="content_Links1" style="display:<?php echo $display?>">
	<div class="Links1Table" id="Links1_Body">
	<a class="sideA" id="Link_4" title="堕落程式" href="http://blog.phptw.idv.tw/" target="_blank">堕落程式</a> 
	<a class="sideA" id="Link_5" title="真空实验室" href="http://blog.tgb.net.cn/" target="_blank">真空实验室</a> 
	<a class="sideA" id="Link_3" title="逸林轩" href="http://www.rainboww.net/" target="_blank">逸林轩</a> 
	<a class="sideA" id="Link_6" title="九里河畔" href="http://www.xpboy.com/" target="_blank">九里河畔</a> 
	<a class="sideA" id="Link_8" title="伊氏部落格" href="http://www.eqq.us/" target="_blank">伊氏部落格</a> 
	<a class="sideA" id="Link_9" title="TinyLog" href="http://www.tinylog.org/" target="_blank">TinyLog</a> 
	<a class="sideA" id="Link_7" title="桔絲9" href="http://jas9.com/" target="_blank">桔絲9</a> 
	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 

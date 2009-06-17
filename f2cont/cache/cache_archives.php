<?php 
if (isset($_COOKIE["content_Archive"])){
	$display=$_COOKIE["content_Archive"];
}else{
 	$display="";
}
?>
<!--歸檔-->
<div class="sidepanel" id="Side_Archive">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Archive')">歸檔</h4>
  <div class="Pcontent" id="content_Archive" style="display:<?php echo $display?>">
	<div class="ArchiveTable" id="Archive_Body">
	<a class="sideA" id="Archive_Link_1" href="archives-200905.html">2009 年 05 月 [4]</a> 
	<a class="sideA" id="Archive_Link_2" href="archives-200903.html">2009 年 03 月 [1]</a> 
	<a class="sideA" id="Archive_Link_3" href="archives-200902.html">2009 年 02 月 [1]</a> 
	<a class="sideA" id="Archive_Link_4" href="archives-200812.html">2008 年 12 月 [10]</a> 
	<a class="sideA" id="Archive_Link_5" href="archives-200811.html">2008 年 11 月 [24]</a> 
	</div> 
  </div> 
  <div class="Pfoot"></div> 
</div> 

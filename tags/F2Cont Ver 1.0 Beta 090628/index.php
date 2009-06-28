<?php 
include_once("include/function.php");

//开启与关闭BLOG
if ($settingInfo['status']) include_once("include/closelogs.inc.php");

//装载主体项目
include_once("include/loadbar.inc.php");

//装载头部文件
include_once("header.php");

//如果*.big.php文件，不装载默认侧边栏
if (strpos($load_file,".big.php")>0){
	include_once(F2BLOG_ROOT."./$load_file");
}else{
?>
  <!--内容-->
  <div id="Tbody"> 
  	<!--正文-->
    <div id="mainContent">
      <div id="innermainContent">
        <div id="mainContent-topimg"></div>
          <?php include_once("include/read_main.php")?>
          <!--主体部分-->
          <?php include_once(F2BLOG_ROOT."./$load_file")?>
		  <?php include_once("include/read_main_foot.php")?>
        <div id="mainContent-bottomimg"></div>
      </div>
    </div>
	<!--处理侧边栏-->
    <div id="sidebar">
      <div id="innersidebar">
        <div id="sidebar-topimg"><!--工具条顶部图象--></div>
		<!--侧边栏显示内容-->
        <?php 
			if ($load=="") {
				include_once("cache/cache_logs_sidebar.php");
			} else { 
				include_once("cache/cache_logs_readsidebar.php");
			}
		?>
        <div id="sidebar-bottomimg"></div>
      </div>
    </div>
    <div style="clear: both;height:1px;overflow:hidden;margin-top:-1px;"></div>
  </div>
<?php }//主体内容?>
<?php include_once("footer.php")?>

<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>

  <div id="content">
	<div class="contenttitle"><?php echo $title?></div>
	<div class="subcontent">
		<iframe id="advPlugin" src="<?php echo $adv?>" width="100%" height="100%" frameborder=0 scrolling=no ></iframe>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn.$strPluginSetting?>" onclick="location.href='<?php echo $PHP_SELF?>'">
	</div>

  </div>
<?php  dofoot(); ?>

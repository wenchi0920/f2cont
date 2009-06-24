<?php 
$PATH="../";
include("./function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strForumData;
$title=$strForumData;
dohead($title,"");
require('admin_menu.php');
?>
<body>

<div id="content">

    <div class="contenttitle"><?php echo $title?></div>
	<?php if ($settingInfo['forum_user']==""){?>
	<div id="updateInfo" style="margin:10px;"><a href="setting.php?set=1"><?php echo $strForumUsernameError?></a></div>
	<?php }else{?>
	<div id="updateInfo" style="margin:10px;"></div>
	<script src="http://www.f2blog.com/f2forum.php?forum_user=<?php echo $settingInfo['forum_user']?>"></script>
	<?php }?>
</div>

<?php  dofoot(); ?>
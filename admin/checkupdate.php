<?php 
$PATH="../";
include("./function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strCheckUpdate;
$title=$strCheckUpdate;
dohead($title,"");
require('admin_menu.php');
?>
<body>

<div id="content">

    <div class="contenttitle"><?php echo $title?></div>

	<div id="updateInfo" style="margin:10px;"></div>
	<script>
	var CVersion="<?php echo blogVersion?>"
	</script>
	<script src="http://www.f2blog.com/f2update.php?language=<?php echo $settingInfo['language']?>"></script>
</div>

<?php  dofoot(); ?>
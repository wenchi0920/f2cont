<?php
# 禁止直接访问该$strPage面
if (basename($_SERVER['PHP_SELF']) == "aboutMe.inc.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

//取得插件ID
if (file_exists("cache/cache_modulesSetting.php")){
	include("cache/cache_modulesSetting.php");
	if (count($plugins_aboutMe)>0){
		foreach($plugins_aboutMe as $key=>$value){
			$file_name=strtolower($value);
			if (strpos($file_name,".gif")>0 || strpos($file_name,".jpg")>0 || strpos($file_name,".jpeg")>0 || strpos($file_name,".png")>0){
				$face_path=$value;
			}else{
				$mydata[$key]=$value;
			}
		}
	}else{
		die("插件没有安装好！");
	}
}
//print_r($mydata[0]);
?>
<div class="Content">
  <div class="Content-top">
    <div class="ContentLeft"></div>
    <div class="ContentRight"></div>
		<h1 class="ContentTitle">个人档案</h1>
		<h2 class="ContentAuthor">About Me</h2>
  </div>
  <div class="Content-body">
	<?php if ($face_path!=""){?><img src="<?php echo $face_path?>" alt="" border="0" align="left" style="margin-right:6px"/><?php }?>
    <table border="0" cellspacing="0" cellpadding="0">
      <?php
	  foreach($mydata as $key=>$value){
	  ?>
	  <tr>
        <td class="commenttop" style="padding:2px;width:150px;"><nobr><?php echo $key?>: </nobr></td>
        <td class="commenttop" style="padding:2px;"><?php echo $value?></td>
      </tr>
	  <?php }?>
    </table>
  </div>
</div>

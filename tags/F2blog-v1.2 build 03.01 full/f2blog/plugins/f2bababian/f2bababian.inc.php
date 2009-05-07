<?php
if (empty($bbbphoto)){$bbbphoto="myphoto";}
if (!empty($_GET['bbbphoto'])){$bbbphoto=$_GET['bbbphoto'];}
$page=(!empty($_GET['page']))?$_GET['page']:"";
$load=$_GET['load'];
if ($page<1){$page=1;}

if ($settingInfo['rewrite']==0) $loadurl="index.php?load=$load&bbbphoto=";
if ($settingInfo['rewrite']==1) $loadurl="rewrite.php/$load-";
if ($settingInfo['rewrite']==2) $loadurl="$load-";

//取得插件ID
if (file_exists("cache/cache_modulesSetting.php")){
	include("cache/cache_modulesSetting.php");
	if (count($plugins_f2bababian)>0){
		$bbb_api_key=$plugins_f2bababian['bbb_api_key'];
		$bbb_user_key=$plugins_f2bababian['bbb_user_key'];
		$bbb_user_id=$plugins_f2bababian['bbb_user_id'];
		$bbb_per_row=$plugins_f2bababian['bbb_per_row'];
		$bbb_per_page=$plugins_f2bababian['bbb_per_page'];
		$bbb_size=$plugins_f2bababian['bbb_size'];
		$bbb_showimage=$plugins_f2bababian['bbb_showimage'];
	}else{
		die("插件没有安装好！");
	}
}

if ($bbb_api_key=="" || $bbb_user_key=="" || $bbb_user_id=="" || $bbb_per_row=="" || $bbb_per_page=="" || $bbb_size=="") die("插件没有设定好！");

//取得相片
include ('plugins/f2bababian/BabaBian.php');
$GoBind=new BabaBian();
$GoBind->api_key=$bbb_api_key;
//echo $bbb_per_row."===".$bbb_per_page."===".$bbb_size;

if (!in_array($bbbphoto,array("myphoto","setphoto","mykey","hotphoto"))) $bbbphoto="myphoto";
switch ($bbbphoto){			
	case "setphoto":$subtitle="我的专辑";break;
	case "mykey":$subtitle="我的关键字";break;
	case "hotphoto":$subtitle="巴巴变精彩照片";break;
	default:$subtitle="我的相册";
}
?>
<div id="Content_ContentList" class="content-width">
  <div class="Content">
    <div class="Content-top">
      <div class="ContentLeft"></div>
      <div class="ContentRight"></div>
      <h1 class="ContentTitle"><span style="font-weight: bold">巴巴变相册 (<?php echo $subtitle?>)</span></h1>
      <h2 class="ContentAuthor">Bababian Photo </h2>
    </div>
    <div class="Content-body">
      <div class="ContentToolsBar">
		<a href="<?php echo $loadurl."myphoto".$settingInfo['stype']?>">我的相册</a> | <a href="<?php echo $loadurl."setphoto".$settingInfo['stype']?>">我的专辑</a> | <a href="<?php echo $loadurl."mykey".$settingInfo['stype']?>">我的关键字</a> | <a href="<?php echo $loadurl."hotphoto".$settingInfo['stype']?>">巴巴变精彩照片</a>
	  </div>
      <?php
		//取得我的相片
		if ($bbbphoto=="myphoto" && empty($_GET['did'])){
			include("photo_myphoto.inc.php");
		}

		//取得我的专辑
		if ($bbbphoto=="setphoto" && empty($_GET['setid']) && empty($_GET['did'])){
			include("photo_setlist.inc.php");
		}

		//取得某专辑的所有图片
		if ($bbbphoto=="setphoto" && !empty($_GET['setid']) && empty($_GET['did'])){
			include("photo_setread.inc.php");
		}

		//取得巴巴最新图片
		if ($bbbphoto=="hotphoto" && empty($_GET['did'])){
			include("photo_hotphoto.inc.php");
		}

		//取得我的关键字
		if ($bbbphoto=="mykey" && empty($_GET['did'])){
			include("photo_keyword.inc.php");
		}

		//读取相片
		if (!empty($_GET['did'])){
			include("photo_info.inc.php");
		}
		?>
    </div>
  </div>
</div>

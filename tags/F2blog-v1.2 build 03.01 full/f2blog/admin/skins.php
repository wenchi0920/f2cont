<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=5;
$mtitle=$strSkinSetting;

//输出头部信息
$title=$strSkinSetting;
$action=$_GET['action'];

//保存
$err=0;
if ($action=="save"){
	$skinPath=$_GET['skinPath'];
	$sql="update ".$DBPrefix."setting set settValue='".$skinPath."' where settName='defaultSkin'";
	$DMC->query($sql);
	
	//输出Cache
	settings_recache();

	//更新cookie
	setcookie("blogSkins",$skinPath,time()+86400*365,$cookiepath,$cookiedomain);

	$ActionMesssage=$strSaveSuccess;
	$action="";

	$settingInfo['defaultSkin']=$skinPath;
}

//列出Skins目录
if ($action=="") {
    $handle=opendir("../skins/"); 
    while ($file = readdir($handle)){ 
		if(file_exists("../skins/$file/skin.xml")){
			$dirlist[] = $file;
		}	
	} 
    closedir($handle);

	//重新生成cache
	skinlist_recache();
}


dohead($title,"");
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">

	  <div class="contenttitle"><?php echo $title?></div>
	  <br>
	  <div class="subcontent">
		<table width="97%"  border="0" cellspacing="0" cellpadding="0">
		  <?php 
		$totSkin=count($dirlist);
		$totRows=ceil($totSkin/3);
		for ($i=0;$i<$totSkin;$i=$i+3) {
			$arrSkin=getSkinInfo($dirlist[$i]);
			$arrSkin1=($i+1<$totSkin)?getSkinInfo($dirlist[$i+1]):"";
			$arrSkin2=($i+2<$totSkin)?getSkinInfo($dirlist[$i+2]):"";
			?>
		  <tr>
			<td >
			  <table class="skinboxfont" width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr class="<?php echo $arrSkin['defSkin']?>">
				  <td width="20">&nbsp;</td>
				  <td width="100"><img src="<?php echo $arrSkin['preview']?>" alt="" border="0" class="skinimg" width="100" height="100"/></td>
				  <td width="20">&nbsp;</td>
				  <td>
					<p><strong>
					  <?php echo $arrSkin['SkinName']?>
					  </strong></p>
					<p><strong>
					  <?php echo $strDesigner?>
					  </strong><a href="<?php echo $arrSkin['DesignerURL']?>">
					  <?php echo $arrSkin['SkinDesigner']?>
					  </a><br>
					  <strong>
					  <?php echo $strPubDate?>
					  </strong>
					  <?php echo $arrSkin['pubDate']?>
					  <br>
					  <a href="<?php echo "$PHP_SELF?action=save&skinPath=".$dirlist[$i]?>">
					  <?php echo $strSetSkin?>
					  </a> </p>
				  </td>
				</tr>
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			</td>
			<td width="20">&nbsp;</td>
			<td>
			  <table class="skinboxfont" width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<?php if ($arrSkin1){?>
				<tr class="<?php echo $arrSkin1['defSkin']?>">
				  <td width="20">&nbsp;</td>
				  <td width="100"><img src="<?php echo $arrSkin1['preview']?>" alt="" border="0" class="skinimg" width="100" height="100"/></td>
				  <td width="20">&nbsp;</td>
				  <td>
					<p><strong>
					  <?php echo $arrSkin1['SkinName']?>
					  </strong></p>
					<p><strong>
					  <?php echo $strDesigner?>
					  </strong><a href="<?php echo $arrSkin1['DesignerURL']?>">
					  <?php echo $arrSkin1['SkinDesigner']?>
					  </a><br>
					  <strong>
					  <?php echo $strPubDate?>
					  </strong>
					  <?php echo $arrSkin1['pubDate']?>
					  <br>
					  <a href="<?php echo "$PHP_SELF?action=save&skinPath=".$dirlist[$i+1]?>">
					  <?php echo $strSetSkin?>
					  </a> </p>
				  </td>
				</tr>
				<?php }else{?>
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<?php }?>
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
			  </table>
			</td>
			<td width="20">&nbsp;</td>
			<td>
<table class="skinboxfont" width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<?php if ($arrSkin2){?>
				<tr class="<?php echo $arrSkin2['defSkin']?>">
				  <td width="20">&nbsp;</td>
				  <td width="100"><img src="<?php echo $arrSkin2['preview']?>" alt="" border="0" class="skinimg" width="100" height="100"/></td>
				  <td width="20">&nbsp;</td>
				  <td>
					<p><strong>
					  <?php echo $arrSkin2['SkinName']?>
					  </strong></p>
					<p><strong>
					  <?php echo $strDesigner?>
					  </strong><a href="<?php echo $arrSkin2['DesignerURL']?>">
					  <?php echo $arrSkin2['SkinDesigner']?>
					  </a><br>
					  <strong>
					  <?php echo $strPubDate?>
					  </strong>
					  <?php echo $arrSkin2['pubDate']?>
					  <br>
					  <a href="<?php echo "$PHP_SELF?action=save&skinPath=".$dirlist[$i+2]?>">
					  <?php echo $strSetSkin?>
					  </a> </p>
				  </td>
				</tr>
				<?php }else{?>
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<?php }?>
				<tr>
				  <td width="20">&nbsp;</td>
				  <td width="100" height="20">&nbsp;</td>
				  <td width="20">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
			  </table>			
			  </td>
		  </tr>
		  <?php }?>
		</table>
	  </div>

  </div>
</form>
<?php  dofoot(); ?>

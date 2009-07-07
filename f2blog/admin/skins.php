<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//输出头部信息
$title=$strSkinSetting;
$action=$_GET['action'];

//保存
$err=0;
if ($action=="save"){
	$skinPath=$_GET['skinPath'];
	$userInfo = $DMC->query("update ".$DBPrefix."setting set defaultSkin='$skinPath' WHERE id='1'");
	
	//输出Cache
	settings_recache();

	//更新cookie
	setcookie("blogSkins",$skinPath,time()+86400*365,substr($PHP_SELF,0,strrpos($PHP_SELF,"/admin")));

	$ActionMesssage=$strSaveSuccess;
	$action="";

	$settingInfo['defaultSkin']=$skinPath;
}

//列出Skins目录
if ($action=="") {
    $handle=opendir("../skins/"); 
    while ($file = readdir($handle)){ 
		if(is_dir("../skins/$file") && file_exists("../skins/$file/skin.xml")){
			$dirlist[] = $file;
		}	
	} 
    closedir($handle); 
	//print_r($dirlist);
}


dohead($title,"");
?>

<form action="" method="post" name="seekform">
  <div id="content">
  <div class="box">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="6" height="20"><img src="images/main/content_lt.gif" width="6" height="21"></td>
        <td height="21" background="images/main/content_top.gif">&nbsp;</td>
        <td width="6" height="20"><img src="images/main/content_rt.gif" width="6" height="21"></td>
      </tr>
      <tr>
        <td width="6" background="images/main/content_left.gif">&nbsp;</td>
        <td bgcolor="#FFFFFF" >
          <div class="contenttitle"><img src="images/content/cache.gif" width="12" height="11">
            <?=$title?>
            <div class="page">
              <?view_page($page_url)?>
            </div>
          </div>
          <br>
          <div class="subcontent">
            <table width="97%"  border="0" cellspacing="0" cellpadding="0">
              <?
	$totSkin=count($dirlist);
	$totRows=ceil($totSkin/2);
	for ($i=0;$i<$totSkin;$i=$i+2) {
		$arrSkin=getSkinInfo($dirlist[$i],"..");
		//echo $dirlist[$i]."==".$dirlist[$i+1];
		if ($i+1<$totSkin){
			$arrSkin1=getSkinInfo($dirlist[$i+1],"..");
		}else{
			$arrSkin1="";
		}
?>
              <tr>
                <td class="skinbox" >
                  <table class="skinboxfont" width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="20">&nbsp;</td>
                      <td width="100" height="20">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr class="<?=$arrSkin['defSkin']?>">
                      <td width="20">&nbsp;</td>
                      <td width="100"><img src="<?=$arrSkin['preview']?>" alt="" border="0" class="skinimg" width="100" height="100"/></td>
                      <td width="20">&nbsp;</td>
                      <td>
                        <p><strong>
                          <?=$arrSkin['SkinName']?>
                          </strong></p>
                        <p><strong>
                          <?=$strDesigner?>
                          </strong><a href="<?=$arrSkin['DesignerURL']?>">
                          <?=$arrSkin['SkinDesigner']?>
                          </a><br>
                          <strong>
                          <?=$strPubDate?>
                          </strong>
                          <?=$arrSkin['pubDate']?>
                          <br>
                          <a href="<?="$PHP_SELF?action=save&skinPath=".$dirlist[$i]?>">
                          <?=$strSetSkin?>
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
                <td class="skinbox">
                  <table class="skinboxfont" width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="20">&nbsp;</td>
                      <td width="100" height="20">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <?if ($arrSkin1){?>
                    <tr class="<?=$arrSkin1['defSkin']?>">
                      <td width="20">&nbsp;</td>
                      <td width="100"><img src="<?=$arrSkin1['preview']?>" alt="" border="0" class="skinimg" width="100" height="100"/></td>
                      <td width="20">&nbsp;</td>
                      <td>
                        <p><strong>
                          <?=$arrSkin1['SkinName']?>
                          </strong></p>
                        <p><strong>
                          <?=$strDesigner?>
                          </strong><a href="<?=$arrSkin1['DesignerURL']?>">
                          <?=$arrSkin1['SkinDesigner']?>
                          </a><br>
                          <strong>
                          <?=$strPubDate?>
                          </strong>
                          <?=$arrSkin1['pubDate']?>
                          <br>
                          <a href="<?="$PHP_SELF?action=save&skinPath=".$dirlist[$i+1]?>">
                          <?=$strSetSkin?>
                          </a> </p>
                      </td>
                    </tr>
                    <?}else{?>
                    <tr>
                      <td width="20">&nbsp;</td>
                      <td width="100" height="20">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <?}?>
                    <tr>
                      <td width="20">&nbsp;</td>
                      <td width="100" height="20">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <?}?>
            </table>
          </div>
        </td>
        <td width="6" background="images/main/content_right.gif">&nbsp;</td>
      </tr>
      <tr>
        <td width="6" height="20"><img src="images/main/content_lb.gif" width="6" height="20"></td>
        <td height="20" background="images/main/content_bottom.gif">&nbsp;</td>
        <td width="6" height="20"><img src="images/main/content_rb.gif" width="6" height="20"></td>
      </tr>
    </table>
  </div>
</form>
<? dofoot(); ?>

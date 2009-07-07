<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//输出头部信息
$title=$strGeneralSetting;
dohead($title,"");

$action=$_GET['action'];

//保存
$err=0;
if ($action=="save"){
	$checkinfo=true;
	
	$name=$_POST['name'];
	$blogTitle=$_POST['blogTitle'];
	$blogUrl=$_POST['blogUrl'];
	$logo_file=$_FILES["logo"]["tmp_name"];
	$favicon_file=$_FILES["favicon"]["tmp_name"];
	$logo_hidden=$_POST['logo_hidden'];
	$favicon_hidden=$_POST['favicon_hidden'];
	$about=$_POST['about'];
	$master=$_POST['master'];
	$email=$_POST['email'];
	$disType=$_POST['disType'];
	$perPageNormal=$_POST['perPageNormal'];
	$perPageList=$_POST['perPageList'];
	$isLinkTagLog=$_POST['isLinkTagLog'];
	$linkTagLog=$_POST['linkTagLog'];
	$isProgramRun=$_POST['isProgramRun'];
	$commTimerout=$_POST['commTimerout'];
	$commLength=$_POST['commLength'];
	$isValidateCode=$_POST['isValidateCode'];
	$isRegister=$_POST['isRegister'];
	$status=$_POST['status'];
	$closeReason=$_POST['closeReason'];
	$language=$_POST['language'];
	$timezone=$_POST['timezone'];
	$timeSystemFormat=$_POST['timeSystemFormat'];
	$isTbApp=$_POST['isTbApp'];
	$tbSiteList=$_POST['tbSiteList'];
	$newRss=$_POST['newRss'];
	$rssContentType=$_POST['rssContentType'];
	$tagNums=$_POST['tagNums'];

	if (substr($blogUrl,strlen($blogUrl)-1,1)!="/"){$blogUrl=$blogUrl."/";}
	
	if ($name=="" or $blogTitle=="" or $blogUrl=="" or $master=="" or $email=="" or $disType=="" or $perPageNormal=="" or $perPageList=="" or $commTimerout=="" or $commLength=="" or $timeSystemFormat=="" or $newRss=="") {
		$checkinfo=false;
		$ActionMessage=$strErrNull;
	}
	
	if ($checkinfo && !check_email($email)) {
		$checkinfo=false;
		$ActionMessage=$strEmail.$strErrEmail;
	}

	if ($checkinfo && !check_number($perPageNormal)) {
		$checkinfo=false;
		$ActionMessage=$strPerPageNormal.$strErrNumber;
	}

	if ($checkinfo && !check_number($perPageList)) {
		$checkinfo=false;
		$ActionMessage=$strPerPageList.$strErrNumber;
	}

	if ($checkinfo && !check_number($commTimerout)) {
		$checkinfo=false;
		$ActionMessage=$strCommTimerout.$strErrNumber;
	}

	if ($checkinfo && !check_number($commLength)) {
		$checkinfo=false;
		$ActionMessage=$strCommLength.$strErrNumber;
	}

	if ($checkinfo && !check_number($newRss)) {
		$checkinfo=false;
		$ActionMessage=$strNewRss.$strErrNumber;
	}

	if ($checkinfo && $isLinkTagLog && !check_number($linkTagLog)) {
		$checkinfo=false;
		$ActionMessage=$LinkTagLog.$strErrNumber;
	}

	if ($checkinfo && $status && $closeReason=="") {
		$checkinfo=false;
		$ActionMessage=$CloseReason.$strErrNull;
	}

	//检测Logo & Favicon的尺寸
	if ($checkinfo && !check_image_type($_FILES["logo"]["name"])) {
		$checkinfo=false;
		$ActionMessage=$strLogo.$strImgTypeMemo;
	}

	if ($checkinfo && !check_image_type($_FILES["favicon"]["name"])) {
		$checkinfo=false;
		$ActionMessage=$strFavicon.$strImgTypeMemo;
	}
	
	if ($_FILES["logo"]["name"]!="" && $checkinfo) {
		$arrISize=get_image_size($_FILES["logo"]["tmp_name"]);
		/*if ($arrISize[1]>100 && $arrISize[3]>100) {
			$checkinfo=false;
			$ActionMessage=$strLogoMemo;
		}*/
	}

	if ($_FILES["favicon"]["name"]!="" && $checkinfo) {
		$arrISize=get_image_size($_FILES["favicon"]["tmp_name"]);
		/*if ($checkinfo && $arrISize[1]>100 && $arrISize[3]>100) {
			$checkinfo=false;
			$ActionMessage=$strFaviconMemo;
		}*/
	}

	//Save
	if ($checkinfo) {
		//上传Logo && Favicon文件
		if ($_FILES["logo"]["name"]=="") {
			$logo=$logo_hidden;
		} else {
			$logo=upload_file($logo_file,$_FILES["logo"]["name"],"../attachments");
		}

		if ($_FILES["favicon"]["name"]=="") {
			$favicon=$favicon_hidden;
		} else {
			$favicon=upload_file($favicon_file,$_FILES["favicon"]["name"],"../attachments");
		}

		$tbSiteList=encode($tbSiteList);
		$closeReason=encode($closeReason);

		$sql="update ".$DBPrefix."setting set name=\"$name\",blogTitle=\"$blogTitle\",blogUrl=\"$blogUrl\",logo=\"$logo\",favicon=\"$favicon\",about=\"$about\",master=\"$master\",email=\"$email\",disType=\"$disType\",perPageNormal=\"$perPageNormal\",perPageList=\"$perPageList\",isLinkTagLog=\"$isLinkTagLog\",linkTagLog=\"$linkTagLog\",isProgramRun=\"$isProgramRun\",commTimerout=\"$commTimerout\",commLength=\"$commLength\",isValidateCode=\"$isValidateCode\",isRegister=\"$isRegister\",status=\"$status\",closeReason=\"$closeReason\",language=\"$language\",timezone=\"$timezone\",timeSystemFormat=\"$timeSystemFormat\",isTbApp=\"$isTbApp\",tbSiteList=\"$tbSiteList\",newRss=\"$newRss\",rssContentType=\"$rssContentType\",tagNums=\"$tagNums\" where id='1'";
		$DMC->query($sql);

		//输出Cache
		settings_recache();
		hottags_recache();

		$ActionMessage=$strSaveSuccess;
	}

}

//查询设置选项
if ($action=="") {
	$settingInfo = $DMC->fetchArray($DMC->query("SELECT * FROM ".$DBPrefix."setting WHERE id='1'"));
	$name=$settingInfo['name'];
	$blogTitle=$settingInfo['blogTitle'];
	$blogUrl=$settingInfo['blogUrl'];
	$logo=$settingInfo['logo'];
	$favicon=$settingInfo['favicon'];
	$about=$settingInfo['about'];
	$master=$settingInfo['master'];
	$email=$settingInfo['email'];
	$disType=$settingInfo['disType'];
	$perPageNormal=$settingInfo['perPageNormal'];
	$perPageList=$settingInfo['perPageList'];
	$isLinkTagLog=$settingInfo['isLinkTagLog'];
	$linkTagLog=$settingInfo['linkTagLog'];
	$isProgramRun=$settingInfo['isProgramRun'];
	$commTimerout=$settingInfo['commTimerout'];
	$commLength=$settingInfo['commLength'];
	$isValidateCode=$settingInfo['isValidateCode'];
	$isRegister=$settingInfo['isRegister'];
	$status=$settingInfo['status'];
	$closeReason=dencode($settingInfo['closeReason']);
	$language=$settingInfo['language'];
	$timezone=$settingInfo['timezone'];
	$timeSystemFormat=$settingInfo['timeSystemFormat'];
	$isTbApp=$settingInfo['isTbApp'];
	$tbSiteList=dencode($settingInfo['tbSiteList']);
	$newRss=$settingInfo['newRss'];
	$rssContentType=$settingInfo['rssContentType'];
	$tagNums=$settingInfo['tagNums'];
}
?>

<form action="" method="post" enctype="multipart/form-data" name="seekform">
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
		  <?if ($ActionMessage!="") { ?>
		  <div class="contenttitle"><img src="images/content/icon_alert.gif" width="12" height="11">
            <?=$strErrorInfo?>: <font color="#FF0000"><?=$ActionMessage?></font>
          </div>
		  <br>
		  <?}?>
          <div class="contenttitle"><img src="images/content/info.gif" width="12" height="11">
            <?=$strBasicInfo?>
          </div>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strName?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="name" type="text" size="30" class="textbox" value="<?=$name?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strTitle?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="blogTitle" type="text" size="50" class="textbox" value="<?=$blogTitle?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-titleblue">
                <?=$strBlogUrl?>
                </span><br>
                <span class="input-titlesub">
                <?=$strBlogUrlMemo?>
                </span></td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="blogUrl" type="text" size="50" class="textbox" value="<?=$blogUrl?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-title">
                <?=$strLogo?>
                </span><br>
                <!--<span class="input-titlesub"><?=$strLogoMemo?></span>-->
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="logo" type="file" size="37" class="filebox" value=""/>
                <input name="logo_hidden" type="hidden" size="37" value="<?=$logo?>"/>
                <?if ($logo!="") { echo "&nbsp;&nbsp;<a href='../attachments/$logo' target='_blank'>$strShow</a>"; }?>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-title">
                <?=$strFavicon?>
                </span><br>
                <span class="input-titlesub">
                <?=$strFaviconMemo?>
                </span></td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="favicon" type="file" size="37" class="filebox" value=""/>
                <input name="favicon_hidden" type="hidden" size="37" value="<?=$favicon?>"/>
                <?if ($logo!="") { echo "&nbsp;&nbsp;<a href='../attachments/$favicon' target='_blank'>$strShow</a>"; }?>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strMaster?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="master" type="text" size="10" class="textbox" value="<?=$master?>" maxlength="10"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strEmail?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="email" type="text" size="50" class="textbox" value="<?=$email?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-title">
                <?=$strAboutGov?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="about" type="text" size="50" class="textbox" value="<?=$about?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-title">
                <?=$strCloseBlog?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="status" type="checkbox" value="1" <? if ($status=="1") { echo "checked"; } else { echo ""; } ?>/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="top" class="input-title">
                <?=$strCloseReason?>
              </td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td width="65%" align="left">
                <textarea name="closeReason" cols="60" rows="5" class="blogeditbox"><?=$closeReason?></textarea>
              </td>
            </tr>
          </table>
          <br>
          <div class="contenttitle"><img src="images/content/info.gif" width="12" height="11">
            <?=$strDisplaySetting?>
          </div>
          <br>
          <table  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strDisType?>
              </td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <select name="disType" class="blogeditbox">
                  <option value="0" <? if ($disType=="0") { echo "selected"; }?>>
                  <?=$strNormal?>
                  </option>
                  <option value="1" <? if ($disType=="1") { echo "selected"; }?>>
                  <?=$strList?>
                  </option>
                </select>
                </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strPerPageNormal?>
              </td>
			  <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <input name="perPageNormal" type="text" size="5" class="textbox" value="<?=$perPageNormal?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-titleblue">
                <?=$strPerPageList?>
                </span></td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <input name="perPageList" type="text" size="5" class="textbox" value="<?=$perPageList?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-title">
                <?=$strIsLinkTagLog?>
                </span></td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <input name="isLinkTagLog" type="checkbox" class="textbox" value="1" <? if ($isLinkTagLog=="1") { echo "checked"; }?>/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-title">
                <?=$strLinkTagLog?>
                </span></td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <input name="linkTagLog" type="text" size="5" class="textbox" value="<?=$linkTagLog?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-title">
                <?=$strTag?>
                </span></td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="655" align="left" valign="middle">
                <input name="tagNums" type="text" size="5" class="textbox" value="<?=$tagNums?>"/>
              </td>
            </tr>
          </table>
          <br>
          <div class="contenttitle"><img src="images/content/other.gif" width="12" height="11">
            <?=$strOtherSetting?>
          </div>
          <br>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="35%" align="right" valign="middle" class="input-title">
                <?=$strIsRegister?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="isRegister" type="checkbox" value="1" <? if ($isRegister=="1") { echo "checked"; }?>/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-title">
                <?=$strIsValidateCode?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="isValidateCode" type="checkbox" value="1" <? if ($isValidateCode=="1") { echo "checked"; }?>/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-title">
                <?=$strIsProgramRun?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="isProgramRun" type="checkbox" value="1" <? if ($isProgramRun=="1") { echo "checked"; }?>/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strCommTimerout?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="commTimerout" type="text" size="5" class="textbox" value="<?=$commTimerout?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle"> <span class="input-titleblue">
                <?=$strCommLength?>
                </span><br>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="commLength" type="text" size="5" class="textbox" value="<?=$commLength?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strLanguage?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <select name="language" class="blogeditbox">
                  <option value="zh_cn" <? if ($language=="zh_cn") { echo "selected"; }?>>
                  <?=$strSChinese?>
                  </option>
                  <option value="zh_tw" <? if ($language=="zh_tw") { echo "selected"; }?>>
                  <?=$strTChinese?>
                  </option>
                  <option value="en" <? if ($language=="en") { echo "selected"; }?>>
                  <?=$strEnglish?>
                  </option>
                </select>
               </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strTimezone?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <select name="timezone" class="blogeditbox">
                  <?
						$arrTimeSet=explode(",",$strTimeSet);
						$arrTimeNum=explode(",",$strTimeNum);
						for ($i=0;$i<count($arrTimeSet);$i++){
							$selected=($arrTimeNum[$i]==$timezone)?" selected":"";
							echo "<option value=\"$arrTimeNum[$i]\" $selected>$arrTimeSet[$i]</option>\r\n";
						}
					?>
                </select>
                </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strTimeSystemFormat?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="timeSystemFormat" type="text" size="30" class="textbox" value="<?=$timeSystemFormat?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strIsTbApp?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <select name="isTbApp" class="blogeditbox">
                  <option value="0" <? if ($isTbApp=="1") { echo "selected"; }?>>
                  <?=$strTbOpen?>
                  </option>
                  <option value="1" <? if ($isTbApp=="0") { echo "selected"; }?>>
                  <?=$strTbClose?>
                  </option>
                </select>
                </td>
            </tr>

			<tr>
			  <td width="35%" align="right" valign="middle">
			   <span class="input-titleblue">
                <?=$strTbSiteList?>
                </span><br>
                <span class="input-titlesub">
                <?=$strTbSiteListMemo?>
                </span>
			  </td>
			  <td width="10" align="left" valign="middle">&nbsp;</td>
			  <td width="65%" align="left" valign="middle"><textarea name="tbSiteList" cols="60" rows="8" class="blogeditbox"><?=$tbSiteList?></textarea></td>
			</tr>

			<tr>
              <td width="35%" align="right" valign="middle" class="input-titleblue">
                <?=$strNewRss?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <input name="newRss" type="text" class="textbox" size="5" value="<?=$newRss?>"/>
              </td>
            </tr>
            <tr>
              <td width="35%" align="right" valign="top" class="input-titleblue">
                <?=$strRssContentType?>
              </td>
              <td width="10" align="left" valign="middle">&nbsp;</td>
              <td width="65%" align="left" valign="middle">
                <select name="rssContentType" class="blogeditbox">
                  <option value="0" <? if ($rssContentType=="0") { echo "selected"; }?>>
                  <?=$strRssUnitPublic?>
                  </option>
                  <option value="1" <? if ($rssContentType=="1") { echo "selected"; }?>>
                  <?=$strRssAllPublic?>
                  </option>
                </select>
              </td>
            </tr>
          </table>
          </br>
          <div class="bottombar-onebtn">
            <input class="btn" type="submit" name="Submit" value=" <?=$strSaveSetting?> " onClick="javascript:seekform.action='<?=$_SERVER['PHP_SELF']?>?action=save'">
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

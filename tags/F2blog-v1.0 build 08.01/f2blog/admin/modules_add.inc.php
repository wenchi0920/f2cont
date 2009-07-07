<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "modules_add.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
?>
<script style="javascript">
<!--
function onclick_update(form) {
	if (isNull(form.name, '<?=$strErrNull?>')) return false;
	if (isNull(form.modTitle, '<?=$strErrNull?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$edit_url&mark_id=$mark_id&action=save"?>";
	form.submit();
}
-->
</script>

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
            <div class="contenttitle"><img src="images/content/ui_manage.gif" width="12" height="11">
              <?=$title?>
            </div>
            <br>
            <div class="subcontent">
              <? if ($ActionMessage!="") { ?>
              <br>
              <fieldset>
              <legend>
              <?=$strErrorInfo?>
              </legend>
              <div align="center">
                <table border="0" cellpadding="2" cellspacing="1">
                  <tr>
                    <td><span class="alertinfo">
                      <?=$ActionMessage?>
                      </span></td>
                  </tr>
                </table>
              </div>
              </fieldset>
              <br>
              <? } ?>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-input">
                  <td width="145" align="right" class="input-titleblue">
                    <?=$strModType?>
                  </td>
                  <td>
                    <select name="disType" onchange="show_tools(this.value)">
                      <option value="1" <? if ($disType=="1") { echo "selected"; }?>>
                      <?=$strModTypeArr[1]?>
                      </option>
                      <option value="2" <? if ($disType=="2") { echo "selected"; }?>>
                      <?=$strModTypeArr[2]?>
                      </option>
                      <option value="3" <? if ($disType=="3") { echo "selected"; }?>>
                      <?=$strModTypeArr[3]?>
                      </option>
                    </select>
                    <input type="hidden" name="oldDisType" value="<?=$oldDisType?>">
                  </td>
                </tr>
                <tr>
                  <td width="145" align="right" class="input-titleblue">
                    <?=$strModName?>
                  </td>
                  <td>
                    <input name="name" class="<?=($mark_id!="")?"readonly":"textbox"?>" type="TEXT" size=50 maxlength=20 value="<?=$name?>" <?=($mark_id!="")?"readonly":""?>>
                  </td>
                </tr>
                <tr>
                  <td width="145" align="right" class="input-titleblue">
                    <?=$strModTitle?>
                  </td>
                  <td>
                    <input name="modTitle" class="textbox" type="TEXT" size=50 maxlength=50 value="<?=$modTitle?>">
                  </td>
                </tr>
                <tr>
                  <td width="145" align="right">
                    <?=$strModuleShowHidden?>
                  </td>
                  <td align="left">
                    <INPUT TYPE="radio" NAME="isHidden" value="" <? if ($isHidden==0) { echo "checked"; }?>>
					<?=$strShow?>
                    <INPUT TYPE="radio" NAME="isHidden" value="1" <? if ($isHidden>0) { echo "checked"; }?>>
					<?=$strHidden?>                    
                  </td>
                </tr>
				<!--模块为内容时显示-->
                <tr id="mod_content" style="display:<?=($disType=="3")?"":"none"?>">
                  <td width="145" align="right">
                    <?=$strModIndexOnly?>
                  </td>
                  <td align="left">
					<SELECT NAME="indexOnly">
						<?
						for ($i=0;$i<count($strModuleContentShow);$i++){
							$selected=($indexOnly==$i)?" selected":"";
							echo "<option value=\"$i\"$selected>$strModuleContentShow[$i]</option> \n";
						}
						?>
					</SELECT>
                  </td>
                </tr>
				<!--模块为侧边栏时显示-->
                <tr id="mod_sidebar1" style="display:<?=($disType=="2")?"":"none"?>">
                  <td width="145" align="right">
                    <?=$strModuleSideShowHidden?>
                  </td>
                  <td align="left">
                    <INPUT TYPE="radio" NAME="isInstall" value="" <? if ($isInstall==0) { echo "checked"; }?>>
					<?=$strShow?>
                    <INPUT TYPE="radio" NAME="isInstall" value="1" <? if ($isInstall>0) { echo "checked"; }?>>
					<?=$strHidden?>
                  </td>
                </tr>
				<!--模块为侧边栏或内容栏时显示-->
                <tr id="mod_sidebar2" style="display:<?=($disType!="1")?"":"none"?>">
                  <td width="145" align="right" valign="top">
                    <div align="right">
                      <?=$strHtmlCode?>
                    </div>
                  </td>
                  <td align="left">
                    <textarea name="htmlCode" cols="65" rows="13"><?=$htmlCode?></textarea>
                  </td>
                </tr>
				<!--模块为顶部时显示-->
                <tr id="mod_top" style="display:<?=($disType=="1")?"":"none"?>">
                  <td width="145" align="right">
                    <div align="right">
                      <?=$strLinkAdd?>
                    </div>
                  </td>
                  <td>
                    <input name="pluginPath" class="textbox" type="TEXT" size=50 maxlength=60 value="<?=$pluginPath?>">
                  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strSave?>" onClick="Javascript:onclick_update(this.form)">
              &nbsp;
              <input name="reback" class="btn" type="button" id="return" value="<?=$strReturn?>" onclick="location.href='<?="$edit_url"?>'">
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
  </div>
</form>
<? dofoot(); ?>

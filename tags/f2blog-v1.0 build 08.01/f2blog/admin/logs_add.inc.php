<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "logs_add.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
?>
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="../editor/tiny_mce_gzip.php"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		elements : "logContent",
		theme : "advanced",
		language : "<?=$settingInfo['language']?>",
		plugins : "<?=$editor_plugins?>",
		theme_advanced_buttons1 : "<?=$editor_button1?>",
		theme_advanced_buttons2 : "<?=$editor_button2?>",
		theme_advanced_buttons3 : "<?=$editor_button3?>",

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,

		handle_event_callback : "editor_events",
		file_browser_callback : "fileBrowserCallBack"
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}

	function editor_events(e) {
		if (e.type == 'keydown' && e.keyCode == 9 && document.all) {
			e.returnValue = false;
			e.cancelBubble = true;
			tinyMCE.execCommand("mceInsertContent", false, "&nbsp;&nbsp;&nbsp;");
			return false;
		}
		return true; // Continue handling
	}

	function onclick_update(form,act) {	
		if (isNull(form.logTitle, '<?=$strErrNull?>')) return false;
		if (isNull(form.cateId, '<?=$strErrNull?>')) return false;

		if (act=="preview"){
			form.target = "_blank";
			form.action = "preview.php";
			form.submit();
		}else{
			form.preview.disabled = true;
			form.save.disabled = true;
			form.reback.disabled = true;
			form.target = window.name;
			form.action = "<?="$edit_url&mark_id=$mark_id&action=\""?>+act;
			form.submit();
		}
	}
	</script>
<!-- /TinyMCE -->
</head>
<body>
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
            <div class="contenttitle"><img src="images/content/write_log.gif" width="12" height="11">
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
                  <td width="5%" nowrap class="input-titleblue">
                    <?=$strSubject?>
                  </td>
                  <td width="65%">
                    <input name="logTitle" class="textbox" type="text" size="80" value="<?=$logTitle?>">
                  </td>
                  <td width="10%" nowrap class="input-titleblue" align="right">
                    <?=$strCategory?>
                  </td>
                  <td width="20%">
                    <? category_select("cateId",$cateId,""); ?>
                    <input name="oldCateId" type="hidden" size="50" value="<?=$oldCateId?>">
                    <input name="edittype" type="hidden" size="50" value="<?=$edittype?>">
                  </td>
                </tr>
                <tr>
                  <td nowrap>
                    <?=$strTag?>
                  </td>
                  <td>
                    <input name="tags" class="textbox" type="text" size="50" value="<?=$tags?>">
                    &nbsp;<span style="color:#999">
                    <?=$strPlsSelTags?>
                    </span> </td>
                  <td nowrap align="right">
                    <?=$strWeather?>
                  </td>
                  <td>
                    <? weather_select("weather",$weather,""); ?>
                    <input name="oldTags" type="hidden" size="50" value="<?=$oldTags?>">
                  </td>
                </tr>
                <tr>
                  <td nowrap>&nbsp;</td>
                  <td colspan="3">
                    <?="<b>$strSelTags</b> ".tag_list("S")?>
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <textarea id="logContent" name="logContent" rows="20" cols="80" style="width: 100%"><?=$logContent?></textarea>
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <iframe src="attach.php?mark_id=<?=$mark_id?>" frameborder="no" scrolling="no" width="100%" height="200"></iframe>
                  </td>
                </tr>
                <? if ($mark_id=="") { ?>
                <tr>
                  <td nowrap>
                    <?=$strQuoteUrl?>
                  </td>
                  <td colspan="3">
                    <input name="quoteUrl" class="textbox" type="text" size="70" value="<?=$quoteUrl?>">
                    &nbsp;<span style="color:#999">
                    <?=$strQuoteAlt?>
                    </span> </td>
                </tr>
                <? } ?>
                <tr>
                  <td nowrap>
                    <?=$strPostTime?>
                  </td>
                  <td colspan="3">
                    <input name="pubTimeType" type="radio" value="now" <?=($pubTimeType=="now" or $pubTimeType=="")?"checked":""?>/>
                    <?=$strCurTime?>
                    <input name="pubTimeType" type="radio" value="com" <?=($pubTimeType=="com")?"checked":""?> />
                    <?=$strDefTime?>
                    :
                    <input name="pubTime" type="text" value="<?=format_time("Y-m-d H:i",time())?>" size="21" class="textbox" onclick="pubTimeType(1).checked=true"/>
                    &nbsp;<span style="color:#999">
                    <?=$strTimeFormat?>
                    </span> </td>
                </tr>
                <tr>
                  <td nowrap>
                    <?=$strPareams?>
                  </td>
                  <td colspan="3">
                    <?=savetype_radio("saveType",$saveType)?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="isTop" type="checkbox" value="1" <? if ($isTop=="1") { echo "checked"; }?>/>
                    <?=$strTopView?>
                  </td>
                </tr>
                <tr>
                  <td nowrap>
                    <?=$strGuestAccess?>
                  </td>
                  <td colspan="3">
                    <input name="isComment" type="checkbox" value="1" <? if ($isComment=="1" or $isComment=="") { echo "checked"; }?>/>
                    <?=$strAllowComment?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="isTrackback" type="checkbox" value="1" <? if ($isTrackback=="1" or $isTrackback=="") { echo "checked"; }?>/>
                    <?=$strAllowTB?>
					&nbsp;&nbsp;&nbsp;&nbsp;
                    <?=$strLogPassword?>
		            <INPUT TYPE="text" NAME="addpassword" size="35" value="<?=$addpassword?>" class="textbox">
				  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
			  <input name="preview" class="btn" type="button" id="preview" value="<?=$strPreview?>" onClick="Javascript:onclick_update(this.form,'preview')">
              &nbsp;
              <input name="save" class="btn" type="button" id="save" value="<?=$strSave?>" onClick="Javascript:onclick_update(this.form,'save')">
              &nbsp;
              <input name="reback" class="btn" type="button" id="return" value="<?=$strReturn?>" onClick="location.href='<?="$edit_url"?>'">
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

<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "logs_list.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,$page_url);
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
            <div class="contenttitle"><img src="images/content/log_manage.gif" width="12" height="11">
              <?=$title?>
              <div class="page">
                <?view_page($page_url)?>
              </div>
            </div>

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
              <tr>
                <td class="searchtool">
                  <?=$strBlueFind?>
                  &nbsp;
                  <input type="text" name="seekname" size="8" value="<?=$seekname?>" class="searchbox">
                  &nbsp;
                  <?=$strCategory?>
                  &nbsp;
                  <? category_select("seekcate",$seekcate,"class=\"searchbox\""); ?>
                  <?=$strTag?>
                  &nbsp;
                  <? tags_select("seektags",$seektags,"class=\"searchbox\""); ?>
                  &nbsp;
                  <input name="find" class="btn" type="submit" value="<?=$strFind?>" onclick="confirm_submit('<?=$seek_url?>','find')">
                  &nbsp;
                  <input name="findall" class="btn" type="button" value="<?=$strAll?>" onclick="confirm_submit('<?=$seek_url?>','all')">
                  &nbsp;
                  <input name="add" class="btn" type="button" value="<?=$strAdd?>" onclick="confirm_submit('<?=$edit_url?>','add')">
                </td>
              </tr>
            </table>
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="11%" nowrap class="whitefont">
                    <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                  </td>
                  <td width="18%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.postTime\">$strPostTime</a>";
		if ($order=="a.postTime"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="10%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.cateId\">$strCategory</a>";
		if ($order=="a.cateId"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="30%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.logTitle\">$strLogTitle</a>";
		if ($order=="a.logTitle"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="10%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.tags\">$strTag</a>";
		if ($order=="a.tags"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="5%" align="center" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.saveType\">$strStatus</a>";
		if ($order=="a.saveType"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="5%" align="center" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isTop\">$strTopTitle</a>";
		if ($order=="a.isTop"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="5%" align="center" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isComment\">$strLogComm</a>";
		if ($order=="a.isComment"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="5%" align="center" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isTrackback\">$strLogTB</a>";
		if ($order=="a.isTrackback"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="5%" align="center" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.password\">$strLogPasswordTitle</a>";
		if ($order=="a.password"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                </tr>
                <?
	//Record Limits
	if ($page<1) { $page=1; }
	$start_record=($page-1)*$cfg_page_size;
	
	$query_sql=$sql." Limit $start_record,$cfg_page_size";
	$query_result=$DMC->query($query_sql);
	while($fa = $DMC->fetchArray($query_result)){
		$index++;
		$imgHidden=($fa['saveType']==0)?"<img src='images/content/lock.gif' title='$strHiddenLog'>":"&nbsp;";
		$imgIndexOnly=($fa['isTop']==1)?"<img src='images/content/add_top.gif' title='$strTopView'>":"&nbsp;";
		$imgComments=($fa['isComment']==0)?"<img src='images/content/tool_post.gif' title='$strLogCommentsHelp'>":"&nbsp;";
		$imgTrackback=($fa['isTrackback']==0)?"<img src='images/content/tool_trackback.gif' title='$strLogTrackbackHelp'>":"&nbsp;";
		$imgPassword=($fa['password']!="")?"<img src='images/content/tool_password.gif' title='$strLogPasswordHelp'>":"&nbsp;";
	?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td nowrap class="subcontent-td">
                    <INPUT type=checkbox value="<?=$fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                    <a href="<?="$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="images/content/icon_modif.gif" alt="<?="$strEdit"?>" border="0"></a>&nbsp; <a href="<?="$edit_url&mark_id=".$fa['id']."&action=trackback"?>"><img src="images/content/icon_track.gif" alt="<?="$strSendTb"?>" border="0"></a> </td>
                  <td nowrap class="subcontent-td">
                    <?=format_time("L",$fa['postTime'])?>
                  </td>
                  <td nowrap class="subcontent-td">
                    <?=$fa['name']?>
                  </td>
                  <td nowrap class="subcontent-td">
                    <?="<a href=\"../index.php?load=read&id=".$fa['id']."\" target=\"_blank\" title=\"".$fa['logTitle']."\">".subString($fa['logTitle'],0,18)."</a>"?>
                  </td>
                  <td nowrap class="subcontent-td">
                    <?=($fa['tags'])?$fa['tags']:"&nbsp;"?>
                  </td>
                  <td nowrap align="center" class="subcontent-td">
                    <?=$imgHidden?>
                  </td>
                  <td nowrap align="center" class="subcontent-td">
                    <?=$imgIndexOnly?>
                  </td>
                  <td nowrap align="center" class="subcontent-td">
                    <?=$imgComments?>
                  </td>
                  <td nowrap align="center" class="subcontent-td">
                    <?=$imgTrackback?>
                  </td>
                  <td nowrap align="center" class="subcontent-td">
                    <?=$imgPassword?>
                  </td>
                </tr>
                <?}?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strDelete?>
              |
              <input type="radio" name="operation" value="publish" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strPubLog?>
              <input type="radio" name="operation" value="nopublish" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strHidLog?>
              |
              <input type="radio" name="operation" value="top" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strTopTitle?>
              <input type="radio" name="operation" value="notop" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strCTopTitle?>
              |
              <input type="radio" name="operation" value="ctshow" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strCommentShow?>
              <input type="radio" name="operation" value="cthidden" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strCommentHidden?>
              |
              <input type="radio" name="operation" value="tbshow" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strTrackBackShow?>
              <input type="radio" name="operation" value="tbhidden" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strTrackBackHidden?>
              | <br>
              <input type="radio" name="operation" value="logmove" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strLogCategoryMove?>
              <?category_select("move_category","","class=\"searchbox\" onchange=\"seekform.operation(9).checked=true;\""); ?>
              |
              <input type="radio" name="operation" value="addpassword" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strLogPassword?>
              <INPUT TYPE="text" NAME="addpassword" size="10" onKeyup="seekform.operation(10).checked=true;">
              |
			  <input type="radio" name="operation" value="settags" onclick="Javascript:this.form.opmethod.value=1">
              <? tags_select("settags","class=\"searchbox\"","onchange=\"seekform.operation(11).checked=true;\""); ?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="1">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')">
              <span class="style1">(
              <?=$strDeleteInfo?>
              )</span> </div>
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

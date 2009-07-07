<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "trackbacks_list.inc.php") {
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
          <div class="contenttitle"><img src="images/content/track_manage.gif" width="12" height="11">
            <?=$title?>
            <div class="page">
              <?view_page($page_url)?>
            </div>
          </div>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="searchtool">
                <?=$strBlueFind?>
                &nbsp;
                <input type="text" name="seekname" size="8" value="<?=$seekname?>" class="pagenav">
                &nbsp;
                <input name="find" class="btn" type="submit" value="<?=$strFind?>" onclick="confirm_submit('<?=$seek_url?>','find')">
                &nbsp;
                <input name="findall" class="btn" type="button" value="<?=$strAll?>" onclick="confirm_submit('<?=$seek_url?>','all')">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" name="treeopen" class="btn" value="<?echo $strTree_Open;?>" onClick="javascript:seekform.action='<?="$showmode_url&showmode=open"?>'">
                &nbsp;
                &nbsp;
                <input type="submit" name="treeopen" class="btn" value="<?echo $strTree_Close;?>" onClick="javascript:seekform.action='<?="$showmode_url&showmode=close"?>'">
              </td>
            </tr>
          </table>
          <div class="subcontent">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr class="subcontent-title">
                <td width="5%" nowrap align="center" class="whitefont"> &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                </td>
                <td width="5%" nowrap align="center" class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=isApp\">$strStatus</a>";
		if ($order=="isApp"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
                <td width="15%" nowrap align="center" class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=postTime\">$strPostTime</a>";
		if ($order=="postTime"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
                <td width="40%" nowrap class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=tbTitle\">$strSubject</a>";
		if ($order=="tbTitle"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
                <td width="15%" nowrap class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=blogSite\">$strName</a>";
		if ($order=="blogSite"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
                <td width="13%" nowrap align="center" class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=ip\">$strFiltersCategory3</a>";
		if ($order=="ip"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
              </tr>
              <?
	//Record Limits
	if ($page<1){$page=1;}
	$start_record=($page-1)*$cfg_page_size;
	
	$query_sql=$sql." Limit $start_record,$cfg_page_size";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);

	if ($_GET['showmode']=="open"){
		$image_path="images/content/expand_no.gif";
		$visible="";
	}else{
		$image_path="images/content/expand_yes.gif";
		$visible="none";
	}
	
	for ($i=0;$i<count($arr_parent);$i++){
		$imgHidden=($arr_parent[$i]['isApp']==0)?"&nbsp;&nbsp;<img src='images/content/lock.gif' title='$strTbAlrHidden'>":"&nbsp;";
	?>
              <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                <td nowrap align="center" class="subcontent-td"> <img align="absMiddle" name="<?echo "open_img$i"?>" src="<?echo $image_path?>" onMouseUp="open_content('<?echo "open$i"?>',<?echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;">
                  <INPUT type=checkbox value="<?=$arr_parent[$i]['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                </td>
                <td align="center" class="subcontent-td">
                  <?=$imgHidden?>
                </td>
                <td class="subcontent-td">
                  <?=format_time("L",$arr_parent[$i]['postTime'])?>
                </td>
                <td nowrap class="subcontent-td"><a href='<?=$arr_parent[$i]['blogUrl']?>' target="_blank">
                  <?=$arr_parent[$i]['tbTitle']?>
                  </a></td>
                <td nowrap class="subcontent-td"><a href='<?=$arr_parent[$i]['blogUrl']?>' target="_blank">
                  <?=$arr_parent[$i]['blogSite']?>
                  </a></td>
                <td nowrap class="subcontent-td">
                  <?=$arr_parent[$i]['ip']?>
                </td>
              </tr>
              <tr id="<?echo "open$i"?>" style="DISPLAY:<?echo $visible?>">
                <td colspan="7">
                  <table width="95%" align="right" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="subcontent-td">
                        <?=dencode($arr_parent[$i]['content'])?>
                      </td>
                    </tr>
                  </table>
              </tr>
              </td>
              
              <?
     }//end while	  
	?>
            </table>
          </div>
          <br>
          <div class="bottombar-onebtn"></div>
          <div class="searchtool">
            <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strDelete?>
            |
            <input type="radio" name="operation" value="hidden" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strHidden?>
            <input type="radio" name="operation" value="show" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strShow?>
            |
            <input name="opselect" type="hidden" value="">
            <input name="opmethod" type="hidden" value="">
            <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')">
            &nbsp;<span class="style1">(
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

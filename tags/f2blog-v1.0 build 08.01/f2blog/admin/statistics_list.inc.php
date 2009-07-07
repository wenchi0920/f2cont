<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "statistics_list.inc.php") {
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
            <div class="contenttitle"><img src="images/content/statistic.gif" width="12" height="11">
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
                  <input type="text" name="seekname" size="8" value="<?=$seekname?>" class="searchbox">
                  &nbsp;
                  <input name="find" class="btn" type="submit" value="<?=$strFind?>" onclick="confirm_submit('<?=$seek_url?>','find')">
                  &nbsp;
                  <input name="findall" class="btn" type="button" value="<?=$strAll?>" onclick="confirm_submit('<?=$seek_url?>','all')">
                  &nbsp;&nbsp;						
                  &nbsp;
                  <input name="findday" class="btn" type="submit" value="<?=$strStatisticsVisitsDay?>" onclick="confirm_submit('<?=$seek_url?>&visits=','find')" <?=($visits=="")?"disabled":""?>>
                  &nbsp;
                  <input name="findmonth" class="btn" type="submit" value="<?=$strStatisticsVisitsMonth?>" onclick="confirm_submit('<?=$seek_url?>&visits=month','find')" <?=($visits=="month")?"disabled":""?>>
                  &nbsp;
                  <input name="findyear" class="btn" type="submit" value="<?=$strStatisticsVisitsYear?>" onclick="confirm_submit('<?=$seek_url?>&visits=year','find')" <?=($visits=="year")?"disabled":""?>>
                </td>
              </tr>
            </table>
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="4%" nowrap align="center">
                    <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                  </td>
                  <td width="36%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=visitDate\">$strStatisticsVisitsDate</a>";
		if ($order=="visitDate"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="36%" nowrap class="whitefont">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=visits\">$strStatisticsVisitsNum</a>";
		if ($order=="visits"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                </tr>
                <?
	//Record Limits
	if ($page<1){$page=1;}
	$start_record=($page-1)*$cfg_page_size;
	
	$query_sql=$sql." Limit $start_record,$cfg_page_size";
	$query_result=$DMC->query($query_sql);
	while($fa = $DMC->fetchArray($query_result)){
		$index++;
		if ($fa['isHidden']==1){
			$hidden="&nbsp;<img src=\"images/content/lock.gif\" valign=\"absMiddle\" alt=\"$strLinksHiddened\" /> \n";
			//$hidden="&nbsp; [<font color=red>$strCategoryHiddened</font>] \n";
		}else{
			$hidden="";
		}
	?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td width="4%" nowrap align="center" class="subcontent-td">
                    <INPUT type=checkbox value="<?=$fa['visitDate']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                  </td>
                  <td width="36%" nowrap class="subcontent-td">
                    <?=$fa['visitDate']?>
                  </td>
                  <td width="36%" nowrap class="subcontent-td"><a href="<?=$fa['visits']?>" target="_blank">
                    <?=$fa['visits']?>
                    </a></td>
                </tr>
                <?}//end while?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="delete" checked onclick="Javascript:this.form.opmethod.value=1">
              <?=$strDelete?>
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

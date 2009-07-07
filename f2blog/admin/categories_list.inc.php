<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "categories_list.inc.php") {
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
            <div class="contenttitle"><img src="images/content/category.gif" width="12" height="11">
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
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="submit" name="treeopen" class="btn" value="<?echo $strTree_Open;?>" onClick="javascript:seekform.action='<?="$showmode_url&showmode=open"?>'">
                  &nbsp;
                  &nbsp;
                  <input type="submit" name="treeopen" class="btn" value="<?echo $strTree_Close;?>" onClick="javascript:seekform.action='<?="$showmode_url&showmode=close"?>'">
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;
                  <input name="add" class="btn" type="button" value="<?=$strAdd?>" onclick="confirm_submit('<?=$edit_url?>','add')">
                </td>
              </tr>
            </table>
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="1%" class="whitefont" nowrap align="center">&nbsp;</td>
                  <td width="1%" class="whitefont" nowrap align="right">&nbsp;</td>
                  <td width="10%" class="whitefont" nowrap align="right">操作&nbsp;&nbsp;<a href="<?="$edit_url&mark_id=0&action=order"?>"><img src="images/content/icon_track.gif" width="16" height="16" alt="<?="$strCategoryExchage"?>" border="0"></a>&nbsp;</td>
                  <td width="8%" class="whitefont" nowrap align="center">
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=orderNo\">$strCategoryOrderNo</a>";
		if ($order=="orderNo"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="20%" class="whitefont" nowrap>
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strCategoryName</a>";
		if ($order=="name"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="30%" class="whitefont" nowrap>
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=cateTitle\">$strCategoryDescription</a>";
		if ($order=="cateTitle"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="10%" class="whitefont" nowrap>
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=cateCount\">$strCategoryCount</a>";
		if ($order=="cateCount"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                  </td>
                  <td width="40%" class="whitefont" nowrap>
                    <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=outLinkUrl\">$strCategoryUrl</a>";
		if ($order=="outLinkUrl"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
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
		if ($arr_parent[$i]['isHidden']==1){
			$hidden="<img src=\"images/content/lock.gif\" alt=\"$strCategoryHiddened\"> \n";
			//$hidden="&nbsp; [<font color=red>$strCategoryHiddened</font>] \n";
		}else{
			$hidden="";
		}

		//取得子菜单
		$sub_sql="select * from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' order by $order";
		$query_result=$DMC->query($sub_sql);
		$parent_num=$DMC->numRows($query_result);
	?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td width="1%" align="center" nowrap class="subcontent-td">
                    <?=$hidden?>
                    &nbsp;</td>
                  <td width="1%" align="right" nowrap class="subcontent-td">
                    <?if ($parent_num>0){?>
                    <img align="absMiddle" name="<?echo "open_img$i"?>" src="<?echo $image_path?>" onMouseUp="open_content('<?echo "open$i"?>',<?echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;">
                    <?}else{echo "&nbsp;";}?>
                  </td>
                  <td width="10%" nowrap class="subcontent-td">
                    <INPUT type=checkbox value="<?=$arr_parent[$i]['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                    <a href="<?="$edit_url&mark_id=".$arr_parent[$i]['id']."&action=edit"?>"><img src="images/content/icon_modif.gif" width="16" height="16" alt="<?="$strEdit"?>" border="0"> </a> &nbsp; <a href="<?="$edit_url&mark_id=".$arr_parent[$i]['id']."&action=order"?>"><img src="images/content/icon_track.gif" width="16" height="16" alt="<?="$strCategoryExchage"?>" border="0"> </a> </td>
                  <td width="8%" nowrap align="center" class="subcontent-td">
                    <?=$arr_parent[$i]['orderNo']?>
                  </td>
                  <td width="20%" nowrap class="subcontent-td">
                    <?=$arr_parent[$i]['name']?>
                  </td>
                  <td width="30%" nowrap class="subcontent-td">
                    <?=($arr_parent[$i]['cateTitle']=="")?"&nbsp;":$arr_parent[$i]['cateTitle']?>
                  </td>
                  <td width="10%" nowrap class="subcontent-td">
                    <?=$arr_parent[$i]['cateCount']?>
                  </td>
                  <td width="40%" nowrap class="subcontent-td">
                    <?=($arr_parent[$i]['outLinkUrl']=="")?"&nbsp;":$arr_parent[$i]['outLinkUrl']?>
                  </td>
                </tr>
                <?if ($parent_num>0){?>
                <tr id="<?echo "open$i"?>" style="DISPLAY:<?echo $visible?>">
                  <td colspan="8">
                    <table width="93%" align="right" border="0" cellspacing="0" cellpadding="0">
                      <tr class="subcontent-title">
                        <td width="8%" align="center" class="whitefont">&nbsp;</td>
                        <td width="8%" align="center" class="whitefont">
                          <?=$strCategoryOrderNo?>
                        </td>
                        <td width="20%" class="whitefont">
                          <?=$strCategoryName?>
                        </td>
                        <td width="30%"  class="whitefont">
                          <?=$strCategoryDescription?>
                        </td>
                        <td width="10%" align="center" class="whitefont">
                          <?=$strCategoryCount?>
                        </td>
                        <td width="30%" class="whitefont">
                          <?=$strCategoryUrl?>
                        </td>
                      </tr>
                      <?while($fa = $DMC->fetchArray($query_result)){?>
                      <tr onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                        <td width="8%" nowrap class="subcontent-td">
                          <INPUT type=checkbox value="<?=$fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                          <a href="<?="$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="images/content/icon_modif.gif" width="16" height="16" alt="<?="$strEdit"?>" border="0"> </a> </td>
                        <td width="8%" nowrap align="center" class="subcontent-td">
                          <?=$fa['orderNo']?>
                        </td>
                        <td width="20%" nowrap class="subcontent-td">
                          <?=$fa['name']?>
                        </td>
                        <td width="30%" nowrap class="subcontent-td">
                          <?=($fa['cateTitle']=="")?"&nbsp;":$fa['cateTitle']?>
                        </td>
                        <td width="10%" align="center" nowrap class="subcontent-td">
                          <?=$fa['cateCount']?>
                        </td>
                        <td width="30%" nowrap class="subcontent-td">
                          <?=($fa['outLinkUrl']=="")?"&nbsp;":$fa['outLinkUrl']?>
                        </td>
                      </tr>
                      <?}?>
                    </table>
                  </td>
                </tr>
                <?}//end parent?>
                <?
     }//end while	  
	?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="ishidden" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strHidden?>
              <input type="radio" name="operation" value="isshow" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strShow?>
              |
              <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strDelete?>
              |
              <input type="radio" name="operation" value="move" onclick="Javascript:this.form.opmethod.value=1">
              <?=$strCategoryMove?>
              <?get_category_parent("parent","","style=\"font-size:12px;\" onchange=\"seekform.operation(3).checked=true;\"")?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')">
              &nbsp;<span class="style1">(
              <?=$strDeleteInfo?>
              )</span></div>
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

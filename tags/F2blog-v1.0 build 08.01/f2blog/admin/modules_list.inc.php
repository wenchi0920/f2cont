<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "modules_list.inc.php") {
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
          <div class="contenttitle"><img src="images/content/ui_manage.gif" width="12" height="11">
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
                &nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;
                <input name="add" class="btn" type="button" value="<?=$strAdd?>" onclick="confirm_submit('<?=$edit_url?>','add')">
              </td>
            </tr>
          </table>
          <div class="subcontent">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr class="subcontent-title">
                <td width="5%" nowrap align="center">&nbsp;</td>
                <td width="6%" nowrap align="center">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=orderNo\">$strCategoryOrderNo</a>";
		if ($order=="orderNo"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
		?>
                </td>
                <td nowrap class="whitefont">
                  <?
		echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strModType</a>";
		if ($order=="name"){echo "<img src=\"images/content/down.gif\" border=\"0\">";}
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

	if ($_GET['showmode']=="open" or $_GET['showmode']==""){
		$image_path="images/content/expand_no.gif";
		$visible="";
	}else{
		$image_path="images/content/expand_yes.gif";
		$visible="none";
	}
	
	$arrImg=array("","module-top.gif","module-side.gif","module-content.gif");
	for ($i=0;$i<count($arr_parent);$i++){
		if ($arr_parent[$i]['isHidden']==1){
			$hidden="&nbsp;<img src=\"images/content/lock.gif\" valign=\"absMiddle\" alt=\"$strCategoryHiddened\" /> \n";
		}else{
			$hidden="";
		}
		$parent=$arr_parent[$i]['id'];
	?>
              <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                <td nowrap align="center" class="subcontent-td"> <img align="absMiddle" name="<?echo "open_img$i"?>" src="<?echo $image_path?>" onMouseUp="open_content('<?echo "open$i"?>',<?echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;"> <a href="<?="$edit_url&mark_id=".$arr_parent[$i]['id']."&action=order"?>"><img src="images/content/icon_track.gif" width="16" height="16" alt="<?="$strCategoryExchage"?>" border="0"> </a> </td>
                <td align="center" class="subcontent-td">
                  <?=$arr_parent[$i]['orderNo']?>
                  <?=$hidden?>
                </td>
                <td nowrap class="subcontent-td">
                  <?="<img src='images/content/".$arrImg[$parent]."'>&nbsp;".$strModTypeArr[$parent]?>
                </td>
              </tr>
              <tr id="<?echo "open$i"?>" style="DISPLAY:<?echo $visible?>">
                <td colspan="3">
                  <table width="95%" align="right" border="0" cellspacing="0" cellpadding="0">
                    <tr class="subcontent-title">
                      <td width="4%" align="center" class="whitefont">&nbsp;</td>
                      <td width="9%" align="center" class="whitefont">
                        <?=$strEdit."/".$strDelete?>
                      </td>
                      <td width="5%" align="center" class="whitefont">
                        <?=$strCategoryOrderNo?>
                      </td>
                      <td width="15%" class="whitefont">
                        <?=$strModName?>
                      </td>
                      <td width="15%"  class="whitefont">
                        <?=$strModTitle?>
                      </td>
                      <td width="30%"  class="whitefont">
                        <?=($parent!=1)?$strHtmlCode:$strLinkAdd?>
                      </td>
                      <td width="5%" align="center" class="whitefont">
                        <?=$strHidden?>
                      </td>
                      <td width="6%" align="center" class="whitefont" nowrap>
                        <?
						if ($parent==1){
							echo "";
						}else if ($parent==2){
							echo $strModuleSideStyle;
						}else{
							echo $strModIndexOnly;
						}
						?>
                      </td>
                      <td width="6%" align="center" class="whitefont">
                        <?=$strPlugin?>
                      </td>
                    </tr>
                    <?
		//取得子菜单
		$sub_sql="select * from ".$DBPrefix."modules where disType='".$arr_parent[$i]['id']."' order by $order";
		$query_result=$DMC->query($sub_sql);

		$index=0;
		while($fa = $DMC->fetchArray($query_result)){
			$index++;
			$parent=$fa['disType'];
			$imgHidden=($fa['isHidden']==1)?"&nbsp;&nbsp;<img src='images/content/lock.gif' title='$strModAlrHidden'>":"&nbsp;";
			$imgSideHidden=($fa['isInstall']==1)?"&nbsp;&nbsp;<img src='images/content/lock.gif' title='$strModuleSideStyleHidden'>":"&nbsp;";
			//$imgIndexOnly=($fa['indexOnly']==1)?"&nbsp;&nbsp;<img src='images/content/add_top.gif' title='$strModIndexOnly'>":"&nbsp;";
			$imgPlugin=($fa['installDate']!=0)?"&nbsp;&nbsp;<img src='images/content/plugin.gif' title='$strPlugin'>":"&nbsp;";
			$modTitle=replace_string($fa['modTitle']);
			if ($fa['isSystem']!=1) {
				$isSystem="<a href='$edit_url&mark_id=".$fa['id']."&action=edit'><img src='images/content/icon_modif.gif' title='$strEdit' border=\"0\"></a>";
				$isSystem.="&nbsp;<a href='#' onclick=\"ConfirmForm('$edit_url&mark_id=".$fa['id']."&action=delete','$strAlertDeleteOption')\"><img src='images/content/del.gif' title='$strDelete' border=\"0\"></a>";
				$isClass="";
			} else {
				$isSystem="&nbsp;";
				$isClass="table_color3";
			}

			if ($parent==3){
				$imgIndexOnly=$strModuleContentShow[$fa['indexOnly']];
			}

	?>
                    <tr class="<?=$isClass?>" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                      <td align="center" nowrap class="subcontent-td">
                        <INPUT type=checkbox value="<?=$fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                        </a> </td>
                      <td align="center" nowrap class="subcontent-td">
                        <?=$isSystem?>
                      </td>
                      <td align="center" nowrap class="subcontent-td">
                        <?=$fa['orderNo']?>
                      </td>
                      <td nowrap class="subcontent-td">
                        <?=$fa['name']?>
                      </td>
                      <td nowrap class="subcontent-td">
                        <?=$modTitle?>
                      </td>
                      <td class="subcontent-td">&nbsp;
                        <?=($parent!=1)?subString($fa['htmlCode'],0,30):$fa['pluginPath']?>
                      </td>
                      <td align="center" nowrap class="subcontent-td">
                        <?=$imgHidden?>
                      </td>
                      <td align="center" nowrap class="subcontent-td">
                        <?=$imgIndexOnly.$imgSideHidden?>
                      </td>
                      <td align="center" nowrap class="subcontent-td">
                        <?=$imgPlugin?>
                      </td>
                    </tr>
                    <?
		}
		?>
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
			<?=$strModuleShowHidden?>:
            <input type="radio" name="operation" value="ishidden" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strHidden?>
            <input type="radio" name="operation" value="isshow" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strShow?>
            |
			<?=$strModuleSideShowHidden?>:
            <input type="radio" name="operation" value="isinstallhidden" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strHidden?>
            <input type="radio" name="operation" value="isInstallshow" onclick="Javascript:this.form.opmethod.value=1">
            <?=$strShow?>
            |
            <input name="opselect" type="hidden" value="">
            <input name="opmethod" type="hidden" value="">
            <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')"><br>
            &nbsp;(<font color=red>
            <?=$strModAlert?>
            </font>) </div>
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

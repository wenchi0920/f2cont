<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,$page_url);
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">

	  <div class="contenttitle"><?php echo $title?>
		<div class="page">
		  <?php view_page($page_url)?>
		</div>
	  </div>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td class="searchtool">
			<?php echo $strBlueFind?>
			&nbsp;
			<input type="text" name="seekname" size="8" value="<?php echo $seekname?>" class="pagenav">
			&nbsp;
			<input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $seek_url?>','find')">
			&nbsp;
			<input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $seek_url?>','all')">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="treeopen" class="btn" value="<?php echo $strTree_Open;?>" onClick="javascript:seekform.action='<?php echo "$showmode_url&showmode=open"?>'">
			&nbsp;
			&nbsp;
			<input type="submit" name="treeopen" class="btn" value="<?php echo $strTree_Close;?>" onClick="javascript:seekform.action='<?php echo "$showmode_url&showmode=close"?>'">
			&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;
			<input name="add" class="btn" type="button" value="<?php echo $strAdd?>" onclick="confirm_submit('<?php echo $edit_url?>','add')">
		  </td>
		</tr>
	  </table>
	  <div class="subcontent">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <tr class="subcontent-title">
			<td width="5%" nowrap align="center">&nbsp;</td>
			<td width="6%" nowrap align="center">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=orderNo\">$strCategoryOrderNo</a>";
				if ($order=="orderNo"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td nowrap class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strModType</a>";
				if ($order=="name"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
		  </tr>
		  <?php 
		//Record Limits
		if ($page<1){$page=1;}
		$start_record=($page-1)*$settingInfo['adminPageSize'];

		$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
		$query_result=$DMC->query($query_sql);
		$arr_parent = $DMC->fetchQueryAll($query_result);

		if ($_GET['showmode']=="open" or $_GET['showmode']==""){
			$image_path="themes/{$settingInfo['adminstyle']}/expand_no.gif";
			$visible="";
		}else{
			$image_path="themes/{$settingInfo['adminstyle']}/expand_yes.gif";
			$visible="none";
		}

		$arrImg=array("","module-top.gif","module-side.gif","module-content.gif");
		for ($i=0;$i<count($arr_parent);$i++){
			if ($arr_parent[$i]['isHidden']==1){
				$hidden="&nbsp;<img src=\"themes/{$settingInfo['adminstyle']}/lock.gif\" valign=\"absMiddle\" alt=\"$strCategoryHiddened\" /> \n";
			}else{
				$hidden="";
			}
			$parent=$arr_parent[$i]['id'];
		?>
		  <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			<td nowrap align="center" class="subcontent-td"> <img align="absMiddle" name="<?php echo "open_img$i"?>" src="<?php echo $image_path?>" onMouseUp="open_content('<?php echo $settingInfo['adminstyle']?>','<?php echo "open$i"?>',<?php echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;"> <a href="<?php echo "$edit_url&mark_id=".$arr_parent[$i]['id']."&action=order"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_track.gif" width="16" height="16" alt="<?php echo "$strCategoryExchage"?>" border="0"> </a> </td>
			<td align="center" class="subcontent-td">
			  <?php echo $arr_parent[$i]['orderNo']?>
			  <?php echo $hidden?>
			</td>
			<td nowrap class="subcontent-td">
			  <?php echo "<img src='themes/{$settingInfo['adminstyle']}/".$arrImg[$parent]."'>&nbsp;".$strModTypeArr[$parent]?>
			</td>
		  </tr>
		  <tr id="<?php echo "open$i"?>" style="DISPLAY:<?php echo $visible?>">
			<td colspan="3">
			  <table width="95%" align="right" border="0" cellspacing="0" cellpadding="0">
				<tr class="subcontent-title">
				  <td width="4%" align="center" class="whitefont">&nbsp;</td>
				  <td width="9%" align="center" class="whitefont">
					<?php echo $strEdit."/".$strDelete?>
				  </td>
				  <td width="5%" align="center" class="whitefont">
					<?php echo $strCategoryOrderNo?>
				  </td>
				  <td width="15%" class="whitefont">
					<?php echo $strModName?>
				  </td>
				  <td width="15%"  class="whitefont">
					<?php echo $strModTitle?>
				  </td>
				  <td width="30%"  class="whitefont">
					<?php echo ($parent!=1)?$strHtmlCode:$strLinkAdd?>
				  </td>
				  <td width="5%" align="center" class="whitefont">
					<?php echo $strHidden?>
				  </td>
				  <td width="6%" align="center" class="whitefont" nowrap>
					<?php 
					if ($parent==1){
						echo $strSidebarOpenWindow;
					}else if ($parent==2){
						echo $strModuleSideStyle;
					}else{
						echo $strModIndexOnly;
					}
					?>
				  </td>
				  <td width="6%" align="center" class="whitefont">
					<?php echo $strPlugin?>
				  </td>
				</tr>
				<?php 
				//取得子菜单
				$sub_sql="select * from ".$DBPrefix."modules where disType='".$arr_parent[$i]['id']."' order by $order";
				$query_result=$DMC->query($sub_sql);

				$index=0;
				while($fa = $DMC->fetchArray($query_result)){
					$index++;
					$parent=$fa['disType'];
					$imgHidden=($fa['isHidden']==1)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/lock.gif' alt='$strModAlrHidden' title='$strModAlrHidden'>":"&nbsp;";
					$imgSideHidden=($fa['isInstall']==1)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/lock.gif' title='$strModuleSideStyleHidden'>":"&nbsp;";
					
					//$imgIndexOnly=($fa['indexOnly']==1)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/add_top.gif' title='$strModIndexOnly'>":"&nbsp;";
					
					$imgPlugin=($fa['installDate']!=0)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/plugin.gif' title='$strPlugin'>":"&nbsp;";
					$modTitle=replace_string($fa['modTitle']);
					if ($fa['isSystem']!=1) {
						$isSystem="<a href='$edit_url&mark_id=".$fa['id']."&action=edit'><img src='themes/{$settingInfo['adminstyle']}/icon_modif.gif' alt='$strEdit' title='$strEdit' border=\"0\"></a>";
						$isSystem.="&nbsp;<a href='#' onclick=\"ConfirmForm('$edit_url&mark_id=".$fa['id']."&action=delete','$strAlertDeleteOption')\"><img src='themes/{$settingInfo['adminstyle']}/del.gif' alt='$strDelete' title='$strDelete' border=\"0\"></a>";
						$isClass="";
					} else {
						$isSystem="&nbsp;";
						$isClass="table_color3";
					}
					
					//显示方式
					if ($parent==1){
						$imgIndexOnly=($fa['indexOnly']==1)?$strYes:$strNo;
					}else if ($parent==2){
						$imgIndexOnly=($fa['indexOnly']==1)?"<img src='themes/{$settingInfo['adminstyle']}/add_top.gif' title='$strSidebarViewPosition' alt='$strSidebarViewPosition'>":"";
					}else{
						$imgIndexOnly=$strModuleContentShow[$fa['indexOnly']];			
					}
				?>
				<tr class="<?php echo $isClass?>" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
				  <td align="center" nowrap class="subcontent-td">
					<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
					</a> </td>
				  <td align="center" nowrap class="subcontent-td">
					<?php echo $isSystem?>
				  </td>
				  <td align="center" nowrap class="subcontent-td">
					<?php echo $fa['orderNo']?>
				  </td>
				  <td nowrap class="subcontent-td">
					<?php echo $fa['name']?>
				  </td>
				  <td nowrap class="subcontent-td">
					<?php echo $modTitle?>
				  </td>
				  <td class="subcontent-td">&nbsp;
					<?php echo ($parent!=1)?subString($fa['htmlCode'],0,30):$fa['pluginPath']?>
				  </td>
				  <td align="center" nowrap class="subcontent-td">
					<?php echo $imgHidden?>
				  </td>
				  <td align="center" nowrap class="subcontent-td">
					<?php echo $imgIndexOnly.$imgSideHidden?>
				  </td>
				  <td align="center" nowrap class="subcontent-td">
					<?php echo $imgPlugin?>
				  </td>
				</tr>
				<?php }?>
			  </table>
		  </tr>
		  </td>
		  <?php }//end while?>
		</table>
	  </div>
	  <br>
	  <div class="bottombar-onebtn"></div>
	  <div class="searchtool">
		<?php echo $strModuleShowHidden?>:
		<input type="radio" name="operation" value="ishidden" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strHidden?>
		<input type="radio" name="operation" value="isshow" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strShow?>
		|
		<?php echo $strModuleSideShowHidden?>:
		<input type="radio" name="operation" value="isinstallhidden" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strHidden?>
		<input type="radio" name="operation" value="isInstallshow" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strShow?>
		|
		<?php echo $strSidebarViewPosition?>:
		<input type="radio" name="operation" value="isIndexOnly" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strYes?>
		<input type="radio" name="operation" value="isIndexNo" onclick="Javascript:this.form.opmethod.value=1">
		<?php echo $strNo."&nbsp;&nbsp;(".$strSidebarViewPosition1.")"; ?>
		|
		<input name="opselect" type="hidden" value="">
		<input name="opmethod" type="hidden" value="">
		<input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')"><br>
		<input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php  dofoot(); ?>

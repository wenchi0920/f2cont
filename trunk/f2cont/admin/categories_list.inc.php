<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,$page_url);
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">
	<div class="contenttitle">
	  <?php echo $title?>
	  <div class="page">
		<?php view_page($page_url)?>
	  </div>
	</div>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="searchtool">
		  <?php echo $strBlueFind?>
		  &nbsp;
		  <input type="text" name="seekname" size="8" value="<?php echo $seekname?>" class="searchbox">
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
		  <td width="1%" class="whitefont" nowrap align="center">&nbsp;</td>
		  <td width="1%" class="whitefont" nowrap align="right">&nbsp;</td>
		  <td width="10%" class="whitefont" nowrap align="right"><a href="<?php echo "$edit_url&mark_id=0&action=order"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_track.gif" width="16" height="16" alt="<?php echo "$strCategoryExchage"?>" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		  <td width="8%" class="whitefont" nowrap align="center">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=orderNo\">$strCategoryOrderNo</a>";
			if ($order=="orderNo"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="8%" class="whitefont" nowrap align="center">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=cateIcons\">$strCategoryIcons</a>";
			if ($order=="cateIcons"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="20%" class="whitefont" nowrap>
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strCategoryName</a>";
			if ($order=="name"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="30%" class="whitefont" nowrap>
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=cateTitle\">$strCategoryDescription</a>";
			if ($order=="cateTitle"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="10%" class="whitefont" nowrap>
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=cateCount\">$strCategoryCount</a>";
			if ($order=="cateCount"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="40%" class="whitefont" nowrap>
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=outLinkUrl\">$strCategoryUrl</a>";
			if ($order=="outLinkUrl"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
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

		if ($_GET['showmode']=="open"){
			$image_path="themes/{$settingInfo['adminstyle']}/expand_no.gif";
			$visible="";
		}else{
			$image_path="themes/{$settingInfo['adminstyle']}/expand_yes.gif";
			$visible="none";
		}

		for ($i=0;$i<count($arr_parent);$i++){
			if ($arr_parent[$i]['isHidden']==1){
				$hidden="<img src=\"themes/{$settingInfo['adminstyle']}/lock.gif\" alt=\"$strCategoryHiddened\"> \n";
				//$hidden="&nbsp; [<font color=red>$strCategoryHiddened</font>] \n";
			}else{
				$hidden="";
			}

			//取得子菜单
			$sub_sql="select * from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' order by $order";
			$query_result=$DMC->query($sub_sql);
			$parent_num=$DMC->numRows($query_result);
			?>
			<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			  <td width="1%" align="center" nowrap class="subcontent-td"><?php echo $hidden?>&nbsp;</td>
			  <td width="1%" align="right" nowrap class="subcontent-td">
				<?php if ($parent_num>0){?>
				<img align="absMiddle" name="<?php echo "open_img$i"?>" src="<?php echo $image_path?>" onMouseUp="open_content('<?php echo $settingInfo['adminstyle']?>','<?php echo "open$i"?>',<?php echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;">
				<?php }else{echo "&nbsp;";}?>
			  </td>
			  <td width="10%" nowrap class="subcontent-td">
				<INPUT type=checkbox value="<?php echo $arr_parent[$i]['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
				<a href="<?php echo "$edit_url&mark_id=".$arr_parent[$i]['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0"> </a> &nbsp; <a href="<?php echo "$edit_url&mark_id=".$arr_parent[$i]['id']."&action=order"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_track.gif" width="16" height="16" alt="<?php echo "$strCategoryExchage"?>" border="0"> </a> </td>
			  <td width="8%" nowrap align="center" class="subcontent-td">
				<?php echo $arr_parent[$i]['orderNo']?>
			  </td>
			  <td width="8%" nowrap class="subcontent-td" align="center">
				<img src="../images/icons/<?php echo $arr_parent[$i]['cateIcons']?>.gif" align="absMiddle"> 
			  </td>
			  <td width="20%" nowrap class="subcontent-td">
				<?php echo $arr_parent[$i]['name']?>
			  </td>
			  <td width="30%" nowrap class="subcontent-td">
				<?php echo ($arr_parent[$i]['cateTitle']=="")?"&nbsp;":$arr_parent[$i]['cateTitle']?>
			  </td>
			  <td width="10%" nowrap class="subcontent-td">
				<?php echo $arr_parent[$i]['cateCount']?>
			  </td>
			  <td width="40%" nowrap class="subcontent-td">
				<?php echo ($arr_parent[$i]['outLinkUrl']=="")?"&nbsp;":$arr_parent[$i]['outLinkUrl']?>
			  </td>
			</tr>
			<?php if ($parent_num>0){?>
			<tr id="<?php echo "open$i"?>" style="DISPLAY:<?php echo $visible?>">
			  <td colspan="9">
				<table width="93%" align="right" border="0" cellspacing="0" cellpadding="0">
				  <tr class="subcontent-title">
					<td width="8%" align="center" class="whitefont">&nbsp;</td>
					<td width="8%" align="center" class="whitefont">
					  <?php echo $strCategoryOrderNo?>
					</td>
					<td width="8%" class="whitefont">
					  <?php echo $strCategoryIcons?>
					</td>
					<td width="20%" class="whitefont">
					  <?php echo $strCategoryName?>
					</td>
					<td width="30%"  class="whitefont">
					  <?php echo $strCategoryDescription?>
					</td>
					<td width="10%" align="center" class="whitefont">
					  <?php echo $strCategoryCount?>
					</td>
					<td width="30%" class="whitefont">
					  <?php echo $strCategoryUrl?>
					</td>
				  </tr>
				  <?php while($fa = $DMC->fetchArray($query_result)){?>
				  <tr onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
					<td width="8%" nowrap class="subcontent-td">
					  <INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
					  <a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0"> </a> </td>
					<td width="8%" nowrap align="center" class="subcontent-td">
					  <?php echo $fa['orderNo']?>
					</td>
				    <td width="8%" nowrap class="subcontent-td" align="center">
					  <img src="../images/icons/<?php echo $fa['cateIcons']?>.gif" align="absMiddle"> 
				    </td>
					<td width="20%" nowrap class="subcontent-td">
					  <?php echo $fa['name']?>
					</td>
					<td width="30%" nowrap class="subcontent-td">
					  <?php echo ($fa['cateTitle']=="")?"&nbsp;":$fa['cateTitle']?>
					</td>
					<td width="10%" align="center" nowrap class="subcontent-td">
					  <?php echo $fa['cateCount']?>
					</td>
					<td width="30%" nowrap class="subcontent-td">
					  <?php echo ($fa['outLinkUrl']=="")?"&nbsp;":$fa['outLinkUrl']?>
					</td>
				  </tr>
				  <?php }?>
				</table>
			</td></tr>
			<?php }//end parent?>
		<?php }//end while?>
		</table>
	</div>

	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="ishidden" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strHidden?>
	  <input type="radio" name="operation" value="isshow" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strShow?>
	  |
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input type="radio" name="operation" value="move" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strCategoryMove?>
	  <?php get_category_parent("parent","","style=\"font-size:12px;\" onChange=\"seekform.elements['operation'][3].checked=true;\"")?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php  dofoot(); ?>
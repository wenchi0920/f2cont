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
		  <?php if ($showmethod=="parent"){?>
		  <input type="submit" name="treeopen" class="btn" value="<?php echo $strTree_Open;?>" onClick="javascript:seekform.action='<?php echo "$showmode_url&showmode=open"?>'">
		  &nbsp;
		  &nbsp;
		  <input type="submit" name="treeopen" class="btn" value="<?php echo $strTree_Close;?>" onClick="javascript:seekform.action='<?php echo "$showmode_url&showmode=close"?>'">
		  <?php }?>
		  &nbsp;
		  &nbsp;
		  <input type="submit" name="list_method" class="btn" value="<?php echo $strNormal;?>" onClick="javascript:seekform.action='<?php echo "$showmethod_url&showmethod=parent"?>'" <?php echo ($showmethod=="parent")?"disabled":""?>>
		  &nbsp;
		  &nbsp;
		  <input type="submit" name="parent_method" class="btn" value="<?php echo $strList;?>" onClick="javascript:seekform.action='<?php echo "$showmethod_url&showmethod=list"?>'" <?php echo ($showmethod=="list")?"disabled":""?>>
		</td>
	  </tr>
	</table>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="1%" nowrap class="whitefont">&nbsp;</td>
		  <td width="1%" nowrap align="center">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  </td>
		  <td width="1%" nowrap align="center" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=isSecret\">$strStatus</a>";
			if ($order=="isSecret"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="15%" nowrap align="center" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=postTime\">$strPostTime</a>";
			if ($order=="postTime"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="35%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=content\">$strAdminGuestBookContent</a>";
			if ($order=="content"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="13%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=author\">$strAdminGuestBookAuthor</a>";
			if ($order=="author"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="10%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=ip\">$strFiltersCategory3</a>";
			if ($order=="ip"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
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
		$imgHidden=($arr_parent[$i]['isSecret']==1)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/lock.gif' title='$strTbAlrHidden'>":"&nbsp;";

		if ($showmethod=="parent"){
			//取得回复
			$sub_sql="select * from ".$DBPrefix."comments where parent='".$arr_parent[$i]['id']."' order by postTime";
			$query_result=$DMC->query($sub_sql);
			$parent_num=$DMC->numRows($query_result);
		}else{
			$parent_num=0;
		}
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="subcontent-td">
			<?php if ($parent_num>0){?>
			<img align="absMiddle" name="<?php echo "open_img$i"?>" src="<?php echo $image_path?>" onMouseUp="open_content('<?php echo $settingInfo['adminstyle']?>','<?php echo "open$i"?>',<?php echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;margin-top:5px;">
			<?php }else{echo "&nbsp;";}?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $arr_parent[$i]['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo "$edit_url&mark_id=".$arr_parent[$i]['id']."&logId=".$arr_parent[$i]['logId']."&action=add"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_track.gif" width="16" height="16" alt="<?php echo "$strGuestBookReply"?>" border="0" align="absMiddle"> </a>
			<a href="<?php echo "$edit_url&mark_id=".$arr_parent[$i]['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0" align="absMiddle"> </a> 
			<a href="<?php echo "../index.php?load=read&id=".$arr_parent[$i]['logId']?>" target="_blank"><img src="themes/<?php echo $settingInfo['adminstyle']?>/browse.gif" width="16" height="16" alt="<?php echo "$strLogTitle"?>" border="0" align="absMiddle"> </a> 
		  </td>
		  <td align="center" class="subcontent-td">
			<?php echo $imgHidden?>
		  </td>
		  <td align="center" nowrap class="subcontent-td">
			<?php echo format_time("L",$arr_parent[$i]['postTime'])?>
		  </td>
		  <td class="subcontent-td">
			<?php echo ubb($arr_parent[$i]['content'])?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $arr_parent[$i]['author']?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $arr_parent[$i]['ip']?>
		  </td>
		</tr>
		<?php if ($parent_num>0){?>
		<tr id="<?php echo "open$i"?>" style="DISPLAY:<?php echo $visible?>">
		  <td colspan="7">
			<table width="95%" align="right" border="0" cellspacing="0" cellpadding="0">
			  <tr class="subcontent-title">
				<td width="1%" nowrap align="center" class="whitefont">&nbsp;</td>
				<td width="1%" nowrap align="center" class="whitefont">
				  <?php echo $strStatus?>
				</td>
				<td width="15%" nowrap align="center" class="whitefont">
				  <?php echo $strPostTime?>
				</td>
				<td width="40%" nowrap class="whitefont">
				  <?php echo $strAdminGuestBookContent?>
				</td>
				<td width="10%" nowrap class="whitefont">
				  <?php echo $strAdminGuestBookAuthor?>
				</td>
				<td width="10%" nowrap align="center" class="whitefont">
				  <?php echo $strFiltersCategory3?>
				</td>
			  </tr>
			  <?php 
			while($fa = $DMC->fetchArray($query_result)){
				$imgHidden=($fa['isSecret']==1)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/lock.gif' title='$strTbAlrHidden'>":"&nbsp;";
			?>
			  <tr onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
				<td width="1%" nowrap class="subcontent-td" valign="top">
				  <INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
				  <a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0" align="absMiddle"> </a> 
				</td>
				<td nowrap align="center" class="subcontent-td" valign="top">
				  <?php echo $imgHidden?>
				</td>
				<td nowrap align="center" class="subcontent-td" valign="top">
				  <?php echo format_time("L",$fa['postTime'])?>
				</td>
				<td class="subcontent-td" valign="top">
				  <?php echo ubb($fa['content'])?>
				</td>
				<td nowrap class="subcontent-td" valign="top">
				  <?php echo $fa['author']?>
				</td>
				<td align="center" nowrap class="subcontent-td" valign="top">
				  <?php echo $fa['ip']?>
				</td>
			  </tr>
			  <?php }?>
			</table>
		  </td>
		</tr>
		<?php }//end parent?>
	<?php }//end while	?>
	  </table>
	</div>

	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input type="radio" name="operation" value="hidden" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strHidden?>
	  <input type="radio" name="operation" value="show" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strShow?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

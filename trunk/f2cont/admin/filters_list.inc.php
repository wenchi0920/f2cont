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
		  <input type="text" name="seekname" size="8" value="<?php echo $seekname?>" class="searchbox">
		  &nbsp;
		  <select name="seekcategory">
			<option value="">
			<?php echo $strFiltersCategorySelect?>
			</option>
			<option value="1" <?php echo ($seekcategory==1)?"selected":""?>>
			<?php echo $strFiltersCategory1?>
			</option>
			<option value="2" <?php echo ($seekcategory==2)?"selected":""?>>
			<?php echo $strFiltersCategory2?>
			</option>
			<option value="3" <?php echo ($seekcategory==3)?"selected":""?>>
			<?php echo $strFiltersCategory3?>
			</option>
			<option value="4" <?php echo ($seekcategory==4)?"selected":""?>>
			<?php echo $strFiltersCategory4?>
			</option>
		  </select>
		  &nbsp;
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $seek_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $seek_url?>','all')">
		  &nbsp;
		  <input name="add" class="btn" type="button" value="<?php echo $strAdd?>" onclick="confirm_submit('<?php echo $edit_url?>','add')">
		</td>
	  </tr>
	</table>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="8%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="8%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=id\">$strFiltersOrderNo</a>";
			if ($order=="id"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="36%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=category\">$strFiltersCategory</a>";
			if ($order=="category"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="36%" nowrap class="whitefont">
			<?php
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strFiltersName</a>";
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
		while($fa = $DMC->fetchArray($query_result)){
		$index++;
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="8%" nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0"> </a> </td>
		  <td width="8%" nowrap class="subcontent-td">
			<?php echo $fa['id']?>
		  </td>
		  <td width="36%" nowrap class="subcontent-td">
			<?php echo getFiltersCategoryName($fa['category'])?>
		  </td>
		  <td width="36%" nowrap class="subcontent-td">
			<?php echo $fa['name']?>
		  </td>
		</tr>
		<?php }//end while?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php  dofoot(); ?>

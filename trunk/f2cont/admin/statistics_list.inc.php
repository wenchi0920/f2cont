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
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $seek_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $seek_url?>','all')">
		  &nbsp;&nbsp;						
		  &nbsp;
		  <input name="findday" class="btn" type="submit" value="<?php echo $strStatisticsVisitsDay?>" onclick="confirm_submit('<?php echo $seek_url?>&visits=','find')" <?php echo ($visits=="")?"disabled":""?>>
		  &nbsp;
		  <input name="findmonth" class="btn" type="submit" value="<?php echo $strStatisticsVisitsMonth?>" onclick="confirm_submit('<?php echo $seek_url?>&visits=month','find')" <?php echo ($visits=="month")?"disabled":""?>>
		  &nbsp;
		  <input name="findyear" class="btn" type="submit" value="<?php echo $strStatisticsVisitsYear?>" onclick="confirm_submit('<?php echo $seek_url?>&visits=year','find')" <?php echo ($visits=="year")?"disabled":""?>>
		</td>
	  </tr>
	</table>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="4%" nowrap align="center">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="36%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=visitDate\">$strStatisticsVisitsDate</a>";
			if ($order=="visitDate"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="36%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=visits\">$strStatisticsVisitsNum</a>";
			if ($order=="visits"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
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
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="4%" nowrap align="center" class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $fa['visitDate']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
		  </td>
		  <td width="36%" nowrap class="subcontent-td">
			<?php echo $fa['visitDate']?>
		  </td>
		  <td width="36%" nowrap class="subcontent-td"><?php echo $fa['visits']?></td>
		</tr>
		<?php }//end while?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="delete" checked onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
   </div>
  </div>
</form>
<?php  dofoot(); ?>

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
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="8%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="15%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=blogLogo\">$strLinkLogo</a>";
			if ($order=="blogLogo"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="19%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=name\">$strLinksName</a>";
			if ($order=="name"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="58%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=blogUrl\">$strLinksLinkUrl</a>";
			if ($order=="blogUrl"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
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
			$blogLogo=($fa['blogLogo']!="")?"<img src='".$fa['blogLogo']."'>":"&nbsp;";
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo "links.php?mark_id=".$fa['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0"> </a> 
		  </td>
		  <td nowrap class="subcontent-td" style="padding-top:3px;padding-bottom:3px"><?php echo $blogLogo?></td>
		  <td nowrap class="subcontent-td"><?php echo safe_convert($fa['name'])?></td>
		  <td nowrap class="subcontent-td"><a href="<?php echo $fa['blogUrl']?>" target="_blank"><?php echo safe_convert($fa['blogUrl'])?></a></td>
		</tr>
		<?php }//end while?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strRefuse?>
	  |
	  <input type="radio" name="operation" value="move" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLinkAddToGroup?>
	  <?php  linkgroup_select("move_group","","class=\"searchbox\" onchange=\"seekform.elements['operation'][1].checked=true;\""); ?>
	  |
	  <input type="radio" name="operation" value="movetext" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLinkAddToGroup2?>
	  <?php  linkgroup_select("move_group2","","class=\"searchbox\" onchange=\"seekform.elements['operation'][2].checked=true;\""); ?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
   </div>
  </div>
</form>
<?php  dofoot(); ?>

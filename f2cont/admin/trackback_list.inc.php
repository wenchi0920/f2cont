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
		  </td>
		</tr>
	  </table>
	  <div class="subcontent">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <tr class="subcontent-title">
			<td width="5%" nowrap align="center" class="whitefont"> &nbsp;&nbsp;&nbsp;&nbsp;
			  <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
			</td>
			<td width="5%" nowrap align="center" class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=isApp\">$strStatus</a>";
				if ($order=="isApp"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td width="15%" nowrap class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.postTime\">$strPostTime</a>";
				if ($order=="a.postTime"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td width="15%" nowrap class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=b.logTitle\">$strLogTitle</a>";
				if ($order=="b.logTitle"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td width="40%" nowrap class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=tbTitle\">$strSubject</a>";
				if ($order=="tbTitle"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td width="15%" nowrap class="whitefont">
			  <?php 
				echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=blogSite\">$strName</a>";
				if ($order=="blogSite"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
				?>
			</td>
			<td width="13%" nowrap class="whitefont">
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

		foreach($arr_parent as $i=>$fa){
			$imgHidden=($fa['isApp']==0)?"&nbsp;&nbsp;<img src='themes/{$settingInfo['adminstyle']}/lock.gif' title='$strTbAlrHidden'>":"&nbsp;";
		?>
		  <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			<td nowrap align="center" class="subcontent-td"> <img align="absMiddle" name="<?php echo "open_img$i"?>" src="<?php echo $image_path?>" onMouseUp="open_content('<?php echo $settingInfo['adminstyle']?>','<?php echo "open$i"?>',<?php echo "open_img$i"?>)" style="COLOR: #ccddff; cursor: pointer;">
			  <INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			</td>
			<td align="center" class="subcontent-td">
			  <?php echo $imgHidden?>
			</td>
			<td class="subcontent-td">
			  <?php echo format_time("L",$fa['postTime'])?>
			</td>
			<td class="subcontent-td">
			  <?php echo "<a href=\"../index.php?load=read&id=".$fa['cid']."\" target=\"_blank\" title=\"".$fa['logTitle']."\">".subString($fa['logTitle'],0,18)."</a>"?>
			</td>
			<td nowrap class="subcontent-td"><a href='<?php echo $fa['blogUrl']?>' target="_blank">
			  <?php echo $fa['tbTitle']?>
			  </a></td>
			<td nowrap class="subcontent-td"><a href='<?php echo $fa['blogUrl']?>' target="_blank">
			  <?php echo $fa['blogSite']?>
			  </a></td>
			<td nowrap class="subcontent-td">
			  <?php echo $fa['ip']?>
			</td>
		  </tr>
		  <tr id="<?php echo "open$i"?>" style="DISPLAY:<?php echo $visible?>">
			<td colspan="7">
			  <table width="95%" align="right" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td class="subcontent-td">
					<?php echo strip_tags(dencode($fa['content']),"<br />")?>
				  </td>
				</tr>
			  </table>
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

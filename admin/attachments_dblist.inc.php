<?php 
@set_time_limit(0);
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
if (empty($editorcode)) require('admin_menu.php');

if (!empty($ActionMessage)){
	print_message($ActionMessage);
}
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
		  <select name="seektype">
			<option value="">
			<?php echo $strAttachmentTypeAll?>
			</option>
			<option value="gif,jpg,png,bmp,jpeg,ico" <?php echo ($seektype=="gif,jpg,png,bmp,jpeg,ico")?"selected":""?>>
			<?php echo $strAttachmentTypeImage?>
			</option>
			<option value="rar,zip,bz2,gz,tar,ace,7z" <?php echo ($seektype=="rar,zip,bz2,gz,tar,ace,7z")?"selected":""?>>
			<?php echo $strAttachmentTypeWinrar?>
			</option>
			<option value="txt,doc,htm,html,wps,xsl,ppt" <?php echo ($seektype=="txt,doc,htm,html,wps,xsl,ppt")?"selected":""?>>
			<?php echo $strAttachmentTypeText?>
			</option>
			<option value="mp3,wma,wmv,rm,ra,rmvb,wav,asf,swf,flv" <?php echo ($seektype=="mp3,wma,wmv,rm,ra,rmvb,wav,asf,swf")?"selected":""?>>
			<?php echo $strAttachmentTypeMp3?>
			</option>
		  </select>
		  &nbsp;
		  <?php  category_select("seekcate",$seekcate,"class=\"searchbox\"",""); ?>
		  &nbsp;
		  <?php echo $strBlueFind?>
		  &nbsp;
		  <input type="text" name="seekname" size="8" value="<?php echo $seekname?>" class="searchbox">
		  &nbsp;
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $seek_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $seek_url?>','all')">
		  &nbsp;
		  <input name="add" class="btn" type="button" value="<?php echo $strAdd?>" onclick="confirm_submit('<?php echo $edit_url?>','add')">
		  &nbsp;
		  <input name="add" class="btn" type="button" value="<?php echo $strAttachmentDisk?>" onclick="confirm_submit('<?php echo $job_url."&job=folder";?>','')">
		  <?php if ($editorcode!=""){?>
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strAttachmentReturn?>" onclick="opener.location.href='<?php echo "attach.php?editorcode=$editorcode&mark_id=$mark_id"?>';opener.reload;window.close();">
		  <?php }?>
		</td>
	  </tr>
	</table>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="8%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="5%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.id\">$strAttachmentsOrderNo</a>";
			if ($order=="a.id"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="33%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.name\">$strAttachmentsName</a>";
			if ($order=="a.name"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="12%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.attTitle\">$strAttachmentsRemark</a>";
			if ($order=="a.attTitle"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <?php if (empty($editorcode)){?>
		  <td width="8%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.fileType\">$strAttachmentType</a>";
			if ($order=="a.fileType"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="9%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.fileSize\">$strAttachmentsSize</a>";
			if ($order=="a.fileSize"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>		  
		  <td width="13%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.postTime\">$strAttachmentsDate</a>";
			if ($order=="a.postTime"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <?php }?>
		  <td width="12%" class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.logId\">$strAttachmentLogs</a>";
			if ($order=="a.logId"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		</tr>
		<?php 
		//Record Limits
		if ($page<1) { $page=1; }
		$start_record=($page-1)*$settingInfo['adminPageSize'];

		$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
		$query_result=$DMC->query($query_sql);
		while($fa = $DMC->fetchArray($query_result)){
			if (strpos($fa['name'],"://")>0){
				$browse_path=$fa['name'];
			}else{
				$browse_path="../attachments/".$fa['name'];
			}
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="8%" nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $fa['name']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo $browse_path?>" target="_blank"><img src="themes/<?php echo $settingInfo['adminstyle']?>/browse.gif" width="16" height="16" alt="<?php echo "$strAttachmentsBrowse"?>" border="0"> </a> 
			<a href="<?php echo "$edit_url&file_id=".($fa['id'])."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" alt="<?php echo "$strEdit"?>" border="0"></a>
		  </td>
		  <td class="subcontent-td">
			<?php echo $fa['id']?>
		  </td>
		  <td class="subcontent-td">
			<?php echo $fa['name']?>
		  </td>
		  <td class="subcontent-td">
			<?php echo strpos($fa['attTitle'],".")>0?substr($fa['attTitle'],0,strrpos($fa['attTitle'],".")):$fa['attTitle']?>
		  </td>
		  <?php if (empty($editorcode)){?>
		  <td class="subcontent-td" nowrap>
			<?php echo empty($fa['fileType'])?"&nbsp;":$fa['fileType']?>
		  </td>
		  <td class="subcontent-td" nowrap>
			<?php echo formatFileSize($fa['fileSize'])?>
		  </td>
		  <td width="13%" class="subcontent-td" nowrap>
			<?php echo format_time("L",$fa['postTime'])?>
		  </td>
		  <?php }?>
		  <td class="subcontent-td">
			<?php 
			 if (empty($fa['logTitle'])) $fa['logTitle']=$strAttachmentNoLogs;
			 if ($fa['logId']<1){
				echo "---";
			 }else{?>
				<a href="../index.php?load=read&amp;id=<?php echo $fa['logId']?>" target="_blank" title="<?php echo $fa['logTitle'];?>"><?php echo $fa['logId']?><?php echo " (".subString($fa['logTitle'],0,5).")"?></a>
			 <?php }?>
		  </td>
		</tr>
		<?php }//end for?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <?php if ($editorcode!=""){?>
	  <input type="radio" name="operation" value="insert" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strAttachmentInsert?>
	  |
	  <?php }?>
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1" <?php echo (empty($editorcode))?"checked":""?>>
	  <?php echo $strDelete?>
	  |
	  <?php if (empty($editorcode)){?>
	  <input type="radio" name="operation" value="change" onclick="Javascript:this.form.opmethod.value=1;this.form.opselect.value=1">
	  <?php echo $strAttachmentChangeName.": ".$strAttachmentChangeOld?>
	  <INPUT TYPE="text" NAME="oldName" size="15" onKeyup="seekform.elements['operation'][1].checked=true;;this.form.opselect.value=1">
	  <?php echo $strAttachmentChangeNew?>
	  <INPUT TYPE="text" NAME="newName" size="15" onKeyup="seekform.elements['operation'][1].checked=true;;this.form.opselect.value=1">
	  |
	  <?php }?>
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  &nbsp;&nbsp;
	  <?php if ($editorcode!=""){?>
	  &nbsp;&nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strAttachmentReturn?>" onclick="opener.location.href='<?php echo "attach.php?editorcode=$editorcode&mark_id=$mark_id"?>';opener.reload;window.close();">
	  <?php }?>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php if (empty($editorcode)) dofoot(); ?>

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

	  <?php  if ($ActionMessage!="") { ?>
	  <br>
	  <fieldset>
	  <legend>
	  <?php echo $strErrorInfo?>
	  </legend>
	  <div align="center">
		<table border="0" cellpadding="2" cellspacing="1">
		  <tr>
			<td><span class="alertinfo">
			  <?php echo $ActionMessage?>
			  </span></td>
		  </tr>
		</table>
	  </div>
	  </fieldset>
	  <br>
	  <?php  } ?>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="searchtool">
		  <?php echo $strBlueFind?>
		  &nbsp;
		  <input type="text" name="seekname" size="5" value="<?php echo $seekname?>">
		  &nbsp;
		  <?php echo $strCategory?>
		  &nbsp;
		  <?php  category_select("seekcate",$seekcate,"class=\"searchbox\"",""); ?>
		  <?php echo $strTag?>
		  &nbsp;
		  <?php  tags_select("seektags",$seektags,"class=\"searchbox\""); ?>
		  &nbsp;
			<select name="seektype" class="searchbox">
			  <option value="" <?php  if ($seektype=="") { echo "selected"; }?>><?php echo $strAll?></option>
			  <option value="0" <?php  if ($seektype=="0") { echo "selected"; }?>><?php echo $strDraft?></option>
			  <option value="1" <?php  if ($seektype=="1") { echo "selected"; }?>><?php echo $strPubLog?></option>
			  <option value="3" <?php  if ($seektype=="3") { echo "selected"; }?>><?php echo $strHidLog?></option>
			</select>
		  &nbsp;
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $seek_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $seek_url?>','all')">
		  &nbsp;
		  <input name="add" class="btn" type="button" value="<?php echo $strAdd?>" onclick="confirm_submit('<?php echo $edit_url?>','add')">
		  &nbsp;
		  <?php if ($settingInfo['isHtmlPage']==1 and $_SESSION['rights']!="author"){?>
		  <input name="html" class="btn" type="button" value="<?php echo $strLogsCreateHtml?>" onclick="ConfirmForm('<?php echo "create_html.inc.php?arrayhtmlid=all"?>','<?php echo $strLogsCreateHtmlHelp?>')">
		  <?php }?>
		</td>
	  </tr>
	</table>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="11%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="18%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.postTime\">$strPostTime</a>";
			if ($order=="a.postTime"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="10%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.cateId\">$strCategory</a>";
			if ($order=="a.cateId"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="30%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.logTitle\">$strLogTitle</a>";
			if ($order=="a.logTitle"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="10%" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.tags\">$strTag</a>";
			if ($order=="a.tags"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.autoSplit\">$strSettingUnitsWords</a>";
			if ($order=="a.autoSplit"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.saveType\">$strStatus</a>";
			if ($order=="a.saveType"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isTop\">$strTopTitle</a>";
			if ($order=="a.isTop"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isComment\">$strLogComm</a>";
			if ($order=="a.isComment"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.isTrackback\">$strLogTB</a>";
			if ($order=="a.isTrackback"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
			?>
		  </td>
		  <td width="5%" align="center" nowrap class="whitefont">
			<?php 
			echo "<a class=\"columntitle\" href=\"$order_url&page=$page&order=a.password\">$strLogPasswordTitle</a>";
			if ($order=="a.password"){echo "<img src=\"themes/{$settingInfo['adminstyle']}/down.gif\" border=\"0\">";}
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
		$index++;
		if ($fa['saveType']==0){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/draft.gif' alt='$strDraftLog' title='$strDraftLog'>";
		}elseif($fa['saveType']==3){
			$imgHidden="<img src='themes/{$settingInfo['adminstyle']}/lock.gif' alt='$strHiddenLog' title='$strHiddenLog'>";
		}else{
			$imgHidden="&nbsp;";
		}
		if ($fa['isTop']==1){
			$imgIndexOnly="<img src='themes/{$settingInfo['adminstyle']}/add_top.gif' alt='$strTopView' title='$strTopView'>";
		}elseif($fa['isTop']==2){
			$imgIndexOnly="<img src='themes/{$settingInfo['adminstyle']}/hidden_top.gif' alt='$strLogTopClose' title='$strLogTopClose'>";
		}else{
			$imgIndexOnly="&nbsp;";
		}
		$imgComments=($fa['isComment']==0)?"<img src='themes/{$settingInfo['adminstyle']}/tool_post.gif' alt='$strLogCommentsHelp' title='$strLogCommentsHelp'>":"&nbsp;";
		$imgTrackback=($fa['isTrackback']==0)?"<img src='themes/{$settingInfo['adminstyle']}/tool_trackback.gif' alt='$strLogTrackbackHelp' title='$strLogTrackbackHelp'>":"&nbsp;";
		$imgPassword=($fa['password']!="")?"<img src='themes/{$settingInfo['adminstyle']}/tool_password.gif' alt='$strLogPasswordHelp' title='$strLogPasswordHelp'>":"&nbsp;";
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" alt="<?php echo "$strEdit"?>" border="0"></a>&nbsp; 
			<a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=trackback"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_track.gif" alt="<?php echo "$strSendTb"?>" border="0"></a>&nbsp; 
			<a href="attachments.php?<?php echo "job=db&seekname=".$fa['id'];?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/attach.gif" alt="<?php echo "$strAttachmentsBrowse"?>" border="0"></a>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo format_time("L",$fa['postTime'])?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo $fa['name']?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo "<a href=\"../index.php?load=read&id=".$fa['id']."\" target=\"_blank\" title=\"".$fa['logTitle']."\">".subString($fa['logTitle'],0,18)."</a>"?>
		  </td>
		  <td nowrap class="subcontent-td">
			<?php echo !empty($fa['tags'])?$fa['tags']:"&nbsp;"?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $fa['autoSplit']?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgHidden?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgIndexOnly?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgComments?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgTrackback?>
		  </td>
		  <td nowrap align="center" class="subcontent-td">
			<?php echo $imgPassword?>
		  </td>
		</tr>
		<?php }?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input type="radio" name="operation" value="publish" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strPubLog?>
	  <input type="radio" name="operation" value="private" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strHidLog?>
	  <input type="radio" name="operation" value="draft" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDraft?>
	  |
	  <input type="radio" name="operation" value="topopen" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogTopOpen?>
	  <input type="radio" name="operation" value="topclose" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogTopClose?>
	  <input type="radio" name="operation" value="notop" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strCTopTitle?>
	  |
	  <input type="radio" name="operation" value="ctshow" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strCommentShow?>
	  <input type="radio" name="operation" value="cthidden" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strCommentHidden?>
	  |
	  <input type="radio" name="operation" value="tbshow" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strTrackBackShow?>
	  <input type="radio" name="operation" value="tbhidden" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strTrackBackHidden?>
	  |
	  <input type="radio" name="operation" value="addautoSplit" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strAutoSplitLogs?>
	  <INPUT TYPE="text" NAME="addautoSplit" size="4" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; " onKeyup="seekform.elements['operation'][11].checked=true;" value="<?php echo $settingInfo['autoSplit']?>"> <?php echo $strSettingUnitsWords?>
	  |
	  <input type="radio" name="operation" value="logmove" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogCategoryMove?>
	  <?php category_select("move_category","","class=\"searchbox\" onchange=\"seekform.elements['operation'][12].checked=true;\"",""); ?>
	  |
	  <input type="radio" name="operation" value="addpassword" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogPassword?>
	  <INPUT TYPE="text" NAME="addpassword" size="10" maxlength="20" onKeyup="seekform.elements['operation'][13].checked=true;">
	  |
	  <input type="radio" name="operation" value="editdate" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strPubDate?>
	  <input name="pubTime" type="text" value="<?php echo format_time("Y-m-d H:i",time())?>" size="12" onKeyup="seekform.elements['operation'][14].checked=true;">
	  |
	  <input type="radio" name="operation" value="settags" onclick="Javascript:this.form.opmethod.value=1">
	  <?php  tags_select("settags","class=\"searchbox\"","onchange=\"seekform.elements['operation'][15].checked=true;\""); ?>
	  |
	  <?php if ($settingInfo['isHtmlPage']==1){?>
	  <input type="radio" name="operation" value="create_html" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strLogsCreateHtmlFoot?>
	  |
	  <?php }?>
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

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

	<div class="contenttitle"><?php echo $title?></div>
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
			<option value="wma,mp3,rm,ra,qt,wmv,swf,flv,mpg,avi,divx,asf,rmvb" <?php echo ($seektype=="wma,mp3,rm,ra,qt,wmv,swf,flv,mpg,avi,divx,asf,rmvb")?"selected":""?>>
			<?php echo $strAttachmentTypeMp3?>
			</option>
		  </select>
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
		  <input name="add" class="btn" type="button" value="<?php echo $strAttachmentDatabase?>" onclick="confirm_submit('<?php echo $job_url."&job=db";?>','')">		  
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
		  <td width="8%" nowrap class="whitefont" align="center"><?php echo "$strAttachmentsOrderNo";?></td>
		  <td width="16%" nowrap class="whitefont"><?php echo "$strAttachmentsName";?></td>
		  <td width="16%" nowrap class="whitefont"><?php echo "$strAttachmentsRemark";?></td>
		  <td width="16%" nowrap class="whitefont"><?php echo "$strAttachmentsSize";?></td>
		  <td width="16%" nowrap class="whitefont"><?php echo "$strAttachmentsDate";?></td>
		  <td width="5%" nowrap class="whitefont"><?php echo $strAttachmentLogs;?></td>
		</tr>
		<?php if ($basedir!="../attachments/"){?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="8%" nowrap align="right" class="subcontent-td"> <a href="<?php echo "$seek_url&basedir=".substr($basedir,0,strrpos(substr($basedir,0,strlen($basedir)-1),"/"))?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/tools_back.gif" alt="<?php echo "$strReturn"?>" border="0"> </a></td>
		  <td width="8%" nowrap class="subcontent-td" align="center">&nbsp;</td>
		  <td width="20%" nowrap class="subcontent-td">
			<?php echo substr($basedir,15,strlen($basedir)-16)?>
		  </td>
		  <td width="20%" nowrap class="subcontent-td">&nbsp;</td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo formatFileSize(filesize($basedir))?>
		  </td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo format_time("L",filemtime($basedir))?>
		  </td>
		  <td width="5%" nowrap class="subcontent-td" align="center">&nbsp;</td>
		</tr>
		<?php }?>
		<?php for ($index=0;$index<count($folderlist);$index++){?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="8%" nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $folderlist[$index]?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo "$seek_url&basedir=$basedir".$folderlist[$index]?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/tools_folder.gif" width="16" height="16" alt="<?php echo "$strAttachmentsBrowse"?>" border="0"> </a> </td>
		  <td width="8%" nowrap class="subcontent-td" align="center">
			<?php echo $index+1?>
		  </td>
		  <td width="20%" nowrap class="subcontent-td">
			<?php echo $folderlist[$index]?>
		  </td>
		  <td width="20%" nowrap class="subcontent-td">&nbsp;</td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo formatFileSize(filesize($basedir.$folderlist[$index]))?>
		  </td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo format_time("L",filemtime($basedir.$folderlist[$index]))?>
		  </td>
		  <td width="5%" nowrap class="subcontent-td" align="center">&nbsp;</td>
		</tr>
		<?php }//end for?>
		<?php 
		for ($index=0;$index<count($filelist);$index++){		 
			$dataInfo = $DMC->fetchArray($DMC->query("select id,attTitle,logId from ".$DBPrefix."attachments where name like '%".$filelist[$index]."'"));
			if ($dataInfo) {
				$fileTitle=$dataInfo['attTitle'];
				$fileid=$dataInfo['id'];
				$logId=$dataInfo['logId'];
			}else{
				$fileTitle="";
				$fileid="";
				$logId="";
			}
		?>
		<tr class="<?php echo (($index+count($folderlist))%2==0)?"table_color1":"table_color2"?>" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td width="8%" nowrap class="subcontent-td">
			<INPUT type=checkbox value="<?php echo $filelist[$index]?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
			<a href="<?php echo $basedir.$filelist[$index]?>" target="_blank"><img src="themes/<?php echo $settingInfo['adminstyle']?>/browse.gif" width="16" height="16" alt="<?php echo "$strAttachmentsBrowse"?>" border="0"> </a> 
			<?php if ($fileTitle!=""){?><a href="<?php echo "$edit_url&file_id=".$fileid."&action=edit"?>"><img src="themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" width="16" height="16" alt="<?php echo "$strEdit"?>" border="0"> </a><?php }?>
		  </td>
		  <td width="8%" nowrap class="subcontent-td" align="center">
			<?php echo $index+1+count($folderlist)?>
		  </td>
		  <td width="20%" nowrap class="subcontent-td">
			<?php echo $filelist[$index]?>
		  </td>
		  <td width="20%" nowrap class="subcontent-td">
			<?php echo (strpos($fileTitle,".")>0)?substr($fileTitle,0,strrpos($fileTitle,".")):"&nbsp;";?>
		  </td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo formatFileSize(filesize($basedir.$filelist[$index]))?>
		  </td>
		  <td width="16%" nowrap class="subcontent-td">
			<?php echo format_time("L",filemtime($basedir.$filelist[$index]))?>
		  </td>
		  <td width="5%" nowrap class="subcontent-td" align="center">
			<?php if ($logId>0){?>
			<a href="../index.php?load=read&id=<?php echo $logId?>" target="_blank" title="<?php echo $strAttachmentReadLogs;?>"><?php echo $logId?></a>
			<?php }else{?>
			&nbsp;
			<?php }?>
		  </td>
		</tr>
		<?php }//end for?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="insert" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strAttachmentInsert?>
	  |
	  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1">
	  <?php echo $strDelete?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">
	  &nbsp;&nbsp;
	  <input name="add" class="btn" type="button" value="<?php echo $strAttachmentFolder?>" onclick="confirm_submit('<?php echo $edit_url?>','addfolder')">
	  <?php if ($editorcode!=""){?>
	  &nbsp;&nbsp;
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strAttachmentReturn?>" onclick="opener.location.href='<?php echo "attach.php?editorcode=$editorcode&mark_id=$mark_id"?>';opener.reload;window.close();">
	  <?php }?>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php if (empty($editorcode)) dofoot(); ?>

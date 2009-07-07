<?
set_time_limit(0);
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "attachments_list.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");

if ($ActionMessage){
	print_message($ActionMessage);
}
?>
<form action="" method="post" name="seekform">
  <div id="content">
    <div class="box">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="6" height="20"><img src="images/main/content_lt.gif" width="6" height="21"></td>
          <td height="21" background="images/main/content_top.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rt.gif" width="6" height="21"></td>
        </tr>
        <tr>
          <td width="6" background="images/main/content_left.gif">&nbsp;</td>
          <td bgcolor="#FFFFFF" >
            <div class="contenttitle"><img src="images/content/attachments.gif" width="12" height="11">
              <?=$title?>
            </div>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="searchtool">
                  <?=$strAttachmentSelect?>
                  <select name="seektype">
                    <option value="">
                    <?=$strAttachmentTypeAll?>
                    </option>
                    <option value="gif,jpg,png,bmp,jpeg,ico" <?=($seektype=="gif,jpg,png,bmp,jpeg,ico")?"selected":""?>>
                    <?=$strAttachmentTypeImage?>
                    </option>
                    <option value="rar,zip,bz2,gz,tar,ace,7z" <?=($seektype=="rar,zip,bz2,gz,tar,ace,7z")?"selected":""?>>
                    <?=$strAttachmentTypeWinrar?>
                    </option>
                    <option value="txt,doc,htm,html,wps,xsl,ppt" <?=($seektype=="txt,doc,htm,html,wps,xsl,ppt")?"selected":""?>>
                    <?=$strAttachmentTypeText?>
                    </option>
                    <option value="mp3,wma,wmv,rm,ra,rmvb,wav,asf,swf" <?=($seektype=="mp3,wma,wmv,rm,ra,rmvb,wav,asf,swf")?"selected":""?>>
                    <?=$strAttachmentTypeMp3?>
                    </option>
                  </select>
                  &nbsp;
                  <?=$strBlueFind?>
                  &nbsp;
                  <input type="text" name="seekname" size="8" value="<?=$seekname?>" class="searchbox">
                  &nbsp;
                  <input name="find" class="btn" type="submit" value="<?=$strFind?>" onclick="confirm_submit('<?=$seek_url?>','find')">
                  &nbsp;
                  <input name="findall" class="btn" type="button" value="<?=$strAll?>" onclick="confirm_submit('<?=$seek_url?>','all')">
                  &nbsp;
                  <input name="add" class="btn" type="button" value="<?=$strAdd?>" onclick="confirm_submit('<?=$edit_url?>','add')">
                </td>
              </tr>
            </table>
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="8%" nowrap class="whitefont">
                    <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                  </td>
                  <td width="8%" nowrap class="whitefont" align="center"><?echo "$strAttachmentsOrderNo";?></td>
                  <td width="16%" nowrap class="whitefont"><?echo "$strAttachmentsName";?></td>
				  <td width="16%" nowrap class="whitefont"><?echo "$strAttachmentsRemark";?></td>
                  <td width="16%" nowrap class="whitefont"><?echo "$strAttachmentsSize";?></td>
                  <td width="16%" nowrap class="whitefont"><?echo "$strAttachmentsDate";?></td>
                  <td width="5%" nowrap class="whitefont">&nbsp;</td>
                </tr>
                <?if ($basedir!="../attachments/"){?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td width="8%" nowrap align="right" class="subcontent-td"> <a href="<?="$seek_url&basedir=".substr($basedir,0,strrpos(substr($basedir,0,strlen($basedir)-1),"/"))?>"><img src="images/content/tools_back.gif" width="16" height="16" alt="<?="$strReturn"?>" border="0"> </a></td>
                  <td width="8%" nowrap class="subcontent-td" align="center">&nbsp;</td>
                  <td width="20%" nowrap class="subcontent-td">
                    <?=substr($basedir,15,strlen($basedir)-16)?>
                  </td>
				  <td width="20%" nowrap class="subcontent-td">&nbsp;</td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=formatFileSize(filesize($basedir))?>
                  </td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=format_time("L",filemtime($basedir))?>
                  </td>
                  <td width="5%" nowrap class="subcontent-td" align="center">&nbsp;</td>
                </tr>
                <?}?>
                <?for ($index=0;$index<count($folderlist);$index++){?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td width="8%" nowrap class="subcontent-td">
                    <INPUT type=checkbox value="<?=$folderlist[$index]?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                    <a href="<?="$seek_url&basedir=$basedir".$folderlist[$index]?>"><img src="images/content/tools_folder.gif" width="16" height="16" alt="<?="$strAttachmentsBrowse"?>" border="0"> </a> </td>
                  <td width="8%" nowrap class="subcontent-td" align="center">
                    <?=$index+1?>
                  </td>
                  <td width="20%" nowrap class="subcontent-td">
                    <?=$folderlist[$index]?>
                  </td>
				  <td width="20%" nowrap class="subcontent-td">&nbsp;</td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=formatFileSize(filesize($basedir.$folderlist[$index]))?>
                  </td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=format_time("L",filemtime($basedir.$folderlist[$index]))?>
                  </td>
                  <td width="5%" nowrap class="subcontent-td" align="center">&nbsp;</td>
                </tr>
                <?}//end for?>
                <?
				for ($index=0;$index<count($filelist);$index++){
					 $file_title=getFieldValue($DBPrefix."attachments","where name like '%".$filelist[$index]."'","attTitle");			
				?>
                <tr class="<?=(($index+count($folderlist))%2==0)?"table_color1":"table_color2"?>" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td width="8%" nowrap class="subcontent-td">
                    <INPUT type=checkbox value="<?=$filelist[$index]?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                    <a href="<?=$basedir.$filelist[$index]?>" target="_blank"><img src="images/content/browse.gif" width="16" height="16" alt="<?="$strAttachmentsBrowse"?>" border="0"> </a> 
					<?if ($file_title!=""){?><a href="<?="$edit_url&file_id=".$filelist[$index]."&action=edit"?>"><img src="images/content/icon_modif.gif" width="16" height="16" alt="<?="$strEdit"?>" border="0"> </a><?}?>
				  </td>
                  <td width="8%" nowrap class="subcontent-td" align="center">
                    <?=$index+1+count($folderlist)?>
                  </td>
                  <td width="20%" nowrap class="subcontent-td">
                    <?=$filelist[$index]?>
                  </td>
                  <td width="20%" nowrap class="subcontent-td">
                    <?echo ($file_title)?substr($file_title,0,strrpos($file_title,".")):"&nbsp;";?>
                  </td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=formatFileSize(filesize($basedir.$filelist[$index]))?>
                  </td>
                  <td width="16%" nowrap class="subcontent-td">
                    <?=format_time("L",filemtime($basedir.$filelist[$index]))?>
                  </td>
                  <td width="5%" nowrap class="subcontent-td" align="center">
                    <?if (check_image_type($basedir.$filelist[$index])){?>
                    <a href="<?=$basedir.$filelist[$index]?>" target="_blank"><img src="<?=$basedir.$filelist[$index]?>" width="30" height="30"/></a>
                    <?}else{?>
                    &nbsp;
                    <?}?>
                  </td>
                </tr>
                <?}//end for?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1" checked>
              <?=$strDelete?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="1">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')">
              <span class="style1">(
              <?=$strDeleteInfo?>
              )</span> </div>
          </td>
          <td width="6" background="images/main/content_right.gif">&nbsp;</td>
        </tr>
        <tr>
          <td width="6" height="20"><img src="images/main/content_lb.gif" width="6" height="20"></td>
          <td height="20" background="images/main/content_bottom.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rb.gif" width="6" height="20"></td>
        </tr>
      </table>
    </div>
  </div>
</form>
<? dofoot(); ?>

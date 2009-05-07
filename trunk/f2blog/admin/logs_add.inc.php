<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');

//读取编辑器
include_once(F2BLOG_ROOT."./include/xmlparse.inc.php");
$handle=opendir("../editor/"); 
$arr_editorName=array();
$arr_editorPath=array();
$editorpath="";
while ($file = readdir($handle)){ 
	if (preg_match("/editor_(.+)\.xml/is",$file)){
		$arr_editor=xmlArray(F2BLOG_ROOT."./editor/$file");
		$arr_editorName[]=$arr_editor['EditorName'];
		$arr_editorRemark[$arr_editor['EditorName']]=$arr_editor['EditorDecription'];
		$arr_editorPath[$arr_editor['EditorName']]=$arr_editor['EditorPath'];
		$arr_editorCode[$arr_editor['EditorName']]=$arr_editor['EditorCode'];

		//取得相对应的编辑器
		if ($arr_editor['EditorName']==$logsediter){
			$editorpath=F2BLOG_ROOT."./".$arr_editor['EditorPath'];
			$editorcode=$arr_editor['EditorCode'];
		}
	}	
} 
//没有该编辑器，将取得默认编辑器。
if (!file_exists($editorpath)){
	$editorcode=$arr_editorCode[$settingInfo['defaultedits']];
	$editorpath=F2BLOG_ROOT."./".$arr_editorPath[$settingInfo['defaultedits']];
}
closedir($handle);
?>
<form action="" method="post" name="seekform">
  <div id="content">
	<div class="contenttitle"><?php echo $title?></div>
	<br>
	<div class="subcontent">
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
		<tr class="subcontent-input">
		  <td width="5%" nowrap class="input-titleblue">
			<?php echo $strSubject?>
		  </td>
		  <td width="65%">
			<input name="logTitle" id="logTitle" class="textbox" type="text" size="80" value="<?php echo $logTitle?>">
		  </td>
		  <td width="10%" nowrap class="input-titleblue" align="right">
			<?php echo $strCategory?>
		  </td>
		  <td width="20%">
			<span id="category"><?php  category_select("cateId",$cateId,"",""); ?></span>
			<input name="btncategory" id="btncategory" class="btn" type="button" value="<?php echo $strAdd?>" onClick="document.getElementById('category').innerHTML='<input name=cateId size=20 class=textbox type=text size=50>';document.getElementById('btncategory').style.display='none';document.seekform.cateId.focus();document.getElementById('btnrcategory').style.display='';">
			<input name="btnrcategory" id="btnrcategory" class="btn" type="button" value="<?php echo $strReturn?>" onClick="document.getElementById('category').innerHTML=document.seekform.catebak.value;document.getElementById('btnrcategory').style.display='none';document.seekform.cateId.focus();document.getElementById('btncategory').style.display='';" style="display:none">
			<input name="oldCateId" type="hidden" size="50" value="<?php echo $oldCateId?>">
			<input name="edittype" id="edittype" type="hidden" size="50" value="<?php echo $edittype?>">
			<input name="catebak" id="catebak" type="hidden" value="<?php ob_start();category_select("cateId",$cateId,"","");$contents=ob_get_contents();ob_end_clean();echo str_replace('"','',$contents);?>">
		  </td>
		</tr>
		<tr>
		  <td nowrap>
			<?php echo $strTag?>
		  </td>
		  <td>
			<input name="tags" class="textbox" type="text" size="50" value="<?php echo $tags?>">
			&nbsp;<span style="color:#999">
			<?php echo $strPlsSelTags?>
			</span> </td>
		  <td nowrap align="right">
			<?php echo $strWeather?>
		  </td>
		  <td nowrap>
			<?php  weather_select("weather",$weather,""); ?>
			<input name="oldTags" type="hidden" size="50" value="<?php echo $oldTags?>">
			<select name="logsediter" id="logsediter" onchange="onclick_update(this.form,'<?php echo $action?>')">
				<?php
				foreach($arr_editorName as $key=>$value){
					$selected=($logsediter==$value)?"selected":"";
					echo "<option value=\"$value\" $selected>".$arr_editorRemark[$value]."</option>\n";
			    }
				?>
			</select>
		  </td>
		</tr>
		<tr>
		  <td><?php if (in_array('ccVideo',$arrPluginName)){do_action('f2_ccVideo');}else{echo "&nbsp;";}?></td>
		  <td colspan="3" style="padding-bottom:4px;padding-top:4px;">
			<?php echo "<b>$strSelTags</b> ".tag_list("S")?>
		  </td>
		</tr>
		<?php include_once($editorpath);	//装载编辑器?>
		<?php if ($settingInfo['autoSave']=="1"){?>
		<tr>
		  <td colspan="4">
			<span id="timemsg"><?php echo $strAutoSaveDisabled?></span>&nbsp; &nbsp;<span id="timemsg2"></span><script type='text/javascript' src='js/autosaver_<?php echo $settingInfo['language']?>.js'></script> <script type='text/javascript' src='js/autosaver.js'></script>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="javascript: stopautosaver();"><?php echo $strAutoSaveStop?></a>] | [<a href="javascript: restartautosaver();"><?php echo $strAutoSaveStart?></a>] | [<a href="javascript: stopforever();"><?php echo $strAutoSaveDisable?></a>] | [<a href="javascript: handsave();"><?php echo $strAutoSaveHandSave?></a>]
			<input type='hidden' name='idforsave' id='idforsave' value='<?php echo $mark_id?>'/>
		  </td>
		</tr>
		<?php }?>
		<tr>
		  <td colspan="4">
			<iframe src="attach.php?editorcode=<?php echo $editorcode?>&mark_id=<?php echo $mark_id?>" frameborder="no" scrolling="no" width="100%" height="200"></iframe>
		  </td>
		</tr>
		<?php if ($mark_id=="") { ?>
		<tr>
		  <td nowrap>
			<?php echo $strQuoteUrl?>
		  </td>
		  <td colspan="3">
			<input name="quoteUrl" class="textbox" type="text" size="70" value="<?php echo !empty($quoteUrl)?$quoteUrl:""?>">
			&nbsp;<span style="color:#999">
			<?php echo $strQuoteAlt?>
			</span> </td>
		</tr>
		<?php } ?>
		<tr>
		  <td nowrap>
			<?php echo $strPostTime?>
		  </td>
		  <td colspan="3">
			<input name="pubTimeType" type="radio" value="now" <?php echo ($pubTimeType=="now" or $pubTimeType=="")?"checked":""?>/>
			<?php echo $strCurTime?>
			<input name="pubTimeType" type="radio" value="com" <?php echo ($pubTimeType=="com")?"checked":""?> />
			<?php echo $strDefTime?>
			:
			<input name="pubTime" type="text" value="<?php echo format_time("Y-m-d H:i",time())?>" size="21" class="textbox" onclick="pubTimeType(1).checked=true"/>
			&nbsp;<span style="color:#999">
			<?php echo $strTimeFormat?>
			</span> </td>
		</tr>
		<tr>
		  <td nowrap>
			<?php echo $strPareams?>
		  </td>
		  <td colspan="3">
		    <input type="radio" name="saveType" value="1" <?php echo ($saveType==1)?"checked=\"checked\"":""?>>  <?php echo $strPubLog?>
			<input type="radio" name="saveType" value="0" <?php echo ($saveType==0)?"checked=\"checked\"":""?>>  <?php echo $strDraft?>		    
		    <input type="radio" name="saveType" value="3" <?php echo ($saveType==3)?"checked=\"checked\"":""?>>  <?php echo $strHidLog?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="radio" name="isTop" value="1" <?php  if ($isTop=="1") { echo "checked"; }?>>
		    <?php echo $strLogTopOpen?>
		    <input type="radio" name="isTop" value="2" <?php  if ($isTop=="2") { echo "checked"; }?>>
		    <?php echo $strLogTopClose?>
		    <input type="radio" name="isTop" value="0" <?php  if ($isTop=="0") { echo "checked"; }?>>
		    <?php echo $strCTopTitle?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $strAutoSplitLogs?>
			<INPUT TYPE="text" NAME="autoSplit" class="textbox" size="5" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; " value="<?php echo ($autoSplit=="")?$settingInfo['autoSplit']:$autoSplit?>"> <?php echo $strSettingUnitsWords?>
		  </td>
		</tr>
		<tr>
		  <td nowrap>
			<?php echo $strGuestAccess?>
		  </td>
		  <td colspan="3">
			<input name="isComment" type="checkbox" value="1" <?php  if ($isComment=="1" or $isComment=="") { echo "checked"; }?>/>
			<?php echo $strAllowComment?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="isTrackback" type="checkbox" value="1" <?php  if ($isTrackback=="1" or $isTrackback=="") { echo "checked"; }?>/>
			<?php echo $strAllowTB?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $strLogPassword?>
			<INPUT TYPE="text" NAME="addpassword" size="35" maxlength="32" value="<?php echo $addpassword?>" class="textbox">
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="preview" class="btn" type="button" id="preview" value="<?php echo $strPreview?>" onClick="Javascript:onclick_update(this.form,'preview')">
	  &nbsp;
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form,'save')">
	  &nbsp;
	  <?php if ($edittype=="front"){$edit_url=($mark_id>0)?"../index.php?load=read&id=$mark_id":"../index.php";?>
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onClick="location.href='<?php echo "$edit_url"?>'">
	  <?php }else{?>
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onClick="location.href='<?php echo "$edit_url"?>'">
	  <?php }?>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=6;
$mtitle=$strEditorPlugins;

//输出头部信息
$title=$strEditorPluginSetting;
$action=$_GET['action'];

//保存
$err=0;
if ($action=="operation" and $_POST['operation']=="save"){
	$plugins=implode(",", $_POST['itemlist']);
	$sql="update ".$DBPrefix."setting set settValue='".encode($plugins)."' where settName='editPluginName'";
	$DMC->query($sql);
		
	//输出Cache
	settings_recache();
	$settingInfo['editPluginName']=$plugins;
	$ActionMessage=$strSaveSuccess;
	$action="";
}

if($action=="operation" and $_POST['operation']=="savebutton") {
	$sql="update ".$DBPrefix."setting set settValue='".encode($_POST['button1'])."' where settName='editPluginButton1'";
	$DMC->query($sql);
	$sql="update ".$DBPrefix."setting set settValue='".encode($_POST['button2'])."' where settName='editPluginButton2'";
	$DMC->query($sql);
	$sql="update ".$DBPrefix."setting set settValue='".encode($_POST['button3'])."' where settName='editPluginButton3'";
	$DMC->query($sql);
			
	//输出Cache
	settings_recache();	
	$settingInfo['editPluginButton1']=encode($_POST['button1']);
	$settingInfo['editPluginButton2']=encode($_POST['button2']);
	$settingInfo['editPluginButton3']=encode($_POST['button3']);

	$ActionMessage=$strSaveSuccess;
	$action="setbutton";
}

//列出editor plugins目录
if ($action=="") {
    $handle=opendir(F2BLOG_ROOT."./editor/plugins/"); 
    while ($file = readdir($handle)){ 
		if(is_dir(F2BLOG_ROOT."./editor/plugins/$file") && file_exists(F2BLOG_ROOT."./editor/plugins/$file/plugin.xml")){
			$dirlist[] = $file;
		}	
	} 
    closedir($handle); 
}

dohead($title,"");
require('admin_menu.php');
?>
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $title?></div>
	<br>
	<?php  if ($ActionMessage!="") { ?>
	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
	  <tr>
		<td>
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
		</td>
	  </tr>
	</table>
	<?php  } ?>
	<?php  if($action=="") { ?>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="5%" nowrap class="whitefont">
			<input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>">
		  </td>
		  <td width="10%" nowrap class="whitefont">
			<?php echo $strPluginName?>
		  </td>
		  <td width="8%" align="center" nowrap class="whitefont">
			<?php echo $strPluginVersion?>
		  </td>
		  <td width="43%" nowrap class="whitefont">
			<?php echo $strPluginDesc?>
		  </td>
		  <td width="11%" align="center" nowrap class="whitefont">
			<?php echo $strLogDate?>
		  </td>
		  <td width="16%" align="center" nowrap class="whitefont">
			<?php echo $strAuthor?>
		  </td>
		</tr>
		<?php 
		for ($i=0;$i<count($dirlist);$i++) {
			$arrPlugin=getEditorPluginInfo($dirlist[$i],"..");
			if(strpos(",".$settingInfo['editPluginName'],trim($arrPlugin['PluginName']))>0) {
				$checked=" checked";
			} else {
				$checked="";
			}
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="input">
			<INPUT type=checkbox value="<?php echo $arrPlugin['PluginName']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1" <?php echo $checked?>>
		  </td>
		  <td class="input"><a href="<?php echo $arrPlugin['AuthorURL']?>" target="_blank">
			<?php echo $arrPlugin['PluginName']?>
			</a></td>
		  <td align="center" class="input">
			<?php echo $arrPlugin['PluginVersion']?>
		  </td>
		  <td nowrap class="input">
			<?php echo $arrPlugin['FunctionDesc']?>
		  </td>
		  <td align="center" class="input">
			<?php echo $arrPlugin['pubDate']?>
		  </td>
		  <td align="center" class="input"><a href="mailto:<?php echo $arrPlugin['AuthorMail']?>">
			<?php echo $arrPlugin['PluginAuthor']?>
			</a></td>
		</tr>
		<?php }?>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn"></div>
	<div class="searchtool">
	  <input type="radio" name="operation" value="save" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strPluginSetting?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$PHP_SELF?action=operation"?>','<?php echo $strConfirmInfo?>')">
	  &nbsp;&nbsp;
	  <input name="setting" class="btn" type="button" value="<?php echo $strSettingButton?>" onclick="location.href='<?php echo "$PHP_SELF?action=setbutton"?>'">
	</div>
	<br>
	<?php  }
	if($action=="setbutton") {
	$defaultButton="bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,undo,redo,link,unlink,image,cleanup,help,code,hr,removeformat,sub,sup,formatselect,fontselect,fontsizeselect,forecolor,backcolor,anchor,charmap,separator,cut,copy,paste";
	$defaultDesc=$strDefBtnDesc;

	$pluginButton="";
	$buttonDesc="";
	$buttonImage="";
	$pluginDir="";
	$plugins=explode(",",$settingInfo['editPluginName']);
	for ($i=0;$i<count($plugins);$i++) {
		$arrPlugin=getEditorPluginInfo($plugins[$i],"..");
		if($arrPlugin['FunctionName']!="") {
			$kk=explode(",",$arrPlugin['FunctionName']);
			for($k=0;$k<count($kk);$k++) {
				$pluginDir.=",".$arrPlugin['PluginName'];
			}
			$pluginButton.=",".$arrPlugin['FunctionName'];
			$buttonDesc.=",".$arrPlugin['FunctionDesc'];
			$buttonImage.=",".$arrPlugin['FunctionImage'];
		}
	}
		
		#检查浏览器类型
		$browse=$_SERVER["HTTP_USER_AGENT"]; 
		if (strpos($browse, "MSIE")>0) {
			$browvr="IE";
			$actionHelp=$strActionIEhelp;
		} else {
			$browvr="firfox";
			$actionHelp=$strActionFXhelp;
		}
	?>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<TR class="subcontent-title">
		  <td>&nbsp;
			<?php echo $strDefFunction."&nbsp;&nbsp;&nbsp;(<font color=red>".$actionHelp."</font>)"?>
		  </td>
		</TR>
		<tr>
		  <td>
			<?php 
			$arrB=explode(",",$defaultButton);
			$arrD=explode(",",$defaultDesc);
			for($i=0;$i<count($arrB);$i++) {
				$name=$arrB[$i];
				$img=($name!="formatselect" and $name!="fontselect" and $name!="fontsizeselect")?"<img src='../editor/themes/advanced/images/".$name.".gif' align=\"absmiddle\">":"";

				if($browvr=="IE") {
					echo "$img<span style=\"cursor: pointer;\" onclick=\"javascript: CopyText('$name');\" title=\"$strCopyLink\">".$arrD[$i]."</span>";
				} else {
					echo "$img".$arrD[$i]."&nbsp;(<font color='#336699'>".$name."</font>)";
				}
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			}
	?>
		  </td>
		</tr>
		<TR class="subcontent-title">
		  <td>&nbsp;
			<?php echo $strPluginFunction."&nbsp;&nbsp;&nbsp;(<font color=red>".$actionHelp."</font>)"?>
		  </td>
		</TR>
		<tr>
		  <td height="30px">
		<?php 
		$arrY=explode(",",$pluginDir);
		$arrB=explode(",",$pluginButton);
		$arrD=explode(",",$buttonDesc);
		$arrI=explode(",",$buttonImage);
		for($i=0;$i<count($arrB);$i++) {
			$name=$arrB[$i];
			$img=($name!="")?"<img src='../editor/plugins/".$arrY[$i]."/images/".$arrI[$i]."' align=\"absmiddle\"> ":"";

			if($browvr=="IE") {
				echo "$img<span style=\"cursor: pointer;\" onclick=\"javascript: CopyText('$name');\" title=\"$strCopyLink\">".$arrD[$i]."</span>";
			} else {
				echo "$img".$arrD[$i]."&nbsp;(<font color='#336699'>".$name."</font>)";
			}
			echo "&nbsp;&nbsp;&nbsp;";
		}

	?>
		  </td>
		</tr>
		<TR class="subcontent-title">
		  <td>&nbsp;
			<?php echo $strRowButton1?>
		  </td>
		</TR>
		<tr>
		  <td>
			<textarea name="button1" cols="80" rows="3"><?php echo $settingInfo['editPluginButton1']?></textarea>
		  </td>
		</tr>
		<TR class="subcontent-title">
		  <td>&nbsp;
			<?php echo $strRowButton2?>
		  </td>
		</TR>
		<tr>
		  <td>
			<textarea name="button2" cols="80" rows="3"><?php echo $settingInfo['editPluginButton2']?></textarea>
		  </td>
		</tr>
		<TR class="subcontent-title">
		  <td>&nbsp;
			<?php echo $strRowButton3?>
		  </td>
		</TR>
		<tr>
		  <td>
			<textarea name="button3" cols="80" rows="3"><?php echo $settingInfo['editPluginButton3']?></textarea>
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input type="radio" name="operation" value="savebutton" onclick="Javascript:this.form.opmethod.value=1" checked>
	  <?php echo $strPluginSetting?>
	  |
	  <input name="opselect" type="hidden" value="">
	  <input name="opmethod" type="hidden" value="1">
	  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="confirm_submit('<?php echo $PHP_SELF?>?test=','operation')">
	  &nbsp;&nbsp;
	  <input name="return" class="btn" type="button" value="<?php echo $strEditorPlugins?>" onclick="location.href='<?php echo "$PHP_SELF?action="?>'">
	</div>
	<?php }?>

  </div>
</form>
<?php  dofoot(); ?>

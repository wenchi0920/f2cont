<?
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//输出头部信息
$title=$strEditorPluginSetting;
$action=$_GET['action'];

//保存
$err=0;
if ($action=="operation" and $_POST['operation']=="save"){
	$plugins=implode(",", $_POST['itemlist']);

	$configfile="admin_config.php";
	$fp = @fopen($configfile, 'r');
	$filecontent = @fread($fp, @filesize($configfile));
	@fclose($fp);

	$filecontent = preg_replace("/[$]editor_plugins\s*\=\s*[\"'].*?[\"']/is", "\$editor_plugins = \"$plugins\"", $filecontent);

	$fp = @fopen($configfile, 'w');
	@fwrite($fp, trim($filecontent));
	@fclose($fp);

	$ActionMessage=$strSaveSuccess;
	$action="";
	$editor_plugins=$plugins;
}

if($action=="operation" and $_POST['operation']=="savebutton") {
	$configfile="admin_config.php";
	$fp = @fopen($configfile, 'r');
	$filecontent = @fread($fp, @filesize($configfile));
	@fclose($fp);

	$filecontent = preg_replace("/[$]editor_button1\s*\=\s*[\"'].*?[\"']/is", "\$editor_button1 = '".$_POST['button1']."'", $filecontent);
	$filecontent = preg_replace("/[$]editor_button2\s*\=\s*[\"'].*?[\"']/is", "\$editor_button2 = '".$_POST['button2']."'", $filecontent);
	$filecontent = preg_replace("/[$]editor_button3\s*\=\s*[\"'].*?[\"']/is", "\$editor_button3 = '".$_POST['button3']."'", $filecontent);

	$fp = @fopen($configfile, 'w');
	@fwrite($fp, trim($filecontent));
	@fclose($fp);

	$ActionMessage=$strSaveSuccess;
	$action="setbutton";
	include("admin_config.php");
}

//列出editor plugins目录
if ($action=="") {
    $handle=opendir("../editor/plugins/"); 
    while ($file = readdir($handle)){ 
		if(is_dir("../editor/plugins/$file") && file_exists("../editor/plugins/$file/plugin.xml")){
			$dirlist[] = $file;
		}	
	} 
    closedir($handle); 
	//print_r($dirlist);
}

dohead($title,"");
?>
</script>

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
            <div class="contenttitle"><img src="images/content/small-editor.gif" width="12" height="11">
              <?=$title?>
            </div>
            <br>
            <? if ($ActionMessage!="") { ?>
            <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <td>
                  <fieldset>
                  <legend>
                  <?=$strErrorInfo?>
                  </legend>
                  <div align="center">
                    <table border="0" cellpadding="2" cellspacing="1">
                      <tr>
                        <td><span class="alertinfo">
                          <?=$ActionMessage?>
                          </span></td>
                      </tr>
                    </table>
                  </div>
                  </fieldset>
                  <br>
                </td>
              </tr>
            </table>
            <? } ?>
            <? if($action=="") { ?>
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="5%" nowrap class="whitefont">
                    <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                  </td>
                  <td width="10%" nowrap class="whitefont">
                    <?=$strPluginName?>
                  </td>
                  <td width="8%" align="center" nowrap class="whitefont">
                    <?=$strPluginVersion?>
                  </td>
                  <td width="43%" nowrap class="whitefont">
                    <?=$strPluginDesc?>
                  </td>
                  <td width="11%" align="center" nowrap class="whitefont">
                    <?=$strLogDate?>
                  </td>
                  <td width="16%" align="center" nowrap class="whitefont">
                    <?=$strAuthor?>
                  </td>
                </tr>
                <?
	for ($i=0;$i<count($dirlist);$i++) {
		$arrPlugin=getEditorPluginInfo($dirlist[$i],"..");
		if(strpos(",".$editor_plugins,trim($arrPlugin['PluginName']))>0) {
			$checked=" checked";
		} else {
			$checked="";
		}
	?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td nowrap class="input">
                    <INPUT type=checkbox value="<?=$arrPlugin['PluginName']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1" <?=$checked?>>
                  </td>
                  <td class="input"><a href="<?=$arrPlugin['AuthorURL']?>" target="_blank">
                    <?=$arrPlugin['PluginName']?>
                    </a></td>
                  <td align="center" class="input">
                    <?=$arrPlugin['PluginVersion']?>
                  </td>
                  <td nowrap class="input">
                    <?=$arrPlugin['FunctionDesc']?>
                  </td>
                  <td align="center" class="input">
                    <?=$arrPlugin['pubDate']?>
                  </td>
                  <td align="center" class="input"><a href="mailto:<?=$arrPlugin['AuthorMail']?>">
                    <?=$arrPlugin['PluginAuthor']?>
                    </a></td>
                </tr>
                <?}?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="save" onclick="Javascript:this.form.opmethod.value=1" checked>
              <?=$strPluginSetting?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="1">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$php_self?action=operation"?>','<?=$strConfirmInfo?>')">
              &nbsp;&nbsp;
              <input name="setting" class="btn" type="button" value="<?=$strSettingButton?>" onclick="location.href='<?="$php_self?action=setbutton"?>'">
            </div>
            <br>
            <? }
	if($action=="setbutton") {
	$defaultButton="bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,undo,redo,link,unlink,image,cleanup,help,code,hr,removeformat,sub,sup,formatselect,fontselect,fontsizeselect,forecolor,backcolor,anchor,charmap,separator,cut,copy,paste";
	$defaultDesc=$strDefBtnDesc;

	$pluginButton="";
	$buttonDesc="";
	$buttonImage="";
	$plugins=explode(",",$editor_plugins);
	for ($i=0;$i<count($plugins);$i++) {
		$arrPlugin=getEditorPluginInfo($plugins[$i],"..");
		if($arrPlugin['FunctionName']!="") {
			$kk=@explode(",",$arrPlugin['FunctionName']);
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
                    <?=$strDefFunction."&nbsp;&nbsp;&nbsp;(<font color=yellow>".$actionHelp."</font>)"?>
                  </td>
                </TR>
                <tr>
                  <td>
                    <?
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
                    <?=$strPluginFunction."&nbsp;&nbsp;&nbsp;(<font color=yellow>".$actionHelp."</font>)"?>
                  </td>
                </TR>
                <tr>
                  <td height="30px">
                <?
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
                    <?=$strRowButton1?>
                  </td>
                </TR>
                <tr>
                  <td>
                    <textarea name="button1" cols="80" rows="3"><?=$editor_button1?></textarea>
                  </td>
                </tr>
                <TR class="subcontent-title">
                  <td>&nbsp;
                    <?=$strRowButton2?>
                  </td>
                </TR>
                <tr>
                  <td>
                    <textarea name="button2" cols="80" rows="3"><?=$editor_button2?></textarea>
                  </td>
                </tr>
                <TR class="subcontent-title">
                  <td>&nbsp;
                    <?=$strRowButton3?>
                  </td>
                </TR>
                <tr>
                  <td>
                    <textarea name="button3" cols="80" rows="3"><?=$editor_button3?></textarea>
                  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input type="radio" name="operation" value="savebutton" onclick="Javascript:this.form.opmethod.value=1" checked>
              <?=$strPluginSetting?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="1">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="confirm_submit('<?=$php_self?>?test=','operation')">
              &nbsp;&nbsp;
              <input name="return" class="btn" type="button" value="<?=$strEditorPlugins?>" onclick="location.href='<?="$php_self?action="?>'">
            </div>
            <?
} ?>
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

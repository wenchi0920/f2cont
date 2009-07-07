<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "plugins_list.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
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
            <div class="contenttitle"><img src="images/content/plugin.gif" width="12" height="11">
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
            <div class="subcontent">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="13%" nowrap class="whitefont">
                    <?=$strPluginName?>
                  </td>
                  <td width="7%" nowrap class="whitefont">
                    <?=$strPluginVersion?>
                  </td>
                  <td width="60%" nowrap class="whitefont">
                    <?=$strPluginDesc?>
                  </td>
				  <td width="7%" nowrap class="whitefont">
                    <?=$strAuthor?>
                  </td>
                  <td width="7%" nowrap align="center" class="whitefont">
                    <?=$strPluginSettingEdit?>
                  </td>
                  <td width="8%" nowrap align="center" class="whitefont">
                    <?=$strPluginAction?>
                  </td>
                </tr>
                <?	foreach($plugins as $plugin_file => $plugin_data) { 
					$plugin=trim($plugin_data['Name']);
					$pfile=trim($plugin_data['Pfile']);

					if(strpos(",".$actPlugins, $plugin)>0) {
						$active="unActive";
						$class="subcontent-td2";
					} else {
						$active="active";
						$class="subcontent-td";
					}
				?>
                <tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td nowrap class="<?=$class?>">
                    <?=$plugin_data['Title']?>
                  </td>
                  <td nowrap class="<?=$class?>">
                    <?=$plugin_data['Version']?>
                  </td>
                  <td class="<?=$class?>">
                    <?=$plugin_data['Description']?>
                  </td>
				  <td nowrap class="<?=$class?>">
                    <?=$plugin_data['Author']?>
                  </td>
                  <td nowrap class="<?=$class?>" align="center">
                    <?=($plugin_data['Setting']!="" and $active=="unActive")?"<a href='$php_self?action=set&plugin=$plugin'><img src='images/content/icon_modif.gif' border='0' alt=\"$strPluginSettingEdit\"></a>":"&nbsp;"?>
				  </td>
                  <td nowrap class="<?=$class?>" align="center">
					<a href='<?="$php_self?action=save&operation=$active&plugin=$plugin&pfile=$pfile"?>'><?=($active=="active")?$strActive:$strUnActive?></a>
				  </td>
                </tr>
                <?}//end for?>
              </table>
            </div>
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

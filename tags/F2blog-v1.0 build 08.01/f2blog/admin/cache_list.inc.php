<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "cache_list.inc.php") {
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
            <div class="contenttitle"><img src="images/content/cache.gif" width="12" height="11">
              <?=$title?>
              <div class="page">
                <?view_page($page_url)?>
              </div>
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
                  <td width="5%" nowrap class="whitefont">
                    <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?=$strSelectCancelAll?>">
                  </td>
                  <td width="20%" nowrap class="whitefont">
                    <?=$strCacheName?>
                  </td>
                  <td width="25%" nowrap class="whitefont">
                    <?=$strCacheModifyTime?>
                  </td>
                  <td width="24%" nowrap class="whitefont">
                    <?=$strCacheSize?>
                  </td>
                </tr>
                <?
	for ($i=0;$i<count($cachedb);$i++) {
	?>
                <tr class="<?=($i%2==0)?"table_color1":"table_color2"?>" onMouseOver="this.style.backgroundColor='<?=$cfg_mouseover_color?>'" onMouseOut="this.style.backgroundColor=''">
                  <td nowrap class="subcontent-td">
                    <INPUT type=checkbox value="<?=$cachedb[$i]['name']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
                  </td>
                  <td nowrap class="subcontent-td">
                    <?=$cachedb[$i]['desc']?>
                  </td>
                  <td nowrap class="subcontent-td">
                    <?=$cachedb[$i]['mtime']?>
                  </td>
                  <td nowrap class="subcontent-td">
                    <?=$cachedb[$i]['size']?>
                  </td>
                </tr>
                <? }//end for?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn"></div>
            <div class="searchtool">
              <input type="radio" name="operation" value="update" onclick="Javascript:this.form.opmethod.value=1" checked>
              <?=$strUpdate?>
              |
              <input name="opselect" type="hidden" value="">
              <input name="opmethod" type="hidden" value="1">
              <input name="op" class="btn" type="button" value="<?=$strConfirm?>" onclick="ConfirmOperation('<?="$edit_url&action=operation"?>','<?=$strConfirmInfo?>')">
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

<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "categories_order.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
?>
<script style="javascript">
<!--
function onclick_update(form) {
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$edit_url&action=saveorder"?>";
	form.submit();
}
-->
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
            <div class="contenttitle"><img src="images/content/links.gif" width="12" height="11">
              <?=$title?>
            </div>
            <br>
            <div class="subcontent">
              <? if ($ActionMessage!="") { ?>
              <br>
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
              <? } ?>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-input">
                  <td width="10%" align="center">
                    <?=$strLinksOrderNo?>
                  </td>
                  <td width="30%">
                    <?=$strLinksName?>
                  </td>
                  <td width="30%">
                    <?=$strLinksLinkUrl?>
                  </td>
                </tr>
                <?for ($i=0;$i<count($arr_parent);$i++){?>
                <tr>
                  <td width="10%" align="center">
                    <input type="hidden" name="arrid[]" value="<?=$arr_parent[$i]['id']?>">
                    <input name="orderNo[]" id="orderNo" class="textbox" type="TEXT" size=2 maxlength=5 value="<?=$arr_parent[$i]['orderNo']?>" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; ">
                  </td>
                  <td width="30%">
                    <?=$arr_parent[$i]['name']?>
                  </td>
                  <td width="30%"><a href="<?=$arr_parent[$i]['blogUrl']?>" target="_blank">
                    <?=$arr_parent[$i]['blogUrl']?>
                    </a></td>
                </tr>
                <?}?>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strSave?>" onClick="Javascript:onclick_update(this.form)">
              &nbsp;
              <input name="reback" class="btn" type="button" id="return" value="<?=$strReturn?>" onclick="location.href='<?="$edit_url"?>'">
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

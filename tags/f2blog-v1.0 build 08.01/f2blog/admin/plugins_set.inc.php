<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "plugins_set.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
?>

<script style="javascript">
<!--
function onclick_update(form) {
	<? for ($y=0;$y<count($fieldCheck);$y++) { ?>
		if (isNull(form.<?=$fieldCheck[$y]?>, '<?=$strErrNull?>')) return false;
	<?}?>

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$PHP_SELF?plugin=$plugin&action=setSave"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
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
			<?=$setCode?>
            </div>
            <br>
            <div class="bottombar-onebtn">
			<? if($arr!="") { ?>
              <input name="save" class="btn" type="button" id="save" value="<?=$strSave?>" onClick="Javascript:onclick_update(this.form)">
              &nbsp;
			<? } ?>
              <input name="reback" class="btn" type="button" id="return" value="<?=$strReturn?>" onclick="location.href='<?=$PHP_SELF?>'">
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

<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "users_add.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//输出头部信息
dohead($title,"");
?>
<script style="javascript">
<!--
function onclick_update(form) {	
	<?
	if ($action=="add"){
		echo "if (isNull(form.addusername, '".$strErrNull."')) return false \n";
		echo "if (isNull(form.addpassword, '".$strErrNull."')) return false; \n";
		echo "if (isNull(form.password_con, '".$strErrNull."')) return false; \n";
	}
	?>

	if (form.addpassword.value!=form.password_con.value){
		alert('<?=$strErrPassword?>');
		form.addpassword.focus();
		return false;
	}

	if (isNull(form.nickname, '<?=$strErrNull?>')) return false;
	if (isNull(form.email, '<?=$strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$edit_url&mark_id=$mark_id&action=save"?>";
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
            <div class="contenttitle"><img src="images/content/user.gif" width="12" height="11">
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
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strLoginUserID?>
                  </td>
                  <td width="90%">
                    <? if ($mark_id=="") { ?>
                    <input name="addusername" class="textbox" type="text" size="30" maxlength="20" value="<?=$addusername?>">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strLoginUserID.$strUserAlert?>
                    </span>
                    <? } else {
					echo "<b>$addusername</b>";
				   }
				?>
                  </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strLoginPassword?>
                  </td>
                  <td width="90%">
                    <input name="addpassword" class="textbox" type="password" size="30" maxlength="20" value="">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strLoginPassword.$strUserAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strConfigPassword?>
                  </td>
                  <td width="90%">
                    <input name="password_con" class="textbox" type="password" size="30" maxlength="20" value="">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strPassAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strNickName?>
                  </td>
                  <td width="90%">
                    <input name="nickname" class="textbox" type="text" size="30" maxlength="20" value="<?=$nickname?>">
                    &nbsp;&nbsp;<span style="color:#CC0033">
                    <?=$strNickName.$strNikeAlert?>
                    </span> </td>
                </tr>
                <tr>
                  <td width="10%" nowrap class="input-titleblue">
                    <?=$strEmail?>
                  </td>
                  <td width="90%">
                    <input name="email" class="textbox" type="TEXT" size="30" maxlength=100 value="<?=$email?>">
                    <input name="isHiddenEmail" type="checkbox" value="1" <? if ($isHiddenEmail=="1") { echo "checked"; }?>/>
                    <?=$strHiddenEmail?>
                  </td>
                </tr>
                <tr>
                  <td width="10%" nowrap>
                    <?=$strUserBlog?>
                  </td>
                  <td width="90%">
                    <input name="homePage" class="textbox" type="TEXT" size="30" maxlength=100 value="<?=$homePage?>">
                  </td>
                </tr>
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

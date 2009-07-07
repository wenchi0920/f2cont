<?
set_time_limit(0);

$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];

//需要操作的表名：
$arrTableName=array("logs","categories","comments","dailystatistics","guestbook","setting","keywords","links","members","modsetting","modules","trackbacks","filters","attachments","visits","tags");

//输出头部信息
dohead($strDataToolsTitle,"");

?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.backup, '<?=$strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?="$PHP_SELF?action=save"?>";
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
            <div class="contenttitle"><img src="images/content/optimize.gif" width="12" height="11">
              <?=$strDataToolsTitle?>
            </div>
            <div class="subcontent">
			  <?
			  if ($action=="save"){
				  for ($i=0;$i<count($_POST['operator']);$i++){
			  ?>
				  <br>
				  <fieldset>
				  <legend>
				  <?=$_POST['operator'][$i]." Table"?>&nbsp;
				  </legend>
				  <div>
					<table border="0" cellpadding="2" cellspacing="1">
						<?
					    $rows=0;
						for ($j=0;$j<count($arrTableName);$j++){
							$tablename=$DBPrefix.$arrTableName[$j];
							$sql=$_POST['operator'][$i]." Table $tablename";
							$DMC->query($sql);
							
							if ($rows==0){echo "<tr>";}
							if ($rows==3){echo "</tr>";$rows=0;}
							echo "<td width=\"25%\">$tablename ... OK</td>";
							$rows++;
						}
						?>
					</table>
				  </div>
				  </fieldset>
				  <br>		
			  <?
					}
				}
				
				if ($action==""){
			  ?>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="checkbox" name="operator[]" value="CHECK" checked/>
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataCheckTable?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="checkbox" name="operator[]" value="REPAIR" checked />
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataRepairTable?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="checkbox" name="operator[]" value="ANALYZE" checked />
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataAnalyzeTable?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="checkbox" name="operator[]" value="OPTIMIZE" checked />
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataOptimizeTable?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="checkbox" name="operator[]" value="FLUSH" checked />
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataFlushTable?>
                  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strDataToolsBegin?>" onclick="ConfirmDataOperation('<?="$PHP_SELF?action=save"?>','<?=$strDataToolsConfirm?>');">
			  <input name="del" class="btn" type="hidden" id="del">
            </div>
			<?}?>
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

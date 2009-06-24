<?php 
@set_time_limit(0);
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strOptimize;

//保存参数
$action=$_GET['action'];

//需要操作的表名：
$arrTableName=array("logs","categories","comments","dailystatistics","guestbook","setting","keywords","links","linkgroup","members","modsetting","modules","trackbacks","filters","attachments","tags");

//输出头部信息
dohead($strDataToolsTitle,"");
require('admin_menu.php');
?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.backup, '<?php echo $strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $strDataToolsTitle?></div>
	<div class="subcontent">
	  <?php 
	  if ($action=="save"){
		  for ($i=0;$i<count($_POST['operator']);$i++){
	  ?>
		  <br>
		  <fieldset>
		  <legend>
		  <?php echo $_POST['operator'][$i]." Table"?>&nbsp;
		  </legend>
		  <div>
			<table border="0" cellpadding="2" cellspacing="1">
				<?php 
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
	  <?php 
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
			<?php echo $strDataCheckTable?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" align="right" class="subcontent-td">
			<input type="checkbox" name="operator[]" value="REPAIR" checked />
		  </td>
		  <td width="90%" class="subcontent-td">
			<?php echo $strDataRepairTable?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" align="right" class="subcontent-td">
			<input type="checkbox" name="operator[]" value="ANALYZE" checked />
		  </td>
		  <td width="90%" class="subcontent-td">
			<?php echo $strDataAnalyzeTable?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td width="10%" align="right" class="subcontent-td">
			<input type="checkbox" name="operator[]" value="OPTIMIZE" checked />
		  </td>
		  <td width="90%" class="subcontent-td">
			<?php echo $strDataOptimizeTable?>
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strDataToolsBegin?>" onclick="ConfirmDataOperation('<?php echo "$PHP_SELF?action=save"?>','<?php echo $strDataToolsConfirm?>');">
	  <input name="del" class="btn" type="hidden" id="del">
	</div>
	<?php }?>

  </div>
</form>
<?php  dofoot(); ?>

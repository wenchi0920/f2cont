<?php 
require_once("function.php");

// 验证用户是否处于登陆状态
check_login();
$parentM=4;
$mtitle=$strLogsEditAuthor;

$title="$strUserTitleEdit";

//保存参数
$action=$_GET['action'];

//保存数据
if ($action=="save"){
	$check_info=1;
	if (count($_POST[chkAuthor])<1){
		$ActionMessage=$strLogsEditAuthorCheck;
		$check_info=0;
	}

	if ($check_info==1 && $_POST[newauthor]==""){
		$ActionMessage=$strlogsEditAuthorError;
		$check_info=0;
	}
	
	if ($check_info==1 && !$DMC->fetchArray($DMC->query("select * from ".$DBPrefix."members where username='".encode($_POST['newauthor'])."' and role!='member'"))){
		$ActionMessage=$strLogsEditAuthorNo;
		$check_info=0;
	}

	if ($check_info==1){
		$authorsql="";
		foreach($_POST[chkAuthor] as $value){
			if ($authorsql==""){
				$authorsql="author='$value'";
			}else{
				$authorsql=" or author='$value'";
			}
		}

		$sql="update ".$DBPrefix."logs set author='".encode($_POST['newauthor'])."' where $authorsql";
		$DMC->query($sql);
		$update_rows=$DMC->affectedRows();
		
		$ActionMessage="$strLogsEditAuthorResult".$update_rows;
	}
}

//取得作者列表
$dataInfo = $DMC->fetchQueryAll($DMC->query("select author from ".$DBPrefix."logs group by author"));

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	if (strlen(form.newauthor.value)==0){
		alert('<?php echo $strlogsEditAuthorError?>');
		form.newauthor.focus();
		return false;
	}

	form.save.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $strLogsEditAuthor?></div>
	<br />
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
	  <br />
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
	  <br />
	  <?php  } ?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strLogsEditOldAuthor?>
		  </td>
		  <td width="90%">
			<?php foreach($dataInfo as $fa){?>
				<label name="<?php echo $fa['author']?>">
				<input name="chkAuthor[]" value="<?php echo $fa['author']?>" type="checkbox"> <?php echo $fa['author']?>
				</label>&nbsp;&nbsp;
			<?php }?>
		  </td>
		</tr>
		<tr>
		  <td width="10%" nowrap class="input-titleblue">
			<?php echo $strLogsEditNewAuthor?>
		  </td>
		  <td width="90%">
			<input name="newauthor" class="textbox" type="text" size="30" maxlength="20" value="<?php echo $_POST[newauthor]?>">
		  </td>
		</tr>
	  </table>
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
	</div>

  </div>
</form>
<?php  dofoot(); ?>

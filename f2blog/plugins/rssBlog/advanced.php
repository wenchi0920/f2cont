<?php
error_reporting(E_ERROR & ~E_NOTICE);

$rootPath=substr(dirname(__FILE__), 0, -15);
$curPath=$rootPath."./plugins/rssBlog/";
include_once($rootPath."./admin/function.php");

check_login(); //检查是否登录
$curStyle=$settingInfo['adminstyle'];
$action=$_GET['action'];
$page=$_GET['page'];
$mark_id=$_GET['mark_id'];

$seekname=encode($_REQUEST['seekname']);

$page_url="$PHP_SELF?seekname=$seekname";	//页面导航链接
$edit_url="$PHP_SELF?page=$page&seekname=$seekname";	//编辑或新增链接

//保存数据
if ($action=="save"){
	$curTime=time();
	if ($mark_id!=""){//编辑
		if ($_POST['blogTitle']!=""){
			$nickrsexits=getFieldValue($DBPrefix."rssBlog","blogTitle='".encode($_POST['blogTitle'])."'","id");
			$check_info=($nickrsexits!="" && $nickrsexits!=$mark_id)?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此博客已存在!";
		}else{
			$sql="update {$DBPrefix}rssBlog set blogTitle='".encode($_POST['blogTitle'])."',blogUrl='".encode($_POST['blogUrl'])."',rssUrl='".encode($_POST['rssUrl'])."',viewLimit='{$_POST['viewLimit']}',rssStatus='{$_POST['rssStatus']}',redate='$curTime' where id='$mark_id'";
			$DMC->query($sql);
		}
	}else{//新增
		if ($_POST['blogTitle']!=""){
			$nickrsexits=getFieldValue($DBPrefix."rssBlog","blogTitle='".encode($_POST['blogTitle'])."'","id");
			$check_info=($nickrsexits!="")?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此博客已存在!";
		}else{
			$sql="INSERT INTO {$DBPrefix}rssBlog(blogTitle,blogUrl,rssUrl,viewLimit,rssStatus,redate) VALUES ('".encode($_POST['blogTitle'])."','".encode($_POST['blogUrl'])."','".encode($_POST['rssUrl'])."','{$_POST['viewLimit']}','{$_POST['rssStatus']}','$curTime')";
			$DMC->query($sql);
		}
	}

	if ($check_info==0) {
		$action=($mark_id!="")?"edit":"add";
	} else {
		$action="";
	}
}

//状态保存排序
if ($action=="saveorder") {
	for ($i=0;$i<count($_POST['arrid']);$i++){
		$sql="update {$DBPrefix}rssBlog set orderNo='".($i+1)."' where id='".$_POST['arrid'][$i]."'";
		$DMC->query($sql);
	}

	$action="order";
}

//其它操作行为：删除，增加／移除到状态
if ($action=="operation"){
	$stritem="";
	$itemlist=$_POST['itemlist'];
	for ($i=0;$i<count($itemlist);$i++){
		if ($stritem!=""){
			$stritem.=" or id='$itemlist[$i]'";
		}else{
			$stritem.="id='$itemlist[$i]'";
		}
	}
	
	if($_POST['operation']=="delete" and $stritem!=""){
		$sql="delete from {$DBPrefix}rssBlog where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="openrss" and $stritem!=""){
		$sql="update {$DBPrefix}rssBlog set rssStatus='1' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="closerss" and $stritem!=""){
		$sql="update {$DBPrefix}rssBlog set rssStatus='0' where $stritem";
		$DMC->query($sql);
	}

	$action="";
}

if ($action=="") {
	$seekname="";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo "../../admin/themes/".$curStyle."/style.css"?>" type="text/css" />
<script type="text/javascript" src="../../admin/js/lib.js"></script>
</head>
<body style="background: #FFFFFF;">

<form action="" method="post" name="seekform">
	<?php
		$class1=($action=="" or $action=="find" or $action=="edit")?"color:#cc0000":"";
		$class2=($action=="add")?"color:#cc0000":"";
		$class3=($action=="order")?"color:#cc0000":"";
	?>

	<div style="color: #6c6c6c; font-size: 11pt; font-weight:bole; margin-top: 8px; padding: 2px; border: solid 1px #c6c6c6;">&nbsp;
		<a href="<?php echo $page_url?>&action=" style="<?php echo $class1?>">RSS地址管理</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=add" style="<?php echo $class2?>">增加RSS地址</a>&nbsp;|&nbsp;
		<a href="<?php echo $page_url?>&action=order" style="<?php echo $class3?>">RSS地址排序</a>
	</div>

	<!--显示RSS地址管理-->
	<?php if ($action=="" or $action=="find") {
		$find="";
		if ($seekname!=""){$find.=" and (blogTitle like '%$seekname%' or blogUrl like '%$seekname%')";}
		if ($find!="") {
			$find=" where ".substr($find,5);
		}

		$sql="select * from {$DBPrefix}rssBlog $find order by id desc";
		$nums_sql="select count(id) as numRows from {$DBPrefix}rssBlog $find";
		$total_num=getNumRows($nums_sql);
	?>
		<div class="searchtool">
		  <?php echo $strBlueFind?>
		  &nbsp;
		  <input type="text" name="seekname" size="5" value="<?php echo $seekname?>">
		  &nbsp;
		  <input name="find" class="btn" type="submit" value="<?php echo $strFind?>" onclick="confirm_submit('<?php echo $page_url?>','find')">
		  &nbsp;
		  <input name="findall" class="btn" type="button" value="<?php echo $strAll?>" onclick="confirm_submit('<?php echo $page_url?>','')">
		  &nbsp;|&nbsp;
		  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1" checked>
		  <?php echo $strDelete?>
		  |
		  <input type="radio" name="operation" value="openrss" onclick="Javascript:this.form.opmethod.value=1">
		  开启RSS地址联播
		  <input type="radio" name="operation" value="closerss" onclick="Javascript:this.form.opmethod.value=1">
		  关闭RSS地址联播
		  <input name="opselect" type="hidden" value="">
		  <input name="opmethod" type="hidden" value="1">
		  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">

		  <div class="page">
			<?php view_page($page_url)?>
		  </div>
		</div>

		<div class="subcontent">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="10%" nowrap class="whitefont">
			  <input type="checkbox" name="checkbox" value="all" onclick="checkall(this.form,this.checked,'itemlist[]')" title="<?php echo $strSelectCancelAll?>"><?php echo $strSelectCancelAll?>
			  </td>
			  <td width="22%" nowrap class="whitefont">博客名称</td>
			  <td width="35%" nowrap class="whitefont">RSS地址</td>
			  <td width="8%" nowrap class="whitefont" align="center">显示数量</td>
			  <td width="10%" nowrap class="whitefont" align="center">状态</td>
			  <td width="15%" nowrap class="whitefont">增加时间</td>
			</tr>
			<?php
			if ($page<1) { $page=1; }
			$start_record=($page-1)*$settingInfo['adminPageSize'];

			$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
			$query_result=$DMC->query($query_sql);
			while($fa = $DMC->fetchArray($query_result)){
				$index++;

				$existsPL=($fa['rssStatus']=="1")?"<font color=blue>开启</font>":"关闭";
			?>
			<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			  <td nowrap class="subcontent-td" align="center">
				<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">&nbsp;
				<a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="../../admin/themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" alt="<?php echo "$strEdit"?>" border="0" /></a>
			  </td>
			  <td nowrap class="subcontent-td"><a href="<?php echo $fa['blogUrl']?>" target="_blank"><?php echo $fa['blogTitle']?></a></td>
			  <td nowrap class="subcontent-td"><?php echo $fa['rssUrl']?></td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $fa['viewLimit']?></td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $existsPL?></td>
			  <td nowrap class="subcontent-td"><?php echo format_time("L",$fa['redate'])?></td>
			</tr>
			<?php }?>
		  </table>
		</div>

	<?php } ?>
	<!--结束显示RSS地址管理-->

	
	<!--增加／编辑RSS地址-->
	<?php if ($action=="add" or ($action=="edit" && is_numeric($mark_id))) { 
		if ($action=="add") {
			$blogTitle=$_POST['blogTitle'];
			$blogUrl=$_POST['blogUrl'];
			$rssUrl=$_POST['rssUrl'];
			$viewLimit=($_POST['viewLimit']=="" or $_POST['viewLimit']<=0)?5:$_POST['viewLimit'];
			$rssStatus=$_POST['rssStatus'];
		} else {
			$dataInfo = $DMC->fetchArray($DMC->query("select * from {$DBPrefix}rssBlog where id='$mark_id'"));
			if ($dataInfo) {
				$blogTitle=$dataInfo['blogTitle'];
				$blogUrl=$dataInfo['blogUrl'];
				$rssUrl=$dataInfo['rssUrl'];
				$viewLimit=$dataInfo['viewLimit'];
				$rssStatus=$dataInfo['rssStatus'];
			}
		}
	?>

		<script type="text/javascript">
		<!--
		function onclick_update(form) {	
			if (form.blogTitle.value==""){
				alert('博客名称不能为空');
				form.blogTitle.focus();
				return false;
			}

			if (form.blogUrl.value==""){
				alert('博客网址不能为空');
				form.blogUrl.focus();
				return false;
			}

			if (form.rssUrl.value==""){
				alert('RSS地址网址不能为空');
				form.rssUrl.focus();
				return false;
			}
			
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=save"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php if ($ActionMessage!="") { ?>
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
		  <?php } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">博客名称</td>
				  <td>
					<input name="blogTitle" type="text" class="textbox" size="20" value="<?php echo $blogTitle?>" maxlength="20">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">博客网称</td>
				  <td>
					<input name="blogUrl" type="text" class="textbox" size="50" value="<?php echo $blogUrl?>" maxlength="200">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">RSS网称</td>
				  <td>
					<input name="rssUrl" type="text" class="textbox" size="50" value="<?php echo $rssUrl?>" maxlength="200">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td>显示数量</td>
				  <td>
					<input name="viewLimit" type="text" class="textbox" size="20" value="<?php echo $viewLimit?>" maxlength="2">
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				  <td>联播状态</td>
				  <td>
					<input name="rssStatus" type="radio" value="1" <? if ($rssStatus=="1" or $rssStatus=="") { echo "checked"; }?>>开启
					<input name="rssStatus" type="radio" value="0" <? if ($rssStatus=="0") { echo "checked"; }?>>关闭
				  </td>
			   </tr>
		  </table>
		</div>
		<br />
		<div>
		  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onclick="Javascript:onclick_update(this.form)"/>
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'"/>
		</div>
	<?php } ?>
	<!--结束增加／编辑RSS地址-->

	<!--RSS地址排序-->
	<?php if ($action=="order") { 
		$sql="select blogTitle,blogUrl,rssUrl,id from {$DBPrefix}rssBlog where rssStatus='1' order by orderNo";
		$result=$DMC->query($sql);
	?>
		<script style="javascript">
		<!--
		function onclick_update(form) {
			form.save.disabled = true;
			form.reback.disabled = true;
			form.action = "<?php echo "$edit_url&action=saveorder"?>";
			form.submit();
		}
		-->
		</script>

		<div class="subcontent">
		  <?php if ($ActionMessage!="") { ?>
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
		  <?php } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="20%">&nbsp;博客名称</td>
			  <td width="80%">RSS地址</td>
			</tr>
		  </table>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="tbCondition">
			<?php while($fa = $DMC->fetchArray($result)){ ?>
			<tr onClick="SelectRow(event)">
			  <td class="subcontent-td" width="20%">
				<input type="hidden" name="arrid[]" value="<?php echo $fa['id']?>">
				<a href="<?php echo $fa['blogUrl']?>" target="_blank"><?php echo $fa['blogTitle']?></a>
			  </td>
			  <td class="subcontent-td" width="80%"><?php echo $fa['rssUrl']?></td>
			</tr>
			<?php }?>
		  </table>
		</div>

		<br>
		<div class="searchbox">
		  <input name="moveup" class="btn" type="button" id="moveup" value="<?php echo $strMoveUp?>" onClick="Move(-1,2)" disabled>
		  &nbsp;
		  <input name="movedown" class="btn" type="button" id="movedown" value="<?php echo $strMoveDown?>" onClick="Move(1,2)" disabled>
		  &nbsp;<font color=red>(<?php echo $strMoveDemo?>)</font>&nbsp;&nbsp;
		  <input name="save" class="btn" type="button" id="save" value="<?php echo $strSave?>" onClick="Javascript:onclick_update(this.form)">
		  &nbsp;
		  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='<?php echo "$edit_url"?>'">
		</div>
	  </div>
	<? } ?>
	<!--结束RSS地址排序-->

</form>

<script type="text/javascript">
parent.document.all("advPlugin").style.height=document.body.scrollHeight;
</script>

</body>
</html>
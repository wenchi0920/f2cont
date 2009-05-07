<?php
error_reporting(E_ERROR & ~E_NOTICE);

$rootPath=substr(dirname(__FILE__), 0, -12);
$curPath=$rootPath."./plugins/vote/";
include_once($rootPath."./admin/function.php");

check_login(); //检查是否登录
$curStyle=$settingInfo['adminstyle'];
$action=$_GET['action'];
$page=$_GET['page'];
$mark_id=$_GET['mark_id'];

$page_url="$PHP_SELF?order=id";	//页面导航链接
$edit_url="$PHP_SELF?page=$page";	//编辑或新增链接

//保存数据
if ($action=="save"){
	$curTime=time();
	if ($mark_id!=""){//编辑
		//检测投票议题不重复
		if ($_POST['voteco']!=""){
			$nickrsexits=getFieldValue($DBPrefix."vote","voteco='".encode($_POST['voteco'])."'","id");
			$check_info=($nickrsexits!="" && $nickrsexits!=$mark_id)?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此投票议题已存在!";
		}else{
			$sql="update {$DBPrefix}vote set voteco='".encode($_POST['voteco'])."',cs_1='".encode($_POST['cs_1'])."',cs_2='".encode($_POST['cs_2'])."',cs_3='".encode($_POST['cs_3'])."',cs_4='".encode($_POST['cs_4'])."',cs_5='".encode($_POST['cs_5'])."',oorc='".encode($_POST['oorc'])."',bg_color='".encode($_POST['bg_color'])."',word_color='".encode($_POST['word_color'])."',word_size='".encode($_POST['word_size'])."',vote_time='$curTime',sorm='{$_POST['sorm']}' where id='$mark_id'";
			$DMC->query($sql);
		}
	}else{//新增
		if ($_POST['voteco']!=""){
			$nickrsexits=getFieldValue($DBPrefix."vote","voteco='".encode($_POST['voteco'])."'","id");
			$check_info=($nickrsexits!="")?0:1;
		}
		
		if ($check_info==0){
			$ActionMessage="此投票议题已存在!";
		}else{
			$sql="INSERT INTO {$DBPrefix}vote(voteco,cs_1,cs_2,cs_3,cs_4,cs_5,oorc,bg_color,word_color,word_size,vote_time,sorm) VALUES ('".encode($_POST['voteco'])."','".encode($_POST['cs_1'])."','".encode($_POST['cs_2'])."','".encode($_POST['cs_3'])."','".encode($_POST['cs_4'])."','".encode($_POST['cs_5'])."','".encode($_POST['oorc'])."','".encode($_POST['bg_color'])."','".encode($_POST['word_color'])."','".encode($_POST['word_size'])."','$curTime','{$_POST['sorm']}')";
			$DMC->query($sql);
		}
	}

	if ($check_info==0) {
		$action=($mark_id!="")?"edit":"add";
	} else {
		$action="";
	}
}

//其它操作行为：删除，开关投票，单／多选
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
		$sql="delete from {$DBPrefix}vote where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="vopen" and $stritem!=""){
		$sql="update {$DBPrefix}vote set oorc='True' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="vclose" and $stritem!=""){
		$sql="update {$DBPrefix}vote set oorc='False' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="single" and $stritem!=""){
		$sql="update {$DBPrefix}vote set sorm='False' where $stritem";
		$DMC->query($sql);
	}

	if($_POST['operation']=="mulite" and $stritem!=""){
		$sql="update {$DBPrefix}vote set sorm='True' where $stritem";
		$DMC->query($sql);
	}

	$action="";
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

	<!--显示管理投票-->
	<? if ($action=="") { 
		$sql="select * from ".$DBPrefix."vote order by id desc";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."vote";
		$total_num=getNumRows($nums_sql);
	?>
		<div class="searchtool">
		  <input type="radio" name="operation" value="delete" onclick="Javascript:this.form.opmethod.value=1" checked>
		  <?php echo $strDelete?>
		  |
		  <input type="radio" name="operation" value="vopen" onclick="Javascript:this.form.opmethod.value=1">
		  开启投票
		  <input type="radio" name="operation" value="vclose" onclick="Javascript:this.form.opmethod.value=1">
		  关闭投票
		  |
		   投票类型:<input type="radio" name="operation" value="single" onclick="Javascript:this.form.opmethod.value=1">
		  单选
		  <input type="radio" name="operation" value="mulite" onclick="Javascript:this.form.opmethod.value=1">
		  多选
		  <input name="opselect" type="hidden" value="">
		  <input name="opmethod" type="hidden" value="1">
		  <input name="op" class="btn" type="button" value="<?php echo $strConfirm?>" onclick="ConfirmOperation('<?php echo "$edit_url&action=operation"?>','<?php echo $strConfirmInfo?>')">

		  &nbsp;
		  <input name="findall" class="btn" type="button" value="管理投票" onclick="confirm_submit('<?php echo $page_url?>','')">
		  &nbsp;
		  <input name="add" class="btn" type="button" value="<?php echo $strAdd?>" onclick="confirm_submit('<?php echo $page_url?>','add')">
		  <div class="page">
			<?view_page($page_url)?>
		  </div>
		</div>

		<div class="subcontent">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td width="5%" nowrap class="whitefont">&nbsp;</td>
			  <td width="3%" nowrap class="whitefont" align="center">ID</td>
			  <td width="55%" nowrap class="whitefont">投票议题</td>
			  <td width="6%" nowrap class="whitefont">类型</td>
			  <td width="9%" nowrap class="whitefont" align="center">选项条目</td>
			  <td width="12%" nowrap class="whitefont">发起时间</td>
			  <td width="7%" nowrap class="whitefont" align="center">投票总数</td>
			  <td width="3%" nowrap class="whitefont"><?php echo $strStatus?></td>
			</tr>
			<?
			$index=0;
			if ($page<1) { $page=1; }
			$start_record=($page-1)*$settingInfo['adminPageSize'];

			$query_sql=$sql." Limit $start_record,{$settingInfo['adminPageSize']}";
			$query_result=$DMC->query($query_sql);
			while($fa = $DMC->fetchArray($query_result)){
				$index++;

				$voteCount=0;
				$voteOptionCount=0;
				for ($j=1;$j<6;$j++){
					if ($fa["cs_".$j]!="") { 
						$voteCount=$voteCount+$fa["cs_".$j."_num"];
						$voteOptionCount=$j;
					}
				}

				$oorc=($fa['oorc']=="True")?"开启":"关闭";
				$sorm=($fa['sorm']=="False")?"单选":"多选";
			?>
			<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
			  <td nowrap class="subcontent-td" align="center">
				<INPUT type=checkbox value="<?php echo $fa['id']?>" id="item" name="itemlist[]"  onclick="Javascript:this.form.opselect.value=1">
				<a href="<?php echo "$edit_url&mark_id=".$fa['id']."&action=edit"?>"><img src="../../admin/themes/<?php echo $settingInfo['adminstyle']?>/icon_modif.gif" title="<?php echo "$strEdit"?>" alt="<?php echo "$strEdit"?>" border="0"></a>
			  </td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $fa['id']?></td>
			  <td nowrap class="subcontent-td"><?php echo $fa['voteco']?></td>
			  <td nowrap class="subcontent-td"><?php echo $oorc?></td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $voteOptionCount?></td>
			  <td nowrap class="subcontent-td"><?php echo format_time("L",$fa['vote_time'])?></td>
			  <td nowrap class="subcontent-td" align="center"><?php echo $voteCount?></td>
			  <td nowrap class="subcontent-td"><?php echo $sorm?></td>
			</tr>
			<?php }?>
		  </table>
		</div>

	<? } ?>
	<!--结束显示管理投票-->

	
	<!--增加／编辑投票-->
	<? if ($action=="add" or ($action=="edit" && is_numeric($mark_id))) { 
		if ($action=="add") {
			$sorm=$_POST['sorm'];
			$voteco=$_POST['voteco'];
			$cs_1=$_POST['cs_1'];
			$cs_2=$_POST['cs_2'];
			$cs_3=$_POST['cs_3'];
			$cs_4=$_POST['cs_4'];
			$cs_5=$_POST['cs_5'];
			$bg_color=$_POST['bg_color'];
			$word_color=$_POST['word_color'];
			$word_size=$_POST['word_size'];
			$oorc=$_POST['oorc'];
		} else {
			$dataInfo = $DMC->fetchArray($DMC->query("select * from {$DBPrefix}vote where id='$mark_id'"));
			if ($dataInfo) {
				$sorm=$dataInfo['sorm'];
				$voteco=$dataInfo['voteco'];
				$cs_1=$dataInfo['cs_1'];
				$cs_2=$dataInfo['cs_2'];
				$cs_3=$dataInfo['cs_3'];
				$cs_4=$dataInfo['cs_4'];
				$cs_5=$dataInfo['cs_5'];
				$bg_color=$dataInfo['bg_color'];
				$word_color=$dataInfo['word_color'];
				$word_size=$dataInfo['word_size'];
				$oorc=$dataInfo['oorc'];
			}
		}
	?>

		<script type="text/javascript">
		<!--
		function onclick_update(form) {	
			if (strlen(form.voteco.value)<1){
				alert('投票议题不能为空');
				form.voteco.focus();
				return false;
			}

			if (strlen(form.cs_1.value)<1 || strlen(form.cs_2.value)<1){
				alert('投票选项至少要两个');
				form.cs_1.focus();
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
		  <? if ($ActionMessage!="") { ?>
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
		  <? } ?>
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			   <tr class="subcontent-input"> 
				  <td class="input-titleblue">投票议题</td>
				  <td>
					<input name="voteco" type="text" class="textbox" size="35" value="<?php echo $voteco?>" maxlength="50">可以达到200字，为了显示美观，请注意字数
				  </td>
			   </tr>
			  <tr class="subcontent-input">
				  <td width="10%" nowrap class="input-titleblue">投票类型</td>
				  <td width="90%">
					<input name="sorm" type="radio" value="False" <? if ($sorm=="False" or $sorm=="") { echo "checked"; }?>>单选
					<input type="radio" name="sorm" value="True" <? if ($sorm=="True") { echo "checked"; }?>>多选
				  </td>
			   </tr>
			   <? for ($i=1;$i<6;$i++) { 
				  $field="cs_".$i;
			   ?>
			   <tr class="subcontent-input"> 
				   <td class="input-titleblue">选项<?php echo $i?></td>
				   <td><input name="<?php echo $field?>" type="text" class="textbox" value="<?php echo $$field?>" maxlength="50"></td>
			   </tr>
			   <? } ?>
			   <tr class="subcontent-input"> 
				  <td nowrap>背景颜色</td>
				  <td>
					<input name="bg_color" type="text" class="textbox"  maxlength="6" value="<?php echo $bg_color?>">如EEEEEE 不用加&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">可以不填，默认是EEEEEE</font>
				  </td>
			   </tr>
			   <tr class="subcontent-input"> 
				   <td nowrap>文字颜色</td>
				   <td>
						<input name="word_color" type="text" class="textbox"  maxlength="6" value="<?php echo $word_color?>">如000000 不用加&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">可以不填，默认是000000</font>
				   </td>
			   </tr>
			   <tr class="subcontent-input"> 
					<td nowrap>文字大小</td>
					<td>
						<input name="word_size" type="text" class="textbox" maxlength="2" value="<?php echo $word_size?>">单位px 如：这是12px <span style="font-size:14px">这是14px</span> <font color="#FF3300">可以不填</font>
					</td>
				</tr>
			  <tr class="subcontent-input">
				  <td width="10%" nowrap class="input-titleblue">投票状态</td>
				  <td width="90%">
					<input name="oorc" type="radio" value="True" <? if ($oorc=="True" or $oorc=="") { echo "checked"; }?>>开启
					<input type="radio" name="oorc" value="False" <? if ($oorc=="False") { echo "checked"; }?>>关闭
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
	<? } ?>
	<!--结束增加／编辑投票-->

</form>

<script type="text/javascript">
parent.document.all("advPlugin").style.height=document.body.scrollHeight;
parent.document.all("advPlugin").style.width=document.body.scrollWidth;
</script>

</body>
</html>
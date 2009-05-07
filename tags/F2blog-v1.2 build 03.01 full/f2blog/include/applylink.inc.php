<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//必须在本站操作
$server_session_id=md5("applink".session_id());
$_GET['action']=empty($_GET['action'])?"":$_GET['action'];
if ($_GET['action']=="save" && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

if ($settingInfo['applylink']==0){
?>
   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?php echo $strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?php echo $strApplyLinkError?><br /><a href="index.php"><?php echo $strErrorBack?></a></div>
	  </div>
	</div>
  </div><br /><br />
<?php }else{?>

<script type="text/javascript">
<!--
function onclick_update(form) {
	if (isNull(form.blogName, '<?php echo $strErrNull2?>')) return false;
	if (isNull(form.blogUrl, '<?php echo $strErrNull2?>')) return false;
	if (!(/^http:\/\//i.test(form.blogUrl.value))){
		alert('<?php echo $strLinkError?>');
		form.blogUrl.focus();
		return false;
	}
	if (!(/^http:\/\/(.*)[gif|jpg|png]$/i.test(form.blogLogo.value)) && form.blogLogo.value!=""){
		alert('<?php echo $strImageError?>');
		form.blogLogo.focus();
		return false;
	}

	<?php if ($settingInfo['isValidateCode']==1){?>
		if (!(/[0-9]{5,6}/.test(form.validate.value))){
			alert('<?php echo $strGuestBookInputValid?>');
			form.validate.focus();
			return false;
		}
	<?php }?>
	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "index.php?load=applylink&action=save";
	form.submit();
}

function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}
-->
</script>

<?php 
if ($_GET['action']=="save"){
	$check_info=1;
	if (empty($_POST['blogName']) || empty($_POST['blogUrl'])){
		$ActionMessage=$strErrBlank;
		$check_info=0;
	}

	if ($check_info==1 && (preg_match("/<|>|\./i",$_POST['blogName']) || preg_match("/<|>|'|\"/i",$_POST['blogUrl']) || preg_match("/<|>|'|\"/i",$_POST['blogLogo']))){
		$ActionMessage=$strErrorCharacter;
		$check_info=0;
		$_POST['blogName']="";
		$_POST['blogUrl']="";
		$_POST['blogLogo']="";
	}

	//检测验证码
	if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
	if ($check_info==1 && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['isValidateCode']==1){
		$ActionMessage=$strGuestBookValidError;
		$check_info=0;
	}

	if ($check_info==1){
		$blogName=safe_convert(strip_tags($_POST['blogName']));
		$blogUrl=safe_convert(strip_tags($_POST['blogUrl']));
		$blogLogo=safe_convert(strip_tags($_POST['blogLogo']));
		$rsexits=getFieldValue($DBPrefix."links","name='$blogName' or blogUrl='$blogUrl'","id");
		if ($rsexits!=""){
			$ActionMessage=$strDataExists;
		}else{
			$sql="INSERT INTO ".$DBPrefix."links(name,blogUrl,blogLogo) VALUES ('$blogName','$blogUrl','$blogLogo')";
			$DMC->query($sql);
			$ActionMessage="$strApplyWaitApprove";
			$blogName=$blogUrl=$blogLogo="";
		}
	}
}

if (preg_match("/http:\/\//is",$settingInfo['linklogo'])){
	$logopath=$settingInfo['linklogo'];
}else{
	$logopath=$settingInfo['blogUrl'].$settingInfo['linklogo'];
}

$logolink="<a href=\"".$settingInfo['blogUrl']."\" target=\"_blank\"><img src=\"".$logopath."\" title=\"{$settingInfo['blogTitle']}\" alt=\"{$settingInfo['blogTitle']}\" border=\"0\"/></a>";
$textlink="<a href=\"".$settingInfo['blogUrl']."\" target=\"_blank\" title=\"{$settingInfo['blogTitle']}\">{$settingInfo['name']}</a>";
$textlinkcode="<textarea cols=\"60\" rows=\"2\" class=\"userpass\">".encode($textlink)."</textarea>";
$logolinkcode="<textarea cols=\"60\" rows=\"5\" class=\"userpass\">".encode($logolink)."</textarea>";
?>

<div id="Content_ContentList" class="content-width">
	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><b><?php echo $strApplyLink?></b></h1>
		<h2 class="ContentAuthor">Apply Link</h2>
	  </div>
	  <div class="Content-body">
		
		<div id="MsgContent">
			<div id="MsgHead"><?php echo $strApplyLink?></div>
			<div id="MsgBody">
			 <?php if (!empty($ActionMessage)) printMessage($ActionMessage);?>
			 <table width="100%" cellpadding="0" cellspacing="0">
				<form name="seekform" action="" method="post" style="margin:0px;">
					<tr>
						<td align="right" width="85"><strong>　<?php echo $strLinksName?>:</strong></td>
						<td align="left" style="padding:3px;"><input name="blogName" type="text" size="18" class="userpass" value="<?php echo empty($_POST['blogName'])?"":$_POST['blogName']; ?>" /><font color="#FF0000">&nbsp;*</font> </td>
					</tr>
					<tr>
						<td align="right" width="85"><strong>　<?php echo $strLinksLinkUrl?>:</strong></td>
						<td align="left" style="padding:3px;"><input name="blogUrl" type="text" size="50" class="userpass" value="<?php echo empty($_POST['blogUrl'])?"":$_POST['blogUrl']; ?>" /><font color="#FF0000">&nbsp;*</font> </td>
					</tr>
					<tr>
						<td align="right" width="85"><strong>　<?php echo $strLinkLogo?>:</strong></td>
						<td align="left" style="padding:3px;"><input name="blogLogo" type="text" size="50" class="userpass" value="<?php echo empty($_POST['blogLogo'])?"":$_POST['blogLogo']; ?>" /> </td>
					</tr>
					<?php if ($settingInfo['isValidateCode']==1){?>
					<tr>
						<td align="right" width="85"><strong>　<?php echo $strGuestBookValid?></strong></td>
						<td align="left" style="padding:3px;">
						  <input name="validate" type="text" size="5" class="userpass" maxlength="10"/> <font color="#FF0000">&nbsp;*</font>
						   <?php if (function_exists('imagecreate')){ $validate_image="include/image_firefox.inc.php"; ?>
								<img id="valid_image" src="<?php echo $validate_image?>" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
						   <?php 
							}else{
								$_SESSION['backValidate']=validCode(6);
								echo "<span id=\"valid_image\">{$_SESSION['backValidate']}</span>";
							}
						   ?>		  
						</td>
					</tr>
					<?php  } ?>
					<tr>
						<td colspan="2" align="center" style="padding:3px;">
							<input name="save" type="button" class="userbutton" value="<?php echo $strApply?>" onclick="Javascript:onclick_update(this.form)"/>
							<input name="reback" type="reset" value="<?php echo $strGuestBookReset?>" class="userbutton"/>
							<input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
						</td>
					</tr>
				</form>
				</table>
			</div>
		</div>		
		
		<br />
		<div id="MsgContent">
			<div id="MsgHead"><?php echo $strLinkInfo1?></div>
			<div id="MsgBody">
				<ul>
					<li><font color=red><strong><?php echo $strLinkInfo2?></strong></font></li>
					<li><?php echo $strLinkInfo3."<br />".$textlink."<br />".$textlinkcode?></li>
					<li><?php echo $strLinkInfo4."<br />".$logolink."<br />".$logolinkcode?></li>
				</ul>
			</div>
		</div>		
	　<br />
	  </div>
	</div>
</div>
<?php }?>
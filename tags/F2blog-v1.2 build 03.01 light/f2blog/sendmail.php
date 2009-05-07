<?php 
include_once("include/function.php");
include_once("header.php");

include_once(F2BLOG_ROOT."./cache/cache_members.php");

if ($settingInfo['showMail']==0){
?>
   <br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:300px">
      <div id="MsgHead"><?php echo $strErrorInformation?></div>
      <div id="MsgBody">
	   <div class="ErrorIcon"></div>
       <div class="MessageText"><?php echo $strLogsSendMailError?><br /><a href="index.php"><?php echo $strErrorBack?></a></div>
	  </div>
	</div>
  </div><br /><br />
<?php }else{?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	if (form.subject.value==""){
		alert('<?php echo $strGuestBookBlankError?>');
		form.subject.focus();
		return false;
	}
	if (form.toaddress.value==""){
		alert('<?php echo $strGuestBookBlankError?>');
		form.toaddress.focus();
		return false;
	}
	if (form.fromaddress.value==""){
		alert('<?php echo $strGuestBookBlankError?>');
		form.fromaddress.focus();
		return false;
	}
	<?php if ($settingInfo['uservalid']==1){?>
	if (form.validate.value==""){
		alert('<?php echo $strErrNull2?>');
		form.validate.focus();
		return false;
	}
	<?php }?>
	if (form.toaddress.value.indexOf("@")<1){
		alert('<?php echo $strLogsSendMailErrAddr?>');
		form.toaddress.focus();
		return false;
	}	
	if (form.fromaddress.value.indexOf("@")<1){
		alert('<?php echo $strLogsSendMailErrAddr?>');
		form.fromaddress.focus();
		return false;
	}

	form.save.disabled = true;
	form.rest.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<?php 
if (!empty($_GET['action']) && $_GET['action']=="save"){
	$check_info=1;

	if (empty($_POST['subject']) || empty($_POST['fromaddress']) || empty($_POST['toaddress'])) {
		$ActionMessage=$strGuestBookBlankError;
		$check_info=0;
	}else{
		$_POST['validate']=safe_convert($_POST['validate']);
		$_POST['subject']=safe_convert($_POST['subject']);
		$_POST['fromaddress']=safe_convert($_POST['fromaddress']);
		$_POST['toaddress']=safe_convert($_POST['toaddress']);
		$_POST['body']=safe_convert($_POST['body']);
	}

	if ($check_info==1) {	
		$toemail=explode(";",$_POST['toaddress']);
		foreach($toemail as $value){
			if (check_email($value)==0){
				$ActionMessage=$strErrEmail;
				$check_info=0;
				break;
			}
		}
	}

	if ($check_info==1 && check_email($_POST['fromaddress'])==0){
		$ActionMessage=$strErrEmail;
		$check_info=0;
	}

	if ($check_info==1 && $_POST['validate']!=$_SESSION['backValidate'] && $settingInfo['uservalid']==1){
		$ActionMessage=$strLoginValidateError;
		$check_info=0;
	}

	//发送邮件
	if ($check_info==1) {
		$sql="select id,logTitle,logContent,postTime,author,password from ".$DBPrefix."logs where id='".$_POST['id']."' and saveType='1'";
		if ($fa=$DMC->fetchArray($DMC->query($sql))){
			if ($fa['password']!="" && (strpos(";".$_SESSION['logpassword'],$fa['password'])<1) && $_SESSION['rights']!="admin"){
				$content=$strLogPasswordHelp;
			}else{
				$content=formatBlogContent($fa['logContent'],1,$fa['id']);
				$author=(!empty($memberscache[$fa['author']]))?$memberscache[$fa['author']]:$fa['author'];
			}
			$body=$_POST['body']."\n";
			$body.="---------------------------------------------------------------------------\n";
			$body.="$strSearchContent\n";
			$body.="---------------------------------------------------------------------------\n";
			$body.="$strSearchTitle: ".$fa['logTitle']."\n";
			$body.="$strAuthor: ".$author."\n";
			$body.="$strLogDate: ".format_time("Y-m-d H:i:s",$fa['postTime'])."\n";
			$body.="$strLogRead: ".$settingInfo['blogUrl']."index.php?load=read&id=".$fa['id']."\n";
			$body.="---------------------------------------------------------------------------\n";
			$body.=dencode($content);
			$body=str_replace("&nbsp;"," ",$body);
			$body=str_replace("<p>","\n",$body);
			$body=str_replace("<br />","\n",$body);
			$body=str_replace("<br>","\n",$body);
			$body=str_replace("<br/>","\n",$body);
			$body=strip_tags($body);

			//邮件发送
			$ActionMessage="";
			foreach($toemail as $value){
				if (send_mail($value,$_POST['subject'],$body,$_POST['fromaddress'])==1){
					$ActionMessage.="$value ... $strLogsSendMailOK <br>";
				}else{
					$ActionMessage.="$value ... <font color=\"red\">$strLogsSendMailBad</font> <br>";
				}
			}
		}else{
			$ActionMessage=$strErrorNoExistsLog;			
		}
	}
}
?>

<!--内容-->
<div id="Tbody">
   <br /><br />
   <div style="text-align:center;">
    <div id="MsgContent" style="width:550px">
      <div id="MsgHead"><?php echo $strLogsSendMailTitle?></div>
      <div id="MsgBody">
		  <?php 
			if (!empty($check_info) && !empty($ActionMessage)) {
				echo "<div class=\"MessageText\"><b>$ActionMessage</div>";
			} else {
				printMessage($ActionMessage);
		  ?>
			  <table width="100%" cellpadding="0" cellspacing="0">
				<form action="" method="post" name="seekform">
				  <tr>
					<td align="right" width="15%"><strong><?php echo $strLogsSendMailSubject?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="subject" type="text" size="50" class="userpass" maxlength="255" value="<?php echo empty($_POST['subject'])?"":$_POST['subject']?>"/>
					</td>
				  </tr>
				  <tr>
					<td align="right" valign="top"><strong><?php echo $strLogsSendMailTo?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="toaddress" type="text" size="50" class="userpass" maxlength="255" value="<?php echo empty($_POST['toaddress'])?"":$_POST['toaddress']?>"/>
						<br /><?php echo $strLogsSendMailToHelp?>
					</td>
				  </tr>
				  <tr>
					<td align="right"><strong><?php echo $strLogsSendMailFrom?>:</strong></td>
					<td align="left" style="padding:3px;">
						<input name="fromaddress" type="text" size="50" class="userpass" maxlength="255" value="<?php echo empty($_POST['fromaddress'])?"":$_POST['fromaddress']?>"/>
					</td>
				  </tr>
				  <tr>
					<td align="right" valign="top"><strong><?php echo $strLogsSendMailBody?>:</strong></td>
					<td align="left" style="padding:3px;">
						<textarea name="body" rows="6" cols="45"><?php echo empty($_POST['body'])?"":$_POST['body']?></textarea>
						<br /><?php echo $strLogsSendMailBodyHelp?>
					</td>
				  </tr>
				  <?php if ($settingInfo['uservalid']==1){?>
				  <tr>
					<td align="right"><strong><?php echo $strLoginValidate?>:</strong></td>
					<td align="left" style="padding:3px;">
					   <input name="validate" type="text" size="5" class="userpass" maxlength="10"/>
					   <?php if (function_exists('imagecreate')){?>
							<img src="include/image_firefox.inc.php" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
					   <?php 
						}else{
							echo $_SESSION['backValidate']=validCode(6);
						}
					   ?>	
						<font color="#FF0000">&nbsp;*</font> <?php echo $strGuestBookInputValid?>
					</td>
				  </tr>
				 <?php }?>
				<tr><td colspan="2" align="center" style="padding:3px;">
					<input name="save" class="userbutton" type="button" id="save" value="<?php echo $strLogsSendMail?>" onclick="Javascript:onclick_update(this.form)">
					<input name="rest" type="reset" class="userbutton" value="<?php echo $strReset?>"/>
					<input name="return" type="button" class="userbutton" value="<?php echo $strReturn?>" onclick="location.href='index.php?load=read&id=<?php echo ($_GET['id']>0)?$_GET['id']:$_POST['id']?>'"/>
					<input name="id" type="hidden" value="<?php echo ($_GET['id']>0)?$_GET['id']:$_POST['id']?>"/>
				</td></tr>
				</form>
			  </table>
		  <?php  } ?>
		</div>
	  </div>
	</div>
<br /><br />
</div>
<?php }?>
<?php include_once("footer.php")?>

<?php 
function send_mail($ToEmail,$subject,$body,$FromEmail){
	$emailname=substr($FromEmail,0,strpos($FromEmail,"@"));
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/plain; charset=utf-8\r\n";

	/* additional headers */
	$headers .= "To: $ToEmail\r\n";
	$headers .= "From: $emailname <$FromEmail>\r\n";

	$subject="=?utf-8?B?".base64_encode("$subject")."?=";

	if(@mail($ToEmail,$subject,$body,"$headers")){
		return 1;
	} else{
		return 0;
	}
}
?>
<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//$per_page=2;
$per_page=$settingInfo['logscomment'];
$page=(empty($_GET['page']))?1:intval($_GET['page']);
$action=(empty($_GET['action']))?"read":$_GET['action'];
$total_num=0;

//过滤IP
$allow_reply=filter_ip(getip());
if ($allow_reply){$allow_reply=$fa['isComment'];}
if ($allow_reply){$allow_reply=$settingInfo['allowComment'];}

if ($settingInfo['isValidateCode']==1){
	$validate_image="include/image_firefox.inc.php";
}

$openwin_width="560";
$openwin_height="450";

if ($page<1){$page=1;}

$posturl="index.php?load=$load&id=".$id;

//保存留言内容
if ($action=="save" && $allow_reply){
	$check_info=true;
	if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		//检测昵称是否合法
		if (empty($_SESSION['rights']) && check_nickname($_POST['username'])==0){
			$ActionMessage=$strNickLengMax;
			$check_info=false;
		}
		//检测验证码
		if (!empty($_POST['validate'])) $_POST['validate']=safe_convert($_POST['validate']);
		if ($check_info && (empty($_POST['validate']) || $_POST['validate']!=$_SESSION['backValidate']) && $settingInfo['isValidateCode']==1){
			$ActionMessage=$strGuestBookValidError;
			$check_info=false;
		}
		//过滤名称与IP
		if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
			$ActionMessage=$strGuestBookFilter.$filter_name;
			$check_info=false;
		}
		//字数是否超过了$settingInfo['commLength']
		if ($check_info && strlen($_POST['message'])>$settingInfo['commLength']){
			$ActionMessage=str_replace("1",$settingInfo['commLength'],$strCommentsLengthError);
			$check_info=false;
		}
		//检测是否在规定的时候内发言
		if (!empty($_SESSION['replytime']) && $_SESSION['replytime']>time()-$settingInfo['commTimerout']){
			$ActionMessage=$strUserCommentTime;
			$check_info=false;
		}
	}

	if ($check_info && $_POST['message']==""){
		$ActionMessage="$strGuestBookBlankError";
		$check_info=false;
	}

	//檢查用戶在此處登錄
	if ($check_info && $_POST['username']!="") {
		if (!empty($_POST['username'])) $_POST['username']=safe_convert($_POST['username']);
		if (!empty($_POST['replypassword'])) $_POST['replypassword']=safe_convert($_POST['replypassword']);

		$sql="SELECT username,role,password FROM {$DBPrefix}members WHERE username='{$_POST['username']}' or nickname='{$_POST['username']}'";
		$userInfo = $DMC->fetchArray($DMC->query($sql));

		if ($userInfo || $settingInfo['master']==$_POST['username']) {
			if (md5($_POST['replypassword'])!=$userInfo['password']) {
				$ActionMessage=$strLoginErrUserPWD;
				$check_info=false;
			} else {
				if ($settingInfo['loginStatus']==0){
					$_SESSION['username'] = $userInfo['username'];
					$_SESSION['password'] = md5($_POST['replypassword']);
					$_SESSION['rights']   = $userInfo['role'];
				}
				$_POST['username'] = $userInfo['username'];
			}
		}
	}

	if ($check_info){
		$parent=0;
		$_POST['isSecret']=($_POST['isSecret'])?$_POST['isSecret']:0;
		$author=($_POST['username'])?$_POST['username']:$_SESSION['username'];
		$replypassword=($_POST['replypassword'])?md5($_POST['replypassword']):"";
		$_POST['bookface']=!empty($_POST['bookface'])?$_POST['bookface']:"face1";
		$_POST['homepage']=(!empty($_POST['homepage']))?$_POST['homepage']:"";
		$_POST['email']=(!empty($_POST['email']))?$_POST['email']:"";


		$sql="insert into ".$DBPrefix."comments(author,password,logId,homepage,email,face,ip,content,postTime,isSecret,parent) values('$author','$replypassword','".$id."','".encode($_POST['homepage'])."','".encode($_POST['email'])."','".substr(encode($_POST['bookface']),4)."','".getip()."','".encode($_POST['message'])."','".time()."','".encode($_POST['isSecret'])."','$parent')";
		//echo $sql;
		$DMC->query($sql);
				
		//更新LOGS评论数量
		settings_recount("comments");
		settings_recache();
		$DMC->query("UPDATE ".$DBPrefix."logs SET commNums=commNums+1 WHERE id='$id'");

		//更新cache
		recentComments_recache();
		logs_sidebar_recache($arrSideModule);

		//保存时间
		$_SESSION['replytime']=time();

		//清空内容
		$_POST['message']="";

		
		header("location:".str_replace("&amp;","&",$gourl)."$settingInfo[stype]");
		//echo "<script language=\"javascript\">window.location.href='$gourl';</script>";
		//echo "<script language=\"javascript\">window.reload</script>";
	}
}

//评论
if ($commNums>0) {
	$start_record=($page-1)*$per_page;
	$sql="select distinct a.*,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."comments as a left join ".$DBPrefix."members as b on a.author=b.username where a.logId='".$id."' and a.parent='0' order by postTime {$settingInfo['commentOrder']}";
	$nums_sql="select count(id) as numRows from ".$DBPrefix."comments where logId='".$id."' and parent='0'";
	$total_num=getNumRows($nums_sql);
	$query_sql=$sql." Limit $start_record,$per_page";
	$query_result = $DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
?>
	<div id="Content_ContentList" class="content-width">
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:left"><span id="load_ajax_msg"></span>
			<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="T") pageBar("$gourl"); ?>
		  </div>
		</div>
		<?php 
		foreach($arr_parent as $value){
			$gContent=ubb($value['content']);
			//头像
			if ($settingInfo['gbface']==1){
				$myIcons=empty($value['face'])?"images/avatars/1.gif":"images/avatars/".$value['face'].".gif";
			}else{
				$myIcons="";
			}

			$icon_path=($value['isSecret']==1)?"images/icon_lock.gif":"images/icon_quote.gif";
			if ($value['member_id']>0){
				$authorname=(!empty($value['nickname']))?$value['nickname']:$value['author'];
				$guestemail=($value['isHiddenEmail']==0 || (!empty($_SESSION['rights']) && $_SESSION['rights']=='admin'))?str_replace("@","#",$value['member_email']):"";
				$guestemail=(!empty($guestemail))?"<a href=\"mailto:{$guestemail}\" title=\"{$guestemail}\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=(!empty($value['member_homepage']))?"<a href=\"{$value['member_homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

				if (!empty($value['member_homepage'])) $value['homepage']=$value['member_homepage'];

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($value['member_email']);
			}else{
				$authorname=$value['author'];
				$guestemail=(!empty($value['email']) && !empty($_SESSION['rights']) && $_SESSION['rights']=='admin')?"<a href=\"mailto:".str_replace("@","#",$value['email'])."\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=(!empty($value['homepage']) && $value['homepage']!="")?"<a href=\"{$value['homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($value['email']);
			}
		  ?>
		  <div class="comment" style="margin-bottom:20px">
			<div class="commenttop"><a name="book<?php echo $value['id']?>"></a> <img src="<?php echo $icon_path?>" border="0" style="margin:0px 1px -3px 0px" alt=""/> <b><?php echo $authorname?></b>
				<span class="commentinfo">[ <?php echo format_time("L",$value['postTime'])?>
				<?php echo $guestemail?> <?php echo $guesthomeurl?>
				<?php echo (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?" | ".$value['ip']:""?>
				<?php if ($allow_reply){?>
				| <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."reply.php?load=$load&amp;page=$page&amp;id=$id&amp;postid=".$value['id']."','$openwin_width','$openwin_height')"?>"> <?php echo $strGuestBookReply?></a>
				| <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."editdel.php?load=$load&amp;page=$page&amp;id=$id&amp;postid=".$value['id']."','$openwin_width','$openwin_height')"?>" title="<?php echo $strGuestBookEditDel?>"><?php echo $strEdit?> <?php echo $strDelete?></a> <?php }?> ]
				</span>
			</div>
			<div class="commentcontent">
			<?php if (!empty($myIcons)){?>
			   <table border="0" cellspacing="0" cellpadding="0">
				   <tr>
					 <td width="90" valign="top" align="center"><?php if (empty($value['homepage'])){?><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/><?php }else{ ?><a href="<?php echo $value['homepage'];?>" target="_blank"><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/></a><?php }?></td>
					 <td valign="top" style="word-break:break-all; table-layout: fixed;"><?php echo ($value['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$gContent?></td>
				   </tr>
			   </table>
			<?php }else{?>
				<div style="padding-left:10px;word-break:break-all; table-layout: fixed;"><?php echo ($value['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$gContent?> </div>
			<?php }?>			
			</div>
			<?php 
			//取得回复
			$sub_sql="select distinct a.*,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."comments as a left join ".$DBPrefix."members as b on a.author=b.username where a.logId='".$id."' and a.parent='".$value['id']."' order by postTime";
			$query_result=$DMC->query($sub_sql);
			$arr_sub = $DMC->fetchQueryAll($query_result);

			foreach ($arr_sub as $fa){
				$rContent=ubb($fa['content']);
				//头像
				if ($settingInfo['gbface']==1){
					$myIcons=empty($fa['face'])?"images/avatars/1.gif":"images/avatars/".$fa['face'].".gif";
				}else{
					$myIcons="";
				}
				$icon_path=($fa['isSecret']==1)?"images/icon_lock.gif":"images/icon_reply.gif";		
				if ($fa['member_id']>0){
					$authorname=(!empty($fa['nickname']))?$fa['nickname']:$fa['author'];
					$guestemail=($fa['isHiddenEmail']==0 || (!empty($_SESSION['rights']) && $_SESSION['rights']=='admin'))?str_replace("@","#",$fa['member_email']):"";
					$guestemail=(!empty($guestemail))?"<a href=\"mailto:{$guestemail}\" title=\"{$guestemail}\" target=\"_blank\">$strShowEmail</a>":"";
					$guesthomeurl=(!empty($fa['member_homepage']))?"<a href=\"{$fa['member_homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

					if (!empty($fa['member_homepage'])) $fa['homepage']=$fa['member_homepage'];

					//myIcons
					if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($fa['member_email']);
				}else{
					$authorname=$fa['author'];
					$guestemail=(!empty($fa['email']) && !empty($_SESSION['rights']) && $_SESSION['rights']=='admin')?"<a href=\"mailto:".str_replace("@","#",$fa['email'])."\" target=\"_blank\">$strShowEmail</a>":"";
					$guesthomeurl=(!empty($fa['homepage']) && $fa['homepage']!="")?"<a href=\"{$fa['homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

					//myIcons
					if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($fa['email']);
				}
			?>
			<div class="commenttop"><div style="padding-left:15px;"><a name="book<?php echo $fa['id']?>"></a><img src="<?php echo $icon_path?>" border="0" style="margin:0px 3px -3px 0px" alt=""/><b><?php echo $authorname?></b>
				<span class="commentinfo">[ <?php echo $strReplyTitle?><?php echo format_time("L",$fa['postTime'])?>
				<?php echo $guestemail?> <?php echo $guesthomeurl?>
				<?php echo (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?" | ".$fa['ip']:""?><?php if ($allow_reply){?> | <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."editdel.php?load=$load&amp;page=$page&amp;id=$id&amp;postid=".$fa['id']."','$openwin_width','$openwin_height')"?>"> <?php echo $strEdit?> <?php echo $strDelete?></a> <?php }?> ]
				</span>
				</div>
			</div>
			<div class="commentcontent">
			<?php if (!empty($myIcons)){?>
			   <table border="0" cellspacing="0" cellpadding="0">
				   <tr>
					 <td width="90" valign="top" align="center"><?php if (empty($fa['homepage'])){?><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/><?php }else{ ?><a href="<?php echo $fa['homepage'];?>" target="_blank"><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/></a><?php }?></td>
					 <td valign="top" style="word-break:break-all; table-layout: fixed;"><?php echo ($fa['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$rContent?></td>
				   </tr>
			   </table>
			<?php }else{?>
				<div style="padding-left:25px;word-break:break-all; table-layout: fixed;"><?php echo ($fa['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$rContent?> </div>
			<?php }?>			
			</div>
			<?php  } ?>
		  </div>
		<?php }?>
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:right">
			<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="B") pageBar("$gourl"); ?>
		  </div>
		</div>
	</div>
<?php }else{?>
	<div id="Content_ContentList" class="content-width">
		<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
		  <div class="page" style="float:left"><span id="load_ajax_msg"></span></div>
		</div>
	</div>
<?php 		
}//end 评论

echo "<a name=\"tb_top\" href=\"tb_top\"></a>\n";
//取得引用
if ($quoteNums>0) {
	$sub_sql="select * from ".$DBPrefix."trackbacks where logId='".$id."' and isApp='1' order by postTime desc";
	$query_result=$DMC->query($sub_sql);

	while($fa = $DMC->fetchArray($query_result)){
	?>
	  <div class="comment">
	    <div class="commenttop"><a name="tb<?php echo $fa['id']?>"></a>
			<img src="images/icon_trackback.gif" alt="" style="margin:0px 4px -3px 0px"/><strong>
			<a href="<?php echo $fa['blogUrl']?>" target="_blank"> <?php echo (strpos($fa['blogSite'],"&amp;")>0)?str_replace("&amp;","&",$fa['blogSite']):$fa['blogSite']?></a></strong> 
			<span class="commentinfo">[<?php echo format_time("L",$fa['postTime'])?>
			<?php if (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin"){ ?>
				 | <a href="admin/trackback.php?action=deltb&amp;id=<?php echo $fa['id']?>" onclick="if (!window.confirm('<?php echo $strDeleteTb?>')) {return false}"><?php echo $strDelete?></a>
			<?php  } ?>	 
			]</span>
		</div>
	    <div class="commentcontent">
		<b><?php echo $strSubject?>:</b> <?php echo (strpos($fa['tbTitle'],"&amp;")>0)?str_replace("&amp;","&",$fa['tbTitle']):$fa['tbTitle']?><br />
		<b><?php echo $strLinks?>:</b> <a href="<?php echo $fa['blogUrl']?>" target="_blank"><?php echo $fa['blogUrl']?></a><br />
		<b><?php echo $strDigest?>:</b> <?php echo strip_tags(dencode($fa['content']),"<br />")?><br />
		</div>
	   </div>
	<?php  }
}//end　引用

//允许回复
if ($allow_reply){
?>
<script type="text/javascript">
<!--
function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

<?php 
//使用Ajax技术
if (strpos(";$settingInfo[ajaxstatus];","G")>0){
?>
function onclick_update(form) {
	<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		if (empty($_SESSION['rights'])) {
	?>
		if (strlen(form.username.value)<3){
			alert('<?php echo $strNickLengMax?>');
			form.username.focus();
			return false;
		}
		if (/[\'\"\\]/.test(form.username.value)){
			alert('<?php echo $strNickNameAlert?>');
			form.username.focus();
			return false;
		}
		<?php  } 
		if ($settingInfo['isValidateCode']==1){?>
			if (!(/[0-9]{5,6}/.test(form.validate.value))){
				alert('<?php echo $strGuestBookInputValid?>');
				form.validate.focus();
				return false;
			}
		<?php }
	}?>
	if (isNull(form.message, '<?php echo $strGuestBookInputContent?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	
	var postData="ajax_display=comment_post&id=<?php echo $id?>";
	postData+="&message="+f2_ajax_encode(form.message.value);
	<?php if ($settingInfo['gbface']==1){?>
		postData+="&bookface="+form.bookface.value;
	<?php }?>
	<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		if (empty($_SESSION['rights'])) {
	?>
		postData+="&username="+f2_ajax_encode(form.username.value);
		postData+="&replypassword="+f2_ajax_encode(form.replypassword.value);
		postData+="&homepage="+f2_ajax_encode(form.homepage.value);
		postData+="&email="+f2_ajax_encode(form.email.value);
		<?php  } ?>
		if (form.isSecret.checked){
			postData+="&isSecret=1";
		}else{
			postData+="&isSecret=0";
		}		
		<?php if ($settingInfo['isValidateCode']==1){?>
			postData+="&validate="+form.validate.value;
		<?php }?>
	<?php }?>
	f2_ajax_post(postData);
}

function f2_ajax_response(returnvalue) {
	<?php 
	$total_page=ceil($total_num/$per_page);
	if ((empty($_SESSION['rights']) or $_SESSION['rights']=="member") && $settingInfo['isValidateCode']==1){
	   if (function_exists('imagecreate')){
	?>
		document.getElementById("valid_image").src="<?php echo $validate_image?>";
	   <?php }?>
		document.frm.validate.value = "";
	<?php }?>
	if (trim(returnvalue)=="") {//处理正常
		document.frm.message.value = "";
		document.getElementById("commNums").innerHTML = parseInt(document.getElementById("commNums").innerHTML)+1;
		f2_ajax_page("f2blog_ajax.php?ajax_display=comment_page<?php echo "&load=$load&id=$id"?>&page=<?php echo $total_page?>");
	}else{
		alert(returnvalue);
	}
	document.frm.save.disabled = false;
	document.frm.reback.disabled = false;
}
<?php }?>

<?php 
//不使用Ajax技术
if (strpos(";$settingInfo[ajaxstatus];","G")<1){
?>
function onclick_update(form) {
	<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
		if (empty($_SESSION['rights'])) {
	?>
		if (strlen(form.username.value)<3){
			alert('<?php echo $strNickLengMax?>');
			form.username.focus();
			return false;
		}
		if (/[\'\"\\]/.test(form.username.value)){
			alert('<?php echo $strNickNameAlert?>');
			form.username.focus();
			return false;
		}
		<?php  } 
		if ($settingInfo['isValidateCode']==1){?>
			if (!(/[0-9]{5,6}/.test(form.validate.value))){
				alert('<?php echo $strGuestBookInputValid?>');
				form.validate.focus();
				return false;
			}
		<?php }?>
	<?php }?>
	if (isNull(form.message, '<?php echo $strGuestBookInputContent?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?php echo "$posturl&action=save"?>";
	form.submit();
}
<?php }?>

function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?php 
if (!empty($ActionMessage)){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?php echo $strCommentsTitle?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
      <table width="100%" cellpadding="0" cellspacing="0">
		<?php if (empty($_SESSION['rights']) or $_SESSION['rights']=="member"){
			if (empty($_SESSION['rights'])) {
		?>
			<tr>
			  <td align="right" width="18%" style="font-weight:bold"><?php echo $strGuestBookName?></td>
			  <td width="33%" align="left" style="padding:3px;">
				<input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?php echo empty($_POST['username'])?"":$_POST['username']?>"/>
			  </td>		  
			  <td width="18%" align="right" style="font-weight:bold"><?php echo $strGuestBookPassword?></td>
			  <td width="33%" align="left" style="padding:3px;">
				<input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?php echo empty($_POST['replypassword'])?"":$_POST['replypassword']?>"/>
			  </td>
			</tr> 
			<tr>
			  <td width="18%" align="right" valign="top" style="font-weight:bold"><?php echo $strGuestBookHomepage?></td>
			  <td width="33%" align="left" style="padding:2px;">
				<input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?php echo empty($_POST['homepage'])?"":$_POST['homepage']?>"/>
			  </td>
			  <td width="18%" align="right" style="font-weight:bold"><?php echo $strGuestBookEmail?></td>
			  <td width="33%" align="left" style="padding:3px;">
				<input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?php echo empty($_POST['email'])?"":$_POST['email']?>"/>
			  </td>
			</tr>		
			<?php
			}

			$_POST['isSecret']=empty($_POST['isSecret'])?"":$_POST['isSecret'];
			if ($settingInfo['isValidateCode']==1){			
			?>
			<tr>
			  <td align="right" width="18%" style="font-weight:bold"><?php echo $strGuestBookValid?></td>
			  <td width="33%" align="left" style="padding:3px;">
				  <input name="validate" type="text" size="5" class="userpass" maxlength="10"/> 
				   <?php if (function_exists('imagecreate')){?>
						<img id="valid_image" src="<?php echo $validate_image?>" alt="<?php echo $strGuestBookValidImage?>" align="middle"/>
				   <?php }else{
						$_SESSION['backValidate']=validCode(6);
						echo "<span id=\"valid_image\">{$_SESSION['backValidate']}</span>";
					}?>
			  </td>
			  <td width="18%" align="right" style="font-weight:bold"><?php echo $strGuestBookOption?></td>
			  <td width="33%" align="left" style="padding:3px;">
				<label for="label5">
				<input name="isSecret" type="checkbox" id="label5" value="1" <?php echo ($_POST['isSecret']=="1")?"checked=\"checked\"":""?>/>
				<?php echo $strGuestBookOptionHidden?>
				</label>
			  </td>
			</tr>   
			<?php }else{?>
			<tr>
			  <td align="right" width="18%" style="font-weight:bold"><?php echo $strGuestBookOption?></td>
			  <td width="33%" align="left" style="padding:3px;">
				<label for="label5">
				<input name="isSecret" type="checkbox" id="label5" value="1" <?php echo ($_POST['isSecret']=="1")?"checked=\"checked\"":""?>/>
				<?php echo $strGuestBookOptionHidden?>
				</label>		  
			  </td>
			  <td width="18%" align="right" style="padding:3px;">&nbsp; </td>
			  <td width="33%" align="left" style="padding:3px;">&nbsp; </td>
        </tr>
		<?php }//validate?>
		<?php }//admin?>
		<?php if ($settingInfo['gbface']==1){?>
		<tr>
		  <td align="right" width="18%" valign="top" style="font-weight:bold"><?php echo $strGuestBookFace?></td>
		  <td colspan="3" style="padding:2px;" align="left">
			   <input type="hidden" name="bookface" value="face1"/>
			   <a href="javascript:void(0)" class="CFace" id="face1" onclick="selectFace('face1')"><img src="images/avatars/1.gif" alt="" border="0" width="45px" height="45px" /></a>
			   <?php for ($face=2;$face<8;$face++){?>
				<a href="javascript:void(0)" class="LFace" id="face<?php echo $face?>" onclick="selectFace('face<?php echo $face?>')"><img src="images/avatars/<?php echo $face?>.gif" alt="" border="0" width="45px" height="45px" /></a>
			   <?php }?>
		  </td></tr>
		<tr>
		<?php }?>
          <td align="right" width="18%" valign="top" style="font-weight:bold"><?php echo $strGuestBookContent?></td>
          <td colspan="3" style="padding:2px;">
            <?php include("ubb.inc.php")?>
          </td>
        </tr>        
        <tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?php echo $strCommentsSubmit?>" onclick="Javascript:onclick_update(this.form)"/>
            <input name="reback" type="reset" class="userbutton" value="<?php echo $strGuestBookReset?>"/>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php }//结束留言>
<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "guestbook.inc.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

//$per_page=2;
$per_page=$settingInfo['perPageNormal'];
$page=$_GET['page'];
$action=$_GET['action'];

//过滤IP
$allow_reply=filter_ip(getip());

if ($settingInfo['isValidateCode']==1){
	//读取验证码的图片
	$validate_image="include/image_firefox.inc.php";
}

$openwin_width="500";
$openwin_height="380";

if ($_SESSION['rights']=="admin"){
	$openwin_width="500";
	$openwin_height="300";
}

//保存留言内容
if ($action=="save" && $allow_reply){
	$check_info=true;
	if ($_SESSION['rights']!="admin"){
		//检测是否输入完全
		if ($_POST['username']=="" || $_POST['message']=="" || ($settingInfo['isValidateCode']==1 && $_POST['validate']=="")){
			$ActionMessage="$strGuestBookBlankError";
			$check_info=false;
		}
		//检测验证码
		if ($check_info && $_POST['validate']!=$_SESSION['validate'] && $settingInfo['isValidateCode']==1){
			$ActionMessage=$strGuestBookValidError;
			$check_info=false;
		}
		//过滤名称与IP
		if ($check_info && ($filter_name=replace_filter($_POST['message']))!=""){
			$ActionMessage=$strGuestBookFilter.$filter_name;
			$check_info=false;
		}

		//检测是否在规定的时候内发言
		if ($_SESSION['replytime'] && $_SESSION['replytime']>time()-$settingInfo['commTimerout']){
			$ActionMessage=$strUserCommentTime;
			$check_info=false;
		}
	}

	if ($check_info){
		$parent=0;
		$author=($_POST['username'])?$_POST['username']:$_SESSION['username'];
		$replypassword=($_POST['replypassword'])?md5($_POST['replypassword']):"";
		$sql="insert into ".$DBPrefix."guestbook(author,password,homepage,email,ip,content,postTime,isSecret,parent) values('$author','$replypassword','".encode($_POST['homepage'])."','".encode($_POST['email'])."','".getip()."',\"".encode($_POST['message'])."\",'".time()."','".$_POST['isSecret']."','$parent')";
		//echo $sql;
		$DMF->query($sql);
			
		//更新cache
		recentGbooks_recache();
		
		//保存时间
		$_SESSION['replytime']=time();

		//清空内容
		$_POST['message']="";
		$message="";
	}
}

$gourl="$PHP_SELF?load=$load";
$posturl="$PHP_SELF?load=$load&page=$page";

//允许回复
if ($allow_reply){
?>
<script style="javascript">
<!--
function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

function onclick_update(form) {
	<?if ($_SESSION['rights']!="admin"){?>
		if (isNull(form.username, '<?=$strGuestBookInputName?>')) return false;	
		<?if ($settingInfo['isValidateCode']==1){?>
			if (isNull(form.validate, '<?=$strGuestBookInputValid?>')) return false;
		<?}?>
	<?}?>
	if (isNull(form.message, '<?=$strGuestBookInputContent?>')) return false;

	form.save.disabled = true;
	form.reback.disabled = true;
	form.action = "<?="$posturl&action=save"?>";
	form.submit();
}

function quickpost(event) {
	if((event.ctrlKey && event.keyCode == 13)||(event.altKey && event.keyCode == 83))	{
		onclick_update(this.document.frm);
	}	
}

<?
if ($ActionMessage){
	echo "alert ('$ActionMessage'); \n";
}
?>
-->
</script>
<div id="MsgContent" style="width:94%;">
  <div id="MsgHead"><?=$strGuestBookTitle?></div>
  <div id="MsgBody">
    <form name="frm" action="" method="post" style="margin:0px;">
      <table width="100%" cellpadding="0" cellspacing="0">
		<?if ($_SESSION['rights']!="admin"){?>
        <tr>
          <td align="right" width="18%"><strong><?=$strGuestBookName?></strong></td>
          <td width="33%" align="left" style="padding:3px;">
            <input name="username" type="text" onkeydown="quickpost(event)" size="18" class="userpass" maxlength="24" value="<?=$_POST['username']?>"/>
          </td>		  
          <td width="18%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookPassword?>
          </strong></td>
          <td width="31%" align="left" style="padding:3px;">
            <input name="replypassword" type="password" size="18" class="userpass" maxlength="24" value="<?=$_POST['replypassword']?>"/>
          </td>
        </tr> 
        <tr>
          <td width="18%" align="right" valign="top"><strong>
            <?=$strGuestBookHomepage?>
          </strong></td>
          <td width="33%" align="left" style="padding:2px;">
            <input name="homepage" type="text" size="18" class="userpass" maxlength="50" value="<?=$_POST['homepage']?>"/>
          </td>
          <td width="18%" align="right" style="padding:3px;"><strong>
            <?=$strGuestBookEmail?>
          </strong></td>
          <td width="31%" align="left" style="padding:3px;">
            <input name="email" type="text" size="18" class="userpass" maxlength="50" value="<?=$_POST['email']?>"/>
          </td>
        </tr>		
		<?if ($settingInfo['isValidateCode']==1){?>
        <tr>
          <td align="right" width="18%"><strong><?=$strGuestBookValid?></strong></td>
          <td width="33%" align="left" style="padding:3px;">
          <input name="validate" type="text" size="5" class="userpass" maxlength="10"/> 
		   <?if (function_exists(imagecreate)){?>
				<img src="<?=$validate_image?>" alt="<?=$strGuestBookValidImage?>" align="absmiddle"/>
		   <?
			}else{
				echo validCode(6);
			}
		   ?>		  
          <td width="18%" align="right" style="padding:3px;"><strong>
          <?=$strGuestBookOption?>
          </strong></td>
          <td width="31%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($_POST['isSecret']=="1")?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>
		  </td>
        </tr>   
		<?}else{?>
        <tr>
          <td align="right" width="18%"><strong>
            <?=$strGuestBookOption?>
          </strong></td>
          <td width="33%" align="left" style="padding:3px;">
            <label for="label5">
            <input name="isSecret" type="checkbox" id="label5" value="1" <?=($_POST['isSecret']=="1")?"checked":""?>/>
            <?=$strGuestBookOptionHidden?>
            </label>		  
		  </td>
          <td width="18%" align="right" style="padding:3px;">&nbsp; </td>
          <td width="31%" align="left" style="padding:3px;">&nbsp; </td>
        </tr>
		<?}//validate?>
		<?}//admin?>
        <tr>
          <td align="right" width="18%" valign="top"><strong><?=$strGuestBookContent?></strong><br/>
          </td>
          <td colspan="3" style="padding:2px;">
            <?include("ubb.inc.php")?>
          </td>
        </tr>
        <tr>
          <td colspan="4" align="center" style="padding:3px;">
            <input name="save" type="button" class="userbutton" value="<?=$strGuestBookSubmit?>" onClick="Javascript:onclick_update(this.form)"/>
            <input name="reback" type="reset" class="userbutton" value="<?=$strGuestBookReset?>"/>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?}//结束过滤?>
<?
if ($page<1){$page=1;}
$start_record=($page-1)*$per_page;

$sql="select * from ".$DBPrefix."guestbook where parent='0' order by postTime desc";
$total_num=$DMF->numRows($DMF->query($sql));

$query_sql=$sql." Limit $start_record,$per_page";
$query_result = $DMF->query($query_sql);
$arr_parent = $DMF->fetchQueryAll($query_result);
?>
<div id="Content_ContentList" class="content-width">
<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
  <div class="page" style="float:left">
	<? pageBar($gourl); ?>
  </div>
</div>
<div class="Content">
  <?
  for ($i=0;$i<count($arr_parent);$i++){
		$gContent=ubb($arr_parent[$i]['content']);
		//$face_path=($arr_parent[$i]['face'])?"<img src=\"images/face/".$arr_parent[$i]['face'].".gif\"/><br>":"";
		$icon_path=($arr_parent[$i]['isSecret']==1)?"images/icon_lock.gif":"images/icon_quote.gif";
		$authorname=getFieldValue($DBPrefix."members","username='".$arr_parent[$i]['author']."'","nickname");
		if ($authorname==""){
			$authorname="<a href=\"userinfo.php?userid=".$arr_parent[$i]['id']."\">".$arr_parent[$i]['author']."</a>";;
		}else{
			$authorname="<a href=\"userinfo.php?author=".$arr_parent[$i]['author']."\">".$authorname."</a>";
		}
  ?>
  <div class="comment" style="margin-bottom:20px">
    <div class="commenttop"><a name="book<?=$arr_parent[$i]['id']?>"></a> <img src="<?=$icon_path?>" border="0" style="margin:0px 1px -3px 0px"/><b><?=$authorname?></b> <span class="commentinfo">[<?=format_time("L",$arr_parent[$i]['postTime'])?><?=($_SESSION['rights']=="admin")?" | ".$arr_parent[$i]['ip']:""?><?if ($allow_reply){?> | <a href="<?="Javascript:openGuestBook('reply.php?load=$load&page=$page&postid=".$arr_parent[$i]['id']."','$openwin_width','$openwin_height')"?>"><?=$strGuestBookReply?></a> | <a href="<?="Javascript:openGuestBook('editdel.php?load=$load&page=$page&postid=".$arr_parent[$i]['id']."','$openwin_width','$openwin_height')"?>" title="<?=$strGuestBookEditDel?>"><?=$strEdit?> <?=$strDelete?></a> <?}?> ]</span></div>
    <div class="commentcontent"><div style="padding-left:10px;"><?=($arr_parent[$i]['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$gContent?></div></div>
	<?
	//取得回复
	$sub_sql="select * from ".$DBPrefix."guestbook where parent='".$arr_parent[$i]['id']."' order by postTime";
	$query_result=$DMF->query($sub_sql);
	$arr_sub = $DMF->fetchQueryAll($query_result);

	for($j=0;$j<count($arr_sub);$j++){
		$fa=$arr_sub[$j];
		$rContent=ubb($fa['content']);
		//$face_path=($fa['face'])?"<img src=\"images/face/".$fa['face'].".gif\"/><br>":"";
		$icon_path=($fa['isSecret']==1)?"images/icon_lock.gif":"images/icon_reply.gif";	
		$authorname=getFieldValue($DBPrefix."members","username='".$fa['author']."'","nickname");
		if ($authorname==""){
			$authorname="<a href=\"userinfo.php?userid=".$fa['id']."\">".$fa['author']."</a>";;
		}else{
			$authorname="<a href=\"userinfo.php?author=".$fa['author']."\">".$authorname."</a>";
		}	
	?>
	<div class="commenttop"><div style="padding-left:10px;"><a name="book<?=$fa['id']?>"></a><img src="<?=$icon_path?>" alt="" border="0" style="margin:0px 3px -3px 0px"/><b><?=$authorname?></b> <span class="commentinfo">[<?=format_time("L",$fa['postTime'])?><?=($_SESSION['rights']=="admin")?" | ".$fa['ip']:""?><?if ($allow_reply){?> | <a href="<?="Javascript:openGuestBook('editdel.php?load=$load&page=$page&postid=".$fa['id']."','$openwin_width','$openwin_height')"?>"> <?=$strEdit?> <?=$strDelete?></a> <?}?> ]</span></div></div>
    <div class="commentcontent"><div style="padding-left:25px;"><?=($fa['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$rContent?> </div> </div>
	<?}?>
  </div>	
  <?}?>
</div>
<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
  <div class="page" style="float:right">
	<? pageBar($gourl); ?>
  </div>
</div>
</div>
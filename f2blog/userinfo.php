<?
include_once("include/function.php");

if ($_GET['author']!=""){
	$userinfo=getRecordValue($DBPrefix."members","username='".$_GET['author']."'");
	$show_user['username']=$userinfo['username'];
	$show_user['nickname']=$userinfo['nickname'];
	$show_user['email']=$userinfo['email'];
	$show_user['homepage']=$userinfo['homePage'];
}

if ($_GET['userid']!=""){
	$userinfo=getRecordValue($DBPrefix."guestbook","id='".$_GET['userid']."'");
	$show_user['nickname']=$userinfo['author'];
	$show_user['email']=$userinfo['email'];
	$show_user['homepage']=$userinfo['homepage'];
}

//装载头部文件
include_once("header.php");
?>
<!--内容-->
<div id="Tbody">
<br/><br/>
   <div style="text-align:center;">
    <div id="MsgContent" style="width:420px">
      <div id="MsgHead"><?=$strUserInfoShow?></div>
      <div id="MsgBody">		  
		  <table width="100%" cellpadding="0" cellspacing="0">	 
			<?if ($show_user['nickname']!=""){?>
			  <?if ($_GET['author']!=""){?>
			  <tr><td align="right" width="85"><strong><?=$strLoginUserID?>:</strong></td><td align="left" style="padding:3px;"><?=$show_user['username']?></td></tr>
			  <?}?>
			  <tr><td align="right" width="85"><strong><?=$strGuestBookName?></strong></td><td align="left" style="padding:3px;"><?=$show_user['nickname']?></td></tr>
			  <tr><td align="right" width="85"><strong><?=$strGuestBookEmail?></strong></td><td align="left" style="padding:3px;"><?if ($show_user['email']!=""){?><a href="mailto:<?=$show_user['email']?>"><?=$show_user['email']?></a><?}else{?>---<?}?></td></tr>
			  <tr><td align="right" width="85"><strong><?=$strGuestBookHomepage?></strong></td><td align="left" style="padding:3px;"><?if ($show_user['homepage']!=""){?><a href="<?=$show_user['homepage']?>" target="_blank"><?=$show_user['homepage']?></a><?}else{?>---<?}?></td></tr>
			<?}else{?>
			  <tr>
				<td colspan="2" align="center" style="padding:3px;"><?=$strNoExits?></td>
			  </tr> 
			<?}?>
			  <tr>
				<td colspan="2" align="center" style="padding:3px;">
				  <input type="button" class="userbutton" value="<?=$strReturn?>" onclick="history.go(-1)"/></td>
			  </tr>        
		  </table>
		</div>
	  </div>
	</div>
<br/><br/>
</div>

<?include_once("footer.php")?>
<?php 
if (!defined('IN_F2CONT')) die ('Access Denied.');

$per_page=$settingInfo['logsgbook'];
$page=$_GET['page'];
$load=$_GET['load'];

if ($settingInfo['rewrite']==0) $gourl="index.php?load=$load";
if ($settingInfo['rewrite']==1) $gourl="rewrite.php/$load";
if ($settingInfo['rewrite']==2) $gourl="$load";


if ($settingInfo['rewrite']==1){
	$base_rewrite="http://".$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strpos($PHP_SELF,"f2blog_ajax.php"));
}else{
	$base_rewrite="";
}

//过滤IP
$allow_reply=filter_ip(getip());

$openwin_width="640";
$openwin_height="450";

if ($page<1){$page=1;}
$start_record=($page-1)*$per_page;

/*
$sql="select distinct a.*,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='0' order by postTime {$settingInfo['gbookOrder']}";
$nums_sql="select count(id) as numRows from ".$DBPrefix."guestbook where parent='0'";
*/

/* spam 過濾器強化	*/
switch (trim($settingInfo['spamfilter'])){
	//	新增留言，但不顯示 加入 spam 記號
	case "close":
		$sql="select distinct a.id, a.author, a.password, a.homepage, a.email, a.face, a.ip, a.content, a.postTime, a.isSecret, a.parent, a.HTTP_REFERER";
		$sql.=", a.isSpam ";
		$sql.=",b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='0' and isSpam='0' order by postTime {$settingInfo['gbookOrder']}";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."guestbook where parent='0' and isSpam='0'";
	break;
	
	//	新增留言，顯示為隱藏 加入 spam 記號
	case "hidden":
	case "default":
	default:
		$sql="select distinct a.id, a.author, a.password, a.homepage, a.email, a.face, a.ip, a.content, a.postTime";
		$sql.=",IF(a.isSpam=1,1,a.isSecret) as isSecret";
		$sql.=", a.parent, a.HTTP_REFERER";
		$sql.=", a.isSpam ";
		$sql.=",b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='0' order by postTime {$settingInfo['gbookOrder']}";
		$nums_sql="select count(id) as numRows from ".$DBPrefix."guestbook where parent='0'";
	break;
}


$total_num=getNumRows($nums_sql);

$query_sql=$sql." Limit $start_record,$per_page";
$query_result = $DMC->query($query_sql);
$arr_parent = $DMC->fetchQueryAll($query_result);
?>
	<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
	  <div class="page" style="float:left"><span id="load_ajax_msg"></span>
		<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="T") pageBar("$gourl"); ?>
	  </div>
	</div>
	<div class="Content">
	  <?php 
	  foreach($arr_parent as $value){
			$gContent=ubb($value['content']);
			$icon_path=($value['isSecret']==1)?"images/icon_lock.gif":"images/icon_quote.gif";

			//头像
			if ($settingInfo['gbface']==1){
				$myIcons=empty($value['face'])?"images/avatars/1.gif":"images/avatars/".$value['face'].".gif";
			}else{
				$myIcons="";
			}

			if ($value['member_id']>0){
				$authorname=($value['nickname']!="")?$value['nickname']:$value['author'];
				$guestemail=($value['isHiddenEmail']==0 || (!empty($_SESSION['rights']) && $_SESSION['rights']=='admin'))?str_replace("@","#",$value['member_email']):"";
				$guestemail=($guestemail!="")?"<a href=\"mailto:{$guestemail}\" title=\"{$guestemail}\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=($value['member_homepage']!="")?"<a href=\"{$value['member_homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";
				
				if (!empty($value['member_homepage'])) $value['homepage']=$value['member_homepage'];

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($value['member_email']);
			}else{
				$authorname=$value['author'];
				$guestemail=(!empty($value['email']) && !empty($_SESSION['rights']) && $_SESSION['rights']=='admin')?"<a href=\"mailto:".str_replace("@","#",$value['email'])."\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=($value['homepage']!="")?"<a href=\"{$value['homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($value['email']);
			}
	  ?>
	  <div class="comment" style="margin-bottom:20px">
		<div class="commenttop"><a name="book<?php echo $value['id']?>"></a> <img src="<?php echo $icon_path?>" border="0" style="margin:0px 1px -3px 0px" alt=""/><b><?php echo $authorname?></b>
			<span class="commentinfo">[ <?php echo format_time("L",$value['postTime'])?>
			<?php echo $guestemail?> <?php echo $guesthomeurl?>
			<?php echo (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?" | ".$value['ip']:""?>
			<?php if ($allow_reply){?>
			| <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."reply.php?load=$load&amp;page=$page&amp;postid=".$value['id']."','$openwin_width','$openwin_height')"?>"><?php echo $strGuestBookReply?></a>
			| <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."editdel.php?load=$load&amp;page=$page&amp;postid=".$value['id']."','$openwin_width','$openwin_height')"?>" title="<?php echo $strGuestBookEditDel?>"><?php echo $strEdit?>/<?php echo $strDelete?></a>
			<?php }?> ]
			</span>
		</div>
		<div class="commentcontent">
			<?php if (!empty($myIcons)){?>
			   <table border="0" cellspacing="0" cellpadding="0">
				   <tr>
					 <td width="100" valign="top" align="center"><?php if (empty($value['homepage'])){?><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/><?php }else{ ?><a href="<?php echo $value['homepage'];?>" target="_blank"><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/></a><?php }?></td>
					 <td valign="top" style="word-wrap:break-word;overflow:hidden;text-align:justify;table-layout:fixed;padding-left:6px;"><?php echo ($value['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$gContent?></td>
				   </tr>
			   </table>
			<?php }else{?>
				<div style="padding-left:10px;word-break:break-all; table-layout: fixed;"><?php echo ($value['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$gContent?> </div>
			<?php }?>
		</div>
		<?php 
		//取得回复
		//$sub_sql="select distinct a.*,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='".$value['id']."' order by postTime";
		
		/* spam 過濾器強化	*/
		switch (trim($settingInfo['spamfilter'])){
			//	新增留言，但不顯示 加入 spam 記號
			case "close":
				
				$sub_sql=" select distinct a.id, a.author, a.password, a.homepage, a.email, a.face, a.ip, a.content, a.postTime, a.isSecret, a.parent, a.HTTP_REFERER  ,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='".$value['id']."' and isSpam='0' order by postTime";
				
			break;
			
			//	新增留言，顯示為隱藏 加入 spam 記號
			case "hidden":
			case "default":
			default:
				
				$sub_sql=" select distinct a.id, a.author, a.password, a.homepage, a.email, a.face, a.ip, a.content, a.postTime";
				$sub_sql.=",IF(a.isSpam=1,1,a.isSecret) as isSecret";
				$sub_sql.=", a.parent, a.HTTP_REFERER  ,b.id as member_id,b.nickname,b.isHiddenEmail,b.email as member_email,b.homePage as member_homepage from ".$DBPrefix."guestbook as a left join ".$DBPrefix."members as b on a.author=b.username where parent='".$value['id']."' order by postTime";
				
			break;
		}
		
		
		$query_result=$DMC->query($sub_sql);
		$arr_sub = $DMC->fetchQueryAll($query_result);

		foreach ($arr_sub as $fa){
			$rContent=ubb($fa['content']);
			$icon_path=($fa['isSecret']==1)?"images/icon_lock.gif":"images/icon_reply.gif";	
			//头像
			if ($settingInfo['gbface']==1){
				$myIcons=empty($fa['face'])?"images/avatars/1.gif":"images/avatars/".$fa['face'].".gif";
			}else{
				$myIcons="";
			}
			if ($fa['member_id']>0){
				$authorname=($fa['nickname']!="")?$fa['nickname']:$fa['author'];
				$guestemail=($fa['isHiddenEmail']==0 || (!empty($_SESSION['rights']) && $_SESSION['rights']=='admin'))?str_replace("@","#",$fa['member_email']):"";
				$guestemail=($guestemail!="")?"<a href=\"mailto:{$guestemail}\" title=\"{$guestemail}\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=($fa['member_homepage']!="")?"<a href=\"{$fa['member_homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

				if (!empty($fa['member_homepage'])) $fa['homepage']=$fa['member_homepage'];

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($fa['member_email']);
			}else{
				$authorname=$fa['author'];
				$guestemail=(!empty($fa['email']) && !empty($_SESSION['rights']) && $_SESSION['rights']=='admin')?"<a href=\"mailto:".str_replace("@","#",$fa['email'])."\" target=\"_blank\">$strShowEmail</a>":"";
				$guesthomeurl=($fa['homepage']!="")?"<a href=\"{$fa['homepage']}\" target=\"_blank\">$strShowHomepage</a>":"";

				//myIcons
				if (function_exists('MyEmailIcon')) $myIcons=MyEmailIcon($fa['email']);
			}
		?>
		<div class="commenttop">
			<div style="padding-left:15px;"><a name="book<?php echo $fa['id']?>"></a><img src="<?php echo $icon_path?>" alt="" border="0" style="margin:0px 3px -3px 0px"/><b><?php echo $authorname?></b>
				<span class="commentinfo">[ <?php echo $strReplyTitle?><?php echo format_time("L",$fa['postTime'])?>
				<?php echo $guestemail?> <?php echo $guesthomeurl?>
				<?php echo (!empty($_SESSION['rights']) && $_SESSION['rights']=="admin")?" | ".$fa['ip']:""?>
				<?php if ($allow_reply){?> | <a href="<?php echo "Javascript:openGuestBook('".$base_rewrite."editdel.php?load=$load&amp;page=$page&amp;postid=".$fa['id']."','$openwin_width','$openwin_height')"?>"> <?php echo $strEdit?>/<?php echo $strDelete?></a>
				<?php }?> ]
				</span>
			</div>
		</div>
		<div class="commentcontent">
			<?php if (!empty($myIcons)){?>
			   <table border="0" cellspacing="0" cellpadding="0">
				   <tr>
					 <td width="100" valign="top" align="center"><?php if (empty($fa['homepage'])){?><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/><?php }else{ ?><a href="<?php echo $fa['homepage'];?>" target="_blank"><img src="<?php echo $myIcons?>" alt="" border="0" style="margin-left:-4px"/></a><?php }?></td>
					 <td valign="top" style="word-wrap:break-word;overflow:hidden;text-align:justify;table-layout:fixed;padding-left:6px;"><?php echo ($fa['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$rContent?></td>
				   </tr>
			   </table>
			<?php }else{?>
				<div style="padding-left:25px;word-break:break-all; table-layout: fixed;"><?php echo ($fa['isSecret']==1 && $_SESSION['rights']!="admin")?$strGuestBookHidden:$rContent?> </div>
			<?php }?>
		</div>
		<?php }?>
	  </div>	
	  <?php }?>
	</div>
	<div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
	  <div class="page" style="float:right">
		<?php  if ($settingInfo['pagebar']=="A" || $settingInfo['pagebar']=="B") pageBar("$gourl"); ?>
	  </div>
	</div>
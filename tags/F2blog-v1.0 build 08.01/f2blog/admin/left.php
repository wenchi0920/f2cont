<?
$PATH="./";
include_once("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="images/css/style.css">
<script language="javascript">
function switchSysBar(){
	if (parent.document.getElementById("contentid").cols=="186,*"){
		parent.document.getElementById("contentid").cols="12,*";
		document.getElementById("menuid").style.display="none";
		document.getElementById("hidemenu").style.height="500";	
		document.getElementById("menusrc").src="images/left/menu_open.gif";
		document.getElementById("menusrc").title="<?=$strLeftMenuShow?>";
	}else{
		parent.document.getElementById("contentid").cols="186,*";
		document.getElementById("menuid").style.display="";
		document.getElementById("hidemenu").style.height="0";
		document.getElementById("menusrc").src="images/left/close_menu.gif";
		document.getElementById("menusrc").title="<?=$strLeftMenuHidden?>";
	}
}

function switchMenu(a){
	var astyle=document.getElementById(a).style.display;
	document.getElementById("child1").style.display="none";
	document.getElementById("child2").style.display="none";
	document.getElementById("child3").style.display="none";
	document.getElementById("child4").style.display="none";
	document.getElementById("child5").style.display="none";
	document.getElementById("child6").style.display="none";
	
	if (astyle=="none"){
		document.getElementById(a).style.display="";
	}else{
		document.getElementById(a).style.display="none";
	}
}
</script>
</head>
<body>
<div id="content-left">
  <table width="186" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td><img src="images/left/bgtop-image.gif" width="186" height="5"></td>
    </tr>
	    <td height="15" bgcolor="#f0e8db"></td>
  </tr>
    <tr>
      <td>
        <table width="186" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td id="menuid">
              <table width="176" border="0" cellpadding="0" cellspacing="0" bgcolor="#f0e8db">
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" onclick="parent.main.location.href='info.php'" style="cursor:pointer;"><div class="main_title" style="background-image:url(images/left/button1-01.gif);"><?=$strLeftMenuHome?></div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" onclick="parent.main.location.href='setting.php'" style="cursor:pointer;"><div class="main_title" style="background-image:url(images/left/button1-02.gif);"><?=$strGeneralSetting?></div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td style="cursor:pointer;">
                    <div id="main1" onclick="switchMenu('child1')" class="main_title" style="background-image:url(images/left/button1-03.gif);"><?=$strCategoryTag?></div>
                    <div id="child1" style="display:none;" class="childmenu"><img src="images/left/submenu_topimg.gif"><br>
					<div class="sub_title" onclick="parent.main.location.href='categories.php'" style="background-image:url(images/left/sub_button1-01.gif);"><?=$strCategory?></div>	
					<div class="sub_title" onclick="parent.main.location.href='tags.php'" style="background-image:url(images/left/sub_button1-02.gif);"><?=$strTag?></div>
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td style="cursor:pointer;">
                    <div id="main2" onclick="switchMenu('child2')" class="main_title" style="background-image:url(images/left/button1-04.gif);"><?=$strLogManagement?></div>
                    <div id="child2" style="display:none;" class="childmenu"> <img src="images/left/submenu_topimg.gif"><br>
                    <div class="sub_title" onclick="parent.main.location.href='logs.php?action=add'" style="background-image:url(images/left/sub_button1-03.gif);"><?=$strLogNew?></div>	
					<div class="sub_title" onclick="parent.main.location.href='logs.php'" style="background-image:url(images/left/sub_button1-04.gif);"><?=$strLogBrowse?></div>	
					<div class="sub_title" onclick="parent.main.location.href='comments.php'" style="background-image:url(images/left/sub_button1-05.gif);"><?=$strCommentBrowse?></div>
					<div class="sub_title" onclick="parent.main.location.href='trackback.php'" style="background-image:url(images/left/sub_button1-06.gif);"><?=$strTrackbackBrowse?></div>	
					<div class="sub_title" onclick="parent.main.location.href='guestbooks.php'" style="background-image:url(images/left/sub_button1-07.gif);"><?=$strGuestBookBrowse?></div>	
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td style="cursor:pointer;">
                    <div id="main3" onclick="switchMenu('child3')" class="main_title" style="background-image:url(images/left/button1-05.gif);"><?=$strSkinManagement?></div>
                    <div id="child3" style="display:none;" class="childmenu"> <img src="images/left/submenu_topimg.gif"><br>
					<div class="sub_title" onclick="parent.main.location.href='skins.php'" style="background-image:url(images/left/sub_button1-08.gif);"><?=$strSkinSetting?></div>
					<div class="sub_title" onclick="parent.main.location.href='modules.php'" style="background-image:url(images/left/sub_button1-09.gif);"><?=$strModuleSetting?></div>
					<div class="sub_title" onclick="parent.main.location.href='plugins.php'" style="background-image:url(images/left/sub_button1-19.gif);"><?=$strPluginSetting?></div>
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td style="cursor:pointer;">
                    <div id="main4" onclick="switchMenu('child4')" class="main_title" style="background-image:url(images/left/button1-06.gif);"><?=$strContentManagement?></div>
                    <div id="child4" style="display:none;" class="childmenu"> <img src="images/left/submenu_topimg.gif"><br>
					<div class="sub_title" onclick="parent.main.location.href='filters.php'" style="background-image:url(images/left/sub_button1-10.gif);"><?=$strFilter?></div>
					<div class="sub_title" onclick="parent.main.location.href='keywords.php'" style="background-image:url(images/left/sub_button1-11.gif);"><?=$strKeyword?></div>
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" style="cursor:pointer;">
                    <div id="main5" onclick="switchMenu('child5')" class="main_title" style="background-image:url(images/left/button1-08.gif);"><?=$strAdvancedManagement?></div>
                    <div id="child5" style="display:none;" class="childmenu"> <img src="images/left/submenu_topimg.gif"><br>
					<div class="sub_title" onclick="parent.main.location.href='users.php'" style="background-image:url(images/left/sub_button1-20.gif);"><?=$strUser?></div>
					<div class="sub_title" onclick="parent.main.location.href='attachments.php'" style="background-image:url(images/left/sub_button1-12.gif);"><?=$strAttachment?></div>
					<div class="sub_title" onclick="parent.main.location.href='statistics.php'" style="background-image:url(images/left/sub_button1-13.gif);"><?=$strStatistics?></div>
					<div class="sub_title" onclick="parent.main.location.href='cache.php'" style="background-image:url(images/left/sub_button1-14.gif);"><?=$strCache?></div>
					<div class="sub_title" onclick="parent.main.location.href='editor_plugins.php'" style="background-image:url(images/left/sub_button1-21.gif);"><?=$strEditorPlugins?></div>
					<div class="sub_title" onclick="parent.main.location.href='info.php'" style="background-image:url(images/left/sub_button1-15.gif);"><?=$strServerInfo?></div>
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" style="cursor:pointer;">
                    <div id="main6" onclick="switchMenu('child6')" class="main_title" style="background-image:url(images/left/button1-11.gif);"><?=$strDataManagement?></div>
                    <div id="child6" style="display:none;" class="childmenu"> <img src="images/left/submenu_topimg.gif"><br>
					<div class="sub_title" onclick="parent.main.location.href='db_backup.php'" style="background-image:url(images/left/sub_button1-16.gif);"><?=$strBackup?></div>
					<div class="sub_title" onclick="parent.main.location.href='db_restore.php'" style="background-image:url(images/left/sub_button1-17.gif);"><?=$strRestore?></div>
					<div class="sub_title" onclick="parent.main.location.href='db_tools.php'" style="background-image:url(images/left/sub_button1-18.gif);"><?=$strOptimize?></div>
                    <img src="images/left/subbtnbottom.gif"> </div>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" onclick="parent.main.location.href='links.php'" style="cursor:pointer;"><div class="main_title" style="background-image:url(images/left/button1-07.gif);"><?=$strLinkManagement?></div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" onclick="parent.main.location.href='edituser.php'" style="cursor:pointer;"><div class="main_title" style="background-image:url(images/left/button1-09.gif);"><?=$strModifyInfo?></div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="11">&nbsp;</td>
                  <td width="155" height="32" onclick="parent.location.href='index.php?action=logout'" style="cursor:pointer;"><div class="main_title" style="background-image:url(images/left/button1-10.gif);"><?=$strLogout?></div></td>
                  <td>&nbsp;</td>
                </tr>
                <tr style="height:0!important; height:10;">
                  <td colspan="3"></td>
                </tr>
              </table>
            </td>
            <td width="10" valign="top" bgcolor="#f0e8db" id="hidemenu"><img src="images/left/close_menu.gif" width="10" height="74" align="middle" onclick="switchSysBar()" id="menusrc" style="cursor:pointer;" title="<?=$strLeftMenuHidden?>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><img src="images/left/bgbottom-image.gif" width="186" height="15"></td>
    </tr>
  </table>
</div>
</body>
</html>

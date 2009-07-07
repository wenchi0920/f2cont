<?php
session_start();
$step = (isset($_GET['step'])) ? $_GET['step'] : $_POST['step'];
$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$dbcharset = 'utf8';
$configfile = '../include/config.php';

$sqlfile = 'install.sql';
if(!is_readable($sqlfile)) {
	exit('$strDBNoExists');
}
$fp = fopen($sqlfile, 'rb');
$sql = fread($fp, 2048000);
fclose($fp);

$language=$_SESSION['language'];
if ($_GET['language']!=""){
	$language=$_GET['language'];
	$_SESSION['language'] = $_GET['language'];
}

if ($language=="") $language='zh_cn';
require("language/$language.php");

if ($step=="") $step=1;
$arrTitle=array("",$strInstallGuide,$strLicense,$strCheckRunSystem,$strSetDatabase,$strImportData,$strSetAdmin,$strInstallComp);
$arrStep=array("0","1","2","3","4","5","6","7");

$nextStep=$step+1;
$prevStep=$step-1;
$nextAccess=1;

?>
<HTML>
<HEAD>
<TITLE><?=$strF2BlogInstall?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<META content="noindex, nofollow" name=robots>
<LINK href="images/interface.css" rel=stylesheet>
<SCRIPT language=JavaScript src="images/js-gui.js"></SCRIPT>
<SCRIPT language=JavaScript src="images/js-form.php"></SCRIPT>
</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 background="images/background.gif" topMargin=0 onload=initPage(); marginheight="0" marginwidth="0">
<form name=settingsform method="post" action="<?=$php_self?>">
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
    <TR>
      <TD vAlign=top><TABLE cellSpacing=0 cellPadding=0 width=181 border=0>
          <TBODY>
            <TR vAlign=top>
              <TD vAlign=bottom width=20 bgColor=#000063 colSpan=2 height=48>
			  <IMG src="images/logo.gif"></TD>
            </TR>
            <TR vAlign=top>
              <TD width=20 height=24><IMG height=20 src="images/grad-1.gif" width=20></TD>
              <TD height=24><IMG height=20 src="images/grad-1.gif" width=160></TD>
            </TR>
            <TR>
              <TD width=20>&nbsp;</TD>
              <TD class=nav><TABLE cellSpacing=0 cellPadding=0 width=160 border=0>
                  <TBODY>
                    <TR>
                      <TD class=nav colSpan=2><B><?=$strNavigation?></B></TD>
                    </TR>
                    <TR>
                      <TD colSpan=2><IMG height=1 src="images/break.gif" width=160 vspace=4></TD>
                    </TR>

					<? for ($i=1;$i<count($arrTitle);$i++) { 
						if ($step>=$arrStep[$i]){
							$class="nav";
							
							$cstyle=($step==$arrStep[$i])?"style='color=red;FONT-WEIGHT: bold'":"";

							$href1="<a href='$php_self?step=".$arrStep[$i]."' $cstyle>";
							$href2="</a>";
						} else {
							$class="nav1";
							$href1="";
							$href2="";
						}
					
					?>
                    <TR>
                      <TD vAlign=top width=20><IMG height=7 src="images/caret-u.gif" width=11>&nbsp;</TD>
                      <TD class=<?=$class?> width=140>
						<?=$href1.$i.". ".$arrTitle[$i].$href2?>
					  </TD>
                    </TR>
                    <TR>
                      <TD colSpan=2><IMG height=1 src="images/break.gif" width=160 vspace=4></TD>
                    </TR>
					<? } ?>

                  </TBODY>
                </TABLE></TD>
            </TR>
          </TBODY>
        </TABLE></TD>

      <TD vAlign=top width="100%"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
          <TBODY>
            <TR>
              <TD width=40 height=20>&nbsp;</TD>
              <TD height=20>&nbsp;</TD>
            </TR>
            <TR>
              <TD width=20>&nbsp;</TD>
              <TD><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR>
                      <TD width="70%" height=20 class="f2blog"><?=$strWelcome?></TD>
                      <TD width="20%" class="nav">
					  	<?=$strSelectLanguage?>: 
						<select name="language" onchange="javascript:location.href=this.value">
							<option value='<?="$php_self?step=$step&language=en"?>' <?=($language=="en")?"selected":""?>><?=$strEnglish?></option>
							<option value='<?="$php_self?step=$step&language=zh_cn"?>' <?=($language=="zh_cn")?"selected":""?>><?=$strSimpledChinese?></option>
							<option value='<?="$php_self?step=$step&language=zh_tw"?>' <?=($language=="zh_tw")?"selected":""?>><?=$strTChinese?></option>
						</select>
					  </TD>
                      <TD width="10%" class="nav">
					    <? if ($step==4 or $step==6){ ?>
							<b><a href='javascript:toggleHelp();'><img src='images/help-book.gif' width='15' height='15' border='0' align='absmiddle'>&nbsp;<?=$strHelp?></a></b>
						<? } ?>
					  </TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
              <TD width=40>&nbsp;</TD>
            </TR>
          </TBODY>
        </TABLE>
        <IMG height=1 src="images/break-el.gif" width="100%" vspace=5>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
          <TBODY>
          <TR>
          <TD width=40>&nbsp;</TD>
          <TD>
          
          <BR>
          <BR>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD vAlign=top><IMG src="images/<?=$step?>.gif"></TD>
                <TD vAlign=top width="100%"><BR>
                  <SPAN class=tab-s><?=$arrTitle[$step]?></SPAN><BR>
                  <IMG height=1 src="images/break-el.gif" width="100%" vspace=8>
				  
				  <?
					switch($step) {
						case 1:
							$str="<SPAN class=install>".$strInstallInfo."</SPAN>";
							break;
						case 2:
							$str="<SPAN class=install>".$strLicenceInfo."</SPAN>";
							break;
						case 3:
							$registerGlobals = (!ini_get('register_globals'))?"True":"False";
							$fileUploadSize = ini_get('upload_max_filesize');

							if (file_exists("../include/config.php")){
								$fileAccess=intval(substr(sprintf('%o', fileperms('../include/config.php')), -4));
							}else{
								$fileAccess=0;
							}
							if (!file_exists("../cache")){mkdir("../cache", 0777);}
							if (!file_exists("../attachments")){mkdir("../attachments", 0777);}
							if (!file_exists("../backup")){mkdir("../backup", 0777);}

							$dirAccess1=intval(substr(sprintf('%o', fileperms('../cache')), -4));
							$dirAccess2=intval(substr(sprintf('%o', fileperms('../attachments')), -4));
							$dirAccess3=intval(substr(sprintf('%o', fileperms('../backup')), -4));
	
							$msg="";
							$curr_php_version = PHP_VERSION;
							if($curr_php_version < '4.0.6') {
								$curr_php_version = "$curr_php_version <font color='red'>$strNoPHPVersion</font>";
								$nextAccess=0;
							}

							$os=strtoupper(substr(PHP_OS, 0, 3));
							$curOs=PHP_OS;
							if ($fileAccess<777 and $os!="WIN") {
								$fileAccess="<b><font color='red'>".$fileAccess." $strNoWriteAccess</font></b>";
								$nextAccess=0;
							}
							if ($dirAccess1<777 and $os!="WIN") {
								$dirAccess1="<b><font color='red'>".$dirAccess1." $strNoWriteAccess</font></b>";
								$nextAccess=0;
							}
							if ($dirAccess2<777 and $os!="WIN") {
								$dirAccess2="<b><font color='red'>".$dirAccess2." $strNoWriteAccess</font></b>";
								$nextAccess=0;
							}
							if ($dirAccess3<777 and $os!="WIN") {
								$dirAccess3="<b><font color='red'>".$dirAccess3." $strNoWriteAccess</font></b>";
								$nextAccess=0;
							}
							
							$str="<SPAN class=install>$strServerInfo     <ul>      <li>$strServerType $curOs </li>      <li>$strWebServer ". $_SERVER['SERVER_SOFTWARE']." </li>      <li>$strPHPVersion $curr_php_version </li>      <li>$strGDVersion ".gd_version()." </li>      <li>$strFileUploadSize $fileUploadSize </li>      <li>Register_globals: $registerGlobals </li>      <li>./backup $strAccess1 $dirAccess3 ($curOs$strSystem) </li>      <li>./attachments $strAccess2 $dirAccess2 ($curOs$strSystem) </li>      <li> ./cache $strAccess3 $dirAccess1 ($curOs$strSystem)</li>      <li>./include/config.php $strAccess4 $fileAccess ($curOs$strSystem) </li>    </ul></SPAN>";    
							break;
						case 4:
							$spec="<tr>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'></td>\n";
							$spec.="    <td width='200'><img src='images/break-l.gif' height='1' width='200' vspace='10'></td>\n";
							$spec.="    <td width='100%'>&nbsp;</td>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'>\n";
							$spec.="  </tr>\n";
						
							$str="<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
							$str.="  <tr onMouseOver=\"setHelp('dbhost')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbhost' class='cellenabled' width='200' valign='top'>$strMySqlHost</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbhost' size='25' value=\"localhost\" onFocus=\"setHelp('dbhost')\" tabindex='1'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbuser')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbuser' class='cellenabled' width='200' valign='top'>$strMySqlUser</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbuser' size='25' value=\"\" onFocus=\"setHelp('dbuser')\" tabindex='2'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbpassword')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strMySqlPass</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='dbpassword' value='' size='25' onFocus=\"setHelp('dbpassword')\" tabindex='3'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbname')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbname' class='cellenabled' width='200' valign='top'>$strMySqlDatabase</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbname' size='25' value=\"\" onFocus=\"setHelp('dbname')\" tabindex='4'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('table_prefix')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_table_prefix' class='cellenabled' width='200' valign='top'>$strDBPrefix</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='table_prefix' size='25' value=\"f2blog_\" onFocus=\"setHelp('table_prefix')\" tabindex='5'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('db_same')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_table_prefix' class='cellenabled' width='200' valign='top'>$strHowSame</td>\n";
							$str.="    <td width='100%' valign='top'><input type='radio' value='1' name='dboverwrite' onclick=\"alert('$strOverAlert');\">$strOverInstall&nbsp;<input type='radio' value='0' name='dboverwrite' checked>$strAbort</td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";

							$str.="  <tr>\n";
							$str.="    <td height='10' colspan='4'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.="  <tr height='1'>\n";
							$str.="    <td colspan='4' bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td>\n";
							$str.="  </tr>\n";
							$str.="</table>\n";
						   
							$str.=" <script language='JavaScript'>\n";
							$str.="	var helpArray = new Array();\n";

							$str.="	helpArray['dbhost'] = '$strAdbhost';\n";
							$str.="	helpArray['dbuser'] = '$strAdbuser';\n";
							$str.="	helpArray['dbpassword'] = '$strAdbpassword';\n";
							$str.="	helpArray['dbname'] = '$strAdbname';\n";
							$str.="	helpArray['table_prefix'] = '$strAtable_prefix';\n";
							$str.="	helpArray['db_same'] = '$strHowSame';\n";
							$str.="	</script>\n";

							break;
						case 5:
							// 执行连结数据库等
							$dbhost = trim($_POST['dbhost']);
							$dbuser = trim($_POST['dbuser']);
							$dbpassword = trim($_POST['dbpassword']);
							$dbname = trim($_POST['dbname']);
							$table_prefix = trim($_POST['table_prefix']);
							
							if ($dbhost=="" or $dbuser=="" or $dbpassword=="" or $dbname=="" or $table_prefix=="") {
								$msg .= "<SPAN class=err>$strIsNull</span><br />";
								$nextAccess=0;
							}
							
							if(strstr($table_prefix, '.') and $nextAccess==1) {
								$msg .= "<SPAN class=err>$strPrefixErr</span><br />";
								$nextAccess=0;
							}
							
							if ($nextAccess==1) {
								include ('../include/db.php');
								// 连结数据库
								$DM = new DummyMySQLClass($dbhost, $dbuser, $dbpassword, $dbname, false);
								if ($DM->error()) {
									$msg .= "<SPAN class=err>$strNoConnDB</span><br />";
									$nextAccess=0;
								}
							}

							if ($nextAccess==1) {
								$mysqlversion = $DM->fetchArray($DM->query("SELECT VERSION() AS version"));
								$curr_mysql_version = $mysqlversion['version'];
								if($curr_mysql_version < '4.0') {
									$msg .= "<SPAN class=err>$strMixMysql</span><br />";
									$nextAccess=0;
								}
							}
							
							if ($nextAccess==1) {
								if($curr_mysql_version > '4.1') {
									$DM->query("ALTER DATABASE `$dbname` DEFAULT CHARACTER SET $dbcharset ");
								}
								
								if($DM->error()) {
									$msg .= "<SPAN class=err>$strDBInfo</span><br />";
									$nextAccess=0;
								}
							}

							//检测有无重复的表格
							if ($nextAccess==1) {
								@$DM->query("select * from ".$table_prefix."setting");
								$error=$DM->error();
								if ($error=="") {
									$dboverwrite=$_POST['dboverwrite'];
									if ($dboverwrite==1) {
										$dsql="DROP TABLE IF EXISTS `".$table_prefix."guestbook`, `".$table_prefix."comments`, `".$table_prefix."categories`, `".$table_prefix."logs`, `".$table_prefix."dailystatistics`, `".$table_prefix."setting`, `".$table_prefix."keywords`, `".$table_prefix."links`, `".$table_prefix."members`, `".$table_prefix."modsetting`, `".$table_prefix."modules`, `".$table_prefix."tags`, `".$table_prefix."trackbacks`, `".$table_prefix."filters`, `".$table_prefix."attachments`";
										$DM->query($dsql);
									} else {
										$msg .= "<SPAN class=err>$strAbort2</span><br />";
										$nextAccess=0;
									}
								}
							}

							if($nextAccess==1) {
								if (is_writeable($configfile)) {
									$fp = @fopen($configfile, 'r');
									$filecontent = @fread($fp, @filesize($configfile));
									@fclose($fp);

									$filecontent = preg_replace("/[$]DBHost\s*\=\s*[\"'].*?[\"']/is", "\$DBHost = '$dbhost'", $filecontent);
									$filecontent = preg_replace("/[$]DBUser\s*\=\s*[\"'].*?[\"']/is", "\$DBUser = '$dbuser'", $filecontent);
									$filecontent = preg_replace("/[$]DBPass\s*\=\s*[\"'].*?[\"']/is", "\$DBPass = '$dbpassword'", $filecontent);
									$filecontent = preg_replace("/[$]DBName\s*\=\s*[\"'].*?[\"']/is", "\$DBName = '$dbname'", $filecontent);
									$filecontent = preg_replace("/[$]DBPrefix\s*\=\s*[\"'].*?[\"']/is", "\$DBPrefix = '$table_prefix'", $filecontent);

									$fp = @fopen($configfile, 'w');
									@fwrite($fp, trim($filecontent));
									@fclose($fp);
								} else {
									$msg .= "<SPAN class=err>$strInsConfig</span><br />";
									$nextAccess=0;
								}
							}
							
							//导入数据
							if ($nextAccess==1) {
								runquery($sql);
								$msg .= str_replace("aaa",$tablenum,$strCreTabInfo)." <br>";
								
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (1, 'top', 'Top', 0, 0, 0, 1, 1, '', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (2, 'side', 'Side', 0, 0, 0, 2, 1, '', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (3, 'main', 'Content', 0, 0, 0, 3, 1, '', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (4, 'aboutBlog', '[var]strAboutBlog[/var]', 2, 0, 0, 2, 1, 'side_description()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (5, 'category', \"[var]strCategory[/var]\", 2, 0, 0, 3, 1, 'side_category()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (6, 'calendar', \"[var]strCalendar[/var]\", 2, 0, 0, 4, 1, 'side_calendar()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (7, 'hotTags', \"[var]strHotTags[/var]\", 2, 0, 0, 5, 1, 'side_hotTags()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (8, 'recentlogs', \"[var]strRecentLogs[/var]\", 2, 0, 0, 6, 1, 'side_recent_logs()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (9, 'recentComments', \"[var]strRecentComments[/var]\", 2, 0, 0, 7, 1, 'side_recent_comment()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (10, 'links', \"[var]strLinks[/var]\", 2, 0, 0, 10, 1, 'side_links()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (11, 'archives', \"[var]strArchives[/var]\", 2, 0, 0, 9, 1, 'side_archive()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (12, 'search', \"[var]strSearch[/var]\", 2, 0, 0, 11, 1, 'side_search()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (14, 'statistics', \"[var]strStatistics[/var]\", 2, 0, 0, 12, 1, 'side_bloginfo()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (16, 'guestbook', \"[var]strGuestbook[/var]\", 1, 0, 0, 3, 1, '', 'include/guestbook.inc.php', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (36, 'skinSwitch', \"[var]strSkinSwitch[/var]\", 2, 1, 0, 1, 1, 'side_skin_switch()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (40, 'guestbook', \"[var]strRecentGBook[/var]\", 2, 0, 0, 8, 1, 'side_guestbook()', '', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (41, 'tags', \"[var]strHomePageTags[/var]\", 1, 0, 0, 2, 1, '', 'include/tags.inc.php', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (42, 'home', \"[var]strHomePageTitle[/var]\", 1, 0, 0, 1, 1, '', 'index.php', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (43, 'admin', \"[var]strHomePageAdmin[/var]\", 1, 0, 0, 5, 1, '', 'admin/index.php', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (44, 'post', \"[var]strHomePagePost[/var]\", 1, 0, 0, 6, 1, '', 'admin/logs.php?action=add', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (45, 'rss', \"[var]strHomePageRss[/var]\", 1, 0, 0, 7, 1, '', 'rss.php', 0, '', 0, '', 0, '')");
								$DM->query("INSERT INTO `".$table_prefix."modules` VALUES (88, 'Function', 'Function', '0', '0', '0', '4', '1', '', '', '0', '', '0', '', '0', '')");

								$msg .= "$strImpModInfo <br>";
							}

							if($nextAccess==0) {
								$msg .= "<br><SPAN class=err>$strGenErrInfo</sapn><br><br>";
								$msg .="  <input type=\"button\" value=\"$strPrevStep\" onclick=\"javascript: window.location=('$php_self?step=$prevStep');\">\n";
								$str.="</p>\n";
							} else {
								$msg .=$strGenSucInfo;
								$msg = "<SPAN class=install>$msg</span>";
							}
							if ($msg) {
								$str="<p>".$msg."</p>";
							}

							break;
						case 6:
							$spec="<tr>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'></td>\n";
							$spec.="    <td width='200'><img src='images/break-l.gif' height='1' width='200' vspace='10'></td>\n";
							$spec.="    <td width='100%'>&nbsp;</td>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'>\n";
							$spec.="  </tr>\n";
						
							$str="<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
							$str.="  <tr onMouseOver=\"setHelp('admin')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbhost' class='cellenabled' width='200' valign='top'>$strAdminUser<br>($strUserAlert)</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='admin' size='25' value=\"\" onFocus=\"setHelp('admin')\" tabindex='1'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_pw')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbuser' class='cellenabled' width='200' valign='top'>$strAdminPass ($strErrLenPw)</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='admin_pw' size='25' value=\"\" onFocus=\"setHelp('admin_pw')\" tabindex='3'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_pw2')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strAdminPass2</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='admin_pw2' value='' size='25' onFocus=\"setHelp('admin_pw2')\" tabindex='4'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_email')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strEmail</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='admin_email' value='' size='25' onFocus=\"setHelp('admin_email')\" tabindex='5'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.="  <tr>\n";
							$str.="    <td height='10' colspan='4'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.="</table>\n";							
							$str.=" <script language='JavaScript'>\n";
							$str.="	var helpArray = new Array();\n";

							$str.="helpArray['admin'] = '$strAUsername';\n";
							$str.="helpArray['admin_pw'] = '$strAPass';\n";
							$str.="helpArray['admin_pw2'] = '$strAPass';\n";
							$str.="helpArray['admin_email'] = '$strAEmail';\n";
							$str.="	</script>\n";

							break;
						case 7:
							//检测输入
							$admin = trim($_POST['admin']);
							$admin_pw = trim($_POST['admin_pw']);
							$admin_pw2 = trim($_POST['admin_pw2']);
							$admin_email = trim($_POST['admin_email']);
							$admin_url="http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-19);
							
							if ($admin=="" or $admin_pw=="" or $admin_pw2=="" or $admin_email=="") {
								$str="<SPAN class=err>$strIsNull</span>";
								$nextAccess=0;
							} elseif (!ereg ("^[a-zA-Z0-9_]+$",$admin)){
								$str="<SPAN class=err>$strUserAlert</span>";
								$nextAccess=0;								
							}elseif (strlen($admin_pw)<6) {
								$str="<SPAN class=err>$strUserAlert</span>";
								$nextAccess=0;
							} elseif ($admin_pw!=$admin_pw2) {
								$str="<SPAN class=err>$strErrPw</span>";
								$nextAccess=0;
							} else {
								//插入管理员
								include ('../include/config.php');
								include ('../include/db.php');
								// 连结数据库
								$DMC = new DummyMySQLClass($DBHost, $DBUser, $DBPass, $DBName, false);
								$DMC->query("TRUNCATE TABLE `".$DBPrefix."members`"); 
								$DMC->query("INSERT INTO `".$DBPrefix."members` (username,password,role) VALUES ('$admin',md5('$admin_pw'),'admin')");

								$DMC->query("INSERT INTO `".$DBPrefix."setting` VALUES (1, 'F2Blog', 'Free & Freedom Blog', '$admin_url', '', '', '', '$admin', '$admin_email', 0, 0, 50, 0, 0, 0, 0, 0, 8, 20, 0, 0, 0, 1, 10, 0, 0, 0, 0, 0, 0, 1, 30, 800, 'default', 0, 1, 1, 0, '', '$language', '8', 'Y-m-d H:i', '', '', '', '', '', '', '', 0, 0, '', 10, 0)");

								//生成Cache文件
								include("../include/cache.php");
								reAllCache();

								$str.="<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
								$str.="  <tr>\n";
								$str.="    <td width='100%' valign='top'><br>\n";
								$str.="     <span class='install'><b>F2Blog$strInstallComp.</b><br>\n";
								$str.="      <br>\n";
								$str.="      $strDelInstall\n";
								$str.="      <br><br>\n";
								$str.="      $strLoginIndex</td>\n";
								$str.="  </tr>\n";
								$str.="</table>\n";
							}
							
							if($nextAccess==0) {
								$str .="<br><br>  <input type=\"button\" value=\"$strPrevStep\" onclick=\"javascript: window.location=('$php_self?step=$prevStep');\">\n";
							}

							break;
					}
					
					echo $str;
                  ?>
				  </TD>
              </TR>
            </TBODY>
          </TABLE>
          <BR>
          <BR>
          </TD>
          
          <TD width=40>&nbsp;</TD>
          </TR>
          </TBODY>
        </TABLE>
        <IMG height=1 src="images/break-el.gif" width="100%" vspace=5>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
          <TBODY>
            <TR>
              <TD width=40>&nbsp;</TD>
              <TD><BR>
                <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                  <TBODY>
                    <TR>
                      <TD align=right>
						<? if (($step>=1 and $step<7) or ($nextAccess==1 and $step<7)) { ?>
						<INPUT type=submit value="<?=$strNextStep?> >" name="proceed" <?=($nextAccess==0)?"disabled":""?>>
						<? } ?>
					 
					  </TD>
                    </TR>
                  </TBODY>
                </TABLE>
                <INPUT type=hidden value=<?=$nextStep?> name="step">
                <BR>
                <BR></TD>
              <TD width=40>&nbsp;</TD>
            </TR>
            <TR>
              <TD colspan=2>
			  <span style="font-size:11px"><p><center>Powered by F2Blog.com. Version 1.0 build 08.01<br>CopyRight &copy; 2006 <a href='http://www.f2blog.com' target='_blank'>F2Blog.com</a> All Rights Reserved.</center></p></span>
			  </TD>
            </TR>
          </TBODY>
        </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>

<div id='helpLayer' name='helpLayer' style='position:absolute; left:181; top:-10; width:10px; height:10px; z-index:1; overflow: hidden; visibility: hidden;'><img id='helpIcon' src='images/help-book.gif' align='absmiddle'><span id='helpContents' name='helpContents'></span></div>

</form>
</BODY>
</HTML>

<?
	function gd_version() {	
		if (function_exists('gd_info')) {
			$GDArray = gd_info(); 
			if ($GDArray['GD Version']) {
				$gd_version_number = $GDArray['GD Version'];
			} else {
				$gd_version_number = 0;
			}
			unset($GDArray);
		} else {
			$gd_version_number = 0;
		}
		return $gd_version_number;
	}

	function result($result = 1, $output = 1) {
		if($result) {
			$text = '... <font color="#0000EE">Yes</font><br />';
			if(!$output) {
				return $text;
			}
			echo $text;
		} else {
			$text = '... <font color="#FF0000">No</font><br />';
			if(!$output) {
				return $text;
			}
			echo $text;
		}
	}

	function runquery($sql) {
		global $dbcharset, $table_prefix, $DM, $tablenum,$strSuccess,$strCreateTable;

		$sql = str_replace("\r", "\n", str_replace('f2blog_', $table_prefix, $sql));
		$ret = array();
		$num = 0;
		foreach(explode(";\n", trim($sql)) as $query) {
			$queries = explode("\n", trim($query));
			foreach($queries as $query) {
				$ret[$num] .= $query[0] == '#' ? '' : $query;
			}
			$num++;
		}
		unset($sql);

		foreach($ret as $query) {
			$query = trim($query);
			if($query) {
				if(substr($query, 0, 12) == 'CREATE TABLE') {
					$name = preg_replace("/CREATE TABLE `(.+?)` \((.+?) ENGINE=MyISAM;*/is", "\\1", $query);
					echo $strCreateTable .$name." ... <font color=\"#0000EE\">$strSuccess</font><br />";
					$DM->query(createtable($query, $dbcharset));
					$tablenum++;
				} else {
					$DM->query($query);
				}
			}
		}
	}

	function createtable($sql, $dbcharset) {
		$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
		$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
			(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
	}

?>
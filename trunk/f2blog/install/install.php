<?php
error_reporting(0);
header("Content-Type: text/html; charset=utf-8");

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
$sql = fread($fp, filesize($sqlfile));
fclose($fp);

$language=$_SESSION['language'];
if ($_GET['language']!=""){
	$language=$_GET['language'];
	$_SESSION['language'] = $_GET['language'];
}

if ($language=="") $language="zh_cn";
if (!in_array($language,array("zh_tw","zh_cn","en"))) $language='zh_cn';

@require("language/$language.php");
require("install_func.php");

if ($step=="") $step=1;
$arrTitle=array("",$strInstallGuide,$strLicense,$strCheckRunSystem,$strSetDatabase,$strImportData,$strSetAdmin,$strInstallComp);
$arrStep=array("0","1","2","3","4","5","6","7");

$nextStep=$step+1;
$prevStep=$step-1;
$nextAccess=1;

?>
<HTML>
<HEAD>
<TITLE><?php echo $strF2BlogInstall?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<META content="noindex, nofollow" name=robots>
<LINK href="images/interface.css" rel=stylesheet>
<SCRIPT language=JavaScript src="images/js-gui.js"></SCRIPT>
<SCRIPT language=JavaScript src="images/js-form.php"></SCRIPT>
</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 background="images/background.gif" topMargin=0 onload=initPage(); marginheight="0" marginwidth="0">
<form name=settingsform method="post" action="<?php echo $php_self?>">
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
    <TR>
      <TD vAlign=top><TABLE cellSpacing=0 cellPadding=0 width=181 border=0>
          <TBODY>
            <TR>
              <TD vAlign="middle" align="center" colSpan=2 height=48><IMG src="images/logo.gif"></TD>
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
                      <TD class=nav colSpan=2><B><?php echo $strNavigation?></B></TD>
                    </TR>
                    <TR>
                      <TD colSpan=2><IMG height=1 src="images/break.gif" width=160 vspace=4></TD>
                    </TR>

					<?php $steptotal=count($arrTitle);
						for ($i=1;$i<$steptotal;$i++) { 
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
                      <TD class=<?php echo $class?> width=140>
						<?php echo $href1.$i.". ".$arrTitle[$i].$href2?>
					  </TD>
                    </TR>
                    <TR>
                      <TD colSpan=2><IMG height=1 src="images/break.gif" width=160 vspace=4></TD>
                    </TR>
					<?php } ?>

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
                      <TD width="70%" height=20 class="f2blog"><?php echo $strWelcome?></TD>
                      <TD width="20%" class="nav">
					  	<?php echo $strSelectLanguage?>: 
						<select name="language" onchange="javascript:location.href=this.value">
							<option value='<?php echo "$php_self?step=$step&language=en"?>' <?php echo ($language=="en")?"selected":""?>><?php echo $strEnglish?></option>
							<option value='<?php echo "$php_self?step=$step&language=zh_cn"?>' <?php echo ($language=="zh_cn")?"selected":""?>><?php echo $strSimpledChinese?></option>
							<option value='<?php echo "$php_self?step=$step&language=zh_tw"?>' <?php echo ($language=="zh_tw")?"selected":""?>><?php echo $strTChinese?></option>
						</select>
					  </TD>
                      <TD width="10%" class="nav">
					    <?php if ($step==4 or $step==6){ ?>
							<b><a href='javascript:toggleHelp();'><img src='images/help-book.gif' width='15' height='15' border='0' align='middle'>&nbsp;<?php echo $strHelp?></a></b>
						<?php } ?>
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
                <TD vAlign=top><IMG src="images/<?php echo $step?>.gif"></TD>
                <TD vAlign=top width="100%"><BR>
                  <SPAN class=tab-s><?php echo $arrTitle[$step]?></SPAN><BR>
                  <IMG height=1 src="images/break-el.gif" width="100%" vspace=8>			  
				  <?php
					switch($step) {
						case 1:
							$str="<SPAN class=install>".$strInstallInfo."</SPAN>";
							break;
						case 2:
							$str="<SPAN class=install>".$strLicenceInfo."</SPAN>";
							break;
						case 3:
							$registerGlobals = (ini_get('register_globals'))?"On":"Off";
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
							$dirAccess4=intval(substr(sprintf('%o', fileperms('../cache/html')), -4));

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
							if ($dirAccess4<777 and $os!="WIN") {
								$dirAccess4="<b><font color='red'>".$dirAccess4." $strNoWriteAccess</font></b>";
								$nextAccess=0;
							}								
							$str="<SPAN class=install>$strServerInfo     <ul>      <li>$strServerType $curOs </li>      <li>$strWebServer ". $_SERVER['SERVER_SOFTWARE']." </li>      <li>$strPHPVersion $curr_php_version </li>      <li>$strGDVersion ".gd_version()." </li>      <li>$strFileUploadSize $fileUploadSize </li>      <li>Register_globals: $registerGlobals </li>      <li>./backup $strAccess1 $dirAccess3 ($curOs$strSystem) </li>      <li>./attachments $strAccess2 $dirAccess2 ($curOs$strSystem) </li>      <li> ./cache $strAccess3 $dirAccess1 ($curOs$strSystem)</li>      <li> ./cache/html $strAccess3 $dirAccess4 ($curOs$strSystem)</li>      <li>./include/config.php $strAccess4 $fileAccess ($curOs$strSystem) </li></ul></SPAN>";    
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
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbhost' size='40' value=\"localhost\" onFocus=\"setHelp('dbhost')\" tabindex='1'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbuser')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbuser' class='cellenabled' width='200' valign='top'>$strMySqlUser</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbuser' size='40' value=\"\" onFocus=\"setHelp('dbuser')\" tabindex='2'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbpassword')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strMySqlPass</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='dbpassword' value='' size='40' onFocus=\"setHelp('dbpassword')\" tabindex='3'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('dbname')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbname' class='cellenabled' width='200' valign='top'>$strMySqlDatabase</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='dbname' size='40' value=\"\" onFocus=\"setHelp('dbname')\" tabindex='4'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.="  <tr onMouseOver=\"setHelp('dbname')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbname' class='cellenabled' width='200' valign='top'>&nbsp;</td>\n";
							$str.="    <td width='100%' valign='top'><INPUT TYPE=\"checkbox\" NAME=\"chkcreate\" value=\"1\" tabindex='5'> $strCreateDataBase</td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('table_prefix')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_table_prefix' class='cellenabled' width='200' valign='top'>$strDBPrefix</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='table_prefix' size='40' value=\"f2blog_\" onFocus=\"setHelp('table_prefix')\" tabindex='6'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('db_same')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_table_prefix' class='cellenabled' width='200' valign='top'>$strHowSame</td>\n";
							$str.="    <td width='100%' valign='top'><input type='radio' value='1' name='dboverwrite' onclick=\"alert('$strOverAlert');\" tabindex='7'>$strOverInstall&nbsp;<input type='radio' value='0' name='dboverwrite' checked tabindex='8'>$strAbort</td>\n";
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
							//数据库参数
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
								//连接数据库
								$DMC = new F2MysqlClass($dbhost, $dbuser, $dbpassword, $dbname);
								if ($DMC->error()){
									if ($_POST[chkcreate]!="1") {
										$msg .= "<SPAN class=err>$strNoConnDB</span><br />";
										$nextAccess=0;
									}else{//建立數據庫
										$DMC->query("create database $dbname","T");
										if ($DMC->error()){
											$msg .= "<SPAN class=err>$strCreateDataBaseNo</span><br />";
											$nextAccess=0;
										}else{
											$DMC->selectDB($dbname);
											$msg = $strCreateDataBaseOK;
										}
									}
								}
							}

							if ($nextAccess==1) {
								$curr_mysql_version = $DMC->getServerInfo();
								if($curr_mysql_version < '3.0') {
									$msg .= "<SPAN class=err>$strMixMysql</span><br />";
									$nextAccess=0;
								}
							}
							
							if ($nextAccess==1) {
								if($curr_mysql_version > '4.1') {
									$DMC->query("ALTER DATABASE `$dbname` DEFAULT CHARACTER SET $dbcharset ");
								}
								
								if($DMC->error()) {
									$msg .= "<SPAN class=err>$strDBInfo</span><br />";
									$nextAccess=0;
								}
							}

							//删除旧数据表
							if ($nextAccess==1) {
								$DMC->query("select id from ".$table_prefix."keywords`","T");
								if ($DMC->errno()==0) {
									if ($_POST['dboverwrite']==1) {
										$dsql="DROP TABLE IF EXISTS `".$table_prefix."guestbook`, `".$table_prefix."comments`, `".$table_prefix."categories`, `".$table_prefix."logs`, `".$table_prefix."dailystatistics`, `".$table_prefix."setting`, `".$table_prefix."keywords`, `".$table_prefix."links`, `".$table_prefix."members`, `".$table_prefix."modsetting`, `".$table_prefix."modules`, `".$table_prefix."tags`, `".$table_prefix."trackbacks`, `".$table_prefix."filters`, `".$table_prefix."attachments`, `".$table_prefix."linkgroup`, `".$table_prefix."tbsession`";
										$DMC->query($dsql);
									} else {
										$msg .= "<SPAN class=err>$strAbort2</span><br />";
										$nextAccess=0;
									}
								}
							}

							if($nextAccess==1) {
								if (is_writeable($configfile)) {
									$fp = @fopen($configfile, 'rbt');
									$filecontent = @fread($fp, @filesize($configfile));
									fclose($fp);

									$filecontent = preg_replace("/[$]DBHost\s*\=\s*[\"'].*?[\"']/is", "\$DBHost = '$dbhost'", $filecontent);
									$filecontent = preg_replace("/[$]DBUser\s*\=\s*[\"'].*?[\"']/is", "\$DBUser = '$dbuser'", $filecontent);
									$filecontent = preg_replace("/[$]DBPass\s*\=\s*[\"'].*?[\"']/is", "\$DBPass = '$dbpassword'", $filecontent);
									$filecontent = preg_replace("/[$]DBName\s*\=\s*[\"'].*?[\"']/is", "\$DBName = '$dbname'", $filecontent);
									$filecontent = preg_replace("/[$]DBPrefix\s*\=\s*[\"'].*?[\"']/is", "\$DBPrefix = '$table_prefix'", $filecontent);

									$fp = @fopen($configfile, 'wbt');
									fwrite($fp, $filecontent);
									fclose($fp);
								} else {
									$msg .= "<SPAN class=err>$strInsConfig</span><br />";
									$nextAccess=0;
								}
							}
							
							//新增模块值
							if ($nextAccess==1) {
								//echo $sql;
								runquery($sql);
								$msg .= str_replace("aaa",$tablenum,$strCreTabInfo)." <br />";
								
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (1, 'top', 'Top', 0, 0, 0, 1, 1, '', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (2, 'side', 'Side', 0, 0, 0, 2, 1, '', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (3, 'main', 'Content', 0, 0, 0, 3, 1, '', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (4, 'aboutBlog', '[var]strAboutBlog[/var]', 2, 1, 0, 2, 1, 'side_aboutBlog()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (5, 'category', '[var]strCategory[/var]', 2, 0, 0, 4, 1, 'side_category()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (6, 'calendar', '[var]strCalendar[/var]', 2, 0, 0, 5, 1, 'side_calendar()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (7, 'hotTags', '[var]strHotTags[/var]', 2, 0, 0, 5, 1, 'side_hotTags()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (8, 'recentlogs', '[var]strRecentLogs[/var]', 2, 0, 0, 7, 1, 'side_recentlogs()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (9, 'recentComments', '[var]strRecentComments[/var]', 2, 0, 0, 8, 1, 'side_recentComments()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (10, 'links', '[var]strLinks[/var]', 2, 0, 0, 11, 1, 'side_links()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (11, 'archives', '[var]strArchives[/var]', 2, 0, 0, 10, 1, 'side_archives()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (12, 'search', '[var]strSearch[/var]', 2, 0, 0, 12, 1, 'side_search()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (14, 'statistics', '[var]strStatistics[/var]', 2, 0, 0, 13, 1, 'side_statistics()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (16, 'guestbook', '[var]strHomePageGBook[/var]', 1, 0, 0, 3, 1, '', 'include/guestbook.inc.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (17, 'userPanel', '[var]strUserPanel[/var]', 2, 1, 0, 3, 1, 'side_userPanel()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (18, 'links', '[var]strLinks[/var]', 1, 0, 0, 4, 1, '', 'include/links.inc.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (19, 'archives', '[var]strArchives[/var]', 1, 0, 0, 5, 1, '', 'include/archives.inc.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (36, 'skinSwitch', '[var]strSkinSwitch[/var]', 2, 1, 0, 1, 1, 'side_skinSwitch()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (40, 'guestbook', '[var]strRecentGBook[/var]', 2, 0, 0, 9, 1, 'side_guestbook()', '', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (41, 'tags', '[var]strHomePageTags[/var]', 1, 0, 0, 2, 1, '', 'include/tags.inc.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (42, 'home', '[var]strHomePageTitle[/var]', 1, 0, 0, 1, 1, '', 'index.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (43, 'admin', '[var]strHomePageAdmin[/var]', 1, 0, 0, 6, 1, '', 'admin/index.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (44, 'post', '[var]strHomePagePost[/var]', 1, 0, 0, 7, 1, '', 'admin/logs.php?action=add&edittype=front', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (45, 'rss', '[var]strHomePageRss[/var]', 1, 0, 0, 8, 1, '', 'rss.php', 0, '', 0, '', 0, '')");
								$DMC->query("INSERT INTO `".$table_prefix."modules` VALUES (88, 'Function', 'Function', '0', '0', '0', '4', '1', '', '', '0', '', '0', '', '0', '')");

								//增加默认连接
								$DMC->query("INSERT INTO `".$table_prefix."linkgroup` VALUES ('1', 'Links', '1', '1')");
								$DMC->query("INSERT INTO `".$table_prefix."links` VALUES ('1', '1', 'F2blog', 'http://forum.f2blog.com/f2s.gif', 'http://www.f2blog.com/', '1', '1', '1')");
								
								//增加默认类别  `id` int(3) NOT NULL auto_increment,
								$DMC->query("INSERT INTO `".$table_prefix."categories` VALUES ('1', '0', '$strDefaultCategory', '1','$strDefaultCategory','','0','0','1')");

								$msg .= "$strImpModInfo <br />";
							}

							if($nextAccess==0) {
								$msg .= "<br /><SPAN class=err>$strGenErrInfo</sapn><br /><br />";
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
							$admin_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-19);		
							$spec="<tr>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'></td>\n";
							$spec.="    <td width='200'><img src='images/break-l.gif' height='1' width='200' vspace='10'></td>\n";
							$spec.="    <td width='100%'>&nbsp;</td>\n";
							$spec.="    <td width='30'><img src='images/spacer.gif' height='1' width='100%'>\n";
							$spec.="  </tr>\n";
						
							$str="<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
							$str.="  <tr onMouseOver=\"setHelp('admin')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbhost' class='cellenabled' width='200' valign='top'>$strAdminUser<br />($strUserAlert)</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='admin' size='40' value=\"\" onFocus=\"setHelp('admin')\" tabindex='1' maxlength='20'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_pw')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbuser' class='cellenabled' width='200' valign='top'>$strAdminPass ($strErrLenPw)</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='admin_pw' size='40' value=\"\" onFocus=\"setHelp('admin_pw')\" tabindex='3' maxlength='20'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_pw2')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strAdminPass2</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='password' name='admin_pw2' value='' size='40' onFocus=\"setHelp('admin_pw2')\" tabindex='4' maxlength='20'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('blog_name')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strName</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='blog_name' value='' size='40' onFocus=\"setHelp('blog_name')\" tabindex='5' maxlength='50'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('blog_desc')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strTitle</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='blog_desc' value='Free & Freedom Blog' size='40' onFocus=\"setHelp('blog_desc')\" tabindex='5' maxlength='150'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('blog_url')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strBlogUrl</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='blog_url' value='".$admin_url."' size='40' onFocus=\"setHelp('blog_url')\" tabindex='5'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_master')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strMaster($strNickNameUserName)</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='admin_master' value='' size='40' onFocus=\"setHelp('admin_master')\" tabindex='5' maxlength='50'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.=$spec;
							$str.="  <tr onMouseOver=\"setHelp('admin_email')\">\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="    <td id='cell_dbpassword' class='cellenabled' width='200' valign='top'>$strEmail</td>\n";
							$str.="    <td width='100%' valign='top'><input class='flat' type='text' name='admin_email' value='' size='40' onFocus=\"setHelp('admin_email')\" tabindex='6' maxlength='100'></td>\n";
							$str.="    <td width='30'>&nbsp;</td>\n";
							$str.="  </tr>\n";
							$str.="</table>\n";							
							$str.=" <script language='JavaScript'>\n";
							$str.="	var helpArray = new Array();\n";

							$str.="helpArray['admin'] = '$strAUsername';\n";
							$str.="helpArray['admin_pw'] = '$strAPass';\n";
							$str.="helpArray['admin_pw2'] = '$strAPass';\n";
							$str.="helpArray['blog_name'] = '$strName';\n";
							$str.="helpArray['blog_desc'] = '$strTitle';\n";
							$str.="helpArray['blog_url'] = '$strBlogUrl';\n";
							$str.="helpArray['admin_master'] = '$strNickNameUserName';\n";
							$str.="helpArray['admin_email'] = '$strAEmail';\n";
							$str.="	</script>\n";

							break;
						case 7:
							//插入管理员
							$admin = encode($_POST['admin']);
							$admin_pw = encode($_POST['admin_pw']);
							$admin_pw2 = encode($_POST['admin_pw2']);
							$admin_email = encode($_POST['admin_email']);
							$blog_name = encode(trim($_POST['blog_name']));
							$blog_desc = encode(trim($_POST['blog_desc']));
							$blog_url = encode($_POST['blog_url']);
							$admin_master = encode(trim($_POST['admin_master']));
							
							if ($admin=="" or $admin_pw=="" or $admin_pw2=="" or $admin_email=="" or $admin_master=="" or $blog_name=="") {
								$str="<SPAN class=err>$strIsNull</span>";
								$nextAccess=0;
							} elseif (!ereg ("^[a-zA-Z0-9_]+$",$admin)){
								$str="<SPAN class=err>$strUserAlert</span>";
								$nextAccess=0;								
							}elseif (strlen($admin_pw)<5 or strlen($admin)<5) {
								$str="<SPAN class=err>$strUserAlert</span>";
								$nextAccess=0;
							} elseif ($admin_pw!=$admin_pw2) {
								$str="<SPAN class=err>$strErrPw</span>";
								$nextAccess=0;
							} elseif ($admin==$admin_master) {
								$str="<SPAN class=err>$strNickNameUserName</span>";
								$nextAccess=0;
							} else {
								if (substr($blog_url,strlen($blog_url)-1,1)!="/"){$blog_url=$blog_url."/";}

								//加载数据
								define('IN_F2BLOG', TRUE);
								define('F2BLOG_ROOT', substr(dirname(__FILE__), 0, -7));

								include('../include/config.php');
								include('../include/db.php');
								include('../include/cache.php');

								$DMC = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName);
								$DMC->query("TRUNCATE TABLE `".$DBPrefix."members`"); 
								$DMC->query("INSERT INTO `".$DBPrefix."members` (username,password,nickname,email,isHiddenEmail,homePage,lastVisitTime,lastVisitIP,regIp,hashKey,role) VALUES ('$admin',md5('$admin_pw'),'$admin_master','$admin_email','1','','".time()."','','','','admin')");
																						
								include(F2BLOG_ROOT."./admin/setting_default.php");
								foreach($arr_setting as $key=>$value){
									$update="insert into ".$DBPrefix."setting values".$value;
									$DMC->query($update);
								}

								settings_recache();	
								include_once(F2BLOG_ROOT."./cache/cache_setting.php");
								include_once(F2BLOG_ROOT."./include/language/home/{$settingInfo['language']}.php");								
								modules_recache();
								include_once(F2BLOG_ROOT."./cache/cache_modules.php");	
								reAllCache();

								$str.="<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
								$str.="  <tr>\n";
								$str.="    <td width='100%' valign='top'><br />\n";
								$str.="     <span class='install'><b>F2Blog$strInstallComp.</b><br />\n";
								$str.="      <br />\n";
								$str.="      $strDelInstall\n";
								$str.="      <br /><br />\n";
								$str.="      $strLoginIndex</td>\n";
								$str.="  </tr>\n";
								$str.="</table>\n";
							}
							
							if($nextAccess==0) {
								$str .="<br /><br />  <input type=\"button\" value=\"$strPrevStep\" onclick=\"javascript: window.location=('$php_self?step=$prevStep');\">\n";
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
						<?php if (($step>=1 and $step<7) or ($nextAccess==1 and $step<7)) { ?>
						<INPUT type=submit value="<?php echo $strNextStep?> >" name="proceed" <?php echo ($nextAccess==0)?"disabled":""?>>
						<?php } ?>
					 
					  </TD>
                    </TR>
                  </TBODY>
                </TABLE>
                <INPUT type=hidden value=<?php echo $nextStep?> name="step">
                <BR>
                <BR></TD>
              <TD width=40>&nbsp;</TD>
            </TR>
            <TR>
              <TD colspan=2>
			  <span style="font-size:11px"><p><center>Powered by F2Blog.com. Version 1.2 build 03.01<br>CopyRight &copy; 2006 <a href='http://www.f2blog.com' target='_blank'>F2Blog.com</a> All Rights Reserved.</center></p></span>
			  </TD>
            </TR>
          </TBODY>
        </TABLE></TD>
    </TR>
  </TBODY>
</TABLE>

<div id='helpLayer' name='helpLayer' style='position:absolute; left:181; top:-10; width:10px; height:10px; z-index:1; overflow: hidden; visibility: hidden;'><img id='helpIcon' src='images/help-book.gif' align='middle'><span id='helpContents' name='helpContents'></span></div>

</form>
</BODY>
</HTML>
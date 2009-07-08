<?php 
include("./function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strServerInfo;
$title=$strServerInfo;
dohead($title,"");
require('admin_menu.php');

$mysqlversion = $DMC->fetchArray($DMC->query("SELECT VERSION() AS version"));
$webserver = $_SERVER['SERVER_SOFTWARE'];
$registerGlobals = (ini_get('register_globals'))?"On":"Off";
?>
<div id="content">
    <div class="contenttitle"><?php echo $title?> &nbsp;&nbsp;<span style="font-size:12px;"><a href="phpinfo.php" target="_blank"><?php echo $strViewPhpinfo?></a></font></div><br />
		<table width="98%"><tr>
		<td width="2%">
		<td valign="top" width="45%">
			<table cellpadding="2" cellspacing="1" width="100%">
			   <tr>
				<td colspan="2" class="rightbox"><strong><?php echo $strSystemInfo?></strong></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><?php echo $strServerType?> <?php echo PHP_OS?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><?php echo $strWebServer?> <?php echo $webserver?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><?php echo $strPHPVersion?> <?php echo PHP_VERSION?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><?php echo $strMysqlVersion?> <?php echo $mysqlversion['version']?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox"><?php echo $strGDVersion?> <?php echo gd_version()?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox">Register_globals: <?php echo $registerGlobals?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;</td>
				<td class="rightbox">get_magic_quotes_gpc: <?php echo (get_magic_quotes_gpc())?"On":"Off"?></td>
			   </tr>
			</table>
		</td>
		<td width="3%">
		<td width="45%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td colspan="4" class="rightbox"><strong><?php echo $strFunctionInfo?></strong></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">fsockopen: <?php echo function_exists("fsockopen")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">file_get_contents: <?php echo function_exists("file_get_contents")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">gzopen: <?php echo function_exists("gzopen")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">gzfile: <?php echo function_exists("gzfile")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">ob_start: <?php echo function_exists("ob_start")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">ob_gzhandler: <?php echo function_exists("ob_gzhandler")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">iconv: <?php echo function_exists("iconv")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">mb_convert_encoding: <?php echo function_exists("mb_convert_encoding")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">imagecolortransparent: <?php echo function_exists("imagecolortransparent")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">imagefilledrectangle: <?php echo function_exists("imagefilledrectangle")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">ImageColorAllocate: <?php echo function_exists("ImageColorAllocate")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">simplexml_load_file: <?php echo function_exists("simplexml_load_file")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			   <tr>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">COM Class: <?php echo class_exists("com")?"On":"<font color=\"red\">Off</font>"?></td>
				<td class="leftbox" width="2%">&nbsp;&nbsp;</td>
				<td class="rightbox">DOMDocument Class: <?php echo class_exists("DOMDocument")?"On":"<font color=\"red\">Off</font>"?></td>
			   </tr>
			</table>
		</td>
		</table>
		<br />
		<table width="98%"><tr>
		<td width="2%">
		<td valign="top" width="98%">
			<table cellpadding="2" cellspacing="1" width="100%">
			   <tr>
				<td colspan="7" class="rightbox"><strong><?php echo $strMysqlDataInfo?></strong></td>
			   </tr>
				<?php 
				echo "<tr>\n";
				echo "	<td class=\"leftbox\" width=\"2%\">&nbsp;</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlDataName."</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlCreateDate."</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlUpdateDate."</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlDataRows."</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlDataLength."</td>\n";
				echo "	<td class=\"rightbox\">".$strMysqlIndexLength."</td>\n";
				echo "</tr>\n";
				$total_rows=0;
				$total_data=0;
				$total_index=0;
				$mysql_stas=$DMC->fetchQueryAll($DMC->query("SHOW TABLE STATUS"));
				foreach($mysql_stas as $key=>$mysqlinfo){
					echo "<tr>\n";
					echo "	<td class=\"leftbox\" width=\"2%\">&nbsp;</td>\n";
					echo "	<td class=\"rightbox\">".$mysqlinfo['Name']."</td>\n";
					echo "	<td class=\"rightbox\">".$mysqlinfo['Create_time']."</td>\n";
					echo "	<td class=\"rightbox\">".$mysqlinfo['Update_time']."</td>\n";
					echo "	<td class=\"rightbox\">".$mysqlinfo['Rows']."</td>\n";
					echo "	<td class=\"rightbox\">".formatFileSize($mysqlinfo['Data_length'])."</td>\n";
					echo "	<td class=\"rightbox\">".formatFileSize($mysqlinfo['Index_length'])."</td>\n";
					echo "</tr>\n";
					$total_rows+=$mysqlinfo['Rows'];
					$total_data+=$mysqlinfo['Data_length'];
					$total_index+=$mysqlinfo['Index_length'];
				}
				echo "<tr>\n";
				echo "	<td class=\"leftbox\" width=\"2%\">&nbsp;</td>\n";
				echo "	<td class=\"rightbox\"><b>".$strMysqlDataTotal."</b></td>\n";
				echo "	<td class=\"rightbox\">&nbsp;</td>\n";
				echo "	<td class=\"rightbox\">&nbsp;</td>\n";
				echo "	<td class=\"rightbox\">".$total_rows."</td>\n";
				echo "	<td class=\"rightbox\">".formatFileSize($total_data)."</td>\n";
				echo "	<td class=\"rightbox\">".formatFileSize($total_index)."</td>\n";
				echo "</tr>\n";
				?>
			</table>
		</td>
		</table>
</div>

<?php  dofoot(); ?>
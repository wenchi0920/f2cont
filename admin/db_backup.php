<?php 
@set_time_limit(0);

require_once("function.php");

//必须在本站操作
$server_session_id=md5("dbbackup".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strBackup;

//保存参数
$action=$_GET['action'];

//默认的f2blog数据表
$arrTableName=array("logs","categories","comments","dailystatistics","guestbook","setting","keywords","links","linkgroup","members","modsetting","modules","trackbacks","filters","attachments","tags");

//列出所有的f2blog表。
$listTable=$DMC->fetchQueryAll($DMC->listTable($DBName));
foreach($listTable as $value){
	$f2blog_table=$value[0];
	if (strpos(";".$f2blog_table,$DBPrefix)>0){
		$f2blog_table=str_replace($DBPrefix,"",$f2blog_table);
		if (!in_array($f2blog_table,$arrTableName)) array_push($arrTableName,$f2blog_table);
	}
}

if ($action=="save"){
	if ($_POST['tables']=="all"){
		$op_table=$arrTableName;
	}else{
		$op_table=$_POST['table'];
	}

	$isgzip=$_POST['isgzip'];
	$filext=".sql";
	$filesize=($_POST['filesize']=="")?1024:$_POST['filesize'];
	$filesize=($isgzip==1)?$filesize*2:$filesize;
	$backup="../backup/".$_POST['backup'];
	$filesize=$filesize*1000;
	$cfg_blog_version=blogVersion;

	//开始备份
	$nowdate=format_time("L",time());
	$dump = "-- F2blog SQL Dump \r\n";
	$dump .= "-- version $cfg_blog_version \r\n";
	$dump .= "-- http://www.f2blog.com \r\n";
	$dump .= "--  \r\n";
	$dump .= "-- Created by: $nowdate \r\n";
	$dump .= "-- \r\n";

	$p=1;
	$error="";
	for ($i=0;$i<count($op_table);$i++){
		//表信息
		$result=$DMC->query("SHOW CREATE TABLE ".$DBPrefix.$op_table[$i]);
		$row=$DMC->fetchAssoc($result);
		unset($rows);
		
		$row['Create Table']=str_replace("\n", "\r\n", $row['Create Table']);
		$row['Create Table']=preg_replace('/AUTO_INCREMENT=([0-9]+?) /is', '', $row['Create Table']);
        $dump .= "\r\n\r\n--\r\n";
        $dump .= "-- Table structure for table '".$DBPrefix.$op_table[$i]."'\r\n";
        $dump .= "--\r\n\r\n";
		$dump .= "DROP TABLE IF EXISTS ".$DBPrefix.$op_table[$i]."; \r\n";
        $dump .= $row['Create Table'];
        $dump .= ";\r\n\r\n";
        $dump .= "\r\n\r\n--\r\n";
        $dump .= "-- Dumping data for table '".$DBPrefix.$op_table[$i]."'\r\n";
        $dump .= "--\r\n\r\n";  

		//记录
        $result = $DMC->query('SELECT * FROM '.$DBPrefix.$op_table[$i]);
		
        while ($row = $DMC->fetchArray($result)) {
			foreach($row as $key=>$value){
				$row[$key]=convert_character($value);
			}
			
        	$dump .= "INSERT INTO " . $DBPrefix.$op_table[$i] . " VALUES ('" . implode("','",$row) . "');\r\n";

			if(strlen($dump)>=$filesize){
				$filename=$backup.("_v".$p.$filext);
				if ($isgzip==0) {
					$msg=write_file($dump,$filename);  //写入分卷文件
				} else {
					$msg=gzwrite_file($dump,$filename);  //写入分卷压缩文件
				}
				if($msg=="") {
					$ActionMessage.="全部数据表-卷$p: 数据备份完成,生成备份文件$filename<br />";
					$p++;
					$dump="";
				} else {
					$ActionMessage.="$msg<br />";
					$error="error";
				}
			}
		}
	} //end for
	
	//写入全部文件
	if ($dump!="" and $error==""){
		if($p>1) {
			$filename=$backup.("_v".$p.$filext);
			$message="$strDataBackupAlert1$p$strDataBackupAlert2$filename";
		} else {
			$filename=$backup.$filext;
			$message=$strDataBackupSuccess;
		}
		
		if ($isgzip==0) {
			$msg=write_file($dump,$filename);  //写入分卷文件
		} else {
			$msg=gzwrite_file($dump,$filename);  //写入分卷压缩文件
		}
		if ($msg==""){
			$ActionMessage.=$message;
		}else{
			$ActionMessage.="$strDataBackupBad";	
		}
	}
}

function convert_character($str){
	if (function_exists('mysql_escape_string')){
		return mysql_escape_string($str);
	}else{
		$str=str_replace("\r","",$str);
		$str=str_replace("\n","",$str);
		$str=str_replace("'","&#39;",$str);
		$str=addslashes($str);
		return $str;
	}
}

//输出头部信息
dohead($strDataBackupTitle,"");
require('admin_menu.php');

if (!function_exists("gzopen")) { 
	$isgzip="0";
	$gzopen="0";
	$strBackupOption=$strBackupOption3;
} else {
	$gzopen="1";
	$isgzip="1";
	$strBackupOption=$strBackupOption1;
}
?>
<script type="text/javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.backup, '<?php echo $strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $strDataBackupTitle?></div>
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
	  <br />
	  <fieldset>
	  <legend>
	  <?php echo $strErrorInfo?>
	  </legend>
	  <div align="center">
		<table border="0" cellpadding="2" cellspacing="1">
		  <tr>
			<td><span class="alertinfo">
			  <?php echo $ActionMessage?>
			  </span></td>
		  </tr>
		</table>
	  </div>
	  </fieldset>
	  <br />
	  <?php  } ?>
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-input">
		  <td width="10%" align="right" class="subcontent-td">
			<input type="radio" name="tables" value="all" checked/>&nbsp;
		  </td>
		  <td width="90%" class="subcontent-td"><?php echo $strDataBackupAll?></td>
		</tr>
		<tr class="subcontent-input">
		  <td align="right" class="subcontent-td">
			<input type="radio" name="tables" value="selected" />&nbsp;
		  </td>
		  <td class="subcontent-td"><?php echo $strDataBackupSelect?></td>
		</tr>
		<tr class="subcontent-input">
		  <td align="right" class="subcontent-td">&nbsp;</td>
		  <td nowrap="nowrap" class="subcontent-td">
			<table border="0" cellpadding="2" cellspacing="1" width="100%">
			<?php 
			$t_rows=count($arrTableName);
			$p_rows=ceil($t_rows/5);

			for ($i=0;$i<$p_rows;$i++){
			?>
			  <tr>
				<td width="10%" nowrap="nowrap">
				  <?php if (!empty($arrTableName[$i*5])){?>
				  <input type="checkbox" name="table[]" value="<?php echo $arrTableName[$i*5]?>" onclick="this.form.tables(1).checked=true" />
				  <?php echo empty($strArrTableTitle[$i*5])?$arrTableName[$i*5]:$strArrTableTitle[$i*5]?>
				  <?php }else{?>
				  &nbsp;
				  <?php }?>
				</td>
				<td width="10%" nowrap="nowrap">
				  <?php if (!empty($arrTableName[$i*5+1])){?>
				  <input type="checkbox" name="table[]" value="<?php echo $arrTableName[$i*5+1]?>" onclick="this.form.tables(1).checked=true" />
				  <?php echo empty($strArrTableTitle[$i*5+1])?$arrTableName[$i*5+1]:$strArrTableTitle[$i*5+1]?>
				  <?php }else{?>
				  &nbsp;
				  <?php }?>
				</td>
				<td width="10%" nowrap="nowrap">
				  <?php if (!empty($arrTableName[$i*5+2])){?>
				  <input type="checkbox" name="table[]" value="<?php echo $arrTableName[$i*5+2]?>" onclick="this.form.tables(1).checked=true" />
				  <?php echo empty($strArrTableTitle[$i*5+2])?$arrTableName[$i*5+2]:$strArrTableTitle[$i*5+2]?>
				  <?php }else{?>
				  &nbsp;
				  <?php }?>
				</td>
				<td width="10%" nowrap="nowrap">
				  <?php if (!empty($arrTableName[$i*5+3])){?>
				  <input type="checkbox" name="table[]" value="<?php echo $arrTableName[$i*5+3]?>" onclick="this.form.tables(1).checked=true" />
				  <?php echo empty($strArrTableTitle[$i*5+3])?$arrTableName[$i*5+3]:$strArrTableTitle[$i*5+3]?>
				  <?php }else{?>
				  &nbsp;
				  <?php }?>
				</td>
				<td width="10%" nowrap="nowrap">
				  <?php if (!empty($arrTableName[$i*5+4])){?>
				  <input type="checkbox" name="table[]" value="<?php echo $arrTableName[$i*5+4]?>" onclick="this.form.tables(1).checked=true" />
				  <?php echo empty($strArrTableTitle[$i*5+4])?$arrTableName[$i*5+4]:$strArrTableTitle[$i*5+4]?>
				  <?php }else{?>
				  &nbsp;
				  <?php }?>
				</td>
			  </tr>
			  <?php }?>
			</table>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td" align="right" nowrap>
			<?php echo $strDataBackupName?>
		  </td>
		  <td class="subcontent-td">
			&nbsp;&nbsp;<input name="backup" type="text" size="45" maxlength="30" value="<?php echo format_time("Y-m-d",time())."-".md5(time())?>">
			&nbsp;&nbsp;&nbsp;
			<?php echo $strDataBackupUnit?>&nbsp;<input name="filesize" type="text" size="3" maxlength="4" value="<?php echo $settingInfo['backupSize']?>"> KB
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td" align="right" nowrap>
			<?php echo $strBackupFileFormat?>
		  </td>
		  <td class="subcontent-td">
			&nbsp;<input type=radio name='isgzip' value="1" <?php echo ($isgzip==1)?"checked":""?> <?php echo ($gzopen=="0")?"disabled":""?>>&nbsp;<?php echo $strBackupOption?>&nbsp;&nbsp;
			<input type=radio name='isgzip' value="0" <?php echo ($isgzip==0)?"checked":""?>>&nbsp;<?php echo $strBackupOption2?>
		  </td>
		</tr>

	  </table>
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strDataBackupBegin?>" onclick="Javascript:onclick_update(this.form)"/>
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php  dofoot(); 

function gzwrite_file ($gzcontent,$gzfilename) {
	global $strDataBackupBad;

	if (is_dir("../backup/")){
		$gzfilename.='.zip';
		$gzfp=gzopen($gzfilename, 'wb9');
		gzwrite($gzfp, $gzcontent);
		gzclose($gzfp);
		$ActionMessage="";
	}else{
		$ActionMessage="$strDataBackupBad";	
	}

	return $ActionMessage;
}
?>
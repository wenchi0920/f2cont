<?
set_time_limit(0);

$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];

$arrTableName=array("logs","categories","comments","dailystatistics","guestbook","setting","keywords","links","members","modsetting","modules","trackbacks","filters","attachments","visits","tags");

if ($action=="save"){
	if ($_POST['tables']=="all"){
		$op_table=$arrTableName;
	}else{
		$op_table=$_POST['table'];
	}
	
	$filesize=($_POST['filesize']=="")?1024:$_POST['filesize'];
	$backup="../backup/".$_POST['backup'];
	$filesize=$filesize*1000;
	$cfg_blog_version=blogVersion;
	//print_r($op_table);
	//开始备份
	$nowdate=format_time("L",time());
	$dump = "-- F2blog SQL Dump \r\n";
	$dump .= "-- version $cfg_blog_version \r\n";
	$dump .= "-- http://www.f2blog.com \r\n";
	$dump .= "--  \r\n";
	$dump .= "-- Created by: $nowdate \r\n";
	$dump .= "-- \r\n";

	$p=1;
	$err="";
	for ($i=0;$i<count($op_table);$i++){
		//表信息
		$result=$DMC->query("SHOW CREATE TABLE ".$DBPrefix.$op_table[$i]);
		$row=$DMC->fetchAssoc();
		unset($rows);

        $dump .= "\r\n\r\n--\r\n";
        $dump .= "-- Table structure for table '".$DBPrefix.$op_table[$i]."'\r\n";
        $dump .= "--\r\n\r\n";
		$dump .= "DROP TABLE IF EXISTS ".$DBPrefix.$op_table[$i]."; \r\n";
        //$dump .= str_replace( "`", "", $row['Create Table'] );
        $dump .= str_replace( "\n", "\r\n", $row['Create Table'] );
        $dump .= ";\r\n\r\n";
        $dump .= "\r\n\r\n--\r\n";
        $dump .= "-- Dumping data for table '".$DBPrefix.$op_table[$i]."'\r\n";
        $dump .= "--\r\n\r\n";  

		//记录
        $result = $DMC->query('SELECT * FROM '.$DBPrefix.$op_table[$i]);
        while ($row = $DMC->fetchArray($result)) {
			//echo count($row)."<br>";
			for ($j=0;$j<count($row)/2;$j++){
                $value = str_replace('"','\\"',$row[$j]);
                $rows[$j] = '"'.$value.'"';
            }
        	$dump .= 'INSERT INTO ' . $DBPrefix.$op_table[$i] . ' VALUES (' . implode(',',$rows) . ");\r\n";

			if(strlen($dump)>=$filesize){
				$filename=$backup.("_v".$p.".sql");
				$msg=write_file($dump,$filename);  //写入分卷文件
				if($msg=="") {
					$ActionMessage.="全部数据表-卷$p: 数据备份完成,生成备份文件$filename<br>";
					$p++;
					$dump="";
				} else {
					$ActionMessage.="$msg<br>";
					$err="error";
				}
			}
		}
	} //end for
	
	//写入全部文件
	if ($dump!="" and $error==""){
		if($p>1) {
			$filename=$backup.("_v".$p.".sql");
			$message="$strDataBackupAlert1$p$strDataBackupAlert2$filename";
		} else {
			$filename=$backup.".sql";
			$message=$strDataBackupSuccess;
		}

		$msg=write_file($dump,$filename);
		if ($msg==""){
			$ActionMessage.=$message;
		}else{
			$ActionMessage.="$strDataBackupBad";	
		}
	}
}

//输出头部信息
dohead($strDataBackupTitle,"");

?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.backup, '<?=$strErrNull?>')) return false;
	
	form.save.disabled = true;
	form.action = "<?="$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform">
  <div id="content">
    <div class="box">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="6" height="20"><img src="images/main/content_lt.gif" width="6" height="21"></td>
          <td height="21" background="images/main/content_top.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rt.gif" width="6" height="21"></td>
        </tr>
        <tr>
          <td width="6" background="images/main/content_left.gif">&nbsp;</td>
          <td bgcolor="#FFFFFF" >
            <div class="contenttitle"><img src="images/content/backup.gif" width="12" height="11">
              <?=$strDataBackupTitle?>
            </div>
            <div class="subcontent">
              <? if ($ActionMessage!="") { ?>
              <br>
              <fieldset>
              <legend>
              <?=$strErrorInfo?>
              </legend>
              <div align="center">
                <table border="0" cellpadding="2" cellspacing="1">
                  <tr>
                    <td><span class="alertinfo">
                      <?=$ActionMessage?>
                      </span></td>
                  </tr>
                </table>
              </div>
              </fieldset>
              <br>
              <? } ?>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="radio" name="tables" value="all" checked/>&nbsp;
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataBackupAll?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">
                    <input type="radio" name="tables" value="selected" />&nbsp;
                  </td>
                  <td width="90%" class="subcontent-td">
                    <?=$strDataBackupSelect?>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" align="right" class="subcontent-td">&nbsp;</td>
                  <td width="90%" nowrap="nowrap" class="subcontent-td">
                    <table border="0" cellpadding="2" cellspacing="1" width="100%">
                      <?
					$t_rows=count($strArrTableTitle);
					$p_rows=ceil($t_rows/5);

					for ($i=0;$i<$p_rows;$i++){
					?>
                      <tr>
                        <td width="10%" nowrap="nowrap">
                          <?if ($arrTableName[$i*5]!=""){?>
                          <input type="checkbox" name="table[]" value="<?=$arrTableName[$i*5]?>" onclick="this.form.tables(1).checked=true" />
                          <?=$strArrTableTitle[$i*5]?>
                          <?}else{?>
                          &nbsp;
                          <?}?>
                        </td>
                        <td width="10%" nowrap="nowrap">
                          <?if ($arrTableName[$i*5+1]!=""){?>
                          <input type="checkbox" name="table[]" value="<?=$arrTableName[$i*5+1]?>" onclick="this.form.tables(1).checked=true" />
                          <?=$strArrTableTitle[$i*5+1]?>
                          <?}else{?>
                          &nbsp;
                          <?}?>
                        </td>
                        <td width="10%" nowrap="nowrap">
                          <?if ($arrTableName[$i*5+2]!=""){?>
                          <input type="checkbox" name="table[]" value="<?=$arrTableName[$i*5+2]?>" onclick="this.form.tables(1).checked=true" />
                          <?=$strArrTableTitle[$i*5+2]?>
                          <?}else{?>
                          &nbsp;
                          <?}?>
                        </td>
                        <td width="10%" nowrap="nowrap">
                          <?if ($arrTableName[$i*5+3]!=""){?>
                          <input type="checkbox" name="table[]" value="<?=$arrTableName[$i*5+3]?>" onclick="this.form.tables(1).checked=true" />
                          <?=$strArrTableTitle[$i*5+3]?>
                          <?}else{?>
                          &nbsp;
                          <?}?>
                        </td>
                        <td width="10%" nowrap="nowrap">
                          <?if ($arrTableName[$i*5+4]!=""){?>
                          <input type="checkbox" name="table[]" value="<?=$arrTableName[$i*5+4]?>" onclick="this.form.tables(1).checked=true" />
                          <?=$strArrTableTitle[$i*5+4]?>
                          <?}else{?>
                          &nbsp;
                          <?}?>
                        </td>
                      </tr>
                      <?}?>
                    </table>
                  </td>
                </tr>
                <tr class="subcontent-input">
                  <td width="10%" class="subcontent-td" align="right" nowrap>
                    <?=$strDataBackupName?>
                  </td>
                  <td width="90%" class="subcontent-td">
                    &nbsp;<input name="backup" type="text" size="50" maxlength="30" value="<?=format_time("Y-m-d",time())."-".md5(time())?>">
					&nbsp;&nbsp;&nbsp;<?=$strDataBackupUnit?>&nbsp;<input name="filesize" type="text" size="10" value="<?=$backup_size?>"> KB</td>
                  </td>
                </tr>
              </table>
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strDataBackupBegin?>" onClick="Javascript:onclick_update(this.form)">
            </div>
          </td>
          <td width="6" background="images/main/content_right.gif">&nbsp;</td>
        </tr>
        <tr>
          <td width="6" height="20"><img src="images/main/content_lb.gif" width="6" height="20"></td>
          <td height="20" background="images/main/content_bottom.gif">&nbsp;</td>
          <td width="6" height="20"><img src="images/main/content_rb.gif" width="6" height="20"></td>
        </tr>
      </table>
    </div>
  </div>
</form>
<? dofoot(); ?>

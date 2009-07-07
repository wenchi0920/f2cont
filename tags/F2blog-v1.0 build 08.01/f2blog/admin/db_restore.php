<?
set_time_limit(0);
$PATH="./";
include("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//保存参数
$action=$_GET['action'];

function restore_file($filename) {
	global $strDataRestoreNoFile,$DMC,$strDataRestoreBad,$strDataRestoreSuccess;

	if (!$fp = fopen($filename,'rb')){
        $ActionMessage="$strDataRestoreNoFile";
    } else{
        $filesize       = filesize($filename);
        $file_position  = isset($HTTP_GET_VARS['pos']) ? $HTTP_GET_VARS['pos'] : 0;
        $errors         = isset($HTTP_GET_VARS['ignore_errors']) ? 0 : 1;

        $buffer = '';
        $inside_quote = 0;
        $quote_inside = '';
        $started_query = 0;

        $data_buffer = '';
        $last_char = "\n";

        // Sets file position indicator
        fseek($fp,$file_position);
        while ((!feof($fp) || strlen($buffer))) {
            do {
                // Deals with the length of the buffer
                if (!strlen($buffer)) {
                    $buffer .= fread ($fp,1024);
                }

                // Fiddle around with the buffers
                $current_char = $buffer[0];
                $buffer = substr($buffer, 1);

                if ($started_query) {
                    $data_buffer .= $current_char;
                } elseif (preg_match("/[A-Za-z]/i",$current_char) && $last_char == "\n") {
                    $started_query = 1;
                    $data_buffer = $current_char;
                }else  {
                    $last_char = $current_char;
                }
            } while (!$started_query && (!feof($fp) || strlen($buffer)));


            if ($inside_quote && $current_char == $quote_inside && $last_char != '\\') {
                $inside_quote = 0;
            } 
            elseif ($current_char == '\\' && $last_char == '\\'){
                $current_char = '';	
            } 
            elseif (!$inside_quote && ($current_char == '"' || $current_char == '`' || $current_char == '\'')){
                $inside_quote = 1;
                $quote_inside = $current_char;
            } elseif (!$inside_quote && $current_char == ';') {
				if (strpos($data_buffer,"TABLE IF EXISTS")>0){
					$name=substr($data_buffer,21,strlen($data_buffer)-22);
					$tablename[]=$name;
					$tableError[$name]=0;
					$tableOk[$name]=0;
				}
				if (mysql_get_server_info()<4.1){
					$data_buffer=str_replace("ENGINE=MyISAM DEFAULT CHARSET=utf8","TYPE=MyISAM",$data_buffer);
				}else{
					$data_buffer=str_replace("TYPE=MyISAM","ENGINE=MyISAM DEFAULT CHARSET=utf8",$data_buffer);
				}

				if (!$DMC->query($data_buffer)){
					$ActionMessage=$strDataRestoreBad;
					if (strpos($data_buffer,"INTO $name")>0){$tableError[$name]++;}
                }else{
					$ActionMessage=$strDataRestoreSuccess;
					if (strpos($data_buffer,"INTO $name")>0){$tableOk[$name]++;}
				}

                $data_buffer = '';
                $last_char = "\n";
                $started_query = 0;
            }
            $last_char = $current_char;
        }
	}
    fclose($fp);
	
	$filename=str_replace("../backup/","",$filename);
	$filename=str_replace(".sql","",$filename);
	$ActionMessage="$filename ==> ".$ActionMessage;
	return $ActionMessage;
}

if ($action=="save"){
	$filename = $_REQUEST['restorefile'];
	$ActionMessage=restore_file("../backup/".$filename.".sql");
	
	if(strpos($filename,"_v")>0) {
		$prev=intval(substr($filename,-1,1));
		$next=$prev+1;
		$nextfile=str_replace("_v".$prev,"_v".$next,$filename);

		if(file_exists("../backup/".$nextfile.".sql")) {
			@header("Content-Type: text/html; charset=utf-8");
			echo "<span style='font-size:14px;color:blue'>$ActionMessage<br><br>$strDataRestoreAlert1".$nextfile.".sql$strDataRestoreAlert2</span>";
			sleep(3);
			echo "<script language='javascript'>"; 
			echo "location='db_restore.php?action=save&restorefile=$nextfile';"; 
			echo "</script>";
		} else {
			$ActionMessage.="<br><br>$strDataRestoreTotal$strDataRestoreSuccess";	
		}
	} 
}

if($action=="delete") {
	$filename = str_replace("_v1","",$_REQUEST['restorefile']);
	$basedir="../backup/";
	
	if(file_exists($basedir.$filename.".sql")) {
		@unlink($basedir.$filename.".sql");
	} else {
		for($i=1;$i<30;$i++){
			$nextfile=$basedir.$filename."_v".$i.".sql";
			if(file_exists($nextfile)) {
				@unlink($nextfile);
			} else {
				break;
			}
		}
	}

	$ActionMessage.="$strDeleteSuccess";
	$action="";
}

//下载文件
if ($action=="download"){
	$file_name=$_GET['filename'].".sql";
	$file_path="../backup/$file_name";
	//echo $file_name."+++".$file_path;
	if (file_exists($file_path)){
		$file_handle=fopen($file_path,"r");
		$file_size=filesize($file_path);
		$temp_buffer=fread($file_handle,$file_size);
		fclose($file_handle);
			
		header("Content-type: application/zip");
		header("Content-disposition: attachment; filename=$file_name");
		echo $temp_buffer;
		exit;
	} else {
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$strFileNoExists\");";
		echo "</script>";
	}
	$action="";
}

//输出头部信息
dohead($strDataRestoreTitle,"");

//读取备份文件
$basedir="../backup/";
$handle=opendir("$basedir"); 
while ($file = readdir($handle)){ 
    if(is_file($basedir.$file) && strpos($basedir.$file,".sql")>0){
		$name=substr($file,0,strlen($file)-4);
		if(strpos($name,"_v1")>0 or !strpos($name,"_v")) {
			if(!strpos($name,"_v")) {
				$data_file[] = $name;
				$data_size[]=formatFileSize(filesize($basedir.$file));
				$data_date[]=filemtime($basedir.$file);
				$data_totUnit[]=0;
			} else {
				$data_file[] = $name;
				$data_date[]=filemtime($basedir.$file);

				$totUnit=1;
				$totSize=filesize($basedir.$name.".sql");;
				for($i=0;$i<30;$i++) {
					$prev=intval(substr($name,-1,1));
					$next=$prev+1;
					$nextfile=str_replace("_v".$prev,"_v".$next,$name);
					
					if(file_exists($basedir.$nextfile.".sql")) {
						$totUnit=$totUnit+1;
						$totSize=$totSize+filesize($basedir.$nextfile.".sql");
						$name=$nextfile;
					} else {
						break;
					}
				}

				$data_totUnit[]=$totUnit;
				$data_size[]=formatFileSize($totSize);
			}
		}
	}
} 
closedir($handle);

?>

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
            <div class="contenttitle"><img src="images/content/restore.gif" width="12" height="11">
              <?=$strDataRestoreTitle?>
            </div>
            <div class="subcontent">
              <? if ($ActionMessage!="") { ?>
              <br>
              <fieldset>
              <legend>
              <?=$strErrorInfo?>
              </legend>
              <div align="center"><font color=red>
				<?
					echo $ActionMessage;
				?></font>
              </div>
              </fieldset>
              <br>
              <? } ?>
			  <?if ($action==""){?>
			  <br>
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr class="subcontent-title">
                  <td width="5%" class="whitefont">&nbsp;</td>
                  <td width="40%" class="whitefont"><?=$strAttachmentsName?></td>
				  <td width="10%" class="whitefont"><?=$strDataRestoreTotUnit?></td>
				  <td width="15%" class="whitefont"><?=$strAttachmentsSize?></td>
				  <td width="15%" class="whitefont"><?=$strAttachmentsDate?></td>
				  <td width="20%" class="whitefont" align="center"><?=$strDownFile?></td>
                </tr>
				<?
				for ($i=count($data_file)-1;$i!=-1;$i--){
				?>
                <tr class="subcontent-input">
                  <td class="subcontent-td" align="center">
                    <INPUT TYPE="radio" NAME="restorefile" value="<?=$data_file[$i]?>" <?if ($i==count($data_file)-1){echo "checked";}?>>&nbsp;
                  </td>
                  <td class="subcontent-td"><?=str_replace("_v1","",$data_file[$i])?></td>
				  <td class="subcontent-td" align="center"><?=($data_totUnit[$i]==0)?"&nbsp;":$data_totUnit[$i]?></td>
				  <td class="subcontent-td"><?=$data_size[$i]?></td>
				  <td class="subcontent-td"><?=format_time("L",$data_date[$i])?></td>
				  <td class="subcontent-td" align="center">
				  <?
				  if($data_totUnit[$i]>0) {
					  for($j=1;$j<=$data_totUnit[$i];$j++) {
						  $name=str_replace("_v1","_v".$j,$data_file[$i]);
						  if($j>1) { echo "&nbsp;&nbsp;&nbsp;"; }
						  echo "<a href=\"$PHP_SELF?action=download&filename=$name\" title=\"$name.sql\"><img src=\"../images/download.gif\" border=\"0\"></a>";
					  }
				  } else {
				  ?>
					  <a href="<?="$PHP_SELF?action=download&filename=".$data_file[$i]?>"><img src="../images/download.gif" border="0"></a>
				  <? } ?>
				  </td>
                </tr>
				<?}?>
              </table>			  
            </div>
            <br>
            <div class="bottombar-onebtn">
              <input name="save" class="btn" type="button" id="save" value="<?=$strDataRestoreBegin?>" onclick="ConfirmDataOperation('<?="$PHP_SELF?action=save"?>','<?=$strDataRestoreConfirm?>');">
			  &nbsp;
              <input name="del" class="btn" type="button" id="del" value="<?=$strDelete?>" onclick="ConfirmDataOperation('<?="$PHP_SELF?action=delete"?>','<?=$strConfirmInfo?>');">
            </div>
			<?}?>
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

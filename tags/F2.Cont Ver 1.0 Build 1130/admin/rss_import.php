<?php 
@set_time_limit(0);

require_once("function.php");

//必须在本站操作
$server_session_id=md5("rssimport".session_id());
if (($_GET['action']=="save" || $_GET['action']=="operation") && $_POST['client_session_id']!=$server_session_id){
	die ('Access Denied.');
}

// 验证用户是否处于登陆状态
check_login();
$parentM=7;
$mtitle=$strRssImport;

//输出头部信息
dohead($strRssImport,"");
require('admin_menu.php');

if ($_GET['action']=="save") {
	$autocate=$_POST['autocate'];
	$curtime=$_POST['curtime'];
	$rss_file=$_FILES["rssfile"]["tmp_name"];
	$fileName=$_FILES["rssfile"]["name"];
	$filetype=getFileType($fileName);
	$path="../backup";			
	$rssname=upload_rssfile($rss_file,$path,$filetype);

	if (strstr($rssname,".gz")) {
		$rsscontent=gzreadfromfile("../backup/$rssname");
	} else {
		$rsscontent=readfromfile("../backup/$rssname");
	}

	$rssCate=($autocate==0)?$_POST['rssCate']:"";
	$array_insert=rssAnalyse($rsscontent,$rssCate,$curtime);
	if (is_array($array_insert)) {
		foreach ($array_insert as $arr) {
			$posttime=($curtime==1)?time():$arr['posttime'];
			$arr['content']=str_replace("'","&#39;",$arr['content']);
			$sql="INSERT INTO {$DBPrefix}logs(cateId,logTitle,logContent,author,postTime,isComment,isTrackback,isTop,weather,saveType,logsediter) VALUES ('{$arr['category']}','{$arr['title']}','{$arr['content']}','{$_SESSION['username']}','$posttime','1','1','0','sunny','1','tiny')";
			$DMC->query($sql);
		}
	}

	//更新Cache
	settings_recount();
	settings_recache();
	categories_recount();
	categories_recache();				
	calendar_recache();
	archives_recache();
	recentLogs_recache();
	logsTitle_recache();
	modules_recache();
	
	$rssCate="";
	$ActionMessage=$strRssImportOption7;	
}
?>
<script style="javascript">
<!--
function onclick_update(form) {	
	if (isNull(form.rssfile, '<?php echo $strRssImportOption1?>')) return false;
	
	var path=form.rssfile.value;
    var pos=path.lastIndexOf(".");
    var filecase=path.substr(pos+1,3);
	if (filecase!="gz" && filecase!="xml") {
		alert("<?php echo $strRssImportOption2?>");
		form.rssfile.focus();
		return false;
	}

	<?php  if (!function_exists("gzopen")) { ?>
		if (filecase=="gz") {
			alert("<?php echo $strBackupOption3?>");
			form.rssfile.focus();
			return false;
		}
	<?php  } ?>

	if (form.autocate[1].checked==true && form.rssCate.value=="") {
		alert("<?php echo $strRssImportOption5?>");
		form.rssCate.focus();
		return false;
	}

	form.save.disabled = true;
	form.action = "<?php echo "$PHP_SELF?action=save"?>";
	form.submit();
}
-->
</script>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <div id="content">

	<div class="contenttitle"><?php echo $strRssImport?></div>
	<div class="subcontent">
	  <?php  if ($ActionMessage!="") { ?>
	  <br>
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
	  <br>
	  <?php  } ?>

	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-input">
		  <td class="subcontent-td">
			&nbsp;&nbsp;<input type=radio name='autocate' value="1" checked>&nbsp;<?php echo $strRssImportOption3?>
			&nbsp;&nbsp;<input type=radio name='autocate' value="0">&nbsp;<?php echo $strRssImportOption4?>
			&nbsp;<?php  category_select("rssCate","","class=\"searchbox\" onchange=\"seekform.elements['autocate'][1].checked=true;\"","rss"); ?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td">
			&nbsp;&nbsp;<input type=radio name='curtime' value="0" checked>&nbsp;<?php echo $strRssImportOption8?>
			&nbsp;&nbsp;<input type=radio name='curtime' value="1">&nbsp;<?php echo $strRssImportOption9?>
		  </td>
		</tr>
		<tr class="subcontent-input">
		  <td class="subcontent-td">
			&nbsp;&nbsp;<?php echo $strRssImportOption1?>&nbsp;&nbsp;<input name="rssfile" class="filebox" type="file" size="50"/>
			&nbsp;&nbsp;(<?php echo $strRssImportOption2?>)
		  </td>
		</tr>
	  </table>
	</div>
	<br>
	<div class="bottombar-onebtn">
	  <input name="save" class="btn" type="button" id="save" value="<?php echo $strRssImport?>" onClick="Javascript:onclick_update(this.form)">
	  <input name="client_session_id" type="hidden" value="<?php echo $server_session_id?>">
	</div>
  </div>
</form>
<?php  
dofoot(); 

function rssAnalyse($data,$rssCate) {
	$data=str_replace("\r", '', $data);
	$data=str_replace("\n", '', $data);
	$data=str_replace("<![CDATA[", '', $data);
	$data=str_replace("]]>", '', $data);
	preg_match_all("/<item>(.+?)<\/item>/is", $data, $array_match);
	$xmlall=$array_match[1];
	if (!is_array($xmlall)) {
		$array_insert[]=parserss($xmlall,$rssCate);
	} else {
		foreach ($xmlall as $xml) {
			$array_insert[]=parserss($xml,$rssCate);
		}
	}
	return $array_insert;
}

function parserss ($xml,$rssCate) {
	global $DMC, $DBPrefix;
	$count_items=preg_match("/<pubDate>(.+?)<\/pubDate>/is", $xml, $array_date);
	$count_items1=preg_match("/<description>(.+?)<\/description>/is", $xml, $array_desc);
	$count_items2=preg_match("/<title>(.+?)<\/title>/is", $xml, $array_title);
	$count_items3=preg_match("/<category>(.+?)<\/category>/is", $xml, $array_cate);
	
	if ($count_items!=0) {
		$title=addslashes($array_title[1]);
		if ($rssCate=="") {
			$catetitle=addslashes($array_cate[1]);
			$cateid=getFieldValue($DBPrefix."categories","name='$catetitle'","id");
			if ($cateid=="") { $cateid=addCategory($catetitle); }
			$category=$cateid;
		} else {
			$category=$rssCate;
		}

		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}/i", $array_date[1]) or strstr($array_date[1],"+0000")) {
			$posttime=str_to_time($array_date[1]);
		} else {
			$posttime=strtotime($array_date[1]);
		}
		$content=addslashes($array_desc[1]);
		//echo $array_date[1]."  ===>   ".format_time("L",$posttime)."<br>";
	}
	
	return array('title'=>$title, 'category'=>$category, 'posttime'=>$posttime, 'content'=>$content);
}

function addCategory($name) {
	global $DMC, $DBPrefix;

	$orderno=getFieldValue($DBPrefix."categories","parent='0' order by orderNo desc","orderNo");
	if ($orderno<1){
		$orderno=1;
	}else{
		$orderno++;
	}
	$sql="INSERT INTO {$DBPrefix}categories(parent,name,orderNo,isHidden,cateIcons) VALUES ('0','$name','$orderno','0','1')";
	$DMC->query($sql);
	return $DMC->insertId();
}

function str_to_time($timestamp){
	global $settingInfo;
	
	$timestamp=strtotime($timestamp);
	if(PHP_VERSION>4){
		$offset = $settingInfo['timezone'];
		$timestamp=$timestamp-$offset*3600;
	}

	return $timestamp;
}

function gzreadfromfile ($gzfilename) {
   $file = gzopen($gzfilename, 'rb');
   if ($file) {
       $data = '';
       while (!gzeof($file)) {
           $data .= gzread($file, 1024);
       }
       gzclose($file);
   }
   return $data;
}

function upload_rssfile($temp_file,$dir,$type) {
	$tmp_name=time();
	$return="";
	
	if ($temp_file!="") {
		if (check_dir($dir)) {
			$return=$tmp_name.".".$type;
			$file_path="$dir/$return";					
			$copy_result=copy($temp_file,"$file_path");
		}
	}

	return $return;
}
?>
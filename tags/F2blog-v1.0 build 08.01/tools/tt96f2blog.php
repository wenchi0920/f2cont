<?php
set_time_limit(0);
session_start();
header("Content-Type: text/html; charset=utf-8");

function encode($string) {
	$string=trim($string);
	//$string=stripSlashes("$string");
	
	$string=str_replace("&","&amp;",$string);
	$string=str_replace("'","&#39;",$string);
	$string=str_replace("&amp;amp;","&amp;",$string);
	$string=str_replace("&amp;quot;","&quot;",$string);
	$string=str_replace("\"","&quot;",$string);
	$string=str_replace("&amp;lt;","&lt;",$string);
	$string=str_replace("<","&lt;",$string);
	$string=str_replace("&amp;gt;","&gt;",$string);
	$string=str_replace(">","&gt;",$string);
	$string=str_replace("&amp;nbsp;","&nbsp;",$string);

	$string=nl2br($string);
	return $string;
}

function codeconvert($string){
	$string=trim($string);
	$string=preg_replace("/\[html\](.+?)\[\/html\]/is","\\1",$string);	
	$string=str_replace("&nbsp;","&amp;nbsp;",$string);
	$string=str_replace("<","&lt;",$string);
	$string=str_replace(">","&gt;",$string);
	$string=str_replace("  ","&nbsp;&nbsp;",$string);
	$string=str_replace("\"","&quot;",$string);	
	return $string;
}

function convertquot($string){
	$string=str_replace("&#39;","\"",$string);
	return $string;
}

if ($_GET['step']>4){$_GET['step']=4;}

//测试连接
if ($_GET['step']==2){
	if ($_POST['host']=="" || $_POST['name']=="" || $_POST['user']=="" || $_POST['pass']=="" || $_POST['prefix']==""){ 
		$step_result=false;
	}else{
		$_SESSION['host']=$_POST['host'];
		$_SESSION['name']=$_POST['name'];
		$_SESSION['user']=$_POST['user'];
		$_SESSION['pass']=$_POST['pass'];
		$_SESSION['prefix']=$_POST['prefix'];
	}
}

//如果没有数据库设定，则转到数据库设定
if ($_GET['step']>1){
	if ($_SESSION['host']=="" || $_SESSION['name']=="" || $_SESSION['user']=="" || $_SESSION['pass']=="" || $_SESSION['prefix']==""){ 
		$_GET['step']=1;
	}else{
		$mysql_conn=@mysql_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
		@mysql_select_db($_SESSION['name'],$mysql_conn);
		@mysql_query("set names 'utf8'");
		@mysql_query("select no from t3_{$_SESSION['prefix']}_ct1 limit 0,1");
		$error=mysql_error();
		if ($error!=""){
			$step_result=false;
			$_GET['step']=2;
		}else{
			$step_result=true;
		}
	}
}

if ($_GET['step']==4){
	$_SESSION['host']="";
	$_SESSION['name']="";
	$_SESSION['user']="";
	$_SESSION['pass']="";
	$_SESSION['prefix']="";
}

if ($_GET['step']==3){
	if (count($_POST['chkData'])<1){
		$step_result=false;
	}else{
		$step_result=true;
		$table=";;".implode(";",$_POST['chkData']).";";
	}
	
	if ($step_result){
		if (strpos($table,";categories;")>0){
			//转换类别
			$contents = "\$categories = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_ct1 order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$arr_result['parent']=0;
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_result['no']."',\n\t\t'parent' => '".$arr_result['parent']."',\n\t\t'name' => '".encode($arr_result['label'])."',\n\t\t'orderNo' => '".$arr_result['sortno']."',\n\t\t'cateTitle' => '".encode($arr_result['label'])."',\n\t\t'cateCount' => '".$arr_result['cnt']."',\n\t\t'isHidden' => '0'),\n";
				$i++;
				$id=$arr_result['no'];
			}
			$id=$id+1;
			$query="select * from t3_".$_SESSION['prefix']."_ct2 order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$id."',\n\t\t'parent' => '".$arr_result['pno']."',\n\t\t'name' => '".encode($arr_result['label'])."',\n\t\t'orderNo' => '".$arr_result['sortno']."',\n\t\t'cateTitle' => '".encode($arr_result['label'])."',\n\t\t'cateCount' => '".$arr_result['cnt']."',\n\t\t'isHidden' => '0'),\n";
				
				$arr_category[$arr_result['no'].$arr_result['pno']]=$id;
				$id++;
				$i++;
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_categories=$i;
		}

		if (strpos($table,";logs;")>0){
			//转换日志
			$contents .= "\$logs = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']." order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){						
				$saveType=($arr_result['is_public']>0)?1:0;

				$image_file_path1=$arr_result['image_file_path1'];
				$image_file_path2=$arr_result['image_file_path2'];
				$f2blog_file_path="../attachments/1/".$image_file_path1.$image_file_path2;

				//替换附件
				$content=$arr_result['body'];
				$content=str_replace("&quot;","\"",$content);
				$content=str_replace("&amp;quot;","\"",$content);				
				$content=str_replace("'","&#39;",$content);
				$content=preg_replace("/(<img.*?>)/ie","convertquot('\\1')",$content);
				$content=preg_replace("/(<a href.*?>)/ie","convertquot('\\1')",$content);
				
				//$pattern = "/([^\/\"\'\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \r\n\<\"\'\)]+)/";
				//$content = preg_replace($pattern, "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $content);

				$content=str_replace("[##_ATTACH_PATH_##]","../attachments/1/",$content);
				$content=preg_replace("/\[##_1L\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"left\"><img src=\"../attachments/1/\\1\" alt=\"open_img(&#39;../attachments/1/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
				$content=preg_replace("/\[##_1R\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"right\"><img src=\"../attachments/1/\\1\" alt=\"open_img(&#39;../attachments/1/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
				$content=preg_replace("/\[##_1C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><img src=\"../attachments/1/\\1\" alt=\"open_img(&#39;../attachments/1/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
				$content=preg_replace("/\[##_2C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><table border=\"0\" cellspacing=\"5\"><tbody><tr><td><img src=\"../attachments/1/\\1\" alt=\"open_img(&#39;../attachments/1/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></td><td><img src=\"../attachments/1/\\6\" alt=\"open_img(&#39;../attachments/1/\\6&#39;)\" width=\"\\7\" height=\"\\8\" /></td></tr></tbody></table></p>",$content);
				$content=preg_replace("/\[##_3C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><table border=\"0\" cellspacing=\"5\"><tbody><tr><td><img src=\"../attachments/1/\\1\" alt=\"open_img(&#39;../attachments/1/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></td><td><img src=\"../attachments/1/\\6\" alt=\"open_img(&#39;../attachments/1/\\6&#39;)\" width=\"\\7\" height=\"\\8\" /></td><td><img src=\"../attachments/1/\\11\" alt=\"open_img(&#39;../attachments/1/\\11&#39;)\" width=\"\\12\" height=\"\\13\" /></td></tr></tbody></table></p>",$content);
				$content=preg_replace("/\[##_Gallery\|(.+?)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?(.*?)_##\]/is","<p align=\"center\"><!--galleryBegin-->\\1,\\2,\\3<!--galleryEnd--></p>",$content);
				$content=preg_replace("/\[##_iMazing\|(.+?)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><!--galleryBegin-->\\1,\\2,\\3<!--galleryEnd--></p>",$content);
				$content=preg_replace("/\[##_1L\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"left\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/1/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/1/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
				$content=preg_replace("/\[##_1R\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"right\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/1/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/1/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
				$content=preg_replace("/\[##_1C\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"center\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/1/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/1/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
				$content=preg_replace("/\[##_1L\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"left\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
				$content=preg_replace("/\[##_1R\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"right\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
				$content=preg_replace("/\[##_1C\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"center\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
				$content=preg_replace("/\[##_Jukebox\|([0-9]+.mp3)\|(.*?)\|_##\]/is","<p align=\"center\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
				$content=preg_replace("/\[#M_(.*?)\|(.*?)\|(.*?)_M#\]/is","<!--hideBegin-->\\3<!--hideEnd-->",$content);
				$content=preg_replace("/\[#M_(.*?)\|(.*?)\|(.*?)_M#\]/is","\\3",$content);
				$content=str_replace("[#M_","",$content);
				$content=str_replace("_M#]","",$content);
				$content=preg_replace("/{{(.*?)}}/is","<img src=\"../attachments/1/\\1.gif\">",$content);
				$content=preg_replace("/\/?(smiles)/is","../attachments/1/\\1",$content);

				//转换HTML或CODE代码			
				$content=str_replace("\n","",$content);
				$content=preg_replace("/\[code\](.+?)\[\/code\]/ie","codeconvert('\\1')",$content);
				$content=preg_replace("/\[html\](.+?)\[\/html\]/ie","codeconvert('\\1')",$content);
				$content=str_replace("[CODE]","",$content);
				$content=str_replace("[/CODE]","",$content);
				$content=str_replace("[HTML]","",$content);
				$content=str_replace("[/HTML]","",$content);
				
				$content=str_replace("\&quot;","&quot;",$content);
				$content=str_replace("\\\"","\"",$content);
				$content=nl2br($content);
				//$content=addslashes($content);
				//$content=encode($content);
				//$content=htmlspecialchars($content);
				//echo $content."<br>";
				$category=($arr_result['category2']==0)?$arr_result['category1']:$arr_category[$arr_result['category2'].$arr_result['category1']];

				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_result['no']."',\n\t\t'cateId' => '".$category."',\n\t\t'author' => '".$arr_result['user_id']."',\n\t\t'logTitle' => '".encode($arr_result['title'])."',\n\t\t'logContent' => '".$content."',\n\t\t'postTime' => '".$arr_result['regdate']."',\n\t\t'isComment' => '".$arr_result['perm_rp']."',\n\t\t'isTrackback' => '".$arr_result['perm_tb']."',\n\t\t'commNums' => '".$arr_result['rp_cnt']."',\n\t\t'quoteNums' => '".$arr_result['tb_cnt']."',\n\t\t'saveType' => '$saveType',\n\t\t'tags' => ''),\n";
				$i++;				
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_blog=$i;
		}
		
		if (strpos($table,";guestbook;")>0){
			//转换留言簿
			$contents .= "\$guestbook = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_guest order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_result['no']."',\n\t\t'parent' => '0',\n\t\t'content' => '".encode($arr_result['body'])."',\n\t\t'author' => '".encode($arr_result['name'])."',\n\t\t'postTime' => '".$arr_result['regdate']."',\n\t\t'ip' => '".$arr_result['ip']."',\n\t\t'homepage' => '".encode($arr_result['homepage'])."'),\n";
				$id=$arr_result['no'];
				$i++;
			}
			$id=$id+1;
			$query="select * from t3_".$_SESSION['prefix']."_guest_reply order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$id."',\n\t\t'parent' => '".$arr_result['pno']."',\n\t\t'content' => '".encode($arr_result['body'])."',\n\t\t'author' => '".encode($arr_result['name'])."',\n\t\t'postTime' => '".$arr_result['regdate']."',\n\t\t'ip' => '".$arr_result['ip']."',\n\t\t'homepage' => '".encode($arr_result['homepage'])."'),\n";
				$i++;		
				$id++;
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_guestbook=$i;
		}

		if (strpos($table,";comments;")>0){
			//转换评论
			$contents .= "\$comments = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_reply order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_result['no']."',\n\t\t'parent' => '0',\n\t\t'logId' => '".$arr_result['pno']."',\n\t\t'content' => '".encode($arr_result['body'])."',\n\t\t'author' => '".$arr_result['name']."',\n\t\t'postTime' => '".$arr_result['regdate']."',\n\t\t'ip' => '".$arr_result['ip']."',\n\t\t'isSecret' => '".$arr_result['is_secret']."',\n\t\t'homepage' => '".encode($arr_result['homepage'])."'),\n";
				$i++;				
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_comments=$i;
		}

		if (strpos($table,";links;")>0){
			//友情链接
			$contents .= "\$links = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_link order by no";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_result['no']."',\n\t\t'name' => '".encode($arr_result['title'])."',\n\t\t'orderNo' => '".$i."',\n\t\t'blogUrl' => '".encode($arr_result['link'])."'),\n";
				$i++;
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_links=$i;
		}

		if (strpos($table,";attachments;")>0){
			//附件
			$contents .= "\$attachments = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_files";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'logId' => '".$arr_result['parent']."',\n\t\t'name' => '1/".$arr_result['attachname']."',\n\t\t'attTitle' => '".encode($arr_result['filename'])."',\n\t\t'fileType' => '',\n\t\t'fileSize' => '".$arr_result['filesize']."',\n\t\t'fileWidth' => '".$arr_result['width']."',\n\t\t'fileHeight' => '".$arr_result['height']."',\n\t\t'downloads' => '0',\n\t\t'postTime' => '".$arr_result['regdate']."'),\n";
				$i++;				
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_attachments=$i;
		}

		if (strpos($table,";trackbacks;")>0){
			//引用
			$contents .= "\$trackbacks = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_trackback";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$contents.="\t'".$i."' => array(\n\t\t'logId' => '".$arr_result['pno']."',\n\t\t'blogUrl' => '".encode($arr_result['url'])."',\n\t\t'postTime' => '".$arr_result['regdate']."',\n\t\t'tbTitle' => '".encode($arr_result['title'])."',\n\t\t'blogSite' => '".$arr_result['site']."',\n\t\t'ip' => '".$arr_result['ip']."',\n\t\t'content' => '".encode($arr_result['body'])."'),\n";
				$i++;				
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_trackbacks=$i;
		}

		if (strpos($table,";dailystatistics;")>0){
			//日志
			$contents .= "\$dailystatistics = array(\r\n";
			$i=0;
			$query="select * from t3_".$_SESSION['prefix']."_count";
			$result=mysql_query($query); //or die(mysql_error());
			while ($arr_result=mysql_fetch_array($result)){
				$date=$arr_result['set_date'];
				$visitDate=substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2);
				$contents.="\t'".$i."' => array(\n\t\t'visitDate' => '".$visitDate."',\n\t\t'visits' => '".$arr_result['count']."'),\n";
				$i++;				
			}
			$contents .= ");\n\n\n";
			//print_r($contents);
			$result_dailystatistics=$i;
		}

		//写入文件
		$datafile="tt_data.dat";
		if(@$fp = fopen($datafile, 'w')) {
			@fwrite($fp, "<?php\r\n//TT 0.96 Data file\r\n \r\n\r\n".$contents."\r\n\r\n?>");
			$result_data=true;
			@fclose($fp);
		} else {
			$result_data=false;
		}	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TT 0.96 数据转换为F2blog数据</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
  TABLE, TR, TD                   { font-family: Verdana,Arial; font-size: 12px; color: #333333 }
  BODY                            { font: 12px Verdana; background-color: #FCFCFC; padding: 0; margin: 0 }
  a:link, a:visited, a:active     { color: #000055 }
  a:hover                         { color: #333377; text-decoration: underline }
  FORM                            { padding: 0; margin: 0 }

  .textbox                        { border: 1px solid black; padding: 1px; width: 100% }
  .headertable                    { background-color: #FFFFFF; border: 1px solid black; padding: 2px }
  .title                          { font-size: 12px; font-weight: bold; line-height: 150%; color: #FFFFFF; height: 26px; background-image: url(./tile_back.gif) }
  .table1                         { background-color: #FFFFFF; width: 100%; align: center; border: 1px solid black }
  .tablewrap                      { border: 1px dashed #777777; background-color: #F5F9FD; vertical-align: middle; }
  .tdrow1                         { background-color: #EEF2F7; padding: 3px }
  .tdrow2                         { background-color: #F5F9FD; padding: 3px }
  .tdtop                          { font-weight: bold; height: 24px; line-height: 150%; color: #FFFFFF; background-image: url(./tile_back.gif) }
  .note                           { margin: 10px; padding: 5px; border: 1px dashed #555555; background-color: #FFFFFF }
.STYLE1 {color: #FF0000}
</style>
<script style="javascript">
<!--
function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

function onclick_step(form,step) {
	<?if ($_GET['step']==1){?>
	if (step==2){
		if (isNull(form.host, '请输入mysql主机名称')) return false;
		if (isNull(form.name, '请输入TT数据库名')) return false;
		if (isNull(form.user, '请输入TT用户名')) return false;
		if (isNull(form.pass, '请输入TT密码')) return false;
		if (isNull(form.prefix, '请输入数据库前辍')) return false;
	}
	<?}?>
	form.step_prev.disabled = true;
	form.step_next.disabled = true;
	form.action = "<?=$_SERVER['PHP_SELF']."?step="?>"+step;
	form.submit();
}
-->
</script>
</head>
<body>
<form name="convert" method="post" action="<?=$_SERVER['PHP_SELF']?>">

<?if (!isset($_GET['step']) || $_GET['step']<1){?> 
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 0.96 数据转换为F2blog数据</td>
    </tr>
    <tr>
      <td>
        <div class="note">序言: 转换程式声明</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2">&nbsp;&nbsp;&nbsp;&nbsp;使用之前，请先阅读本说明。<br />
              <br />
&nbsp;&nbsp;&nbsp;&nbsp;该程序只是把 <span class="STYLE1">TT 0.96</span> 中的数据表按照一定的字段对应关系转换成F2blog可以使用的数据。然后通过f2blog.php通用程式汇入到f2blog中，汇入过程中将删除F2blog数据库原有的类别、日志、留言簿、评论、友情链接、引用、附件、访问日志等数据表的数据，故建议在安装f2blog后马上使用该程式把TT数据转换到f2blog中。
<p>&nbsp;&nbsp;&nbsp;&nbsp;该操作将分四步完成。 </p>
            </td>
          </tr>
          <tr>
            <td class="tdrow2">
              <div align="center">
			    <input type="hidden" name="step_prev" value="">
                <input type="button" name="step_next" value="下一步(TT数据库设置)" onclick="onclick_step(this.form,'1')">
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
<?}?>  

<?if ($_GET['step']==1){?> 
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 数据转成 F2blog 程序</td>
    </tr>
    <tr>
      <td>
        <div class="note">第一步: TT 数据库设置 (1/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td width="100" class="tdrow1"><strong>服务器</strong></td>
            <td width="350" class="tdrow2">
              <input type="text" class="textbox" name="host" value="localhost">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库名称</strong></td>
            <td class="tdrow2">
              <input type="text" name="name" class="textbox" value="tt0">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库用户名</strong></td>
            <td class="tdrow2">
              <input type="text" name="user" class="textbox" value="root">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库密码</strong></td>
            <td class="tdrow2">
              <input type="password" name="pass" class="textbox">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库前辍</strong></td>
            <td class="tdrow2">
              <input type="text" name="prefix" class="textbox" value="tts">
            </td>
          </tr>  
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(序言)" onclick="onclick_step(this.form,'0')">
                <input type="button" name="step_next" value="下一步(连接数据库)" onclick="onclick_step(this.form,'2')">
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
<?}?>

<?if ($_GET['step']==2){?>   
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 数据转成 F2blog 程序</td>
    </tr>
    <tr>
      <td>
        <div class="note">第二步: 数据库连接 (2/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2" colspan="2">
			<?if ($step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;数据库连接成功，选择要转换的数据表，然后单击“下一步”就可以进行数据库转换了！<?}?>
            <?if (!$step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">数据库连接失败，单击“上一步”修改数据库设置！<?}?> 
			</td>
          </tr>
		  <?if ($step_result){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="categories" checked></td>
            <td width="350" class="tdrow2"><strong>1. 类别</strong></td>
          </tr>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="logs" checked></td>
            <td width="350" class="tdrow2"><strong>2. 日志</strong></td>
          </tr>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="guestbook" checked></td>
            <td width="350" class="tdrow2"><strong>3. 留言簿</strong></td>
          </tr>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="comments" checked></td>
            <td width="350" class="tdrow2"><strong>4. 评论</strong></td>
          </tr>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="links" checked></td>
            <td width="350" class="tdrow2"><strong>5. 友情链接</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="attachments" checked></td>
            <td width="350" class="tdrow2"><strong>6. 附件</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="trackbacks" checked></td>
            <td width="350" class="tdrow2"><strong>7. 引用</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="dailystatistics" checked></td>
            <td width="350" class="tdrow2"><strong>8. 访问日志 <br>(如访问日志非常大，建议分两次汇出或不汇出）</strong></td>
          </tr> 
		  <?}?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(数据库设置)" onclick="onclick_step(this.form,'1')">
				<?if ($step_result){?>
                <input type="button" name="step_next" value="下一步(开始汇出)" onclick="onclick_step(this.form,'3')">
				<?}else{?>
				<input type="hidden" name="step_next" value="">
				<?}?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
<?}?>

<?if ($_GET['step']==3){?>   
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 数据转成 F2blog 程序</td>
    </tr>
    <tr>
      <td>
        <div class="note">第三步: 数据转换 (3/4)</div>
        <table class="table1" align="center" width="100%">
		  <?if (strpos($table,";categories;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>1. 类别</strong></td>
            <td width="350" class="tdrow2"><?if ($result_categories>0){?>“转换了<font color="#FF0000"><?=$result_categories?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";logs;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>2. 日志</strong></td>
            <td width="350" class="tdrow2"><?if ($result_blog>0){?>“转换了<font color="#FF0000"><?=$result_blog?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";guestbook;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>3. 留言簿</strong></td>
            <td width="350" class="tdrow2"><?if ($result_guestbook>0){?>“转换了<font color="#FF0000"><?=$result_guestbook?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";comments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>4. 评论</strong></td>
            <td width="350" class="tdrow2"><?if ($result_comments>0){?>“转换了<font color="#FF0000"><?=$result_comments?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";links;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>5. 友情链接</strong></td>
            <td width="350" class="tdrow2"><?if ($result_links>0){?>“转换了<font color="#FF0000"><?=$result_links?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";attachments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>6. 附件</strong></td>
            <td width="350" class="tdrow2"><?if ($result_attachments>0){?>“转换了<font color="#FF0000"><?=$result_attachments?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";filters;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>7. 过滤器</strong></td>
            <td width="350" class="tdrow2"><?if ($result_filters>0){?>“转换了<font color="#FF0000"><?=$result_filters?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";trackbacks;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>8. 引用</strong></td>
            <td width="350" class="tdrow2"><?if ($result_trackbacks>0){?>“转换了<font color="#FF0000"><?=$result_trackbacks?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";dailystatistics;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>9. 访问日志</strong></td>
            <td width="350" class="tdrow2"><?if ($result_dailystatistics>0){?>“转换了<font color="#FF0000"><?=$result_dailystatistics?></font> 条”<?}else{?><font color="#FF0000">“转换失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if ($step_result){?>
          <tr>
            <td width="100" class="tdrow1"><strong>写入文件</strong></td>
            <td width="350" class="tdrow2"><?if ($result_data){?>“写入成功”<?}else{?><font color="#FF0000">“写入失败”</font><?}?></td>
          </tr>	
		  <?}else{?>
          <tr>
            <td class="tdrow2" colspan="2" align="center"><font color="#FF0000">“没有选择要汇出的数据表”</font></td>
          </tr>	
		  <?}?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(连接数据库)" onclick="onclick_step(this.form,'2')">
				<?if ($step_result){?>
                <input type="button" name="step_next" value="下一步(下载报表)" onclick="onclick_step(this.form,'4')">
				<?}else{?>
				<input type="hidden" name="step_next" value="">
				<?}?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<br />
<?}?>

<?if ($_GET['step']==4){?> 
<br />
<table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
  <tr>
    <td align="center" class="title">TT 数据转成 F2blog 程序</td>
  </tr>
  <tr>
    <td>
      <div class="note">第四步:下载报表 (4/4)</div>
      <table class="table1" align="center" width="100%">
        <tr>
          <td class="tdrow2" colspan="2"> &nbsp;&nbsp;&nbsp;&nbsp;数据库已汇成了F2blog可以使用的数据源了，单击下面的地址，可以把转换后的数据下载到本地服务器，也可以直接进入F2blog通用数据汇入进行操作！ </td>
        </tr>
        <tr height="50px">
          <td width="100" class="tdrow1"><strong>下载地址</strong></td>
          <td width="350" class="tdrow2"><a href="tt_data.dat" target="_blank">tt_data.dat</a></td>
        </tr>		
        <tr>
          <td class="tdrow2" colspan="2">
            <div align="center">
              <input type="hidden" name="step_prev" value="">
              <input type="button" name="step_next" value="完成汇出，进入F2blog通用数据汇入" onclick="javascript:location.href='f2blog.php'">
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?}?>
</form>
<div align="center">CopyRight © 2006 <a href="http://www.f2blog.com" target="_blank">www.f2blog.com</a> All Rights Reserved. </div>
</body>
</html>

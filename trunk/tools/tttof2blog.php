<?php
@set_time_limit(0);
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
@session_start();
@header("Content-Type: text/html; charset=utf-8");

if ($_GET['step']>4){$_GET['step']=4;}

//测试连接
if ($_GET['step']==2){
	if ($_POST['s_dbHost']=="" || $_POST['s_dbName']=="" || $_POST['s_dbUser']=="" || $_POST['s_dbPass']=="" || $_POST['s_dbPrefix']==""){ 
		$step_result=false;
	}else{
		$_SESSION['s_dbHost']=$_POST['s_dbHost'];
		$_SESSION['s_dbName']=$_POST['s_dbName'];
		$_SESSION['s_dbUser']=$_POST['s_dbUser'];
		$_SESSION['s_dbPass']=$_POST['s_dbPass'];
		$_SESSION['s_dbPrefix']=$_POST['s_dbPrefix'];
		$_SESSION['s_dbCharset']=($_POST['s_dbCharset']!="")?$_POST['s_dbCharset']:"utf8";
		$_SESSION['s_dbSize']=(is_numeric($_POST['s_dbSize']))?$_POST['s_dbSize']:"1024";
		$_SESSION['s_dbType']=$_POST['s_dbType'];
	}
}

//如果没有数据库设定，则转到数据库设定
if ($_GET['step']>1){
	if ($_SESSION['s_dbHost']=="" || $_SESSION['s_dbName']=="" || $_SESSION['s_dbUser']=="" || $_SESSION['s_dbPass']=="" || $_SESSION['s_dbPrefix']==""){ 
		$_GET['step']=1;
	}else{
		$mysql_conn=@mysql_connect($_SESSION['s_dbHost'], $_SESSION['s_dbUser'], $_SESSION['s_dbPass']);
		@mysql_select_db($_SESSION['s_dbName'],$mysql_conn);
		if(@mysql_get_server_info() > '4.1') @mysql_query("SET NAMES '{$_SESSION['s_dbCharset']}'");
		if(@mysql_get_server_info() > '5.0.1') @mysql_query("SET sql_mode=''");
		@mysql_query("set names '{$_SESSION['s_dbCharset']}'");
		@mysql_query("select * from {$_SESSION['s_dbPrefix']}Categories limit 0,1");
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
	$_SESSION['s_dbHost']="";
	$_SESSION['s_dbName']="";
	$_SESSION['s_dbUser']="";
	$_SESSION['s_dbPass']="";
	$_SESSION['s_dbPrefix']="";
}

if ($_GET['step']==3){
	if (count($_POST['chkData'])<1){
		$step_result=false;
	}else{
		$step_result=true;
		$table=";;".implode(";",$_POST['chkData']).";";
	}
	
	if ($step_result){
		$insert_sql="";
		$filename=gmdate("Ymd")."-".substr(md5(time()),0,8);
		$filetype=($_SESSION['s_dbType']=="zip")?".sql.zip":".sql";
		$p=0;//分卷

		if (strpos($table,";categories;")>0){
			//转换类别
			$arr_fields=array(
					"id"=>"id",
					"parent"=>"parent",
					"name"=>"name",
					"orderNo"=>"order",//1.05 priority//tt1.06
					"cateTitle"=>"label",
					"cateCount"=>"entries",
			);
			$f2blog_table="f2blog_categories";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Categories order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				if ($arr_result['parent']<1){$arr_result['parent']=0;}
				if ($arr_result['priority']){$arr_result['order']=$arr_result['priority'];}
				
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",isHidden) VALUES('".implode("','",$insert_value)."','0');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_categories=($error)?$error:$i;
		}

		if (strpos($table,";logs;")>0){
			//转换日志
			$arr_fields=array(
					"id"=>"id",
					"cateId"=>"category",
					"logTitle"=>"title",
					"logContent"=>"content",
					"author"=>"name",
					"isComment"=>"acceptComment",
					"isTrackback"=>"acceptTrackback",
					"commNums"=>"comments",
					"quoteNums"=>"trackbacks",
					"postTime"=>"published",
					"saveType"=>"visibility",
			);
			$f2blog_table="f2blog_logs";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Entries as a inner join ".$_SESSION['s_dbPrefix']."Users as b on a.owner=b.userid where draft=0 order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				//查找标签
				$query1="select name from ".$_SESSION['s_dbPrefix']."TagRelations as a inner join ".$_SESSION['s_dbPrefix']."Tags as b on a.tag=b.id where a.entry='".$arr_result['id']."'";
				$result1=mysql_query($query1); //or die(mysql_error());
				unset($arrtags);
				while ($arr_result1=mysql_fetch_array($result1)){
					$arrtags[]=$arr_result1['name'];
				}
				$arr_result['tags']=@implode(";",$arrtags);
				//echo $tags."<br />";
				
				$arr_result['visibility']=($arr_result['visibility']>0)?1:0;
				if ($arr_result['category']<1){$arr_result['category']=1;}
			
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					if ($key=="logContent"){				//格式化内容
						$insert_value[]=convert_content($arr_result['content']);
					}else{
						$insert_value[]=encode($arr_result[$value]);
					}
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_blog=($error)?$error:$i;
		}
		
		if (strpos($table,";guestbook;")>0){
			//转换留言簿
			$arr_fields=array(
				"id"=>"id",
				"parent"=>"parent",
				"author"=>"name",
				"content"=>"comment",
				"ip"=>"ip",
				"isSecret"=>"secret",
				"postTime"=>"written",
				"homepage"=>"homepage",
			);
			$f2blog_table="f2blog_guestbook";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Comments where entry=0 order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_guestbook=($error)?$error:$i;
		}

		if (strpos($table,";comments;")>0){
			//转换评论
			$arr_fields=array(
				"id"=>"id",
				"logId"=>"entry",
				"parent"=>"parent",
				"author"=>"name",
				"content"=>"comment",
				"ip"=>"ip",
				"isSecret"=>"secret",
				"postTime"=>"written",
				//"homepage"=>"homepage",
			);
			$f2blog_table="f2blog_comments";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Comments where entry>0 order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_comments=($error)?$error:$i;
		}

		if (strpos($table,";links;")>0){
			//友情链接
			$arr_fields=array(
				"id"=>"id",
				"name"=>"name",
				"blogUrl"=>"url",
			);
			$f2blog_table="f2blog_links";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Links order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",orderNo) VALUES('".implode("','",$insert_value)."','$i');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_links=($error)?$error:$i;
		}

		if (strpos($table,";tags;")>0){
			//标签TAG
			$arr_fields=array(
				"id"=>"id",
				"name"=>"name",
				"logNums"=>"logNums",
			);
			$f2blog_table="f2blog_tags";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select id,name,count(a.tag) as logNums from ".$_SESSION['s_dbPrefix']."TagRelations as a inner join ".$_SESSION['s_dbPrefix']."Tags as b on a.tag=b.id group by a.tag order by a.tag";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_tags=($error)?$error:$i;
		}

		if (strpos($table,";attachments;")>0){
			//附件
			$arr_fields=array(
				"logId"=>"parent",
				"name"=>"name",
				"attTitle"=>"label",
				"fileType"=>"mime",
				"fileSize"=>"size",
				"fileWidth"=>"width",
				"fileHeight"=>"height",
				"downloads"=>"downloads",
				"postTime"=>"attached",
			);
			$f2blog_table="f2blog_attachments";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."Attachments";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_attachments=($error)?$error:$i;
		}

		if (strpos($table,";filters;")>0){
			//过滤器
			$f2blog_table="f2blog_filters";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."GuestFilters";
			$result=@mysql_query($query); //or die(mysql_error());
			//$error=mysql_error();
			while ($arr_result=@mysql_fetch_array($result)){
				$insert_sql.="INSERT INTO $f2blog_table(category,name) VALUES('1','".encode($arr_result['name'])."');\r\n";
				$i++;				
			}
			$query="select * from ".$_SESSION['s_dbPrefix']."HostFilters";
			$result=@mysql_query($query); //or die(mysql_error());
			//$error=mysql_error();
			while ($arr_result=@mysql_fetch_array($result)){
				$insert_sql.="INSERT INTO $f2blog_table(category,name) VALUES('1','".encode($arr_result['name'])."');\r\n";
				$i++;				
			}
			$query="select * from ".$_SESSION['s_dbPrefix']."UrlFilters";
			$result=@mysql_query($query); //or die(mysql_error());
			//$error=mysql_error();
			while ($arr_result=@mysql_fetch_array($result)){
				$insert_sql.="INSERT INTO $f2blog_table(category,name) VALUES('4','".encode($arr_result['name'])."');\r\n";
				$i++;				
			}
			$query="select * from ".$_SESSION['s_dbPrefix']."ContentFilters";
			$result=@mysql_query($query); //or die(mysql_error());
			//$error=mysql_error();
			while ($arr_result=@mysql_fetch_array($result)){
				$insert_sql.="INSERT INTO $f2blog_table(category,name) VALUES('1','".encode($arr_result['word'])."');\r\n";
				$i++;				
			}			
			$query="select * from ".$_SESSION['s_dbPrefix']."Filters";
			$result=@mysql_query($query); //or die(mysql_error());
			//$error=mysql_error();
			while ($arr_result=@mysql_fetch_array($result)){
				switch ($arr_result['type']){
					case "content":$category=1;break;
					case "name":$category=1;break;
					case "url":$category=4;break;
					case "ip":$category=3;break;
			    }
				$insert_sql.="INSERT INTO $f2blog_table(category,name) VALUES('$category','".encode($arr_result['pattern'])."');\r\n";
				$i++;				
			}
			$result_filters=($error)?$error:$i;
		}

		if (strpos($table,";trackbacks;")>0){
			//引用
			$arr_fields=array(
					"logId"=>"entry",
					"blogUrl"=>"url",
					"postTime"=>"written",
					"tbTitle"=>"subject",
					"blogSite"=>"site",
					"ip"=>"ip",
					"content"=>"excerpt",
			);
			$f2blog_table="f2blog_trackbacks";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$query="select * from ".$_SESSION['s_dbPrefix']."Trackbacks";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).") VALUES('".implode("','",$insert_value)."');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_trackbacks=($error)?$error:$i;
		}

		if (strpos($table,";dailystatistics;")>0){
			//日志
			$f2blog_table="f2blog_dailystatistics";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$query="select * from ".$_SESSION['s_dbPrefix']."DailyStatistics";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$date=$arr_result['date'];
				$visitDate=substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2);
				$insert_sql.="INSERT INTO $f2blog_table(visitDate,visits) VALUES('$visitDate','".$arr_result['visits']."');\r\n";
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}				
				$i++;				
			}
			$result_dailystatistics=($error)?$error:$i;
		}

		//写入最后的代码
		if (strlen($insert_sql)>0){
			if ($p>0) $p++;
			$msg=write_file($insert_sql,$filename);
		}
		
		$_SESSION['f2blog_file']=array();
		if ($p>1){
			//下载地址
			for($i=1;$i<=$p;$i++){
				$_SESSION['f2blog_file'][]=$filename."_v$i".$filetype;
			}
			$result_data="数据分{$p}卷写入“{$filename}{$filetype}”文件中";
		}else{
			//下载地址
			$_SESSION['f2blog_file'][]=$filename.$filetype;
			$result_data="数据已写入“{$filename}{$filetype}”文件中";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TT 1.0 以上数据转换为F2blog数据</title>
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
<script type="text/javascript">
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
	<?php if ($_GET['step']==1){?>
	if (step==2){
		if (isNull(form.s_dbHost, '请输入mysql主机名称')) return false;
		if (isNull(form.s_dbName, '请输入TT数据库名')) return false;
		if (isNull(form.s_dbUser, '请输入TT用户名')) return false;
		if (isNull(form.s_dbPass, '请输入TT密码')) return false;
		if (isNull(form.s_dbPrefix, '请输入数据库前辍')) return false;
	}
	<?php }?>
	form.step_prev.disabled = true;
	form.step_next.disabled = true;
	form.action = "<?php echo $_SERVER['PHP_SELF']."?step="?>"+step;
	form.submit();
}
-->
</script>
</head>
<body>
<form name="convert" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">

<?php if (!isset($_GET['step']) || $_GET['step']<1){?> 
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 1.0 以上数据转换为F2blog数据</td>
    </tr>
    <tr>
      <td>
        <div class="note">序言: 转换程式声明</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2">&nbsp;&nbsp;&nbsp;&nbsp;使用之前，请先阅读本说明。<br />
              <br />
&nbsp;&nbsp;&nbsp;&nbsp;该程序只是把 <span class="STYLE1">TT 1.0 以上</span>中的数据表按照一定的字段对应关系转换成F2blog可以使用的数据。然后通过f2blog.php通用程式汇入到f2blog中，汇入过程中将删除F2blog数据库原有的类别、日志、留言簿、评论、友情链接、引用、过滤器、标签、附件、访问日志等数据表的数据，故建议在安装f2blog后马上使用该程式把TT数据转换到f2blog中。
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
<?php }?>  

<?php if ($_GET['step']==1){?> 
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
            <td width="130" class="tdrow1"><strong>服务器</strong></td>
            <td width="350" class="tdrow2">
              <input type="text" class="textbox" name="s_dbHost" value="<?php echo ($_SESSION['s_dbHost'])?$_SESSION['s_dbHost']:"localhost"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库名称</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbName" class="textbox" value="<?php echo ($_SESSION['s_dbName'])?$_SESSION['s_dbName']:"tt"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库用户名</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbUser" class="textbox" value="<?php echo ($_SESSION['s_dbUser'])?$_SESSION['s_dbUser']:"root"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库密码</strong></td>
            <td class="tdrow2">
              <input type="password" name="s_dbPass" class="textbox" value="<?php echo ($_SESSION['s_dbPass'])?$_SESSION['s_dbPass']:""?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库前辍</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbPrefix" class="textbox" value="<?php echo ($_SESSION['s_dbPrefix'])?$_SESSION['s_dbPrefix']:"tts"?>">
            </td>
          </tr>  
          <tr>
            <td class="tdrow1"><strong>数据库编码(<font color="red">慎改</font>)</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbCharset" class="textbox" value="<?php echo ($_SESSION['s_dbCharset'])?$_SESSION['s_dbCharset']:"utf8"?>">
            </td>
          </tr> 
          <tr>
            <td class="tdrow1"><strong>输出数据压缩</strong></td>
            <td class="tdrow2">
              <input type="radio" name="s_dbType" value="" checked> 无
			  <input type="radio" name="s_dbType" value="zip" <?php echo (function_exists("gzopen"))?"":"disabled"?>>zip 压缩
            </td>
          </tr> 
          <tr>
            <td class="tdrow1"><strong>数据每卷大小(<font color="red">kb</font>)</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbSize" class="textbox" value="<?php echo ($_SESSION['s_dbSize'])?$_SESSION['s_dbSize']:"1024"?>">
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
<?php }?>

<?php if ($_GET['step']==2){?>   
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
			<?php if ($step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;数据库连接成功，选择要转换的数据表，然后单击“下一步”就可以进行数据库转换了！<?php }?>
            <?php if (!$step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">数据库连接失败或数据库／数据表不存在！，单击“上一步”修改数据库设置！<?php }?> 
			</td>
          </tr>
		  <?php if ($step_result){?>
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
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="tags" checked></td>
            <td width="350" class="tdrow2"><strong>6. 标签（TAG）</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="attachments" checked></td>
            <td width="350" class="tdrow2"><strong>7. 附件</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="filters" checked></td>
            <td width="350" class="tdrow2"><strong>8. 过滤器</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="trackbacks" checked></td>
            <td width="350" class="tdrow2"><strong>9. 引用</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="dailystatistics" checked></td>
            <td width="350" class="tdrow2"><strong>10. 访问日志 <br />(如访问日志非常大，建议分两次汇出或不汇出）</strong></td>
          </tr> 
		  <?php }?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(数据库设置)" onclick="onclick_step(this.form,'1')">
				<?php if ($step_result){?>
                <input type="button" name="step_next" value="下一步(开始汇出)" onclick="onclick_step(this.form,'3')">
				<?php }else{?>
				<input type="hidden" name="step_next" value="">
				<?php }?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
<?php }?>

<?php if ($_GET['step']==3){?>   
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">TT 数据转成 F2blog 程序</td>
    </tr>
    <tr>
      <td>
        <div class="note">第三步: 数据转换 (3/4)</div>
        <table class="table1" align="center" width="100%">
		  <?php if (strpos($table,";categories;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>1. 类别</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_categories)){?>“转换了<font color="#FF0000"><?php echo $result_categories?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_categories?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";logs;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>2. 日志</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_blog)){?>“转换了<font color="#FF0000"><?php echo $result_blog?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_blog?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";guestbook;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>3. 留言簿</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_guestbook)){?>“转换了<font color="#FF0000"><?php echo $result_guestbook?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_guestbook?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";comments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>4. 评论</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_comments)){?>“转换了<font color="#FF0000"><?php echo $result_comments?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_comments?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";links;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>5. 友情链接</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_links)){?>“转换了<font color="#FF0000"><?php echo $result_links?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_links?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";tags;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>6. 标签TAG</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_tags)){?>“转换了<font color="#FF0000"><?php echo $result_tags?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_tags?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";attachments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>7. 附件</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_attachments)){?>“转换了<font color="#FF0000"><?php echo $result_attachments?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_attachments?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";filters;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>8. 过滤器</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_filters)){?>“转换了<font color="#FF0000"><?php echo $result_filters?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_filters?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";trackbacks;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>9. 引用</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_trackbacks)){?>“转换了<font color="#FF0000"><?php echo $result_trackbacks?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_trackbacks?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";dailystatistics;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>10. 访问日志</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_dailystatistics)){?>“转换了<font color="#FF0000"><?php echo $result_dailystatistics?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_dailystatistics?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if ($step_result){?>
          <tr>
            <td width="100" class="tdrow1"><strong>写入文件</strong></td>
            <td width="350" class="tdrow2"><?php if ($result_data){echo $result_data;}else{?><font color="#FF0000">“写入失败”</font><?php }?></td>
          </tr>	
		  <?php }else{?>
          <tr>
            <td class="tdrow2" colspan="2" align="center"><font color="#FF0000">“没有选择要汇出的数据表”</font></td>
          </tr>	
		  <?php }?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(连接数据库)" onclick="onclick_step(this.form,'2')">
				<?php if ($step_result){?>
                <input type="button" name="step_next" value="下一步(下载报表)" onclick="onclick_step(this.form,'4')">
				<?php }else{?>
				<input type="hidden" name="step_next" value="">
				<?php }?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<br />
<?php }?>

<?php if ($_GET['step']==4){?> 
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
          <td width="350" class="tdrow2">
		  <?php foreach($_SESSION['f2blog_file'] as $value){?>
		  <a href="<?php echo $value?>" target="_blank"><?php echo $value?></a><br />
		  <?php }?>
		  <br />(可以用鼠标右键保存到本地电脑)
		  </td>
        </tr>		
        <tr>
          <td class="tdrow2" colspan="2">
            <div align="center">
              <input type="hidden" name="step_prev" value="">
              <input type="button" name="step_next" value="已完成汇出，马上进入F2blog通用数据汇入" onclick="javascript:location.href='f2blog.php'">
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?php }?>
</form>
<div align="center">CopyRight © 2006 <a href="http://www.f2blog.com" target="_blank">www.f2blog.com</a> All Rights Reserved. </div>
</body>
</html>

<?php 
function write_file ($sql,$backname) {
	global $p,$_SESSION;
	if ($p>0) $backname.="_v".$p;

	$msg="";
	if ($_SESSION['s_dbType']=="zip") {
		$backname.='.sql.zip';
		if ($gzfp=@gzopen($backname, 'wb9')){
			@gzwrite($gzfp, $sql);
			@gzclose($gzfp);
		}else{
			$msg="无权写入文件到tools目录下，在linux等下请把它改为777权限！";
		}		
	}else{
		$backname.='.sql';
		if ($fp = fopen($backname, 'wbt')) {
			fwrite($fp,$sql);
			fclose($fp);
		}else{
			$msg="无权写入文件到tools目录下，在linux等下请把它改为777权限！";
		}		
	}
	return $msg;
}

function convert_content($content){
	$content=str_replace("&quot;","\"",$content);
	$content=str_replace("&amp;quot;","\"",$content);
	$content=str_replace("'","&#39;",$content);
	$content=preg_replace("/(<img.*?>)/ie","convertquot('\\1')",$content);
	$content=preg_replace("/(<a href.*?>)/ie","convertquot('\\1')",$content);

	//$pattern = "/([^\/\"\'\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \r\n\<\"\'\)]+)/";
	//$content = preg_replace($pattern, "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $content);

	$content=str_replace("[##_ATTACH_PATH_##]","../attachments/",$content);
	$content=preg_replace("/\[##_1L\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"left\"><img src=\"../attachments/\\1\" alt=\"open_img(&#39;../attachments/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
	$content=preg_replace("/\[##_1R\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"right\"><img src=\"../attachments/\\1\" alt=\"open_img(&#39;../attachments/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
	$content=preg_replace("/\[##_1C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><img src=\"../attachments/\\1\" alt=\"open_img(&#39;../attachments/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></p>",$content);
	$content=preg_replace("/\[##_2C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><table border=\"0\" cellspacing=\"5\"><tbody><tr><td><img src=\"../attachments/\\1\" alt=\"open_img(&#39;../attachments/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></td><td><img src=\"../attachments/\\6\" alt=\"open_img(&#39;../attachments/\\6&#39;)\" width=\"\\7\" height=\"\\8\" /></td></tr></tbody></table></p>",$content);
	$content=preg_replace("/\[##_3C\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)\|([0-9]+\.[jpg|gif|png]+)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><table border=\"0\" cellspacing=\"5\"><tbody><tr><td><img src=\"../attachments/\\1\" alt=\"open_img(&#39;../attachments/\\1&#39;)\" width=\"\\2\" height=\"\\3\" /></td><td><img src=\"../attachments/\\6\" alt=\"open_img(&#39;../attachments/\\6&#39;)\" width=\"\\7\" height=\"\\8\" /></td><td><img src=\"../attachments/\\11\" alt=\"open_img(&#39;../attachments/\\11&#39;)\" width=\"\\12\" height=\"\\13\" /></td></tr></tbody></table></p>",$content);
	$content=preg_replace("/\[##_Gallery\|(.+?)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?(.*?)_##\]/is","<p align=\"center\"><!--galleryBegin-->\\1,\\2,\\3<!--galleryEnd--></p>",$content);
	$content=preg_replace("/\[##_iMazing\|(.+?)\|width=\"?([0-9]+)\"? height=\"?([0-9]+)\"?\s?(.*?)\|(.*?)_##\]/is","<p align=\"center\"><!--galleryBegin-->\\1,\\2,\\3<!--galleryEnd--></p>",$content);
	$content=preg_replace("/\[##_1L\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"left\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
	$content=preg_replace("/\[##_1R\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"right\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
	$content=preg_replace("/\[##_1C\|(.+?).swf\|(.*?)\|(.*?)_##\]/is","<p align=\"center\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"500\" height=\"363\"><param name=\"movie\" value=\"../attachments/\\1.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"../attachments/\\1.swf\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"363\"></embed></object></p>",$content);	
	$content=preg_replace("/\[##_1L\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"left\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
	$content=preg_replace("/\[##_1R\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"right\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
	$content=preg_replace("/\[##_1C\|(.+?)\|(.*?)\|(.*?)_##\]/is","<p align=\"center\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
	$content=preg_replace("/\[##_Jukebox\|([0-9]+.mp3)\|(.*?)\|_##\]/is","<p align=\"center\"><!--fileBegin-->\\1<!--fileEnd--></p>",$content);
	$content=preg_replace("/\[#M_(.*?)\|(.*?)\|(.*?)_M#\]/is","<!--hideBegin-->\\3<!--hideEnd-->",$content);
	$content=preg_replace("/\[#M_(.*?)\|(.*?)\|(.*?)_M#\]/is","\\3",$content);
	$content=str_replace("[#M_","",$content);
	$content=str_replace("_M#]","",$content);
	$content=preg_replace("/{{(.*?)}}/is","<img src=\"../attachments/\\1.gif\">",$content);
	$content=preg_replace("/\/?(smiles)/is","../attachments/\\1",$content);

	//转换HTML或CODE代码			
	$content=preg_replace("/\[code\](.+?)\[\/code\]/ie","codeconvert('\\1')",$content);
	$content=preg_replace("/\[html\](.+?)\[\/html\]/ie","codeconvert('\\1')",$content);
	$content=str_replace("[CODE]","",$content);
	$content=str_replace("[/CODE]","",$content);
	$content=str_replace("[HTML]","",$content);
	$content=str_replace("[/HTML]","",$content);	
	$content=str_replace("\&quot;","&quot;",$content);
	$content=str_replace("\\\"","\"",$content);
	$content=nl2br($content);
	$content=str_replace("\r","",$content);
	$content=str_replace("\n","",$content);

	return $content;
}

function encode($string) {
	if (function_exists('mysql_escape_string')){
		return mysql_escape_string($string);
	}else{
		$string=str_replace("\r","",$string);
		$string=str_replace("\n","",$string);
		$string=str_replace("'","&#39;",$string);
		$string=addslashes($string);
		return $string;
	}
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
?>
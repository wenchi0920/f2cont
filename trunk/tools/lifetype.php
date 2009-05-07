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
		@mysql_query("select id from {$_SESSION['s_dbPrefix']}users limit 0,1");
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
					"parent"=>"parent_id",
					"name"=>"name",
					"cateTitle"=>"description",
			);
			$f2blog_table="f2blog_categories";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."articles_categories order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",orderNo,isHidden) VALUES('".implode("','",$insert_value)."','$i','0');\r\n";
				
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
					"cateId"=>"category_id",
					"logTitle"=>"topic",
					"logContent"=>"text",
					"author"=>"name",
					"postTime"=>"date",
					"saveType"=>"status",
			);
			$f2blog_table="f2blog_logs";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select *,a.id as id from ".$_SESSION['s_dbPrefix']."articles as a inner join ".$_SESSION['s_dbPrefix']."articles_text as b on a.id=b.article_id inner join ".$_SESSION['s_dbPrefix']."users as c on a.user_id=c.id inner join ".$_SESSION['s_dbPrefix']."article_categories_link as d on a.id=d.article_id order by a.id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$arr_result['status']=($arr_result['status']>1)?0:1;

				$arr_result['date']=convertdate($arr_result['date']);

				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					if ($key=="logContent"){				//格式化内容
						$insert_value[]=convert_content($arr_result['text']);
					}else{
						$insert_value[]=encode($arr_result[$value]);
					}
				}			

				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",isComment,isTrackback) VALUES('".implode("','",$insert_value)."','1','1');\r\n";

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
				"parent"=>"parent_id",
				"author"=>"name",
				"content"=>"text",
				"ip"=>"client_ip",
				"postTime"=>"date",
				"isSecret"=>"private",
				"homepage"=>"user_url",
				"email"=>"user_email",
			);
			$f2blog_table="f2blog_guestbook";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."guestbook order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$arr_result['date']=convertdate($arr_result['date']);

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
				"parent"=>"parent_id",
				"logId"=>"article_id",
				"author"=>"user_name",
				"content"=>"text",
				"ip"=>"client_ip",
				"postTime"=>"date",
				"isSecret"=>"status",
				//"homepage"=>"repurl",
				//"email"=>"repemail",
			);
			$f2blog_table="f2blog_comments";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."articles_comments order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$arr_result['date']=convertdate($arr_result['date']);

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
			$query="select * from ".$_SESSION['s_dbPrefix']."mylinks order by id";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$id=$i+1;
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",isApp,orderNo) VALUES('".implode("','",$insert_value)."','1','$id');\r\n";
				
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

		if (strpos($table,";trackbacks;")>0){
			//引用
			$arr_fields=array(
					"logId"=>"article_id",
					"blogUrl"=>"url",
					"postTime"=>"date",
					"tbTitle"=>"title",
					"blogSite"=>"blog_name",
					"content"=>"properties",
			);
			$f2blog_table="f2blog_trackbacks";
			$insert_sql.="TRUNCATE TABLE $f2blog_table;\r\n";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."trackbacks";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$arr_result['date']=convertdate($arr_result['date']);
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",isApp) VALUES('".implode("','",$insert_value)."','1');\r\n";
				
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

		if (strpos($table,";members;")>0){
			//会员
			$arr_fields=array(
					"username"=>"user",
					"password"=>"password",
					"nickname"=>"full_name",
					"email"=>"email",
			);
			$f2blog_table="f2blog_members";
			$i=0;
			$query="select * from ".$_SESSION['s_dbPrefix']."users";
			$result=mysql_query($query); //or die(mysql_error());
			$error=mysql_error();
			while ($arr_result=mysql_fetch_array($result)){
				$insert_field=array();
				$insert_value=array();
				foreach($arr_fields as $key=>$value){
					$insert_field[]=$key;
					$insert_value[]=encode($arr_result[$value]);
				}
				$insert_sql.="INSERT INTO $f2blog_table(".implode(",",$insert_field).",role) VALUES('".implode("','",$insert_value)."','member');\r\n";
				
				//是否分卷
				if(strlen($insert_sql)>=$_SESSION['s_dbSize']*1000){
					$p++;
					$msg=write_file($insert_sql,$filename);  //写入分卷文件
					$insert_sql="";
				}
				$i++;				
			}
			$result_members=($error)?$error:$i;
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
<title>LifeType 以上数据转换为F2blog数据</title>
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
		if (isNull(form.s_dbName, '请输入LifeType数据库名')) return false;
		if (isNull(form.s_dbUser, '请输入LifeType用户名')) return false;
		if (isNull(form.s_dbPass, '请输入LifeType密码')) return false;
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
      <td align="center" class="title">LifeType 以上数据转换为F2blog数据</td>
    </tr>
    <tr>
      <td>
        <div class="note">序言: 转换程式声明</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2">&nbsp;&nbsp;&nbsp;&nbsp;使用之前，请先阅读本说明。<br />
              <br />
&nbsp;&nbsp;&nbsp;&nbsp;该程序只是把 <span class="STYLE1">LifeType 以上</span>中的数据表按照一定的字段对应关系转换成F2blog可以使用的数据。然后通过f2blog.php通用程式汇入到f2blog中，汇入过程中将删除F2blog数据库原有的类别、日志、留言簿、评论、友情链接、引用等数据表的数据，故建议在安装f2blog后马上使用该程式把LifeType数据转换到f2blog中。
<p>&nbsp;&nbsp;&nbsp;&nbsp;该操作将分四步完成。 </p>
            </td>
          </tr>
          <tr>
            <td class="tdrow2">
              <div align="center">
			    <input type="hidden" name="step_prev" value="">
                <input type="button" name="step_next" value="下一步(LifeType数据库设置)" onclick="onclick_step(this.form,'1')">
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
      <td align="center" class="title">LifeType 数据转成 F2blog 程序</td>
    </tr>
    <tr>
      <td>
        <div class="note">第一步: LifeType 数据库设置 (1/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td width="100" class="tdrow1"><strong>服务器</strong></td>
            <td width="350" class="tdrow2">
              <input type="text" class="textbox" name="s_dbHost" value="<?php echo ($_SESSION['s_dbHost'])?$_SESSION['s_dbHost']:"localhost"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库名称</strong></td>
            <td class="tdrow2">
              <input type="text" name="s_dbName" class="textbox" value="<?php echo ($_SESSION['s_dbName'])?$_SESSION['s_dbName']:"lifetype"?>">
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
              <input type="text" name="s_dbPrefix" class="textbox" value="<?php echo ($_SESSION['s_dbPrefix'])?$_SESSION['s_dbPrefix']:"lt_"?>">
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
      <td align="center" class="title">LifeType 数据转成 F2blog 程序</td>
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
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="trackbacks" checked></td>
            <td width="350" class="tdrow2"><strong>6. 引用</strong></td>
          </tr> 
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" id="chkData" value="members" checked></td>
            <td width="350" class="tdrow2"><strong>7. 会员列表</strong></td>
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
      <td align="center" class="title">LifeType 数据转成 F2blog 程序</td>
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
		  <?php if (strpos($table,";trackbacks;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>6. 引用</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_trackbacks)){?>“转换了<font color="#FF0000"><?php echo $result_trackbacks?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_trackbacks?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if (strpos($table,";members;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>7. 会员列表</strong></td>
            <td width="350" class="tdrow2"><?php if (is_int($result_members)){?>“转换了<font color="#FF0000"><?php echo $result_members?></font> 条”<?php }else{?><font color="#FF0000">“转换失败:<?php echo $result_members?>”</font><?php }?></td>
          </tr>
		  <?php }?>
		  <?php if ($step_result){?>
          <tr>
            <td width="100" class="tdrow1"><strong>写入文件</strong></td>
            <td width="350" class="tdrow2"><?php if ($result_data){?>“写入成功”<?php }else{?><font color="#FF0000">“写入失败”</font><?php }?></td>
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
    <td align="center" class="title">LifeType 数据转成 F2blog 程序</td>
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
		  <?php foreach ($_SESSION['f2blog_file'] as $value){?>
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

function convert_content($content) {
	$basicubb_search=array('[hr]', '<br />');
	$basicubb_replace=array('<hr/>', '<br />');
	$content=str_replace($basicubb_search, $basicubb_replace, $content);

	$content=preg_replace("/\[img( align=L| align=M| align=R)?( width=[0-9]+)?( height=[0-9]+)?\]\s*(\S+?)\s*\[\/img\]/ise","makeimg('\\1', '\\2', '\\3', '\\4')",$content);

	$content=preg_replace("/\[sfile\]\s*(\S+?)\s*\[\/sfile\]/ie", "makefile('\\1')", $content);
	$content=preg_replace("/\[file\]\s*(\S+?)\s*\[\/file\]/ie", "makefile('\\1')", $content);
	$content=str_replace("[separator]", "<!--more-->", $content);
	$content=str_replace("[newpage]", "<!--nextpage-->", $content);
	
	$regubb_search = array(
				"/\s*\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is",
				"/\s*\[quote=(.+?)\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is",
				"/\s*\[code\][\n\r]*(.+?)[\n\r]*\[\/code\]\s*/ie",
				"/\[url\]([^\[]*)\[\/url\]/ie",
				"/\[url=www.([^\[\"']+?)\](.+?)\[\/url\]/is",
				"/\[url=([^\[]*)\](.+?)\[\/url\]/is",
				"/\[email\]([^\[]*)\[\/email\]/is",
				"/\[acronym=([^\[]*)\](.+?)\[\/acronym\]/is",
				"/\[color=([^\[\<]+?)\](.+?)\[\/color\]/i",
				"/\[size=([^\[\<]+?)\](.+?)\[\/size\]/ie",
				"/\[font=([^\[\<]+?)\](.+?)\[\/font\]/i",
				"/\[p align=([^\[\<]+?)\](.+?)\[\/p\]/i",
				"/\[b\](.+?)\[\/b\]/i",
				"/\[i\](.+?)\[\/i\]/i",
				"/\[u\](.+?)\[\/u\]/i",
				"/\[strike\](.+?)\[\/strike\]/i",
				"/\[sup\](.+?)\[\/sup\]/i",
				"/\[sub\](.+?)\[\/sub\]/i",
				"/\s*\[php\][\n\r]*(.+?)[\n\r]*\[\/php\]\s*/ie",
				"/\[(wmp|swf|real)=([^\[\<]+?),([^\[\<]+?)\]\s*([^\[\<\r\n]+?)\s*\[\/(wmp|swf|real)\]/is"
	);
	$regubb_replace =  array(
				"<br /><div class=\"UBBPanel\" style=\"padding-left: 3px; margin: 15px;\"><div class=\"UBBContent\">\\1</div></div>",
				"<br /><div class=\"UBBPanel\" style=\"padding-left: 3px; margin: 15px;\"><div class=\"UBBContent\">\\2</div></div>",
				"makecode('\\1')",
				"makeurl('\\1')",
				"<a href=\"http://www.\\1\" target=\"_blank\">\\2</a>",
				"<a href=\"\\1\" target=\"_blank\">\\2</a>",
				"<a href=\"mailto: \\1\">\\1</a>",
				"<acronym title=\"\\1\">\\2</acronym>",
				"<span style=\"color: \\1;\">\\2</span>",
				"makefontsize('\\1', '\\2')",
				"<span style=\"font-family: \\1;\">\\2</span>",
				"<p align=\"\\1\">\\2</p>",
				"<strong>\\1</strong>",
				"<em>\\1</em>",
				"<u>\\1</u>",
				"<del>\\1</del>",
				"<sup>\\1</sup>",
				"<sub>\\1</sub>",				
				"xhtmlHighlightString('\\1')",
				"<!--musicBegin-->\\4|\\1|\\2|\\3<!--musicEnd-->"
	);
	$content=preg_replace($regubb_search, $regubb_replace, $content);

	$content=str_replace("'","&#39;",$content);
	$content=nl2br($content);
	$content=str_replace("\r","",$content);
	$content=str_replace("\n","",$content);
	return $content;
}

function makefile($file) {
	if (strpos(";$file","attachments/")>0){
		//$file=$settingInfo[blogUrl].$file;
		$filename=str_replace("attachments/","",$file);		
	}else{
		$filename=substr($file,strrpos($file,"/")+1);
	}
	$return="<a href=\"$file\">$filename</a>";
	return $return;
}

function makeurl($url) {
	$urllink='<a href=\"'.(substr(strtolower($url), 0, 4) == 'www.' ? "http://$url" : $url).'" target="_blank">'.$url.'</a>';
	return $urllink;
}

function makefontsize ($size, $word) {
	$sizeitem=array (0, 8, 10, 12, 14, 18, 24, 36); 
	$size=$sizeitem[$size];
	return "<span style=\"font-size: {$size}px;\">{$word}</span>";
}

function makecode ($str) {
	$kkstr="<div class=\"UBBPanel\" style=\"padding-left: 3px; margin: 15px;\"><div class=\"UBBContent\">$str</div></div>";
	return $kkstr;
}

function makeimg ($aligncode, $widthcode, $heightcode, $src) {
	$align=str_replace(' align=', '', strtolower($aligncode));
	if ($align=='l') $show=' align="left"';
	elseif ($align=='r') $show=' align="right"';
	else $alignshow='';
	$width=str_replace(' width=', '', strtolower($widthcode));
	if (!empty($width)) $show.=" width=\"{$width}\"";
	$height=str_replace(' height=', '', strtolower($heightcode));
	if (!empty($height)) $show.=" height=\"{$height}\"";

	$code="<img src=\"{$src}\" alt=\"open_img(&#39;{$src}&#39;)\" {$show}/>";

	return $code;
}

function xhtmlHighlightString($str) {
	$str=base64_decode($str);
	if (PHP_VERSION<'4.2.0') return "<div class=\"code\" style=\"overflow: auto;\">$str</div>";
	$hlt = highlight_string($str, true);
	if (PHP_VERSION>'5') return "<div class=\"code\" style=\"overflow: auto;\">$hlt</div>";
	$fon = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $hlt);
	$ret = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $fon);
	return "<div class=\"code\" style=\"overflow: auto;\">$ret;</div>";
}

function convert_character($str){
	if (function_exists('mysql_real_escape_string')){
		return mysql_real_escape_string($str);
	}else{
		$str=str_replace("\r","",$str);
		$str=str_replace("\n","",$str);
		$str=str_replace("'","&#39;",$str);
		$str=addslashes($str);
		return $str;
	}
}
function convertdate($timestamp){
	//echo $timestamp;
	if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}/i", $timestamp)) {
		list($date,$time)=explode(" ",$timestamp);
		list($year,$month,$day)=explode("-",$date);
		list($hour,$minute,$second)=explode(":",$time);
		//echo "$year,$month,$day,$hour,$minute<br>";
		$timestamp=gmmktime($hour,$minute,$second,$month,$day,$year);
		
		if(PHP_VERSION>4){
			$offset = $settingInfo['timezone'];
			$timestamp=$timestamp-$offset*3600;
		}
	}else{
		$timestamp=time();
	}
	//echo "++++".gmdate("Y-m-d H:i:s",$timestamp)."<br><br><br>";

	return $timestamp;
}
?>
<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

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
	if (($_SESSION['host']=="" || $_SESSION['name']=="" || $_SESSION['user']=="" || $_SESSION['pass']=="" || $_SESSION['prefix']=="")){ 
		$_GET['step']=1;
	}else{
		$mysql_conn=@mysql_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
		@mysql_select_db($_SESSION['name'],$mysql_conn);
		@mysql_query("set names 'utf8'");
		@mysql_query("select id from {$_SESSION['prefix']}categories limit 0,1");
		$error=mysql_error();
		if ($error!=""){
			$step_result=false;
			$_GET['step']=2;
		}else{
			$step_result=true;
		}
	}
}

if ($_GET['step']==3){
	$step_result=true;
	if ($_POST['source']=="server"){
		$source_data="tt_data.dat";
	}else{
		if ($_FILES['myfile']['tmp_name']==""){
			$step_result=false;
		}else{
			if (strpos($_FILES['myfile']['name'],".dat")<1){
				$step_result=false;
			}else{
				@copy($_FILES['myfile']['tmp_name'],"tmp.dat");
				$source_data="tmp.dat";
			}
		}
	}

	if (!file_exists($source_data)){
		$step_result=false;
	}

	if ($step_result){
		include($source_data);
		if (isset($categories)){$source_arr[]="categories";}
		if (isset($logs)){$source_arr[]="logs";}
		if (isset($comments)){$source_arr[]="comments";}
		if (isset($guestbook)){$source_arr[]="guestbook";}
		if (isset($links)){$source_arr[]="links";}
		if (isset($tags)){$source_arr[]="tags";}
		if (isset($attachments)){$source_arr[]="attachments";}
		if (isset($dailystatistics)){$source_arr[]="dailystatistics";}
		if (isset($filters)){$source_arr[]="filters";}
		if (isset($trackbacks)){$source_arr[]="trackbacks";}
		//print_r($source_table);
		$source_table=";;".implode(";",$source_arr).";";
	}	
}

if ($_GET['step']==4){
	if (count($_POST['chkData'])<1){
		$step_result=false;
	}else{
		$step_result=true;
		$table=";;".implode(";",$_POST['chkData']).";";
	}
	
	if (!file_exists($_POST['source_data'])){
		$step_result=false;
	}else{
		include($_POST['source_data']);
	}

	if ($step_result){
		if (strpos($table,";categories;")>0){
			//转换类别
			//print_r($categories);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."categories";
			mysql_query($del_sql);
			for ($i=0;$i<count($categories);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."categories (id,parent,name,orderNo,cateTitle,cateCount,isHidden) VALUES('".$categories[$i]['id']."','".$categories[$i]['parent']."','".$categories[$i]['name']."','".$categories[$i]['orderNo']."','".$categories[$i]['cateTitle']."','".$categories[$i]['cateCount']."','".$categories[$i]['isHidden']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_categories=count($categories);
		}

		if (strpos($table,";logs;")>0){
			//转换日志
			//print_r($logs);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."logs";
			mysql_query($del_sql);
			for ($i=0;$i<count($logs);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."logs (id,cateId,logTitle,logContent,author,postTime,quoteUrl,saveType,commNums,quoteNums,isComment,isTrackback,tags) VALUES('".$logs[$i]['id']."','".$logs[$i]['cateId']."','".$logs[$i]['logTitle']."','".addslashes($logs[$i]['logContent'])."','".$logs[$i]['author']."','".$logs[$i]['postTime']."','".$logs[$i]['quoteUrl']."','".$logs[$i]['saveType']."','".$logs[$i]['commNums']."','".$logs[$i]['quoteNums']."','".$logs[$i]['isComment']."','".$logs[$i]['isTrackback']."','".$logs[$i]['tags']."')";
				//echo htmlspecialchars($query)."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_logs=count($logs);
		}
		
		if (strpos($table,";guestbook;")>0){
			//转换留言簿
			//print_r($guestbook);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."guestbook";
			mysql_query($del_sql);
			for ($i=0;$i<count($guestbook);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."guestbook (id,parent,author,homepage,ip,content,postTime) VALUES('".$guestbook[$i]['id']."','".$guestbook[$i]['parent']."','".$guestbook[$i]['author']."','".$guestbook[$i]['homepage']."','".$guestbook[$i]['ip']."','".$guestbook[$i]['content']."','".$guestbook[$i]['postTime']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_guestbook=count($guestbook);
		}

		if (strpos($table,";comments;")>0){
			//转换评论
			//print_r($comments);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."comments";
			mysql_query($del_sql);
			for ($i=0;$i<count($comments);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."comments (id,logId,parent,content,author,postTime,ip,isSecret) VALUES('".$comments[$i]['id']."','".$comments[$i]['logId']."','".$comments[$i]['parent']."','".$comments[$i]['content']."','".$comments[$i]['author']."','".$comments[$i]['postTime']."','".$comments[$i]['ip']."','".$comments[$i]['isSecret']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_comments=count($comments);
		}

		if (strpos($table,";links;")>0){
			//转换友情链接
			//print_r($links);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."links";
			mysql_query($del_sql);
			for ($i=0;$i<count($links);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."links (id,name,blogUrl,orderNo) VALUES('".$links[$i]['id']."','".$links[$i]['name']."','".$links[$i]['blogUrl']."','".$links[$i]['orderNo']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_links=count($links);
		}

		if (strpos($table,";tags;")>0){
			//转换TAGS
			//print_r($tags);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."tags";
			mysql_query($del_sql);
			for ($i=0;$i<count($tags);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."tags (id,name,logNums) VALUES('".$tags[$i]['id']."','".$tags[$i]['name']."','".$tags[$i]['logNums']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_tags=count($tags);
		}

		if (strpos($table,";attachments;")>0){
			//转换附件
			//print_r($attachments);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."attachments";
			mysql_query($del_sql);
			for ($i=0;$i<count($attachments);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."attachments (logId,name,attTitle,fileType,fileSize,fileWidth,fileHeight,downloads,postTime) VALUES('".$attachments[$i]['logId']."','".$attachments[$i]['name']."','".$attachments[$i]['attTitle']."','".$attachments[$i]['fileType']."','".$attachments[$i]['fileSize']."','".$attachments[$i]['fileWidth']."','".$attachments[$i]['fileHeight']."','".$attachments[$i]['downloads']."','".$attachments[$i]['postTime']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_attachments=count($attachments);
		}

		if (strpos($table,";dailystatistics;")>0){
			//转换访问记录
			//print_r($dailystatistics);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."dailystatistics";
			mysql_query($del_sql);
			for ($i=0;$i<count($dailystatistics);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."dailystatistics (visitDate,visits) VALUES('".$dailystatistics[$i]['visitDate']."','".$dailystatistics[$i]['visits']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_dailystatistics=count($dailystatistics);
		}

		if (strpos($table,";filters;")>0){
			//转换过滤器
			//print_r($filters);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."filters";
			mysql_query($del_sql);
			for ($i=0;$i<count($filters);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."filters (category,name) VALUES('".$filters[$i]['category']."','".$filters[$i]['name']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_filters=count($filters);
		}

		if (strpos($table,";trackbacks;")>0){
			//转换引用
			//print_r($trackbacks);
			//删除数据
			$del_sql="TRUNCATE TABLE ".$_SESSION['prefix']."trackbacks";
			mysql_query($del_sql);
			for ($i=0;$i<count($trackbacks);$i++){
				$query="INSERT INTO ".$_SESSION['prefix']."trackbacks (logId,blogUrl,postTime,tbTitle,blogSite,ip,content) VALUES('".$trackbacks[$i]['logId']."','".$trackbacks[$i]['blogUrl']."','".$trackbacks[$i]['postTime']."','".$trackbacks[$i]['tbTitle']."','".$trackbacks[$i]['blogSite']."','".$trackbacks[$i]['ip']."','".$trackbacks[$i]['content']."')";
				//echo $query."<br>";
				mysql_query($query) or die(mysql_error());
			}
			$result_trackbacks=count($trackbacks);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>F2blog通用数据汇入</title>
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
		if (isNull(form.name, '请输入F2blog数据库名')) return false;
		if (isNull(form.user, '请输入F2blog用户名')) return false;
		if (isNull(form.pass, '请输入F2blog密码')) return false;
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
<form name="convert" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

<?if (!isset($_GET['step']) || $_GET['step']<1){?> 
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">序言: 汇入程式声明</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2"> &nbsp;&nbsp;&nbsp;&nbsp;使用之前，请先阅读本说明。<br />
&nbsp;&nbsp;&nbsp;&nbsp;该程序只接受使用本站使用的数据格式来导入数据。从TT等blog汇入来的数据可以汇入，请注意汇入后原有的日志，类别，留言簿、评论、友情链接将会清空。
<p> &nbsp;&nbsp;&nbsp;&nbsp;该操作将分四步完成。 </p>
            </td>
          </tr>
          <tr>
            <td class="tdrow2">
              <div align="center">
			    <input type="hidden" name="step_prev" value="">
                <input type="button" name="step_next" value="下一步(F2blog数据库设置)" onclick="onclick_step(this.form,'1')">
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第一步: F2blog 数据库设置 (1/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td width="100" class="tdrow1"><strong>服务器</strong></td>
            <td width="350" class="tdrow2">
              <input type="text" class="textbox" name="host" value="<?=($_SESSION['host'])?$_SESSION['host']:"localhost"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库名称</strong></td>
            <td class="tdrow2">
              <input type="text" name="name" class="textbox" value="f2blog">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库用户名</strong></td>
            <td class="tdrow2">
              <input type="text" name="user" class="textbox" value="<?=($_SESSION['user'])?$_SESSION['user']:"root"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库密码</strong></td>
            <td class="tdrow2">
              <input type="password" name="pass" class="textbox" value="<?=($_SESSION['pass'])?$_SESSION['pass']:""?>">
            </td>
          </tr>    
          <tr>
            <td class="tdrow1"><strong>数据库前辍</strong></td>
            <td class="tdrow2">
              <input type="text" name="prefix" class="textbox" value="f2blog_">
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第二步: 选择要汇入的数据源 (2/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2" colspan="2">
			<?if ($step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;数据库连接成功，选择汇入的数据源，单击“下一步”读取数据源！<?}?>
            <?if (!$step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">数据库连接失败，单击“上一步”修改数据库设置！<?}?> 
			</td>
          </tr>
		  <?if ($step_result){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="radio" name="source" value="server" checked></td>
            <td width="350" class="tdrow2"><strong>服务器上的f2blog数据源</strong></td>
          </tr>
          <tr>
            <td width="100" class="tdrow1" align="right" valign="top"><input type="radio" name="source" value="client"></td>
            <td width="350" class="tdrow2">
			<strong>本地电脑上载f2blog数据源</strong><br>
			<input type="file" name="myfile" size="28" onclick="Javascript:this.form.source(1).checked='checked'">
			</td>
          </tr>
		  <?}?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(数据库设置)" onclick="onclick_step(this.form,'1')">
				<?if ($step_result){?>
                <input type="button" name="step_next" value="下一步(读取数据源)" onclick="onclick_step(this.form,'3')">
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第三步: 读取数据源 (3/4)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2" colspan="2">
			<?if ($step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;数据源连接成功，单击“下一步”就可以进行数据库汇入了！<?}?>
            <?if (!$step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">数据源连接失败，单击“上一步”继续选择数据源！<?}?> 
			</td>
          </tr>
		  <?if (strpos($source_table,";categories;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="categories" checked></td>
            <td width="350" class="tdrow2"><strong>类别</strong></td>
          </tr>
		  <?}?>
		  <?if (strpos($source_table,";logs;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="logs" checked></td>
            <td width="350" class="tdrow2"><strong>日志</strong></td>
          </tr>
		  <?}?>
		  <?if (strpos($source_table,";guestbook;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="guestbook" checked></td>
            <td width="350" class="tdrow2"><strong>留言簿</strong></td>
          </tr>
		  <?}?>
		  <?if (strpos($source_table,";comments;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="comments" checked></td>
            <td width="350" class="tdrow2"><strong>评论</strong></td>
          </tr>
		  <?}?>
		  <?if (strpos($source_table,";links;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="links" checked></td>
            <td width="350" class="tdrow2"><strong>友情链接</strong></td>
          </tr> 
		  <?}?>
		  <?if (strpos($source_table,";tags;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="tags" checked></td>
            <td width="350" class="tdrow2"><strong>标签TAG</strong></td>
          </tr> 
		  <?}?>
		  <?if (strpos($source_table,";attachments;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="attachments" checked></td>
            <td width="350" class="tdrow2"><strong>附件</strong></td>
          </tr> 
		  <?}?>
		  <?if (strpos($source_table,";filters;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="filters" checked></td>
            <td width="350" class="tdrow2"><strong>过滤器</strong></td>
          </tr> 
		  <?}?>
		  <?if (strpos($source_table,";trackbacks;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="trackbacks" checked></td>
            <td width="350" class="tdrow2"><strong>引用</strong></td>
          </tr> 
		  <?}?>
		  <?if (strpos($source_table,";dailystatistics;")>0){?>
          <tr>
            <td width="100" class="tdrow1" align="right"><input type="checkbox" name="chkData[]" value="dailystatistics" checked></td>
            <td width="350" class="tdrow2"><strong>访问记录</strong></td>
          </tr> 
		  <?}?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(选择数据源)" onclick="onclick_step(this.form,'2')">
				<?if ($step_result){?>
                <input type="button" name="step_next" value="下一步(开始汇入)" onclick="onclick_step(this.form,'4')">
				<?}else{?>
				<input type="hidden" name="step_next" value="">
				<?}?>
				<input type="hidden" name="source_data" value="<?=$source_data?>">
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第四步: 数据汇入 (4/4)</div>
        <table class="table1" align="center" width="100%">
		  <?if (strpos($table,";categories;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>类别</strong></td>
            <td width="350" class="tdrow2"><?if ($result_categories){?>“汇入了<font color="#FF0000"><?=$result_categories?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";logs;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>日志</strong></td>
            <td width="350" class="tdrow2"><?if ($result_logs){?>“汇入了<font color="#FF0000"><?=$result_logs?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";guestbook;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>留言簿</strong></td>
            <td width="350" class="tdrow2"><?if ($result_guestbook){?>“汇入了<font color="#FF0000"><?=$result_guestbook?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";comments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>评论</strong></td>
            <td width="350" class="tdrow2"><?if ($result_comments){?>“汇入了<font color="#FF0000"><?=$result_comments?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>
		  <?}?>
		  <?if (strpos($table,";links;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>友情链接</strong></td>
            <td width="350" class="tdrow2"><?if ($result_links){?>“汇入了<font color="#FF0000"><?=$result_links?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
		  <?if (strpos($table,";tags;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>标签TAG</strong></td>
            <td width="350" class="tdrow2"><?if ($result_tags){?>“汇入了<font color="#FF0000"><?=$result_tags?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
		  <?if (strpos($table,";attachments;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>附件</strong></td>
            <td width="350" class="tdrow2"><?if ($result_attachments){?>“汇入了<font color="#FF0000"><?=$result_attachments?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
		  <?if (strpos($table,";filters;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>过滤器</strong></td>
            <td width="350" class="tdrow2"><?if ($result_filters){?>“汇入了<font color="#FF0000"><?=$result_filters?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
		  <?if (strpos($table,";trackbacks;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>引用</strong></td>
            <td width="350" class="tdrow2"><?if ($result_trackbacks){?>“汇入了<font color="#FF0000"><?=$result_trackbacks?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
		  <?if (strpos($table,";dailystatistics;")>0){?>
          <tr>
            <td width="100" class="tdrow1"><strong>访问记录</strong></td>
            <td width="350" class="tdrow2"><?if ($result_dailystatistics){?>“汇入了<font color="#FF0000"><?=$result_dailystatistics?></font> 条”<?}else{?><font color="#FF0000">“汇入失败”</font><?}?></td>
          </tr>	 
		  <?}?>
          <tr>
            <td class="tdrow2" colspan="2">
              <div align="center">
                数据已汇入完成！接下来，你需要到<a href="../admin/cache.php" style="color:red">f2blog管理后台</a>，运行“高级管理” -> “缓存”来更新blog的cache，以使前台首页显示正常！
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

<?php
@set_time_limit(0);
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
@session_start();
header("Content-Type: text/html; charset=utf-8");

if ($_GET['step']>3){$_GET['step']=3;}

//测试连接
if ($_GET['step']==2){
	if ($_POST['t_dbHost']=="" || $_POST['t_dbName']=="" || $_POST['t_dbUser']=="" || $_POST['t_dbPass']=="" || $_POST['t_dbPrefix']==""){ 
		$step_result=false;
	}else{
		$_SESSION['t_dbHost']=$_POST['t_dbHost'];
		$_SESSION['t_dbName']=$_POST['t_dbName'];
		$_SESSION['t_dbUser']=$_POST['t_dbUser'];
		$_SESSION['t_dbPass']=$_POST['t_dbPass'];
		$_SESSION['t_dbPrefix']=$_POST['t_dbPrefix'];
		$_SESSION['array_errorsql']="";
	}
}

//如果没有数据库设定，则转到数据库设定
if ($_GET['step']>1){
	if (($_SESSION['t_dbHost']=="" || $_SESSION['t_dbName']=="" || $_SESSION['t_dbUser']=="" || $_SESSION['t_dbPass']=="" || $_SESSION['t_dbPrefix']=="")){ 
		$_GET['step']=1;
	}else{
		$mysql_conn=@mysql_connect($_SESSION['t_dbHost'], $_SESSION['t_dbUser'], $_SESSION['t_dbPass']);
		if(@mysql_get_server_info() > '4.1') @mysql_query("SET NAMES 'utf8'");
		if(@mysql_get_server_info() > '5.0.1') @mysql_query("SET sql_mode=''");
		@mysql_select_db($_SESSION['t_dbName']);
		@mysql_query("select id from {$_SESSION['t_dbPrefix']}categories limit 0,1");
		$error=mysql_error();
		if ($error!=""){
			$step_result=false;
			$_GET['step']=2;
		}else{
			$step_result=true;
		}
	}
}

//列出数据文件
if ($_GET['step']==2){
	$f2data_file=array();
	$file_count=array();
	$handle=@opendir("./");
	while (false !== ($file = @readdir($handle))) {	
		if (strpos($file,".sql")>0){
			$file_type=substr($file,strpos($file,".sql"));
			//分卷
			if (strpos($file,"_v")>0){
				$filename=substr($file,0,strpos($file,"_v")).$file_type;						
				$file_count[$filename]++;
				$file_size[$filename]=$file_size[$filename]+filesize($file);
				$file_time[$filename]=filemtime($file);
				if (!in_array($filename,$f2data_file)) $f2data_file[]=$filename;
			}else{
				$f2data_file[]=$file;
				$file_count[$file]=1;						
				$file_size[$file]=filesize($file);
				$file_time[$file]=filemtime($file);
			}
		}			
	}
	closedir($handle);
}

//保存数据
if ($_GET['step']==3){
	$step_result=true;
	@list($f2blog_source,$f2blog_count)=explode("|",$_REQUEST['source']);

	if ($f2blog_source=="" || $f2blog_count<1){
		$step_result=false;
	}else{
		if ($f2blog_count>1){//分卷
			$curr_page=($_GET[curr_page]>1)?$_GET[curr_page]:1;
			$next_page=$curr_page+1;
			$curr_source=str_replace(".sql","_v{$curr_page}.sql",$f2blog_source);
			$next_source=str_replace(".sql","_v{$next_page}.sql",$f2blog_source);
			//echo $curr_source."==".$next_source;
			import($curr_source);
			if (file_exists($next_source)){
				echo NavigatorNextURL("f2blog.php?step=3&curr_page={$next_page}&source={$f2blog_source}|{$f2blog_count}","程序将在3秒钟后自动开始导入第<font color=red>{$next_page}</font>卷文件，共<font color=red>{$f2blog_count}</font>卷，请勿手动中止程序的运行，以免数据库结构受损");
				exit;
			}				
		}else{
			//不分卷
			import($f2blog_source);
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
		if (isNull(form.t_dbHost, '请输入mysql主机名称')) return false;
		if (isNull(form.t_dbName, '请输入F2blog数据库名')) return false;
		if (isNull(form.t_dbUser, '请输入F2blog用户名')) return false;
		if (isNull(form.t_dbPass, '请输入F2blog密码')) return false;
		if (isNull(form.t_dbPrefix, '请输入数据库前辍')) return false;
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
<form name="convert" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

<?php if (!isset($_GET['step']) || $_GET['step']<1){?> 
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
<?php }?>  

<?php if ($_GET['step']==1){?> 
  <br />
  <table align="center" class="tablewrap" cellpadding="0" cellspacing="3" width="450">
    <tr>
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第一步: F2blog 数据库设置 (1/3)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td width="100" class="tdrow1"><strong>服务器</strong></td>
            <td width="350" class="tdrow2">
              <input type="text" class="textbox" name="t_dbHost" value="<?php echo ($_SESSION['t_dbHost'])?$_SESSION['t_dbHost']:"localhost"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库名称</strong></td>
            <td class="tdrow2">
              <input type="text" name="t_dbName" class="textbox" value="f2blog">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库用户名</strong></td>
            <td class="tdrow2">
              <input type="text" name="t_dbUser" class="textbox" value="<?php echo ($_SESSION['t_dbUser'])?$_SESSION['t_dbUser']:"root"?>">
            </td>
          </tr>
          <tr>
            <td class="tdrow1"><strong>数据库密码</strong></td>
            <td class="tdrow2">
              <input type="password" name="t_dbPass" class="textbox" value="<?php echo ($_SESSION['t_dbPass'])?$_SESSION['t_dbPass']:""?>">
            </td>
          </tr>    
          <tr>
            <td class="tdrow1"><strong>数据库前辍</strong></td>
            <td class="tdrow2">
              <input type="text" name="t_dbPrefix" class="textbox" value="f2blog_">
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第二步: 选择要汇入的数据源 (2/3)</div>
        <table class="table1" align="center" width="100%">
          <tr>
            <td class="tdrow2" colspan="5">
			<?php if ($step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;数据库连接成功，选择汇入的数据源，单击“下一步”读取数据源！<?php }?>
            <?php if (!$step_result){?>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">F2blog的数据库连接失败，单击“上一步”修改数据库设置！<?php }?> 
			</td>
          </tr>
		  <?php if ($step_result){?>
          <tr>
            <td width="10" class="tdrow1" align="right">&nbsp;</td>
            <td width="540" class="tdrow2" colspan="4"><strong>服务器上的f2blog数据源（放在tools目录下）</strong></td>
          </tr>
          <tr>
            <td width="10" class="tdrow1" align="right">&nbsp;</td>
            <td width="140" class="tdrow2">数据名</td>
			<td width="80" class="tdrow2">大小</td>
			<td width="50" class="tdrow2">分卷</td>
			<td width="100" class="tdrow2">创建日期</td>
          </tr>
		  <?php foreach ($f2data_file as $key=>$value){?>
          <tr>
            <td width="10" class="tdrow1" align="right"><input type="radio" name="source" value="<?php echo "{$value}|{$file_count[$value]}"?>" <?php echo ($key==0)?"checked":""?> <?php echo (strpos($value,".zip")>0 && !function_exists('gzfile'))?"disabled=\"disabled\"":""?>></td>
            <td width="200" class="tdrow2" nowrap><?php echo $value?></td>
			<td width="100" class="tdrow2"><?php echo formatFileSize($file_size[$value])?></td>
			<td width="50" class="tdrow2"><?php echo $file_count[$value]?></td>
			<td width="100" class="tdrow2"><?php echo gmdate("Y-m-d H:i:s",$file_time[$value]+3600*8)?></td>
          </tr>
		  <?php }}?>
          <tr>
            <td class="tdrow2" colspan="5">
              <div align="center">
                <input type="button" name="step_prev" value="上一步(数据库设置)" onclick="onclick_step(this.form,'1')">
				<?php if ($step_result){?>
                <input type="button" name="step_next" value="下一步(汇入数据)" onclick="onclick_step(this.form,'3')">
				<?}else{?>
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
      <td align="center" class="title">F2blog通用数据汇入</td>
    </tr>
    <tr>
      <td>
        <div class="note">第三步: 数据汇入 (3/3)</div>
        <table class="table1" align="center" width="100%">		  
          <tr>
            <td class="tdrow2" colspan="2">
				<?php if ($step_result==false){?>
				数据源连接出错<br /><br />
                <input type="button" name="step_prev" value="上一步(选择数据源)" onclick="onclick_step(this.form,'2')">	
				<input type="hidden" name="step_next" value="">
				<?php
				}else{
					if (is_array($_SESSION['array_errorsql'])){
						echo "<font color=\"red\">有部分数据汇入失败，共有</font><font color=\"blue\">".count($_SESSION['array_errorsql'])."</font><font color=\"red\">条错误，</font><br />";
						foreach($_SESSION['array_errorsql'] as $error=>$query){
							echo "<br /> <font color=\"red\">错误原因 :</font> ".htmlspecialchars($error);
							echo "<br /> <font color=\"red\">错误代码 :</font> ".htmlspecialchars($query)." <br>";
						}
						$_SESSION['array_errorsql']="";
				?>
						<br /><br />
						如果觉得这些数据不重要，到<a href="../admin/cache.php" style="color:red">f2blog管理后台</a>，运行“高级管理” -> “缓存”来更新blog的cache，以使前台首页显示正常！
				<?php
					}else{
				?>			
					数据已全部汇入完成！<br /><br>接下来，您需要到<a href="../admin/cache.php" style="color:red">f2blog管理后台</a>，运行“高级管理” -> “缓存”来更新blog的cache，以使前台首页显示正常！
				<?php }?>
				<?php }?>
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

function formatFileSize($file_size){
	if ($file_size<=0 || $file_size==""){
		$file_size_view="0 Byte";
	}else{
		if ($file_size<1024){
			$file_size_view="$file_size Byte";
		}else if ($file_size<1048576){
			$file_size_view=round($file_size/1024,2);
			$file_size_view="$file_size_view KB";
		}else{
			$file_size_view=round($file_size/1048576,2);
			$file_size_view="$file_size_view MB";
		}
	}
	return $file_size_view;
}

function import($fname){
	global $mysql_conn,$_SESSION;
	if (strpos($fname,".zip")>0){
		$sqls=gzfile($fname);
	}else{
		$sqls=file($fname);
	}
	$query="";
	foreach($sqls as $sql){
		$sql=trim($sql);
		$sql=str_replace("\r","",$sql);
		$sql=str_replace("\n","",$sql);

		if (substr($sql,0,2)!="--" && $sql!=""){
			if ($lastchar==true && preg_match("/^[INSERT INTO|TRUNCATE TABLE]/",$sql)){
				$query=str_replace("f2blog_",$_SESSION['t_dbPrefix'],$query);
				mysql_query($query);				
				if (mysql_error()!=""){
					$_SESSION['array_errorsql'][mysql_error()]=$query;
				}
				$query="";
				$lastchar=false;
			}
			$query.=$sql;
			if (substr($sql,strlen($sql)-1)==";") $lastchar=true;
		}
	}
	return true;
}

//导航页面
function NavigatorNextURL($url,$content){
	$out=<<<HTML
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<title>$content</title>
	<meta http-equiv="Refresh" content="1;URL=$url" />
	</head>

	<body>
		<span style='font-size:14px;color:blue'>$content</span>
	</body>
	</html>
HTML;
	return $out;
}
?>
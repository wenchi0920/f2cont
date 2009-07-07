<?
//echo phpinfo();

$PATH="./";
include_once("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();

//输出头部信息
$title=$strServerInfo;
dohead($title,"");

$mysqlversion = $DMC->fetchArray($DMC->query("SELECT VERSION() AS version"));
$webserver = $_SERVER['SERVER_SOFTWARE'];
$registerGlobals = (ini_get('register_globals'))?"On":"Off";

?>

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
	  	<div id="updateInfo" style="margin:10px;background:ffffe1;border:1px solid #89441f;padding:4px;display:none"></div>
		<script>
		 var CVersion="<?=blogVersion?>"
		</script>
		<script src="http://www.f2blog.com/f2update.php?language=<?=$settingInfo['language']?>"></script>
		<br>

        <div class="contenttitle"><img src="images/content/info.gif" width="12" height="11">
          <?=$strServerInfo?>
        </div>
		<div id="info">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strCurVersion?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox">F2Blog v<?=blogVersion?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strSoftLastUpdate?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=blogUpdateDate?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strServerType?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=PHP_OS?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strWebServer?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=$webserver?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strPHPVersion?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=PHP_VERSION?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strMysqlVersion?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=$mysqlversion['version']?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strGDVersion?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><?=gd_version()?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox">Register_globals:</td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"> <?=$registerGlobals?></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox">&nbsp;</td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox">&nbsp;</td>
			   </tr>
			   <tr>
				 <td width="30%" align="right" class="leftbox"><?=$strDevTeam?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><a href='http://www.f2blog.com/' target='_blank'>F2Blog.com</a></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strProMant?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox"><a href='http://www.chong.com.hk/' target='_blank'>Terry</a></td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strProgramer?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox">
					<a href='http://joesen.f2blog.com/' target='_blank'>Joesen</a>
					&nbsp;<a href='http://korsen.f2blog.com/' target='_blank'>Harry</a> 				
				</td>
			   </tr>
			   <tr>
				<td width="30%" align="right" class="leftbox"><?=$strDesignerArt?></td>
				<td width="5" nowrap class="rightbox">&nbsp;</td>
				<td width="70%" class="rightbox">
					<a href='http://yudesign.f2blog.com/' target='_blank'>Kembo</a>
					&nbsp;<a href='http://leitin.f2blog.com/' target='_blank'>Thunderrain</a>
					&nbsp;<a href='http://viewz.cn' target='_blank'>Kill13</a>
					&nbsp;<a href='http://ricky.f2blog.com/' target='_blank'>Ricky</a>
				</td>
			   </tr>
			</table>
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
<? dofoot(); ?>

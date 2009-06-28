<?php 
require_once("function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strAboutF2Blog;
$title=$strAboutF2Blog;
dohead($title,"");
require('admin_menu.php');
?>
<body>

<div id="content">
    <div class="contenttitle"><?php echo $title?></div><br />
		<table width="100%"><tr>
		<td width="2%">
		<td width="45%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				 <td class="leftbox" width="<?php echo ($settingInfo['language']=="en")?"15%":"9%"?>"><?php echo $strDevTeam?></td>
				<td class="rightbox" width="9%">F2Blog.com</td>
				<td class="rightbox" width="27%"><a href='http://www.f2blog.com/' target='_blank'>http://www.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strProMant?></td>
				<td nowrap class="rightbox">Terry</td>
				<td class="rightbox"><a href='http://www.chong.com.hk/' target='_blank'>http://www.chong.com.hk/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strProgramer?></td>
				<td nowrap class="rightbox">Harry</td>
				<td class="rightbox"><a href='http://korsen.f2blog.com/' target='_blank'>http://korsen.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Joesen</td>
				<td class="rightbox"><a href='http://joesen.f2blog.com/' target='_blank'>http://joesen.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strDesignerArt?></td>
				<td nowrap class="rightbox">Leison</td>
				<td class="rightbox"><a href='http://leison.f2blog.com/' target='_blank'>http://leison.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Ricky</td>
				<td class="rightbox"><a href='http://ricky.f2blog.com/' target='_blank'>http://ricky.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Kembo</td>
				<td class="rightbox"><a href='http://yudesign.f2blog.com/' target='_blank'>http://yudesign.f2blog.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Killer13</td>
				<td class="rightbox"><a href='http://viewz.cn/' target='_blank'>http://viewz.cn/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strSupporter?></td>
				<td nowrap class="rightbox">Chill</td>
				<td class="rightbox"><a href='http://www.99way.com/' target='_blank'>http://www.99way.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">王猛</td>
				<td class="rightbox"><a href='http://www.81mil.cn/' target='_blank'>http://www.81mil.cn/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Dennis</td>
				<td class="rightbox"><a href='http://www.dennis-kai.com/' target='_blank'>http://www.dennis-kai.com/</a></td>
			   </tr>
			</table>
		</td>
		<td width="3%">
		<td width="45%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td class="leftbox"><?php echo $strTanks?></td>
				<td nowrap class="rightbox">Ruiz</td>
				<td class="rightbox"><a href='http://www.ruizi.net/' target='_blank'>http://www.ruizi.net/</a></td>
			   </tr>
			   <tr>
				<td colspan="3">&nbsp;</td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strZhao1?></td>
				<td nowrap class="rightbox" colspan="2"><?php echo $strZhao2?></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox" colspan="2"><?php echo $strZhao3?></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox" colspan="2"><?php echo $strZhao4?></td>
			   </tr>
			   <tr>
				<td colspan="3">&nbsp;</td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td colspan="2"><a href='http://forum.f2blog.com/forum-11-1.html' target='_blank' style="color:red"><?php echo $strZhao5?></a></td>
			   </tr>
			</table>
			<br />
			<br />
			<br />
			<br />
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td>
				<img src="themes/<?php echo $settingInfo['adminstyle']?>/logo.gif" alt=""><br /><br />
				<?php 
				$end_date=(date("Y")=="2006")?"":"- ".date("Y");
				echo "Version ".blogVersion;
				echo "<br>CopyRight &copy; 2006 $end_date <a href='http://www.f2blog.com' target='_blank'>F2Blog.com</a> All Rights Reserved.";
				?>
				</td>
			   </tr>
			</table>
		</td>
		</table>
</div>

<?php  dofoot(); ?>
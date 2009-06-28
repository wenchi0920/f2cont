<?php 
require_once("function.php");

check_login();

//输出头部信息
$parentM=0;
$mtitle=$strAboutF2Cont;
$title=$strAboutF2Cont;
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
				<td class="rightbox" width="27%"><a href='http://www.f2cont.com/' target='_blank'>http://www.f2cont.com/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strProMant?></td>
				<td nowrap class="rightbox">phtpw</td>
				<td class="rightbox"><a href='http://blog.phptw.idv.tw/' target='_blank'>http://blog.phptw.idv.tw/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox"><?php echo $strProgramer?></td>
				<td nowrap class="rightbox">phtpw</td>
				<td class="rightbox"><a href='http://blog.phptw.idv.tw/' target='_blank'>http://blog.phptw.idv.tw/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">小瓜</td>
				<td class="rightbox"><a href='http://www.tinylog.org/' target='_blank'>http://www.tinylog.org/</a></td>
			   </tr>
			   
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">实验小白鼠</td>
				<td class="rightbox"><a href='http://blog.tgb.net.cn/' target='_blank'>http://blog.tgb.net.cn/</a></td>
			   </tr>
			   
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Kiyomi</td>
				<td class="rightbox"><a href='http://kiyomi.idv.tw/' target='_blank'>http://kiyomi.idv.tw/</a></td>
			   </tr>
			   
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">korui</td>
				<td class="rightbox"><a href='http://guorui.org/' target='_blank'>http://guorui.org/</a></td>
			   </tr>
			   
			   
			   
			   
			   
			   
			   <!--
Enny
剑雪
Jas

-->
			   
			   
			   
			   
			   <tr>
				<td class="leftbox"><?php echo $strDesignerArt?></td>
				<td nowrap class="rightbox">Enny</td>
				<td class="rightbox">&nbsp;</td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">剑雪</td>
				<td class="rightbox">&nbsp;</td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Jas</td>
				<td class="rightbox">&nbsp;</td>
			   </tr>

			   
<!--

傑尼斯
原木 
Yuki 
泡面
naodai(新F2cont 成員   http://xphper.com/
white   http://white.sytes.net/
ljweb  http://ljweb.com.ru 、http://yalove.org/blog  090624 新增 
-->
			   
			   
			   
			   
			   <tr>
				<td class="leftbox"><?php echo $strSupporter?></td>
				<td nowrap class="rightbox">傑尼斯</td>
				<td class="rightbox"><a href="http://www.janis.idv.tw/" target="_blank">http://www.janis.idv.tw/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">原木</td>
				<td class="rightbox"><a href="http://beau.tw" target="_blank">http://beau.tw</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">Yuki</td>
				<td class="rightbox"><a href="http://yukiblog.tw" target="_blank">http://yukiblog.tw</a></td>
			   </tr>
			   			   
<!--

傑尼斯
原木 
Yuki 
泡面
naodai(新F2cont 成員   http://xphper.com/
white   http://white.sytes.net/
ljweb  http://ljweb.com.ru 、http://yalove.org/blog  090624 新增 
-->
			   
				   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">泡面</td>
				<td class="rightbox"><a href="http://www.wx1044.cn" target="_blank">http://www.wx1044.cn</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">naodai</td>
				<td class="rightbox"><a href='http://xphper.com/' target='_blank'>http://xphper.com/</a></td>
			   </tr>
			   
			   
				   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">white</td>
				<td class="rightbox"><a href='http://white.sytes.net/' target='_blank'>http://white.sytes.net/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">ljweb</td>
				<td class="rightbox"><a href='http://yalove.org/blog/' target='_blank'>http://yalove.org/blog/</a></td>
			   </tr>
			   
			   
			   
				   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">风之逸</td>
				<td class="rightbox"><a href='http://www.rainboww.net/' target='_blank'>http://www.rainboww.net/</a></td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td nowrap class="rightbox">puer</td>
				<td class="rightbox"><a href='http://domiso.cn/' target='_blank'>http://domiso.cn/</a></td>
			   </tr>
			   
			   
			</table>
		</td>
		<td width="3%">
		<td width="45%" valign="top">
			<table cellpadding=2 cellspacing=1 width="100%">
			   <tr>
				<td class="leftbox"><?php echo $strTanks?></td>
				<td nowrap class="rightbox">white</td>
				<td class="rightbox"><a href='http://white.sytes.net/' target='_blank'>http://white.sytes.net/</a></td>
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
				<td nowrap class="rightbox" colspan="2"><?php echo $strF2ContZhao4?></td>
			   </tr>
			   <tr>
				<td colspan="3">&nbsp;</td>
			   </tr>
			   <tr>
				<td class="leftbox">&nbsp;</td>
				<td colspan="2"><a href='http://bbs.f2cont.com/forum-15-1.html' target='_blank' style="color:red"><?php echo $strZhao5?></a></td>
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
				echo "<br>CopyRight &copy; 2008 $end_date <a href='http://www.f2cont.com' target='_blank'>F2Cont.com</a> All Rights Reserved.";
				?>
				</td>
			   </tr>
			</table>
		</td>
		</table>
</div>

<?php  dofoot(); ?>
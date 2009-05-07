<?php
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$error=<<<HTML
   <br/>
	<table width="450" height="96" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#efefef" style="border:2px solid #999999; ">
	  <tr bgcolor="#4685C4">
		<td height="22" colspan="3" style='color:#FFFFFF;font-size:12px;letter-spacing:.2em; '>　^_^感谢您使用CC视频展区功能</td>
	  </tr>
	  <tr>
		<td width="206" valign="top" bgcolor="#FFFFFF"><a href="http://union.bokecc.com"><img src=http://union.bokecc.com/images/logo.gif border="0" style="width:195px;height:60px"></a></td>
		<td width="370" colspan="2" rowspan="2" align="center" style="color:#FF0000;font-size:13px; "><h3>!出错提示</h3>
		  您还没填写用户ID．(<a href="http://union.bokecc.com/signup.bo?type=f2blog" target="_blank" style="color:blue">还没注册?</a>)<br/>请后台修改 <b>CC Video 的基本设置</b> 里的用户ID号<a href="Plugins/ccVideo/help.htm#setUser" target="_blank"><b style="color:blue">?</b></a><br>
		  <br>
		  如有疑问请到<a href="http://bbs.bokecc.com" target="_blank" style="font-size:13px;color:#0066CC ">[官方论坛]</a>咨询<BR>
		</p>
		</td>
	  </tr>
	  <tr>
			<td bgcolor="#FFFFFF" ><div style="color:#000099;font-size:13px;line-height:180%; border:1px solid #999999;margin:1px;padding:5px;">【本版列表特点】<br>
		  ●支持搜索引擎抓取，让您的视频更容易的被搜索引擎找到<br>
		  ●采用服务器缓存加速访问<br>
		●集成管理入口，方便您对视频进行编辑，删除管理</div></td>
	  </tr>
	</table>
 <br/>
HTML;

//取得插件ID
if (file_exists("cache/cache_modulesSetting.php")){
	include(F2BLOG_ROOT."./cache/cache_modulesSetting.php");
	if (!empty($plugins_ccVideo) && is_numeric($plugins_ccVideo['ccuser']) && is_numeric($plugins_ccVideo['ccorder'])){
		$ccuser=$plugins_ccVideo['ccuser'];
		$ccorder=$plugins_ccVideo['ccorder'];

		$cururl= $settingInfo['blogUrl']."index.php?load=ccVideo";
		$safeurl = "http://union.bokecc.com/bbslist.bo?sys=f2blog&id=" . $ccuser . "&lc=" . $cururl . "&od=" . $ccorder . "&code=utf8";
		$cclink = "http://union.bokecc.com/bbslist.bo?sys=f2blog&id=" . $ccuser . "&od=" . $ccorder . "&code=utf8";
		$error="";
	}
}

if ($error==""){
?>
<div id="Tbody">
	<script type="text/javascript">
		function resizeVFrame(n){
		 var cFrame = document.getElementById("cc_frame");
		 var cH = parseInt(cFrame.style.height,10);
		 cH += n;
		 cFrame.style.height = cH + "px";
		}
	</script>
	<div>
	  <a onclick="resizeVFrame(100)" href="#" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;" title="放大视频展区">放大</a>&nbsp;&nbsp;
	  <a onclick="resizeVFrame(-100)" href="#" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;" title="缩小视频展区">缩小</a>&nbsp;&nbsp;
	  <a href="<?php echo $cclink?>" target="_blank" title="论坛视频展区 视频在线播放" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;">全屏查看</a>
	</div>
	<iframe style="padding:0px;margin: 0px;align:center;overflow-x:hidden;" src="<?php echo $safeurl?>"  frameBorder="0" width="100%" scrolling="auto" allowTransparency="true" id="cc_frame" style="height:950px"><a href="<?php echo $cclink?>" target="_blank" title="论坛精彩视频荟萃">论坛精彩视频荟萃</a></iframe>
	<div>
	  <a onclick="resizeVFrame(100)" href="#" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;" title="放大视频展区">放大</a>&nbsp;&nbsp;
	  <a onclick="resizeVFrame(-100)" href="#" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;" title="缩小视频展区">缩小</a>&nbsp;&nbsp;
	  <a href="<?php echo $cclink?>" target="_blank" title="论坛视频展区 视频在线播放" style="border:1px solid #DDDDDD;padding:0 5px 0 5px;">全屏查看</a>
	</div>
 </div>
<?php
}else{
	echo $error;
}
?>
 <div style="font: 0px/0px sans-serif;clear: both;display: block"></div>
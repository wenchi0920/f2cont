<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>
  <!--底部-->
  <div id="foot">
    <p>
		<strong><a href="mailto:<?php echo $settingInfo['email']?>"><?php echo $settingInfo['master']?></a></strong> 's blog
		Powered By <a href="http://www.f2cont.com" target="_blank"><strong>F2blog<?php echo blogVersion?></strong></a>
		CopyRight 2006 <?php echo (date("Y")!="2006" && date("Y")>2006)?" - ".date("Y"):""?>
		<a href="http://validator.w3.org/check/referer" target="_blank">XHTML</a> |
		<a href="http://jigsaw.w3.org/css-validator/validator-uri.html" target="_blank">CSS</a> |
		<a href="archives/index.php" target="_blank">Archiver</a> | 
		<a href="googlesitemap.php" target="_blank">Sitemap</a>
	</p>
    <p style="font-size:11px;">		
		<a href="<?php echo $getDefaultSkinInfo['DesignerURL']?>" target="_blank"><strong><?php echo $getDefaultSkinInfo['SkinName']?></strong></a>
		程序維護 By <a href="http://blog.phptw.idv.tw" target="_blank"><strong>墮落程式</strong></a>
		Design by <a href="mailto:<?php echo $getDefaultSkinInfo['DesignerMail']?>"><?php echo $getDefaultSkinInfo['SkinDesigner']?></a> Skin from <?php echo $getDefaultSkinInfo['SkinSource']?>
		<?php 
		/*if ($settingInfo['about']!="") {
			echo "<a href=\"http://www.miibeian.gov.cn\" target=\"_blank\">".$settingInfo['about']."</a>";
		}*/

		if ($settingInfo['footcode']!=""){
			echo htmldecode($settingInfo['footcode']);
		}

		if ($settingInfo['isProgramRun']==1) {
			$mtime = explode(' ', microtime());
			$totaltime = number_format(($mtime[0] + $mtime[1] - $starttime), 6);
			if ($settingInfo['gzipstatus']==1) {
				$gzipstatus="Gzip enabled";
			}else{
				$gzipstatus="";
			}
			echo "<br /> Processed in <b>".$totaltime."</b> second(s), <b>".$DMC->querycount."</b> queries, $gzipstatus<br />\n";
		}
		?>
    </p>
  </div>
</div>
<?php  do_action("f2_footer"); ?>
<link type="text/css" rel="stylesheet" href="SyntaxHighlighter/css/SyntaxHighlighter.css"></link>
<script language="javascript" src="SyntaxHighlighter/js/shCore.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushCSharp.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushXml.js"></script>
<script language="javascript">
dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/js/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('code');
</script>

</body>
</html>
<?php 
$contents=ob_get_contents();
ob_end_clean();
if ($settingInfo['gzipstatus']==1) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}
echo $contents;
//flush;
exit;
?>
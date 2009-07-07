<?
/**
======================================================
	底部页面: Harry Zhang (korsenzhang@gmail.com)
	更新时间: 2006-5-23
======================================================
*/

/** 禁止直接访问该页面 */
if (basename($_SERVER['PHP_SELF']) == "footer.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

if ($settingInfo['isProgramRun']) {
	$mtime = explode(' ', microtime());
	$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
	$debug = "<br> Processed in <b>".$totaltime."</b> second(s), <b>".$DMF->querycount."</b> queries<br>\n";
}
?>
  <!--底部-->
  <div id="foot">
    <p>
		<strong><a href="mailto:<?=$settingInfo['email']?>"><?=$settingInfo['master']?></a></strong> 's blog
		Powered By <a href="http://www.f2blog.com" target="_blank"><strong>F2blog v<?=blogVersion?></strong></a>
		CopyRight 2006 <?=(date("Y")!="2006" && date("Y")>2006)?" - ".date("Y"):""?>
		<a href="http://validator.w3.org/check/referer" target="_blank">xhtml</a> |
		<a href="http://jigsaw.w3.org/css-validator/validator-uri.html">css</a>
	</p>
    <p style="font-size:11px;">
		<a href="<?=$getDefaultSkinInfo['DesignerURL']?>" target="_blank"><strong><?=$getDefaultSkinInfo['SkinName']?></strong></a>
		Design by <a href="mailto:<?=$getDefaultSkinInfo['DesignerMail']?>"><?=$getDefaultSkinInfo['SkinDesigner']?></a>
		<?=$settingInfo['about']?>
		<?=$debug?>
    </p>
  </div>
</div>
</body>
</html>
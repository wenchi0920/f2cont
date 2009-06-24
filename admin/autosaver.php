<?php 
require_once("function.php");

check_login();

$logTitle=$_POST['logTitle'];
$logContent=$_POST['logContent'];
$idforsave=$_POST['idforsave'];
$editor=$_POST['editor'];

if (get_magic_quotes_gpc()) {
	$logContent=stripslashes($logContent);
	$logTitle=stripslashes($logTitle);
}

if ($editor=="ubb"){
	$logContent=ubblogencode($logContent);
}
$logContent=str_replace("'","&#39;",$logContent);

$contents = "\$autosavecontent = array(\r\n";
$contents.="\t'idforsave' => '".$idforsave."',\r\n";
$contents.="\t'editor' => '".$editor."',\r\n";
$contents.="\t'logTitle' => '".$logTitle."',\r\n";
$contents.="\t'logContent' => '".$logContent."',\r\n";
$contents .= ");";

$cachefile = '../cache/cache_autosave.php';
if($fp = fopen($cachefile, 'wbt')) {
	fwrite($fp, "<?php\r\n//F2Blog auto save cache file\r\n".$contents."\r\n\r\n?>");
	fclose($fp);
	chmod($cachefile, 0777);
} else {
	echo "Can not write to cache files, please check directory $cachedir .";
	exit;
}
?>
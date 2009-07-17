<?PHP
//禁止使用Windows记事本修改文件，由此造成的一切使用不正常恕不解答！
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once ("data/config.php");

//Auto detect mirror site
$tmp_host=($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
$config['blogurl']=str_replace('{host}', $tmp_host, $config['blogurl']);

$use_blogalias=false;

if (isset($entryid)) $itemid=floor($entryid);
elseif (isset($_REQUEST['entryid'])) {
	$itemid=floor($_REQUEST['entryid']);
} 

if (!isset($itemid)) {
	if (isset($_REQUEST['blogalias'])) $blogalias=$_REQUEST['blogalias'];
	if ($blogalias) {
		$blogaliasp=addslashes($blogalias);
		$use_blogalias=true;
		$itemid='';
	}
	else {
		$nav=($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : $_ENV["REQUEST_URI"];
		if ($nav && strstr($nav, '.htm')) {
			$nav=basename($nav);
			$itemid=str_replace('.htm', '', $nav);
			if ($itemid=='test') {
				@include_once ("data/cache_latest.php");
				$itemid=$cache_latest_all[0]['blogid'];
			}
		} else $itemid=basename($_SERVER['QUERY_STRING']);
	}
}



//Very Simple Re-direct
$act='read';
require ("index.php");
?>
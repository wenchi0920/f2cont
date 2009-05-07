<?
@error_reporting(0);
include_once("../../include/config.php");
include_once ("../../include/db.php");
include_once ("../../include/global.inc.php");

// 连结数据库
$DMF = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);

$thisid=trim($_REQUEST['voteid']);
if (is_numeric($thisid)) {
	if ($_COOKIE['vote'.$thisid]=="pubvote_".$thisid){
		echo"&back=AL";
	} else {
		setcookie('vote'.$thisid,"pubvote_".$thisid,time()+86400*365,$cookiepath,$cookiedomain);
		
		$mychoose=trim($_REQUEST['mychoose']);
		$cs=explode(",",$mychoose);

		for ($i=0;$i<5;$i++){
			if ($cs[$i]=="true") {
				$k=$i+1;
				$DMF->query("update {$DBPrefix}vote set cs_{$k}_num=cs_{$k}_num+1 where id='$thisid'");
			}
		}

		echo "&back=AC";
	}
}
?>
<? 
@error_reporting(0);
include_once("../../include/config.php");
include_once ("../../include/db.php");

// 连结数据库
$DMF = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);

$thisid=htmlspecialchars(trim($_REQUEST['voteid']), ENT_QUOTES);
if (is_numeric($thisid) or $thisid=="") {
	if ($thisid=="") {
		$sql="select * from {$DBPrefix}vote where oorc!='False' order by id desc limit 0,1";
	} else {
		$sql="select * from {$DBPrefix}vote where id='$thisid'";
	}
	$result=$DMF->query($sql);
	$my=mysql_fetch_array($result);
	$thisid=$my['id'];
	$DMF->query("update {$DBPrefix}vote set votevi=votevi+1 where id='$thisid'");
	
	$totOption=5;
	for ($i=1;$i<6;$i++){
		if ($my['cs_'.$i]=="") { 
			$totOption=$i-1;
			break;
		}
	}

	header('Content-Type: text/xml; charset=utf-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
	echo "<vote>\n";
	echo "<system>\n";
	echo "<voteid>";
	echo $thisid;
	echo "</voteid>\n";
	echo"<voteco><![CDATA[";
	echo $my['voteco'];
	echo "]]></voteco>\n";
	echo "<votevi>";
	echo $my['votevi'];
	echo "</votevi>\n";
	echo "<votebgcolor><![CDATA[";
	$bgcolor=($my['bg_color']=="")?"EEEEEE":$my['bg_color'];
	echo $bgcolor;
	echo "]]></votebgcolor>\n";
	echo "<votewordcolor><![CDATA[";
	$wordcolor=($my['word_color']=="")?"000000":$my['word_color'];
	echo $wordcolor;
	echo "]]></votewordcolor>\n";
	echo "<votecount>";
	echo $totOption;
	echo "</votecount>\n";
	echo "<word_size>";
	$wordsize=($my['word_size']=="")?"12":$my['word_size'];
	echo $wordsize;
	echo "</word_size>\n";
	echo "<sorm>";
	echo $my['sorm'];
	echo "</sorm>\n";
	echo "</system>\n";

	for ($i=2;$i<7;$i++){
		if ($my[$i]=="") { 
			break; 
		} else {
			$k=$i+5;
			echo "<cs>";
			echo"<csco><![CDATA[";
			echo $my[$i];
			echo "]]></csco>\n";
			echo "<csnum>";
			echo $my[$k];
			echo "</csnum>\n";
			echo "</cs>\n";
		}
	}

	$sql="select id from {$DBPrefix}vote where id>'$thisid' and oorc!='False' order by id limit 0,1";
	$result=$DMF->query($sql);
	$numrows=mysql_num_rows($result);
	if ($numrows==0) {
		$nextid="";
	} else {
		$my=mysql_fetch_array($result);
		$nextid=$my['id'];
	}
	echo "<prenext>";
	echo "<next>";
	echo $nextid;
	echo "</next>\n";

	$sql="select id from {$DBPrefix}vote where id<'$thisid' and oorc!='False' order by id desc limit 0,1";
	$result=$DMF->query($sql);
	$numrows=mysql_num_rows($result);
	if ($numrows==0) {
		$previd="";
	} else {
		$my=mysql_fetch_array($result);
		$previd=$my['id'];
	}
	echo "<pre>";
	echo $previd;
	echo "</pre>";
	echo "</prenext>\n";
	echo "</vote>\n";
}
?>
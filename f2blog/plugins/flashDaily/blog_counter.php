<? 
@error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once("../../include/config.php");
include_once ("../../include/db.php");

$count=$_POST['count'];
// 连结数据库
$DMF = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);

//$count=7;
$sql="select visitDate,visits from ".$DBPrefix."dailystatistics order by visitDate desc limit $count";
$result=$DMF->query($sql); 

$i=$count;
while($data=$DMF->fetchArray($result)) {
	echo "&count$i=".$data[visits];
	echo "&date$i=".str_replace("-","",$data[visitDate]);
	echo $i--;
}

for ($j=$i;$j>0;$j--){
	echo "&count$j=0";
	echo "&date$j=".date("Ymd",time()-($i-$j+1)*86400);
	echo $j;
}
?>
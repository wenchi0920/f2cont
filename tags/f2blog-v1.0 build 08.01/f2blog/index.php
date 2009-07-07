<?
include_once("include/function.php");
include_once("include/sidebar.inc.php");

//开启与关闭BLOG
if ($settingInfo['status']){
	header("Content-Type: text/html; charset=utf-8");
	echo "<table width=\"70%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" height=\"100px\"><tr><td>\n";
	echo "<fieldset> \n";
	echo "	<legend>F2blog Status</legend> \n";
	echo "	<div align=\"center\"> \n";
	echo	$settingInfo['closeReason'];
	echo "	</div> \n";
	echo "	</fieldset>	 \n";
	echo "</td></tr></table> \n";
	exit;
}

$load=$_GET['load'];

//读取主体部份的包文件
$borwseTitle="";
switch ($load){
	case "read":
		//增加本日志阅读量
		$id=$_GET['id'];
		if (strpos($_COOKIE['readlog'],";$id;")<1){
			if ($_COOKIE['readlog']!=""){
				$cookie_readlog=$_COOKIE['readlog']."$id;";
			}else{
				$cookie_readlog=";;$id;";
			}			
			setcookie ("readlog", $cookie_readlog, time()+(1*24*3600),$cookie_path);
			$DMF->query("update ".$DBPrefix."logs set viewNums=viewNums+1 WHERE id='$id'");
		}
		$sql="select a.logTitle,b.name from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id";
		$sql.=" where a.saveType=1 and a.id='$id'";
		$result=$DMF->query($sql);
		$numRows=$DMF->numRows($result);
		if ($numRows>=1) {
			$arr_array=$DMF->fetchQueryAll($result);
			$fa=$arr_array[0];
			$borwseTitle=$fa['logTitle'];
		}
		$load_file="include/readlogs.inc.php";
		break;
	case "tags":
		$load_file="include/tags.inc.php";
		$borwseTitle=$strHotTags;
		break;
	case "userinfo":
		$load_file="include/userinfo.inc.php";
		$borwseTitle=$strHotTags;
		break;
	default:
		for ($i=0;$i<count($arrTopModule);$i++){
			if ($load==$arrTopModule[$i]['name'] && strpos($arrTopModule[$i]['pluginPath'],".inc.php")>0){
				$load_file=$arrTopModule[$i]['pluginPath'];
				$borwseTitle=replace_string($arrTopModule[$i]['modTitle']);
				break;
			}
			if ($load==$arrTopModule[$i]['name'] && strpos($arrTopModule[$i]['pluginPath'],".big.php")>0){
				$load_file=$arrTopModule[$i]['pluginPath'];
				$borwseTitle=replace_string($arrTopModule[$i]['modTitle']);
				break;
			}
		}
		//装载默认首页
		if (!isset($load_file)){
			//保存列表／普通显示
			if ($_GET['disType']=="" and $_COOKIE['disType']=="") {
				$disType=$settingInfo['disType'];
			} else if ($_GET['disType']!="") {
				$disType=$_GET['disType'];
				setcookie ("disType", $disType, time()+(1*24*3600),$cookie_path);	
			} else {
				$disType=$_COOKIE['disType'];
			}

			$load="";
			$load_file="include/content.inc.php";
		}
}

//装载头部文件
include_once("header.php");

//如果*.big.php文件，不装载默认侧边栏
if (strpos($load_file,".big.php")>0){
	require($load_file);
}else{
?>
  <!--内容-->
  <div id="Tbody"> 

  	<!--正文-->
    <div id="mainContent">
      <div id="innermainContent">
        <div id="mainContent-topimg"></div>
          <?require("include/read_main.php")?>
          <!--主体部分-->
          <?require($load_file)?>
		  <?require("include/read_main_foot.php")?>
        <div id="mainContent-bottomimg"></div>
      </div>
    </div>
	<!--处理侧边栏-->
    <div id="sidebar">
      <div id="innersidebar">
        <div id="sidebar-topimg"></div>
		<!--侧边栏显示内容-->
        <?require("include/read_sidebar.php")?>
        <div id="sidebar-bottomimg"></div>
      </div>
    </div>
    <div style="clear: both;height:1px;overflow:hidden;margin-top:-1px;"></div>
  </div>
<?}//主体内容?>
<?include_once("footer.php")?>

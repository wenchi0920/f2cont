<?
# 禁止直接访问该$strPage面
if (basename($_SERVER['PHP_SELF']) == "function.php") {
    header("HTTP/1.0 404 Not Found");
	exit;
}

$PHP_SELF=$_SERVER['PHP_SELF'];
$cookie_path=substr($PHP_SELF,0,strrpos($PHP_SELF,"/"));
include_once($plugins_path."include/cache.php");
include_once($plugins_path."include/common.php");
include_once($plugins_path."include/config.php");
include_once($plugins_path."include/db.php");
include_once($plugins_path."include/global.inc.php");

//如果设定config.php文件，则安装。
if ($DBUser=="" || $DBPass=="" || $DBName==""){
	if (!file_exists($plugins_path.'install/install.php')) {
		@header("Content-Type: text/html; charset=utf-8");
		die ("F2blog isn't install success, the reason is: <br>1. Install file 'install/install.php' isn't exists. <br>2. Your mysql setting file 'include/config.php' isn't connect database!<br><br>Please check up the reason.");
	}else{
		header("Location: install/install.php");
	}
}

// 连结数据库
$DMF = new DummyMySQLClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);

//检测是否安装了，如果连接数据库正常表示安装成功了。
if (!$DMF->query("select id from ".$DBPrefix."keywords limit 0,1")){
	if (!file_exists($plugins_path.'install/install.php')) {
		@header("Content-Type: text/html; charset=utf-8");
		die ("F2blog isn't install success, the reason is: <br>1. Install file 'install/install.php' isn't exists. <br>2. Your mysql setting file 'include/config.php' isn't connect database!<br><br>Please check up the reason.");
	}else{
		header("Location: install/install.php");
	}
}else{
	//如果不存在cache，则重新建立Cache。
	if (!file_exists($plugins_path."cache/cache_modules.php")){
		reAllCache();
	}
}

//装载设定文件。
include_once($plugins_path."cache/cache_setting.php");
include_once($plugins_path."cache/cache_modules.php");

/********** 检查插件 **********/
for($i=0;$i<count($arrFuncModule);$i++) {
	if($arrFuncModule[$i]['installDate']!=0) {
		$plugins_include=$plugins_path."plugins/".$arrFuncModule[$i]['name']."/".$arrFuncModule[$i]['name'].".php";
		if (file_exists($plugins_include)){
			include_once($plugins_include);
		}
	}
}
for($i=0;$i<count($arrMainModule);$i++) {
	if($arrMainModule[$i]['installDate']!=0 && $arrMainModule[$i]['pluginPath']=="") {
		$plugins_include=$plugins_path."plugins/".$arrMainModule[$i]['name']."/".$arrMainModule[$i]['name'].".php";
		if (file_exists($plugins_include)){
			include_once($plugins_include);
		}
	}
}
for($i=0;$i<count($arrSideModule);$i++) {
	if($arrSideModule[$i]['installDate']!=0) {
		$plugins_include=$plugins_path."plugins/".$arrSideModule[$i]['name']."/".$arrSideModule[$i]['name'].".php";
		if (file_exists($plugins_include)){
			include_once($plugins_include);
		}
	}
}
for($i=0;$i<count($arrTopModule);$i++) {
	if($arrTopModule[$i]['installDate']!=0) {
		$plugins_include=$plugins_path."plugins/".$arrTopModule[$i]['name']."/".$arrTopModule[$i]['name'].".php";
		if (file_exists($plugins_include)){			
			include_once($plugins_include);
		}
	}
}

//读取皮肤设置
//print_r($arrSideModule);
//echo array_search('skinSwitch', $arrSideModule[0]);
//检测是否开启了skin switch
$skin_switch_info=false;
for ($i=0;$i<count($arrSideModule);$i++){
	if (array_search('skinSwitch', $arrSideModule[$i])=="name"){
		$skin_switch_info=true;
		break;
	}
}
if ($skin_switch_info){	
	if (isset($_POST['skinSelect'])){
		$blogSkins=$_POST['skinSelect'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookie_path);
	}else if (isset($_COOKIE['blogSkins'])){
		$blogSkins=$_COOKIE['blogSkins'];
	}else{
		$blogSkins=$settingInfo['defaultSkin'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookie_path);
	}
}else{
	$blogSkins=$settingInfo['defaultSkin'];
}

if (!file_exists("skins/$blogSkins/global.css")){
	$blogSkins="default";
}

//读取语言包
$curLanguage=$settingInfo['language'];
include_once($plugins_path."include/language/".$curLanguage.".php");

//如果安装文件存在，则不能使用blog
if (file_exists('install/install.php')) {
	@header("Content-Type: text/html; charset=utf-8");
	die ($strNoInstallFile);
}

// 增加访问量
$curDate=gmdate('Y-m-d', time()+3600*$settingInfo['timezone']);
$prevtime=time()-48*3600;
// 删除两天前的访问记录
$empty_sql="delete from ".$DBPrefix."visits where visittime<='$prevtime'";
$DMF->query($empty_sql);

// 是否增加访问记录 (1小时之内相同IP不增加)
$sameip_time=time()-3600;
$same_sql="select * from ".$DBPrefix."visits where visittime<'".time()."' and visittime>='$sameip_time' and ip='".getip()."'";
$same_result=$DMF->query($same_sql);
if ($DMF->numRows()==0) {
	$DMF->query("insert into ".$DBPrefix."visits (ip,visittime) values ('".getip()."','".time()."')");
	
	$query   = $DMF->query("SELECT visitDate FROM ".$DBPrefix."dailystatistics WHERE visitDate='".$curDate."'");
	$num     = $DMF->numRows();
	if($num == 0) {
		$DMF->query("insert into ".$DBPrefix."dailystatistics (visitDate,visits) values ('$curDate','1')");
	} else {
		$DMF->query("UPDATE ".$DBPrefix."dailystatistics SET visits = visits+1 where visitDate='$curDate'");
	}
	
	add_bloginfo("visitNums","adding",1);
}

// 统计在线人数 (以1小时内)
$online_time=time()-3600;
$online_sql="select * from ".$DBPrefix."visits where visittime<='".time()."' and visittime>='$online_time'";
$online_result=$DMF->query($online_sql);
$online_count=$DMF->numRows();


// 增加Blog基本信息中的日志，评论等数量
function add_bloginfo($field,$type,$value) {
	global $DMF,$DBPrefix;
	
	$types=($type=="adding")?"+":"-";
	$modify_sql="UPDATE ".$DBPrefix."setting set $field=$field$types$value WHERE id=1";
	$DMF->query($modify_sql);
}

// 取得$table表中符合条件$where的整条记录值
function getRecordValue($table,$where){
	global $DMF, $DBPrefix;

	if ($table!="" && $where!=""){
		//echo "select * from $table where $where";
		$dataInfo = $DMF->fetchArray($DMF->query("select * from $table where $where"));
		if ($dataInfo) {
			$rstr=$dataInfo;
		}else{
			$rstr="";
		}
	}else{
		$rstr="";
	}
	return $rstr;
}

function searchSQL($job,$seekname) {
	global $DMF, $DBPrefix;
	$searchSql="";

	if ($seekname=="") { $job=""; }
	switch($job){
		case "category":
			$sql="select id from ".$DBPrefix."categories where parent='$seekname' or id='$seekname'";
			$result=$DMF->query($sql);
			$str="";
			while($fa=$DMF->fetchArray($result)) {
				$str.=" or a.cateId='".$fa['id']."'";
			}
			$searchSql=" and (".substr($str,4).")";
		break;
		case "tag":
			$searchSql=" and concat(';',a.tags,';') like '%;$seekname;%'";
		break;
		case "calendar":
			$arrDate=explode("|",$seekname);
			$curYear=$arrDate[0];
			$curMonth=$arrDate[1];
			$curDay=$arrDate[2];
			$start = strtotime($curYear.'-'.$curMonth.'-'.$curDay." 00:00:00");
			$end = strtotime($curYear.'-'.$curMonth.'-'.$curDay." 23:59:59");

			$searchSql=" and a.postTime>='$start' and a.postTime<='$end'";
		break;
		case "archives":
			$arrDate=explode("|",$seekname);
			$curYear=$arrDate[0];
			$curMonth=$arrDate[1];
			$start = strtotime($curYear.'-'.$curMonth.'-1');
			if ($curMonth==12) {
				$curMonth=1;
				$curYear=$curYear+1;
			} else {
				$curMonth=$curMonth+1;
			}
			$end   = strtotime($curYear.'-'.$curMonth.'-1');

			$searchSql=" and a.postTime>='$start' and a.postTime<'$end'";
		break;
		case "search":
			$searchSql=" and (a.logTitle like '%$seekname%' or a.logContent like '%$seekname%')";
		break;
		default:
			$searchSql="";
		break;
	}
	return $searchSql;
}

//分解Tags为链接
function tagList($tags){
	$tagsInfo=explode(";",$tags);
	for ($i=0;$i<count($tagsInfo);$i++){
		$str=$str."&nbsp;<a href=\"index.php?job=tag&seekname=".$tagsInfo[$i]."\">".$tagsInfo[$i]."</a>";
	}

	$str=substr($str,6);
	return $str;
}

// 格式化日志内容
function formatBlogContent ($content,$attr,$logId) {
	global $plugins_path;
	$content=str_replace("../attachments","attachments",$content);
	$content=str_replace("alt=\"open_img","onclick=\"open_img",$content);
	$content=str_replace("../editor/plugins/","editor/plugins/",$content);

	if ($attr==0){
		$content=preg_replace("/<!--hideBegin-->(.+?)<!--hideEnd-->/ie", "makeMoreLess('\\1')", $content);
	}

	$reg_search = array(
		"/<!--musicBegin-->(.+?)<!--musicEnd-->/ie",
		"/<!--galleryBegin-->(.+?)<!--galleryEnd-->/ie",
		"/<!--fileBegin-->(.+?)<!--fileEnd-->/ie",
		"/([^\/\"\'\=\>&#39;&quot;])(mms|http|ftp|telnet)\:\/\/(.[^ \r\n\<\"\'\)]+)/is"
	);
	$reg_replace =  array(
		"makemusic('\\1')",
		"makegallery('\\1')",
		"makefile('\\1')",
		"\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>"
	);
	$content=preg_replace($reg_search, $reg_replace, $content);

	$content=repKeyword($content);
	
	//插件
	$content=do_filter("f2_content",$content,$logId);

	return $content;
}

//替換關鍵字
function repKeyword($content){
	global $DMF, $DBPrefix,$strTagsCount;
	
	//默认替换F2Blog
	$patterns[] = "~(?!((<.*?)|(<a.*?)))(f2blog)(?!(([^<>]*?)>)|([^>]*?</a>))~si";
	$replacements[] = "<a href=\"http://www.f2blog.com\" target=\"_blank\" title=\"http://www.f2blog.com\" class=\"KeyWordStyle\">\\4<img src=\"images/f2.gif\" border=\"0\"></a>";

	$result=$DMF->query("select * from ".$DBPrefix."keywords order by id");
	while($dataInfo = $DMF->fetchArray($result)) {
		$linkUrl=$dataInfo['linkUrl'];
		$keyword=$dataInfo['keyword'];
		$linkImage=$dataInfo['linkImage'];
		
		$f2=($linkImage=="")?"images/keyword.gif":"attachments/".$linkImage;

		$patterns[] = "~(?!((<.*?)|(<a.*?)))(".$keyword.")(?!(([^<>]*?)>)|([^>]*?</a>))~si";
		$replacements[] = "<a href=\"".$linkUrl."\" target=\"_blank\" title=\"".$linkUrl."\" class=\"KeyWordStyle\">\\4<img src=\"".$f2."\" border=\"0\"></a>";
	}

	/*/ 替换Tags
	$result=$DMF->query("select * from ".$DBPrefix."tags order by id");
	while($dataInfo = $DMF->fetchArray($result)) {
		$name=$dataInfo['name'];
		$f2="images/keyword.gif";
		$strDayLog=$strTagsCount.": ".$dataInfo['logNums'];

		$patterns[] = "~(?!((<.*?)|(<a.*?)))(".$name.")(?!(([^<>]*?)>)|([^>]*?</a>))~si";
		$replacements[] = "<a href=\"index.php?job=tag&seekname=".$name."\" title=\"".$strDayLog."\">\\4<img src=\"".$f2."\" border=\"0\"></a>";
	}*/

	$content = preg_replace($patterns, $replacements, $content, 1);
	return $content;
}

function makeMoreLess($more){
	global $strContentMore,$strContentLess;
	for ($index=0;$index<4;$index++){
		$default_id.=rand(0,9);
	}
	$aid="More".$default_id;
	$bid="MoreLess".$default_id;
	$str="<br><a href=\"Javascript:open_more('$aid','$bid','$strContentMore','$strContentLess')\" id=\"$aid\">$strContentMore</a>\n";
	$str.="<div id=\"$bid\" style=\"display:none\">".stripslashes($more)."</div>";
	return $str;
}

function makegallery($strid) {
	global $DMF,$DBPrefix,$settingInfo;
	require("./admin/admin_config.php");
	for ($index=0;$index<4;$index++){
		$default_id.=rand(0,9);
	}
	$gid="Gallery".$default_id;
	$str="<div id=\"$gid\"></div><script type=\"text/javascript\">var $gid = new F2Gallery(\"$gid\",\"".$settingInfo['language']."\");";

	//echo $strid;
	$arrGallery=explode(",", $strid);
	$arrId=explode("|", $arrGallery[0]);
	$width=$arrGallery[1];
	$height=$arrGallery[2];

	for ($i=0;$i<count($arrId);$i++) {
		$id=$arrId[$i];
		if ($id!=""){
			//echo $id."==".$width."==".$height."===<br>";
			//图片地址有可能是ID，也可能是文件路径。
			$attInfo=getRecordValue($DBPrefix."attachments"," id='$id' or name like '%$id'");
			if ($attInfo['fileWidth']>$content_width){				
				$attInfo['fileHeight']=$attInfo['fileHeight']*$content_width/$attInfo['fileWidth'];
				$attInfo['fileWidth']=$content_width;
			}
			$arrTitle=explode(".", $attInfo['attTitle']);
			$attTitle=$arrTitle[0];
			$str.="$gid.appendImage(\"attachments/".$attInfo['name']."\", \"".$attTitle."\", ".$attInfo['fileWidth'].", ".$attInfo['fileHeight'].");";
		}
	}

	$str.="$gid.show();</script>";
	return $str;
}

function makefile($fileid) {
	global $DMF,$DBPrefix,$strDownFile1,$strDownFile2,$strPlayMusic,$strOnlinePlay,$strOnlineStop,$strDownload,$strRightBtnSave;
	$kkstr="";
	$arrId=explode("|", $fileid);

	for ($i=0;$i<count($arrId);$i++) {
		$aid=$arrId[$i];
		//地址有可能是ID，也可能是文件路径。
		$dataInfo=getRecordValue($DBPrefix."attachments"," id='$aid' or name like '%$aid'");
		
		if($dataInfo!="") {
			if(in_array($dataInfo['fileType'], array('wma','mp3','rm','ra','qt','wmv'))) {
				for ($index=0;$index<4;$index++){
					$default_id.=rand(0,9);
				}
				$fid="music".$default_id;

				$kkstr.="<div class=\"UBBPanel\">";
				$kkstr.="<div class=\"UBBTitle\"><img src=\"images/music.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$dataInfo['attTitle']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-weight:normal;'><a href='".$arrId[0]."'>$strDownload</a>"."&nbsp;($strRightBtnSave)</span></div>";
				$kkstr.="<div class=\"UBBContent\">";
				$kkstr.="<a id=\"".$fid."_href\" href=\"javascript:MediaShow('".$dataInfo['fileType']."','$fid','attachments/".$dataInfo['name']."','400','300','$strOnlinePlay','$strOnlineStop')\">";
				$kkstr.="<img name=\"".$fid."_img\" src=\"images/mm_snd.gif\" style=\"margin:0px 3px -2px 0px\" border=\"0\" alt=\"\"/>";
				$kkstr.="<span id=\"".$fid."_text\">$strOnlinePlay</span></a><div id=\"".$fid."\">";
				$kkstr.="</div></div></div>";
			} else {
				$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/><a href=\"download.php?id=$aid\">".$dataInfo['attTitle']."</a>&nbsp;($strDownFile1".$dataInfo['downloads']."$strDownFile2)";
			}
		}
	}

	return $kkstr;
}

function makemusic($fileid) {
	global $strPlayMusic,$strOnlinePlay,$strOnlineStop,$strDownload,$strRightBtnSave;
	$kkstr="";
	$arrId=explode("|", $fileid);

	for ($index=0;$index<4;$index++){
		$default_id.=rand(0,9);
	}
	$fid="music".$default_id;
	$arrType=explode(".", $arrId[0]);
	$max=count($arrType)-1;
	$file_type=$arrType[$max];
	if (strpos($file_type,"?")>0){
		$file_type=substr($file_type,0,strpos($file_type,"?"));
	}

	$kkstr.="<div class=\"UBBPanel\">";
	$kkstr.="<div class=\"UBBTitle\"><img src=\"images/music.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$arrId[1];
	//$kkstr.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-weight:normal;'><a href='".$arrId[0]."'>$strDownload</a>"."&nbsp;($strRightBtnSave)</span>";
	$kkstr.="</div>";
	$kkstr.="<div class=\"UBBContent\">";
	$kkstr.="<a id=\"".$fid."_href\" href=\"javascript:MediaShow('".$file_type."','$fid','".$arrId[0]."','".$arrId[2]."','".$arrId[3]."','$strOnlinePlay','$strOnlineStop')\">";
	$kkstr.="<img name=\"".$fid."_img\" src=\"images/mm_snd.gif\" style=\"margin:0px 3px -2px 0px\" border=\"0\" alt=\"\"/>";
	$kkstr.="<span id=\"".$fid."_text\">$strOnlinePlay</span></a><div id=\"".$fid."\">";
	$kkstr.="</div></div></div>";

	return $kkstr;
}

// 输出分$strPage信息
function pageBar($gourl){
	global $per_page,$page,$strPrevPage,$strNextPage,$strFirstPage,$strLastPage,$total_num;

	if ($page<1 or $page==""){$page=1;}
	if (!isset($per_screen)) $per_screen=18;//每$strPage显示的$strPage数
	$pages_num=ceil($total_num/$per_page);

	if ($page>$pages_num){$page=$pages_num;}
	$start_record=($page-1)*$per_page+1;
	$end_record=$page*$per_page;
	if ($end_record>$total_num){$end_record=$total_num;}

	$mid = ceil(($per_screen+1)/2); 
	$nav = '';
	if($page<=$mid ) {
		$begin = 1;
	}else if($page > $pages_num-$mid) {
		$begin = $pages_num-$per_screen+1;
	}else {
		$begin = $page-$mid+1;
	}

	if($begin<0) $begin = 1;

	$nav.="<ul><li class=\"pageNumber\">";
	if($page>1){
		$nav.= "<a href=\"$gourl&page=1\" title=\"$strFirstPage\">|<</a>&nbsp;";
	}

	if($page>1){
		$nav.= "<a href=\"$gourl&page=".($page-1)."\" title=\"$strPrevPage\" style=\"text-decoration:none\"><</a>&nbsp;";
	}
	
	$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
	for($i=$begin; $i<$end; $i++) {
		$nav.=($page!=$i)?"<a href=\"$gourl&page=$i\" title=\"$strPageDi $i $strPage\">$i</a>&nbsp;":" <strong>$i</strong>&nbsp;";
	}
	
	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=".($page+1)."\" title=\"$strNextPage\">></a>&nbsp;";
	}

	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=$pages_num\" title=\"$strLastPage\">>|</a>&nbsp;";
	}
	
	$nav.="</li></ul>";

	echo $nav;
}

function replace_filter($content){
	global $DMF,$DBPrefix;

	$result=$DMF->query("select * from ".$DBPrefix."filters where category='1' or category='4' order by id");
	while($dataInfo = $DMF->fetchArray($result)) {
		//$content=preg_replace("/".$dataInfo['name']."/is","[***过滤了非法文字***]",$content);
		if (@strpos(";$content",$dataInfo['name'])>0){
			return $dataInfo['name'];
		}
	}
	return "";
}

function filter_ip($ip){
	global $DMF,$DBPrefix;

	$result=$DMF->query("select * from ".$DBPrefix."filters where category='3' and name='$ip'");
	if ($dataInfo = $DMF->fetchArray($result)) {
		return false;
	}else{
		return true;
	}
}

function tb_extra($logId,$postTime) {
	$str=substr(md5($logId.$postTime),0,6);
	return $str;
}

function total_comments($id,$addsub){
	global $DMF,$DBPrefix;
	
	if ($id!=""){
		$result=$DMF->query("select * from ".$DBPrefix."logs where id='$id'");
		if ($dataInfo = $DMF->fetchArray($result)) {
			$commNums=$dataInfo['commNums'] + ($addsub);
			$updatesql="update ".$DBPrefix."logs set commNums='$commNums' where id='$id'";
			$DMF->query($updatesql);
			return $commNums;
		}else{
			return true;
		}
	}
}

// 取得标签最大及最小值
function getTagRange(){
	global $DMF,$DBPrefix;

	$max=$min=0;
	$result=$DMF->query("select max(logNums) as max,min(logNums) as min from ".$DBPrefix."tags");
	if($result){
		$kk=$DMF->fetchArray($result);
		$max=$kk['max'];
		$min=$kk['min'];
	}
	return array($max,$min);
}

// 根据标签热门程度，来定颜色
function getTagHot($count,$max,$min){
	$dist=$max/3;
	if($count==$min)
		return "#999";
	elseif($count==$max)
		return "#f60";
	elseif($count>=$min+($dist*2))
		return "#069";
	elseif($count>=$min+$dist)
		return "#690";
	else
		return "#09c";
}

/********** 取得$table表中符合条件$where的$getfield字段值 **********/
function getFieldValue($table,$where,$getfield){
	global $DMF, $DBPrefix;
	if ($table!="" && $where!="" && $getfield!=""){
		$where=(strpos($where,"=")>0)?"where $where":$where;
		//echo "select $getfield from $table $where";
		$dataInfo = $DMF->fetchArray($DMF->query("select $getfield from $table $where"));
		if ($dataInfo) {
			$return=$dataInfo[$getfield];
		}else{
			$return="";
		}
	}else{
		$return="";
	}
	return $return;
}

function getLogs($logId){
	global $DMF, $DBPrefix;

	$sql="select * from ".$DBPrefix."logs where id='$logId'";
	$result=$DMF->query($sql);
	$dataInfo = $DMF->fetchArray($result);
	return $dataInfo;
}

?>
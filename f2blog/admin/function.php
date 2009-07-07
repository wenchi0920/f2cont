<?
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "function.php") {
    header("HTTP/1.0 404 Not Found");
}

$PATH="..";
$PHP_SELF=$_SERVER['PHP_SELF'];
require_once("admin_config.php");
require_once("$PATH/include/cache.php");
require_once("$PATH/include/config.php");
require_once("$PATH/include/db.php");
require_once("$PATH/include/common.php");

//如果设定config.php文件，则安装。
if ($DBUser=="" || $DBPass=="" || $DBName==""){
	if (!file_exists("$PATH/install/install.php")) {
		@header("Content-Type: text/html; charset=utf-8");
		die ("F2blog isn't install success, the reason is: <br>1. Install file 'install/install.php' isn't exists. <br>2. Your mysql setting file 'include/config.php' isn't connect database!<br><br>Please check up the reason.");
	}else{
		header("Location: $PATH/install/install.php");
	}
}

/********** Connect Database **********/
$DMC = new DummyMySQLClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);

//检测是否安装了，如果连接数据库正常表示安装成功了。
if (!$DMC->query("select id from ".$DBPrefix."keywords limit 0,1")){
	if (!file_exists("$PATH/install/install.php")) {
		@header("Content-Type: text/html; charset=utf-8");
		die ("F2blog isn't install success, the reason is: <br>1. Install file 'install/install.php' isn't exists. <br>2. Your mysql setting file 'include/config.php' isn't connect database!<br><br>Please check up the reason.");
	}else{
		header("Location: $PATH/install/install.php");
	}
}else{
	//如果不存在cache，则重新建立Cache。
	if (!file_exists("$PATH/cache/cache_modules.php")){
		reAllCache();
	}
}

//装载设定文件。
include_once("$PATH/cache/cache_setting.php");
include_once("$PATH/cache/cache_modules.php");

/********** 读取设定文件Cache **********/
//$settingInfo = stripslashes_array($setting);
$curLanguage=$settingInfo['language'];
include_once("$PATH/include/language/".$curLanguage.".php");

$cookie_path=substr($PHP_SELF,0,strrpos($PHP_SELF,"/"));

//如果安装文件存在，则不能使用blog
if (file_exists('../install/install.php')) {
	@header("Content-Type: text/html; charset=utf-8");
	die ($strNoInstallFile);
}

/********** 检查插件設置文件 **********/
$f2_filter="";
for($i=0;$i<count($arrFuncModule);$i++) {
	if($arrFuncModule[$i]['installDate']!=0) {
		$setFile=$PATH."/plugins/".$arrFuncModule[$i]['name']."/setting.php";
		if(file_exists($setFile)) {
			include_once($setFile);
		}
	}
}
for($i=0;$i<count($arrMainModule);$i++) {
	if($arrMainModule[$i]['installDate']!=0 && $arrMainModule[$i]['pluginPath']=="") {
		$setFile=$PATH."/plugins/".$arrMainModule[$i]['name']."/setting.php";
		if(file_exists($setFile)) {
			include_once($setFile);
		}
	}
}
for($i=0;$i<count($arrSideModule);$i++) {
	if($arrSideModule[$i]['installDate']!=0) {
		$setFile=$PATH."/plugins/".$arrSideModule[$i]['name']."/setting.php";
		if(file_exists($setFile)) {
			include_once($setFile);
		}
	}
}
for($i=0;$i<count($arrTopModule);$i++) {
	if($arrTopModule[$i]['installDate']!=0) {
		$setFile=$PATH."/plugins/".$arrTopModule[$i]['name']."/setting.php";
		if(file_exists($setFile)) {
			include_once($setFile);
		}
	}
}

/********** 检查用户和密码 **********/
function check_user_pw($username, $password, $md5=false) {
	global $DMC, $DBPrefix;
	if ($username!="" && $password!=""){
		$username = trim($username);
		//echo $password;
		if (strpos(";$password","####")>0 && strlen($password)>4){
			$password=substr($password,4);
			$sql="SELECT * FROM ".$DBPrefix."members WHERE hashKey='".md5($password)."' and role='admin'";
		}else{
			$password = ($md5)?$password:md5($password);
			$sql="SELECT * FROM ".$DBPrefix."members WHERE username='".$username."' and password='".$password."' and role='admin'";
		}
		//echo $sql;
		$userInfo = $DMC->fetchArray($DMC->query($sql));
		if (count($userInfo)>0) {
			return $userInfo;
		} else {
			return false;
		}
	}else{
		return false;
	}
}

/********** 是否登录检测 **********/
function check_login(){
	if ($_SESSION['username']!="" && $_SESSION['password']!="") {
		if (!check_user_pw($_SESSION['username'], $_SESSION['password'], true)) { login_page(); }
	}else if ($_COOKIE['username']!="" && $_COOKIE['password']!="" && $_COOKIE['rights']=="admin"){
		$_SESSION['username']=$_COOKIE['username'];
		$_SESSION['password']=$_COOKIE['password'];
		$_SESSION['rights']=$_COOKIE['rights'];
		if (!check_user_pw($_SESSION['username'], $_SESSION['password'], true)) { login_page(); }
	}else{
		login_page();
	}
}

/********** 后台登陆入口页面 **********/
function login_page(){
	global $cookie_path;
	$_SESSION['prelink']="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$_SESSION['username']="";
	$_SESSION['password']="";
	$_SESSION['rights']="";
	setcookie("username","",time()+86400*365,$cookie_path);
	setcookie("password","",time()+86400*365,$cookie_path);
	setcookie("rights","",time()+86400*365,$cookie_path);
	header("Location: index.php");
}

/********** 后台头部信息 **********/
function dohead($title,$page_url){
	global $strPosition,$ActionMessage;
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>$title</title>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"images/css/style.css\">\n";
	echo "<script type=\"text/javascript\" src=\"images/js/lib.js\"></script>\n";
	echo "</head>\n";
	echo "<body> \n";
	/*echo "<body topmargin=\"0\" leftmargin=\"3\">\n";
	if ($ActionMessage and $page_url!=""){
		print_message($ActionMessage);
	}
	if ($page_url!="") { echo "<form action=\"\" method=\"post\" name=\"seekform\">\n"; }
	echo "<table class=tableborder_full height=30 cellSpacing=0 cellPadding=0 width=\"100%\" border=0>\n";
	echo "  <tbody>\n";
	echo "	<tr>\n";
	echo "	  <td class=\"header_left_bg\" width=\"100%\">\n";
	echo "		<span class=\"tablenav_link\">&nbsp;".$strPosition.$title."</span>\n";
	echo "	  </td>\n";
	echo "	  <td width=\"100%\" class="input">";
	if ($page_url!="") { view_page("$page_url"); } else { echo "&nbsp;"; }
	echo "</td>\n";
	echo "	</tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	*/
}

/********** 后台底部版权信息 **********/
function dofoot($copyrights=1){
	if ($copyrights){docopyrights();}
	echo "</body>\n";
	echo "</html>\n";
}

/********** 版权信息 **********/
function docopyrights(){
	global $settingInfo,$starttime,$DMC;
	$isProgramRun=$settingInfo['isProgramRun'];
	if ($isProgramRun) {
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
		$debug = "Processed in <b>".$totaltime."</b> second(s), <b>".$DMC->querycount."</b> queries<br>\n";
	}
	$end_date=(date("Y")=="2006")?"":"- ".date("Y");
	echo "<div class=\"copyright\">\n";	
	echo "CopyRight &copy; 2006 $end_date <a href='http://www.f2blog.com' target='_blank'>F2Blog.com</a> All Rights Reserved. Version ".blogVersion."<br>";
	echo $debug;
	echo "</div>";
}

/********** 输出警告信息 **********/
function print_message($ActionMessage){
	if ($ActionMessage){
		echo "<script language=\"Javascript\"> \n";
		echo "alert(\"$ActionMessage\");";
		echo "</script>";
	}
}

/********** 取得根分类表 **********/
function get_category_parent($field_name,$field_value,$style){
	global $DMC, $DBPrefix, $strCategoryParent;
	$query_result=$DMC->query("SELECT * FROM ".$DBPrefix."categories where parent=0 order by orderNo");
	echo "<select name=\"$field_name\" $style>\n";
	echo "<option value=\"0\" >--- $strCategoryParent ---</option>\n";
	while($fa = $DMC->fetchArray($query_result)){
		$selected=($field_value==$fa['id'])?"selected":"";

		echo "<option value=\"".$fa['id']."\" $selected>".$fa['name']."</option>\n";
	}
	echo "</select>\n";
}

/********** 取得$table表中符合条件$where的$getfield字段值 **********/
function getFieldValue($table,$where,$getfield){
	global $DMC, $DBPrefix;
	if ($table!="" && $where!="" && $getfield!=""){
		$where=(strpos($where,"=")>0)?"where $where":$where;
		//echo "select $getfield from $table $where";
		$dataInfo = $DMC->fetchArray($DMC->query("select $getfield from $table $where"));
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

/********** 取得$table表中符合条件$where的整条记录值 **********/
function getRecordValue($table,$where){
	global $DMC, $DBPrefix;
	if ($table!="" && $where!=""){
		$dataInfo = $DMC->fetchArray($DMC->query("select * from $table where $where"));
		if ($dataInfo) {
			$return=$dataInfo;
		}else{
			$return="";
		}
	}else{
		$return="";
	}
	return $return;
}

/********** 输出分页信息 **********/
function view_page($gourl){
	global $cfg_page_size,$page,$strFirst,$strPrev,$strNext,$strLast,$total_num;

	if ($page<1 or $page==""){$page=1;}
	if (!isset($per_screen)) $per_screen=18;//每$strPage显示的$strPage数
	$pages_num=ceil($total_num/$cfg_page_size);

	if ($page>$pages_num){$page=$pages_num;}
	$start_record=($page-1)*$cfg_page_size+1;
	$end_record=$page*$cfg_page_size;
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

	//$nav.="<ul><li class=\"pageNumber\">";
	if($page>1){
		$nav.= "<a href=\"$gourl&page=1\" title=\"$strFirstPage\">|<</a>&nbsp;";
	}

	if($page>1){
		$nav.= "<a href=\"$gourl&page=".($page-1)."\" title=\"$strPrevPage\" style=\"text-decoration:none\"><</a>&nbsp;";
	}
	
	$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
	for($i=$begin; $i<$end; $i++) {
		$nav.=($page!=$i)?"<a href=\"$gourl&page=$i\" title=\"$strPageDi $i $strPage\">$i</a>&nbsp;":"<strong>$i</strong>&nbsp;";
	}
	
	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=".($page+1)."\" title=\"$strNextPage\">></a>&nbsp;";
	}

	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=$pages_num\" title=\"$strLastPage\">>|</a>&nbsp;";
	}
	
	//$nav.="</li></ul>";

	echo $nav;
}

/********** 输出图片文件的宽，高尺寸 **********/
function get_image_size($temp_file) {
	$size=getimagesize ($temp_file);
	$arrISize=explode("\"",$size[3]);

	return $arrISize;
}

/********** 检查图片文件类型 **********/
function check_image_type($file_name) {
	if ($file_name=="") {
		return true;
	} else {
		$type=getFileType($file_name);
		if ($type!="jpg" and $type!="gif" and $type!="bmp" and $type!="ico" and $type!="png") {
			return false;
		} else {
			return $type;
		}
	}
}

/********** 检查允许上传文件类型 **********/
function checkFileType($file_name) {
	global $cfg_upload_file;
	if ($file_name=="") {
		return false;
	} else {
		$type=getFileType($file_name);
		if (strpos(";".$cfg_upload_file,$type)>0) {
			return true;
		} else {
			return false;
		}
	}
}

/********** 检查允许上传文件大小 **********/
function checkFileSize($size) {
	global $cfg_upload_size;
	if ($size=="" or $size==0) {
		return false;
	} else {
		if ($size<=$cfg_upload_size) {
			return true;
		} else {
			return false;
		}
	}
}

/********** 检查上传目录存不存在，不存在新建目录 **********/
function check_dir($path) {
	if(!is_dir($path)) {
		mkdir($path);
		
		if(!is_dir($path)) {
			return false;
		} else {
			@chmod($path,0777);
			return true;
		}
	} else {
		return true;
	}
}

/********** 上传文件到目录 **********/
function upload_file($temp_file,$file_name,$dir) {
	global $cfg_upload_file;
	$tmp_name=time();
	$return="";
	
	if ($temp_file!="") {
		if (check_dir($dir)) {	
			$type=getFileType($file_name);
			if (checkFileType($file_name)) {
				$file_path="$dir/$tmp_name.$type";					
				$copy_result=@copy($temp_file,"$file_path");
				$return=$tmp_name.".".$type;
			}
		}
	}

	return $return;
}

/********** 取得上传文件的扩展名 **********/
function getFileType($file_name) {
	$type=strtolower(substr($file_name,strrpos($file_name,".")+1));
	return $type;
}

/********** 过滤分类 **********/
function getFiltersCategoryName($category){
	global $strFiltersCategory1,$strFiltersCategory2,$strFiltersCategory3,$strFiltersCategory4;
	switch ($category){
		case "1":$return=$strFiltersCategory1;break;
		case "2":$return=$strFiltersCategory2;break;
		case "3":$return=$strFiltersCategory3;break;
		default:$return=$strFiltersCategory4;break;
	}
	return $return;
}

/********** 格式化文件大小 **********/
function formatFileSize($file_size){
	if ($file_size<=0 || $file_size==""){
		$file_size_view="0 Byte";
	}else{
		if ($file_size<1024){
			$file_size_view="$file_size Byte";
		}else if ($file_size<1048576){
			$file_size_view=round($file_size/1024,2);
			$file_size_view="$file_size_view KB";
		}else{
			$file_size_view=round($file_size/1048576,2);
			$file_size_view="$file_size_view MB";
		}
	}
	return $file_size_view;
}

/********** 删除附件，用于当记录删除后，相关的附件也要删除 **********/
function delAttachments($table,$id,$getfield,$basedir="../attachments/"){
	global $DMC;
	$sql="select $getfield from $table where id='$id'";
	$dataInfo = $DMC->fetchArray($DMC->query($sql));
	if (file_exists($basedir.$dataInfo[$getfield]) and $dataInfo[$getfield]!=""){
		unlink($basedir.$dataInfo[$getfield]);
	}
}

/********** 删除附件和记录 **********/
function delAttAndRecord($table,$parent,$getfield,$basedir="../attachments/"){
	global $DMC;
	$sql="select $getfield from $table where id='$id'";
	$dataInfo = $DMC->fetchArray($DMC->query($sql));
	if (file_exists($basedir.$dataInfo[$getfield])){
		unlink($basedir.$dataInfo[$getfield]);
	}
}

/********** 输出XML Head **********/
function echo_xml_head($error){
	if($error===true)
		$error=0;
	elseif($error===false)
		$error=1;
	header('Content-Type: text/xml');
	print ("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response>\n<error>$error</error>\n</response>");
	exit;
}

/********** 取得类别下拉框 **********/
function category_select($field_name,$field_value,$style){
	global $DMC, $DBPrefix, $strCategoryParent;

	echo "<select name=\"$field_name\" $style>\n";
	echo "<option value=\"\" >--- $strCategoryParent ---</option>\n";

	$query_sql="SELECT * FROM ".$DBPrefix."categories where parent='0' order by orderNo";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		//取得子菜单
		$sub_sql="select * from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' order by orderNo";
		$sub_result=$DMC->query($sub_sql);
		$selected=($field_value==$arr_parent[$i]['id'])?"selected":"";
		echo "<option value=\"".$arr_parent[$i]['id']."\" $selected> ".$arr_parent[$i]['name']."</option>\n";

		while($fa = $DMC->fetchArray($sub_result)){
			$selected=($field_value==$fa['id'])?"selected":"";
			echo "<option value=\"".$fa['id']."\" $selected>|-&nbsp;".$fa['name']."</option>\n";
		}
	}
	echo "</select>\n";
}

/********** 取得Tags下拉框 **********/
function tags_select($field_name,$field_value,$style){
	global $DMC, $DBPrefix, $strTag;

	echo "<select name=\"$field_name\" $style>\n";
	echo "<option value=\"\" >--- $strTag ---</option>\n";

	$query_sql="SELECT * FROM ".$DBPrefix."tags order by logNums desc";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		$selected=($field_value==$arr_parent[$i]['name'])?"selected":"";
		echo "<option value=\"".$arr_parent[$i]['name']."\" $selected>".$arr_parent[$i]['name']."</option>\n";
	}
	echo "</select>\n";
}

/********** 取得天气下拉框 **********/
function weather_select($field_name,$field_value,$style){
	global $arrWeatherOption,$arrWeatherValue;

	echo "<select name=\"$field_name\" $style>\n";
	for ($i=0;$i<count($arrWeatherOption);$i++){
		$selected=($field_value==$arrWeatherValue[$i])?"selected":"";
		echo "<option value=\"".$arrWeatherValue[$i]."\" $selected>".$arrWeatherOption[$i]."</option>\n";
	}
	echo "</select>\n";
}

/********** 取得保存类型 **********/
function savetype_radio($field_name,$field_value){
	global $arrSaveType,$arrSaveValue;
	
	$field_value=($field_value=="")?1:$field_value;
	for ($i=0;$i<count($arrSaveValue);$i++){
		$checked=($field_value==$arrSaveValue[$i])?"checked":"";
		echo "<input name=\"$field_name\" type=\"radio\" value=\"".$arrSaveValue[$i]."\" $checked/>".$arrSaveType[$i]."&nbsp;";
	}
}

/********** 取得标签列表 **********/
/*  $type=S时为输出字符串
/*　$type=A时为输出数组
/**********************************/
function tag_list($type){
	global $DMC,$DBPrefix;
	$str="";
	$arr="";
	$tag_result = $DMC->query("select * from ".$DBPrefix."tags order by logNums desc");
	while ($tagsInfo = $DMC->fetchArray($tag_result)) {		
		if ($type=="S") {
			$str=$str.", <a href=\"javascript: insert_tag('".$tagsInfo['name']."', 'tags');\">".$tagsInfo['name']."</a>";
		} else {
			$arr[]=$tagsInfo['name'];
		}
	}

	if ($type=="S") {
		$str=substr($str,1);
		return $str;
	} else {
		return $arr;
	}
}

/********** Send Trackback **********/
function send_trackback ($url, $title, $excerpt) {
	global $settingInfo;

	$blog_url=$settingInfo['blogUrl'];
	$blog_name=$settingInfo['name'];
	$trackback_url=parse_url($url);

	$out="POST {$trackback_url['path']}".($trackback_url['query'] ? '?'.$trackback_url['query'] : '')." HTTP/1.0\r\n";
	$out.="Host: {$trackback_url['host']}\r\n";
	$out.="Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
	$query_string="nouse=nouse&title=".urlencode($title)."&url=".urlencode($blog_url)."&blog_name=".urlencode($blog_name)."&excerpt=".urlencode($excerpt);
	$out.='Content-Length: '.strlen($query_string)."\r\n";
	$out.="User-Agent: F2Blog\r\n\r\n";
	$out.=$query_string;

	if ($trackback_url['port']=="") {
		$trackback_url['port']=80;
	}

	$fs=@fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 10);
	if (!$fs) return false;
	fputs($fs, $out);
	$http_response = '';
	while(!feof($fs)) {
		$http_response .= fgets($fs, 128);
	}
	@fclose($fs);
	@list($http_headers, $http_content) = @explode("\r\n\r\n", $http_response);
	if (strstr($http_content, "<error>0</error>")) return ("ok");
	elseif (preg_match("/<message>(.+?)<\/message>/is", $http_content, $messages)==1) {
		return (htmlspecialchars($messages[1]));
	}
	else return (htmlspecialchars($http_content));
}

/********** 增加Blog基本信息中的日志，评论等数量 **********/
function add_bloginfo($field,$type,$value) {
	global $DMC,$DBPrefix;
	
	$types=($type=="adding")?"+":"-";
	$modify_sql="UPDATE ".$DBPrefix."setting set $field=$field$types$value WHERE id=1";
	$DMC->query($modify_sql);
}

/********** 更新Category的数量 **********/
function update_cateCount($cateId,$type,$value) {
	global $DMC,$DBPrefix;
	
	$types=($type=="adding")?"+":"-";
	$parent=getFieldValue($DBPrefix."categories","id='".$cateId."'","parent");
	$where=($parent==0)?" id='$cateId'":" (id='$cateId' or id='$parent')";
	$modify_sql="UPDATE ".$DBPrefix."categories set cateCount=cateCount$types$value WHERE $where";
	$DMC->query($modify_sql);
}

/********** 增减一些统计的数量 **********/
function update_num($table,$field,$where,$type,$num) {
	global $DMC,$DBPrefix;
	
	$types=($type=="adding")?"+":"-";
	$modify_sql="UPDATE $table set $field=$field$types$num WHERE $where";
	$DMC->query($modify_sql);
}

/********** 检查记录存不存在 **********/
function check_record($table,$where) {
	global $DMC,$DBPrefix;
	
	$modify_sql="select * from $table where $where";
	$result=$DMC->query($modify_sql);
	$numRows=$DMC->numRows($result);

	if ($numRows<=0) {
		return true;
	} else {
		return false;
	}
}

/**************************************************************
/*                                                            *
/*   插件的函数　                                             *
/*                                                            *
/*************************************************************/

function get_plugins($basedir) {
	$plugins = array ();
	$plugin_root = $basedir;

	// Files in plugins directory
	$plugins_dir = @ dir($plugin_root);
	if ($plugins_dir) {
		while (($file = $plugins_dir->read()) !== false) {
			if (preg_match('|^\.+$|', $file))
				continue;
			if (is_dir($plugin_root.'/'.$file)) {
				$plugins_subdir = @ dir($plugin_root.'/'.$file);
				if ($plugins_subdir) {
					while (($subfile = $plugins_subdir->read()) !== false) {
						if (preg_match('|^\.+$|', $subfile))
							continue;
						if (preg_match('|\.php$|', $subfile))
							$plugin_files[] = "$file/$subfile";
					}
				}
			} else {
				if (preg_match('|\.php$|', $file))
					$plugin_files[] = $file;
			}
		}
	}

	if (!$plugins_dir || !$plugin_files) {
		return $plugins;
	}

	sort($plugin_files);

	foreach ($plugin_files as $plugin_file) {
		if ( !is_readable("$plugin_root/$plugin_file"))
			continue;

		$plugin_data = get_plugin_data("$plugin_root$plugin_file");

		if (empty ($plugin_data['Name'])) {
			continue;
		}

		$plugins[plugin_basename($plugin_file)] = $plugin_data;
	}

	return $plugins;
}

function get_plugin_data($plugin_file) {
	global $strVisitPluginHomepage;
	$plugin_data = implode('', file($plugin_file));
	preg_match("|Plugin Name:(.*)|i", $plugin_data, $plugin_name);
	preg_match("|Plugin URI:(.*)|i", $plugin_data, $plugin_uri);
	preg_match("|Description:(.*)|i", $plugin_data, $description);
	preg_match("|Author:(.*)|i", $plugin_data, $author_name);
	preg_match("|Author URI:(.*)|i", $plugin_data, $author_uri);
	if (preg_match("|Version:(.*)|i", $plugin_data, $version))
		$version = $version[1];
	else
		$version = '';

	$description = $description[1];

	$name = $plugin_name[1];
	$name = trim($name);
	$plugin = $name;

	if ('' != $plugin_uri[1] && '' != $name) {
		$plugin = '<a href="'.$plugin_uri[1].'" title="'.$strVisitPluginHomepage.'">'.$plugin.'</a>';
	}

	if ('' == $author_uri[1]) {
		$author = $author_name[1];
	} else {
		$author = '<a href="'.$author_uri[1].'" title="'.$strVisitPluginHomepage.'">'.$author_name[1].'</a>';
	}
	

	$arr=explode("/",$plugin_file);
	$plugin_root=$arr[0]."/".$arr[1]."/".$arr[2];
	$pfile=$arr[3];

	if (file_exists($plugin_root.'/setting.php')) {
		$setting="setting.php";
	} else {
		$setting="";
	}
	//echo $plugin_root."/setting.php     ".$setting."<br>";
	return array ('Name' => $name, 'Title' => $plugin, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Setting' => $setting ,'Pfile' => $pfile);
}

function plugin_basename($file) {
	$file = preg_replace('|\\\\+|', '\\\\', $file);
	$file = preg_replace('/^.*..[\\\\\/]plugins[\\\\\/]/', '', $file);
	return $file;
}

function install_plugins($arr) {
	global $DMC, $DBPrefix,$strDataExists;
	
	$category=trim($arr['Type']);
	$name=encode(trim($arr['Desc']));
	$plugin=encode(trim($arr['Name']));
	$htmlcode=encode(trim($arr['Code']));
	$pluginpath=trim($arr['Path']);
	$indexOnly=trim($arr['indexOnly']);
	$isInstall=trim($arr['isInstall']);

	$DefaultField=$arr['DefaultField'];
	$DefaultValue=$arr['DefaultValue'];

	switch($category) {
		case "Top":
			$disType="1";
			break;
		case "Side":
			$disType="2";
			break;
		case "Main":
			$disType="3";
			break;
		case "Func":
			$disType="88";
			break;
	}

	$rsexits=getFieldValue($DBPrefix."modules","name='$name' and disType='$disType'","id");
	if ($rsexits!=""){
		$ActionMessage="$strDataExists";
	}else{
		$orderno=getFieldValue($DBPrefix."modules","disType='$disType' order by orderNo desc","orderNo");
		if ($orderno<1){
			$orderno=1;
		}else{
			$orderno++;
		}
		
		$postTime=time();
		$sql="INSERT INTO ".$DBPrefix."modules(name,modTitle,disType,htmlCode,pluginPath,orderNo,installDate,isInstall,indexOnly) VALUES ('$plugin',\"$name\",'$disType',\"$htmlcode\",'$pluginpath','$orderno','$postTime','$isInstall','$indexOnly')";
		$DMC->query($sql);
		$error=$DMC->error();
		if ($error=="") {
			//Set Default value
			$modId=$DMC->insertId();
			
			for($i=0;$i<count($DefaultField);$i++) {
				setPlugSet($modId,$DefaultField[$i],$DefaultValue[$i]);
			}

			$ActionMessage="";
		} else {
			$ActionMessage=$error;
		}
	}
			
	return $ActionMessage;
}

function unstall_plugins($plugin) {
	global $DMC, $DBPrefix;
	
	$modId=getFieldValue($DBPrefix."modules","name='$plugin' and installDate!=0","id");

	$sql="delete from ".$DBPrefix."modsetting where modId='$modId'";
	$DMC->query($sql);

	$sql="delete from ".$DBPrefix."modules where name='$plugin' and installDate!=0";
	$DMC->query($sql);
	$error=$DMC->error();

	if ($error=="") {
		$ActionMessage="";
	} else {
		$ActionMessage=$error;
	}

	return $ActionMessage;
}

function getModSet($plugin) {
	global $DMC, $DBPrefix;
	$modId=getFieldValue($DBPrefix."modules","name='$plugin'","id");
	
	$arr="";
	$sql="select * from ".$DBPrefix."modsetting where modId='$modId' order by id";
	$result=$DMC->query($sql);
	$i=0;
	while($fa = $DMC->fetchArray($result)){
		$keyName=$fa['keyName'];
		$arr[$keyName]=$fa['keyValue'];
		$arr[$i]=$fa['keyValue'];
		$i++;
	}

	return $arr;
}

function setPlugSet($modId,$keyName,$keyValue) {
	global $DMC, $DBPrefix;
	
	$sql="select * from ".$DBPrefix."modsetting where modId='$modId' and keyName='$keyName'";
	$result=$DMC->query($sql);
	$rows=$DMC->numRows();
	
	if($rows<=0 && trim($keyValue)!="") {
		$sql="INSERT INTO ".$DBPrefix."modsetting(modId,keyName,keyValue) VALUES ('$modId','$keyName',\"$keyValue\")";
	} else {
		//echo $keyValue."<br>";
		if (trim($keyValue)==""){
			$sql="delete from ".$DBPrefix."modsetting where modId='$modId' and keyName='$keyName'";
		}else{
			$sql="update ".$DBPrefix."modsetting set keyValue='$keyValue' where modId='$modId' and keyName='$keyName'";
		}
	}
	$DMC->query($sql);
}

/********** 取出Editor Plugins信息 **********/
function getEditorPluginInfo($plugindir,$basedir){
	global $settingInfo;

	$arrPlugin="";
	$wdir="$basedir/editor/plugins/".$plugindir."/";
	$xmlfile=$wdir."plugin.xml";
	
	if (file_exists($xmlfile)){
		if (function_exists(simplexml_load_file)){
			$xml = simplexml_load_file($xmlfile);
			$arrPlugin['PluginName']=$xml->PluginName;
			$arrPlugin['PluginVersion']=$xml->PluginVersion;
			$arrPlugin['FunctionName']=$xml->FunctionName;
			$arrPlugin['FunctionImage']=$xml->FunctionImage;
			$arrPlugin['FunctionDesc']=$xml->FunctionDesc;
			$arrPlugin['pubDate']=$xml->pubDate;
			$arrPlugin['PluginAuthor']=$xml->PluginAuthor;
			$arrPlugin['AuthorURL']=$xml->AuthorURL;
			$arrPlugin['AuthorMail']=$xml->AuthorMail;
		}else{
			include_once "../include/kxparse.php";
			$xmlnav=new kxparse($xmlfile);

			$arrPlugin['PluginName']=$xmlnav->get_tag_text("PluginSet:PluginName","1:1");
			$arrPlugin['PluginVersion']=$xmlnav->get_tag_text("PluginSet:PluginVersion","1:1");
			$arrPlugin['FunctionName']=$xmlnav->get_tag_text("PluginSet:FunctionName","1:1");
			$arrPlugin['FunctionImage']=$xmlnav->get_tag_text("PluginSet:FunctionImage","1:1");
			$arrPlugin['FunctionDesc']=$xmlnav->get_tag_text("PluginSet:FunctionDesc","1:1");
			$arrPlugin['pubDate']=$xmlnav->get_tag_text("PluginSet:pubDate","1:1");
			$arrPlugin['PluginAuthor']=$xmlnav->get_tag_text("PluginSet:PluginAuthor","1:1");
			$arrPlugin['AuthorURL']=$xmlnav->get_tag_text("PluginSet:AuthorURL","1:1");
			$arrPlugin['AuthorMail']=$xmlnav->get_tag_text("PluginSet:AuthorMail","1:1");
		}
	}
	return $arrPlugin;
}

/**  备份数据库写入文件   **/
function write_file($sql,$filename) {
	global $strDataBackupBad,$strDataBackupSuccess;

	if (is_dir("../backup/")){
		if (!$fp = fopen($filename, 'ab')) {
			$ActionMessage="$strDataBackupBad";	
		} else {
			fwrite($fp,$sql);
			fclose($fp);
			$ActionMessage="";
		}
	}else{
		$ActionMessage="$strDataBackupBad";	
	}

	return $ActionMessage;
}

function updateLogsTags($stags,$ttags) {
	global $DMC, $DBPrefix;
	//echo $stags."==".$ttags."<br>";
	$sql="select id,tags from ".$DBPrefix."logs where concat(';',tags,';') like '%;$stags;%' order by id";
	$result=$DMC->query($sql);
	$fa = $DMC->fetchQueryAll($result);
    for ($i=0;$i<count($fa);$i++){
		$id=$fa[$i][id];
		$tags=$fa[$i][tags];

		$tags=str_replace(";$stags;",";$ttags;",";$tags;");
		$tags=substr($tags,1,strlen($tags)-2);

		$update="update ".$DBPrefix."logs set tags='$tags' where id='$id'";
		$DMC->query($update);
		//echo $update."<br>";
	}
}
?>
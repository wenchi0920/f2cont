<?php 
//error_reporting(0);


//读取公共文件
include_once(substr(dirname(__FILE__), 0, -5)."./include/common.php");

//初始化常
$_GET['action']=isset($_GET['action'])?$_GET['action']:"";
$_GET['order']=isset($_GET['order'])?$_GET['order']:"";
$_GET['page']=isset($_GET['page'])?$_GET['page']:1;
$_REQUEST['seekname']=isset($_REQUEST['seekname'])?$_REQUEST['seekname']:"";
$_GET['mark_id']=isset($_GET['mark_id'])?$_GET['mark_id']:"";
$_GET['id']=isset($_GET['id'])?$_GET['id']:"";
$_GET['showmode']=isset($_GET['showmode'])?$_GET['showmode']:"";
$_GET['showmethod']=isset($_GET['showmethod'])?$_GET['showmethod']:"";

//读取语言包
if ($settingInfo['language']=="") $settingInfo['language']="zh_cn";
include_once(F2BLOG_ROOT."./include/language/admin/".basename($settingInfo['language']).".php");
include_once(F2BLOG_ROOT."./include/cache.php");

$home_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"admin"));

/********** 检查插件設置文件（图片水印有使用） **********/
$f2_filter="";
foreach($arrPluginName as $key=>$value){
	$plugins_include=F2BLOG_ROOT."./plugins/".basename($value)."/setting.php";
	if (file_exists($plugins_include)){
		include_once($plugins_include);
	}
}

//以下为后台函数
/********** 检查用户和密码 **********/
function check_user_pw($username, $password, $md5=false) {
	global $DMC, $DBPrefix,$_SESSION;
	if ($username!="" && $password!=""){
		if (strpos(";$password","####")>0 && strlen($password)>4){
			$password=substr($password,4);
			$sql="SELECT * FROM ".$DBPrefix."members WHERE username='".$username."' and hashKey='".md5($password)."' and role in ('admin','editor','author')";
		}else{
			$password = ($md5)?$password:md5($password);
			$sql="SELECT * FROM ".$DBPrefix."members WHERE username='".$username."' and password='".$password."' and role in ('admin','editor','author')";
		}

		if ($userInfo = $DMC->fetchArray($DMC->query($sql))) {
			return $userInfo;
		} else {
			return false;
		}
	}else{
		return false;
	}
}

/********** 是否登录检测及检查权限 **********/
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
	$_SESSION['prelink']="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$_SESSION['username']="";
	$_SESSION['password']="";
	$_SESSION['rights']="";

	setcookie("username","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("password","",time()+86400*365,$cookiepath,$cookiedomain);
	setcookie("rights","",time()+86400*365,$cookiepath,$cookiedomain);

	header("Location: index.php");
}

/********** 后台头部信息 **********/
function dohead($title,$page_url){
	global $strPosition,$ActionMessage,$settingInfo;
	echo "<!DOCTYPE html PUBLIC &quout;-//W3C//DTD XHTML 1.0 Transitional//EN&quout; &quout;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quout;>";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>$title</title>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"themes/{$settingInfo['adminstyle']}/style.css\">\n";
	echo "<script type=\"text/javascript\" src=\"js/lib.js\"></script>\n";
	echo "</head>\n";
	echo "<body> \n";
}

/********** 后台底部版权信息 **********/
function dofoot($copyrights=1){
	if ($copyrights){docopyrights();}
	echo "</body>\n";
	echo "</html>\n";
	$contents=ob_get_contents();
	ob_end_clean();
	echo $contents;
	exit;
}

/********** 版权信息 **********/
function docopyrights(){
	global $settingInfo,$starttime,$DMC;
	if ($settingInfo['isProgramRun']==1) {
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
		$debug = "Processed in <b>".$totaltime."</b> second(s), <b>".$DMC->querycount."</b> queries<br />\n";
	}else{
		$debug = "";
	}
	$end_date=(date("Y")=="2006")?"":"- ".date("Y");
	if (strpos($_SERVER['PHP_SELF'],"index.php")>0){
		echo "<div class=\"indexfooter\">\n";	
	}else{
		echo "<div class=\"footer\">\n";	
	}
	echo "CopyRight &copy; 2006 $end_date <a href='http://www.f2blog.com' target='_blank'>F2Blog.com</a> All Rights Reserved. Version ".blogVersion."<br />";
	echo $debug;
	echo "</div>";
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

/********** 输出分页信息 **********/
function view_page($gourl){
	global $settingInfo,$page,$strFirst,$strPrev,$strNext,$strLast,$total_num;

	if ($page<1 or $page==""){$page=1;}
	if (!isset($per_screen)) $per_screen=$settingInfo['adminPerPage'];//每$strPage显示的$strPage数
	if ($settingInfo['adminPageSize']<1) $settingInfo['adminPageSize']=15;
	$pages_num=ceil($total_num/$settingInfo['adminPageSize']);

	if ($page>$pages_num){$page=$pages_num;}
	$start_record=($page-1)*$settingInfo['adminPageSize']+1;
	$end_record=$page*$settingInfo['adminPageSize'];
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
		$nav.= "<a href=\"$gourl&page=1\" title=\"$strFirst\">|<</a>&nbsp;";
	}

	if($page>1){
		$nav.= "<a href=\"$gourl&page=".($page-1)."\" title=\"$strPrev\" style=\"text-decoration:none\"><</a>&nbsp;";
	}
	
	$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
	for($i=$begin; $i<$end; $i++) {
		$nav.=($page!=$i)?"<a href=\"$gourl&page=$i\">$i</a>&nbsp;":"<strong>$i</strong>&nbsp;";
	}
	
	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=".($page+1)."\" title=\"$strNext\">></a>&nbsp;";
	}

	if($page<$pages_num){
		$nav.= "<a href=\"$gourl&page=$pages_num\" title=\"$strLast\">>|</a>&nbsp;";
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
	global $settingInfo;
	if ($file_name=="") {
		return false;
	} else {
		$type=getFileType($file_name);
		if (strpos(";".$settingInfo['attachType'],$type)>0 && strpos(";php|phtml|php3|jsp|exe|dll|asp|aspx|asa|cgi|fcgi|pl",$type)<1) {
			return true;
		} else {
			return false;
		}
	}
}

/********** 检查上传目录存不存在，不存在新建目录 **********/
function check_dir($path) {
	if (!is_dir($path)) mkdir($path);	
	if (is_writable($path)) {
		return true;
	}elseif(chmod($path,0777)){
		return true;
	} else {
		return false;
	}
}

/********** 上传文件到目录 **********/
function upload_file($temp_file,$file_name,$dir,$tmp_name="") {
	if (empty($tmp_name)) $tmp_name=validCode(10);

	$return="";
	
	if ($temp_file!="") {
		if (check_dir($dir)) {
			$type=getFileType($file_name);
			if (checkFileType($file_name)) {
				$file_path="$dir/$tmp_name.$type";					
				//$copy_result=copy($temp_file,"$file_path");
				
				if(@copy($temp_file, $file_path) || (function_exists('move_uploaded_file') && @move_uploaded_file($temp_file, $file_path))) {
					@unlink($temp_file);
					$check_info = true;
				}

				if(!$check_info && is_readable($temp_file)) {
					$attachedfile=readfromfile($temp_file);

					$fp = @fopen($file_path, 'wb');
					@flock($fp, 2);
					if(@fwrite($fp, $attachedfile)) {
						@unlink($temp_file);
						$check_info = true;
					}
					@fclose($fp);
				}

				$return=($check_info)?$tmp_name.".".$type:"";
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

/********** 删除附件，用于当记录删除后，相关的附件也要删除 **********/
function delAttachments($table,$id,$getfield,$basedir="../attachments/"){
	global $DMC;
	$sql="select $getfield from $table where id='$id'";
	$dataInfo = $DMC->fetchArray($DMC->query($sql));
	if (file_exists($basedir.$dataInfo[$getfield]) and $dataInfo[$getfield]!=""){
		@unlink($basedir.$dataInfo[$getfield]);
	}
}

/********** 取得类别下拉框 **********/
function category_select($field_name,$field_value,$style,$type){
	global $DMC, $DBPrefix, $strCategoryParent,$strRssImportOption6;

	echo "<select name=\"$field_name\" $style>\n";
	if ($type=="rss") {
		echo "<option value=\"\" >--- $strRssImportOption6 ---</option>\n";
	} else {
		echo "<option value=\"\" >--- $strCategoryParent ---</option>\n";
	}

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

/********** 取得链接级别下拉框 **********/
function linkGroup_select($field_name,$field_value,$style){
	global $DMC, $DBPrefix, $strlinkgroupTitle;

	echo "<select name=\"$field_name\" $style>\n";
	echo "<option value=\"\" >--- $strlinkgroupTitle ---</option>\n";

	$query_sql="SELECT * FROM ".$DBPrefix."linkgroup order by id";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		$selected=($field_value==$arr_parent[$i]['id'])?"selected":"";
		echo "<option value=\"".$arr_parent[$i]['id']."\" $selected>".$arr_parent[$i]['name']."</option>\n";
	}
	echo "</select>\n";
}

/********** 取得标签列表 **********/
/*  $type=S时为输出字符串
/*　$type=A时为输出数组
/**********************************/
function tag_list($type){
	global $DMC,$DBPrefix,$settingInfo,$strTree_Open,$strTree_Close;
	$showstr=array();
	$morestr=array();
	$arr=array();
	$i=0;
	$tag_result = $DMC->query("select * from ".$DBPrefix."tags order by logNums desc");
	while ($tagsInfo = $DMC->fetchArray($tag_result)) {		
		if ($type=="S") {
			$tags_name=str_replace("&amp;","&",$tagsInfo['name']);
			$tags_value=str_replace("&#39;","\&#39;",$tags_name);
			if ($settingInfo['tagNums']>$i){
				$showstr[]="<a href=\"javascript: insert_tag('".$tags_value."', 'tags');\">".$tags_name."</a>";
			}else{
				$morestr[]="<a href=\"javascript: insert_tag('".$tags_value."', 'tags');\">".$tags_name."</a>";
			}
		} else {
			$arr[]=$tagsInfo['name'];
		}
		$i++;
	}

	if ($type=="S") {
		$str=implode(",",$showstr);
		if (count($morestr)>0){
			$str.=" <input name=\"btntags\" id=\"btntags\" class=\"btn\" type=\"button\" value=\"{$strTree_Open}/{$strTree_Close}\" onClick=\"if (document.getElementById('tagsMore').style.display==''){document.getElementById('tagsMore').style.display='none';}else{document.getElementById('tagsMore').style.display='';}\"> &nbsp; <span id=\"tagsMore\" style=\"display:none\">".implode(",",$morestr)."</span>";
		}
		return $str;
	} else {
		return $arr;
	}
}

/********** Send Trackback **********/
function send_trackback ($url, $title, $excerpt,$blog_url) {
	global $settingInfo;

	$blog_name=$settingInfo['name'];
	$trackback_url=parse_url($url);

	$out="POST {$trackback_url['path']}".($trackback_url['query'] ? '?'.$trackback_url['query'] : '')." HTTP/1.0\r\n";
	$out.="Host: {$trackback_url['host']}\r\n";
	$out.="Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
	$query_string="nouse=nouse&title=".urlencode($title)."&url=".urlencode($blog_url)."&blog_name=".urlencode($blog_name)."&excerpt=".urlencode($excerpt);
	$out.='Content-Length: '.strlen($query_string)."\r\n";
	$out.="User-Agent: F2Blog\r\n\r\n";
	$out.=$query_string;

	if (empty($trackback_url['port'])) {
		$trackback_url['port']=80;
	}

	$fs=fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 30);
	if (!$fs) {
		return "$errno: unable to connect to http://".$trackback_url['host'].":".$trackback_url['port'];	
		//return false;
	}
	fputs($fs, $out);
	$http_response = '';
	while(!feof($fs)) {
		$http_response .= fgets($fs, 128);
	}
	fclose($fs);
	list($http_headers, $http_content) = explode("\r\n\r\n", $http_response);
	if (strstr($http_content, "<error>0</error>")) return ("ok");
	elseif (preg_match("/<message>(.+?)<\/message>/is", $http_content, $messages)==1) {
		return (htmlspecialchars($messages[1]));
	}
	else return (htmlspecialchars($http_content));
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
/*   插件的函数　                                             *
/*************************************************************/
function get_plugins($basedir) {
	$plugins = array ();
	$plugin_files = array();

    $handle=opendir($basedir); 
    while ($file = readdir($handle)){ 
		if (is_dir($basedir.$file) && $file!="." && $file!=".." && file_exists("$basedir$file/$file.php")){
			$plugin_files[]="$file/$file.php";
		}
    } 
    closedir($handle); 
	sort($plugin_files);
	foreach ($plugin_files as $plugin_file) {
		if ( !is_readable("$basedir/$plugin_file"))
			continue;

		$plugin_data = get_plugin_data("$basedir$plugin_file");

		if (empty ($plugin_data['Name'])) {
			continue;
		}

		$plugins[plugin_basename($plugin_file)] = $plugin_data;
	}

	return $plugins;
}

function get_plugin_data($plugin_file) {
	global $strVisitPluginHomepage;
	$plugin_data = readfromfile($plugin_file);
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

	if (!empty($plugin_uri[1]) && !empty($name)) {
		$plugin = '<a href="'.$plugin_uri[1].'" title="'.$strVisitPluginHomepage.'">'.$plugin.'</a>';
	}

	if (empty($author_uri[1])) {
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
	
	if (file_exists($plugin_root.'/advanced.php')) {
		$advanced="advanced.php";
	} else {
		$advanced="";
	}

	return array ('Name' => $name, 'Title' => $plugin, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Setting' => $setting ,'Advanced' => $advanced ,'Pfile' => $pfile);
}

function plugin_basename($file) {
	$file = preg_replace('|\\\\+|', '\\\\', $file);
	$file = preg_replace('/^.*..[\\\\\/]plugins[\\\\\/]/', '', $file);
	return $file;
}

function install_plugins($arr) {
	global $DMC, $DBPrefix,$strDataExists;
	
	$category=isset($arr['Type'])?encode(trim($arr['Type'])):"";
	$name=isset($arr['Desc'])?encode(trim($arr['Desc'])):"";
	$plugin=isset($arr['Name'])?encode(trim($arr['Name'])):"";
	$htmlcode=isset($arr['Code'])?encode(trim($arr['Code'])):"";
	$pluginpath=isset($arr['Path'])?encode(trim($arr['Path'])):"";
	$indexOnly=(isset($arr['indexOnly']))?trim($arr['indexOnly']):0;
	$isInstall=(isset($arr['isInstall']))?trim($arr['isInstall']):0;

	$DefaultField=isset($arr['DefaultField'])?$arr['DefaultField']:"";
	$DefaultValue=isset($arr['DefaultValue'])?$arr['DefaultValue']:"";

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

	$rsexits=getFieldValue($DBPrefix."modules","name='".encode($plugin)."' and disType='$disType'","id");
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
		$sql="INSERT INTO ".$DBPrefix."modules(name,modTitle,disType,htmlCode,pluginPath,orderNo,installDate,isInstall,indexOnly,isHidden) VALUES ('$plugin','$name','$disType','$htmlcode','$pluginpath','$orderno','$postTime','$isInstall','$indexOnly','0')";
		$DMC->query($sql);
		$error=$DMC->error();
		if ($error=="") {
			//Set Default value
			$modId=$DMC->insertId();
			
			if (is_array($DefaultField)){
				for($i=0,$max=count($DefaultField);$i<$max;$i++) {
					setPlugSet($modId,encode($DefaultField[$i]),encode($DefaultValue[$i]));
				}
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
	
	$modId=getFieldValue($DBPrefix."modules","name='".encode($plugin)."' and installDate!=0","id");

	$sql="delete from ".$DBPrefix."modsetting where modId='$modId'";
	$DMC->query($sql);

	$sql="delete from ".$DBPrefix."modules where name='".encode($plugin)."' and installDate!=0";
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
	$modId=getFieldValue($DBPrefix."modules","name='".encode($plugin)."'","id");
	
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
	
	$sql="select * from ".$DBPrefix."modsetting where modId='$modId' and keyName='".encode($keyName)."'";
	$result=$DMC->query($sql);
	$rows=$DMC->numRows($result);
	
	if($rows<=0 && trim($keyValue)!="") {
		$sql="INSERT INTO ".$DBPrefix."modsetting(modId,keyName,keyValue) VALUES ('$modId','".encode($keyName)."','".encode($keyValue)."')";
	} else {
		//echo $keyValue."<br />";
		if (trim($keyValue)==""){
			$sql="delete from ".$DBPrefix."modsetting where modId='$modId' and keyName='".encode($keyName)."'";
		}else{
			$sql="update ".$DBPrefix."modsetting set keyValue='".encode($keyValue)."' where modId='$modId' and keyName='".encode($keyName)."'";
		}
	}
	$DMC->query($sql);
}

/********** 取出Editor Plugins信息 **********/
function getEditorPluginInfo($plugindir,$basedir){
	global $settingInfo;

	$arrPlugin=array();
	$wdir="$basedir/editor/plugins/".$plugindir."/";
	$xmlfile=$wdir."plugin.xml";
	
	if (file_exists($xmlfile)){
		include_once(F2BLOG_ROOT."./include/xmlparse.inc.php");
		$arrPluginList=xmlArray($xmlfile);

		$arrPlugin['PluginName']=$arrPluginList['PluginName'];
		$arrPlugin['PluginVersion']=$arrPluginList['PluginVersion'];
		$arrPlugin['FunctionName']=$arrPluginList['FunctionName'];
		$arrPlugin['FunctionImage']=$arrPluginList['FunctionImage'];
		$arrPlugin['FunctionDesc']=$arrPluginList['FunctionDesc'];
		$arrPlugin['pubDate']=$arrPluginList['pubDate'];
		$arrPlugin['PluginAuthor']=$arrPluginList['PluginAuthor'];
		$arrPlugin['AuthorURL']=$arrPluginList['AuthorURL'];
		$arrPlugin['AuthorMail']=$arrPluginList['AuthorMail'];
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
	}
}

function addFilterUrl($url) {
	global $DMC, $DBPrefix;

	$weburl=str_replace("http://","",$url);
	$weburl=str_replace("https://","",$weburl);
	$posurl=strpos($weburl,"/");
	if ($posurl>=1) {
		$weburl=substr($weburl,0,$posurl);
	}

	$sql="select * from ".$DBPrefix."filters where name='$weburl' and category='4'";
	$result=$DMC->query($sql);
	$rows=$DMC->numRows($result);
	if ($rows==0) {
		$sql="INSERT INTO ".$DBPrefix."filters(name,category) VALUES ('$weburl','4')";
		$DMC->query($sql);
	}
}

function addSettingValue($setType,$setName,$setField="",$setValue="",$setOption="") {
	global $setInfoResult,$strShow,$strDelete,$DMC,$DBPrefix;

	//如果该值没有设定则增加到数据库
	if ($setField!=""){
		if (!keyExists($setField, $setInfoResult)){
			$DMC->query("insert into ".$DBPrefix."setting(settName,settValue,settAuto) values('$setField','$setValue','0')");
			$setInfoResult[$setField]=$setValue;
		}
		if ($setField=="ncalendar") $setInfoResult[$setField]=str_replace("}{","}\r\n{",dencode($setInfoResult[$setField]));
		if ($setField=="gcalendar") $setInfoResult[$setField]=str_replace("}{","}\r\n{",dencode($setInfoResult[$setField]));
	}

	//echo $setType."==".$setName."==".$setField."==".$setOption."<br />";
	switch ($setType) {
		case 't': //text input
			if ($setField=="linklogo") {
				$changecode="onchange=\"if ((/^http:\/\//i.test(this.value))){document.getElementById('linklogoimg').src=this.value}else{document.getElementById('linklogoimg').src='{$setInfoResult['blogUrl']}'+this.value}\"";
			}else{
				$changecode="";
			}

			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle">
                <input name="$setField" type="text" size="50" class="textbox" value="$setInfoResult[$setField]" $changecode/> $setOption
              </td>
            </tr>
HTMLCODE;
			break;
		case 'ta': //textarea
			$setInfoResult[$setField]=str_replace("<br />","",dencode($setInfoResult[$setField]));
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="top" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="top">&nbsp;</td>
              <td width="75%" align="left">
                <textarea name="$setField" cols="60" rows="6" class="blogeditbox">$setInfoResult[$setField]</textarea> $setOption
              </td>
            </tr>
HTMLCODE;
			break;
		case 'tn': //text input
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle">
                <input name="$setField" type="text" size="5" class="textbox" value="$setInfoResult[$setField]" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false; "/> $setOption
              </td>
            </tr>
HTMLCODE;
			break;
		case 'f': //file input
			if ($setInfoResult[$setField]!="") {
				$show_images = "&nbsp;&nbsp;<a href='../attachments/$setInfoResult[$setField]' target='_blank'>$strShow</a>";
				$show_images .= "&nbsp;&nbsp;<a href='setting.php?delete=$setField'>$strDelete</a>";
			}else{
				$show_images="";
			}
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle">
				<input name="$setField" type="file" size="37" class="filebox" value=""/>
				$show_images  $setOption
              </td>
            </tr>
HTMLCODE;
			break;
		case 'r': //radio button
			$arr_radio="";
			$arrOption=explode("|",$setOption);
			for($i=0;$i<count($arrOption);$i++){
				if (strpos($arrOption[$i],"=>")){
					list($r_name,$r_value)=explode("=>",$arrOption[$i]);
					$checked=($setInfoResult[$setField]==$r_value)?" checked=\"checked\"":"";
					$arr_radio.="<input type=\"radio\" name=\"$setField\" value=\"$r_value\"$checked> $r_name \n";
				}else{
					$arr_radio.=$arrOption[$i];
				}
			}
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle" style="padding-top:7px">$arr_radio</td>
            </tr>
HTMLCODE;
			break;
		case 'c': //check button
			$arr_check="";
			$arrOption=explode("|",$setOption);
			for($i=0;$i<count($arrOption);$i++){
				if (strpos($arrOption[$i],"=>")){
					list($r_name,$r_value)=explode("=>",$arrOption[$i]);
					$checked=(strpos(";$setInfoResult[$setField];",$r_value)>0)?" checked=\"checked\"":"";
					$arr_check.="<input type=\"checkbox\" name=\"{$setField}[]\" value=\"$r_value\"$checked> $r_name \n";
				}else{
					$arr_radio.=$arrOption[$i];
				}
			}
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle" style="padding-top:7px">$arr_check</td>
            </tr>
HTMLCODE;
			break;
		case 'sel': //check button
			$arr_select="";
			$arrOption=explode("|",$setOption);
			for($i=0;$i<count($arrOption);$i++){
				if (strpos($arrOption[$i],"=>")){
					list($r_name,$r_value)=explode("=>",$arrOption[$i]);
					if (strpos($r_name,",")>0){
						$arr_name=explode(",",$r_name);
						$arr_value=explode(",",$r_value);
						for($j=0;$j<count($arr_name);$j++){
							$selected=($setInfoResult[$setField]==$arr_value[$j])?" selected":"";
							$arr_select.="<option value='{$arr_value[$j]}' $selected>{$arr_name[$j]}</option> \n";
						}
					}else{
						$selected=($setInfoResult[$setField]==$r_value)?" selected":"";
						$arr_select.="<option value='$r_value' $selected>$r_name</option> \n";
					}
				}else{
					$arr_select.=$arrOption[$i];
				}
			}
			$output=<<<HTMLCODE
            <tr>
              <td width="25%" align="right" valign="middle" class="input-titleblue">$setName</td>
              <td width="4" align="left" valign="middle">&nbsp;</td>
              <td width="75%" align="left" valign="middle" style="padding-top:7px">
				<select name="$setField" class="blogeditbox">
				$arr_select
				</select>
			  </td>
            </tr>
HTMLCODE;
			break;
		case 'sec': //A separator
			$output=<<<HTMLCODE
            <tr>
              <td width="100%" colspan="3">
				<div class="settitle"> $setName </div>	
			  </td>
            </tr>
HTMLCODE;
			break;
	}
	return $output;
}

//生成缩略图
function generate_thumbnail($attach_thumb=array()) {
	global $settingInfo;

	$return = array();
	$image  = "";
	$thumb_file = $attach_thumb['filepath'];
	$remap  = array( 1 => 'gif', 2 => 'jpg', 3 => 'png' );

	if ( $attach_thumb['thumbswidth'] && $attach_thumb['thumbsheight'] ) {
		$filesize = GetImageSize( $thumb_file );
		
		if ( $filesize[0] > $attach_thumb['thumbswidth'] || $filesize[1] > $attach_thumb['thumbsheight'] ) { 
			$im = scale_image( array(
				"max_width"  => $attach_thumb['thumbswidth'],
				"max_height" => $attach_thumb['thumbsheight'],
				"cur_width"  => $filesize[0],
				"cur_height" => $filesize[1]
			));
			$return['thumbwidth']   = $im['img_width'];
			$return['thumbheight']  = $im['img_height'];
			
			if ( $remap[ $filesize[2] ] == 'gif' ) {
				if (function_exists( 'imagecreatefromgif')) {
					$image = imagecreatefromgif( $thumb_file );
					$type = 'gif';
				}
			} else if ($remap[ $filesize[2] ] == 'png') {
				if (function_exists( 'imagecreatefrompng')) {
					$image = imagecreatefrompng( $thumb_file );
					$type = 'png';
				}
			} else if ($remap[ $filesize[2] ] == 'jpg') {
				if (function_exists( 'imagecreatefromjpeg')) { 
					$image = imagecreatefromjpeg( $thumb_file );
					$type = 'jpg';
				}
			}

			if ( $image ) {
				$thumb = imagecreatetruecolor( $im['img_width'], $im['img_height'] );
				imagecopyresampled($thumb, $image, 0, 0, 0, 0, $im['img_width'], $im['img_height'], $filesize[0], $filesize[1] );
				$file_extension = $attach_thumb['extension'];
				$thumb_filename = str_replace(".".$file_extension,"",$attach_thumb['filename']).'_f2s';

				$curPath=str_replace($attach_thumb['filename'],"",$attach_thumb['filepath']);

				if ( $file_extension == 'gif' ) {
					imagegif( $thumb, $curPath.$thumb_filename.".gif" );
					imagedestroy( $thumb );
				} else if ( $file_extension == 'jpg' ) {
					imagejpeg( $thumb, $curPath.$thumb_filename.".jpg" );
					imagedestroy( $thumb );
				} else if ( $file_extension == 'png' ) {
					imagepng( $thumb, $curPath.$thumb_filename.".png" );
					imagedestroy( $thumb );
				} else {
					$return['thumbfilepath'] = $thumb['filepath'];
					return $return;
				}
				$return['thumbfilepath'] = $curPath.$thumb_filename.'.'.$file_extension;
				return $return;
			} else {
				$return['thumbwidth']    = $im['img_width'];
				$return['thumbheight']   = $im['img_height'];
				$return['thumbfilepath'] = $attach_thumb['filepath'];
				return $return;
			}
		} else { 
			$return['thumbwidth']    = $filesize[0];
			$return['thumbheight']   = $filesize[1];
			$return['thumbfilepath'] = $attach_thumb['filepath'];
			return $return;
		}
	}
}

function scale_image($arg) {
	$ret = array('img_width' => $arg['cur_width'], 'img_height' => $arg['cur_height']);
	if ( $arg['cur_width'] > $arg['max_width'] ) {
		$ret['img_width']  = $arg['max_width'];
		$ret['img_height'] = ceil( ( $arg['cur_height'] * ( ( $arg['max_width'] * 100 ) / $arg['cur_width'] ) ) / 100 );
		$arg['cur_height'] = $ret['img_height'];
		$arg['cur_width']  = $ret['img_width'];
	}
	if ( $arg['cur_height'] > $arg['max_height'] ) {
		$ret['img_height']  = $arg['max_height'];
		$ret['img_width']   = ceil( ( $arg['cur_width'] * ( ( $arg['max_height'] * 100 ) / $arg['cur_height'] ) ) / 100 );
	}
	return $ret;
}

//数组是否存在该键值
function keyExists($key,$array){
	if (function_exists('array_key_exists')){
		return array_key_exists($key,$array);
	}else{
		return key_exists($key,$array);
	}
}
?>
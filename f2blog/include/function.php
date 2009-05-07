<?php 
error_reporting(E_ALL & ~E_NOTICE);

//读取公共文件
include_once(substr(dirname(__FILE__), 0, -7)."./include/common.php");

//读取语言包
if ($settingInfo['language']=="") $settingInfo['language']="zh_cn";
include_once(F2BLOG_ROOT."./include/language/home/".basename($settingInfo['language']).".php");

//读取皮肤设置
//检测是否开启了skin switch
if ($settingInfo['skinSwitch']==0){//0表示开启了skin switch
	include_once(F2BLOG_ROOT."./cache/cache_skinlist.php");

	if (!empty($_POST['skinSelect'])){
		$blogSkins=$_POST['skinSelect'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookiepath,$cookiedomain);
	}elseif (!empty($_COOKIE['blogSkins'])){
		$blogSkins=$_COOKIE['blogSkins'];
	}else{
		$blogSkins=$settingInfo['defaultSkin'];
		setcookie("blogSkins",$blogSkins,time()+86400*365,$cookiepath,$cookiedomain);
	}

	if (!file_exists(F2BLOG_ROOT."./skins/$blogSkins/global.css")) $blogSkins=$settingInfo['defaultSkin'];

	//取得皮肤的信息
	$getDefaultSkinInfo=$skinlistcache[$blogSkins];
}else{
	//取得默认皮肤信息
	include_once(F2BLOG_ROOT."./cache/cache_defaultskin.php");
	$blogSkins=$settingInfo['defaultSkin'];
	$getDefaultSkinInfo=$defaultskincache;
}

//以下为前台函数
function searchSQL($job,$seekname) {
	global $DMC, $DBPrefix,$settingInfo;
	$searchSql="";

	if ($seekname=="") {
		$job="";
	}else{
		$seekname=str_replace("&amp;","&",urldecode($seekname));
	}
	switch($job){
		case "category":
			$searchSql="";
			$sql="select id from ".$DBPrefix."categories where parent='$seekname' or id='$seekname'";
			if ($result=$DMC->fetchQueryAll($DMC->query($sql))){
				$cateSql="";
				foreach($result as $key=>$value){
					if (empty($cateSql)){
						$cateSql="cateId='$value[id]'";
					}else{
						$cateSql.=" or cateId='$value[id]'";
					}
				}
				$searchSql=($key>0)?" and ($cateSql)":" and $cateSql";
			}
		break;
		case "private":
			$searchSql=" and saveType=3";
		break;
		case "tags":
			$searchSql=" and concat(';',a.tags,';') like '%;$seekname;%'";
		break;
		case "calendar"://与archives查找代一样。
		case "archives":
			$curYear=substr($seekname,0,4);
			$curMonth=substr($seekname,4,2);
			$curDay=substr($seekname,6,2);
			if ($curDay>0){
				$start = str_format_time($curYear.'-'.$curMonth.'-'.$curDay." 00:00");
				$end = str_format_time($curYear.'-'.$curMonth.'-'.$curDay." 23:59");
			}elseif($curMonth>0){
				$start = str_format_time($curYear.'-'.$curMonth.'-01 00:00');
				$maxDay=gmdate("t",$start+$settingInfo['timezone']*3600);
				$end = str_format_time($curYear.'-'.$curMonth.'-'.$maxDay." 23:59");
			}else{
				$start = str_format_time($curYear.'-01-01 00:00');
				$end = str_format_time($curYear.'-12-31 23:59');
			}

			$searchSql=" and a.postTime>='$start' and a.postTime<='$end'";
		break;
		case "searchTitle":
			$searchSql=" and (a.logTitle like '%$seekname%')";
		break;
		case "searchContent":
			$searchSql=" and (a.logContent like '%$seekname%')";
		break;
		case "searchAll":
			$searchSql=" and (a.logTitle like '%$seekname%' or a.logContent like '%$seekname%')";
		break;
		default:
			$searchSql="";
		break;
	}
	//echo $searchSql;
	return $searchSql;
}

//分解Tags为链接
function tagList($tags){
	global $settingInfo;
	if ($settingInfo['rewrite']==0) $gourl="index.php?job=tags&amp;seekname=";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/tags-";
	if ($settingInfo['rewrite']==2) $gourl="tags-";
	$tags=dencode($tags);
	$tagsInfo=explode(";",$tags);
	$str="";
	foreach($tagsInfo as $value){
		$value=encode($value);
		$string="<a href=\"$gourl".urlencode($value).$settingInfo['stype']."\">".$value."</a>";
		$str.=($str=="")?$string:"&nbsp;".$string;
	}
	return $str;
}

// 格式化日志内容
function formatBlogContent ($content,$attr,$logId,$statichtml=0) {
	global $settingInfo,$DMC;
	$content=str_replace("../attachments","attachments",$content);
	$content=str_replace("../editor","editor",$content);
	$content=preg_replace("/alt=\"open_img\(&#39;(.+?)&#39;\)\"/is","style=\"cursor:pointer;\" onclick=\"open_img(&#39;\\1&#39;)\" alt=\"\\1\"",$content);
	$content=preg_replace("/alt=\"open_img\(&amp;#39(.+?)&amp;#39\)\"/is","style=\"cursor:pointer;\" onclick=\"open_img(&#39;\\1&#39;)\" alt=\"\\1\"",$content);		
	$content=preg_replace("/alt=\"open_img\('(.+?)'\)\"/is","style=\"cursor:pointer;\" onclick=\"open_img(&#39;\\1&#39;)\" alt=\"\\1\"",$content);		

	if ($attr==0){//展开与隐藏，首页才隐藏，阅读的时候全展开。
		$content=preg_replace("/<!--hideBegin-->(.+?)<!--hideEnd-->/ie", "makeMoreLess('\\1')", $content);
	}

	if (preg_match ("/\[.+?\]/i",$content)){
		$content=ubb($content);
	}

	if (preg_match ("/<!--(.*?)Begin-->(.+?)<!--(.*?)End-->/i",$content)){
		$reg_search = array(
			"/<!--musicBegin-->(.+?)<!--musicEnd-->/ie",
			"/<!--galleryBegin-->(.+?)<!--galleryEnd-->/ie",
			"/<!--mfileBegin-->(.+?)<!--mfileEnd-->/ie",
			"/<!--fileBegin-->(.+?)<!--fileEnd-->/ie",
			"/<!--fileBegin-->(.+?)<!--fileEnd-->/ie"
		);
		$reg_replace =  array(
			"makemusic('\\1')",
			"makegallery('\\1')",
			"makemfile('\\1',$statichtml)",
			"makefile('\\1',$statichtml)"
		);
		$content=preg_replace($reg_search, $reg_replace, $content);
	}

	//插件
	$content=do_filter("f2_content",$content,$logId);
	
	//自动转换连接，非常耗时
	if ($settingInfo['autoUrl']==1){
		$content=preg_replace("/([^\/\"\'\=\>&#39;&quot;])(mms|http|ftp|telnet)\:\/\/(.[^ \r\n\<\"\'\)]+)/is","\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $content);		
	}

	//显示关键字，非常耗时
	if ($settingInfo['showKeyword']==1){
		$content=repKeyword($content);
	}

	//mysql4.0下
	if($DMC->getServerInfo() < '4.1') {
		$content=stripslashes($content);
	}

	return $content;
}

//替換關鍵字
function repKeyword($content){
	global $DMC, $DBPrefix,$strTagsCount;
	
	//默认替换F2Blog
	$patterns[] = "~(?!((<.*?)|(<a.*?)|(://.*?)))(f2blog)(?!(([^<>]*?)>)|([^>]*?</a>))~si";
	$replacements[] = "<a href=\"http://www.f2blog.com\" target=\"_blank\" title=\"http://www.f2blog.com\" class=\"KeyWordStyle\">\\5<img src=\"images/f2.gif\" border=\"0\" alt=\"\"/></a>";

	$keywordscache=$DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."keywords order by id"));
	foreach($keywordscache as $dataInfo){
		$linkUrl=$dataInfo['linkUrl'];
		$keyword=$dataInfo['keyword'];
		$linkImage=$dataInfo['linkImage'];
		
		$f2=($linkImage=="")?"images/keyword.gif":"attachments/".$linkImage;

		$patterns[] = "~(?!((<.*?)|(<a.*?)|(://.*?)))(".$keyword.")(?!(([^<>]*?)>)|([^>]*?</a>))~si";
		$replacements[] = "<a href=\"".$linkUrl."\" target=\"_blank\" title=\"".$linkUrl."\" class=\"KeyWordStyle\">\\5<img src=\"".$f2."\" border=\"0\" alt=\"\"/></a>";
	}

	$content = preg_replace($patterns, $replacements, $content, 1);
	return $content;
}

//显示与隐藏
function makeMoreLess($more){
	global $strContentMore,$strContentLess;
	$default_id="";
	for ($index=0;$index<4;$index++){
		$default_id.=rand(0,9);
	}
	$aid="More".$default_id;
	$bid="MoreLess".$default_id;
	$str="<br /><a href=\"Javascript:open_more('$aid','$bid','$strContentMore','$strContentLess')\" id=\"$aid\">$strContentMore</a>\n";
	$str.="<div id=\"$bid\" style=\"display:none\">".stripslashes($more)."</div>";
	return $str;
}

//图片翻页
function makegallery($strid) {
	global $DMC,$DBPrefix,$settingInfo;
	
	$gid="Gallery".validCode(4);
	$str="<div id=\"$gid\">&nbsp;</div><script type=\"text/javascript\">var $gid = new F2Gallery(\"$gid\",\"".$settingInfo['language']."\");";

	//echo $strid;
	$arrGallery=explode(",", $strid);
	$arrId=explode("|", $arrGallery[0]);
	$cacheattachments=array();

	$find_sql="";
	foreach($arrId as $value){
		if ($find_sql==""){
			$find_sql.="id='$value' or name='$value'";
		}else{
			$find_sql.=" or id='$value' or name='$value'";
		}
	}
	$attachments=$DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."attachments where $find_sql"));
	foreach($attachments as $value){
		$cacheattachments[$value['id']]=$value;
	}
	
	//查找数据
	foreach($arrId as $value){
		if (!empty($cacheattachments[$value])){
			$attInfo=$cacheattachments[$value];
			if (count($attInfo)>0){
				$arrTitle=explode(".", $attInfo['attTitle']);
				$attTitle=$arrTitle[0];
				if ($attInfo['fileWidth']>$settingInfo['img_width']){				
					$attInfo['fileHeight']=$attInfo['fileHeight']*$settingInfo['img_width']/$attInfo['fileWidth'];
					$attInfo['fileWidth']=$settingInfo['img_width'];
				}
				if (strpos($attInfo['name'],"://")<1) $attInfo['name']="attachments/".$attInfo['name'];

				$str.="$gid.appendImage(\"".$attInfo['name']."\", \"".$attTitle."\", ".$attInfo['fileWidth'].", ".$attInfo['fileHeight'].");";
			}
		}
	}

	$str.="$gid.show();</script>";

	return $str;
}

function makefile($fileid,$statichtml=0) {
	global $DMC,$DBPrefix,$strDownFile,$strDownFile1,$strDownFile2,$strPlayMusic,$strOnlinePlay,$strOnlineStop,$strDownload,$strRightBtnSave,$settingInfo;

	$kkstr="";
	$dataInfo=$DMC->fetchArray($DMC->query("select id,name,attTitle,downloads,fileSize,fileWidth,fileHeight from ".$DBPrefix."attachments where id='$fileid' or name='$fileid'"));

	if (is_array($dataInfo)){
		$fileType=strtolower(substr($dataInfo['name'],strrpos($dataInfo['name'],".")+1));

		if(in_array($fileType, array('wma','mp3','rm','ra','qt','wmv','swf','flv','mpg','avi','divx','asf','rmvb'))) {
			$fid="media".validCode(4);
		
			if (strpos($dataInfo['name'],"://")<1) $dataInfo['name']="attachments/".$dataInfo['name'];

			if (strpos(";$settingInfo[ajaxstatus];","M")<1){
				$intWidth=($dataInfo['fileWidth']==0)?400:$dataInfo['fileWidth'];
				$intHeight=($dataInfo['fileHeight']==0)?300:$dataInfo['fileHeight'];

				$kkstr.="<div class=\"UBBPanel\">";
				$kkstr.="<div class=\"UBBTitle\"><img src=\"images/music.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$dataInfo['attTitle'];
				$kkstr.="</div>";
				$kkstr.="<div class=\"UBBContent\">";
				$kkstr.="<a id=\"".$fid."_href\" href=\"javascript:MediaShow('".$fileType."','$fid','".$dataInfo['name']."','$intWidth','$intHeight','$strOnlinePlay','$strOnlineStop')\">";
				$kkstr.="<img name=\"".$fid."_img\" src=\"images/mm_snd.gif\" style=\"margin:0px 3px -2px 0px\" border=\"0\" alt=\"\"/>";
				$kkstr.="<span id=\"".$fid."_text\">$strOnlinePlay</span></a><div id=\"".$fid."\">";
				$kkstr.="</div></div></div>";
			}else{
				$kkstr.="<div class=\"UBBPanel\">";
				$kkstr.="<div class=\"UBBTitle\"><img src=\"images/music.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$dataInfo['attTitle'];
				$kkstr.="</div>";
				$kkstr.="<div class=\"UBBContent\">";
				$kkstr.="<a id=\"".$fid."_href\" href=\"javascript:void(0)\" onclick=\"javascript:f2_ajax_media('f2blog_ajax.php?ajax_display=media&amp;media=$fid&amp;id={$dataInfo['id']}','$fid','$strOnlinePlay','$strOnlineStop')\">";
				$kkstr.="<img name=\"".$fid."_img\" src=\"images/mm_snd.gif\" style=\"margin:0px 3px -2px 0px\" border=\"0\" alt=\"\"/>";
				$kkstr.="<span id=\"".$fid."_text\">$strOnlinePlay</span></a><div id=\"".$fid."\" style=\"display:none;\">";
				$kkstr.="</div></div></div>";
			}
		} else {
			if ($statichtml==1){//静态页面
				$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/><a href=\"download.php?id=".$dataInfo['id']."\">".$dataInfo['attTitle']."</a>&nbsp;(".formatFileSize($dataInfo['fileSize'])." ,{$strDownFile1}<?php echo !empty(\$cachedownload['".$dataInfo['id']."'])?\$cachedownload['".$dataInfo['id']."']:'0'?>$strDownFile2)";
			}else{
				$dataInfo['downloads']=($dataInfo['downloads']!="")?$dataInfo['downloads']:"0";
				$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/><a href=\"download.php?id=".$dataInfo['id']."\">".$dataInfo['attTitle']."</a>&nbsp;(".formatFileSize($dataInfo['fileSize'])." , $strDownFile1".$dataInfo['downloads']."$strDownFile2)";
			}
		}
	}
	return $kkstr;
}

function makemfile($fileid,$statichtml=0) {
	global $DMC,$DBPrefix,$strDownFile,$strDownFile1,$strDownFile2,$strDownload,$strLoginDown;
	$kkstr="";
	$dataInfo=$DMC->fetchArray($DMC->query("select id,attTitle,downloads from ".$DBPrefix."attachments where id='$fileid' or name='$fileid'"));
	if ($statichtml==1){//静态页面
		$kkstr.="<?if (!empty(\$_SESSION['username'])) {?>\n";
		$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/><a href=\"download.php?id=".$dataInfo['id']."\">".$dataInfo['attTitle']."</a>&nbsp;({$strDownFile1}<?=(\$cachedownload['".$dataInfo['id']."']>0)?\$cachedownload['".$dataInfo['id']."']:'0'?>$strDownFile2)";
		$kkstr.="<?}else{?>\n";
		$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/>".$dataInfo['attTitle']."&nbsp;($strLoginDown)";
		$kkstr.="<?}?>\n";
	}else{
		$dataInfo['downloads']=($dataInfo['downloads']!="")?$dataInfo['downloads']:"0";
		if (!empty($_SESSION['username'])) {
			$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/><a href=\"download.php?id=".$dataInfo['id']."\">".$dataInfo['attTitle']."</a>&nbsp;($strDownFile1".$dataInfo['downloads']."$strDownFile2)";
		} else {
			$kkstr.="<img src=\"images/download.gif\" alt=\"$strDownFile\" style=\"margin:0px 2px -4px 0px\"/>".$dataInfo['attTitle']."&nbsp;($strLoginDown)";
		}
	}
	return $kkstr;
}

function makemusic($fileid) {
	global $strPlayMusic,$strOnlinePlay,$strOnlineStop,$strDownload,$strRightBtnSave;
	$kkstr="";		
	$arrId=explode("|", $fileid);
	$arrType=explode(".", $arrId[0]);
	$max=count($arrType)-1;
	$file_type=$arrType[$max];
	if (strpos($file_type,"?")>0){
		$file_type=substr($file_type,0,strpos($file_type,"?"));
	}

	$fid="media".validCode(4);
	$kkstr.="<div class=\"UBBPanel\">";
	$kkstr.="<div class=\"UBBTitle\"><img src=\"images/music.gif\" alt=\"\" style=\"margin:0px 2px -3px 0px\" border=\"0\"/>$strPlayMusic -- ".$arrId[1];
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
	global $per_page,$page,$strPrevPage,$strNextPage,$strFirstPage,$strLastPage,$total_num,$settingInfo;
	
	if ($page<1 or $page==""){$page=1;}
	if (!isset($per_screen)) $per_screen=$settingInfo['logspage'];//每页显示的$strPage数
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

	if ($settingInfo['rewrite']==0){
		$last_char=substr($gourl,strlen($gourl)-1);
		if ($last_char=="&" || $last_char=="?"){
			$page_url=$gourl."page=";
		}else{
			$page_url=(strpos($gourl,"?")>0)?$gourl."&amp;page=":$gourl."?page=";
		}
	}else{
		$page_url=($gourl=="" || $gourl=="rewrite.php/")?$gourl:$gourl."-";
	}

	$comm_top=(strpos(";$page_url","read")>0)?"#comm_top":"";

	if($page>1){
		$nav.= "<a href=\"{$page_url}1{$settingInfo['stype']}{$comm_top}\" title=\"$strFirstPage\">|<</a>&nbsp;";
	}

	if($page>1){
		$nav.= "<a href=\"{$page_url}".($page-1)."{$settingInfo['stype']}{$comm_top}\" title=\"$strPrevPage\" style=\"text-decoration:none\"><</a>&nbsp;";
	}
		
	$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
	for($i=$begin; $i<$end; $i++) {
		$nav.=($page!=$i)?"<a href=\"{$page_url}$i{$settingInfo['stype']}$comm_top\">$i</a>&nbsp;":"<strong>$i</strong>&nbsp;";
	}
		
	if($page<$pages_num){
		$nav.= "<a href=\"{$page_url}".($page+1)."{$settingInfo['stype']}$comm_top\" title=\"$strNextPage\">></a>&nbsp;";
	}

	if($page<$pages_num){
		$nav.= "<a href=\"{$page_url}{$pages_num}{$settingInfo['stype']}{$comm_top}\" title=\"$strLastPage\">>|</a>&nbsp;";
	}
		
	$nav.="</li></ul>";
	echo $nav;
}

function replace_filter($content){
	include_once("cache/cache_filters.php");
	if (!empty($filtercache1) && is_array($filtercache1)){
		foreach($filtercache1 as $value){
			if (strpos(";$content",$value)>0){
				return $value;
			}
		}
	}
	if (!empty($filtercache4) && is_array($filtercache4)){
		foreach($filtercache4 as $value){
			if (strpos(";$content",$value)>0){
				return $value;
			}
		}
	}
	return "";
}

function filter_ip($ip){
	include_once("cache/cache_filters.php");
	if (!empty($filtercache3) && is_array($filtercache3)){
		foreach($filtercache3 as $value){
			$value=str_replace(".","\.",$value);
			if (preg_match("/$value/is",$ip)){
				return false;
			}
		}
	}
	return true;
}

function filter_url($url){
	if (file_exists("cache/cache_filters.php")){
		include("cache/cache_filters.php");
		if (isset($filtercache4) && in_array($url, $filtercache4)){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

function tb_extra($length) {
    $str = ''; 
    for ($i = 0; $i < $length; $i++){ 
        $rand = rand(1,35); 
        if ($rand < 10) $str .= $rand; 
        else $str .= chr($rand + 87); 
    } 
	return $str;
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

function getLogs($logId){
	global $DBPrefix;

	$dataInfo=getRecordValue($DBPrefix."logs","id='$logId'");
	return $dataInfo;
}

//取得总的访问数
function getDailyStatisticsTotal(){
	global $DMC,$DBPrefix;

	$sql="select sum(visits) as v_total from ".$DBPrefix."dailystatistics";
	$arr_result=$DMC->fetchArray($DMC->query($sql));
	$v_total=$arr_result['v_total'];

	return ($v_total>0)?$v_total:0;
}

//取得某天的访问数
function getDailyStatisticsDay($setDate){
	global $DMC,$DBPrefix;
	$sql="select visits from ".$DBPrefix."dailystatistics where visitDate='".$setDate."'";
	$arr_result=$DMC->fetchArray($DMC->query($sql));
	$d_total=$arr_result['visits'];

	return ($d_total>0)?$d_total:0;
}

function printMessage($ActionMessage){
	$out="";
	if ($ActionMessage!="") {
		$out.="<div align=\"center\"> \n";
		$out.="<fieldset style=\"width:60%;\"> \n";
		$out.="<legend> \n";
		$out.="提示信息 \n";
		$out.="</legend> \n";		
		$out.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\"> \n";
		$out.="<tr><td><font color=\"#FF0000\">$ActionMessage</font></td></tr> \n";
		$out.="</table> \n";
		$out.="</fieldset> \n";
		$out.="<br /> \n";
		$out.="</div> \n";

	}
	echo $out;
}
?>
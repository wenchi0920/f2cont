<?php
/**
 * 代码部分主要参考了bo-blog
 * 修改者：korsen zhang
 */
include_once("include/common.php");

ob_end_clean();
header('Content-type: text/xml; charset=utf-8', true);

//读取语言包
if ($settingInfo['language']=="") $settingInfo['language']="zh_cn";
include_once(F2BLOG_ROOT."./include/language/admin/".basename($settingInfo['language']).".php");
include_once(F2BLOG_ROOT."./include/cache.php");

$settingInfo['blogUrl']="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"xmlrpc.php"));

$rawdata=get_http_raw_post_data();
//writetofile ("text4.xml", $rawdata); //For debug use
//$rawdata=file_get_contents("text4.xml"); //For debug use

if (!$rawdata) die ("Sorry, don't visit this web!");

$stringType_o="i4|int|boolean|struct|string|double|base64|dateTime\.iso8601";
$stringType="(".$stringType_o.")";

$rawdata=str_replace("\r", '', $rawdata);
$rawdata=str_replace("\n", '', $rawdata);
$rawdata=str_replace("\t", '', $rawdata);
$rawdata=str_replace("<![CDATA[", '', $rawdata);
$rawdata=str_replace("]]>", '', $rawdata); //Stupid CDATA, I don't want it
$rawdata=preg_replace("/<([^>]+?) \/>/is", '<\\1></\\1>', $rawdata); //Self-closed tags
//$rawdata=convert_utf8($rawdata);

$rawdata=preg_replace_callback("/<struct>(.+?)<\/struct>/is", 'filter_struct', $rawdata); //Struct can be a trouble, use this to avoid values and names being parsed

$nameType=array (
	'blogger.newPost' => array ('appkey', 'id', 'username', 'password', 'logContent', 'publish'),
	'blogger.editPost' => array ('appkey', 'postid', 'username', 'password', 'logContent', 'publish'),
	'blogger.getUsersBlogs' => array ('appkey', 'username', 'password'),
	'blogger.getUserInfo' => array ('appkey', 'username', 'password'),
	'blogger.deletePost' => array ('appkey', 'postid', 'username', 'password'),
	'blogger.getTemplate' => array ('appkey', 'id', 'username', 'password', 'templateType'),
	'blogger.setTemplate' => array ('appkey', 'id', 'username', 'password', 'template', 'templateType'),
 	'metaWeblog.newPost' => array ('id', 'username', 'password', 'struct', 'publish'),
 	'metaWeblog.editPost' => array ('postid', 'username', 'password', 'struct', 'publish'),
 	'metaWeblog.getPost' => array ('postid', 'username', 'password'),
 	'metaWeblog.newMediaObject' => array ('id', 'username', 'password', 'struct'),
 	'metaWeblog.getCategories' => array ('id', 'username', 'password'),
 	'metaWeblog.getRecentPosts' => array ('id', 'username', 'password', 'numberOfPosts')
);
$methodFamily=array('blogger.newPost', 'blogger.editPost', 'blogger.getUsersBlogs', 'blogger.getUserInfo', 'blogger.deletePost', 	'blogger.getTemplate', 	'blogger.setTemplate', 	'metaWeblog.newPost', 'metaWeblog.editPost', 'metaWeblog.getPost', 'metaWeblog.newMediaObject', 'metaWeblog.getCategories', 'metaWeblog.getRecentPosts'); 


function parse_get ($whole_line, $parser, $single=false) { //Parse specific value(s)
	$reg= "/<".$parser.">(.+?)<\/".$parser.">/is";
	preg_match_all ($reg, $whole_line, $array_matches);
	if ($single) return $array_matches[1][0];
	else return $array_matches[1];
}


function parse_walk_array ($array, $names) { //Turn all values into readable forms
	global $stringType, $nameType;
	if (!is_array($nameType[$names])) return;
	$reg= "/<".$stringType.">(.+?)<\/".$stringType.">/is";
	$i=0;
	foreach ($array as $whole_line) {
		$name=$nameType[$names][$i];
		if (is_array($whole_line)) $return[$name]=$whole_line;
		else {
			$try=preg_match($reg, $whole_line, $matches);
			if ($try=0) $return[$name]='';
			else {
				@list($whole, $type, $value)=$matches;
				if ($type!='struct') $return[$name]=$value;
				else $return[$name]=parse_struct($value);
			}
		}
		$i+=1;
		unset ($try, $name, $whole, $type, $value);
	}
	return $return;
}

function filter_struct ($matches) {
	global $stringType;
	$structlogContent=$matches[0];
	$structlogContent=preg_replace("/<".$stringType.">/is", "<struct-\\1>", $structlogContent);
	$structlogContent=preg_replace("/<\/".$stringType.">/is", "</struct-\\1>", $structlogContent);
	$structlogContent=str_replace("<value>", "<struct-value>", $structlogContent);
	$structlogContent=str_replace("</value>", "</struct-value>", $structlogContent);
	$structlogContent=str_replace("<struct-struct>", "<struct>", $structlogContent);
	$structlogContent=str_replace("</struct-struct>", "</struct>", $structlogContent);
	//$structlogContent=preg_replace("/<struct>(.+?)<\/struct>/is", '', $structlogContent);
	//die ($structlogContent);
	return $structlogContent;
}

function parse_struct ($struct) { //Now let's deal with struct
	global $stringType;
	$reg= "/<struct-".$stringType.">(.+?)<\/struct-".$stringType.">/is";
	$all_names=parse_get($struct, 'name');
	$all_values=parse_get($struct, 'struct-value');
	foreach ($all_values as $single_value) {
		$try=preg_match($reg, $single_value, $matches);
		@list($whole, $type, $value)=$matches;
		$result_values[]=$value; //I don't care any types
		unset ($whole, $type, $value);
	}
	$all_values=$result_values;
	$all_total=count($all_names);
	for ($i=0; $i<$all_total; $i++) {
		$key=$all_names[$i];
		$value=$all_values[$i];
		$result[$key]=$value;
	}
	return $result;
}

function xml_error ($error) { //Output an error
	$xml=<<<eot
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>500</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>{$error}</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse> 
eot;
	send_response ($xml);
}
	
function xml_generate ($body_xml) { //Generate an XML cluster with certain format
	$xml=<<<eot
<methodResponse>
	<params>
		<param>
			<value>
				{$body_xml}
			</value>
		</param>
	</params>
</methodResponse>
eot;
	return $xml;
}

function make_xml_piece ($type, $values) { //Compose a piece of XML
	switch ($type) {
		case "array":
			$xml="
					<array>
						<data>";
			foreach ($values as $singlevalue) {
				$xml.="
							<value>
								{$singlevalue}
							</value>";
			}
			$xml.="
						</data>
					</array>";
			break;
		case "struct":
			$xml="
					<struct>";
			while (@list($key, $singlevalue)=@each($values)) {
				if ($key=='dateCreated') $stype="<dateTime.iso8601>{$singlevalue}</dateTime.iso8601>";
				elseif ($key=='categories') $stype=$singlevalue;
				else $stype="<string>{$singlevalue}</string>";
				$xml.="
						<member>
							<name>{$key}</name>
							<value>
							{$stype}
							</value>
						</member>";
			}
			$xml.="
					</struct>";
			break;
		default:
			$xml="<{$type}>{$values}</{$type}>";
		break;
	}
	return $xml;
}

function send_response ($xml) { //Send out the response
	$date_p=date('r', time());
	$xml="<?xml version=\"1.0\"?>\n".$xml;
	$lens=strlen($xml);
	header("HTTP/1.1 200 OK");
	header("Connection: close");
	header("logContent-Length: {$lens}");
	header("logContent-Type: text/xml");
	header("Date: {$date_p}");
	header("Server: F2BLOG 1.0");
	echo ($xml);
	exit();
}
$methodName=parse_get($rawdata, 'methodName', true);
if (!@in_array($methodName, $methodFamily)) xml_error ("Method ({$methodName}) is not availble.");
$values=parse_get($rawdata, 'value');

$values=parse_walk_array($values, $methodName);
//print_r($values); //For debug only
//exit();
//Get default category, for those editors which don't support Categories
$sql="select * from ".$DBPrefix."categories limit 0,1";
$arr_category=$DMC->fetchArray($DMC->query($sql));
$defualtcategoryid=$arr_category[id];

$methodName=str_replace('.', '_', $methodName);
call_user_func ($methodName, $values);

function checkuser($username, $password) {
	global $defualtcategoryid,$DMC, $DBPrefix;
	$username = trim($username);
	$password = md5($password);

	$sql="SELECT * FROM ".$DBPrefix."members WHERE username='".$username."' and password='".$password."' and role='admin'";
	$userInfo = $DMC->fetchArray($DMC->query($sql));
	if (count($userInfo)>0) {
		return $userInfo;
	} else {
		return false;
	}
}

function check_user_pw ($username, $password) {
	$userdetail=checkuser($username, $password);
	if (!$userdetail) xml_error("Authentification failed by the conbination of provided username ({$username}) and password.");
	else return $userdetail;
}

//functions of MetawebblogAPI
//We no longer provide the methods that resembles the same function as in bloggerAPI, eg metaWeblog.newPost is supported, but blogger.newPost is not
function blogger_getUsersBlogs ($values) {
	global $settingInfo;
	$userdetail=check_user_pw ($values['username'], $values['password']);
	$value_body=array('url'=>$settingInfo['blogUrl'], 'blogid'=>$values['appkey'], 'blogName'=>$settingInfo['name']);
	$array_body[0]=make_xml_piece ("struct", $value_body);
	$xml_logContent=make_xml_piece("array", $array_body);
	$body_xml=xml_generate($xml_logContent);
	//writetofile ("text2.xml", $body_xml); //For debug use
	send_response ($body_xml);
}

function metaWeblog_newPost ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix,$arrSideModule;
	global $strArrayMonth,$strArrayDay,$strYear,$strMonth,$arrWeek,$strDayLogs,$strCalendar;
	global $strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;

	$struct=$values['struct'];
	$userdetail=check_user_pw ($values['username'], $values['password']);
	if (!$struct['title']) $logTitle="Untitled MetaWeblogAPI Entry";
	else $logTitle=reduce_entities($struct['title']);
	if (!$struct['description']) xml_error("You MUST provide a decription element in your post.");
	else $logContent=reduce_entities($struct['description']);
	if ($struct['pubDate']) $struct['dateCreated']=$struct['pubDate'];
	if ($struct['dateCreated']) $postTime=get_time_unix($struct['dateCreated']);
	else $postTime=time();

	if ($struct['categories']!='') {
		if (strpos(";".$struct['categories'],"|-")>0) $struct['categories']=trim(substr($struct['categories'],2));	
		$c_tmp=$DMC->fetchArray($DMC->query("SELECT id FROM `{$DBPrefix}categories` WHERE `name`='{$struct['categories']}'"));
		$category=$c_tmp['id'];
		if ($category=='') $category=$defualtcategoryid;
	}
	else $category=$defualtcategoryid;

	$query="INSERT INTO `{$DBPrefix}logs` (cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter) VALUES ('{$category}','$logTitle','$logContent','{$userdetail['username']}','','$postTime','1','1','0','','1','','','tiny')";
	$DMC->query($query);
	//echo $DMC->error();
	$currentid=$DMC->insertId();

	//更新缓存
	$DMC->query("UPDATE ".$DBPrefix."categories set cateCount=cateCount+1 WHERE id='$category'");
	$DMC->query("UPDATE ".$DBPrefix."setting set settValue=settValue+1 where settName='setlogs'");

	//更新Cache
	settings_recache();
	categories_recount();
	categories_recache();
	recentLogs_recache();
	archives_recache();
	calendar_recache();
	logsTitle_recache();
	logs_sidebar_recache($arrSideModule);

	$xml_logContent=make_xml_piece("string", $currentid);
	$body_xml=xml_generate($xml_logContent);
	//writetofile ("text2.xml", $body_xml); //For debug use
	send_response ($body_xml);
}

function metaWeblog_editPost ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix,$arrSideModule;
	global $strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;

	$struct=$values['struct'];
	$userdetail=check_user_pw ($values['username'], $values['password']);
	$records=$DMC->fetchArray($DMC->query("SELECT * FROM `{$DBPrefix}logs` WHERE `id`='{$values['postid']}'"));
	if ($records['id']=='') xml_error ("Entry does not exist.");

	if (!$struct['title']) $logTitle="Untitled MetaWeblogAPI Entry";
	else $logTitle=reduce_entities($struct['title']);
	if (!$struct['description']) xml_error("You MUST provide a decription element in your post.");
	else $logContent=reduce_entities($struct['description']);
	$nowtime=time();
	if ($struct['dateCreated']) $postTime=get_time_unix($struct['dateCreated']);
	else $postTime=$records['postTime'];

	if ($struct['categories']!='') {
		if (strpos(";".$struct['categories'],"|-")>0) $struct['categories']=trim(substr($struct['categories'],2));	
		$c_tmp=$DMC->fetchArray($DMC->query("SELECT id FROM `{$DBPrefix}categories` WHERE `name`='{$struct['categories']}'"));
		$category=$c_tmp['id'];
		if ($category=='') $category=$defualtcategoryid;
	}
	else $category=$records['cateId'];

	$query="UPDATE `{$DBPrefix}logs` SET `logTitle`='{$logTitle}', `postTime`='{$postTime}', `cateId`='{$category}', `logContent`='{$logContent}' WHERE `id`='{$values['postid']}'";
	$DMC->query($query);

	//更新Cache
	recentLogs_recache();
	logsTitle_recache();
	logs_sidebar_recache($arrSideModule);

	$xml_logContent=make_xml_piece("boolean", '1');
	$body_xml=xml_generate($xml_logContent);
	//writetofile ("text2.xml", $body_xml); //For debug use
	send_response ($body_xml);
}

function metaWeblog_getPost ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix;
	$userdetail=check_user_pw ($values['username'], $values['password']);
	$records=$DMC->fetchArray($DMC->query("SELECT *,a.id as id FROM `{$DBPrefix}logs` as a inner join `{$DBPrefix}categories` as b on a.cateId=b.id  WHERE `id`='{$values['postid']}'"));
	if ($records['id']=='') xml_error ("Entry does not exist.");
	else {
		$postTime=get_time_unix($record['postTime'], 'iso');
		if ($record[parent]>0) $record['name']="|-".$record['name'];
		$value_body=array('dateCreated'=>$postTime, 'userid'=>$userdetail['username'], 'postid'=>$record['id'], 'description'=>htmlspecialchars($record['logContent']), 'title'=>htmlspecialchars($record['logTitle']), 'link'=>"{$settingInfo['blogUrl']}read.php?{$record['id']}", 'categories'=>make_xml_piece('array', array("{$record['name']}")));
		$body=make_xml_piece ("struct", $value_body);
		$body_xml=xml_generate($body);
		send_response ($body_xml);
	}
}

function metaWeblog_getRecentPosts ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix;
	$userdetail=check_user_pw ($values['username'], $values['password']);

	$records=$DMC->fetchQueryAll($DMC->query("SELECT *,a.id as id FROM `{$DBPrefix}logs` as a inner join `{$DBPrefix}categories` as b on a.cateId=b.id ORDER BY `postTime` DESC LIMIT 0, {$values['numberOfPosts']}"));
	if ($records[0]['id']=='') xml_error ("Entry does not exist.");
	else {
		foreach($records as $record){
			$postTime=get_time_unix($record['postTime'], 'iso');
			if ($record[parent]>0) $record['name']="|-".$record['name'];
			$value_body=array('dateCreated'=>$postTime, 'userid'=>$userdetail['username'], 'postid'=>$record['id'], 'description'=>htmlspecialchars($record['logContent']), 'title'=>htmlspecialchars($record['logTitle']), 'link'=>"{$settingInfo['blogUrl']}index.php?load=read&amp;id={$record['id']}", 'categories'=>make_xml_piece('array', array("{$record['name']}")));
			$value_bodys[]=make_xml_piece ("struct", $value_body);
		}
		$body=make_xml_piece ("array", $value_bodys);
		$body_xml=xml_generate($body);
		//writetofile ("text2.xml", $body_xml); //For debug use
		send_response ($body_xml);
	}
}

function metaWeblog_getCategories ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix;
	$userdetail=check_user_pw ($values['username'], $values['password']);
	//Get Categories
	$result=$DMC->query("SELECT * FROM `{$DBPrefix}categories` where parent='0' ORDER BY `orderNo`");
	$parent_arr=$DMC->fetchQueryAll($result);
	foreach ($parent_arr as $key=>$value){
		$result=$DMC->query("SELECT * FROM `{$DBPrefix}categories` where parent='{$value[id]}' ORDER BY `orderNo`");
		$struct_body[]=make_xml_piece ("struct", array('description'=>"{$value['name']}", 'htmlUrl'=>"{$settingInfo['blogUrl']}index.php?job=category&amp;seekname={$value['id']}", 'rssUrl'=>"{$settingInfo['blogUrl']}rss.php?cateID={$value['id']}"));
		while ($row=$DMC->fetchArray($result)) {
			$row['name']="|-".$row['name'];
			$struct_body[]=make_xml_piece ("struct", array('description'=>"{$row['name']}", 'htmlUrl'=>"{$settingInfo['blogUrl']}index.php?job=category&amp;seekname={$row['id']}", 'rssUrl'=>"{$settingInfo['blogUrl']}rss.php?cateID={$row['id']}"));
		}
	}
	$xml_logContent.=make_xml_piece ("array", $struct_body);
	$body_xml=xml_generate($xml_logContent);
	//writetofile ("text2.xml", $body_xml); //For debug use
	send_response ($body_xml);
}

function blogger_getUserInfo ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix;
	$userdetail=check_user_pw ($values['username'], $values['password']);
	$xml_logContent=make_xml_piece ("struct", array('nickname'=>$values['nickname'], 'userid'=>$userdetail['username'], 'url'=>$settingInfo['blogUrl'], 'email'=>$userdetail['email']));
	$body_xml=xml_generate($xml_logContent);
	send_response ($body_xml);
}

//Give an error code for bloggerAPI aliases
function blogger_newPost ($values) {
	global $settingInfo,$defualtcategoryid,$DMC, $DBPrefix,$arrSideModule;
	global $strArrayMonth,$strArrayDay,$strYear,$strMonth,$arrWeek,$strDayLogs,$strCalendar;
	global $strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;
	
	$struct=$values['struct'];
	$userdetail=check_user_pw ($values['username'], $values['password']);
	if (!$struct['title']) $logTitle="Untitled MetaWeblogAPI Entry";
	else $logTitle=reduce_entities($struct['title']);
	if (!$struct['description']) xml_error("You MUST provide a decription element in your post.");
	else $logContent=reduce_entities($struct['description']);
	if ($struct['pubDate']) $struct['dateCreated']=$struct['pubDate'];
	if ($struct['dateCreated']) $postTime=get_time_unix($struct['dateCreated']);
	else $postTime=time();

	if ($struct['categories']!='') {
		if (strpos(";".$struct['categories'],"|-")>0) $struct['categories']=trim(substr($struct['categories'],2));	
		$c_tmp=$DMC->fetchArray($DMC->query("SELECT id FROM `{$DBPrefix}categories` WHERE `name`='{$struct['categories']}'"));
		$category=$c_tmp['id'];
		if ($category=='') $category=$defualtcategoryid;
	}
	else $category=$defualtcategoryid;

	$query="INSERT INTO `{$DBPrefix}logs` (cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter) VALUES ('{$category}','$logTitle','$logContent','{$userdetail['username']}','','$postTime','1','1','0','','1','','','tiny')";
	$DMC->query($query);
	//echo $DMC->error();
	$currentid=$DMC->insertId();
	
	//更新缓存
	$DMC->query("UPDATE ".$DBPrefix."categories set cateCount=cateCount+1 WHERE id='$category'");
	$DMC->query("UPDATE ".$DBPrefix."setting set settValue=settValue+1 where settName='setlogs'");

	//更新Cache
	settings_recache();
	categories_recount();
	categories_recache();
	recentLogs_recache();
	archives_recache();
	calendar_recache();
	logsTitle_recache();
	logs_sidebar_recache($arrSideModule);

	$xml_logContent=make_xml_piece("string", $currentid);
	$body_xml=xml_generate($xml_logContent);
	//writetofile ("text2.xml", $body_xml); //For debug use
	send_response ($body_xml);
}


function blogger_deletePost ($values) {
	global $settingInfo,$DMC, $DBPrefix,$arrSideModule;
	global $strArrayMonth,$strArrayDay,$strYear,$strMonth,$arrWeek,$strDayLogs,$strCalendar;
	global $strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;

	$userdetail=check_user_pw ($values['username'], $values['password']);
	$records=$DMC->fetchArray($DMC->query("SELECT * FROM `{$DBPrefix}logs` WHERE `id`='{$values['postid']}'"));
	if ($records['id']=='') xml_error ("Entry does not exist.");
	else {
		$DMC->query("DELETE FROM `{$DBPrefix}logs` WHERE `id`='{$values['postid']}'");
		
		//更新Cache
		settings_recount("logs");
		settings_recache();
		categories_recount();
		categories_recache();
		recentLogs_recache();
		archives_recache();
		calendar_recache();
		logsTitle_recache();
		logs_sidebar_recache($arrSideModule);

		$xml_logContent=make_xml_piece("boolean", '1');
		$body_xml=xml_generate($xml_logContent);
		//writetofile ("text2.xml", $body_xml); //For debug use
		send_response ($body_xml);
	}
}

function metaWeblog_newMediaObject ($values) { //2007-02-01 add support for uploading files
	global $settingInfo,$DMC, $DBPrefix,$defualtcategoryid;
	$userdetail = check_user_pw ($values['username'], $values['password']);
	$struct=$values['struct'];
	//writetofile ('text1.php', $struct['bits']); //debug only
	if ($struct['bits'] && $struct['name']) {
		$writefilecontent=base64_decode($struct['bits']);
		if (file_exists("attachments/{$struct['name']}")) @unlink("attachments/{$struct['name']}");
		
		$filenum=@fopen("attachments/{$struct['name']}","wb");
		if (!$filenum) {
			xml_error ("Sorry, uploading file ({$struct['name']}) failed.");
		}
		flock($filenum,LOCK_EX);
		fwrite($filenum,$writefilecontent);
		fclose($filenum);
	}
	$xml_content=make_xml_piece ("struct", array('url'=>"{$settingInfo['blogurl']}/attachments/{$struct['name']}"));
	$body_xml=xml_generate($xml_content);
	send_response ($body_xml);
}

function blogger_editPost ($values) {
	xml_error ("Sorry, this method is no longer supported. Please use metaWeblog.editPost instead.");
}

//Give an error code for unsupported methods, like template
function blogger_getTemplate ($values) {
	xml_error ("Sorry, this method is not supported yet.");
}

function blogger_setTemplate ($values) {
	xml_error ("Sorry, this method is not supported yet.");
}

function reduce_entities($str) { //Convert the submitted content back to HTML
	$str=stripslashes($str);
	$str=html_entity_decode($str, ENT_QUOTES);
	$str=string_convert($str, 1);
	return $str;
}

function string_convert($string, $html=0, $filterslash=0) { //Words Filter
	if ($html==0) {
		$string=htmlspecialchars($string, ENT_QUOTES);
		$string=str_replace("<","&lt;",$string);
		$string=str_replace(">","&gt;",$string);
		if ($filterslash==1) $string=str_replace("\\", '&#92;', $string);
	} else {
		$string=addslashes($string);
		if ($filterslash==1) $string=str_replace("\\\\", '&#92;', $string);
	}
	$string=str_replace("\r","<br />",$string);
	$string=str_replace("\n","",$string);
	$string=str_replace("\t","&nbsp;&nbsp;",$string);
	$string=str_replace("  "," &nbsp;",$string);
	$string=str_replace('|', '&#124;', $string);
	$string=str_replace("&amp;#96;","&#96;",$string);
	$string=str_replace("&amp;#92;","&#92;",$string);
	$string=str_replace("&amp;#91;","&#91;",$string);
	$string=str_replace("&amp;#93;","&#93;",$string);
	return $string;
}

function writetofile ($filename, $data) { //File Writing
	$filenum=@fopen($filename,"w");
	if (!$filenum) {
		return false;
	}
	flock($filenum,LOCK_EX);
	$file_data=fwrite($filenum,$data);
	fclose($filenum);
	return true;
}

function get_time_unix ($date, $destination="stamp") { //Convert an iso8601 date into unix time format, or vice versa
	global $settingInfo;
	if ($destination=="stamp") {
		$year=substr($date, 0, 4);
		$month=substr($date, 4, 2);
		$day=substr($date, 6, 2);
		$hour=substr($date, 9, 2);
		$minute=substr($date, 12, 2);
		$second=substr($date, 15, 2);
		$timestamp=gmmktime((integer)$hour, (integer)$minute, (integer)$second, (integer)$month, (integer)$day,  (integer)$year);
	} else {
		$timestamp=gmdate("Ymd\TH:i:s\Z", $date);
	}
	return $timestamp;
}

function get_http_raw_post_data () { //Get http_raw_post_data
	global $HTTP_RAW_POST_DATA;
	if (isset($HTTP_RAW_POST_DATA)) { //Good, the server supports $HTTP_RAW_POST_DATA, then return it directly
		return trim($HTTP_RAW_POST_DATA);
	}
	elseif (PHP_OS>="4.3.0") { //PHP 4.3.0 and higher version supports another way to get it
		return readfromfile( 'php://input' );
	}
	else return false; //Sorry, no way out, or $raw data is not set at all
}
?>
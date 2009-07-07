<?php
# 禁止直接访问该页面
if (basename($_SERVER['PHP_SELF']) == "cache.php") {
    header("HTTP/1.0 404 Not Found");
}

// 写入缓存文件
function writetocache($cachename, $cachedata = '') {
	global $DMF;
	if ($DMF==""){
		$cachedir = '../cache/';
	} ELSE {
		$cachedir = './cache/';
	}
	
	if(!is_dir($cachedir)) {
		@mkdir($cachedir, 0777);
	}

	$cachefile = $cachedir.'cache_'.$cachename.'.php';
	if(@$fp = fopen($cachefile, 'w')) {
		@fwrite($fp, "<?php\r\n//F2Blog $cachename cache file\r\n".$cachedata."\r\n\r\n?>");
		@fclose($fp);
		@chmod($cachefile, 0777);
	} else {
		echo "Can not write to cache files, please check directory $cachedir .";
		exit;
	}
}

// 更新设置选项
function settings_recache()	{
	global $DMC,$DMF,$DBPrefix;	
	$DMC=($DMF=="")?$DMC:$DMF;
	$arrSet=array("name","blogTitle","blogUrl","logo","favicon","about","master","email","disType","perPageNormal","perPageList","isLinkTagLog","linkTagLog","isProgramRun","commTimerout","commLength","isValidateCode","isRegister","status","closeReason","language","timezone","timeSystemFormat","isTbApp","tbSiteList","newRss","rssContentType","defaultSkin","logNums","commNums","tagNums","visitNums","tbNums");
	
	$setInfo = $DMC->fetchArray($DMC->query("SELECT * FROM ".$DBPrefix."setting where id='1'"));
	$contents = "\$settingInfo = array(\r\n";
	for ($i=0;$i<count($arrSet);$i++) {
		$key=$arrSet[$i];
		$value=($key=="tbSiteList")?str_replace("<br />\n",";",$setInfo[$key]):$setInfo[$key];
		$contents.="\t'".addslashes($key)."' => '".addslashes($value)."',\r\n";
	}
	$contents .= ");";
	writetocache('setting',$contents);
}

//　更新博客统计
function statistics_recache() {
	global $DMC, $DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF; 

	$arrSet=array("logNums","commNums","tagNums","visitNums","tbNums","messageNums","memberNums");
	$setInfo = $DMC->fetchArray($DMC->query("SELECT * FROM ".$DBPrefix."setting where id='1'"));
	$contents = "\$statisticscache = array(\r\n";
	for ($i=0;$i<count($arrSet);$i++) {
		$key=$arrSet[$i];
		$value=$setInfo[$key];
		$contents.="\t'".addslashes($key)."' => '".addslashes($value)."',\r\n";
	}
	$contents .= ");";
	writetocache('statistics',$contents);
}

// 重新计算类别的日志数量
function categories_recount() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;
	
	//清空Categories的数量
	$sql="update ".$DBPrefix."categories set cateCount=0";
	$DMC->query($sql);

	//重新计算数量
	$count_sql="select cateId,count(cateId) as count_total from ".$DBPrefix."logs where saveType='1' group by cateId order by cateId";
	$count_result=$DMC->query($count_sql);
	$arr_result=$DMC->fetchQueryAll($count_result);
	for($i=0;$i<count($arr_result);$i++){
		//$arr_result[$i]["cateId"]."==".$arr_result[$i]["count_total"]."<br>";
		$update_sql="update ".$DBPrefix."categories set cateCount=".$arr_result[$i]["count_total"]." where id='".$arr_result[$i]["cateId"]."'";
		$DMC->query($update_sql);
		//echo $update_sql."<br>";
	}

	//汇总主类别
	$sum_sql="select parent,sum(cateCount) as sum_total from ".$DBPrefix."categories where parent>'0' group by parent";
	$sum_result=$DMC->query($sum_sql);
	$arr_result=$DMC->fetchQueryAll($sum_result);
	for($i=0;$i<count($arr_result);$i++){
		$update_sql="update ".$DBPrefix."categories set cateCount=cateCount+".$arr_result[$i]['sum_total']." where id='".$arr_result[$i]["parent"]."'";
		$DMC->query($update_sql);
		//echo $update_sql."<br>";
	}
}

// 更新分类
function categories_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	//get sum category
	$sum_sql="select sum(cateCount) as sum_total from ".$DBPrefix."categories where parent='0' and isHidden='0'";
	$sum_result=$DMC->query($sum_sql);
	if ($arr_result=$DMC->fetchArray($sum_result)){
		$sum_total=($arr_result['sum_total']=="" or $arr_result['sum_total']==0)?0:$arr_result['sum_total'];
	}else{
		$sum_total=0;
	}

	//get main category
	$query_sql="select id,name,cateTitle,outLinkUrl,cateCount from ".$DBPrefix."categories where parent='0' and isHidden='0' order by orderNo";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		//get sub category
		$sub_sql="select id,name,cateTitle,outLinkUrl,cateCount from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' and isHidden='0' order by orderNo";
		$sub_result=$DMC->query($sub_sql);
		$arr_sub[$i] = $DMC->fetchQueryAll($sub_result);
	}
	
	//保存菜单
	$parent_contents = "\$arr_parent = array(\r\n";
	$sub_contents = "\$arr_sub = array(\r\n";
	for ($i=0;$i<count($arr_parent);$i++){
		$parent_contents.="\t'".$i."' => array(\n\t\t'id' => '".$arr_parent[$i]['id']."',\n\t\t'name' => '".$arr_parent[$i]['name']."',\n\t\t'cateTitle' => '".$arr_parent[$i]['cateTitle']."',\n\t\t'outLinkUrl' => '".$arr_parent[$i]['outLinkUrl']."',\n\t\t'cateCount' => '".$arr_parent[$i]['cateCount']."',\n\t\t),\n";

		$sub_contents .= "\t'".$i."' => array(\r\n";
		for ($j=0;$j<count($arr_sub[$i]);$j++){
			$sub_contents.="\t\t'".$j."' => array(\n\t\t\t'id' => '".$arr_sub[$i][$j]['id']."',\n\t\t\t'name' => '".$arr_sub[$i][$j]['name']."',\n\t\t\t'cateTitle' => '".$arr_sub[$i][$j]['cateTitle']."',\n\t\t\t'outLinkUrl' => '".$arr_sub[$i][$j]['outLinkUrl']."',\n\t\t\t'cateCount' => '".$arr_sub[$i][$j]['cateCount']."',\n\t\t\t),\n";
		}
		$sub_contents .= "\n\t\t),\n";
	}
	$parent_contents .= ");\n\n";
	$sub_contents .= ");\n\n";

	//保存Cache
	$contents = "\$sum_total=$sum_total;\n\n".$parent_contents.$sub_contents;

	writetocache('categories',$contents);
}

// 更新热门标签
function hottags_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	//取得标签数量
	$arr_result=$DMC->fetchArray($DMC->query("select count(id) as count from ".$DBPrefix."tags"));
	$tags_count=$arr_result['count'];

	//取得显示标签数量
	$arr_result=$DMC->fetchArray($DMC->query("select TagNums from ".$DBPrefix."setting where id='1'"));
	$max_count=$arr_result['TagNums'];

	if ($max_count<1){
		$limit=($tags_count>50)?50:$tags_count;
	}else if ($max_count<$tags_count){
		$limit=$max_count;
	}else{
		$limit=$tags_count;
	}

	$tag_result = $DMC->query("select * from ".$DBPrefix."tags order by logNums desc limit 0,$limit");
	$contents = "\$tagscache = array(\r\n";
	while ($tags = $DMC->fetchArray($tag_result)) {		
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$tags['id']."',\n\t\t'name' => '".$tags['name']."',\n\t\t'logNums' => '".$tags['logNums']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('hottags',$contents);
}

// 更新归档
function archives_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$archives = $DMC->query("SELECT postTime FROM ".$DBPrefix."logs where saveType='1' ORDER BY postTime  DESC");
	$contents = "\$archivescache = array(\r\n";
	$articledb = array();
	while ($article = $DMC->fetchArray($archives)) {
		$article['dateline'] = format_time("Y,m",$article['postTime']);
		$articledb[]=$article['dateline'];
	}
	unset($article);
	$archivedb = array_count_values($articledb);
	unset($articledb);
	$i=0;
	foreach($archivedb as $key => $val){
		if ($i>=6) { break; }
		$contents.="\t'".$i."' => array(\n\t\t'month' => '".$key."',\n\t\t'logNums' => '".$val."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('archives',$contents);
}

// 更新日历
function calendar_recache() {
	global $DMC,$DBPrefix,$settingInfo;
	if ($DMC=="") $DMC=$DMF;

	$archives = $DMC->query("SELECT postTime FROM ".$DBPrefix."logs where saveType='1' ORDER BY postTime DESC");
	$contents = "\$calendarcache = array(\r\n";
	$articledb = array();
	while ($article = $DMC->fetchArray($archives)) {
		$article['dateline'] = format_time("Y-n-j",$article['postTime']);
		$articledb[]=$article['dateline'];
	}
	unset($article);
	$archivedb = array_count_values($articledb);
	unset($articledb);
	$i=0;
	foreach($archivedb as $key => $val){
		$contents.="\t'".$key."' => '".$val."',\n";
		$i++;
	}
	$contents .= ");";

	writetocache('calendar',$contents);
}

// 更新链接
function links_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select * from ".$DBPrefix."links where isHidden='0' order by orderNo");
	$contents = "\$linkscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {		
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['id']."',\n\t\t'name' => '".$my['name']."',\n\t\t'blogUrl' => '".$my['blogUrl']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('links',$contents);
}

// 更新关键字
function keywords_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select * from ".$DBPrefix."keywords order by id");
	$contents = "\$keywordscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {		
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['id']."',\n\t\t'keyword' => '".$my['keyword']."',\n\t\t'linkUrl' => '".$my['linkUrl']."',\n\t\t'linkImage' => '".$my['linkImage']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('keywords',$contents);
}

// 更新过滤器
function filters_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select * from ".$DBPrefix."filters order by id");
	$contents = "\$filterscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {		
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['id']."',\n\t\t'category' => '".$my['category']."',\n\t\t'name' => '".$my['name']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('filters',$contents);
}

// 更新模块
function modules_recache() {
	global $DMC,$DMF,$DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$query_sql="select id,name,modTitle from ".$DBPrefix."modules where disType='0' and isHidden='0' order by orderNo";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		//get sub category
		$sub_sql="select * from ".$DBPrefix."modules where disType='".$arr_parent[$i]['id']."' and isHidden='0' order by orderNo";
		$sub_result=$DMC->query($sub_sql);
		$arr_sub[$i] = $DMC->fetchQueryAll($sub_result);
	}
	
	//保存菜单
	for ($i=0;$i<count($arr_parent);$i++){
		switch($i) {
			case 0:
			$sub_contents.= "\$arrTopModule = array(\r\n";
			break;
			case 1:
			$sub_contents.= "\$arrSideModule = array(\r\n";
			break;
			case 2:
			$sub_contents.= "\$arrMainModule = array(\r\n";
			break;
			case 3:
			$sub_contents.= "\$arrFuncModule = array(\r\n";
			break;
			default:
			$sub_contents.= "\$arrOtherModule = array(\r\n";
		}

		for ($j=0;$j<count($arr_sub[$i]);$j++){
			$sub_contents.="\t'".$j."' => array(\n\t\t'id' => '".$arr_sub[$i][$j]['id']."',\n\t\t'name' => '".$arr_sub[$i][$j]['name']."',\n\t\t'modTitle' => '".$arr_sub[$i][$j]['modTitle']."',\n\t\t'htmlCode' => '".$arr_sub[$i][$j]['htmlCode']."',\n\t\t'indexOnly' => '".$arr_sub[$i][$j]['indexOnly']."',\n\t\t'pluginPath' => '".$arr_sub[$i][$j]['pluginPath']."',\n\t\t'isInstall' => '".$arr_sub[$i][$j]['isInstall']."',\n\t\t'installFolder' => '".$arr_sub[$i][$j]['installFolder']."',\n\t\t'installDate' => '".$arr_sub[$i][$j]['installDate']."',\n\t\t'configPath' => '".$arr_sub[$i][$j]['configPath']."',\n\t\t),\n";
		}
		$sub_contents .= "\n);\n\n";
	}

	//保存Cache
	$contents = $sub_contents;

	writetocache('modules',$contents);
}

// 更新最新日志
function recentLogs_recache() {
	global $DMC,$DMF, $DBPrefix;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select * from ".$DBPrefix."logs where saveType='1' order by postTime desc Limit 0,10");
	$contents = "\$recentlogscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['id']."',\n\t\t'cateId' => '".$my['cateId']."',\n\t\t'logTitle' => '".$my['logTitle']."',\n\t\t'author' => '".$my['author']."',\n\t\t'postTime' => '".$my['postTime']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('recentLogs',$contents);
}

// 更新最新评论
function recentComments_recache() {
	global $DMC,$DMF,$DBPrefix,$strGuestBookHidden;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select a.*,a.author as cauthor,a.id as cid from ".$DBPrefix."comments as a inner join ".$DBPrefix."logs as b on a.logId=b.id where b.saveType=1 order by a.postTime desc Limit 0,10");
	$contents = "\$recentcommentscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {
		$content=str_replace("<br />","",subString($my['content'],0,100));
		if ($my['isSecret']){$content=$strGuestBookHidden;}
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['cid']."',\n\t\t'logId' => '".$my['logId']."',\n\t\t'content' => '".$content."',\n\t\t'author' => '".$my['cauthor']."',\n\t\t'postTime' => '".$my['postTime']."',\n\t\t'parent' => '".$my['parent']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('recentComments',$contents);
}

// 更新最新留言
function recentGbooks_recache() {
	global $DMC,$DMF,$DBPrefix,$strGuestBookHidden;
	if ($DMC=="") $DMC=$DMF;

	$i=0;
	$result = $DMC->query("select * from ".$DBPrefix."guestbook order by postTime desc Limit 0,10");
	$contents = "\$recentgbookscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {
		$content=str_replace("<br />","",subString($my['content'],0,100));
		if ($my['isSecret']){$content=$strGuestBookHidden;}
		$contents.="\t'".$i."' => array(\n\t\t'id' => '".$my['id']."',\n\t\t'content' => '".$content."',\n\t\t'author' => '".$my['author']."',\n\t\t'postTime' => '".$my['postTime']."',\n\t\t),\n";
		$i++;
	}
	$contents .= ");";
	writetocache('recentGbooks',$contents);
}

//更新所有Cache
function reAllCache() {
	settings_recache();
	modules_recache();
	categories_recache();
	hottags_recache();
	archives_recache();
	links_recache();
	keywords_recache();
	filters_recache();
	recentLogs_recache();
	recentGbooks_recache();
	recentComments_recache();
	calendar_recache();
	statistics_recache();
	categories_recount();
}

?>
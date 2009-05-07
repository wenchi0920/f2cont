<?php
if (!defined('IN_F2BLOG')) die ('Access Denied.');

// 写入缓存文件
function writetocache($cachename, $cachedata = '', $cachetype = 'php') {
	$cachedir=F2BLOG_ROOT."./cache/";
	if(!is_dir($cachedir)) {
		mkdir($cachedir, 0777);
	}
	$cachefile = $cachedir.'cache_'.$cachename.'.php';
	if($fp = fopen($cachefile, 'wbt')) {
		if ($cachetype=="php"){
			fwrite($fp, "<?php\r\n//F2Blog $cachename cache file\r\n".$cachedata."\r\n\r\n?>");
		}else{
			fwrite($fp, $cachedata);
		}
		fclose($fp);
	} else {
		echo "Can not write to cache files, please check directory $cachefile .";
		exit;
	}
}

// 更新设置选项
function settings_recache()	{
	global $DMC,$DBPrefix;	
	$contents = "\$settingInfo = array(\r\n";
	$setInfoResult = $DMC->query("SELECT * FROM ".$DBPrefix."setting order by settName");	
	while($arr_result=$DMC->fetchArray($setInfoResult)){
		$arr_result['settValue']=($arr_result['settName']=="tbSiteList")?str_replace("<br />\n",";",$arr_result['settValue']):$arr_result['settValue'];
		$contents .= "\t'".$arr_result['settName']."'=>'".$arr_result['settValue']."',\n";
	}
	$contents .= ");";
	writetocache('setting',$contents);
}

// Skin List
function skinlist_recache()	{
	global $settingInfo,$arrSideModule,$DMC,$DBPrefix;

	//列出Skins目录
	$handle=opendir(F2BLOG_ROOT."./skins/"); 
	while (false !== ($file = readdir($handle))) {			
		if(is_dir(F2BLOG_ROOT."./skins/$file") && file_exists(F2BLOG_ROOT."./skins/$file/skin.xml")){
			$arrSkinList[$file]=getSkinInfo($file);
		}			
	} 
	closedir($handle); 

	$contents = "\$skinlistcache = array(\r\n";
	foreach($arrSkinList as $key=>$value){
		$contents.="\t'".$key."' => array(\n\t\t";
		foreach($value as $subkey=>$subvalue){
			if ($subvalue!="" && in_array($subkey,array('SkinName','SkinSource','SkinDesigner','DesignerURL','DesignerMail','UseFlash','FlashPath','FlashWidth','FlashHeight','FlashAlign','FlashTop','FlashTransparent'))){
				$contents.="'$subkey' => '".$subvalue."',\n\t\t";
			}
		}
		$contents.="),\n";
	}
	$contents.= ");";
	writetocache('skinlist',$contents);

	//保存当前skin信息
	if (file_exists(F2BLOG_ROOT."./skins/".$settingInfo['defaultSkin']."/skin.xml")){
		$arrDefaultSkinInfo=getSkinInfo($settingInfo['defaultSkin']);
	}else{
		//如果默认skin不存在，则选择上述列表中的第一个皮肤做为默认皮肤。
		$arrDefaultSkinInfo=$value;
		$DMC->query("update ".$DBPrefix."setting set settValue='".$key."' where settName='defaultSkin'");
		settings_recache();
	}

	$contents = "\$defaultskincache = array(\r\n";
	foreach($arrDefaultSkinInfo as $subkey=>$subvalue){
		if ($subvalue!="" && in_array($subkey,array('SkinName','SkinSource','SkinDesigner','DesignerURL','DesignerMail','UseFlash','FlashPath','FlashWidth','FlashHeight','FlashAlign','FlashTop','FlashTransparent'))){
			$contents.="\t'$subkey' => '".$subvalue."',\n";
		}
	}
	$contents.= ");";
	
	writetocache('defaultskin',$contents);
}

// 重新计算类别的日志数量
function categories_recount() {
	global $DMC,$DBPrefix;
	
	//清空Categories的数量
	$sql="update ".$DBPrefix."categories set cateCount=0";
	$DMC->query($sql);

	//重新计算数量
	$count_sql="select cateId,count(cateId) as count_total from ".$DBPrefix."logs where saveType='1' group by cateId order by cateId";
	$count_result=$DMC->query($count_sql);
	$arr_result=$DMC->fetchQueryAll($count_result);
	for($i=0;$i<count($arr_result);$i++){
		$update_sql="update ".$DBPrefix."categories set cateCount=".$arr_result[$i]["count_total"]." where id='".$arr_result[$i]["cateId"]."'";
		$DMC->query($update_sql);
	}

	//汇总主类别
	$sum_sql="select parent,sum(cateCount) as sum_total from ".$DBPrefix."categories where parent>'0' group by parent";
	$sum_result=$DMC->query($sum_sql);
	$arr_result=$DMC->fetchQueryAll($sum_result);
	for($i=0;$i<count($arr_result);$i++){
		$update_sql="update ".$DBPrefix."categories set cateCount=cateCount+".$arr_result[$i]['sum_total']." where id='".$arr_result[$i]["parent"]."'";
		$DMC->query($update_sql);
	}
}

// 更新日历
function calendar_recache() {
	global $DMC,$DBPrefix,$strArrayMonth,$strArrayDay,$strYear,$strMonth,$arrWeek,$strDayLogs,$settingInfo,$arrSideModule,$strCalendar;
	
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
	writetocache('arrcalendar',$contents);

	//生成日历静态页面
	ob_start();
	$calendarcache=$archivedb;
	if ($settingInfo['showcalendar']==1){
		include(F2BLOG_ROOT."./include/ncalendar.inc.php");
	}else{
		include(F2BLOG_ROOT."./include/calendar.inc.php");
	}
	//$out_contents=create_sidebar_header("Calendar",$strCalendar,$arrSideModule["calendar"]["isInstall"]);
	$out_contents.=ob_get_contents();
	//$out_contents.=create_sidebar_footer();
	ob_end_clean();
	writetocache('calendar',$out_contents,"html");
}

// 更新链接
function links_recache() {
	global $DMC,$DBPrefix,$arrSideModule,$strApplyLink,$settingInfo;
	
	//取出连接分组类别
	$sql="select * from ".$DBPrefix."linkgroup where isSidebar='1' order by orderNo";
	$result=$DMC->query($sql);
	$arr_listgroup=$DMC->fetchQueryAll($result);
	$out_contents="";
	$i=0;

	if ($settingInfo['linkshow']==1){//分组在同一个模块内
		$out_contents.=create_sidebar_header("Links",$arrSideModule["links"]["modTitle"],$arrSideModule["links"]["isInstall"]);
		foreach($arr_listgroup as $key=>$value){
			$result = $DMC->query("select * from ".$DBPrefix."links where lnkGrpId='$value[id]' and isSidebar=1 and isApp=1 order by orderNo");
			$out_contents.="<div id=\"linksgroup_{$value['id']}\">{$value['name']}</div> \n";
			$numrows=$DMC->numRows($result);
			if ($settingInfo['linkmarquee']>0 && $settingInfo['linkmarquee']<$numrows){//出现滚动连接				
			    $out_contents.="<marquee direction=\"up\" scrollamount=\"2\" style=\"width:100%;height:150px\" onMouseOver=\"this.stop()\" onMouseOut=\"this.start()\"> \n";
			}
			while ($my = $DMC->fetchArray($result)) {
				//$show_content=($my[blogLogo]!="")?"<img src=\"$my[blogLogo]\" width=\"88px\" height=\"31px\" alt=\"\"> $my[name]":$my[name];
				$show_content=$my['name'];
				$out_contents.="	<a class=\"sideA\" id=\"Link_".$my['id']."\" title=\"".$my['name']."\" href=\"".$my['blogUrl']."\" target=\"_blank\">".$show_content."</a> \n";
				$i++;
			}
			if ($settingInfo['linkmarquee']>0 && $settingInfo['linkmarquee']<$numrows){//出现滚动连接
				$out_contents.="</marquee>\n";
			}
		}
		if ($settingInfo['applylink']==1)	$out_contents.="	<a href=\"index.php?load=applylink\">&gt;&gt;&gt; $strApplyLink &lt;&lt;&lt;</a> \n";
		$out_contents.=create_sidebar_footer();
	}else{//分组不在同一个模块内
		foreach($arr_listgroup as $key=>$value){
			$out_contents.=create_sidebar_header("Links".(($key>0)?$key:""),$value['name'],$arrSideModule["links"]["isInstall"]);

			$result = $DMC->query("select * from ".$DBPrefix."links where lnkGrpId='$value[id]' and isSidebar=1 and isApp=1 order by orderNo");
			$numrows=$DMC->numRows($result);
			if ($settingInfo['linkmarquee']>0 && $settingInfo['linkmarquee']<$numrows){//出现滚动连接				
				$out_contents.="<marquee direction=\"up\" scrollamount=\"2\" style=\"width:100%;height:150px\" onMouseOver=\"this.stop()\" onMouseOut=\"this.start()\"> \n";
			}
			while ($my = $DMC->fetchArray($result)) {
				//$show_content=($my[blogLogo]!="")?"<img src=\"$my[blogLogo]\" width=\"88px\" height=\"31px\" alt=\"\"> $my[name]":$my[name];
				$show_content=$my['name'];
				$out_contents.="	<a class=\"sideA\" id=\"Link_".$my['id']."\" title=\"".$my['name']."\" href=\"".$my['blogUrl']."\" target=\"_blank\">".$show_content."</a> \n";
				$i++;
			}
			if ($settingInfo['linkmarquee']>0 && $settingInfo['linkmarquee']<$numrows){//出现滚动连接
				$out_contents.="</marquee>\n";
			}
			if ($key==0 && $settingInfo['applylink']==1){
				$out_contents.="	<a href=\"index.php?load=applylink\">&gt;&gt;&gt; $strApplyLink &lt;&lt;&lt;</a> \n";
			}
			$out_contents.=create_sidebar_footer();
		}
	}

	writetocache('links',$out_contents,"html");
}

// 更新关键字
function keywords_recache() {
	global $DMC,$DBPrefix;
	
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

// 更新会员列表
function members_recache() {
	global $DMC,$DBPrefix;
	
	$i=0;
	$result = $DMC->query("select username,nickname from ".$DBPrefix."members where role!='member'");
	$contents = "\$memberscache = array(\r\n";
	while ($my = $DMC->fetchArray($result)) {
		if ($my['nickname']=="") $my['nickname']=$my['username'];
		$contents.="\t'".$my['username']."' => '".$my['nickname']."',\n";
		$i++;
	}
	$contents .= ");";
	writetocache('members',$contents);
}

// 更新过滤器
function filters_recache() {
	global $DMC,$DBPrefix;
	
	$i=0;
	$result = $DMC->query("select category from ".$DBPrefix."filters group by category order by category");
	$arr_category = $DMC->fetchQueryAll($result);
	$contents="";
	for ($i=0;$i<count($arr_category);$i++){
		//echo $arr_category[$i]['category'];
		$result = $DMC->query("select * from ".$DBPrefix."filters where category='".$arr_category[$i]['category']."' order by id");
		$j=0;
		$contents .= "\$filtercache".$arr_category[$i]['category']." = array(\r\n";
		while ($my = $DMC->fetchArray($result)) {		
			$contents.="\t'$j' => '".$my['name']."',\n";
			$j++;
		}
		$contents .= ");\n\n\n";
	}
	writetocache('filters',$contents);
}

//在线更新
function online_recache(){
	if (!file_exists(F2BLOG_ROOT."./cache_online.php")){
		$contents = "\$onlinecache = array(\r\n";
		$contents.="\t'ip' => array(''),\n";
		$contents.="\t'times' => array('')\n";
		$contents.= ");\n";
		$contents.= "\$cache_visits_yesterday = 0;\n";
		$contents.= "\$cache_visits_today = 0;\n";
		$contents.= "\$cache_visits_total = 0;\n";
	}
	writetocache('online',$contents);
}

// 更新模块设定值
function modulesSetting_recache() {
	global $DMC,$DBPrefix;
	
	$result = $DMC->query("select modId,name from ".$DBPrefix."modsetting as a inner join ".$DBPrefix."modules as b on a.modId=b.id group by modId order by modId");
	$arr_modId = $DMC->fetchQueryAll($result);
	$contents="";
	for ($i=0;$i<count($arr_modId);$i++){
		$result = $DMC->query("select KeyName,KeyValue from ".$DBPrefix."modsetting where modId='".$arr_modId[$i]['modId']."' order by id");
		$j=0;
		$contents .= "\$plugins_".$arr_modId[$i]['name']." = array(\r\n";
		while ($my = $DMC->fetchArray($result)) {		
			$contents.="\t'".$my['KeyName']."' => '".$my['KeyValue']."',\n";
			$j++;
		}
		$contents .= ");\n\n\n";
	}
	writetocache('modulesSetting',$contents);
}

// 更新模块
function modules_recache() {
	global $DMC,$DBPrefix;
	
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
	$sub_contents="";
	$plugin_array_name=array();
	for ($i=0;$i<count($arr_parent);$i++){
		switch($i) {
			case 0://顶部栏
				$out_fields=array("modTitle","installDate","pluginPath","indexOnly");	
				$sub_contents.= "\$arrTopModule = array(\r\n";
				break;
			case 1://侧边栏
				$out_fields=array("modTitle","htmlCode","isInstall","installDate","indexOnly");	
				$sub_contents.= "\$arrSideModule = array(\r\n";
				break;
			case 2://主体栏
				$out_fields=array("modTitle","installDate","indexOnly","htmlCode");	
				$sub_contents.= "\$arrMainModule = array(\r\n";
				break;
			case 3://功能栏
				$out_fields=array("modTitle","installDate");	
				$sub_contents.= "\$arrFuncModule = array(\r\n";
				break;
		}

		for ($j=0;$j<count($arr_sub[$i]);$j++){
			$sub_contents.="\t'".$arr_sub[$i][$j]['name']."' => array(\n\t\t";
			foreach($out_fields as $value){
				if (strpos($arr_sub[$i][$j][$value],"[/var]")>0) $arr_sub[$i][$j][$value]=replace_string($arr_sub[$i][$j][$value]);
				if (($value=="installDate" && $arr_sub[$i][$j]["installDate"]>0) || $value!="installDate"){
					$sub_contents.="'$value' => '".$arr_sub[$i][$j][$value]."',\n\t\t";
				}
			}
			$sub_contents.="),\n";
			if ($arr_sub[$i][$j]['installDate']>0){
				$plugin_array_name[]=$arr_sub[$i][$j]['name'];
			}
		}
		$sub_contents .= "\n);\n\n";
	}

	$sub_contents .= "\$arrPluginName = array(\r\n";
	for($i=0;$i<count($plugin_array_name);$i++){
		$sub_contents.="\t'".$i."' => '".$plugin_array_name[$i]."',\n";
	}
	$sub_contents .= ");";

	//保存Cache
	$contents = $sub_contents;

	writetocache('modules',$contents);

	//生成头部cache
	logs_header_recache($arr_sub[0]);
	//生成侧边栏cache
	logs_sidebar_recache($arr_sub[1]);
}

function logs_header_recache($arrTopModule){
	global $settingInfo;

	//读取顶部栏
	$output="";
	foreach($arrTopModule as $key=>$value){
		$topname=(is_int($key))?$value['name']:$key;
		$toptitle=$value['modTitle'];
		$htmlcode=$value['htmlCode'];
		$pluginPath=$value['pluginPath'];
		$installDate=empty($value['installDate'])?"":$value['installDate'];
		$indexOnly=$value['indexOnly'];

		if (in_array($topname,array("tags","guestbook","f2bababian","links","archives")) && $settingInfo['rewrite']>0){
			if ($settingInfo['rewrite']==1) $gourl="rewrite.php/".$topname.$settingInfo['stype'];
			if ($settingInfo['rewrite']==2) $gourl=$topname.$settingInfo['stype'];
		}else{
			$gourl="index.php?load=$topname";
		}
	
		if (strpos($pluginPath,".inc.php")>0 || strpos($pluginPath,".big.php")>0){
			$output.="<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"{$gourl}\">$toptitle</a></li> \n";
		}else{			
			if ($installDate>0){//表示为插件
				ob_start();
				echo '<?php do_filter("'.$topname.'","'.$topname.'","',$toptitle.'");'."?>\n";
				$output.=ob_get_contents();
				ob_end_clean();
			}else{
				$pluginPath=str_replace("&","&amp;",$pluginPath);
				$target=($indexOnly==1)?" target=\"_blank\"":"";
				$output.="<li><a class=\"menuA\" id=\"$topname\" title=\"$toptitle\" href=\"$pluginPath\"$target>$toptitle</a></li> \n";
			}
		}
	}
	writetocache('logs_header',$output,"html");
}

function logs_sidebar_recache($arrSideModule){
	global $settingInfo,$strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;

	ob_start();
	$arrReadModule=array();

	foreach($arrSideModule as $key=>$value){
		$indexOnly=$value['indexOnly'];
		
		if ($indexOnly==0) {
			$arrReadModule[$key]=$value;
		}
		echo createSideFunc($value,$key);
	}
	$contents=str_replace("\r\n","",ob_get_contents());
	ob_end_clean();
	writetocache('logs_sidebar',$contents,"html");

	ob_start();
	foreach($arrReadModule as $key=>$value){	
		echo createSideFunc($value,$key);
	}
	$contents=str_replace("\r\n","",ob_get_contents());
	ob_end_clean();
	writetocache('logs_readsidebar',$contents,"html");
}

function createSideFunc($value,$key) {
	global $settingInfo,$strModifyInfo,$strLogout,$strLoginSubmit,$strUserRegister;
	global $strSearchErr,$strKeyword,$strSearchTitle,$strSearchContent,$strSearchTitleContent,$strFind;

	$contents="";
	ob_start();
	if (is_array($value)){
		$sidename=(is_int($key) && !empty($value['name']))?$value['name']:$key;
		$sidetitle=$value['modTitle'];
		$htmlcode=empty($value['htmlCode'])?"":$value['htmlCode'];
		$installDate=empty($value['installDate'])?"":$value['installDate'];
		$pluginPath=empty($value['pluginPath'])?"":$value['pluginPath'];
		$isInstall=$value['isInstall'];
		

		if (in_array($sidename,array("statistics","category","guestbook","hotTags","recentlogs","recentComments","archives","links"))){
			echo readfromfile(F2BLOG_ROOT."cache/cache_$sidename.php");
		}else{
			if ($installDate>0){//表示为插件
				echo '<?php do_filter("'.$sidename.'","'.$sidename.'","',$sidetitle.'","',$htmlcode.'","'.$isInstall.'");'."?>\n";
			}else{
				switch ($sidename){
					case "calendar":
						echo create_sidebar_header("Calendar",$sidetitle,$isInstall);
						echo "<?php \n";
						echo "if (!empty(\$job) && \$job==\"calendar\" && \$seekname!=gmdate('Ym', time()+3600*\$settingInfo['timezone'])){\n";
						echo "	if (\$settingInfo['showcalendar']==1){\n";
						echo "		include(\"include/ncalendar.inc.php\");\n";
						echo "	}else{\n";
						echo "		include(\"include/calendar.inc.php\");\n";
						echo "	}\n";
						echo "}else{\n";
						echo "	echo readfromfile(F2BLOG_ROOT.\"./cache/cache_calendar.php\");\n";
						echo "}?>\n";
						break;
					case "skinSwitch":
						echo create_sidebar_header("SkinSwitchForPJBlog",$sidetitle,$isInstall);
						echo "<form name=\"skinForm\" action=\"\" method=\"post\" style=\"margin:0px;\">\n";
						echo "  <select name=\"skinSelect\" onchange=\"if (this.value!='0') {document.forms['skinForm'].submit();}\">\n";
						echo "	<?php \n";
						echo "	foreach(\$skinlistcache as \$key=>\$value){\n";
						echo "		\$selected=(\$key==\$blogSkins)?\"selected\":\"\";\n";
						echo "		echo \"<option value='\$key' \$selected>\$key</option>\n\";\n";
						echo "	}\n";
						echo "	?>\n";
						echo "  </select>\n";
						echo "</form>\n";
						break;
					case "aboutBlog":
						echo create_sidebar_header("AboutMe",$sidetitle,$isInstall);
						echo "<p align=\"center\"> \n";
						echo "<img src=\"./attachments/".$settingInfo['logo']."\" align=\"middle\" alt=\"\" />\n";
						echo "<br />".$settingInfo['name']."<br />".$settingInfo['blogTitle']."\n";
						echo "</p> \n";
						break;
					case "userPanel":
						echo create_sidebar_header("User",$sidetitle,$isInstall);
						echo "<?php  if (!empty(\$_SESSION['username']) && \$_SESSION['username']!=\"\") { ?> \n";
						echo "<a href=\"register.php\" class=\"sideA\">$strModifyInfo</a> \n";
						echo "<a href=\"login.php?action=logout\" class=\"sideA\">$strLogout</a> \n";
						echo "<?php  } else { ?> \n";
						echo "<?php  if (\$settingInfo['loginStatus']==0) { ?> \n";
						echo "<a href=\"login.php\" class=\"sideA\">$strLoginSubmit</a> \n";
						echo "<?php  } ?> \n";
						echo "<?php  if (\$settingInfo['isRegister']==0) { ?> \n";
						echo "<a href=\"register.php\" class=\"sideA\">$strUserRegister</a> \n";
						echo "<?php  } ?> \n";
						echo "<?php  } ?> \n";
						break;
					case "search":
						echo create_sidebar_header("Search",$sidetitle,$isInstall);
						echo "<form style=\"MARGIN: 0px\" onsubmit=\"if (this.seekname.value.length&lt;1) {alert('".$strSearchErr."');this.seekname.focus();return false}\" action=\"index.php\"> \n";
						echo "  $strKeyword ";
						if ($settingInfo['disSearch']==0){//不显示类别与按键
							echo "  <input class=\"userpass\" name=\"seekname\" onmouseup=\"document.getElementById('searchbar').style.display=''\"/> \n";
							echo "	<div style=\"overflow: hidden; height: 3px\">&nbsp;</div> \n";
							echo "  <div id=\"searchbar\" style=\"display:none\"> \n";
							echo "	<select name=\"job\"> \n";
							echo "	<option value=\"searchTitle\" selected=\"selected\">$strSearchTitle</option> \n";
							echo "	<option value=\"searchContent\">$strSearchContent</option> \n";
							echo "	<option value=\"searchAll\">$strSearchTitleContent</option> \n";
							echo "	</select> \n";
							echo "  <input name=\"submit\" type=\"submit\" class=\"userbutton\" value=\"$strFind\" /> \n";
							echo "  </div> \n";
						}else{
							echo "  <input class=\"userpass\" name=\"seekname\"> \n";
							echo "	<div style=\"overflow: hidden; height: 3px\">&nbsp;</div> \n";
							echo "	<select name=\"job\"> \n";
							echo "	<option value=\"searchTitle\" selected=\"selected\">$strSearchTitle</option> \n";
							echo "	<option value=\"searchContent\">$strSearchContent</option> \n";
							echo "	<option value=\"searchAll\">$strSearchTitleContent</option> \n";
							echo "	</select> \n";
							echo "  <input name=\"submit\" type=\"submit\" class=\"userbutton\" value=\"$strFind\" /> \n";
						}
						echo "</form> \n";
						break;
					default://自定HTML代码
						echo create_sidebar_header($sidename,$sidetitle,$isInstall);
						echo htmldecode($htmlcode);
						break;
				}
				echo create_sidebar_footer();				
			}
		}

		$contents=str_replace("\r\n","",ob_get_contents());
		ob_end_clean();
	}

	return $contents;
}

// 附件
function attachments_recache() {
	global $DMC, $DBPrefix;
	
	$contents = "\$cacheattachments = array(\r\n";
	$result = $DMC->query("select * from ".$DBPrefix."attachments order by id desc");
	while ($my = $DMC->fetchArray($result)) {
		$contents.="\t'".$my['id']."' => array(\r\n";
		$contents.="\t\t'name' => '".$my['name']."',\n";
		$contents.="\t\t'attTitle' => '".$my['attTitle']."',\n";
		if ($my['fileWidth']>0)	$contents.="\t\t'fileWidth' => '".$my['fileWidth']."',\n";
		if ($my['fileHeight']>0) $contents.="\t\t'fileHeight' => '".$my['fileHeight']."',\n";
		if($my['downloads']>0) $contents.="\t\t'downloads' => '".$my['downloads']."',\n";

		$contents .= "\t\t),\n";
	}
	$contents .= ");";
	writetocache('attachments',$contents);
}

// 附件下载次数
function download_recache() {
	global $DMC, $DBPrefix;
	
	$contents = "\$cachedownload = array(\r\n";
	$result = $DMC->query("select id,downloads from ".$DBPrefix."attachments order by id desc");
	while ($my = $DMC->fetchArray($result)) {
		if($my['downloads']>0) {
			$contents.="\t'".$my['id']."' => '".$my['downloads']."',\n";
		}
	}
	$contents .= ");";
	writetocache('download',$contents);
}

// 更新日志标题
function logsTitle_recache() {
	global $DMC, $DBPrefix;
	
	$result = $DMC->query("select *,a.id as id from ".$DBPrefix."logs as a inner join ".$DBPrefix."categories as b on a.cateId=b.id where saveType='1' order by postTime desc");
	$contents = "\$logsTitlecache = array(";
	while ($my = $DMC->fetchArray($result)) {
		//$contents.="\t'".$my['id']."' => array(\n\t\t'cateName' => '".$my['name']."',\n\t\t'cateId' => '".$my['cateId']."',\n\t\t'logTitle' => '".$my['logTitle']."',\n\t\t'author' => '".$my['author']."',\n\t\t'commNums' => '".$my['commNums']."',\n\t\t'viewNums' => '".$my['viewNums']."',\n\t\t'quoteNums' => '".$my['quoteNums']."',\n\t\t),\n";
		//$contents.="\t'".$my['id']."' => '".$my['name']." - ".$my['logTitle']."',\n";
		//$contents.="'".$my['id']."',";
		$contents.="\t'".$my['id']."' => '".str_replace("'","&#39;",$my['logTitle'])."',\n";
	}
	$contents .= ");";
	writetocache('logsTitle',$contents);
}

// 更新最新日志
function recentLogs_recache() {
	global $DMC, $DBPrefix, $settingInfo,$strRecentLogs,$arrSideModule,$strSideBarAnd,$strHomePagePost;
	
	$out_contents=create_sidebar_header("NewLogForPJBlog",$arrSideModule["recentlogs"]["modTitle"],$arrSideModule["recentlogs"]["isInstall"]);

	if ($settingInfo['rewrite']==0) $gourl="index.php?load=read&amp;id=";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/read-";
	if ($settingInfo['rewrite']==2) $gourl="read-";
	
	
	$i=0;
	$maxlength=$settingInfo['sidelogslength'];
	$result = $DMC->query("select a.postTime,b.nickname,a.author,a.id,a.logTitle from {$DBPrefix}logs as a left join {$DBPrefix}members as b on a.author=b.username where a.saveType='1' order by a.postTime desc Limit 0,$settingInfo[sidelogsPage]");
	while ($my = $DMC->fetchArray($result)) {
		$author=($my['nickname']!="")?$my['nickname']:$my['author'];
		$content=strip_tags($my['logTitle']);
		$content=subString($content,0,$maxlength);

		$content=str_replace("{","!##_#_###_###_##!",$content);
		$content=str_replace("}","!##_###_###_##_#!",$content);
		$show_content=str_replace("[","\[",$settingInfo['sidelogsstyle']);
		$show_content=str_replace("]","\]",$show_content);
		$show_content=preg_replace("/({title})/is",$content,$show_content);
		$show_content=preg_replace("/({author})/is",$author,$show_content);
		$show_content=preg_replace("/{(.*?)}/ie","format_time('\\1',$my[postTime])",$show_content);
		$show_content=str_replace("\[","[",$show_content);
		$show_content=str_replace("\]","]",$show_content);
		$show_content=str_replace("!##_#_###_###_##!","{",$show_content);
		$show_content=str_replace("!##_###_###_##_#!","}",$show_content);

		$out_contents.="	<a class=\"sideA\" id=\"NewLog_Link_".$my['id']."\" title=\"".$author." $strSideBarAnd ".format_time("L",$my['postTime'])." $strHomePagePost \r\n".$my['logTitle']."\" href=\"$gourl".$my['id']."{$settingInfo['stype']}\">".$show_content."</a> \n";
		$i++;
	}
	$out_contents.=create_sidebar_footer();

	writetocache("recentlogs",$out_contents,'html');
}

// 更新最新评论
function recentComments_recache() {
	global $DMC,$DBPrefix,$strGuestBookHidden,$settingInfo,$strCommentsTitle,$arrSideModule,$strSideBarAnd;
	
	$out_contents=create_sidebar_header("Comment",$arrSideModule["recentComments"]["modTitle"],$arrSideModule["recentComments"]["isInstall"]);

	if ($settingInfo['rewrite']==0) $gourl="index.php?load=read&amp;id=";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/read-";
	if ($settingInfo['rewrite']==2) $gourl="read-";
	
	$i=0;
	$maxlength=$settingInfo['sidecommentlength'];
	$result = $DMC->query("select a.*,a.author as author,a.id as id,c.nickname from ".$DBPrefix."comments as a inner join ".$DBPrefix."logs as b on a.logId=b.id left join {$DBPrefix}members as c on a.author=c.username where b.saveType=1 order by a.postTime desc Limit 0,$settingInfo[commPage]");
	while ($my = $DMC->fetchArray($result)) {
		if ($my['isSecret']){
			$content=$strGuestBookHidden;
			$my['content']=$strGuestBookHidden;
		}else{
			$my['content']=strip_tags($my['content']);
			$content=subString($my['content'],0,$maxlength);
		}
		$author=($my['nickname']!="")?$my['nickname']:$my['author'];
		$content=str_replace("{","!##_#_###_###_##!",$content);
		$content=str_replace("}","!##_###_###_##_#!",$content);
		$show_content=str_replace("[","\[",$settingInfo['sidecommentstyle']);
		$show_content=str_replace("]","\]",$show_content);
		$show_content=preg_replace("/({title})/is",$content,$show_content);
		$show_content=preg_replace("/({author})/is",$author,$show_content);
		$show_content=preg_replace("/{(.*?)}/ie","format_time('\\1',$my[postTime])",$show_content);
		$show_content=str_replace("\[","[",$show_content);
		$show_content=str_replace("\]","]",$show_content);
		$show_content=str_replace("!##_#_###_###_##!","{",$show_content);
		$show_content=str_replace("!##_###_###_##_#!","}",$show_content);

		$out_contents.="	<a class=\"sideA\" id=\"comments_Link_".$my['id']."\" title=\"".$author." $strSideBarAnd ".format_time("L",$my['postTime'])." $strCommentsTitle \r\n".$my['content']."\"  href=\"$gourl".$my['logId']."{$settingInfo['stype']}#book".$my['id']."\">".$show_content."</a> \r\n";
		$i++;
	}
	$out_contents.=create_sidebar_footer();

	writetocache("recentComments",$out_contents,'html');
}

// 更新最新留言
function recentGbooks_recache() {
	global $DMC,$DBPrefix,$strGuestBookHidden,$settingInfo,$strSideBarAnd,$strSideBarGuestBook,$strRecentGBook,$arrSideModule;
	
	$out_contents=create_sidebar_header("GuestBookForPJBlogSubItem1",$arrSideModule["guestbook"]["modTitle"],$arrSideModule["guestbook"]["isInstall"]);

	if ($settingInfo['rewrite']==0) $gourl="index.php?load=guestbook";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/guestbook";
	if ($settingInfo['rewrite']==2) $gourl="guestbook";

	$i=0;
	$maxlength=$settingInfo['sidegbooklength'];
	$result = $DMC->query("select a.id,a.content,a.author,a.postTime,b.nickname,a.isSecret from {$DBPrefix}guestbook as a left join {$DBPrefix}members as b on a.author=b.username order by postTime desc Limit 0,$settingInfo[gbookPage]");
	while ($my = $DMC->fetchArray($result)) {
		$author=($my['nickname']!="")?$my['nickname']:$my['author'];
		if ($my['isSecret']){
			$content=$strGuestBookHidden;
			$my['content']=$strGuestBookHidden;
		}else{
			$my['content']=strip_tags($my['content']);
			$content=subString($my['content'],0,$maxlength);
		}

		$content=str_replace("{","!##_#_###_###_##!",$content);
		$content=str_replace("}","!##_###_###_##_#!",$content);
		$show_content=str_replace("[","\[",$settingInfo['sidegbookstyle']);
		$show_content=str_replace("]","\]",$show_content);
		$show_content=preg_replace("/({title})/is",$content,$show_content);
		$show_content=preg_replace("/({author})/is",$author,$show_content);
		$show_content=preg_replace("/{(.*?)}/ie","format_time('\\1',$my[postTime])",$show_content);
		$show_content=str_replace("\[","[",$show_content);
		$show_content=str_replace("\]","]",$show_content);
		$show_content=str_replace("!##_#_###_###_##!","{",$show_content);
		$show_content=str_replace("!##_###_###_##_#!","}",$show_content);

		$out_contents.="	<a class=\"sideA\" id=\"GuestBook_Link".$my['id']."\" title=\"".$author." $strSideBarAnd ".format_time("L",$my['postTime'])." $strSideBarGuestBook \r\n".$my['content']."\"  href=\"$gourl{$settingInfo['stype']}#book".$my['id']."\">".$show_content."</a> \r\n";
		$i++;
	}
	$out_contents.=create_sidebar_footer();

	writetocache("guestbook",$out_contents,'html');
}

// 更新归档
function archives_recache() {
	global $DMC,$DBPrefix,$settingInfo,$strYear,$strMonth,$strArchives,$arrSideModule;
	
	$archives = $DMC->query("SELECT postTime FROM ".$DBPrefix."logs where saveType='1' ORDER BY postTime DESC");
	$articledb = array();
	while ($article = $DMC->fetchArray($archives)) {
		$article['dateline'] = format_time("Y,m",$article['postTime']);
		$articledb[]=$article['dateline'];
	}
	unset($article);
	$archivedb = array_count_values($articledb);
	unset($articledb);

	$out_contents=create_sidebar_header("Archive",$arrSideModule["archives"]["modTitle"],$arrSideModule["archives"]["isInstall"]);
	$i=0;
	foreach($archivedb as $key => $val){
		if ($i>=$settingInfo['archivesmonth']) { break; }
		$i++;

		list($logYear,$logMonth)=explode(",",$key);
		$logName="$logYear $strYear $logMonth $strMonth";

		if ($settingInfo['rewrite']==0) $gourl="index.php?job=archives&amp;seekname=$logYear$logMonth";
		if ($settingInfo['rewrite']==1) $gourl="rewrite.php/archives-$logYear$logMonth";
		if ($settingInfo['rewrite']==2) $gourl="archives-$logYear$logMonth";

		$out_contents.="	<a class=\"sideA\" id=\"Archive_Link_".$i."\" href=\"".$gourl."{$settingInfo['stype']}\">".$logName." [".$val."]</a> \r\n";
	}
	$out_contents.=create_sidebar_footer();

	writetocache("archives",$out_contents,"html");
}

// 更新热门标签
function hottags_recache() {
	global $DMC,$DBPrefix,$settingInfo,$strTagsCount,$strHotTags,$arrSideModule;
	
	//取得标签数量
	$arr_result=$DMC->fetchArray($DMC->query("select count(id) as count from ".$DBPrefix."tags"));
	$tags_count=$arr_result['count'];

	//取得显示标签数量
	if ($settingInfo['tagNums']<$tags_count){
		$limit=$settingInfo['tagNums'];
	}else{
		$limit=$tags_count;
	}
	if ($limit<1) $limit=50;
	
	//保存标签最大最小数
	$arr_result=$DMC->fetchArray($DMC->query("select max(logNums) as max,min(logNums) as min from ".$DBPrefix."tags"));
	if ($arr_result['max']=="") $arr_result['max']=0;
	if ($arr_result['min']=="") $arr_result['min']=0;

	if ($settingInfo['rewrite']==0) $gourl="index.php?job=tags&amp;seekname=";
	if ($settingInfo['rewrite']==1) $gourl="rewrite.php/tags-";
	if ($settingInfo['rewrite']==2) $gourl="tags-";

	foreach($arrSideModule as $key=>$value){
		if (!empty($value['name']) && $value['name']=="hotTags"){
			$sidename=$value['name'];
			$sidetitle=$value['modTitle'];
			$isInstall=$value['isInstall'];
			break;
		}
	}
	$out_contents=create_sidebar_header("HotTags",$arrSideModule["hotTags"]["modTitle"],$arrSideModule["hotTags"]["isInstall"]);

	$result = $DMC->query("select * from ".$DBPrefix."tags order by logNums desc limit 0,$limit");
	while ($my = $DMC->fetchArray($result)) {
		$curColor=getTagHot($my['logNums'],$arr_result['max'],$arr_result['min']);
		$strDayLog=$strTagsCount.": ".$my['logNums'];

		$out_contents.="	<a href=\"$gourl".urlencode($my['name'])."{$settingInfo['stype']}\" style=\"color:$curColor\" title=\"$strDayLog\">".$my['name']."</a> \n";
	}
	$out_contents.=create_sidebar_footer();

	writetocache("hotTags",$out_contents,"html");
}

// 日志信息
function statistics_recache(){
	global $DMC,$DBPrefix,$settingInfo,$arrSideModule;
	global $strStatisticsCategory,$strStatisticsLogs,$strStatisticsComments,$strStatisticsTags,$strStatisticsAttachments,$strStatisticsGuestbook;
	global $strStatisticsQuote,$strStatisticsUser,$strStatisticsToday,$strStatisticsYesterday,$strStatisticsTotal,$strStatisticsOnline;
	
	$output=array();
	if ($settingInfo['showtoday']==1)	$output[]="$strStatisticsToday: <?php echo \$cache_visits_today?>";
	if ($settingInfo['showyester']==1) $output[]="$strStatisticsYesterday: <?php echo \$cache_visits_yesterday?>";
	if ($settingInfo['showtotal']==1) $output[]="$strStatisticsTotal: <?php echo \$cache_visits_total+\$settingInfo['visitNums']?>";
	if ($settingInfo['showonline']==1) $output[]="$strStatisticsOnline: <?php echo \$online_count?>";
	if ($settingInfo['showlogs']==1) $output[]="$strStatisticsLogs: <?php echo \$settingInfo['setlogs']?>";
	if ($settingInfo['showcate']==1) $output[]="$strStatisticsCategory: <?php echo \$settingInfo['setcategories']?>";
	if ($settingInfo['showcomment']==1) $output[]="$strStatisticsComments: <?php echo \$settingInfo['setcomments']?>";
	if ($settingInfo['showtags']==1) $output[]="$strStatisticsTags: <?php echo \$settingInfo['settags']?>";
	if ($settingInfo['showattach']==1) $output[]="$strStatisticsAttachments:  <?php echo \$settingInfo['setattachments']?>";
	if ($settingInfo['showquote']==1) $output[]="$strStatisticsQuote: <?php echo \$settingInfo['settrackbacks']?>";
	if ($settingInfo['showuser']==1) $output[]="$strStatisticsUser: <?php echo \$settingInfo['setmembers']?>";
	if ($settingInfo['showguest']==1) $output[]="$strStatisticsGuestbook: <?php echo \$settingInfo['setguestbook']?>";

	if (count($output)>0){
		$out_contents=create_sidebar_header("BlogInfo",$arrSideModule["statistics"]["modTitle"],$arrSideModule["statistics"]["isInstall"]);
		$out_contents.=implode("<br />\n",$output);
		$out_contents.="<?php do_action(\"f2_stat\");?>";
		$out_contents.=create_sidebar_footer();

		writetocache("statistics",$out_contents,"html");
	}
}

// 更新分类
function categories_recache() {
	global $DMC,$DBPrefix,$settingInfo,$strCategory,$arrSideModule,$strAllCategory,$strPrivateLog;

	//get sum category
	$sum_sql="select sum(cateCount) as sum_total from ".$DBPrefix."categories where parent='0' and isHidden='0'";
	$sum_result=$DMC->query($sum_sql);
	if ($arr_result=$DMC->fetchArray($sum_result)){
		$sum_total=($arr_result['sum_total']=="" or $arr_result['sum_total']==0)?0:$arr_result['sum_total'];
	}else{
		$sum_total=0;
	}

	//隐私日志
	$arr_private=$DMC->fetchArray($DMC->query("select count(id) as private_count from ".$DBPrefix."logs where saveType=3"));
	$private_count=$arr_private['private_count'];

	//get main category
	$query_sql="select id,name,cateTitle,outLinkUrl,cateCount,cateIcons from ".$DBPrefix."categories where parent='0' and isHidden='0' order by orderNo";
	$query_result=$DMC->query($query_sql);
	$arr_parent = $DMC->fetchQueryAll($query_result);
	for ($i=0;$i<count($arr_parent);$i++){
		//get sub category
		$sub_sql="select id,name,cateTitle,outLinkUrl,cateCount,cateIcons from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' and isHidden='0' order by orderNo";
		$sub_result=$DMC->query($sub_sql);
		$arr_sub[$i] = $DMC->fetchQueryAll($sub_result);
	}

	ob_start();
	if (strpos($settingInfo['categoryImgPath'],"tree")>0){
		include(F2BLOG_ROOT."./include/treemenu.inc.php");
	}else{
		include(F2BLOG_ROOT."./include/ulmenu.inc.php");
	}

	$out_contents=create_sidebar_header("Category",$strCategory,$arrSideModule["category"]["isInstall"]);
	$out_contents.=ob_get_contents();
	$out_contents.=create_sidebar_footer();
	ob_end_clean();

	writetocache('category',$out_contents,"html");
}

function create_sidebar_header($sidename,$sidetitle,$isInstall){
	$sidebar_header='<?php '."\r\n";
	$sidebar_header.='if (isset($_COOKIE["content_'.$sidename.'"])){'."\r\n";
	$sidebar_header.='	$display=$_COOKIE["content_'.$sidename.'"];'."\r\n";
	$sidebar_header.='}else{'."\r\n";
	if ($isInstall>0){
		$sidebar_header.=' 	$display="none";'."\r\n";
	}else{
		$sidebar_header.=' 	$display="";'."\r\n";
	}
	$sidebar_header.='}'."\r\n";
	$sidebar_header.='?>'."\r\n";
	$sidebar_header.='<!--'.$sidetitle.'-->'."\r\n";
	$sidebar_header.='<div class="sidepanel" id="Side_'.$sidename.'">'."\r\n";
	$sidebar_header.='  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools(\'content_'.$sidename.'\')">'.$sidetitle.'</h4>'."\r\n";
	$sidebar_header.='  <div class="Pcontent" id="content_'.$sidename.'" style="display:<?php echo $display?>">'."\r\n";
	$sidebar_header.='	<div class="'.$sidename.'Table" id="'.$sidename.'_Body">'."\r\n";

	return $sidebar_header;
}

function create_sidebar_footer(){
	$sidebar_footer="	</div> \r\n";
	$sidebar_footer.="  </div> \r\n";
	$sidebar_footer.="  <div class=\"Pfoot\"></div> \r\n";
	$sidebar_footer.="</div> \r\n";

	return $sidebar_footer;
}

// 更新评论的数量到日志中
function totalComments_recache() {
	global $DMC,$DBPrefix;
	
	$result = $DMC->query("select count(id) as commNums,logId from ".$DBPrefix."comments group by logId order by logId");
	$arr_result=$DMC->fetchQueryAll($result);
	foreach ($arr_result as $key=>$value){
		$commNums=$value['commNums'];
		$logId=$value['logId'];
		$update="update ".$DBPrefix."logs set commNums='$commNums' where id='$logId'";
		//echo $update."<br />";
		$DMC->query($update);
	}
}

//更新数量
function settings_recount($fields="") {
	global $DMC,$DBPrefix;
	
	$arrTotal=array("logs"=>"where saveType='1'","comments"=>"","trackbacks"=>"","guestbook"=>"","members"=>"where role='member'","attachments"=>"","tags"=>"","categories"=>"where isHidden='0'");
	
	if ($fields!=""){ //更新某个字段
		$sql="select count(id) as v_total from ".$DBPrefix.$fields." ".$arrTotal[$fields];
		$arr_result=$DMC->fetchArray($DMC->query($sql));

		$update="UPDATE ".$DBPrefix."setting set settValue='".$arr_result['v_total']."' where settName='set".$fields."'";
		$DMC->query($update);
	}else{ //重新统计所有
		foreach($arrTotal as $key=>$value){
			$sql="select count(id) as v_total from ".$DBPrefix."$key $value";
			$arr_result=$DMC->fetchArray($DMC->query($sql));

			$update="UPDATE ".$DBPrefix."setting set settValue='".$arr_result['v_total']."' where settName='set".$key."'";
			$DMC->query($update);
		}
	}
}

//更新所有Cache
function reAllCache() {
	global $arrSideModule;
	categories_recount();
	categories_recache();	
	calendar_recache();
	statistics_recache();
	hottags_recache();
	archives_recache();
	links_recache();
	filters_recache();
	keywords_recache();
	recentLogs_recache();
	recentComments_recache();
	recentGbooks_recache();
	logsTitle_recache();
	modulesSetting_recache();
	download_recache();
	attachments_recache();
	members_recache();
	skinlist_recache();
	online_recache();
	logs_sidebar_recache($arrSideModule);
}
?>
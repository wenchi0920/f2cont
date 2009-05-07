<?php
/*
Plugin Name: EnglishXML
Plugin URI: http://korsen.f2blog.com
Description: 英语学习插件
Author: korsen & PuterJam
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function EnglishXML_install() {
	$arrPlugin['Name']="EnglishXML";  //Plugin name
	$arrPlugin['Desc']="英语学习插件";  //Plugin title
	$arrPlugin['Type']="Main";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path
	$arrPlugin['indexOnly']="0";     //$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function EnglishXML_unstall() {
	$ActionMessage=unstall_plugins("EnglishXML");
	return $ActionMessage;
}

#英语学习
function englishSentence($mainname,$maintitle,$htmlcode){
?>
<!--英语学习-->
<div id="MainContent_<?php echo $mainname?>" class="content-width">
  <link rel="stylesheet" href="plugins/EnglishXML/english.css" />
  <script type="text/javascript" src="plugins/EnglishXML/english.js"></script>
  <script type="text/javascript">InitEnglishStyle();var EngCTime=15</script>
  <div id="EngCn">
    <div id="EnglishDiv" class="AlDiv">English Sentence Loading...</div>
    <div id="ChineseDiv" class="AlDiv">英语句子加载中...</div>
  </div>
  <script type="text/javascript">window.setTimeout("StartEnglish()",1000)</script>
</div>
<?php
} #END英语学习

add_filter('EnglishXML','englishSentence',4);
?>
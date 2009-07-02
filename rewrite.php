<?php
error_reporting(0);

$PHP_SELF = $_SERVER['REQUEST_URI'];

$rewrite=explode("rewrite.php",$PHP_SELF);
$base_rewrite="http://".$_SERVER['HTTP_HOST'].$rewrite[0];

if (strpos($PHP_SELF,".html")>0){
	$string_nav=str_replace(".html","",basename($PHP_SELF));
	$vars = explode("-", $string_nav);
	if (count($vars)>0){
		if ($vars[0]=="test"){ //测试页面
			header("Location: ../testrewrite.php?test=$vars[1]");
			exit;
		}
		if (is_numeric($vars[0])){
			$_GET['page']=$vars[0];
			if (is_numeric($vars[1])) $_GET['disType']=$vars[1];
		}
		if ($vars[0]=="read" || $vars[0]=="links"){
			$_GET['load']=$vars[0];
			if (is_numeric($vars[1])) $_GET[id]=$vars[1];
			if (is_numeric($vars[2])) $_GET['page']=$vars[2];
		}
		if ($vars[0]=="guestbook"){
			$_GET['load']=$vars[0];
			if (is_numeric($vars[1])) $_GET['page']=$vars[1];
		}
		if ($vars[0]=="archives" || $vars[0]=="tags"){
			if (!empty($vars[1])) {
				$_REQUEST['job']=$vars[0];
				$_GET['seekname']=$vars[1];
				if (is_numeric($vars[2])) $_GET['page']=$vars[2];
				if (is_numeric($vars[3])) $_GET['disType']=$vars[3];
			}else{
				$_GET['load']=$vars[0];
			}
		}
		if (in_array($vars[0],array("calendar","category","searchTitle","searchContent","searchAll"))){
			$_REQUEST['job']=$vars[0];
			$_GET['seekname']=$vars[1];
			if (is_numeric($vars[2])) $_GET['page']=$vars[2];
			if (is_numeric($vars[3])) $_GET['disType']=$vars[3];
		}
		if ($vars[0]=="f2bababian"){
			$_GET['load']=$vars[0];
			$_GET['bbbphoto']=$vars[1];
			if ($vars[2]=="set"){
				if (is_numeric($vars[3])){
					$_GET['page']=$vars[3];
					$_GET['setid']=$vars[4];				
				}else{
					$_GET['setid']=$vars[3];
					$_GET['page']=$vars[4];
					if ($vars[5]!="") $_GET['did']=$vars[5];
				}
			}else{
				$_GET['page']=$vars[2];
				if ($vars[3]!="") $_GET['did']=$vars[3];
			}	
		}
	}
}

//调用index.php文件。
include("index.php");
?>
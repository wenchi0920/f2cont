<?php 
include("include/function.php");

if (!empty($_GET['ajax_display'])){
	$ajax_display=$_GET['ajax_display'];
}else if (!empty($_POST['ajax_display'])){
	$ajax_display=$_POST['ajax_display'];
}else{
	$ajax_display="";
}

switch ($ajax_display){
	case "content_page":
		include("include/content_page_ajax.inc.php");
		break;
	case "gbook_page":
		include("include/guestbook_page_ajax.inc.php");
		break;	
	case "gbook_post":
		include_once("include/cache.php");
		include("include/guestbook_post_ajax.inc.php");
		break;	
	case "comment_page":
		include("include/comment_page_ajax.inc.php");
		break;	
	case "comment_post":
		include_once("include/cache.php");
		include("include/comment_post_ajax.inc.php");
		break;
	case "trackback_session":
		include("include/trackback_session_ajax.inc.php");
		break;
	case "logspassword_login":
		include("include/logspassword_ajax.inc.php");
		break;	
	case "calendar":
		if ($settingInfo['showcalendar']==1){
			include("include/ncalendar.inc.php");
		}else{
			include("include/calendar.inc.php");
		}
		break;	
	case "media":
		include_once("include/cache.php");
		include("include/readmedia.inc.php");
		break;	
	default:
		echo $strAjaxError;
}
?>
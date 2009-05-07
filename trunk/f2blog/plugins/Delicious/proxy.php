<?php
header("Content-Type:text/xml");
header('Content-Type: text/xml; charset=utf-8');
header('Cache-Control', 'no-cache');

function getHttp($url){
	$http_handle = fopen($url, 'r');
	$http_content = "";
	while(!feof($http_handle)){
		$temp_buffer = fgets($http_handle, 1024);
		$http_content .= $temp_buffer;
	}
	fclose($http_handle);		

	return $http_content;
}

$url = "http://del.icio.us/rss/";
$user = $_GET["user_name"];

if($user == ""){
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n<rdf/>";
	return;
}

$user = htmlspecialchars($user, ENT_QUOTES);
$url .= $user;

$feed = getHttp($url);
echo $feed;

?>
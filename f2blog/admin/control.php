<?
$PATH="../";
include("./function.php");

// 验证用户是否处于登陆状态
check_login();

if ($_SESSION['prelink']!="" && strpos($_SESSION['prelink'],"control.php")<0){
	$to_url=$_SESSION['prelink'];
} else{
	$to_url="info.php";
}
?>
<html>
<head>
<title><?=$strLoginTitle?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<frameset rows="50,*" cols="*" border="0" frameborder="no" framespacing="0">
	<frame name="top" src="top.php" noresize="noresize" scrolling="no" />
	<frameset cols="186,*" border="0" frameborder="no" framespacing="0" id="contentid">
		<frame name="left" src="left.php" scrolling="no" noresize="noresize" />
		<frame name="main" src="<?=$to_url?>" scrolling="yes" />
	</frameset>

	<noframes>
		<body></body>
	</noframes>
</frameset>
</html>

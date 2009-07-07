<?
$PATH="./";
include_once("$PATH/function.php");

// 验证用户是否处于登陆状态
check_login();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="images/css/style.css">
</head>
<body>
<div id="top">
	<a href="control.php" target="_parent"><div class="logo"></div>	</a>
	<a href="../index.php" target="_top"><div class="exit"></div></a>
	<div class="info"></div>
</div>
</body>
</html>
<?php
include_once("../../include/config.php");
include_once ("../../include/db.php");

// 连结数据库
$DMC = new F2MysqlClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);
 
$DMC->query("DROP TABLE `{$DBPrefix}music`");
$DMC->query("DROP TABLE `{$DBPrefix}musicclass`");
$DMC->query("DROP TABLE `{$DBPrefix}musicsetting`");
?>
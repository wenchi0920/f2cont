<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>搜索巴巴变照片</title>
<link rel="stylesheet" rev="stylesheet" href="../../skins/<?php echo $_GET[blogSkins]?>/layout.css" type="text/css" media="all" />
<!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="../../skins/<?php echo $_GET[blogSkins]?>/typography.css" type="text/css" media="all" />
<!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="../../skins/<?php echo $_GET[blogSkins]?>/link.css" type="text/css" media="all" />
<!--超链接样式表-->
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
</head>

<body>
<h2>高级搜索： </h2>
<form method="post" action="http://www.bababian.com/seallphoto.sl" target="_blank">
  <input name="type" value="tag" type="hidden">
  <input value="moretag" name="path" type="hidden">
  <p style="MARGIN-BOTTOM:5px" class="fontMauve"> <font>按照关键字搜索</font> </p>
  <input maxlength="100" name="search" size="35" class="userpass">
  &nbsp;
  <input value=" 搜索 " class="userbutton" type="submit" name="submit">
  <br />
  <p style="color:#999999;MARGIN-TOP: 5px;MARGIN-BOTTOM:0px"> 例如：搜索照片关键字<em>漂亮 青岛 女孩</em> </p>
  <br />
  <br />
  <input name="all" value="0" checked type="radio" id="tagmodeall">
  <label for="tagmodeall"> 搜索包含<b>所有</b>关键字的照片 </label>
  <br />
  <input name="all" value="1" type="radio" id="tagmodeany">
  <label for="tagmodeany"> 搜索包含<b>任何一个</b>关键字的照片 </label>
</form>
<form method="post" action="http://www.bababian.com/seallphoto.sl" target="_blank">
  <input name="type" value="title" type="hidden">
  <input value="title" name="path" type="hidden">
  <p style="MARGIN-BOTTOM:5px" class="fontMauve"> <font>按照标题、关键字、描述搜索</font> </p>
  <input maxlength="100" name="search" size="35" class="userpass">
  &nbsp;
  <input value=" 搜索 " class="userbutton" type="submit" name="submit">
</form>
</body>
</html>

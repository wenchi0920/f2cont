<?php
//后台设定
function MyIcon_setCode($arr) {
	if (empty($arr['myIconsDefault'])) $arr['myIconsDefault']="images/avatars/1.gif";
	if (empty($arr['myIconsSize'])) $arr['myIconsSize']="80";

	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\" width=\"100%\"> \n";
	$string .= "  <tr> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">默认头像地址(gif/jpg/png)</td> \n";
	$string .= "    <td class=\"whitefont\"> <input name=\"myIconsDefault\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['myIconsDefault']."\"/> <br>用于访客邮箱没有申请头像时的显示图片。可以使用http://的图片绝对路径或者相对ＢＬＯＧ根目录的图片相对路径</td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">图像尺寸(30-80px）</td> \n";
	$string .= "    <td class=\"whitefont\"> <input name=\"myIconsSize\" class=\"textbox\" type=\"input\" size=\"30\" value=\"".$arr['myIconsSize']."\"/></td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr> \n";
	$string .= "    <td align=\"right\" width=\"30%\"><br />使用说明：</td> \n";
	$string .= "    <td class=\"whitefont\"><br />如果站长自己想拥有个性化的头像，可以使用你的邮箱去这里申请一个<a href=\"http://www.myicon.com.tw/\" target=\"_blank\"><font color=\"#FF0000\">申请头像</font></a>， 同时注意在用户信息中您的邮箱必须与myicons的申请邮箱相同，这样在评论与留言的地方就可以显示你的个性化头像了。</td> \n";
	$string .= "  </tr> \n";
	$string .= "</table> \n";

	return $string;
}

// save setting
function MyIcon_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	setPlugSet($modId,"myIconsDefault",encode($arr["myIconsDefault"]));
	setPlugSet($modId,"myIconsSize",intval($arr["myIconsSize"]));
}
?>
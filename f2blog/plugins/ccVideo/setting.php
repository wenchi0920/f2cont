<?php
//编辑器接口
function editor_ccVideo(){
	include_once(F2BLOG_ROOT."./cache/cache_modulesSetting.php");
	
	if (!empty($plugins_ccVideo['ccuser']) && is_numeric($plugins_ccVideo['ccuser'])){
		echo <<<HTML
			<span style="padding-right:5px;padding-bottom:5px;"><script type='text/javascript' src='http://union.bokecc.com/ccplugin.bo?userID={$plugins_ccVideo['ccuser']}&type=f2blog'></script></span>
HTML;
	}else{
		echo "CC视频联盟设定不正确";
	}
}

add_filter('f2_ccVideo','editor_ccVideo');

//后台设定
function ccVideo_setCode($arr) {
	$string = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" style=\"margin:6px\" width=\"100%\"> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">您在CC视频联盟的用户ID</td> \n";
	$string .= "    <td class=\"whitefont\"> <input name=\"ccuser\" class=\"textbox\" type=\"input\" size=\"20\" value=\"".$arr['ccuser']."\"/></td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">排序方式</td> \n";
	$string .= "    <td class=\"whitefont\"> <input type=\"radio\" name=\"ccorder\" value=\"1\"".(($arr['ccorder']!=2)?" checked":"")."> 依时间 &nbsp;<input type=\"radio\" name=\"ccorder\" value=\"2\"".(($arr['ccorder']==2)?" checked":"")."> 依点击数</td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr class=\"subcontent-title\"> \n";
	$string .= "    <td class=\"input-titleblue\" align=\"right\" width=\"30%\">播放方式</td> \n";
	$string .= "    <td class=\"whitefont\"> <input type=\"radio\" name=\"ccstatus\" value=\"0\"".(($arr['ccstatus']==0)?" checked":"")."> 开启才播放CC &nbsp;<input type=\"radio\" name=\"ccstatus\" value=\"1\"".(($arr['ccstatus']>0)?" checked":"")."> 显示CC播放窗口 </td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr> \n";
	$string .= "    <td align=\"right\" width=\"30%\"><br />使用说明：</td> \n";
	$string .= "    <td class=\"whitefont\"><br />首先，先确认已经在 <a href=\"http://union.bokecc.com/\" target=\"_blank\"><font color=\"#FF0000\">CC视频联盟</font></a> 注册了用户并且已经获得了 UserID . 如果没有请先到<a href=\"http://union.bokecc.com/signup.bo?type=f2blog\" target=\"_blank\"><font color=\"#FF0000\"> CC视频联盟进行注册 </a></font></td> \n";
	$string .= "  </tr> \n";
	$string .= "  <tr> \n";
	$string .= "    <td align=\"right\" width=\"30%\">&nbsp;</td> \n";
	$string .= "    <td class=\"whitefont\"><br />其次，在上面输入框中输入您在CC视频联盟的用户ID，然后保存，这样在新增日志的时候就可以使用CC视频了。</td> \n";
	$string .= "  </tr> \n";
	$string .= "</table> \n";

	return $string;
}

// save setting
function ccVideo_setSave($arr,$modId) {
	global $DMC, $DBPrefix;
	
	setPlugSet($modId,"ccuser",$arr["ccuser"]);
	setPlugSet($modId,"ccorder",$arr["ccorder"]);
	setPlugSet($modId,"ccstatus",$arr["ccstatus"]);
}
?>
<?php 
# 禁止直接访问该页面
if (!defined('IN_F2BLOG')) die ('Access Denied.');

if ($settingInfo['language']=="zh_cn"){
	$editor_language_font="字体";
	$editor_language_color="颜色";
	$editor_language_fontsize="字号";
	$editor_language_bold="粗体";
	$editor_language_italicize="斜体";
	$editor_language_underline="下划线";
	$editor_language_center="居中";;
	$editor_language_rming="插入rm电影文件";
	$editor_language_wmv="插入wmv电影文件";
	$editor_language_image="插入图片";
	$editor_language_setswf="插入 Flash 动画";
	$editor_language_showurl="插入url链接";
	$editor_language_showcode="插入代码";
	$editor_language_quoteme="插入引用";
	$editor_language_list="插入列表";
	$editor_language_setfly="飞行字";
	$editor_language_movesign="移动字";
	$editor_language_glow="发光字";
	$editor_language_hide="隐藏日志内容";
	$editor_language_more="截取日志";
	$editor_language_nextpage="分页显示";
}else if ($settingInfo['language']=="zh_tw"){
	$editor_language_font="字體";
	$editor_language_color="顏色";
	$editor_language_fontsize="字號";
	$editor_language_bold="粗體";
	$editor_language_italicize="斜體";
	$editor_language_underline="下劃線";
	$editor_language_center="居中";;
	$editor_language_rming="插入rm電影文件";
	$editor_language_wmv="插入wmv電影文件";
	$editor_language_image="插入圖片";
	$editor_language_setswf="插入 Flash 動畫";
	$editor_language_showurl="插入url鏈接";
	$editor_language_showcode="插入代碼";
	$editor_language_quoteme="插入引用";
	$editor_language_list="插入列表";
	$editor_language_setfly="飛行字";
	$editor_language_movesign="移動字";
	$editor_language_glow="發光字";
	$editor_language_hide="隱藏日誌內容";
	$editor_language_more="截取日誌";
	$editor_language_nextpage="分頁顯示";
}else{
	$editor_language_font="font";
	$editor_language_color="color";
	$editor_language_fontsize="font size";
	$editor_language_bold="bold";
	$editor_language_italicize="italicize";
	$editor_language_underline="underline";
	$editor_language_center="center";;
	$editor_language_rming="insert rming file";
	$editor_language_wmv="insert wmv file";
	$editor_language_image="insert image";
	$editor_language_setswf="insert flash";
	$editor_language_showurl="insert url";
	$editor_language_showcode="insert code";
	$editor_language_quoteme="insert quote";
	$editor_language_list="insert list";
	$editor_language_setfly="insert fly";
	$editor_language_movesign="insert move sign";
	$editor_language_glow="insert glow";
	$editor_language_hide="Hide";
	$editor_language_more="More";
	$editor_language_nextpage="Page";
}

$ubb_path="../editor/wind";
?>
<SCRIPT language=JavaScript src="<?php echo $ubb_path?>/wind_editor.js"></SCRIPT>
<br>
<table cellPadding=0 cellSpacing=0 width="100%">
<tr><td id='edit' width='100%'>
<select onChange="showfont(this.options[this.selectedIndex].value);this.selectedIndex=0;this.selectedIndex=0" name=font>
<option value=''>- <?php echo $editor_language_font?> -</option>
<option value='Arial'>Arial</option>
<option value='Georgia'>Georgia</option>
<option value='Impact'>Impact</option>
<option value='Tahoma'>Tahoma</option>
<option value='Verdana'>Verdana</option>
<option value='楷体_GB2312'>楷体_GB2312</option>
</select>
<select onChange="showsize(this.options[this.selectedIndex].value);this.selectedIndex=0" name=size>
<option value=''>- <?php echo $editor_language_fontsize?> -</option>
<option value=1>1</option><option value=2>2</option><option value=3>3</option>
<option value=4>4</option><option value=5>5</option><option value=6>6</option>
</select>
<select onChange="showcolor(this.options[this.selectedIndex].value);this.selectedIndex=0" name=color>
<option value=''>- <?php echo $editor_language_color?> -</option>
<option value='skyblue' style='background-color:skyblue;color:skyblue'>&nbsp; &nbsp; &nbsp;  &nbsp;</option>
<option value='royalblue' style='background-color:royalblue;color:royalblue'></option>
<option value='blue' style='background-color:blue'></option>
<option value='darkblue' style='background-color:darkblue'></option>
<option value='orange' style='background-color:orange'></option>
<option value='orangered' style='background-color:orangered'></option>
<option value='crimson' style='background-color:crimson'></option>
<option value='red' style='background-color:red'></option>
<option value='firebrick' style='background-color:firebrick'></option>
<option value='darkred' style='background-color:darkred'></option>
<option value='green' style='background-color:green'></option>
<option value='limegreen' style='background-color:limegreen'></option>
<option value='seagreen' style='background-color:seagreen'></option>
<option value='teal' style='background-color:teal'></option>
<option value='deeppink' style='background-color:deeppink'></option>
<option value='tomato' style='background-color:tomato'></option>
<option value='coral' style='background-color:coral'></option>
<option value='purple' style='background-color:purple'></option>
<option value='indigo' style='background-color:indigo'></option>
<option value='burlywood' style='background-color:burlywood'></option>
<option value='sandybrown' style='background-color:sandybrown'></option>
<option value='sienna' style='background-color:sienna'></option>
<option value='chocolate' style='background-color:chocolate'></option>
<option value='silver' style='background-color:silver'></option></select>

<img onClick=bold() alt='<?php echo $editor_language_bold?>' src='<?php echo "$ubb_path/images/"?>bold.gif' align="absMiddle">
<img onClick=italicize() alt='<?php echo $editor_language_italicize?>' src='<?php echo "$ubb_path/images/"?>italic.gif' align="absMiddle">
<img onClick=underline() alt='<?php echo $editor_language_underline?>' src='<?php echo "$ubb_path/images/"?>underline.gif' align="absMiddle">
<img onClick=center() alt='<?php echo $editor_language_center?>' src='<?php echo "$ubb_path/images/"?>center.gif' align="absMiddle">
<img onClick=msc() alt='<?php echo $editor_language_music?>' src='<?php echo "$ubb_path/images/"?>msc.gif' align="absMiddle">
<img onClick=image() alt='<?php echo $editor_language_image?>' src='<?php echo "$ubb_path/images/"?>image.gif' align="absMiddle">
<img onClick=setswf() alt='<?php echo $editor_language_setswf?>' src='<?php echo "$ubb_path/images/"?>swf.gif' align="absMiddle">
<img onClick=showurl() alt='<?php echo $editor_language_showurl?>' src='<?php echo "$ubb_path/images/"?>url.gif' align="absMiddle">
<img onClick=showcode() alt='<?php echo $editor_language_showcode?>' src='<?php echo "$ubb_path/images/"?>code.gif' align="absMiddle">
<img onClick=quoteme() alt='<?php echo $editor_language_quoteme?>' src='<?php echo "$ubb_path/images/"?>quote.gif' align="absMiddle">
<img onClick=list() alt='<?php echo $editor_language_list?>' src='<?php echo "$ubb_path/images/"?>list_2.gif' align="absMiddle">
<img onClick=hide() alt='<?php echo $editor_language_hide?>' src='<?php echo "$ubb_path/images/"?>hide.gif' align="absMiddle">
<img onClick=more() alt='<?php echo $editor_language_more?>' src='<?php echo "$ubb_path/images/"?>more.gif' align="absMiddle">
<img onClick=nextpage() alt='<?php echo $editor_language_nextpage?>' src='<?php echo "$ubb_path/images/"?>page.gif' align="absMiddle">
</td></tr></table>
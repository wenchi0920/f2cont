<?php
/*
Plugin Name: eMule
Version: 1.1
Plugin URI: http://www.joesen.com/read-566.html
Author: Joesen & yeyezai
Author URI: http://joesen.f2blog.com
Description: eMule[电驴]链接的发布
*/

// Install Plugin
function eMule_install() {
	$arrPlugin['Name']="eMule";  //Plugin name
	$arrPlugin['Desc']="将eMule[电驴]链接的发布到博客文章中来";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function eMule_unstall() {
	$ActionMessage=unstall_plugins("eMule");
	return $ActionMessage;
}

function eMule($Text) {
	$Text = preg_replace("/\[emule\](.+?)\[\/emule\]/eis","emu('\\1')", $Text);

	return $Text;
}

function emu($code) {
	$view="";
	$code=dencode($code);
	$searcharray=explode("<br />", $code);
	$view = '';
	$temp = $total = 0;
	$view = "<div class=\"emuletop\">eMule资源</div><div class=\"emulemain\" align=\"center\"><table border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\"><tr><td colspan=\"2\" align=\"center\"><a href=\"http://www.emule.org.cn/download/\" target=\"_blank\">下面是用户共享的文件列表，安装eMule后，您可以点击这些文件名进行下载</a></td></tr>";
	foreach($searcharray as $emule) {
		$emule=trim($emule);
		if($emule!='' && strpos(";$emule","ed2k://")==1){
			$temp++;
			$emule = trim($emule);
			$emule_array = explode("|",$emule);
			$total += $emule_array[3];
			$totalper = esizecount($emule_array[3]);
			if($temp%2!=0){
				$view.="<tr><td align=\"left\" class=\"post2\"><input type=\"checkbox\" class=\"forminput\" name=\"em$codecount\" value=\"$emule\" onclick=\"em_size('em$codecount');\" checked=\"checked\" /> <a href=\"$emule\"><script language=\"javascript\">document.write(unescape(decodeURIComponent(\"$emule_array[2]\")));</script></a></td><td align=\"center\" class=\"post2\">$totalper</td></tr>";
			}else{
				$view.="<tr><td align=\"left\"><input type=\"checkbox\" class=\"forminput\" name=\"em$codecount\" value=\"$emule\" onclick=\"em_size('em$codecount');\" checked=\"checked\" /> <a href=\"$emule\"><script language=\"javascript\">document.write(unescape(decodeURIComponent(\"$emule_array[2]\")));</script></a></td><td align=\"center\">$totalper</td></tr>";
			}
		}
	}

	$total=esizecount($total);
	if($temp>0 && $temp!=1){
		$view.="<tr><td align=\"left\"class=\"post2\"><input type=\"checkbox\" id=\"checkall_em$codecount\" class=\"forminput\"  onclick=\"echeckAll('em$codecount',this.checked)\" checked /> <label for=\"checkall_em$codecount\">全选</label> <input type=\"button\" value=\"下载选中的文件\" class=\"button\" onclick=\"download('em$codecount',0,1)\" /> <input type=\"button\" value=\"复制选中的链接\" class=\"button\" onclick=\"copy('em$codecount')\" /><div id=\"ed2kcopy_em$codecount\" style=\"position:absolute;height:0px;width:0px;overflow:hidden;\"></div></td><td align=\"center\" id=\"size_em$codecount\"class=\"post2\">$total</td></tr>";
	}
	$return = "$view</table></div>";
	return $return;
}

function esizecount($filesize) {
	if($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' G';
	} elseif($filesize >= 1048576) {
			$filesize = round($filesize / 1048576 * 100) / 100 . ' M';
	} elseif($filesize >= 1024) {
			$filesize = round($filesize / 1024 * 100) / 100 . ' K';
	} else {
			$filesize = $filesize . ' bytes';
	}
	return $filesize;
}

function eMule_js() {
    echo "<script type=\"text/javascript\" src=\"./plugins/eMule/eMule.js\"></script>\n";
    echo "<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"./plugins/eMule/eMule.css\" type=\"text/css\">\n";
}

add_action('f2_head', 'eMule_js');
add_filter('f2_content', 'eMule', 2);
?>

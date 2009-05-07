<?php

$watermark_fieldCheck=array("wm_position","wm_text","wm_font","wm_color","wm_transparence");

//setting HtmlCode
function watermark_setCode($arr) {
	global $watermark_fieldCheck;

	$chk=@implode(",",$watermark_fieldCheck);
	$class[0]=(strpos(",".$chk,"wm_position")>0)?"input-titleblue":"";
	$class[1]=(strpos(",".$chk,"wm_image")>0)?"input-titleblue":"";
	$class[2]=(strpos(",".$chk,"wm_text")>0)?"input-titleblue":"";
	$class[3]=(strpos(",".$chk,"wm_font")>0)?"input-titleblue":"";
	$class[4]=(strpos(",".$chk,"wm_color")>0)?"input-titleblue":"";
	$class[5]=(strpos(",".$chk,"wm_transparence")>0)?"input-titleblue":"";
	$class[6]=(strpos(",".$chk,"wm_width")>0)?"input-titleblue":"";
	$class[7]=(strpos(",".$chk,"wm_height")>0)?"input-titleblue":"";

	$wm_position=$arr[wm_position];
	$wm_position_0=($wm_position=="0")?"selected":"";
	$wm_position_1=($wm_position=="1")?"selected":"";
	$wm_position_2=($wm_position=="2")?"selected":"";
	$wm_position_3=($wm_position=="3")?"selected":"";
	$wm_position_4=($wm_position=="4")?"selected":"";
	$wm_position_5=($wm_position=="5")?"selected":"";
	$wm_position_6=($wm_position=="6")?"selected":"";
	$wm_position_7=($wm_position=="7")?"selected":"";

	$path = "../plugins/watermark";
	$string = <<<HTML
   <script language=JavaScript src="$path/picker.js"></script>
   <table border="0" cellpadding="2" cellspacing="1" style="margin:6px">
          <tr>
            <td class="$class[0]" align="right">水印位置:</td>
            <td colspan=2><select name="wm_position" class="textbox">
				<option value="0" $wm_position_0>随机位置</option>
                <option value="1" $wm_position_1>顶部居左</option>
                <option value="2" $wm_position_2>顶部居中</option>
                <option value="3" $wm_position_3>顶部居右</option>
                <option value="4" $wm_position_4>底部居左</option>
                <option value="5" $wm_position_5>底部居中</option>
                <option value="6" $wm_position_6>底部居右</option>
                <option value="7" $wm_position_7>中心位置</option>
              </select></td>
          </tr>
          <tr>
            <td class="$class[1]" align="right" width="200">水印图片存放路径:</td>
            <td width="300"><input type="text" name="wm_image" size="45" class="textbox" value="$arr[wm_image]">
			<br>如果水印图片不存在，则使用文字水印.
			</td>
			<td>&nbsp;<img src="../$arr[1]"></td>
          </tr>
          <tr>
            <td class="$class[2]" align="right">水印图片文字:</td>
            <td colspan=2><input type="text" name="wm_text" size="35" class="textbox" value="$arr[wm_text]"></td>
          </tr>
          <tr>
            <td class="$class[3]" align="right">水印图片文字字体大小:</td>
            <td colspan=2><input type="text" name="wm_font" size="35" class="textbox" value="$arr[wm_font]"></td>
          </tr>
          <tr>
            <td class="$class[4]" align="right">水印图片文字颜色:</td>
            <td colspan=2><input type="text" name="wm_color" size="35" class="textbox" value="$arr[wm_color]"></td>
          </tr>
          <tr>
            <td class="$class[5]" align="right">水印透明度:</td>
            <td colspan=2><input type="text" name="wm_transparence" size="5" class="textbox" value="$arr[wm_transparence]">  范围为 1~100 的整数,数值越大水印图片透明度越低.</td>
          </tr>
          <tr>
            <td class="$class[6]" align="right">添加水印的图片大小控制:</td>
            <td colspan=2>
			宽： <input type=text size="5" class="textbox" name="wm_width"  value="$arr[wm_width]">
			高：<input type=text size="5" class="textbox" name="wm_height"  value="$arr[wm_height]"> 只对超过程序设置的大小的附件图片才加上水印图片或文字(设置为0不限制)</td>
          </tr>
    </table>
HTML;

	return $string;
}

//Retun check field list
function watermark_fieldCheck() {
	global $watermark_fieldCheck;
	$arr=$watermark_fieldCheck;
	return $arr;
}

// save setting
function watermark_setSave($arr,$modId) {
	global $DMC, $DBPrefix;

	$fieldList=array("wm_position","wm_image","wm_text","wm_font","wm_color","wm_transparence","wm_width","wm_height");
	for($i=0;$i<count($fieldList);$i++) {
		$name=$fieldList[$i];
		setPlugSet($modId,$name,$arr[$name]);
	}
	
	//Check file visit access
	$configFile="../plugins/watermark/watermark_config.php";
	$os=strtoupper(substr(PHP_OS, 0, 3));
	$fileAccess=intval(substr(sprintf('%o', fileperms($configFile)), -4));
	if ($fileAccess<777 and $os!="WIN") {
		$ActionMessage="<b><font color='red'>watermark_config.php => Please change the CHMOD as 777.</font></b>";
	} else {
		//Write Config
		$fp = @fopen($configFile, 'r');
		$filecontent = @fread($fp, @filesize($configFile));
		@fclose($fp);

		$filecontent = preg_replace("/[$]wm_width\s*\=\s*[\"'].*?[\"']/is", "\$wm_width = \"".$arr['wm_width']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_height\s*\=\s*[\"'].*?[\"']/is", "\$wm_height = \"".$arr['wm_height']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_position\s*\=\s*[\"'].*?[\"']/is", "\$wm_position = \"".$arr['wm_position']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_image\s*\=\s*[\"'].*?[\"']/is", "\$wm_image = \"".$arr['wm_image']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_text\s*\=\s*[\"'].*?[\"']/is", "\$wm_text = \"".$arr['wm_text']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_font\s*\=\s*[\"'].*?[\"']/is", "\$wm_font = \"".$arr['wm_font']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_color\s*\=\s*[\"'].*?[\"']/is", "\$wm_color = \"".$arr['wm_color']."\"", $filecontent);
		$filecontent = preg_replace("/[$]wm_transparence\s*\=\s*[\"'].*?[\"']/is", "\$wm_transparence = \"".$arr['wm_transparence']."\"", $filecontent);

		$fp = @fopen($configFile, 'w');
		@fwrite($fp, trim($filecontent));
		@fclose($fp);

		$ActionMessage="";
	}
	
	return $ActionMessage;
}

// 创建水印
function watermark($source){
	include("../plugins/watermark/watermark_config.php");
	
    if(!empty($source) && file_exists($source)){
        $source_info = getimagesize($source);
        $source_w    = $source_info[0];
        $source_h    = $source_info[1];
		
		if ($source_w>=$wm_width and $source_h>=$wm_height) { // 图片宽，高要大于设置的宽高
			switch($source_info[2]){
				case 1 :
					$source_img = imagecreatefromgif($source);
					break;
				case 2 :
					$source_img = imagecreatefromjpeg($source);
					break;
				case 3 :
					$source_img = imagecreatefrompng($source);
					break;
				default :
					return;
			}
		} else {
			return;
		}
    }else{
        return;
    }

	$wm_image="../".$wm_image;
    if(file_exists($wm_image) && $wm_image!="../"){
        $ifWaterImage = 1;
        $water_info   = getimagesize($wm_image);
        $width        = $water_info[0];
        $height       = $water_info[1];
        switch($water_info[2]){
            case 1 :
				$water_img = imagecreatefromgif($wm_image);
				break;
            case 2 :
				$water_img = imagecreatefromjpeg($wm_image);
				break;
            case 3 :
				$water_img = imagecreatefrompng($wm_image);
				break;
            default :
				return;
        }
    }else{
		$ifWaterImage = 0;
        $temp = imagettfbbox(ceil($wm_font*2.5),0,"./cour.ttf",$wm_text);//取得使用 TrueType 字体的文本的范围
        $width = $temp[2] - $temp[6];
        $height = $temp[3] - $temp[7];
        unset($temp);
    }
    switch($wm_position){
        case 0:
            $wX = rand(0,($source_w - $width));
            $wY = rand(0,($source_h - $height));
            break;
        case 1:
            $wX = 5;
            $wY = 5;
            break;
        case 2:
            $wX = ($source_w - $width) / 2;
            $wY = 0;
            break;
        case 3:
            $wX = $source_w - $width;
            $wY = 0;
            break;
        case 4:
            $wX = 0;
            $wY = $source_h - $height;
            break;
		case 5:
            $wX = ($source_w - $width) / 2;
            $wY = $source_h - $height;
            break;
		case 6:
			$wX = $source_w - $width;
			$wY = $source_h - $height;
			break;
        default:
			$wX = ($source_w - $width) / 2;
			$wY = ($source_h - $height) / 2;
            break;
    }
    imagealphablending($source_img, true);

    if($ifWaterImage){
		imagecopymerge($source_img, $water_img, $wX, $wY, 0, 0, $width,$height,$wm_transparence);
    }else{
        if(!empty($wm_color) && (strlen($wm_color)==7)){
            $R = hexdec(substr($wm_color,1,2));
            $G = hexdec(substr($wm_color,3,2));
            $B = hexdec(substr($wm_color,5));
        }else{
            return;
        }
        imagestring($source_img,$wm_font,$wX,$wY,$wm_text,imagecolorallocate($source_img,$R,$G,$B));
    }

	//strpos($source,'..')!==false && exit('Forbidden');
	@unlink($source);
    switch($source_info[2]){
        case 1 :
			imagegif($source_img,$source);
			break;
        case 2 :
			imagejpeg($source_img,$source);
			break;
        case 3 :
			imagepng($source_img,$source);
			break;
        default :
			return;
    }

    if(isset($water_info)){
		unset($water_info);
	}
    if(isset($water_img)){
		imagedestroy($water_img);
	}
    unset($source_info);
    imagedestroy($source_img);
}

add_filter("f2_attach",'watermark');

?>
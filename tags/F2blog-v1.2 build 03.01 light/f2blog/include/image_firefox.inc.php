<?php 
//生成验证码图片
if (function_exists("Imagepng")){
	$type = 'png';
}else if (function_exists("Imagejpeg")){
	$type = 'jpeg';
}else{
	$type = 'gif';
}

define('F2BLOG_ROOT', substr(dirname(__FILE__), 0, -7));
//require_once("common.php");
include("global.inc.php");
if ($sessionpath!="") session_save_path($sessionpath);
session_start();
session_cache_limiter("private, must-revalidate");
header("Content-type: image/".$type);
srand((double)microtime()*1000000);
$str=numstr(5);
$_SESSION['backValidate']=$str;

$width= 62;
$height= 20;
$im = imagecreate($width,$height); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200); 

imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $gray );//背景位置
imagecolortransparent($im,$gray);
imagestring($im, 5, 9, 3, $str, $black);

$ImageFun='Image'.$type;
$ImageFun($im);
ImageDestroy($im); 

//生成随机数 
function mystr($length) { 
    $str = ''; 
    for ($i = 0; $i < $length; $i++) 
    { 
        $rand = mt_rand(1,35); 
        if ($rand < 10) 
            $str .= $rand; 
        else 
			if (chr($rand + 87)!="o") $str .= chr($rand + 87); 
    } 
    return $str; 
} 

//生成随机数 
function numstr($length) { 
    $str = ''; 
    for ($i = 0; $i < $length; $i++) 
    { 
        $rand = mt_rand(0,9); 
		$str .= $rand; 
    } 
    return $str; 
} 
?>
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
$arr=numstr();
$_SESSION['backValidate']=$arr[1];

$width= 72;
$height= 20;
$im = imagecreate($width,$height); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200);
$colorset[]=""; //Rainbow Color Set
$colorset[0]=ImageColorAllocate($im, 255,0,0);//red
$colorset[1]=ImageColorAllocate($im, 255,204,51);//orange
$colorset[2]=ImageColorAllocate($im, 100,100,255);//blue
$colorset[3]=ImageColorAllocate($im, 186,85,211); //purple

imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $gray );//背景位置
imagecolortransparent($im,$gray);

imagestring($im, 5, 6, 2, $arr[0], $colorset[mt_rand(0,3)]);

$ImageFun='Image'.$type;
$ImageFun($im);
ImageDestroy($im); 

//生成随机数 
function numstr() { 
    $arr[] = ""; 
    $rand1 = mt_rand(1,9);
    $rand2 = mt_rand(3,11);
	$rStyle= mt_rand(0,1);
	
	if ($rStyle==0) {
		$arr[0]=$rand1."+".$rand2."=?";
		$arr[1]=$rand1+$rand2;
	} else {
		$arr[0]=$rand1."x".$rand2."=?";
		$arr[1]=$rand1*$rand2;
	}

	return $arr; 
} 
?>
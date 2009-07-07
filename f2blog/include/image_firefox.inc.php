<?
//生成验证码图片 
Header("Content-type: image/PNG"); 
//srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用

require_once("common.php");

//session_start();//将随机数存入session中
$str=strtoupper(numstr(5));
$_SESSION['validate']=$str;

$im = imagecreate(62,20); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200); 

imagefill($im,0,0,$gray); //采用区域填充法，设定（0,0）
imagecolortransparent($im,$gray);
imagestring($im, 5, 9, 3, $str, $black);
// 用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。
//如果 font 是 1，2，3，4 或 5，则使用内置字体

/*for($i=0;$i<10;$i++) //加入干扰象素 
{ 
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand()%50 , rand()%30 , $randcolor); 
} */


ImagePNG($im); 
ImageDestroy($im); 

//生成随机数 
function mystr($length) { 
    $str = ''; 
    for ($i = 0; $i < $length; $i++) 
    { 
        $rand = rand(1,35); 
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
        $rand = rand(0,9); 
		$str .= $rand; 
    } 
    return $str; 
} 
?>
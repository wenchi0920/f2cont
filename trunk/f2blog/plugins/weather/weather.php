<?php
/*
Plugin Name: weather
Plugin URI: http://korsen.f2blog.com
Description: 天气预报
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function weather_install() {
	$arrPlugin['Name']="weather";
	$arrPlugin['Desc']="天气预报";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']=<<<HTML
		<IFRAME ID='ifm2' WIDTH='175' HEIGHT='190' ALIGN='CENTER' MARGINWIDTH='0' MARGINHEIGHT='0' HSPACE='0' VSPACE='0' FRAMEBORDER='0' SCROLLING='NO' SRC='http://weather.news.qq.com/inc/ss272.htm'></IFRAME>	
HTML;
	$arrPlugin['Path']="";

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function weather_unstall() {
	$ActionMessage=unstall_plugins("weather");
	return $ActionMessage;
}

#天气预报
function weather_report($sidename,$sidetitle,$htmlcode,$isInstall){
	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--天气预报-->
<div id="Side_WeatherReport" class="sidepanel">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
  	<?php
	if ($htmlcode!=""){
		echo dencode($htmlcode);
	}else{
	?>
		<IFRAME ID='ifm2' WIDTH='175' HEIGHT='190' ALIGN='CENTER' MARGINWIDTH='0' MARGINHEIGHT='0' HSPACE='0' VSPACE='0' FRAMEBORDER='0' SCROLLING='NO' SRC='http://weather.news.qq.com/inc/ss272.htm'></IFRAME>
	<?php }?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
} #END欢迎介面

add_action("weather",'weather_report',4);
?>
<?php
/*
Plugin Name: birth
Plugin URI: http://joesen.f2blog.com/read-463.html
Description: 计时小孩到今天为止岁数
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function birth_install() {
	$arrPlugin['Name']="birth";
	$arrPlugin['Desc']="计时小孩到今天为止岁数";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array(
		"birthTitle1","birthDate1","textColor1"
	); //Default Filed
	$arrPlugin['DefaultValue']=array(
		"测试","2001-10-20","#CC3333"
	); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function birth_unstall() {
	$ActionMessage=unstall_plugins("birth");
	return $ActionMessage;
}

function birth($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;

	include_once("include/xmlparse.inc.php");
	$xmlArray=xmlArray("plugins/birth/birth.xml");
	foreach ($xmlArray['item'] as $value){
		$arrbirthTitle[]=$value['birthTitle'];
		$arrbirthDate[]=$value['birthDate'];
		$arrtextColor[]=$value['textColor'];
	}

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_birth">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?php foreach($arrbirthTitle as $key=>$value) { 
		$timestamp=$arrbirthDate[$key]." 00:00:00";
		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}/i", $timestamp)) {
			list($date,$time)=explode(" ",$timestamp);
			list($year,$month,$day)=explode("-",$date);
			list($hour,$minute,$second)=explode(":",$time);
			$timestamp=gmmktime($hour,$minute,$second,$month,$day,$year);
			
			if(PHP_VERSION>4){
				$offset = $settingInfo['timezone'];
				$timestamp=$timestamp-$offset*3600;
			}
		}

		$byear=format_time("Y",$timestamp);
		$bmonth=format_time("n",$timestamp);
		$bday=format_time("j",$timestamp);
		$textColor=$arrtextColor[$key];
	?>
		<SCRIPT type="text/javascript">
			var rstr="";
			rstr=show_date("<?php echo $value?>",<?php echo $byear?>,<?php echo $bmonth?>,<?php echo $bday?>,"<?php echo $textColor?>");
			document.write(rstr); 
		</SCRIPT>
	<?php } ?>
	</div>
  <div class="Pfoot"></div>
</div>
<?php
}

function add_jskk() {
    echo "<script type=\"text/javascript\" src=\"plugins/birth/birth.js\"></script>\n";
}

add_action('f2_head','add_jskk');
add_filter("birth",'birth',4);
?>
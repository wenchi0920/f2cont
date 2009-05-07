<?php
/*
Plugin Name: TimeCounter
Plugin URI: http://joesen.f2blog.com/read-461.html
Description: 时间倒计时插件
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function TimeCounter_install() {
	$arrPlugin['Name']="TimeCounter";
	$arrPlugin['Desc']="倒计时";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array(
		"CounterTitle1","TargetDate1","TargetTime1","CounterStyle1",
		"CounterTitle2","TargetDate2","TargetTime2","CounterStyle2",
		"CounterTitle3","TargetDate3","TargetTime3","CounterStyle3"
	); //Default Filed
	$arrPlugin['DefaultValue']=array(
		"距农历春节还有","2007-02-18","00:00:00","border:1px solid black;margin:5px;padding:2px;width:123px",
		"","","","",
		"","","",""
	); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function TimeCounter_unstall() {
	$ActionMessage=unstall_plugins("TimeCounter");
	return $ActionMessage;
}

function TimeCounter($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;
	//解析XML文件
	include_once("include/xmlparse.inc.php");
	$xmlArray=xmlArray("plugins/TimeCounter/TimeCounter.xml");
	foreach ($xmlArray['item'] as $value){
		$arrCounterTitle[]=$value['CounterTitle'];
		$arrTargetDate[]=$value['TargetDate'];
		$arrTargetTime[]=$value['TargetTime'];
		$arrCounterStyle[]=$value['CounterStyle'];
	}

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_TimeCounter">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<? foreach($arrCounterTitle as $key=>$value) { 
		//$timestamp=str_format_time($arrTargetDate[$key]." ".$arrTargetTime[$key])-2592000;
		$timestamp=$arrTargetDate[$key]." ".$arrTargetTime[$key];
		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}/i", $timestamp)) {
			list($date,$time)=explode(" ",$timestamp);
			list($year,$month,$day)=explode("-",$date);
			list($hour,$minute,$second)=explode(":",$time);
			$timestamp=gmmktime($hour,$minute,$second,$month-1,$day,$year);
			
			if(PHP_VERSION>4){
				$offset = $settingInfo['timezone'];
				$timestamp=$timestamp-$offset*3600;
			}
		}
		$counter=format_time("Y,m,d,H,i,s",$timestamp);
	?>
		<div><?php echo $value?></div>
		<div id="TimeCounter_<?php echo $key?>" style="<?php echo $arrCounterStyle[$key]?>"></div>
		<SCRIPT type="text/javascript">
		<!--
		function show_date_time_<?php echo $key?>(){
			window.setTimeout("show_date_time_<?php echo $key?>()", 1000);
			target=new Date(<?php echo $counter?>);
			today=new Date();
			timeold=(target.getTime()-today.getTime());
			
			sectimeold=timeold/1000;
			secondsold=Math.floor(sectimeold);
			msPerDay=24*60*60*1000;
			e_daysold=timeold/msPerDay;
			daysold=Math.floor(e_daysold);
			e_hrsold=(e_daysold-daysold)*24;
			hrsold=Math.floor(e_hrsold);
			e_minsold=(e_hrsold-hrsold)*60;
			minsold=Math.floor((e_hrsold-hrsold)*60);
			seconds=Math.floor((e_minsold-minsold)*60);
			
			if (daysold<0) {
				document.getElementById("TimeCounter_<?php echo $key?>").innerHTML="逾期,倒计时已经失效";
			} else {
				if (daysold<10) {daysold="0"+daysold}
				if (daysold<100) {daysold="0"+daysold}
				if (hrsold<10) {hrsold="0"+hrsold}
				if (minsold<10) {minsold="0"+minsold}
				if (seconds<10) {seconds="0"+seconds}
				if (daysold<3) {
					document.getElementById("TimeCounter_<?php echo $key?>").innerHTML="<font color=red>"+daysold+"天"+hrsold+"小时"+minsold+"分"+seconds+"秒</font>";
				} else {
					document.getElementById("TimeCounter_<?php echo $key?>").innerHTML=daysold+"天"+hrsold+"小时"+minsold+"分"+seconds+"秒";
				}
			}
		}

		show_date_time_<?php echo $key?>();
		//-->
		</SCRIPT>
	<? } ?>
	</div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_filter("TimeCounter",'TimeCounter',4);
?>
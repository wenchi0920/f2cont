<?php  
if (!defined('IN_F2BLOG')) die ('Access Denied.');

include(F2BLOG_ROOT."./cache/cache_arrcalendar.php");

//当前时间
$curTime=time()+$settingInfo['timezone']*3600;
$curYear="";
$curMonth="";

//当前月份
$_GET['seekname']=empty($_GET['seekname'])?"":$_GET['seekname'];
if (strlen($_GET['seekname'])==6){
	$curYear=intval(substr($_GET['seekname'],0,4));
	$curMonth=intval(substr($_GET['seekname'],4,2));
}

if ($curYear=="" || $curMonth=="" || ($curYear<1901 or $curYear>2050) || ($curMonth<1 || $curMonth>12)){
	$curYear=gmdate("Y",$curTime);
	$curMonth=gmdate("n",$curTime);
}

//本月总天数
$curMaxDays=gmdate("t",gmmktime(0,0,0,$curMonth,1,$curYear));

//上一个月
if ($curMonth==1) {
	$prevYear=$curYear-1;
	$prevMonth=12;
}else{
	$prevYear=$curYear;
	$prevMonth=$curMonth-1;
}
//上月总天数
$prevMaxDays=gmdate("t",gmmktime(0,0,0,$prevMonth,1,$prevYear));

//下一个月
if ($curMonth==12) {
	$nextYear=$curYear+1;
	$nextMonth=1;
}else{
	$nextYear=$curYear;
	$nextMonth=$curMonth+1;
}

//每月1号星期几
$firstWeek=gmdate("w",gmmktime(0,0,0,$curMonth,1,$curYear));

//每月最后一天星期几
$lastWeek=gmdate("w",gmmktime(0,0,0,$curMonth,$curMaxDays,$curYear));

//1号前面补上月日期
$n=0;
for ($i=0;$i<$firstWeek;$i++){
	$arrCurWeek[$n]=$prevMaxDays-$firstWeek+$i+1;
	$arrDateTime[$n]="$prevYear-$prevMonth-".$arrCurWeek[$n];
	$arrLogsUrl[$n]='';
	$arrLogsTitle[$n]='';
	$arrClass[$n]=' class="otherday"';
	$n++;
}
//本月日期
for ($i=1;$i<=$curMaxDays;$i++){
	$arrCurWeek[$n]=$i;
	$arrDateTime[$n]="$curYear-$curMonth-$i";

	//检查是否存在日志
	if (!empty($calendarcache[$arrDateTime[$n]]) && $calendarcache[$arrDateTime[$n]]>0){
		$arrLogsUrl[$n]=' href="'.calendar_url($curYear,$curMonth,$i).'"';
		$arrLogsTitle[$n]=' title="'.str_replace("1",$calendarcache[$arrDateTime[$n]],$strDayLogs).'"';
		$arrClass[$n]=' class="haveD"';
	}else{
		$arrLogsUrl[$n]='';
		$arrLogsTitle[$n]='';
		$arrClass[$n]='';
	}
	//今天
	if ($arrDateTime[$n]==gmdate("Y-n-j",$curTime)) $arrClass[$n]=' class="today"';
	$n++;
}
//最后天数补下月日期
for ($i=1;$i<7-$lastWeek;$i++){
	$arrCurWeek[$n]=$i;
	$arrDateTime[$n]="$nextYear-$nextMonth-$i";
	$arrLogsUrl[$n]='';
	$arrLogsTitle[$n]='';
	$arrClass[$n]=' class="otherday"';
	$n++;
}

function frmCalendar($num){
	return (strlen($num)==1)?$num="0$num":$num;
}

//取得日历连接
function calendar_url($year,$month,$day=0){
	global $settingInfo;
	switch ($settingInfo['rewrite']){
		case 0:
			$gourl=($day>0)?"index.php?job=calendar&amp;seekname=$year".frmCalendar($month).frmCalendar($day):"index.php?job=calendar&amp;seekname=$year".frmCalendar($month);
			break;
		case 1:
			$gourl=($day>0)?"rewrite.php/calendar-$year".frmCalendar($month).frmCalendar($day):"rewrite.php/calendar-$year".frmCalendar($month);
			break;
		case 2:						
			$gourl=($day>0)?"calendar-$year".frmCalendar($month).frmCalendar($day):"calendar-$year".frmCalendar($month);
			break;
	}
	return $gourl.$settingInfo['stype'];
}
?>

<div id="Calendar_Top">
	<div><a href="<?php echo strpos(";$settingInfo[ajaxstatus];","C")>0?"Javascript:void(0)\" onclick=\"f2_ajax_calendar('f2blog_ajax.php?job=calendar&amp;seekname=".frmCalendar($prevYear).frmCalendar($prevMonth)."&amp;ajax_display=calendar')":calendar_url($prevYear,$prevMonth);?>" id="LeftB"></a></div>
	<div><a href="<?php echo strpos(";$settingInfo[ajaxstatus];","C")>0?"Javascript:void(0)\" onclick=\"f2_ajax_calendar('f2blog_ajax.php?job=calendar&amp;seekname=".frmCalendar($nextYear).frmCalendar($nextMonth)."&amp;ajax_display=calendar')":calendar_url($nextYear,$nextMonth);?>" id="RightB"></a></div>
	<?php echo "$curYear $strYear  $curMonth $strMonth"?>
</div>
<div id="Calendar_week">
	<ul class="Week_UL">
	<li><font color="#ff0000"><?php echo $arrWeek[0]?></font></li>
	<li><?php echo $arrWeek[1]?></li>
	<li><?php echo $arrWeek[2]?></li>
	<li><?php echo $arrWeek[3]?></li>
	<li><?php echo $arrWeek[4]?></li>
	<li><?php echo $arrWeek[5]?></li>
	<li><?php echo $arrWeek[6]?></li>
	</ul>
</div>
<?php for ($i=0;$i<count($arrCurWeek)/7;$i++){?>
	<div class="Calendar_Day">
		<ul class="Day_UL">
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7].$arrClass[$i*7].$arrLogsTitle[$i*7]?>><?php echo $arrCurWeek[$i*7]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+1].$arrClass[$i*7+1].$arrLogsTitle[$i*7+1]?>><?php echo $arrCurWeek[$i*7+1]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+2].$arrClass[$i*7+2].$arrLogsTitle[$i*7+2]?>><?php echo $arrCurWeek[$i*7+2]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+3].$arrClass[$i*7+3].$arrLogsTitle[$i*7+3]?>><?php echo $arrCurWeek[$i*7+3]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+4].$arrClass[$i*7+4].$arrLogsTitle[$i*7+4]?>><?php echo $arrCurWeek[$i*7+4]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+5].$arrClass[$i*7+5].$arrLogsTitle[$i*7+5]?>><?php echo $arrCurWeek[$i*7+5]?></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+6].$arrClass[$i*7+6].$arrLogsTitle[$i*7+6]?>><?php echo $arrCurWeek[$i*7+6]?></a></li>
		</ul>
	</div>
<?php }?>      

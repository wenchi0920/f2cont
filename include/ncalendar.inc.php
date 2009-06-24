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

if ($curYear=="" || $curMonth=="" || ($curYear<1901 || $curYear>2050) || ($curMonth<1 || $curMonth>12)){
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
	$arrDateTime[$n]=FormatMonth("$prevMonth-".$arrCurWeek[$n]);
	$arrLogsUrl[$n]='';
	$arrLogsTitle[$n]='';
	$arrClass[$n]=' class="otherday"';
	$n++;
}
//本月日期
for ($i=1;$i<=$curMaxDays;$i++){
	$arrCurWeek[$n]=$i;
	$arrDateTime[$n]=FormatMonth("$curMonth-$i");

	//检查是否存在日志
	if (!empty($calendarcache["$curYear-$curMonth-$i"]) && $calendarcache["$curYear-$curMonth-$i"]>0){
		$arrLogsUrl[$n]=' href="'.calendar_url($curYear,$curMonth,$i).'"';
		$arrLogsTitle[$n]=' title="'.str_replace("1",$calendarcache["$curYear-$curMonth-$i"],$strDayLogs).'"';
		$arrClass[$n]=' class="haveD"';
	}else{
		$arrLogsUrl[$n]='';
		$arrLogsTitle[$n]='';
		$arrClass[$n]='';
	}
	//今天
	if ($arrDateTime[$n]==gmdate("nd",$curTime)) $arrClass[$n]=' class="today"';
	$n++;
}
//最后天数补下月日期
for ($i=1;$i<7-$lastWeek;$i++){
	$arrCurWeek[$n]=$i;
	$arrDateTime[$n]=FormatMonth("$nextMonth-$i");
	$arrLogsUrl[$n]='';
	$arrLogsTitle[$n]='';
	$arrClass[$n]=' class="otherday"';
	$n++;
}

//以下为农历处理
#农历每月的天数
$everymonth=array(
0=>array(8,0,0,0,0,0,0,0,0,0,0,0,29,30,7,1),
1=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,8,2),
2=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,9,3),
3=>array(5,29,30,29,30,29,29,30,29,29,30,30,29,30,10,4),
4=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,1,5),
5=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,2,6),
6=>array(4,29,30,30,29,30,29,30,29,30,29,30,29,30,3,7),
7=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,8),
8=>array(0,30,29,29,30,30,29,30,29,30,30,29,30,0,5,9),
9=>array(2,29,30,29,29,30,29,30,29,30,30,30,29,30,6,10),
10=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,7,11),
11=>array(6,30,29,30,29,29,30,29,29,30,30,29,30,30,8,12),
12=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,9,1),
13=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,10,2),
14=>array(5,30,30,29,30,29,30,29,30,29,30,29,29,30,1,3),
15=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,2,4),
16=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,3,5),
17=>array(2,30,29,29,30,29,30,30,29,30,30,29,30,29,4,6),
18=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,5,7),
19=>array(7,29,30,29,29,30,29,29,30,30,29,30,30,30,6,8),
20=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,7,9),
21=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,8,10),
22=>array(5,30,29,30,30,29,29,30,29,29,30,29,30,30,9,11),
23=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,10,12),
24=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,1,1),
25=>array(4,30,29,30,29,30,30,29,30,30,29,30,29,30,2,2),
26=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,3,3),
27=>array(0,30,29,29,30,29,30,29,30,29,30,30,30,0,4,4),
28=>array(2,29,30,29,29,30,29,29,30,29,30,30,30,30,5,5),
29=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,6,6),
30=>array(6,29,30,30,29,29,30,29,29,30,29,30,30,29,7,7),
31=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,8,8),
32=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,9,9),
33=>array(5,29,30,30,29,30,30,29,30,29,30,29,29,30,10,10),
34=>array(0,29,30,29,30,30,29,30,29,30,30,29,30,0,1,11),
35=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,2,12),
36=>array(3,30,29,29,30,29,29,30,30,29,30,30,30,29,3,1),
37=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,4,2),
38=>array(7,30,30,29,29,30,29,29,30,29,30,30,29,30,5,3),
39=>array(0,30,30,29,29,30,29,29,30,29,30,29,30,0,6,4),
40=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,7,5),
41=>array(6,30,30,29,30,30,29,30,29,29,30,29,30,29,8,6),
42=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,9,7),
43=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,10,8),
44=>array(4,30,29,30,29,30,29,30,29,30,30,29,30,30,1,9),
45=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,2,10),
46=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,3,11),
47=>array(2,30,30,29,29,30,29,29,30,29,30,29,30,30,4,12),
48=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,5,1),
49=>array(7,30,29,30,30,29,30,29,29,30,29,30,29,30,6,2),
50=>array(0,29,30,30,29,30,30,29,29,30,29,30,29,0,7,3),
51=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,8,4),
52=>array(5,29,30,29,30,29,30,29,30,30,29,30,29,30,9,5),
53=>array(0,29,30,29,29,30,30,29,30,30,29,30,29,0,10,6),
54=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,1,7),
55=>array(3,29,30,29,30,29,29,30,29,30,29,30,30,30,2,8),
56=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,3,9),
57=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,29,4,10),
58=>array(0,30,30,30,29,30,29,29,30,29,30,29,30,0,5,11),
59=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,6,12),
60=>array(6,30,29,30,29,30,30,29,30,29,30,29,30,29,7,1),
61=>array(0,30,29,30,29,30,29,30,30,29,30,29,30,0,8,2),
62=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,9,3),
63=>array(4,30,29,30,29,29,30,29,30,29,30,30,30,29,10,4),
64=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,1,5),
65=>array(0,29,30,29,30,29,29,30,29,29,30,30,29,0,2,6),
66=>array(3,30,30,30,29,30,29,29,30,29,29,30,30,29,3,7),
67=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,4,8),
68=>array(7,29,30,29,30,30,29,30,29,30,29,30,29,30,5,9),
69=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,6,10),
70=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,7,11),
71=>array(5,29,30,29,29,30,29,30,29,30,30,30,29,30,8,12),
72=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,9,1),
73=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,10,2),
74=>array(4,30,30,29,30,29,29,30,29,29,30,30,29,30,1,3),
75=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,2,4),
76=>array(8,30,30,29,30,29,30,29,30,29,29,30,29,30,3,5),
77=>array(0,30,29,30,30,29,30,29,30,29,30,29,29,0,4,6),
78=>array(0,30,29,30,30,29,30,30,29,30,29,30,29,0,5,7),
79=>array(6,30,29,29,30,29,30,30,29,30,30,29,30,29,6,8),
80=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,7,9),
81=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,8,10),
82=>array(4,30,29,30,29,29,30,29,29,30,29,30,30,30,9,11),
83=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,10,12),
84=>array(10,30,29,30,30,29,29,30,29,29,30,29,30,30,1,1),
85=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,2,2),
86=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,3,3),
87=>array(6,30,29,30,29,30,30,29,30,30,29,30,29,29,4,4),
88=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,5,5),
89=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,6,6),
90=>array(5,29,30,29,29,30,29,29,30,29,30,30,30,30,7,7),
91=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,8,8),
92=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,9,9),
93=>array(3,29,30,30,29,30,29,30,29,29,30,29,30,29,10,10),
94=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,1,11),
95=>array(8,29,30,30,29,30,29,30,30,29,29,30,29,30,2,12),
96=>array(0,29,30,29,30,30,29,30,29,30,30,29,29,0,3,1),
97=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,4,2),
98=>array(5,30,29,29,30,29,29,30,30,29,30,30,29,30,5,3),
99=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,6,4),
100=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,7,5),
101=>array(4,30,30,29,30,29,30,29,29,30,29,30,29,30,8,6),
102=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,9,7),
103=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,10,8),
104=>array(2,29,30,29,30,30,29,30,29,30,29,30,29,30,1,9),
105=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,2,10),
106=>array(7,30,29,30,29,30,29,30,29,30,30,29,30,30,3,11),
107=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,4,12),
108=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,5,1),
109=>array(5,30,30,29,29,30,29,29,30,29,30,29,30,30,6,2),
110=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,7,3),
111=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,8,4),
112=>array(4,30,29,30,30,29,30,29,30,29,30,29,30,29,9,5),
113=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,10,6),
114=>array(9,29,30,29,30,29,30,29,30,30,29,30,29,30,1,7),
115=>array(0,29,30,29,29,30,29,30,30,30,29,30,29,0,2,8),
116=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,3,9),
117=>array(6,29,30,29,30,29,29,30,29,30,29,30,30,30,4,10),
118=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,5,11),
119=>array(0,30,29,30,29,30,29,29,30,29,29,30,30,0,6,12),
120=>array(4,29,30,30,30,29,30,29,29,30,29,30,29,30,7,1)
);

#阳历总天数 至1900年12月21日
$total=11;
#阴历总天数
$mtotal=0;

##############################
#计算到所求日期阳历的总天数-自1900年12月21日始
#先算年的和
for ($y=1901;$y<$curYear;$y++){
	$total=$total+365;
	if ($y%4==0) $total ++;
}
#本年天数
if ($curMonth>1){
	$total=$total+gmdate("z",gmmktime(0,0,0,$curMonth,1-$firstWeek,$curYear));
}else{
	$total=$total-$firstWeek;
}

#用农历的天数累加来判断是否超过阳历的天数
$flag1=0;#判断跳出循环的条件
$nJ=0;
while ($nJ<=120){
      $nI=1;
      while ($nI<=13){
            $mtotal=$mtotal+$everymonth[$nJ][$nI];
            if ($mtotal>=$total){
                 $flag1=1;
                 break;
            }
            $nI++;
      }
	  if ($flag1==1) break;
      $nJ++;
}
//日历第一天的农历
$md=$everymonth[$nJ][$nI]-($mtotal-$total);

//农历假日
unset($nHoliday);unset($nHolidayTitle);
$arr_Ncalendar=explode("}{","}$settingInfo[ncalendar]{");
foreach($arr_Ncalendar as $key=>$value){
	if ($value!="")	list($nHoliday[],$nHolidayTitle[])=explode(",",$value);
}

//公历假日
unset($gHoliday);unset($gHolidayTitle);
$arr_Gcalendar=explode("}{","}$settingInfo[gcalendar]{");
foreach($arr_Gcalendar as $key=>$value){
	if ($value!="")	list($gHoliday[],$gHolidayTitle[])=explode(",",$value);
}

#生成中文农历
for ($n=0;$n<count($arrCurWeek);$n++){
	 //闰月份:$everymonth[$nJ][0]
	 $smonth=($nI==$everymonth[$nJ][0]+1 && $everymonth[$nJ][0]>0)?$everymonth[$nJ][0]:"";
	 if ($md==1){  //1日打印月份
		if ($everymonth[$nJ][0]>0 && $everymonth[$nJ][0]<$nI){
			$mm=$nI-1;
		}else{
			$mm=$nI;
		}
		if ($nI==$everymonth[$nJ][0]+1 && $everymonth[$nJ][0]>0) {
			$arrNdayShow[$n]=$strArrayMonth[0].$strArrayMonth[$mm];  //月
		} else {
			$arrNdayShow[$n]=$strArrayMonth[$mm].$strArrayMonth[13];
		}
    }else{
		if ($everymonth[$nJ][0]>0 && $everymonth[$nJ][0]<$nI){
			$mm=$nI-1;
		}else{
			$mm=$nI;
		}
		$arrNdayShow[$n]=$strArrayDay[$md];
    }
	//农历颜色
	$arrNdayColor[$n]="";

	//农历假日
	$curMdate=FormatMonth($mm."-".$md);
	if (($th=array_search($curMdate,$nHoliday))>0 && $smonth=="") {
		$arrNdayShow[$n]=$nHolidayTitle[$th];
		$arrNdayColor[$n]=(substr($arrDateTime[$n],0,2)==$curMonth)?"red":"";
	}
	//公历假日
	if (($th=array_search($arrDateTime[$n],$gHoliday))>0) {
		$arrNdayShow[$n]=$gHolidayTitle[$th];
		$arrNdayColor[$n]=(substr($arrDateTime[$n],0,2)==$curMonth)?"red":"";
	}

	$md++;
	if ($md>$everymonth[$nJ][$nI]){
		$md=$md-$everymonth[$nJ][$nI];
		$nI++;
	}
	if (($nI>12 && $everymonth[$nJ][0]==0) || ($nI>13 && $everymonth[$nJ][0]>0)){
		$nI=1;
		$nJ++;
	}
}

function FormatMonth($time){
	$fmonth=intval(substr($time,0,strpos($time,"-")));
	$fday=intval(substr($time,strpos($time,"-")+1));
	if (strlen($fmonth)==1){$fmonth="0$fmonth";}
	if (strlen($fday)==1){$fday="0$fday";}
	return "$fmonth$fday";
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
	<div class="Calendar_Day" style="height:30px;">
		<ul class="Day_UL">
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7].$arrClass[$i*7].$arrLogsTitle[$i*7]?>><?php echo $arrCurWeek[$i*7]?><br /><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7]?>" title="<?php echo $arrNdayShow[$i*7]?>"><?php echo subString($arrNdayShow[$i*7],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+1].$arrClass[$i*7+1].$arrLogsTitle[$i*7+1]?>><?php echo $arrCurWeek[$i*7+1]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+1]?>" title="<?php echo $arrNdayShow[$i*7+1]?>"><br /><?php echo subString($arrNdayShow[$i*7+1],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+2].$arrClass[$i*7+2].$arrLogsTitle[$i*7+2]?>><?php echo $arrCurWeek[$i*7+2]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+2]?>" title="<?php echo $arrNdayShow[$i*7+2]?>"><br /><?php echo subString($arrNdayShow[$i*7+2],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+3].$arrClass[$i*7+3].$arrLogsTitle[$i*7+3]?>><?php echo $arrCurWeek[$i*7+3]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+3]?>" title="<?php echo $arrNdayShow[$i*7+3]?>"><br /><?php echo subString($arrNdayShow[$i*7+3],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+4].$arrClass[$i*7+4].$arrLogsTitle[$i*7+4]?>><?php echo $arrCurWeek[$i*7+4]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+4]?>" title="<?php echo $arrNdayShow[$i*7+4]?>"><br /><?php echo subString($arrNdayShow[$i*7+4],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+5].$arrClass[$i*7+5].$arrLogsTitle[$i*7+5]?>><?php echo $arrCurWeek[$i*7+5]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+5]?>" title="<?php echo $arrNdayShow[$i*7+5]?>"><br /><?php echo subString($arrNdayShow[$i*7+5],0,2)?></span></a></li>
			<li class="DayA"><a<?php echo $arrLogsUrl[$i*7+6].$arrClass[$i*7+6].$arrLogsTitle[$i*7+6]?>><?php echo $arrCurWeek[$i*7+6]?><span style="dipslay:none;font-size:9px;color:<?php echo $arrNdayColor[$i*7+6]?>" title="<?php echo $arrNdayShow[$i*7+6]?>"><br /><?php echo subString($arrNdayShow[$i*7+6],0,2)?></span></a></li>
		</ul>
	</div>
<?php }?>
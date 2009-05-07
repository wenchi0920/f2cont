function arrayOfDayInMonths(isLeapYear)
{ this[0] = 31;
  this[1] = 31;
  this[2] = 28;
  if (isLeapYear)
    this[2] = 29;
  this[3] = 31;
  this[4] = 30;
  this[5] = 31;
  this[6] = 30;
  this[7] = 31;
  this[8] = 31;
  this[9] = 30;
  this[10] = 31;
  this[11] = 30;
}

function daysInMonth(month, year){
  var isLeapYear = ((( year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0));
  var monthdays = new arrayOfDayInMonths(isLeapYear);
  return monthdays[month];
} 

function show_date(name,b_yy,b_mm,b_dd,textcolor) {
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth();
	var day = today.getDate();
	var t_yy = 0;
	var t_mm = 0;
	var t_dd = 0;
	var rstr = "";
	
	t_yy = year - b_yy - 1;

	if ( month + 1 > b_mm){ 
		t_mm = month - b_mm ;
		t_yy++;
	} else {
	  t_mm = 12 - b_mm + month;
	}

	if (day > b_dd){ 
		t_dd = day - b_dd;
		t_mm++;
	} else {
	  t_dd = daysInMonth(month, year) - b_dd + day;
	}

	if (t_dd > daysInMonth(month, year)-1){
		t_dd = 0;
		t_mm++;
	}

	if (t_mm > 11){ 
		t_mm = 0;
		t_yy++;
	}  

	rstr="<font color='"+textcolor+"'>"+name + "</font> 今天<font color='"+textcolor+"'>" + t_yy + "</font>岁"; 
	
	if (t_mm > 0)
	  rstr=rstr + "<font color='"+textcolor+"'>"+t_mm + "</font>个月";

	if (t_dd > 0)
	  rstr=rstr + "又<font color='"+textcolor+"'>" + t_dd + "</font>天<br />";

	return rstr;
}
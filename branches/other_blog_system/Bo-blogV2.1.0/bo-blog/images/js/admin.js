<!-- Move Up and Down JS from:  Bob Rockers (brockers@subdimension.com) [javascript.internet.com] -->
function move(fbox,tbox) {
var i = 0;
if(fbox.value != "") {
var no = new Option();
no.value = fbox.value;
no.text = fbox.value;
tbox.options[tbox.options.length] = no;
fbox.value = "";
   }
}
function remove(box) {
for(var i=0; i<box.options.length; i++) {
if(box.options[i].selected && box.options[i] != "") {
box.options[i].value = "";
box.options[i].text = "";
   }
}
BumpUp(box);
} 
function BumpUp(abox) {
for(var i = 0; i < abox.options.length; i++) {
if(abox.options[i].value == "")  {
for(var j = i; j < abox.options.length - 1; j++)  {
abox.options[j].value = abox.options[j + 1].value;
abox.options[j].text = abox.options[j + 1].text;
}
var ln = i;
break;
   }
}
if(ln < abox.options.length)  {
abox.options.length -= 1;
BumpUp(abox);
   }
}
function Moveup(dbox) {
for(var i = 0; i < dbox.options.length; i++) {
if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[0]) {
var tmpval = dbox.options[i].value;
var tmpval2 = dbox.options[i].text;
dbox.options[i].value = dbox.options[i - 1].value;
dbox.options[i].text = dbox.options[i - 1].text
dbox.options[i-1].value = tmpval;
dbox.options[i-1].text = tmpval2;
dbox.options[i-1].selected='selected'; //Improved by Bob
dbox.options[i].selected=''; //Improved by Bob
      }
   }
}
function Movedown(ebox) {
for(var i = 0; i < ebox.options.length; i++) {
if (ebox.options[i].selected && ebox.options[i] != "" && ebox.options[i+1] != ebox.options[ebox.options.length]) {
var tmpval = ebox.options[i].value;
var tmpval2 = ebox.options[i].text;
ebox.options[i].value = ebox.options[i+1].value;
ebox.options[i].text = ebox.options[i+1].text
ebox.options[i+1].value = tmpval;
ebox.options[i+1].text = tmpval2;
ebox.options[i+1].selected='selected'; //Improved by Bob
ebox.options[i].selected=''; //Improved by Bob
break; //Improved by Bob
      }
   }
}
<!-- End Move Up and Down JS -->

function GetOptions(ebox, urlnew) {
	var optionsout='';
	for(var i = 0; i < ebox.options.length; i++) {
		optionsout+=ebox.options[i].value+':';
	}
	var urlnews=urlnew+optionsout;
	window.location=urlnews;
}

function checkallbox(the_form, do_check) {
    var elts = (typeof(document.forms[the_form].elements['selid[]']) != 'undefined')
                  ? document.forms[the_form].elements['selid[]']
                  : (typeof(document.forms[the_form].elements['selid[]']) != 'undefined')
          ? document.forms[the_form].elements['selid[]']
          : document.forms[the_form].elements['selid[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
        } 
    } else {
        elts.checked  = do_check;
    } 
    return true;
}

function ensuredel (blogid, property) {
	if (property==1) 	{
		var urlreturn="admin.php?go=entry_deleteblog_"+blogid+'';
	} else if (property==2)  {
		var urlreturn="admin.php?go=reply_delreply_"+blogid+'';
	} else if (property==3)  {
		var urlreturn="admin.php?go=message_delreply_"+blogid+'';
	} else {
		var urlreturn="admin.php?go=entry_deletedraft_"+blogid+'';
	}
	if(confirm(jslang[16])){
		window.location=urlreturn;
	}
	else {
		return;
	}
}

function redirectcomfirm (returnurl) {
	if(confirm(jslang[5])){
		window.location=returnurl;
	}
	else {
		return;
	}
}

function inserttag (realvalue, taginputname) {
	var targetinput=document.getElementById(taginputname);
	if (targetinput && realvalue!='' && realvalue!=null) {
		if (targetinput.value=='') var newvalue=realvalue;
		else var newvalue=' '+realvalue;
		targetinput.value+=newvalue;
	}
}

function makesuredelweather(weathername) {
	var urlreturn="admin.php?go=misc_weatherdel_"+weathername+'';
	if(confirm(jslang[17])){
		window.location=urlreturn;
	}
	else {
		return;
	}
}

function timechanger() {
	if (document.getElementById('changemytime').checked) document.getElementById('changetime').style.display='block';
	else document.getElementById('changetime').style.display='none';
}

function ajax_addcategory () {
	if (shutajax==0) {
		var newcatename=blogencode(document.getElementById('newcatename').value);
		var newcatedesc=blogencode(document.getElementById('newcatedesc').value);
		var seld=document.getElementById('newcatemode');
		var newcatemode=blogencode(seld.options[seld.selectedIndex].value);
		seld=document.getElementById('newcateproperty');
		var newcateproperty=blogencode(seld.options[seld.selectedIndex].value);
		seld=document.getElementById('targetcate');
		var targetcate=blogencode(seld.options[seld.selectedIndex].value);
		var postData = "unuse=unuse&newcatename="+newcatename+"&newcatedesc="+newcatedesc+"&newcatemode="+newcatemode+"&newcateproperty="+newcateproperty+"&targetcate="+targetcate;
		var gourl="admin.php?ajax=on&go=category_new";
		makeRequest(gourl, 'quickaddcategory', 'POST', postData);
	}
}

function changeeditor() {
	if(confirm(jslang[68])){
		var oldgo=document.getElementById('oldgo').value;
		var editorbody=document.getElementById('useeditor');
		var useeditor=editorbody.options[editorbody.selectedIndex].value;
		window.location="admin.php?"+oldgo+"&useeditor="+useeditor;
	}
	else {
		return;
	}
}
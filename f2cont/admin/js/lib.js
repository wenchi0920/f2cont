function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

function ConfirmForm(theLink,confirmMsg)
{
	choise=confirm(confirmMsg);
	if (choise==true)
		{
			document.seekform.action=theLink;
			document.seekform.submit();
		}
}

function ConfirmDataOperation(theLink,confirmMsg)
{
	choise=confirm(confirmMsg);
	if (choise==true)
		{
			document.seekform.save.disabled=true;
			document.seekform.del.disabled=true;
			document.seekform.action=theLink;
			document.seekform.submit();
		}
}

function ConfirmOperation(theLink,confirmMsg)
{
	if (document.seekform.opselect.value==""){
		alert("Please select record!");
	}else if (document.seekform.opmethod.value==""){
		alert("Please select operation action!");
	}else{
		choise=confirm(confirmMsg);
		if (choise==true){
			document.seekform.action=theLink;
			document.seekform.submit();
		}
	}
}

function confirm_submit(theLink,act)
{
	document.seekform.action=theLink + "&action=" + act;
	document.seekform.submit();
}

function confirm_link(theLink,act)
{
	if (document.seekform.seekname.value==""){
		alert("Please select a Link Group!");
	} else {
		document.seekform.action=theLink + "&action=" + act;
		document.seekform.submit();
	}
}

function checkall(form,check_value,check_name)
{	
	for (var i=0;i<form.elements.length;i++)
	{
		var e=form.elements[i];
		if (e.name==check_name) {e.checked=check_value;}
	}
	form.opselect.value=1;
}

function open_content(themes,ss,tt){
	if(document.getElementById(ss).style.display=="none"){
		document.getElementById(ss).style.display="";
		tt.src="themes/"+themes+"/expand_no.gif";
	}else{
		document.getElementById(ss).style.display="none";
		tt.src="themes/"+themes+"/expand_yes.gif";
	}
}

function show_tools(ss){
	if (ss==1){
		document.getElementById("mod_content").style.display="none";
		document.getElementById("mod_sidebar1").style.display="none";
		document.getElementById("mod_sidebar2").style.display="none";
		document.getElementById("mod_sidebar5").style.display="none";
		document.getElementById("mod_top").style.display="";
		document.getElementById("mod_top2").style.display="";
	}else if (ss==2){
		document.getElementById("mod_content").style.display="none";
		document.getElementById("mod_sidebar1").style.display="";
		document.getElementById("mod_sidebar2").style.display="";
		document.getElementById("mod_sidebar5").style.display="";
		document.getElementById("mod_top").style.display="none";
		document.getElementById("mod_top2").style.display="none";
	}else{
		document.getElementById("mod_content").style.display="";
		document.getElementById("mod_sidebar1").style.display="none";
		document.getElementById("mod_sidebar2").style.display="";
		document.getElementById("mod_sidebar5").style.display="none";
		document.getElementById("mod_top").style.display="none";
		document.getElementById("mod_top2").style.display="none";
	}
}

function insert_tag (realvalue, taginputname) {  
  var targetinput;  
  if(document.getElementById){
    targetinput=document.getElementById(taginputname);
  }   
  if( targetinput == undefined && document.all.tags){
    targetinput = document.all.tags["input"][taginputname]; 
  }  
  if (targetinput && realvalue!='' && realvalue!=null) {
    if (targetinput.value=='') var newvalue=realvalue;
    else var newvalue=';'+realvalue;
    targetinput.value+=newvalue;
  }
}

function CopyText(str) {
	clipboardData.setData('text',','+str);
}

function open_setting(ss,tt,mm){
	document.getElementById("setting1").style.display=ss;
	document.getElementById("setting2").style.display=tt;
	document.getElementById("setting3").style.display=mm;
	if (ss=="" && tt=="" && mm==""){
		document.getElementById("br1").style.display="";
		document.getElementById("br2").style.display="";
	}else{
		document.getElementById("br1").style.display="none";
		document.getElementById("br2").style.display="none";
	}
}

//ÅÅÐò
var curRow=null;
function SelectRow(event){
	if (event == null){ event = window.event; }
	var clickrow = event.srcElement?event.srcElement:event.target;

	while(clickrow.tagName!="TR"){
		clickrow=event.srcElement?clickrow.parentElement:clickrow.parentNode;
	}
	
	var len=document.getElementById("tbCondition").rows.length;
	
	if(curRow) {
		curRow.style.backgroundColor ="";
		curRow.style.fontWeight ="normal";
	}
	
	clickrow.style.backgroundColor ="#FFFFCC";
	clickrow.style.fontWeight ="bold";
	curRow=clickrow;
	
	if (curRow.rowIndex>0){
		document.getElementById("moveup").disabled=false;
	} else {
		document.getElementById("moveup").disabled=true;
	}

	if (curRow.rowIndex>=0 && curRow.rowIndex<len-1){
		document.getElementById("movedown").disabled=false;
	} else {
		document.getElementById("movedown").disabled=true;
	}
}

function Move(flag,tdColumns){
	var tbCondition=document.getElementById("tbCondition");
	var chgRow=tbCondition.rows[curRow.rowIndex+flag];
	
	for(var i=0;i<tdColumns;i++){
		var strTd=curRow.cells[i].innerHTML;
		curRow.cells[i].innerHTML=chgRow.cells[i].innerHTML;
		chgRow.cells[i].innerHTML=strTd;
	}

	ie = (document.all)? true:false
	if (ie){
		chgRow.click();
	} else {
		chgRow.style.backgroundColor ="#FFFFCC";
		chgRow.style.fontWeight ="bold";
		curRow.style.backgroundColor ="";
		curRow.style.fontWeight ="normal";
		curRow=chgRow;
		
		var len=tbCondition.rows.length;
		if (curRow.rowIndex>0){
			document.getElementById("moveup").disabled=false;
		} else {
			document.getElementById("moveup").disabled=true;
		}

		if (curRow.rowIndex>=0 && curRow.rowIndex<len-1){
			document.getElementById("movedown").disabled=false;
		} else {
			document.getElementById("movedown").disabled=true;
		}
	}
}

function trim(str) {
	return str.replace(/^\s*(.*?)[\s\n]*$/g, '$1');
}

function strlen(str){
	var str=trim(str);
	return str.replace(/[^\x00-\xff]/g, "**").length;
}
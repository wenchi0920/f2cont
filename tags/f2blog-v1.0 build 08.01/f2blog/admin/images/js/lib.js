function isNull(field,message) {
	if (field.value=="") {
		alert(message + '\t');
		field.focus();
		return true;
	}
	return false;
}

//open a new window
function OpenAttachment(mywin)
{
	window.open(mywin,"popwin","width=500,height=370,left=100,top=100,scrollbars=yes,menubar=no,toolbar=no,resizable=1");
}//end of the 'openwin()'

function ConfirmAction(theLink, confirmMsg)
{
    var is_confirmed = confirm(confirmMsg);
    //if (is_confirmed) {
    //    theLink.href += '&is_confirmed=1';
    //}

    return is_confirmed;
} // end of the 'confirmLink()' function 


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
	//alert(document.seekform.opselect.value);
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

function checkall(form,check_value,check_name)
{	
	for (var i=0;i<form.elements.length;i++)
	{
		var e=form.elements[i];
		if (e.name==check_name) {e.checked=check_value;}
	}
	form.opselect.value=1;
}

function OpenImagesWin(theURL,FormName,CtrlName,features)
{
   var Name=FormName+"EINSTAND"+CtrlName;
   var remote=open(theURL,Name,features);
   remote.opener=self;
}

function OpenGameWindows(url)
{
	var sSelClient = String(window.showModalDialog(url, 0, 'dialogWidth=750px;dialogHeight=500px;scroll=yes;center=1;border=thin;help=0;status=0;edge:raised'));
}

function open_content(ss,tt){
	if(document.getElementById(ss).style.display=="none"){
		document.getElementById(ss).style.display="";
		tt.src="images/content/expand_no.gif";
	}else{
		document.getElementById(ss).style.display="none";
		tt.src="images/content/expand_yes.gif";
	}
}

function show_tools(ss){
	if (ss==1){
		document.getElementById("mod_content").style.display="none";
		document.getElementById("mod_sidebar1").style.display="none";
		document.getElementById("mod_sidebar2").style.display="none";
		document.getElementById("mod_top").style.display="";
	}else if (ss==2){
		document.getElementById("mod_content").style.display="none";
		document.getElementById("mod_sidebar1").style.display="";
		document.getElementById("mod_sidebar2").style.display="";
		document.getElementById("mod_top").style.display="none";
	}else{
		document.getElementById("mod_content").style.display="";
		document.getElementById("mod_sidebar1").style.display="none";
		document.getElementById("mod_sidebar2").style.display="";
		document.getElementById("mod_top").style.display="none";
	}
}

//打开新窗�?
function open_window(url,title,width,height){
    var w = 1024;
    var h = 768;

    if (document.all || document.layers){
        w = screen.availWidth;
        h = screen.availHeight;
    }

    var leftPos = (w/2-width/2);
    var topPos = (h/2.3-height/2.3);

    window.open(url,title,"width="+width+",height="+height+",top="+topPos+",left="+leftPos+",scrollbars=no,resizable=no,status=no")
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

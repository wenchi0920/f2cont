function sidebarTools(ss){
	var date = new Date();
	date.setTime(date.getTime()+(86400*365*1000));
	if(document.getElementById(ss).style.display=="none"){
		document.getElementById(ss).style.display="";
		setSideCookie(ss, "", date, "", "", "");
	}else{
		document.getElementById(ss).style.display="none";
		setSideCookie(ss, "none", date, "", "", "");
	}
}

/*
   name - name of the cookie
   value - value of the cookie
   [expires] - expiration date of the cookie
     (defaults to end of current session)
   [path] - path for which the cookie is valid
     (defaults to path of calling document)
   [domain] - domain for which the cookie is valid
     (defaults to domain of calling document)
   [secure] - Boolean value indicating if the cookie transmission requires
     a secure transmission
   * an argument defaults when it is assigned null as a placeholder
   * a null placeholder is not required for trailing omitted arguments
*/

function setSideCookie(name, value, expires, path, domain, secure) {
  var curCookie = name + "=" + escape(value) +
      ((expires) ? "; expires=" + expires.toGMTString() : "") +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      ((secure) ? "; secure" : "");
  document.cookie = curCookie;
}

function F2Gallery(containerId,language)
{
	this.containerId = containerId;
	this.language = language;
	this.container = document.getElementById(this.containerId);
	this.container.style.filter = "progid:DXImageTransform.Microsoft.Fade(duration=0.3, overlap=1.0)";
	this.container.style.textAlign = "center";
	this.container.style.width = "100%";
	this.container.instance = this;

	this.numImages = 0;
	this.imageLoaded = 0;
	this.offset = 0;
	this.playg = 0;
	this.playbutton = "";

	this.src = new Array();
	this.caption = new Array();
	this.width = new Array();
	this.height = new Array();
	this.imageCache = new Array();
}

F2Gallery.prototype.appendImage = function(src, caption, width, height)
{
	this.numImages++;

	var imageCache = new Image();
	imageCache.src = src;
	imageCache.onload = function() { var tmp = this.src; }
	
	this.imageCache[this.imageCache.length] = src;
	this.src[this.src.length] = src;
	this.width[this.width.length] = width;
	this.height[this.height.length] = height;
	this.caption[this.caption.length] = caption;
}

F2Gallery.prototype.getControl = function()
{
	var control = document.createElement("div");
	control.style.marginBottom = "10px";
	control.className = "galleryControl";
	control.style.color = "#777";
	control.style.font = "bold 0.9em Verdana, Sans-serif";
	control.innerHTML = '(' + (this.offset + 1) + '/' + this.numImages + ') <a href="#" onclick="document.getElementById(\'' + this.containerId + '\').instance.autoplay(1);"><img src="images/gallery/'+ this.language +'/playnext.gif" alt="Play" style="vertical-align: middle"/></a> <a href="#" onclick="document.getElementById(\'' + this.containerId + '\').instance.autoplay(0);"><img src="images/gallery/'+ this.language +'/stop.gif" alt="Stop" style="vertical-align: middle"/></a> <a href="#" onclick="document.getElementById(\'' + this.containerId + '\').instance.prev(); return false"><img src="images/gallery/'+ this.language +'/back.gif"  alt="PREVIOUS" style="vertical-align: middle"/></a> <a href="#" onclick="document.getElementById(\'' + this.containerId + '\').instance.showImagePopup1(); return false"><img src="images/gallery/'+ this.language +'/zoom.gif" alt="ZOOM" style="vertical-align: middle"/></a> <a href="#" onclick="document.getElementById(\'' + this.containerId + '\').instance.next(); return false"><img src="images/gallery/'+ this.language +'/next.gif" alt="NEXT" style="vertical-align: middle"/></a>';
	
	return control;
}

F2Gallery.prototype.getImage = function()
{
	var image = document.createElement("img");
	image.instance = this;
	image.src = this.src[this.offset];
	image.width = this.width[this.offset];
	image.height = this.height[this.offset];
	image.onclick = this.showImagePopup2;
	image.style.cursor = "pointer";
	//image.style.visibility = "hidden";
	//image.style.filter = "revealTrans(Duration=1.5,Transition=4)";

	return image;
}

F2Gallery.prototype.getCaption = function()
{
	var captionText = this.caption[this.offset];
	captionText = captionText.replace(new RegExp("&lt;?", "gi"), "<");
	captionText = captionText.replace(new RegExp("&gt;?", "gi"), ">");
	
	var caption = document.createElement("div");
	caption.style.textAlign = "center";
	caption.style.marginTop = "8px";
	caption.style.color = "#627e89";
	caption.className = "galleryCaption";
	caption.appendChild(document.createTextNode(captionText));

	return caption;
}

F2Gallery.prototype.show = function(offset)
{
	if(typeof offset == "undefined")
		this.offset = 0;
	else
	{
		if(offset < 0)
			this.offset = this.numImages -1;
		else if(offset >= this.numImages)
			this.offset = 0;
		else
			this.offset = offset;
	}

	if(this.container.filters)
		this.container.filters[0].Apply();
	
	this.container.innerHTML = "";
	this.container.appendChild(this.getControl());
	this.container.appendChild(this.getImage());
	this.container.appendChild(this.getCaption());

	if(this.container.filters)
		this.container.filters[0].Play();
}

F2Gallery.prototype.prev = function()
{
	this.show(this.offset-1);
}

F2Gallery.prototype.next = function()
{
	this.show(this.offset+1);
}

F2Gallery.prototype.autoplay = function(flag)
{
	this.playg = flag;
	//alert(this.playg+"====="+flag);
	if (flag==1)
	{
		this.play();
	}
}

F2Gallery.prototype.play = function()
{
    this.next();
    this.playtimeout();
}

F2Gallery.prototype.playtimeout = function()
{
	var members = this;
    function fun()
    {
      members.play();
    }
	
    if(this.playg==1){
		setTimeout(fun,3000);
    }
}

F2Gallery.prototype.showImagePopup1 = function()
{
	this.showImagePopup();
}

F2Gallery.prototype.showImagePopup2 = function()
{
	this.instance.showImagePopup();
}

F2Gallery.prototype.showImagePopup = function(offset)
{
	open_img(this.src[this.offset]);
}

function open_img(img_src) {

	img_view = window.open('','img_popup','width=0,height=0,left=0,top=0,scrollbars=no,resizable=yes');
	img_view.document.write(
	'<html>\n'+ 
	'<head>\n'+
	'<script language=\"javascript\">\n'+
	'function resizing(){\n'+
	'		var winWidth=document.images.imazingimg.width+100\n'+
	'		var winHeight=document.images.imazingimg.height+100\n'+		
	'		window.resizeTo(winWidth,winHeight)\n'+
	'		window.moveTo(screen.width/2-winWidth/2,screen.height/2-winHeight/2)\n'+					
	'}\n'+
	'\n'+		
	'\n'+
	'\n'+
	'<\/script>\n	'+
	'<title> :: View :: <\/title>\n'+
	'<\/head>\n'+
	'<body topmargin="0" leftmargin="0">\n'+
	
	'<table style="width: 100%; height: 100%">\n'+
	'	<tr>\n'+
	'		<td style="text-align: center" valign="middle">\n'+
	'			<a href="#" onclick="window.close()" onfocus="this.blur()">\n'+
	'				<img src="'+img_src+'" galleryimg="no" border="0" name="imazingimg" onload="resizing()">\n'+
	'			<\/a>\n'+
	'		<\/td>\n'+
	'	<\/tr>\n'+
	'<\/table>\n'+
	'<\/body>\n'+
	'<\/html>\n');

	img_view.document.close();
	try { img_view.document.focus(); }
	catch(e) { }
}

function openGuestBook(url,width,height) {
	var openWindow = '';
	openWindow = window.open(url, "f2blog", "width="+width+",height="+height+",location=0,menubar=0,resizable=0,scrollbars=0,status=0,toolbar=0");
	openWindow.focus();
	openWindow.moveTo(screen.width/2-width/2,screen.height/2-height/2);
}

function SetFont(size){
	document.getElementById("logPanel").style.fontSize=size
}

var MediaTemp=new Array()
function MediaShow(strType,strID,strURL,intWidth,intHeight,strPlay,strStop)
{
	var tmpstr = '';
	if (MediaTemp[strID]==undefined) {
		MediaTemp[strID]=false;
	} else {
		MediaTemp[strID]=!MediaTemp[strID];
	}

	if(MediaTemp[strID]){
		if ( document.all )	{
	         document.getElementById(strID).outerHTML = '<div id="'+strID+'"></div>'
		} else {
	         document.getElementById(strID).innerHTML = ''
		}

		document.images[strID+"_img"].src="images/mm_snd.gif" 		
		document.getElementById(strID+"_text").innerHTML=strPlay	
	}else{
		document.images[strID+"_img"].src="images/mm_snd_stop.gif" 		
		document.getElementById(strID+"_text").innerHTML=strStop
		switch(strType){
			case "swf":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+intWidth+'" height="'+intHeight+'"><param name="movie" value="'+strURL+'" /><param name="quality" value="high" /><param name="AllowScriptAccess" value="never" /><embed src="'+strURL+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+intWidth+'" height="'+intHeight+'" /></object>';
				break;
			case "wma":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"  id="MediaPlayer" width="450" height="70"><param name=""howStatusBar" value="-1"><param name="AutoStart" value="False"><param name="Filename" value="'+strURL+'"></object>';
				break;
			case "mp3":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"  id="MediaPlayer" width="450" height="70"><param name=""howStatusBar" value="-1"><param name="AutoStart" value="False"><param name="Filename" value="'+strURL+'"></object>';
				break;
			case "wmv":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,02,902" type="application/x-oleobject" standby="Loading..." width="'+intWidth+'" height="'+intHeight+'"><param name="FileName" VALUE="'+strURL+'" /><param name="ShowStatusBar" value="-1" /><param name="AutoStart" value="true" /><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="'+strURL+'" autostart="true" width="'+intWidth+'" height="'+intHeight+'" /></object>';
				break;
			case "rm":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+intWidth+'" height="'+intHeight+'"><param name="SRC" value="'+strURL+'" /><param name="CONTROLS" VALUE="ImageWindow" /><param name="CONSOLE" value="one" /><param name="AUTOSTART" value="true" /><embed src="'+strURL+'" nojava="true" controls="ImageWindow" console="one" width="'+intWidth+'" height="'+intHeight+'"></object>'+
                '<br/><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+intWidth+'" height="32" /><param name="CONTROLS" value="StatusBar" /><param name="AUTOSTART" value="true" /><param name="CONSOLE" value="one" /><embed src="'+strURL+'" nojava="true" controls="StatusBar" console="one" width="'+intWidth+'" height="24" /></object>'+'<br /><object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+intWidth+'" height="32" /><param name="CONTROLS" value="ControlPanel" /><param name="AUTOSTART" value="true" /><param name="CONSOLE" value="one" /><embed src="'+strURL+'" nojava="true" controls="ControlPanel" console="one" width="'+intWidth+'" height="24" autostart="true" loop="false" /></object>';
				break;
			case "ra":
				tmpstr='<div style="height:6px;overflow:hidden"></div><object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" id="RAOCX" width="450" height="60"><param name="_ExtentX" value="6694"><param name="_ExtentY" value="1588"><param name="AUTOSTART" value="true"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="0"><param name="SRC" value="'+strURL+'"><param name="CONTROLS" value="StatusBar,ControlPanel"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="0"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000"><embed src="'+strURL+'" width="450" autostart="true" height="60"></embed></object>';
				break;
			case "qt":
				tmpstr='<div style="height:6px;overflow:hidden"></div><embed src="'+strURL+'" autoplay="true" loop="false" controller="true" playeveryframe="false" cache="false" scale="TOFIT" bgcolor="#000000" kioskmode="false" targetcache="false" pluginspage="http://www.apple.com/quicktime/" />';
		}
		document.getElementById(strID).innerHTML = tmpstr;
	}
	
	document.getElementById(strID+"_href").blur()
}

function CopyText(obj) {
	ie = (document.all)? true:false
	if (ie){
		var rng = document.body.createTextRange();
		rng.moveToElementText(obj);
		rng.scrollIntoView();
		rng.select();
		rng.execCommand("Copy");
		rng.collapse(false);
	}
}

function open_more(a,b,amore,aless){
	if (document.getElementById(b).style.display==""){
		document.getElementById(b).style.display="none";
		document.getElementById(a).innerHTML=amore;
	}else{
		document.getElementById(b).style.display="";
		document.getElementById(a).innerHTML=aless;
	}
}

function WriteHeadFlash(Path,Width,Height,Transparent){
	 var Temp,T=""
	 Temp='<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="FlashH" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="'+Width+'" height="'+Height+'">'
	 Temp+='<param name="movie" value="'+Path+'"/>'
	 Temp+='<param name="quality" value="High"/>'
	 Temp+='<param name="scale" value="ExactFit"/>'
	 if (Transparent) {Temp+=' <param name="wmode" value="transparent"/>';T='wmode="transparent"'}
	 Temp+='<embed src="'+Path+'" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="FlashH" width="'+Width+'" height="'+Height+'" quality="High"'+T+' scale="ExactFit"/>'
	 Temp+='</object>'
	 document.getElementById("FlashHead").innerHTML=Temp
}
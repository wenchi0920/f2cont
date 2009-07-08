/**
F2blog AJAX Script (guestbook,reply)
*/

//发送一个请求
function makeRequest(url,functionName,httpType,sendData) {
	http_request = false;
	
	if (!httpType) httpType = "GET";

	if (window.XMLHttpRequest) { // Non-IE...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { //IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!http_request) {
		alert('Cannot Create an XMLHttp request');
		return false;
	}
	http_request.onreadystatechange = functionName;
	http_request.open(httpType, url, true);
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http_request.send(sendData);
}

//转换分页
function f2_ajax_page(url){
	document.getElementById("load_ajax_msg").innerHTML = "Loading ... ";
	makeRequest(url,f2_ajax_page_result,"GET",null);
}

//返回结果
function f2_ajax_page_result() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//alert(http_request.responseText);
			document.getElementById("load_ajax_msg").innerHTML = "";
			setInnerHTML(document.getElementById("Content_ContentList"),http_request.responseText);
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//新窗转换分页
function f2_ajax_newwin_page(url){
	opener.document.getElementById("load_ajax_msg").innerHTML = "Loading ... ";
	makeRequest(url,f2_ajax_newwin_page_result,"GET",null);
}

//返回结果
function f2_ajax_newwin_page_result() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//alert(http_request.responseText);
			opener.document.getElementById("load_ajax_msg").innerHTML = "";
			setInnerHTML(opener.document.getElementById("Content_ContentList"),http_request.responseText);
			if (navigator.product == 'Gecko'){
				window.close();
				alert("Don't auto close this window, please click close window!");
			}else window.close();
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//留言与评论发表
function f2_ajax_post(postData){
	makeRequest("f2blog_ajax.php",f2_ajax_post_result,"POST",postData);
}

//返回结果
function f2_ajax_post_result(){
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//返回处理结果
			f2_ajax_response(http_request.responseText);
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//取得blog的随机码
function f2_ajax_tbsession(url){
	makeRequest(url,f2_ajax_tbsession_result,"GET",null);
}

//返回结果
function f2_ajax_tbsession_result(){
	//alert(http_request.responseText);
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//alert(http_request.responseText);
			returnvalue=http_request.responseText;
			if (returnvalue.indexOf("trackback.php")>0){
				document.getElementById("tbURL").innerHTML = returnvalue;
				document.getElementById("tbURL").style.display="";
				document.getElementById("gettbUrl").style.display="none";
			}else{
				//alert(returnvalue);
				document.getElementById("tbURL").style.display="none";
				document.getElementById("gettbUrl").style.display="";
			}
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//读取加密日志
function f2_ajax_logspassword(postData){
	makeRequest("f2blog_ajax.php",f2_ajax_logspassword_result,"POST",postData);
}

//返回结果
function f2_ajax_logspassword_result(){
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//返回处理结果
			var pass_result=http_request.responseText;
			if (pass_result.indexOf("+|*+|+*|+")>0){
				arr_result=pass_result.split("+|*+|+*|+");
				setInnerHTML(document.getElementById(arr_result[0]),arr_result[1]);
			}else{
				alert(pass_result);
			}
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//日历翻页 onclick="f2_ajax_calendar('f2blog_ajax.php?job=calendar&amp;seekname=200611&amp;ajax_display=calendar')"
function f2_ajax_calendar(url){
	document.getElementById("Calendar_Body").innerHTML = "Loading ... ";
	makeRequest(url,f2_ajax_calendar_result,"GET",null);
}

//返回结果
function f2_ajax_calendar_result() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//alert(http_request.responseText);
			setInnerHTML(document.getElementById("Calendar_Body"),http_request.responseText);
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//媒体读取 onclick="f2blog_ajax.php?ajax_display=media&amp;media=$fid&amp;id={$dataInfo['id']}"
function f2_ajax_media(url,strID,strPlay,strStop){
	if (document.getElementById(strID).style.display!='none') {
		document.getElementById(strID).innerHTML='';
		document.getElementById(strID).style.display='none';
		document.images[strID+"_img"].src="images/mm_snd.gif" 		
		document.getElementById(strID+"_text").innerHTML=strPlay	
	}else{
		document.getElementById(strID).innerHTML = "Loading ... ";
		document.getElementById(strID).style.display='';
		document.images[strID+"_img"].src="images/mm_snd_stop.gif" 		
		document.getElementById(strID+"_text").innerHTML=strStop
		makeRequest(url,f2_ajax_media_result,"GET",null);
	}
}

//返回结果
function f2_ajax_media_result() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			//返回处理结果
			var media_result=http_request.responseText;
			arr_result=media_result.split("+|*+|+*|+");

			if (media_result.indexOf("<")>0){			
				setInnerHTML(document.getElementById(arr_result[0]),arr_result[1]);
			}else{
				document.getElementById(arr_result[0]).innerHTML = arr_result[1];
				alert(media_result);
			}			
		} else {
			alert('There was a problem with the request. Check your permission or contact with administrator.\n\n'+http_request.responseText);
		}
	}
}

//把提交的内容转换编码
function f2_ajax_encode (str) {
    str=encodeURIComponent(str);
    if (navigator.product == 'Gecko') str=str.replace(/%0A/g, "%0D%0A"); //In IE, a new line is encoded as rn, while in Mozilla it's n
    return str;
}

/*
 * 描述：跨浏览器的设置 innerHTML 方法
 *       允许插入的 HTML 代码中包含 script 和 style
 * 作者：kenxu <kenxu at ajaxwing dot com>
 * 日期：2006-03-23
 * 参数：
 *    el: 合法的 DOM 树中的节点
 *    htmlCode: 合法的 HTML 代码
 * 经测试的浏览器：ie5+, firefox1.5+, opera8.5+
 */
var setInnerHTML = function (el, htmlCode) {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf('msie') >= 0 && ua.indexOf('opera') < 0) {
        htmlCode = '<div style="display:none">for IE</div>' + htmlCode;
        htmlCode = htmlCode.replace(/<script([^>]*)>/gi,'<script$1 defer>');
        el.innerHTML = htmlCode;
        el.removeChild(el.firstChild);
    } else {
        var el_next = el.nextSibling;
        var el_parent = el.parentNode;
        el_parent.removeChild(el);
        el.innerHTML = htmlCode;
        if (el_next) {
            el_parent.insertBefore(el, el_next)
        } else {
            el_parent.appendChild(el);
        }
    }
}
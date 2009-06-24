/*var url = tinyMCE.getParam("Music_external_list_url");
if (url != null) {
	// Fix relative
	if (url.charAt(0) != '/' && url.indexOf('://') == -1)
		url = tinyMCE.documentBasePath + "/" + url;

	document.write('<sc'+'ript language="javascript" type="text/javascript" src="' + url + '"></sc'+'ript>');
}*/

function init() {
	tinyMCEPopup.resizeToInnerSize();

	//document.getElementById("filebrowsercontainer").innerHTML = getBrowserHTML('filebrowser','file','Music','Music');

	// Image list outsrc
	var html = getMusicListHTML('filebrowser','file','Music','Music');
	/*if (html == "")
		document.getElementById("linklistrow").style.display = 'none';
	else
		document.getElementById("linklistcontainer").innerHTML = html;*/

	var formObj = document.forms[0];
	var swffile   = tinyMCE.getWindowArg('swffile');
	var swff2desc  = '' + tinyMCE.getWindowArg('swff2desc');
	var swfwidth  = '' + tinyMCE.getWindowArg('swfwidth');
	var swfheight = '' + tinyMCE.getWindowArg('swfheight');

	if (swfwidth.indexOf('%')!=-1) {
		formObj.width2.value = "%";
		formObj.width.value  = swfwidth.substring(0,swfwidth.length-1);
	} else {
		formObj.width2.value = "px";
		formObj.width.value  = swfwidth;
	}

	if (swfheight.indexOf('%')!=-1) {
		formObj.height2.value = "%";
		formObj.height.value  = swfheight.substring(0,swfheight.length-1);
	} else {
		formObj.height2.value = "px";
		formObj.height.value  = swfheight;
	}

	formObj.file.value = swffile;
	formObj.f2desc.value = swff2desc;
	formObj.insert.value = tinyMCE.getLang('lang_' + tinyMCE.getWindowArg('action'), 'Insert', true);

	selectByValue(formObj, 'linklist', swffile);

	// Handle file browser
	if (isVisible('filebrowser'))
		document.getElementById('file').style.width = '230px';

	// Auto select Music in list
	/*if (typeof(tinyMCEMusicList) != "undefined" && tinyMCEMusicList.length > 0) {
		for (var i=0; i<formObj.linklist.length; i++) {
			if (formObj.linklist.options[i].value == tinyMCE.getWindowArg('swffile'))
				formObj.linklist.options[i].selected = true;
		}
	}*/
}

function getMusicListHTML() {
	if (typeof(tinyMCEMusicList) != "undefined" && tinyMCEMusicList.length > 0) {
		var html = "";

		html += '<select id="linklist" name="linklist" style="width: 250px" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="this.form.file.value=this.options[this.selectedIndex].value;">';
		html += '<option value="">---</option>';

		for (var i=0; i<tinyMCEMusicList.length; i++)
			html += '<option value="' + tinyMCEMusicList[i][1] + '">' + tinyMCEMusicList[i][0] + '</option>';

		html += '</select>';

		return html;
	}

	return "";
}

function insertMusic() {
	var formObj = document.forms[0];
	var html      = '';
	var file      = formObj.file.value;
	var f2desc      = formObj.f2desc.value;
	var width     = formObj.width.value;
	var height    = formObj.height.value;
	if (formObj.width2.value=='%') {
		width = width + '%';
	}
	if (formObj.height2.value=='%') {
		height = height + '%';
	}

	if (width == "")
		width = 300;

	if (height == "")
		height = 48;

	html += ''
		+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
		+ 'width="' + width + '" height="' + height + '" '
		+ 'border="0" alt="' + file + '" title="' + f2desc + '" class="mceItemMusic" />';
	tinyMCEPopup.execCommand("mceInsertContent", true, html);
	tinyMCE.selectedInstance.repaint();

	tinyMCEPopup.close();
}

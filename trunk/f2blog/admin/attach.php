<?php 
require_once("function.php");

check_login();

$action=$_GET['action'];
$mark_id=intval($_GET['mark_id']);
$editorcode=$_GET['editorcode'];

if (empty($_POST['chkname']) && $action==""){
	$_POST['chkname']=$settingInfo['disAttach'];
}elseif (empty($_POST['chkname'])){
	$_POST['chkname']=0;
}

//输出头部信息
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"themes/{$settingInfo['adminstyle']}/style.css\">\n";
echo "</head>\n";
echo "<body topmargin=\"0\" leftmargin=\"3\">\n";
?>
<script type="text/javascript" src="../editor/wind/jquery.js"></script>
<script type="text/javascript" src="../editor/wind/thickbox.js"></script>
<link rel="stylesheet" href="../editor/wind/thickbox.css" type="text/css" media="screen" />
<script style="javascript">
<!--
	function onclick_update(form) {
		if (form.attfile.value==""){
			alert("<?php echo $strErrNull?>");
			return false;
		}

		form.action = "<?php echo "$PHP_SELF?editorcode=$editorcode&mark_id=$mark_id&action=save"?>";
		form.submit();
	}

	function onclick_delete(form,editorcode) {
		var fileList = document.getElementById('fileList');
		if (fileList.selectedIndex < 0) {
			alert("<?php echo $strPlsSelectFile?>");
			return false;
		}

		document.seekform.action = "<?php echo "$PHP_SELF?mark_id=$mark_id&action=delete&editorcode="?>"+editorcode;
		document.seekform.submit();
	}

	function onclick_delete_all(form,editorcode) {
		var fileList = document.getElementById('fileList');
		if (fileList.selectedIndex < 0) {
			alert("<?php echo $strPlsSelectFile?>");
			return false;
		}

		document.seekform.action = "<?php echo "$PHP_SELF?mark_id=$mark_id&action=deleteall&editorcode="?>"+editorcode;
		document.seekform.submit();
	}

	function onclick_attachment(checked){			
		if(checked){
			document.getElementById('attstyle1').style.display='';
			document.getElementById('attfile1').disabled=false;
			document.getElementById('attstyle2').style.display='none';
			document.getElementById('attfile2').disabled=true;
		}else{
			document.getElementById('attstyle1').style.display='none';
			document.getElementById('attfile1').disabled=true;
			document.getElementById('attstyle2').style.display='';
			document.getElementById('attfile2').disabled=false;
		}
	}

	function selectAttachment() {
	try {
		width = document.getElementById('previewSelected').clientWidth;
		height = document.getElementById('previewSelected').clientHeight;

		var code = '';
		if (document.forms[0].fileList.selectedIndex < 0)
			return false;
		var fileName = document.forms[0].fileList.value.split("|")[0];

		if (!new RegExp("://", "gi").exec(fileName)){
			fileName = '../attachments/' + fileName;
		}
					
		if((new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName))) {
			document.getElementById('previewSelected').innerHTML = '<img style="width: '+width+'px; height: '+height+'px" src="'+fileName+'" alt="" onerror="this.src=\'../attachments/spacer.gif\'"/>';
			return false;
		}
					
		if((new RegExp("\\.(mp3)$", "gi").exec(fileName))) {
			code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="100%"><param name="movie" value="mini.swf"/><param name="FlashVars" value="sounds='+fileName+'*!_!&amp;autoPlay=1&amp;visible=1"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/>';
						
			code += '<!--[if !IE]> <--><object type="application/x-shockwave-flash" data="../images/mini.swf" width="100%" height="100%"><param name="FlashVars" value="sounds='+fileName+'*!_!&amp;autoPlay=1&amp;visible=1"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/></object><!--> <![endif]--></object>';
		}
					
		if((new RegExp("\\.(swf)$", "gi").exec(fileName))) {			
			code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="100%"><param name="movie" value="'+fileName+'"/><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/>';
			code += '<!--[if !IE]> <--><object type="application/x-shockwave-flash" data="'+fileName+'" width="100%" height="100%"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/></object><!--> <![endif]--></object>';
		}

		if((new RegExp("\\.(mov)$", "gi").exec(fileName))) {			
			code = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="'+width+'" height="'+height+'"><param name="src" value="'+fileName+'"/><param name="controller" value="true"><param name="scale" value="Aspect">';
			code += '<!--[if !IE]> <--><object type="video/quicktime" data="'+fileName+'" width="'+width+'" height="'+height+'" showcontrols="true" TYPE="video/quicktime" scale="Aspect" nomenu="true"><param name="showcontrols" value="true"><param name="scale" value="ToFit"></object><!--> <![endif]--></object>';
		}
					
		if((new RegExp("\\.(mp2|wma|mid|midi|mpg|wav)$", "gi").exec(fileName))) {
			code ='<object width="'+width+'" height="'+height+'" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading for you" type="application/x-oleobject" align="middle">';		
			code +='<param name="FileName" value="'+fileName+'">';
			code +='<param name="ShowStatusBar" value="False">';
			code +='<param name="DefaultFrame" value="mainFrame">';
			code +='<param name="showControls" value="false">';
			code +='<embed type="application/x-mplayer2" pluginspage = "http://www.microsoft.com/Windows/MediaPlayer/" src="'+fileName+'" align="middle" width="'+width+'" height="'+height+'" showControls="false" defaultframe="mainFrame" showstatusbar="false"></embed>';
			code +='</object>';
		}
					
		if((new RegExp("\\.(rm|ram)$", "gi").exec(fileName))) {		
			code = '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+width+'" height="'+height+'"><param name="src" value="'+fileName+'"/><param name="CONTROLS" value="imagewindow"><param name="AUTOGOTOURL" value="FALSE"><param name="CONSOLE" value="radio"><param name="AUTOSTART" value="TRUE">';
			code += '<!--[if !IE]> <--><object type="audio/x-pn-realaudio-plugin" data="'+fileName+'" width="'+width+'" height="'+height+'" ><param name="CONTROLS" value="imagewindow"><param name="AUTOGOTOURL" value="FALSE"><param name="CONSOLE" value="radio"><param name="AUTOSTART" value="TRUE"></object><!--> <![endif]--></object>';			
		}
					
		if (code == undefined || code == '') {
			document.getElementById('previewSelected').innerHTML = "<table width=\"100%\" height=\"100%\"><tr><td valign=\"middle\" align=\"center\"><?php echo $strBrowse?></td></tr></table>";
			return true;
		}
					
		document.getElementById('previewSelected').innerHTML = code;		
		return false;
	} catch (e) {
		document.getElementById('previewSelected').innerHTML = "<table width=\"100%\" height=\"100%\"><tr><td valign=\"middle\" align=\"center\"><?php echo $strBrowse?></td></tr></table>";	
		return true;
	}
	}

	<?php echo "var contentWidth=$settingInfo[img_width];"?>
	
	//插入文件
	function insert_file(align,editorcode) {
		var oSelect = document.forms[0].fileList;
		var code="";
		if (oSelect.selectedIndex < 0) {
			alert("<?php echo $strPlsSelectFile?>");
			return false;
		}

		var value = oSelect.options[oSelect.selectedIndex].value.split("|");
		var width = value[1];
		var height = value[2];
		var att_id=value[3];
		var fileName=value[0];

		if (!new RegExp("://", "gi").exec(fileName)){
			fileName = 'attachments/' + fileName;
		}

		if(width>0 && height>0 && (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)))
		{
			if(width > contentWidth) {
				height = parseInt(height * (contentWidth / width));
				width = contentWidth;
			}

			if (editorcode=="tiny") {//tiny
				var mce_src = fileName;
				mce_src1 = mce_src.replace('_f2s','');
				
				if (new RegExp("://", "gi").exec(fileName)){
					var src = fileName;
				}else{
					var src =  "<?php echo $home_url?>" + fileName;
					mce_src = "../" + fileName;
				}

				value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + mce_src1 + "')\"";

				if (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) {
					if (align=="L") align=' align="left"';
					if (align=="C") align=' align="middle"';
					if (align=="R") align=' align="right"';
					if (align=="F") align='';

					code = "<img src=\"" + src + "\" mce_src=\"" + mce_src + "\" " + value[1] + align + " />";
				} else {
					code += ''
						+ '<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
						+ 'width="' + width + '" height="' + height + '" '
						+ 'border="0" alt="' + src + '" title="' + src + '" class="mceItemFlash" />';
					
					if (align=="L") code="<p align=\"left\">" + code + "</p>";
					if (align=="C") code="<p align=\"center\">" + code + "</p>";
					if (align=="R") code="<p align=\"right\">" + code + "</p>";
				}
			} else if (editorcode=="html"){ //html
				var mce_src = fileName;
				mce_src1 = mce_src.replace('_f2s','');

				if (new RegExp("://", "gi").exec(fileName)){
					var src = fileName;
				}else{
					var src =  "<?php echo $home_url?>"+ fileName;
					mce_src = "../" + fileName;
				}

				value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + mce_src1 + "')\"";

				if (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) {
					if (align=="L") align=' align="left"';
					if (align=="C") align=' align="middle"';
					if (align=="R") align=' align="right"';
					if (align=="F") align='';

					code = "<img src=\"" + src + "\" " + value[1] + align + "/>";
				} else {
					code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="' + width + '" height="' + height + '"><param name="movie" value="' + src + '"/><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/>';
					code += '<!--[if !IE]> <--><object type="application/x-shockwave-flash" data="' + src + '" width="' + width + '" height="' + height + '"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/></object><!--> <![endif]--></object>';

					if (align=="L") code="<p align=\"left\">" + code + "</p>";
					if (align=="C") code="<p align=\"center\">" + code + "</p>";
					if (align=="R") code="<p align=\"right\">" + code + "</p>";
				}
			} else { //UBB Editor
				if (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) {
					code = '[img width=' + width + ',height=' + height + ']'+fileName+'[/img]';

					if (align=="L") code="[align=left]" + code + "[/align]";
					if (align=="C") code="[align=center]" + code + "[/align]";
					if (align=="R") code="[align=right]" + code + "[/align]";
				} else {
					code = '[flash=' + width + ',' + height + ']' + fileName + '[/flash]';

					if (align=="L") code="[align=left]" + code + "[/align]";
					if (align=="C") code="[align=center]" + code + "[/align]";
					if (align=="R") code="[align=right]" + code + "[/align]";
				}
			}
		} else {
			if (editorcode=="tiny") {
				var code ='<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" width="80" height="25" border="0" class="mceItemFile" alt="' + att_id + '"/>';

				if (align=="L") code="<p align=\"left\">" + code + "</p>";
				if (align=="C") code="<p align=\"center\">" + code + "</p>";
				if (align=="R") code="<p align=\"right\">" + code + "</p>";
			} else { //UBB Editor
				var code='[fileBegin]'+att_id+'[fileEnd]';

				if (align=="L") code="[align=left]" + code + "[/align]";
				if (align=="C") code="[align=center]" + code + "[/align]";
				if (align=="R") code="[align=right]" + code + "[/align]";
			}
		}
		
		//alert(code);
		//插入编辑器中
		parent.AddText(code);
		
		//附件记忆
		f2_attachments = getCookie('f2_attachments');
		if (f2_attachments!="") {
			f2_attachments = f2_attachments + '|' + att_id;
		}else{
			f2_attachments = att_id;
		}
		//alert(f2_attachments);
		setCookie ('f2_attachments',f2_attachments,null,null, null, false);

		return true;
	}

	//插入加需要用户注册才能下载的文件
	function insert_mfile(editorcode) {
		var oSelect = document.forms[0].fileList;
		if (oSelect.selectedIndex < 0) {
			alert("<?php echo $strPlsSelectFile?>");
			return false;
		}

		var value = oSelect.options[oSelect.selectedIndex].value.split("|");
		var att_id=value[3];

		if (editorcode=="tiny") {
			var code ='<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" width="50" height="25" border="0" class="mceItemMFile" alt="' + att_id + '"/>';
		} else { //UBB Editor
			var code='[mfileBegin]'+att_id+'[mfileEnd]';
		}

		//插入编辑器中
		parent.AddText(code);

		//附件记忆
		f2_attachments = getCookie('f2_attachments');
		if (f2_attachments!="") {
			f2_attachments = f2_attachments + '|' + att_id;
		}else{
			f2_attachments = att_id;
		}
		//alert(f2_attachments);
		setCookie ('f2_attachments',f2_attachments,null,null, null, false);

		return true;
	}
	
	//插入多个图片。
	function insert_file_mul(num,editorcode) {
		var oSelect = document.forms[0].fileList;
		var count = 0;
		var prefix = '';
		for (var i = 0; i < oSelect.options.length; i++) {
			if (oSelect.options[i].selected == true) {
				var value = oSelect.options[i].value.split("|");
				var width = value[1];
				var height = value[2];
				var fileName=value[0];
				
				if (width>0 && height>0 && (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) )
				{
					if (width > contentWidth / num) {
						height = parseInt(height * (contentWidth / num / width));
						width = contentWidth / num;
					}
		
					var src = fileName;					
					if (editorcode=="tiny") {
						if (!new RegExp("://", "gi").exec(src)){
							src = '../attachments/' + fileName;
						}
						src1 = src.replace('_f2s','');
						value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + src1 + "')\"";
						var code = "<img src=\"" + src + "\" " + value[1] + "/>";
					} else if (editorcode=="html"){
						if (!new RegExp("://", "gi").exec(src)){
							src = '<?php echo $home_url?>attachments/' + fileName;
						}
						src1 = src.replace('_f2s','');
						value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + src1 + "')\"";
						var code = "<img src=\"" + src + "\" " + value[1] + "/>";
					} else {
						var code = '[img]'+src+'[/img]';
					}
				
					prefix = prefix + '^' + code;
					count ++;


					//附件记忆
					var att_id=value[3];
					f2_attachments = getCookie('f2_attachments');
					if (f2_attachments!="") {
						f2_attachments = f2_attachments + '|' + att_id;
					}else{
						f2_attachments = att_id;
					}
					//alert(f2_attachments);
					setCookie ('f2_attachments',f2_attachments,null,null, null, false);
				} 				
			}
		}

		if (count != num) {
			if (num==2) {
				alert("<?php echo $strPlsSelectFile2?>");
			} else {
				alert("<?php echo $strPlsSelectFile3?>");
			}
			return false;
		}

		var imageinfo = prefix.split("^");
		try {
			if (editorcode=="tiny" || editorcode=="html") {
				var code = "<p align=\"center\"><table cellspacing=\"5\"><tr>";
				for (var j = 1; j < imageinfo.length; j++) {
					code = code + "<td>" + imageinfo[j] + "</td>";
				}
				code = code + "</tr></table></p>";
			} else {
				var code = "[align=center]";
				for (var j = 1; j < imageinfo.length; j++) {
					code = code + "    " + imageinfo[j];
				}
				code = code + "[/align]";
			}

			//插入编辑器中
			parent.AddText(code);

			return true;
		} catch(e) { }

		return true;
	}

	//插入图片播放
	function insert_img_play(editorcode)
	{
		try
		{
			var oSelect = document.forms[0].fileList;
			if (oSelect.selectedIndex < 0) {
				alert("<?php echo $strPlsSelectFile?>\t");
				return false;
			}
			
			var fileList = '';
			for (var i = 0; i<oSelect.length; i++) {
				if (!oSelect.options[i].selected) continue;
				var value = oSelect.options[i].value.split("|");
				file=value[0];
				file_id=value[3];
				if(new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(file)){
					fileList += file_id+'|';

					//附件记忆
					var att_id=value[3];
					f2_attachments = getCookie('f2_attachments');
					if (f2_attachments!="") {
						f2_attachments = f2_attachments + '|' + file_id;
					}else{
						f2_attachments = file_id;
					}
					//alert(f2_attachments);
					setCookie ('f2_attachments',f2_attachments,null,null, null, false);
				}
			}
			if(fileList == '') {
				alert("<?php echo $strInsertImgOnly?>");
				return false;
			}
			fileList = fileList.substr(0,fileList.length-1);

			try {
				if (editorcode=="tiny") {
					var code ='<p align="center"><img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" width="400" height="300" border="0" class="mceItemGallery" alt="' + fileList + ',400,300"/></p>';
				} else if (editorcode=="html"){
					var code ='<p align="center">[galleryBegin]'+fileList+'[galleryEnd]</p>';
				} else {
					var code ='[align=center][galleryBegin]'+fileList+'[galleryEnd][/align]';
				}

				//插入编辑器中
				parent.AddText(code);

				return true;
			} catch(e) { }
			return true;
		}
		catch(e)
		{
			return false;
		}
	}

	function insert_hide(editorcode) {
		if (editorcode=="tiny") {
			//' + parent.inst.selection.getSelectedHTML() + 
			var code = '<div id="MoreLess" class="mceItemMoreLess">隐藏文字</div>';
		} else { //UBB Editor
			var code='[hideBegin]隐藏文字[hideEnd]';
		}

		//插入编辑器中
		parent.AddText(code);

		return true;
	}

	function insert_more(editorcode) {
		if (editorcode=="tiny") {
			var code ='<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" ';
				code += ' width="100%" height="10px" ';
				code += 'class="mce_plugin_f2blog_more" name="mce_plugin_f2blog_more" />';
		} else { //UBB Editor
			var code='[more]';
		}

		//插入编辑器中
		parent.AddText(code);

		return true;
	}

	function insert_page(editorcode) {
		if (editorcode=="tiny") {
			var code = '<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" ';
				code += ' width="100%" height="10px" ';
				code += 'class="mce_plugin_f2blog_page" name="mce_plugin_f2blog_page" />';
		} else { //UBB Editor
			var code='[nextpage]';
		}

		//插入编辑器中
		parent.AddText(code);

		return true;
	}

	function insert_quote(editorcode) {
		if (editorcode=="tiny") {
			var code = '<div style="background-color: #F9FBFC;border: 1px solid #C3CED9;margin: 0;margin-bottom: 5px;width: auto;height: auto;overflow : auto;text-align: left;font-family: "Courier New", Courier, monospace;font-size: 12px;"><p>这是引用代码：</p><br/><br/></div>';
		} else { //UBB Editor
			var code='[quote] [/quote]';
		}

		//插入编辑器中
		parent.AddText(code);

		return true;
	}

	function openAtttachment(url,width,height) {
		var openWindow = '';
		openWindow = window.open(url, "f2blog", "width="+width+",height="+height+",location=0,menubar=0,resizable=1,scrollbars=1,status=0,toolbar=0");
		openWindow.focus();
		openWindow.moveTo(screen.width/2-width/2,screen.height/2-height/2);
	}

	function openEditorWindows(url,width,height) {
		var openWindow = '';
		openWindow = window.open(url, "f2blog", "width="+width+",height="+height+",location=0,menubar=0,resizable=0,scrollbars=0,status=0,toolbar=0");
		openWindow.focus();
		openWindow.moveTo(screen.width/2-width/2,screen.height/2-height/2);
	}

	//Cookie
	function setCookie(name,value,expiry,path,domain,secure) {
		var nameString = name + "=" + value;
		var expiryString = (expiry == null) ? "" : " ;expires = "+ expiry.toGMTString();
		var pathString = (path == null) ? "" : " ;path = "+ path;
		var domainString = (path == null) ? "" : " ;domain = "+ domain;
		var secureString = (secure) ?";secure" :"";
		document.cookie = nameString + expiryString + pathString + domainString + secureString;
	}

	function getCookie (name) {
		var CookieFound = false;
		var start = 0;
		var end = 0;
		var CookieString = document.cookie;
		var i = 0;

		while (i <= CookieString.length) {
			start = i ;
			end = start + name.length;
			if (CookieString.substring(start, end) == name){
				CookieFound = true;
				break;
			}
			i++;
		}

		if (CookieFound){
			start = end + 1;
			end = CookieString.indexOf(";",start);
			if (end < start) end = CookieString.length;
			return unescape(CookieString.substring(start, end));
		}
		return "";
	}
</script>
<?php
	//保存数据
	if ($action=="save") {
		if(count($_FILES)==1) {
			$attachment_file=$_FILES["attfile"]["tmp_name"];
			$subpath=($settingInfo['attachDir']=="1")?date("Ym")."/":"";
			$path="../attachments/".$subpath;

			$fileName=$_FILES["attfile"]["name"];
			$fileSize=$_FILES["attfile"]["size"];
			$updateStyle=$_FILES["attfile"]["type"];
			$info=$fileName." (".formatFileSize($fileSize).")";
			$attdesc=trim($_POST['attdesc']); //可以为空
			
			$attachment=upload_file($attachment_file,$fileName,$path);
			if ($attachment=="") {
				print_message($strAttachmentsError);
				$action="";
			} else {
				$value=$subpath.$attachment;
				$basefile="../attachments/".$value;
				
				$fileType=getFileType($fileName);
				if($imageAtt=getimagesize($path.$attachment)){
					$fileWidth=$imageAtt[0];
					$fileHeight=$imageAtt[1];
				}else{
					$fileWidth=0;
					$fileHeight=0;
				}

				// 判断是否为图片格式
				if ($fileType == 'gif' || $fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png') {
					// 判断是否使用缩略图
					if ($settingInfo['genThumb']=="1") {
						$tsize = explode('x', strtolower($settingInfo['thumbSize']));
						if (($fileWidth > $tsize[0]) || ($fileHeight > $tsize[1])) {
							$attach_thumb = array(
								'filepath'     => "../attachments/".$value,
								'filename'     => $attachment,
								'extension'    => $fileType,
								'thumbswidth'  => $tsize[0],
								'thumbsheight' => $tsize[1],
							);
							
							$thumb_data = generate_thumbnail($attach_thumb);
							$fileWidth    = $thumb_data['thumbwidth'];
							$fileHeight   = $thumb_data['thumbheight'];
							$thumbfile = $thumb_data['thumbfilepath'];
							$value=str_replace("../attachments/","",$thumbfile);
						}
					}
				}else{
					$thumbfile="";
				}
				
				//写进数据库
				$fileName=($attdesc=="")?$fileName:encode($attdesc).".".$fileType;
				$rsexits=getFieldValue($DBPrefix."attachments","attTitle='".$fileName."' and fileType='".$updateStyle."' and fileSize='".$fileSize."' and logId='0'","name");
				if ($rsexits==""){
					$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime,logId) VALUES ('$value','$fileName','$updateStyle','$fileSize','$fileWidth','$fileHeight','".time()."',0)";			
					$DMC->query($sql);
				}else{
					print_message($strDataExists);
				}

				do_filter("f2_attach",$basefile);
				if (!empty($thumbfile)) {
					do_filter("f2_attach",$thumbfile); //縮略圖
				}

				settings_recount("attachments");
				settings_recache();
				$action="";
			}
		}
	}

	if ($action=="delete" || $action=="deleteall"){
		$stritem="";
		$fileListNew=$_POST['fileList'];
		for ($i=0;$i<count($fileListNew);$i++) {
			$arrname=explode("|",$fileListNew[$i]);
			$name=$arrname[0];

			//刪除文件
			if ($action=="deleteall" && strpos($name,"://")<1){
				$curName="../attachments/".$name;
				if (file_exists($curName)) @unlink($curName);

				//有無縮略圖，刪除大文件
				if (strpos($name,"_f2s")>0){
					$curName="../attachments/".str_replace("_f2s","",$name);
					if (file_exists($curName)) @unlink($curName);
				}
			}

			if ($stritem!=""){
				$stritem.=" or name='$name'";
			}else{
				$stritem.="name='$name'";
			}
		}

		$sql="delete from ".$DBPrefix."attachments where $stritem";
		$DMC->query($sql);

		$action="";
	}
	
	//查找没有关连日志的附件
	$where=($mark_id=="")?"logId=0":"logId='$mark_id' or logId='0'";
	$sql="select * from ".$DBPrefix."attachments where $where order by logId desc,postTime";
	$query_result=$DMC->query($sql);
?>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <table cellSpacing=0 cellPadding=3 width="100%" align=center border=1  bordercolordark=#ffffff bordercolorlight=#B3B3B3>
    <tr>
      <td width="213" valign="center" align="center" rowspan="2">
        <div id="previewSelected" style="width:160px; height:120px; background:#FFFFFF">
          <table width="100%" height="100%" valign="center" align="center">
            <tr>
              <td valign="middle" align="center">
                <?php echo $strBrowse?>
              </td>
            </tr>
          </table>
        </div>
      </td>
      <td valign="top" width="500">
        <select size="5" name="fileList[]" id="fileList" multiple="multiple" style="width:500px;" onChange="selectAttachment();">
          <?php while($fa = $DMC->fetchArray($query_result)){ ?>
          <option value="<?php echo $fa['name']."|".$fa['fileWidth']."|".$fa['fileHeight']."|".$fa['id']?>">
             <?php
				$imgSize=($fa['fileWidth']>0)?$fa['fileWidth']."X".$fa['fileHeight']." / ":"";
				echo $fa['attTitle']." (".$imgSize.formatFileSize($fa['fileSize']).") [".$fa['id']."]";
			 ?>
          </option>
          <?php } ?>
        </select>
	  </td>
      <td width="492" valign="top" style="padding:3px">
		<a href="remote_editor.php?<?php echo "mark_id=$mark_id&editorcode=$editorcode";?>&TB_iframe=true&height=110&width=500" title="<?php echo $strEditorInsertRemote;?>" class="thickbox"><img src="themes/<?php echo $settingInfo['adminstyle']?>/web.gif" title="<?php echo $strEditorInsertRemote;?>" alt="<?php echo $strEditorInsertRemote;?>" border="0" /></a>
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/AlignFree.gif" style="cursor:pointer;" onclick="insert_file('F','<?php echo $editorcode?>')" title="<?php echo $strSelFilFree?>" alt="<?php echo $strSelFilFree?>"/>
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/AlignLeft.gif" style="cursor:pointer;" onClick="insert_file('L','<?php echo $editorcode?>')" title="<?php echo $strSelFilLeft?>" alt="<?php echo $strSelFilLeft?>"/> 
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/AlignCenter.gif" style="cursor:pointer;" onClick="insert_file('C','<?php echo $editorcode?>')" title="<?php echo $strSelFilCenter?>" alt="<?php echo $strSelFilCenter?>"/> 
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/AlignRight.gif" style="cursor:pointer;" onClick="insert_file('R','<?php echo $editorcode?>')" title="<?php echo $strSelFilRight?>" alt="<?php echo $strSelFilRight?>"/>          
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/Align2Center.gif" style="cursor:pointer;" onClick="insert_file_mul(2,'<?php echo $editorcode?>')" title="<?php echo $strSelFilCenter2?>" alt="<?php echo $strSelFilCenter2?>"/>
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/Align3Center.gif" style="cursor:pointer;" onClick="insert_file_mul(3,'<?php echo $editorcode?>')" title="<?php echo $strSelFilCenter3?>" alt="<?php echo $strSelFilCenter3?>"/> 
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/gallery.gif" style="cursor:pointer;" onClick="insert_img_play('<?php echo $editorcode?>')" title="<?php echo $strImgPlay?>" alt="<?php echo $strImgPlay?>"/> 
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/attachDown.gif" style="cursor:pointer;" onClick="insert_mfile('<?php echo $editorcode?>')" title="<?php echo $strMemberDown?>" alt="<?php echo $strMemberDown?>"/>
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/attachDel.gif" style="cursor:pointer;" onClick="Javascript:onclick_delete(this.form,'<?php echo $editorcode?>')" title="<?php echo $strEditorDeleteAttach?>" alt="<?php echo $strEditorDeleteAttach?>"/> 
		<img src="themes/<?php echo $settingInfo['adminstyle']?>/drop.gif" style="cursor:pointer;" onClick="Javascript:onclick_delete_all(this.form,'<?php echo $editorcode?>')" title="<?php echo $strEditorDeleteAll?>" alt="<?php echo $strEditorDeleteAll?>"/> 
		<img src="../editor/plugins/f2blog/images/hide.gif" style="cursor:pointer;" onClick="Javascript:insert_hide('<?php echo $editorcode?>')" title="<?php echo $strEditorPluginHide;?>" alt="<?php echo $strEditorPluginHide;?>"/> 
		<img src="../editor/plugins/f2blog/images/more.gif" style="cursor:pointer;" onClick="Javascript:insert_more('<?php echo $editorcode?>')" title="<?php echo $strEditorPluginMore;?>" alt="<?php echo $strEditorPluginMore;?>"/> 
		<img src="../editor/plugins/f2blog/images/page.gif" style="cursor:pointer;" onClick="Javascript:insert_page('<?php echo $editorcode?>')" title="<?php echo $strEditorPluginPage;?>" alt="<?php echo $strEditorPluginPage;?>"/>
		<a href="../editor/wind/f2mcEditor.php?TB_iframe=true&height=110&width=500" title="<?php echo $strEditorPluginMusic;?>" class="thickbox"><img src="../images/mm_snd.gif" title="<?php echo $strEditorPluginMusic;?>" alt="<?php echo $strEditorPluginMusic;?>" border="0" /></a>
		<?php do_filter("f2_editor",$editorcode); ?>
	  </td>
    </tr>
    <tr>
      <td colspan="2">
		<?php echo $strAttType."<font color=red>".$settingInfo['attachType']."</font>";?>
		<INPUT TYPE="checkbox" NAME="chkname" value="1" <?php echo ($_POST['chkname']==1)?"checked":""?>  onclick="onclick_attachment(this.checked)"><?php echo $strAttachShowTitle?>
		<input class="btn" type="button" name="selectfile" value="<?php echo $strAttachmentManager?>" onClick="Javascript:openAtttachment('attachments.php?editorcode=<?php echo $editorcode?>&mark_id=<?php echo $mark_id?>',780,450)">
		<div id="attstyle1" style="display:<?php echo ($_POST['chkname']==1)?"":"none"?>">
        <?php echo "<span style='color:#239'>".$strAttachment."</span>: "?><input name="attfile" <?php echo ($_POST['chkname']==1)?"":"disabled"?> id="attfile1" class="filebox" type="file" size="88"/><br>
		<?php echo $strPluginDesc.": "?><input name="attdesc" class="textbox" type="text" size="90" maxlength="150"/>&nbsp;
		<input class="btn" type="submit" name="Submit" value="<?php echo $strApply?>" onClick="Javascript:onclick_update(this.form)">
        </div>
		<div id="attstyle2" style="display:<?php echo ($_POST['chkname']==1)?"none":""?>">
		<input name="attfile" id="attfile2" <?php echo ($_POST['chkname']==1)?"disabled":""?> class="filebox" type="file" onchange="Javascript:onclick_update(this.form)" size="90"/>
		</div>
      </td>
    </tr>
  </table>
</form>

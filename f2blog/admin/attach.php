<?
	$PATH="./";
	include("$PATH/function.php");

	$action=$_GET['action'];
	$mark_id=$_GET['mark_id'];

	//下载文件
	if ($action=="download"){
		$file_name=$_GET['file_name'];
		$file_path="../attachments/".$file_name;
		//读取文件内容
		if (file_exists($file_path)){
			$file_handle=fopen($file_path,"r");
			$file_size=filesize($file_path);
			$temp_buffer=fread($file_handle,$file_size);
			fclose($file_handle);
			
			$filename=getFieldValue($DBPrefix."attachments","name='$file_name'","attTitle");

			header("Content-type: application/zip");
			header("Content-disposition: attachment; filename=$filename");
			echo $temp_buffer;
			exit;
		}else{
			print_message($strFileNoExists);
		}
	}

	//输出头部信息
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"images/css/style.css\">\n";
	echo "</head>\n";
	echo "<body topmargin=\"0\" leftmargin=\"3\">\n";

	
?>
<script style="javascript">
<!--
	function onclick_update(form) {
		form.action = "<?="$PHP_SELF?mark_id=$mark_id&action=save"?>";
		form.submit();
	}

	function onclick_delete(form) {
		var fileList = document.getElementById('fileList');
		if (fileList.selectedIndex < 0) {
			alert("<?=$strPlsSelectFile?>");
			return false;
		}

		document.seekform.action = "<?="$PHP_SELF?mark_id=$mark_id&action=delete"?>";
		document.seekform.submit();
	}

	function onclick_download() {
		var fileList = document.getElementById('fileList');
		if (fileList.selectedIndex < 0) {
			alert("<?=$strPlsSelectFile?>");
			return false;
		}

		for(var i=0; fileList.length; i++) {
			if (fileList[i].selected) {
				var fileName = fileList[i].value.split("|")[0];
				window.location="<?="$PHP_SELF?action=download&file_name="?>"+fileName;
				break;
			}
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
					
		if((new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName))) {
			document.getElementById('previewSelected').innerHTML = '<img style="width: '+width+'px; height: '+height+'px" src="../attachments/'+fileName+'" alt="" onerror="this.src=\'../attachments/spacer.gif\'"/>';
			return false;
		}
					
		if((new RegExp("\\.(mp3)$", "gi").exec(fileName))) {
			code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="100%"><param name="movie" value="../attachments/mini.swf"/><param name="FlashVars" value="sounds=../attachments/'+fileName+'*!_!&amp;autoPlay=1&amp;visible=1"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/>';
						
			code += '<!--[if !IE]> <--><object type="application/x-shockwave-flash" data="../images/mini.swf" width="100%" height="100%"><param name="FlashVars" value="sounds=../attachments/'+fileName+'*!_!&amp;autoPlay=1&amp;visible=1"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/></object><!--> <![endif]--></object>';
		}
					
		if((new RegExp("\\.(swf)$", "gi").exec(fileName))) {			
			code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="100%"><param name="movie" value="../attachments/'+fileName+'"/><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/>';
			code += '<!--[if !IE]> <--><object type="application/x-shockwave-flash" data="../attachments/'+fileName+'" width="100%" height="100%"><param name="allowScriptAccess" value="sameDomain" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF"/></object><!--> <![endif]--></object>';
		}
					
		if((new RegExp("\\.(mov)$", "gi").exec(fileName))) {			
			code = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="'+width+'" height="'+height+'"><param name="src" value="../attachments/'+fileName+'"/><param name="controller" value="true"><param name="scale" value="Aspect">';
			code += '<!--[if !IE]> <--><object type="video/quicktime" data="../attachments/'+fileName+'" width="'+width+'" height="'+height+'" showcontrols="true" TYPE="video/quicktime" scale="Aspect" nomenu="true"><param name="showcontrols" value="true"><param name="scale" value="ToFit"></object><!--> <![endif]--></object>';
		}
					
		if((new RegExp("\\.(mp2|wma|mid|midi|mpg|wav)$", "gi").exec(fileName))) {
			code ='<object width="'+width+'" height="'+height+'" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading for you" type="application/x-oleobject" align="middle">';		
			code +='<param name="FileName" value="../attachments/'+fileName+'">';
			code +='<param name="ShowStatusBar" value="False">';
			code +='<param name="DefaultFrame" value="mainFrame">';
			code +='<param name="showControls" value="false">';
			code +='<embed type="application/x-mplayer2" pluginspage = "http://www.microsoft.com/Windows/MediaPlayer/" src="../attachments/'+fileName+'" align="middle" width="'+width+'" height="'+height+'" showControls="false" defaultframe="mainFrame" showstatusbar="false"></embed>';
			code +='</object>';
		}
					
		if((new RegExp("\\.(rm|ram)$", "gi").exec(fileName))) {		
			code = '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+width+'" height="'+height+'"><param name="src" value="../attachments/'+fileName+'"/><param name="CONTROLS" value="imagewindow"><param name="AUTOGOTOURL" value="FALSE"><param name="CONSOLE" value="radio"><param name="AUTOSTART" value="TRUE">';
			code += '<!--[if !IE]> <--><object type="audio/x-pn-realaudio-plugin" data="../attachments/'+fileName+'" width="'+width+'" height="'+height+'" ><param name="CONTROLS" value="imagewindow"><param name="AUTOGOTOURL" value="FALSE"><param name="CONSOLE" value="radio"><param name="AUTOSTART" value="TRUE"></object><!--> <![endif]--></object>';			
		}
					
		if (code == undefined || code == '') {
			document.getElementById('previewSelected').innerHTML = "<table width=\"100%\" height=\"100%\"><tr><td valign=\"middle\" align=\"center\"><?=$strBrowse?></td></tr></table>";
			return true;
		}
					
		document.getElementById('previewSelected').innerHTML = code;		
		return false;
	} catch (e) {
		document.getElementById('previewSelected').innerHTML = "<table width=\"100%\" height=\"100%\"><tr><td valign=\"middle\" align=\"center\"><?=$strBrowse?></td></tr></table>";	
		return true;
	}
	}

	<?="var contentWidth=$content_width;"?>

	function insert_file(align) {
		var oSelect = document.forms[0].fileList;
		var code="";
		if (oSelect.selectedIndex < 0) {
			alert("<?=$strPlsSelectFile?>");
			return false;
		}

		var value = oSelect.options[oSelect.selectedIndex].value.split("|");
		var width = value[1];
		var height = value[2];
		var att_id=value[3];
		var fileName=value[0];

		if(width>0 && height>0 &&(new RegExp("\\.(gif|jpe?g|png|swf)$", "gi").exec(fileName)))
		{
			if(width > contentWidth) {
				height = parseInt(height * (contentWidth / width));
				width = contentWidth;
			}
			var mce_src = "../attachments/"+ fileName;
			var src =  "<?=$settingInfo['blogUrl']?>attachments/"+ fileName;
			//value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + src + "')\"";
			value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + mce_src + "')\"";

			if (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) {
				code = "<img src=\"" + src + "\" mce_src=\"" + mce_src + "\" " + value[1] + "/>";
			} else {
				code += ''
					+ '<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
					+ 'width="' + width + '" height="' + height + '" '
					+ 'border="0" alt="' + src + '" title="' + src + '" class="mceItemFlash" />';
			}
		} else {
			//code = "[Down]"+att_id+"[/Down]";
			var code ='<img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" width="80" height="25" border="0" class="mceItemFile" alt="' + att_id + '"/>';
		}

		try {
			switch(align)
			{
				case "L":
					var reg = new RegExp('/>', "ig");
					var code = code.replace(reg,'align="left"/>');
					//var code = "<p align=\"left\">" + code + "</p>";
					break;
				case "C":
					var code = "<p align=\"center\">" + code + "</p>";
					break;
				case "R":
					var reg = new RegExp('/>', "ig");
					var code = code.replace(reg,'align="right"/>');
					//var code = "<p align=\"right\">" + code + "</p>";
					break;
				case "F":
					var code = code;
					break;
			}
			parent.tinyMCE.execCommand('mceInsertContent',false,code);
			return true;
		} catch(e) { }
		return true;
	}

	function insert_file_mul(num) {
		var oSelect = document.forms[0].fileList;
		var count = 0;
		var prefix = '';
		for (var i = 0; i < oSelect.options.length; i++) {
			if (oSelect.options[i].selected == true) {
				var value = oSelect.options[i].value.split("|");
				var width = value[1];
				var height = value[2];
				var fileName=value[0];

				if(width>0 && height>0 && (new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(fileName)) )
				{
					if(width > contentWidth / num) {
						height = parseInt(height * (contentWidth / num / width));
						width = contentWidth / num;
					}
					var src = "../attachments/"+ fileName;

					value[1] = "width=\"" + width + "\" height=\"" + height + "\" alt=\"open_img('" + src + "')\"";
					var code = "<img src=\"" + src + "\" " + value[1] + "/>";
				
					prefix = prefix + '^' + code;
					count ++;
				} 				
			}
		}

		if (count != num) {
			if (num==2) {
				alert("<?=$strPlsSelectFile2?>");
			} else {
				alert("<?=$strPlsSelectFile3?>");
			}
			return false;
		}

		var imageinfo = prefix.split("^");
		try {
			var code = "<p align=\"center\"><table cellspacing=\"5\"><tr>";
			for (var j = 1; j < imageinfo.length; j++) {
				code = code + "<td>" + imageinfo[j] + "</td>";
			}
			code = code + "</tr></table></p>";
			parent.tinyMCE.execCommand('mceInsertContent',false,code);
			return true;
		} catch(e) { }

		return true;
	}

	function insert_img_play()
	{
		try
		{
			var oSelect = document.forms[0].fileList;
			if (oSelect.selectedIndex < 0) {
				alert("<?=$strPlsSelectFile?>\t");
				return false;
			}
			
			var fileList = '';
			for (var i = 0; i<oSelect.length; i++) {
				if (!oSelect.options[i].selected) continue;
				var value = oSelect.options[i].value.split("|");
				file=value[0];
				file_id=value[3];
				if(new RegExp("\\.(gif|jpe?g|png)$", "gi").exec(file))
					fileList += file_id+'|';
			}
			if(fileList == '') {
				alert("<?=$strInsertImgOnly?>");
				return false;
			}
			fileList = fileList.substr(0,fileList.length-1);

			try {
				var code ='<p align="center"><img src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + (parent.tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" width="400" height="300" border="0" class="mceItemGallery" alt="' + fileList + ',400,300"/></p>';

				parent.tinyMCE.execCommand('mceInsertContent',false,code);
				return true;
			} catch(e) { }
			return true;
		}
		catch(e)
		{
			return false;
		}
	}

-->
</script>
<?
	//保存数据
	if ($action=="save") {
		if(count($_FILES)==1) {
			$attachment_file=$_FILES["attfile"]["tmp_name"];
			$path="../attachments/month_".date("Ym");
			$fileName=$_FILES["attfile"]["name"];
			$fileSize=$_FILES["attfile"]["size"];
			$info=$fileName." (".formatFileSize($fileSize).")";
			
			if (!checkFileSize($fileSize)) {
				print_message($strAttachmentsError1.formatFileSize($cfg_upload_size));
			} else {
				$attachment=upload_file($attachment_file,$fileName,$path);
				if ($attachment=="") {
					print_message($strAttachmentsError);
				} else {
					$value="month_".date("Ym")."/".$attachment;
					
					do_filter("f2_attach","../attachments/".$value);

					//写进数据库
					$fileType=getFileType($fileName);
					if($imageAtt=@getimagesize($path."/".$attachment)){
						$fileWidth=$imageAtt[0];
						$fileHeight=$imageAtt[1];
					}else{
						$fileWidth=0;
						$fileHeight=0;
					}
					
					$rsexits=getFieldValue($DBPrefix."attachments","attTitle='".$fileName."' and fileType='".$fileType."' and fileSize='".$fileSize."'","name");
					if ($rsexits==""){
						$sql="INSERT INTO ".$DBPrefix."attachments(name,attTitle,fileType,fileSize,fileWidth,fileHeight,postTime) VALUES ('$value','$fileName','$fileType','$fileSize','$fileWidth','$fileHeight','".time()."')";			
						$DMC->fetchArray($DMC->query($sql));
					}else{
						print_message($strDataExists);
					}

					$action="";
				}
			}
		}
	}

	if ($action=="delete"){
		$stritem="";
		$fileListNew=$_POST['fileList'];
		for ($i=0;$i<count($fileListNew);$i++) {
			$arrname=explode("|",$fileListNew[$i]);
			$name=$arrname[0];

			//刪除文件
			$curName="../attachments/".$name;
			if (file_exists($curName)){
				unlink($curName);
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

	//查询数据库
	if ($action=="") {
		$where=($mark_id=="")?"logId=0":"logId='$mark_id' or logId='0'";
		$sql="select * from ".$DBPrefix."attachments where $where order by postTime";
		$query_result=$DMC->query($sql);
	}

?>

<form action="" method="post" name="seekform" enctype="multipart/form-data">
  <TABLE cellSpacing=0 cellPadding=3 width="100%" align=center border=1 class="blogeditbox" bordercolordark=#ffffff bordercolorlight=#B3B3B3>
    <tr>
      <td width="130" valign="center" align="center" rowspan="2">
        <div id="previewSelected" style="width:160px; height:120px; background:#FFFFFF">
          <table width="100%" height="100%" valign="center" align="center">
            <tr>
              <td valign="middle" align="center">
                <?=$strBrowse?>
              </td>
            </tr>
          </table>
        </div>
      </td>
      <td valign="top" align="center" width="350">
        <select size="7" name="fileList[]" id="fileList" multiple="multiple" style="width:350px;" onChange="selectAttachment();">
          <? while($fa = $DMC->fetchArray($query_result)){ ?>
          <option value="<?=$fa['name']."|".$fa['fileWidth']."|".$fa['fileHeight']."|".$fa['id']?>">
          <?
				$imgSize=($fa['fileWidth']>0)?$fa['fileWidth']."X".$fa['fileHeight']." / ":"";
				echo $fa['attTitle']." (".$imgSize.formatFileSize($fa['fileSize']).")";
			 ?>
          </option>
          <? } ?>
        </select>
      </td>
      <td width="110" align="center" valign="top">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="33" height="28" align="center" valign="top"> <img src="images/content/AlignLeft.gif" onClick="insert_file('L')" alt="<?=$strSelFilLeft?>"/> </td>
            <td width="33" align="center" valign="top"> <img src="images/content/AlignCenter.gif" onClick="insert_file('C')" alt="<?=$strSelFilCenter?>"/> </td>
            <td width="33" align="center" valign="top"> <img src="images/content/AlignRight.gif" onClick="insert_file('R')" alt="<?=$strSelFilRight?>"/> </td>
          </tr>
          <tr>
            <td colspan="3" background="images/content/dotted_attach.gif"></td>
          </tr>
          <tr>
            <td height="31" align="center"> <img src="images/content/Align2Center.gif" onClick="insert_file_mul(2)" alt="<?=$strSelFilCenter2?>"/> </td>
            <td align="center"> <img src="images/content/Align3Center.gif" onClick="insert_file_mul(3)"alt="<?=$strSelFilCenter3?>"/> </td>
            <td align="center"> <img src="images/content/gallery.gif" onClick="insert_img_play()" alt="<?=$strImgPlay?>"/> 
          </tr>
          <tr>
            <td colspan="3" background="images/content/dotted_attach.gif"></td>
          </tr>
          <tr>
            <td height="31" align="center"> <img src="images/content/AlignFree.gif" onclick="insert_file('F')" alt="<?=$strSelFilFree?>"/> </td>
            <td align="center"> <img src="images/content/attachDown.gif" onClick="Javascript:onclick_download()" alt="<?=$strDownload?>"/> </td>
            <td align="center"> <img src="images/content/attachDel.gif" onClick="Javascript:onclick_delete(this.form)" alt="<?=$strDelete?>"/> </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <?=$strAttType."<font color=red>".$cfg_upload_file."</font><br>".$strAttachmentsError1."<font color=red>".formatFileSize($cfg_upload_size)."</font><br>";?>
        <input name="attfile" class="filebox" type="file" style="font-size: 9pt; height:24px" onchange="Javascript:onclick_update(this.form)" size="50"/>
      </td>
    </tr>
  </table>
</form>

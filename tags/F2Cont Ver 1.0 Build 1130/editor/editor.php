<?php
# 禁止直接访问该页面
if (!defined('IN_F2BLOG')) die ('Access Denied.');

$logContent=str_replace("&","&amp;",$logContent);
?>

<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="../editor/<?php echo ($settingInfo['fastEditor']=="1")?"tiny_mce_gzip.php":"tiny_mce.js"?>"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		elements : "logContent",
		theme : "advanced",
		language : "<?php echo $settingInfo['language']?>",
		plugins : "<?php echo $settingInfo['editPluginName']?>,inlinepopups",
		theme_advanced_buttons1 : "<?php echo $settingInfo['editPluginButton1']?>",
		theme_advanced_buttons2 : "<?php echo $settingInfo['editPluginButton2']?>",
		theme_advanced_buttons3 : "<?php echo $settingInfo['editPluginButton3']?>",

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		convert_urls : false,
		force_br_newlines : true,
		handle_event_callback : "editor_events",
		file_browser_callback : "fileBrowserCallBack"
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}

	function editor_events(e) {
		if (e.type == 'keydown' && e.keyCode == 9 && document.all) {
			e.returnValue = false;
			e.cancelBubble = true;
			tinyMCE.execCommand("mceInsertContent", false, "&nbsp;&nbsp;&nbsp;");
			return false;
		}
		return true; // Continue handling
	}

	function onclick_update(form,act) {	
		if (act=="preview"){
			if (isNull(form.logTitle, '<?php echo $strErrNull?>')) return false;
			if (isNull(form.cateId, '<?php echo $strErrNull?>')) return false;
			if (tinyMCE.getContent("mce_editor_0")==""){
				alert("<?php echo $strErrNull?>");
				return false;
			}
			form.target = "_blank";
			form.action = "preview.php";
			form.submit();
		}else if (act=="save"){
			if (isNull(form.logTitle, '<?php echo $strErrNull?>')) return false;
			if (isNull(form.cateId, '<?php echo $strErrNull?>')) return false;
			if (tinyMCE.getContent("mce_editor_0")==""){
				alert("<?php echo $strErrNull?>");
				return false;
			}
			form.preview.disabled = true;
			form.save.disabled = true;
			form.reback.disabled = true;
			form.target = window.name;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=\""?>+act;
			form.submit();
		}else{
			form.target = window.name;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=\""?>+act;
			form.submit();
		}
	}

	function AddText(codetext){
		tinyMCE.execInstanceCommand('logContent','mceInsertContent',false,codetext,true);
	}
</script>
<tr>
  <td colspan="4">					
	<textarea id="logContent" name="logContent" rows="20" cols="80" style="width: 100%"><?php echo $logContent?></textarea>
  </td>
</tr>

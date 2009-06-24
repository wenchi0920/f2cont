function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertEmotion(file_name) {

	var html = '<img src="../editor/plugins/emotions/images/' + file_name + '" mce_src="../editor/plugins/emotions/images/' + file_name + '" border="0" />';
	
	tinyMCE.execCommand('mceInsertContent', false, html);
	tinyMCEPopup.close();
}

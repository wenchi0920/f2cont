/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.2 $
 * $Date: 2006/08/30 16:29:38 $
 *
 * @author Joesen
 * @copyright Copyright ?2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('emotions', 'en,zh_cn,zh_tw');

// Plucin static class
var TinyMCE_EmotionsPlugin = {
	getInfo : function() {
		return {
			longname : 'Emotions',
			author : 'Joesen',
			authorurl : 'http://joesen.f2blog.com',
			infourl : 'http://joesen.f2blog.com/index.php?load=read&id=288',
			version : '1.1'
		};
	},

	/**
	 * Returns the HTML contents of the emotions control.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "emotions":
				return tinyMCE.getButtonHTML(cn, 'lang_emotions_desc', '{$pluginurl}/images/emotions.gif', 'mceEmotion');
		}

		return "";
	},

	/**
	 * Executes the mceEmotion command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceEmotion":
				var template = new Array();

				template['file'] = '../../plugins/emotions/emotions.htm'; // Relative to theme
				template['width'] = 550;
				template['height'] = 350;

				// Language specific width and height addons
				template['width'] += tinyMCE.getLang('lang_emotions_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_emotions_delta_height', 0);

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});

				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	cleanup : function(type, content) {
		switch (type) {
			case "insert_to_editor":
				var reg = new RegExp('admin/../editor/plugins/emotions/images/', "ig");
				content = content.replace(reg,'editor/plugins/emotions/images/');
				break;

			case "get_from_editor":
				var reg = new RegExp('admin/../editor/plugins/emotions/images/', "ig");
				content = content.replace(reg,'editor/plugins/emotions/images/');
				break;
		}

		// Pass through to next handler in chain
		return content;
	}
};

// Register plugin
tinyMCE.addPlugin('emotions', TinyMCE_EmotionsPlugin);

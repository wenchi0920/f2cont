/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.0 $
 * $Date: 7/7/2006 $
 *
 * @author Joesen(http://joesen.f2blog.com)
 * @copyright Copyright ?2004-2006, Wuvist, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('f2music', 'en,zh_cn,zh_tw');

var TinyMCE_f2musicPlugin = {
	getInfo : function() {
		return {
			longname : 'f2music',
			author : 'Joesen',
			authorurl : 'http://joesen.f2blog.com',
			infourl : 'http://joesen.f2blog.com',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		if (!tinyMCE.settings['f2music_skip_plugin_css'])
			tinyMCE.importCSS(inst.getDoc(), tinyMCE.baseURL + "/plugins/f2music/css/content.css");
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "f2_music":
				return tinyMCE.getButtonHTML(cn, 'lang_Music_desc', '{$pluginurl}/images/Music.gif', 'mceMusic');
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceMusic":
				var name = "", swff2desc = "", swffile = "", swfwidth = "", swfheight = "", action = "insert";
				var template = new Array();
				var inst = tinyMCE.getInstanceById(editor_id);
				var focusElm = inst.getFocusElement();

				template['file']   = '../../plugins/f2music/Music.htm'; // Relative to theme
				template['width']  = 430;
				template['height'] = 175;

				template['width'] += tinyMCE.getLang('lang_f2music_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_f2music_delta_height', 0);

				// Is selection a image
				if (focusElm != null && focusElm.nodeName.toLowerCase() == "img") {
					name = tinyMCE.getAttrib(focusElm, 'class');

					if (name.indexOf('mceItemMusic') == -1) // Not a Music
						return true;

					// Get rest of Music items
					swffile = tinyMCE.getAttrib(focusElm, 'alt');

					if (tinyMCE.getParam('convert_urls'))
						swffile = eval(tinyMCE.settings['urlconverter_callback'] + "(swffile, null, true);");
					
					swff2desc = tinyMCE.getAttrib(focusElm, 'title');
					swfwidth = tinyMCE.getAttrib(focusElm, 'width');
					swfheight = tinyMCE.getAttrib(focusElm, 'height');
					action = "update";
				}

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes", swffile : swffile, swff2desc : swff2desc, swfwidth : swfwidth, swfheight : swfheight, action : action});
			return true;
	   }

	   // Pass to next handler in chain
	   return false;
	},

	cleanup : function(type, content) {
		switch (type) {
			case "insert_to_editor_dom":
				if (tinyMCE.getParam('convert_urls')) {
					var imgs = content.getElementsByTagName("img");
					for (var i=0; i<imgs.length; i++) {
						if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemMusic") {
							var src = tinyMCE.getAttrib(imgs[i], "alt");
							var desc = tinyMCE.getAttrib(imgs[i], "title");

							if (tinyMCE.getParam('convert_urls'))
								src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");

							imgs[i].setAttribute('alt', src);
							imgs[i].setAttribute('title', desc);
						}
					}
				}
				break;

			case "get_from_editor_dom":
				var imgs = content.getElementsByTagName("img");
				for (var i=0; i<imgs.length; i++) {
					if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemMusic") {
						var src = tinyMCE.getAttrib(imgs[i], "alt");
						var desc = tinyMCE.getAttrib(imgs[i], "title");

						if (tinyMCE.getParam('convert_urls'))
							src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");

						imgs[i].setAttribute('alt', src);
						imgs[i].setAttribute('title', desc);
					}
				}
				break;

			case "insert_to_editor":
				var arrMusicDesc=content.match(/<!--musicBegin-->(.*?\<!--musicEnd-->)/g);
				var curMusic='';
				var curDesc='';
				if (arrMusicDesc!=null){
					for (var i=0;i<arrMusicDesc.length;i++){
						curMusic=arrMusicDesc[i];
						curMusic=curMusic.replace("<!--musicBegin-->","");
						curMusic=curMusic.replace("<!--musicEnd-->","");
						curMArr=curMusic.match(/[^\|]+/g);
							
						curDesc = '<img width="' + curMArr[2] + '" height="' + curMArr[3] + '"';
						curDesc += ' src="' + (tinyMCE.getParam("theme_href") + '/images/spacer.gif') + '" title="' + curMArr[1] + '"';
						curDesc += ' alt="' + curMArr[0] + '" class="mceItemMusic" />';
						content = content.replace('<!--musicBegin-->'+curMusic+'<!--musicEnd-->',curDesc);
					}
				}

				break;
			case "get_from_editor":
				var startPos = -1;
				while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
					var endPos = content.indexOf('/>', startPos);
					var attribs = TinyMCE_f2musicPlugin._parseAttributes(content.substring(startPos + 4, endPos));

					// Is not Music, skip it
					if (attribs['class'] != "mceItemMusic")
						continue;

					endPos += 2;

					var embedHTML = '';
					embedHTML += '<!--musicBegin-->' + attribs["alt"] + '|' +  attribs["title"] + '|' + attribs["width"] + '|' + attribs["height"] + '<!--musicEnd-->';

					// Insert embed/object chunk
					chunkBefore = content.substring(0, startPos);
					chunkAfter = content.substring(endPos);
					content = chunkBefore + embedHTML + chunkAfter;
				}
				break;
		}

		// Pass through to next handler in chain
		return content;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (node == null)
			return;

		do {
			if (node.nodeName == "IMG" && tinyMCE.getAttrib(node, 'class').indexOf('mceItemMusic') == 0) {
				tinyMCE.switchClass(editor_id + '_f2_music', 'mceButtonSelected');
				return true;
			}
		} while ((node = node.parentNode));

		tinyMCE.switchClass(editor_id + '_f2_music', 'mceButtonNormal');

		return true;
	},

	// Private plugin internal functions

	_parseAttributes : function(attribute_string) {
		var attributeName = "";
		var attributeValue = "";
		var withInName;
		var withInValue;
		var attributes = new Array();
		var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');

		if (attribute_string == null || attribute_string.length < 2)
			return null;

		withInName = withInValue = false;

		for (var i=0; i<attribute_string.length; i++) {
			var chr = attribute_string.charAt(i);

			if ((chr == '"' || chr == "'") && !withInValue)
				withInValue = true;
			else if ((chr == '"' || chr == "'") && withInValue) {
				withInValue = false;

				var pos = attributeName.lastIndexOf(' ');
				if (pos != -1)
					attributeName = attributeName.substring(pos+1);

				attributes[attributeName.toLowerCase()] = attributeValue.substring(1);

				attributeName = "";
				attributeValue = "";
			} else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
				withInName = true;

			if (chr == '=' && withInName)
				withInName = false;

			if (withInName)
				attributeName += chr;

			if (withInValue)
				attributeValue += chr;
		}

		return attributes;
	}
};

tinyMCE.addPlugin("f2music", TinyMCE_f2musicPlugin);

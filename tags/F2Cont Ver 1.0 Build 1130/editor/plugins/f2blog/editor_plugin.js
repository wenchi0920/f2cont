/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('f2blog', 'en,zh_cn,zh_tw');

var TinyMCE_f2blogPlugin = {
	getInfo : function() {
		return {
			longname : 'F2Blog Plugin',
			author : 'Joesen',
			authorurl : 'http://joesen.f2blog.com',
			infourl : 'http://joesen.f2blog.com',
			version : '1.0'
		};
	},

	getControlHTML : function(control_name) {
		switch (control_name) {
			case "f2_hide":
				return tinyMCE.getButtonHTML(control_name, 'lang_f2blog_hide_button', '{$pluginurl}/images/hide.gif', 'f2Hide');
			case "f2_more":
				return tinyMCE.getButtonHTML(control_name, 'lang_f2blog_more_button', '{$pluginurl}/images/more.gif', 'f2More');
			case "f2_page":
				return tinyMCE.getButtonHTML(control_name, 'lang_f2blog_page_button', '{$pluginurl}/images/page.gif', 'f2Page');
			case "f2_quote":
				return tinyMCE.getButtonHTML(control_name, 'lang_f2blog_quote_button', '{$pluginurl}/images/quote.gif', 'f2Quote');
			case "f2_help":
				return tinyMCE.getButtonHTML(control_name, 'lang_help_button_title', '{$pluginurl}/images/help.gif', 'f2Help');
		}
		return '';
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		var inst = tinyMCE.getInstanceById(editor_id);
		var focusElm = inst.getFocusElement();
		var doc = inst.getDoc();

		function getAttrib(elm, name) {
			return elm.getAttribute(name) ? elm.getAttribute(name) : "";
		}

		// Handle commands
		switch (command) {
			case "f2Hide":
				var flag = "";
				var template = new Array();
				var altMore = tinyMCE.getLang('lang_f2blog_hide_alt');

				html = '<div id="MoreLess" class="mceItemMoreLess">' + inst.selection.getSelectedHTML() + '<!--hideEnd--></div>';
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, html);
				tinyMCE.selectedInstance.repaint();
				return true;
			case "f2Quote":
				var flag = "";
				var template = new Array();
				var altMore = tinyMCE.getLang('lang_f2blog_quote_alt');

				html = '<div style="background-color: #F9FBFC;border: 1px solid #C3CED9;margin: 0;margin-bottom: 5px;width: auto;height: auto;overflow : auto;text-align: left;font-family: "Courier New", Courier, monospace;font-size: 12px;">' + inst.selection.getSelectedHTML() + '</div>';
				//html = '<blockquote style="MARGIN-RIGHT: 0px">' + inst.selection.getSelectedHTML() + '</blockquote>'; 
				// blockquote {border-left:3px solid #BEDCFF;margin:20px;padding-left:10px;}

				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, html);
				tinyMCE.selectedInstance.repaint();
				return true;
			case "f2More":
				var flag = "";
				var template = new Array();
				var altMore = tinyMCE.getLang('lang_f2blog_more_alt');

				// Is selection a image
				if (focusElm != null && focusElm.nodeName.toLowerCase() == "img") {
					flag = getAttrib(focusElm, 'class');

					if (flag != 'mce_plugin_f2blog_more') // Not a f2blog
						return true;

					action = "update";
				}

				html = ''
					+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
					+ ' width="100%" height="10px" '
					+ 'alt="'+altMore+'" title="'+altMore+'" class="mce_plugin_f2blog_more" name="mce_plugin_f2blog_more" />';
				tinyMCE.execInstanceCommand(editor_id, 'mceInsertContent', false, html);
				tinyMCE.selectedInstance.repaint();
				return true;

			case "f2Page":
				var flag = "";
				var template = new Array();
				var altPage = tinyMCE.getLang('lang_f2blog_more_alt');

				// Is selection a image
				if (focusElm != null && focusElm.nodeName.toLowerCase() == "img") {
					flag = getAttrib(focusElm, 'name');

					if (flag != 'mce_plugin_f2blog_page') // Not a f2blog
						return true;

					action = "update";
				}

				html = ''
					+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
					+ ' width="100%" height="10px" '
					+ 'alt="'+altPage+'" title="'+altPage+'" class="mce_plugin_f2blog_page" name="mce_plugin_f2blog_page" />';
				tinyMCE.execCommand("mceInsertContent",true,html);
				tinyMCE.selectedInstance.repaint();
				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	cleanup : function(type, content) {
		switch (type) {
			case "insert_to_editor":
				var startPos = 0;
				var altMore = tinyMCE.getLang('lang_f2blog_more_alt');
				var altPage = tinyMCE.getLang('lang_f2blog_page_alt');
				var altHide = tinyMCE.getLang('lang_f2blog_hide_alt');

				var reg = new RegExp('<!--hideBegin-->', "ig");
				content = content.replace(reg,'<div id="MoreLess" class="mceItemMoreLess">');
				reg = new RegExp('<!--hideEnd-->', "ig");
				content = content.replace(reg,'<!--hideEnd--></div>');

				//Gallery
				var arrDesc=content.match(/(<!--galleryBegin-->.*?\<!--galleryEnd-->)/g);
				var curInfo='';
				var curDesc='';
				if (arrDesc!=null){
					for (var i=0;i<arrDesc.length;i++){
						curInfo=arrDesc[i];
						curInfo=curInfo.replace("<!--galleryEnd-->","");
						curInfo=curInfo.replace("<!--galleryBegin-->","");
							
						curDesc = '<img width="400" height="300"';
						curDesc += ' src="' + (tinyMCE.getParam("theme_href") + '/images/spacer.gif') + '" title="Gallery"';
						curDesc += ' alt="' + curInfo + '" class="mceItemGallery" />';
						content = content.replace('<!--galleryBegin-->'+curInfo+'<!--galleryEnd-->',curDesc);
					}
				}

				//File
				var arrDesc=content.match(/(<!--fileBegin-->.*?\<!--fileEnd-->)/g);
				var curInfo='';
				var curDesc='';
				if (arrDesc!=null){
					for (var i=0;i<arrDesc.length;i++){
						curInfo=arrDesc[i];
						curInfo=curInfo.replace("<!--fileEnd-->","");
						curInfo=curInfo.replace("<!--fileBegin-->","");
							
						curDesc = '<img width="80" height="25"';
						curDesc += ' src="' + (tinyMCE.getParam("theme_href") + '/images/spacer.gif') + '" title="File"';
						curDesc += ' alt="' + curInfo + '" class="mceItemFile" />';
						content = content.replace('<!--fileBegin-->'+curInfo+'<!--fileEnd-->',curDesc);
					}
				}

				//Member File
				var arrDesc=content.match(/(<!--mfileBegin-->.*?\<!--mfileEnd-->)/g);
				var curInfo='';
				var curDesc='';
				if (arrDesc!=null){
					for (var i=0;i<arrDesc.length;i++){
						curInfo=arrDesc[i];
						curInfo=curInfo.replace("<!--mfileEnd-->","");
						curInfo=curInfo.replace("<!--mfileBegin-->","");
							
						curDesc = '<img width="50" height="25"';
						curDesc += ' src="' + (tinyMCE.getParam("theme_href") + '/images/spacer.gif') + '" title="Member File"';
						curDesc += ' alt="' + curInfo + '" class="mceItemMFile" />';
						content = content.replace('<!--mfileBegin-->'+curInfo+'<!--mfileEnd-->',curDesc);
					}
				}

				var startPos = 0;
				// Parse all <!--more--> tags and replace them with images
				while ((startPos = content.indexOf('<!--more-->', startPos)) != -1) {
					// Insert image
					var contentAfter = content.substring(startPos + 11);
					content = content.substring(0, startPos);
					content += '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" ';
					content += ' width="100%" height="10px" ';
					content += 'alt="'+altMore+'" title="'+altMore+'" class="mce_plugin_f2blog_more" name="mce_plugin_f2blog_more" />';
					content += contentAfter;

					startPos++;
				}

				var startPos = 0;
				// Parse all <!--page--> tags and replace them with images
				while ((startPos = content.indexOf('<!--nextpage-->', startPos)) != -1) {
					// Insert image
					var contentAfter = content.substring(startPos + 15);
					content = content.substring(0, startPos);
					content += '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" ';
					content += ' width="100%" height="10px" ';
					content += 'alt="'+altPage+'" title="'+altPage+'" class="mce_plugin_f2blog_page" name="mce_plugin_f2blog_page" />';
					content += contentAfter;

					startPos++;
				}

				// Look for \n in <pre>, replace with <br>
				var startPos = -1;
				while ((startPos = content.indexOf('<pre', startPos+1)) != -1) {
					var endPos = content.indexOf('</pre>', startPos+1);
					var innerPos = content.indexOf('>', startPos+1);
					var chunkBefore = content.substring(0, innerPos);
					var chunkAfter = content.substring(endPos);
					
					var innards = content.substring(innerPos, endPos);
					innards = innards.replace(/\n/g, '<br />');
					content = chunkBefore + innards + chunkAfter;
				}

				break;

			case "get_from_editor":
				//Hide
				var reg = new RegExp('<div id="MoreLess" class="mceItemMoreLess">', "ig");
				content = content.replace(reg,'<!--hideBegin-->');
				reg = new RegExp('<!--hideEnd--></div>', "ig");
				content = content.replace(reg,'<!--hideEnd-->');

				// Parse all img tags and replace them with <!--more-->
				var startPos = -1;
				while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
					var endPos = content.indexOf('/>', startPos);
					var attribs = this._parseAttributes(content.substring(startPos + 4, endPos));
					
					//Gallery
					if (attribs['class'] == "mceitemgallery") {
						endPos += 2;
						var embedHTML = '<!--galleryBegin-->' + attribs["alt"] + '<!--galleryEnd-->';
						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}

					//File
					if (attribs['class'] == "mceitemfile") {
						endPos += 2;
						var embedHTML = '<!--fileBegin-->' + attribs["alt"] + '<!--fileEnd-->';

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}

					//Member File
					if (attribs['class'] == "mceitemmfile") {
						endPos += 2;
						var embedHTML = '<!--mfileBegin-->' + attribs["alt"] + '<!--mfileEnd-->';

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}

					if (attribs['class'] == "mce_plugin_f2blog_more" || attribs['name'] == "mce_plugin_f2blog_more") {
						endPos += 2;

						var embedHTML = '<!--more-->';

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}
					if (attribs['class'] == "mce_plugin_f2blog_page" || attribs['name'] == "mce_plugin_f2blog_page") {
						endPos += 2;

						var embedHTML = '<!--nextpage-->';

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}
				}

				// Remove normal line breaks
				content = content.replace(/\n|\r/g, ' ');

				// Look for <br> in <pre>, replace with \n
				var startPos = -1;
				while ((startPos = content.indexOf('<pre', startPos+1)) != -1) {
					var endPos = content.indexOf('</pre>', startPos+1);
					var innerPos = content.indexOf('>', startPos+1);
					var chunkBefore = content.substring(0, innerPos);
					var chunkAfter = content.substring(endPos);
					
					var innards = content.substring(innerPos, endPos);
					innards = innards.replace(new RegExp('<br\\s?/?>', 'g'), '\n');
					innards = innards.replace(new RegExp('\\s$', ''), '');
					content = chunkBefore + innards + chunkAfter;
				}

				// Remove anonymous, empty paragraphs.
				content = content.replace(new RegExp('<p>(\\s|&nbsp;)*</p>', 'mg'), '');
	
				// Handle table badness.
				content = content.replace(new RegExp('<(table( [^>]*)?)>.*?<((tr|thead)( [^>]*)?)>', 'mg'), '<$1><$3>');
				content = content.replace(new RegExp('<(tr|thead|tfoot)>.*?<((td|th)( [^>]*)?)>', 'mg'), '<$1><$2>');
				content = content.replace(new RegExp('</(td|th)>.*?<(td( [^>]*)?|th( [^>]*)?|/tr|/thead|/tfoot)>', 'mg'), '</$1><$2>');
				content = content.replace(new RegExp('</tr>.*?<(tr|/table)>', 'mg'), '</tr><$1>');
				content = content.replace(new RegExp('<(/?(table|tbody|tr|th|td)[^>]*)>(\\s*|(<br ?/?>)*)*', 'g'), '<$1>');
	
				// Pretty it up for the source editor.
				var blocklist = 'blockquote|ul|table|thead|tr|th|td|h\\d|pre|p';
				content = content.replace(new RegExp('\\s*</('+blocklist+')>\\s*', 'mg'), '</$1>\n');
				content = content.replace(new RegExp('\\s*<(('+blocklist+')[^>]*)>\\s*', 'mg'), '\n<$1>');
				content = content.replace(new RegExp('<((/?tr|/?thead|/?tfoot)( [^>]*)?)>', 'g'), '\t<$1>');
				content = content.replace(new RegExp('<((td|th)( [^>]*)?)>', 'g'), '\t\t<$1>');
				content = content.replace(new RegExp('\\s*<br ?/?>\\s*', 'mg'), '<br />\n');
				content = content.replace(new RegExp('^\\s*', ''), '');
				content = content.replace(new RegExp('\\s*$', ''), '');

				break;
		}

		// Pass through to next handler in chain
		return content;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {

		tinyMCE.switchClass(editor_id + '_f2_more', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_f2_page', 'mceButtonNormal');

		if (node == null)
			return;

		do {
			if (node.nodeName.toLowerCase() == "img" && tinyMCE.getAttrib(node, 'class').indexOf('mce_plugin_f2blog_more') == 0)
				tinyMCE.switchClass(editor_id + '_f2_more', 'mceButtonSelected');
			if (node.nodeName.toLowerCase() == "img" && tinyMCE.getAttrib(node, 'class').indexOf('mce_plugin_f2blog_page') == 0)
				tinyMCE.switchClass(editor_id + '_f2_page', 'mceButtonSelected');
		} while ((node = node.parentNode));

		return true;
	},

	saveCallback : function(el, content, body) {
		// We have a TON of cleanup to do.

		// Mark </p> if it has any attributes.
		content = content.replace(new RegExp('(<p[^>]+>.*?)</p>', 'mg'), '$1</p#>');

		// Decode the ampersands of time.
		// content = content.replace(new RegExp('&amp;', 'g'), '&');

		// Get it ready for wpautop.
		content = content.replace(new RegExp('[\\s]*<p>[\\s]*', 'mgi'), '');
		content = content.replace(new RegExp('[\\s]*</p>[\\s]*', 'mgi'), '\n\n');
		content = content.replace(new RegExp('\\n\\s*\\n\\s*\\n*', 'mgi'), '\n\n');
		content = content.replace(new RegExp('\\s*<br ?/?>\\s*', 'gi'), '\n');

		// Fix some block element newline issues
		var blocklist = 'blockquote|ul|ol|li|table|thead|tr|th|td|div|h\\d|pre';
		content = content.replace(new RegExp('\\s*<(('+blocklist+') ?[^>]*)\\s*>', 'mg'), '\n<$1>');
		content = content.replace(new RegExp('\\s*</('+blocklist+')>\\s*', 'mg'), '</$1>\n');
		content = content.replace(new RegExp('<li>', 'g'), '\t<li>');

		// Unmark special paragraph closing tags
		content = content.replace(new RegExp('</p#>', 'g'), '</p>\n');
		content = content.replace(new RegExp('\\s*(<p[^>]+>.*</p>)', 'mg'), '\n$1');

		// Trim any whitespace
		content = content.replace(new RegExp('^\\s*', ''), '');
		content = content.replace(new RegExp('\\s*$', ''), '');

		// Hope.
		return content;

	},

	_parseAttributes : function(attribute_string) {
		var attributeName = "";
		var attributeValue = "";
		var withInName;
		var withInValue;
		var attributes = new Array();
		var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');
		var titleText = tinyMCE.getLang('lang_f2blog_more');
		var titleTextPage = tinyMCE.getLang('lang_f2blog_page');

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

				attributes[attributeName.toLowerCase()] = attributeValue.substring(1).toLowerCase();

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

tinyMCE.addPlugin("f2blog", TinyMCE_f2blogPlugin);

/* This little hack protects our More and Page placeholders from the removeformat command */
tinyMCE.orgExecCommand = tinyMCE.execCommand;
tinyMCE.execCommand = function (command, user_interface, value) {
	re = this.orgExecCommand(command, user_interface, value);

	if ( command == 'removeformat' ) {
		var inst = tinyMCE.getInstanceById('mce_editor_0');
		doc = inst.getDoc();
		var imgs = doc.getElementsByTagName('img');
		for (i=0;img=imgs[i];i++)
			img.className = img.name;
	}
	return re;
};

/*tinyMCE.orgFixGeckoBaseHREFBug = tinyMCE.fixGeckoBaseHREFBug;
tinyMCE.fixGeckoBaseHREFBug = function(m, e, h) {
	if ( tinyMCE.isGecko && m == 1 )
		h = h.replace(new RegExp('<((a|img|select|area|iframe|base|input|script|embed|object|link)\\s([^>]*\\s)?)(src)\\s*=', 'gi'), '<$1 x$4=');
	else
		h = tinyMCE.orgFixGeckoBaseHREFBug(m, e, h);

	return h;
};

tinyMCE.orgStoreAwayURLs = tinyMCE.storeAwayURLs;
tinyMCE.storeAwayURLs = function(s) {
		// Remove all mce_src, mce_href and replace them with new ones
		s = s.replace(new RegExp('mce_(src)\\s*=\\s*\"[^ >\"]*\"', 'gi'), '');
		s = s.replace(new RegExp('<((a|img|select|area|iframe|base|input|script|embed|object|link)\\s([^>]*\\s)?)(src)\\s*=\\s*"([^"]*)"', 'gi'), '<$1 $4="$5" mce_$4="$5"');

		return s;
};*/

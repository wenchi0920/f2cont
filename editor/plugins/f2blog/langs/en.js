// EN lang variables

if (navigator.userAgent.indexOf('Mac OS') != -1) {
// Mac OS browsers use Ctrl to hit accesskeys
	var metaKey = 'Ctrl';
}
else {
	var metaKey = 'Alt';
}

tinyMCE.addToLang('',{
f2blog_more_button : 'Split post with More tag (' + metaKey + '+t)',
f2blog_page_button : 'Split post with Page tag',
f2blog_hide_button : 'Hide',
f2blog_quote_button : 'Quote',
f2blog_adv_button : 'Show/Hide Advanced Toolbar (' + metaKey + '+b)',
f2blog_hide_alt : 'Hide...',
f2blog_more_alt : 'More...',
f2blog_quote_alt : 'Quote box...',
f2blog_page_alt : '...page...',
help_button_title : 'Help'
});
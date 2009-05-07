// EN lang variables

if (navigator.userAgent.indexOf('Mac OS') != -1) {
// Mac OS browsers use Ctrl to hit accesskeys
	var metaKey = 'Ctrl';
}
else {
	var metaKey = 'Alt';
}

tinyMCE.addToLang('',{
f2blog_more_button : '截止 (' + metaKey + '+t)',
f2blog_page_button : '分页',
f2blog_hide_button : '隐藏',
f2blog_quote_button : '引用',
f2blog_adv_button : '查看/隐藏 (' + metaKey + '+b)',
f2blog_hide_alt : '隐藏...',
f2blog_more_alt : '更多...',
f2blog_quote_alt : '引用框...',
f2blog_page_alt : '...页...',
help_button_title : '帮助'
});
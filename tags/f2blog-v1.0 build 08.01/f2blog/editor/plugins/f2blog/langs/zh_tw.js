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
f2blog_page_button : '分頁',
f2blog_hide_button : '隱藏',
f2blog_adv_button : '查看/隱藏 (' + metaKey + '+b)',
f2blog_hide_alt : '隱藏...',
f2blog_more_alt : '更多...',
f2blog_page_alt : '...頁...',
help_button_title : '幫助'
});
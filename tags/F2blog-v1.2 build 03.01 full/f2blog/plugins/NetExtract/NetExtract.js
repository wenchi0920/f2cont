document.write(" <a href=\"javascript:location.href='http://www.google.com/bookmarks/mark?op=add&bkmk='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\"><img src='plugins/NetExtract/images/google.gif' alt='Google书签' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:location.href='http://del.icio.us/post?&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\"><img src='plugins/NetExtract/images/delicious.gif' alt='Del.icio.us' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(vivi=window.open('http://myweb.cn.yahoo.com/popadd.html?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title),'yahoo','scrollbars=no,width=720,height=420,left=75,top=20,status=no,resizable=yes'));vivi.focus();\"><img src='plugins/NetExtract/images/yahoo.gif' alt='Yahoo书签' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(vivi=window.open('http://vivi.sina.com.cn/collect/icollect.php?pid=28&title='+escape(d.title)+'&url='+escape(d.location.href)+'&desc='+escape(t),'vivi','scrollbars=no,width=480,height=480,left=75,top=20,status=no,resizable=yes'));vivi.focus();\"><img src='plugins/NetExtract/images/vivi.gif' alt='新浪ViVi' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://z.sohu.com/storeit.do?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'keyit','scrollbars=no,width=475,height=575,left=75,top=20,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/sohuz.gif' alt='搜狐网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://www.365key.com/storeit.aspx?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'keyit','scrollbars=no,width=475,height=575,left=75,top=20,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/365key.gif' alt='365Key网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(yesky=window.open('http://hot.yesky.com/dp.aspx?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t)+'&st=2','yesky','scrollbars=no,width=400,height=480,left=75,top=20,status=no,resizable=yes'));yesky.focus();\"><img src='plugins/NetExtract/images/yesky.gif' alt='天极网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(wozhai=window.open('http://www.wozhai.com/wozhai/Cento.asp#t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'wozhai','scrollbars=no,width=475,height=575,left=75,top=20,status=no,resizable=yes'));wozhai.focus();\"><img src='plugins/NetExtract/images/wozhai.gif' alt='我摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://my.poco.cn/fav/storeIt.php?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t)+'&img=http://www.h-strong.com/blog/logo.gif','keyit','scrollbars=no,width=475,height=575,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/poco.gif' alt='POCO网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://blogmark.bokee.com/jsp/key/quickaddkey.jsp?k='+encodeURI(d.title)+'&u='+encodeURI(d.location.href)+'&c='+encodeURI(t),'keyit','scrollbars=no,width=500,height=430,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/bokee.gif' alt='博采网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:u=location.href;t=document.title;void(open('http://www.YouNote.com/NoteIt.aspx?u='+escape(u)+'&t='+escape(t)+'&c='+escape(document.selection.createRange().text),'网络书签', 'toolbar=no,width=475,height=575,left=75,top=20,status=no,resizable=yes'));\"><img src='plugins/NetExtract/images/younote.gif' alt='YouNote网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:t=document.title;u=location.href;e=document.selection?(document.selection.type!='None'?document.selection.createRange().text:''):(document.getSelection?document.getSelection():'');void(open('http://bookmark.hexun.com/post.aspx?title='+escape(t)+'&url='+escape(u)+'&excerpt='+escape(e),'HexunBookmark','scrollbars=no,width=600,height=450,left=80,top=80,status=no,resizable=yes'));\"><img src='plugins/NetExtract/images/hexun.gif' alt='和讯网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(blog=window.open('http://www.bolaa.com/CommendBlog/SmallLogin.aspx?title='+escape(d.title)+'&newspath='+escape(d.location.href)+'&subtitle='+escape(t),'bolaa','width=400px,height=400px'));blog.focus();\"><img src='plugins/NetExtract/images/bolaa.gif' alt='博拉网' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(websnip=window.open('http://x.yeeyoo.com/MouseAdd.aspx?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'yeeyoo','scrollbars=no,width=475,height=450,left=280,top=50,status=no,resizable=yes'));websnip.focus();;\"><img src='plugins/NetExtract/images/yeeyoo.gif' alt='亿友响享' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:u=location.href;t=document.title;void(open('http://www.igooi.com/addnewitem.aspx?noui=yes&jump=close&url='+escape(u)+'&title='+escape(t)+'&sel='+escape(document.selection.createRange().text),'igooi', 'toolbar=no,width=400,height=480'));\"><img src='plugins/NetExtract/images/igooi.gif' alt='igooi网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://www.i2key.com/StoreIt.aspx?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'keyit','scrollbars=no,width=475,height=475,left=75,top=20,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/i2key.gif' alt='I2Key网摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(keyit=window.open('http://www.cn3.cn/user/addurl.asp?t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'keyit','scrollbars=no,width=490,height=450,left=120,top=50,status=no,resizable=yes'));keyit.focus();\"><img src='plugins/NetExtract/images/cn3.gif' alt='天下图摘' border='0'></a>&nbsp;");
document.write(" <a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(vkey=window.open('http://www.bytemen.net/vkey/AddText.aspx?site=2005&t='+escape(d.title)+'&u='+escape(d.location.href)+'&c='+escape(t),'vkey','scrollbars=no,width=475,height=575,left=75,top=20,status=no,resizable=yes'));vkey.focus();\"><img src='plugins/NetExtract/images/bytemen.gif' alt='百特门网摘' border='0'></a>&nbsp;");
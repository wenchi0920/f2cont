<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

function doMetaHeader(){
	
	
	echo "
	
	
	
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<meta http-equiv=\"Content-Language\" content=\"UTF-8\" />
<meta name=\"robots\" content=\"all\" />
<meta name=\"author\" content=\"info(at)f2cont.com\" />
<meta name=\"Copyright\" content=\"CopyRight 2008 F2Blog.com|F2Cont.com All Rights Reserved.\" />
<meta name=\"keywords\" content=\"f2blog,f2cont,\" />
<meta name=\"description\" content=\"F2Blog.Cont - 自由誌 開發部落落 - Free &amp; Freedom Blog be Continued\" />
<title>F2Blog.Cont - 自由誌 開發部落落</title>
<link rel=\"alternate\" type=\"application/rss+xml\" href=\"http://blog.f2cont.com/rss.php\" title=\"F2Blog.Cont - 自由誌 開發部落落(Rss2)\" />
<link rel=\"alternate\" type=\"application/atom+xml\" href=\"http://blog.f2cont.com/atom.php\" title=\"F2Blog.Cont - 自由誌 開發部落落(Atom)\" />
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"skins/f2cont/global.css\" type=\"text/css\" media=\"all\" />
<!--全局样式表-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"skins/f2cont/layout.css\" type=\"text/css\" media=\"all\" />
<!--层次样式表-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"skins/f2cont/typography.css\" type=\"text/css\" media=\"all\" />
<!--局部样式表-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"skins/f2cont/link.css\" type=\"text/css\" media=\"all\" />
<!--超链接样式表-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"skins/f2cont/f2blog.css\" type=\"text/css\" media=\"all\" />
<!--F2blog特有CSS-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"include/common.css\" type=\"text/css\" media=\"all\" />
<!--F2blog共用CSS-->
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"./skins/f2cont/UBB/editor.css\" type=\"text/css\" media=\"all\" />
<!--UBB样式-->
<link rel=\"icon\" href=\"attachments/9577490391.ico\" type=\"image/x-icon\" />
<link rel=\"shortcut icon\" href=\"attachments/9577490391.ico\" type=\"image/x-icon\" />
<script type=\"text/javascript\" src=\"include/common.js.php\"></script>
<script type=\"text/javascript\" src=\"editor/ubb/nicetitle.js\"></script>
<script type=\"text/javascript\" src=\"plugins/pagepost/phprpc_client.js\"></script>
<script type=\"text/javascript\" src=\"plugins/pagepost/innerhtml.js\"></script>
<script type=\"text/javascript\" src=\"plugins/pagepost/pagepost.js.php\"></script>
<link rel=\"stylesheet\" href=\"plugins/pagepost/pagepost.css\" />
<script type=\"text/javascript\" src=\"./plugins/eMule/eMule.js\"></script>
<link rel=\"stylesheet\" rev=\"stylesheet\" href=\"./plugins/eMule/eMule.css\" type=\"text/css\">
<script type=\"text/javascript\">
var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
</script>
<script type=\"text/javascript\">
try {
	var pageTracker = _gat._getTracker(\"UA-8665275-2\");
	pageTracker._trackPageview();
} catch(err) {}</script>
	
	
	
	
	
	";
	
	
	
	
}

function doHtmlHeader(){
	
	echo "
	
    <!--顶部-->
    <div id=\"header\">
        <div id=\"blogname\"> F2Blog.Cont - 自由誌 開發部落落
            <div id=\"blogTitle\"> Free &amp; Freedom Blog be Continued </div>
        </div>
        <!--顶部菜单-->
        <div id=\"menu\">
            <div id=\"Left\"></div>
            <div id=\"Right\"></div>
            <ul>
                <li class=\"menuL\"></li>
                <li><a class=\"menuA\" id=\"home\" title=\"首頁\" href=\"index.php\">首頁</a></li>
                <li><a class=\"menuA\" id=\"tags\" title=\"標籤\" href=\"tags.html\">標籤</a></li>
                <li><a class=\"menuA\" id=\"guestbook\" title=\"留言板\" href=\"guestbook.html\">留言板</a></li>
                <li><a class=\"menuA\" id=\"論壇\" title=\"論壇\" href=\"http://bbs.f2cont.com/index.html\" target=\"_blank\">論壇</a></li>
                <li><a class=\"menuA\" id=\"下載\" title=\"下載\" href=\"http://code.google.com/p/f2cont/downloads/list\" target=\"_blank\">下載</a></li>
                <li><a class=\"menuA\" id=\"links\" title=\"連結\" href=\"links.html\">連結</a></li>
                <li><a class=\"menuA\" id=\"archives\" title=\"歸檔\" href=\"archives.html\">歸檔</a></li>
                <li><a class=\"menuA\" id=\"rss\" title=\"RSS\" href=\"rss.php\">RSS</a></li>
                <li><a class=\"menuA\" id=\"admin\" title=\"管理\" href=\"admin/index.php\">管理</a></li>
                <li class=\"menuR\"></li>
            </ul>
        </div>
    </div>
	
	";
	
	
}

function doHtmlMainContent(){
	
	
	echo "
	
	
		<!--正文-->
		<div id=\"mainContent\">
            <div id=\"innermainContent\">
                <div id=\"mainContent-topimg\"></div>
                <!--主体部分-->
                <!--工具栏-->
                <div id=\"Content_ContentList\" class=\"content-width\">
                    <div class=\"pageContent\" style=\"OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right\">
                        <div class=\"page\" style=\"FLOAT: left\"> </div>
                        瀏覽模式: <a href=\"1-0.html\">普通</a> | <a href=\"1-1.html\">列表</a> </div>
                    <!--显示-->
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-5.html\" title=\"作者:风之逸&nbsp;日期:2008-11-16 00:06\"> F2.Cont Ver 1.0 Build 1130 版本发布</a> (11-16) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-5.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-5.html#tb_top\" title=\"引用\">2</a> | <span title=\"閱讀\">3440</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-47.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:44\"> F2 程序下載&nbsp;<img src=\"images/icon_top.gif\" border=\"0\" align=\"middle\" alt=\"\"/></a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-47.html#comm_top\" title=\"評論\">5</a> | <a href=\"read-47.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">510</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-39.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-04 12:14\"> F2 招聘 人員&nbsp;<img src=\"images/icon_top.gif\" border=\"0\" align=\"middle\" alt=\"\"/></a> (05-04) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-39.html#comm_top\" title=\"評論\">23</a> | <a href=\"read-39.html#tb_top\" title=\"引用\">1</a> | <span title=\"閱讀\">470</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-38.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-04 12:08\"> F2將由程序員接手&nbsp;<img src=\"images/icon_top.gif\" border=\"0\" align=\"middle\" alt=\"\"/></a> (05-04) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-38.html#comm_top\" title=\"評論\">11</a> | <a href=\"read-38.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">377</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-10.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 外部资讯的網誌\" style=\"margin:0px 2px -3px 0px\"/>[外部资讯]</a> <a href=\"read-54.html\" title=\"作者:堕落程式&nbsp;日期:2009-06-30 11:42\"> 09/06/30 G型主機異常</a> (06-30) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-54.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-54.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">26</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-53.html\" title=\"作者:堕落程式&nbsp;日期:2009-06-28 17:37\"> F2Cont 1.0 Beta 090628 8盎司非力牛 版</a> (06-28) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-53.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-53.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">48</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-52.html\" title=\"作者:堕落程式&nbsp;日期:2009-06-24 15:09\"> F2Cont 1.0 Beta 090624 6盎司非力牛 版</a> (06-24) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-52.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-52.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">80</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-10.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 外部资讯的網誌\" style=\"margin:0px 2px -3px 0px\"/>[外部资讯]</a> <a href=\"read-51.html\" title=\"作者:堕落程式&nbsp;日期:2009-06-09 22:33\"> 關於這兩天 F2Cont 連不上的原因</a> (06-09) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-51.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-51.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">156</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-1.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程序发布的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程序发布]</a> <a href=\"read-50.html\" title=\"作者:堕落程式&nbsp;日期:2009-06-01 16:12\"> F2Cont 安全行更新 20090601</a> (06-01) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-50.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-50.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">197</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-49.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-13 12:15\"> F2 開發進度</a> (05-13) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-49.html#comm_top\" title=\"評論\">2</a> | <a href=\"read-49.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">300</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-6.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 f2cont的網誌\" style=\"margin:0px 2px -3px 0px\"/>[f2cont]</a> <a href=\"read-48.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:58\"> 感謝 SAW 部落格學院 提供資源</a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-48.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-48.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">295</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-46.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:41\"> 請F2 團隊 人員 與我聯絡</a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-46.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-46.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">157</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-45.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:34\"> F2 招聘 推廣人員 數名</a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-45.html#comm_top\" title=\"評論\">4</a> | <a href=\"read-45.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">175</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-44.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:28\"> F2 招聘 美工設計人員 數名</a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-44.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-44.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">155</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-43.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-12 16:20\"> F2 招聘 測試人員 數名</a> (05-12) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-43.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-43.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">136</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-6.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 f2cont的網誌\" style=\"margin:0px 2px -3px 0px\"/>[f2cont]</a> <a href=\"read-42.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-11 00:06\"> F2 招聘 PHP 程序員(程式員) 數名</a> (05-11) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-42.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-42.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">170</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-6.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 f2cont的網誌\" style=\"margin:0px 2px -3px 0px\"/>[f2cont]</a> <a href=\"read-41.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-08 11:59\"> 感謝各位的支持</a> (05-08) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-41.html#comm_top\" title=\"評論\">2</a> | <a href=\"read-41.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">238</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-40.html\" title=\"作者:堕落程式&nbsp;日期:2009-05-04 16:54\"> F2 優化</a> (05-04) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-40.html#comm_top\" title=\"評論\">3</a> | <a href=\"read-40.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">249</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-4.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 关于F2的網誌\" style=\"margin:0px 2px -3px 0px\"/>[关于F2]</a> <a href=\"read-37.html\" title=\"作者:风之逸&nbsp;日期:2009-03-24 14:02\"> F2的终点还是起点</a> (03-24) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-37.html#comm_top\" title=\"評論\">28</a> | <a href=\"read-37.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">865</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-36.html\" title=\"作者:实验小白鼠&nbsp;日期:2009-02-28 17:20\"> MG相册 1.31 升级版 by 蘿莉 寶兒</a> (02-28) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-36.html#comm_top\" title=\"評論\">20</a> | <a href=\"read-36.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">947</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-11.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 修正补丁的網誌\" style=\"margin:0px 2px -3px 0px\"/>[修正补丁]</a> <a href=\"read-35.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-12-17 15:48\"> F2blog.Cont RSS 修正补丁 by Yuki</a> (12-17) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-35.html#comm_top\" title=\"評論\">2</a> | <a href=\"read-35.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1493</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-34.html\" title=\"作者:Enny&nbsp;日期:2008-12-10 22:06\"> 【模板】狗狗物語</a> (12-10) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-34.html#comm_top\" title=\"評論\">2</a> | <a href=\"read-34.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1495</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-33.html\" title=\"作者:风之逸&nbsp;日期:2008-12-07 12:38\"> 文章分页 - 插件支持 - 功能修复</a> (12-07) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-33.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-33.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">928</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-5.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 开发日志的網誌\" style=\"margin:0px 2px -3px 0px\"/>[开发日志]</a> <a href=\"read-32.html\" title=\"作者:风之逸&nbsp;日期:2008-12-06 22:47\"> 分页测试</a> (12-06) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-32.html#comm_top\" title=\"評論\">2</a> | <a href=\"read-32.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">823</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-31.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-12-06 00:14\"> 〖插件〗编辑区右键菜单 for F2blog TinyMCE编辑器</a> (12-06) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-31.html#comm_top\" title=\"評論\">7</a> | <a href=\"read-31.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1094</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-7.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 程式增强的網誌\" style=\"margin:0px 2px -3px 0px\"/>[程式增强]</a> <a href=\"read-30.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-12-05 17:01\"> 「编辑器」F2blog 外挂编辑器 TinyMCE 2.1.3</a> (12-05) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-30.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-30.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1688</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-29.html\" title=\"作者:剑雪·风&nbsp;日期:2008-12-03 22:59\"> 【模板】WIN7风格for f2cont.blog</a> (12-03) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-29.html#comm_top\" title=\"評論\">10</a> | <a href=\"read-29.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1834</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-11.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 修正补丁的網誌\" style=\"margin:0px 2px -3px 0px\"/>[修正补丁]</a> <a href=\"read-28.html\" title=\"作者:堕落程式&nbsp;日期:2008-12-02 09:05\"> F2.Cont Ver 1.0 Beta 1202 版本发布</a> (12-02) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-28.html#comm_top\" title=\"評論\">7</a> | <a href=\"read-28.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1557</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-11.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 修正补丁的網誌\" style=\"margin:0px 2px -3px 0px\"/>[修正补丁]</a> <a href=\"read-27.html\" title=\"作者:堕落程式&nbsp;日期:2008-12-01 22:00\"> F2.Cont Ver 1.0 Beta 1201 版本发布</a> (12-01) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-27.html#comm_top\" title=\"評論\">12</a> | <a href=\"read-27.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1449</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-8.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 疑问解答的網誌\" style=\"margin:0px 2px -3px 0px\"/>[疑问解答]</a> <a href=\"read-26.html\" title=\"作者:风之逸&nbsp;日期:2008-12-01 21:51\"> 如何解决垃圾评论的骚扰 - 内容过滤器的使用</a> (12-01) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-26.html#comm_top\" title=\"評論\">14</a> | <a href=\"read-26.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">994</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-25.html\" title=\"作者:小瓜&nbsp;日期:2008-11-30 18:35\"> 远程图片保存到本地</a> (11-30) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-25.html#comm_top\" title=\"評論\">5</a> | <a href=\"read-25.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">940</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-11.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 修正补丁的網誌\" style=\"margin:0px 2px -3px 0px\"/>[修正补丁]</a> <a href=\"read-24.html\" title=\"作者:风之逸&nbsp;日期:2008-11-30 18:15\"> 修正部分服务器环境前台管理/评论/留言无效的问题</a> (11-30) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-24.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-24.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">900</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-23.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-11-30 11:52\"> 〖插件〗CC视频插件 1.1 for F2blog</a> (11-30) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-23.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-23.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1025</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-22.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-11-29 22:45\"> 〖插件〗编辑窗口全屏插件 for F2blog TinyMCE编辑器</a> (11-29) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-22.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-22.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">920</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-3.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 插件专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[插件专栏]</a> <a href=\"read-21.html\" title=\"作者:实验小白鼠&nbsp;日期:2008-11-29 22:23\"> 〖插件〗eMule下载插件 v1.1 for F2blog TinyMCE编辑器</a> (11-29) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-21.html#comm_top\" title=\"評論\">5</a> | <a href=\"read-21.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1095</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-20.html\" title=\"作者:Enny&nbsp;日期:2008-11-28 18:07\"> 【模板】夢中的旋轉木馬</a> (11-28) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-20.html#comm_top\" title=\"評論\">5</a> | <a href=\"read-20.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1313</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-19.html\" title=\"作者:Enny&nbsp;日期:2008-11-27 18:05\"> 【模板】遇見‧咖啡館</a> (11-27) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-19.html#comm_top\" title=\"評論\">6</a> | <a href=\"read-19.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1237</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-18.html\" title=\"作者:Enny&nbsp;日期:2008-11-27 03:22\"> 【模板】快樂冬季</a> (11-27) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-18.html#comm_top\" title=\"評論\">0</a> | <a href=\"read-18.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1178</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-17.html\" title=\"作者:Enny&nbsp;日期:2008-11-27 00:20\"> 【模板】咖啡情語</a> (11-27) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-17.html#comm_top\" title=\"評論\">4</a> | <a href=\"read-17.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">995</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"Content-body\" style=\"text-align:Left\">
                        <table cellpadding=\"2\" cellspacing=\"2\" width=\"100%\">
                            <tr>
                                <td valign=\"top\"><a href=\"category-2.html\" > <img src=\"images/icons/1.gif\" border=\"0\" alt=\"查看 模板专栏的網誌\" style=\"margin:0px 2px -3px 0px\"/>[模板专栏]</a> <a href=\"read-16.html\" title=\"作者:Enny&nbsp;日期:2008-11-27 00:15\"> 【模板】憂鬱雨季</a> (11-27) </td>
                                <td valign=\"top\" width=\"60\"><nobr> <a href=\"read-16.html#comm_top\" title=\"評論\">1</a> | <a href=\"read-16.html#tb_top\" title=\"引用\">0</a> | <span title=\"閱讀\">1114</span> </nobr></td>
                            </tr>
                        </table>
                    </div>
                    <div class=\"pageContent\">
                        <div class=\"page\" style=\"float:right\">
                            <ul>
                                <li class=\"pageNumber\"><strong>1</strong>&nbsp;<a href=\"2.html\">2</a>&nbsp;<a href=\"2.html\" title=\"下一頁\">></a>&nbsp;<a href=\"2.html\" title=\"尾頁\">>|</a>&nbsp;</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id=\"mainContent-bottomimg\"></div>
            </div>
        </div>	
	
	
	
	
	
	
	";
	
	
}

function doHtmlLeftSidebar(){
	
	
	echo "
	
		<!--处理侧边栏-->
		<div id=\"sidebar\">
            <div id=\"innersidebar\">
                <div id=\"sidebar-topimg\">
                    <!--工具条顶部图象-->
                </div>
                <!--侧边栏显示内容-->
                <!--用戶面板-->
                <div class=\"sidepanel\" id=\"Side_User\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_User')\">用戶面板</h4>
                    <div class=\"Pcontent\" id=\"content_User\" style=\"display:\">
                        <div class=\"UserTable\" id=\"User_Body\"> <a href=\"login.php\" class=\"sideA\">登錄</a> <a href=\"register.php\" class=\"sideA\">用戶註冊</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--類別-->
                <div class=\"sidepanel\" id=\"Side_Category\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Category')\">類別</h4>
                    <div class=\"Pcontent\" id=\"content_Category\" style=\"display:\">
                        <div class=\"CategoryTable\" id=\"Category_Body\">
                            <script type=\"text/javascript\">
	function openCategory(category) {
		var oLevel1 = document.getElementById(\"category_\" + category);
		var oImg = oLevel1.getElementsByTagName(\"img\")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case \"isleaf\":
			return true;
			case \"closed\":
			oImg.src = \"images/tree/base/tab_opened.gif\";
			showLayer(\"category_\" + category + \"_children\");
			expanded = true;
			return true;
			case \"opened\":
			oImg.src = \"images/tree/base/tab_closed.gif\";
			hideLayer(\"category_\" + category + \"_children\");
			expanded = false;
			return true;
		}
		return false;
	}

	function showLayer(id) {
		document.getElementById(id).style.display = \"block\";
		return true;
	}

	function hideLayer(id) {
		document.getElementById(id).style.display = \"none\";
		return true;
	}
</script>
                            <div id=\"treeComponent\">
                                <div id=\"category_0\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;\"><img src=\"images/tree/base/tab_top.gif\" width=\"16\" align=\"top\" alt=\"\"/></div>
                                    <div style=\"display:inline; vertical-align:middle; margin-left:3px; padding-left:3px; cursor:pointer;\"> <a href='index.php'>所有分類 (53)</a> <span class=\"rss\"><a href='rss.php'>[RSS]</a></span></div>
                                </div>
                                <div id=\"category_1\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\" onClick=\"openCategory('1')\"><img src=\"images/tree/base/tab_opened.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"程序发布 - f2cont程序更新\">
                                        <div id=\"text_1\" style=\"display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;\"><a href='category-1.html'>程序发布		(10)</a> <span class=\"rss\"><a href='rss.php?cateID=1'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_1_children\" style=\"display:\">
                                    <div id=\"subcategory_00\" style=\"line-height: 100%\">
                                        <div style=\"display:inline;\"><img src=\"images/tree/base/navi_back_active.gif\" width=\"17\" align=\"top\" alt=\"\"/><img src=\"images/tree/base/tab_treed_end.gif\" width=\"22\" align=\"top\" alt=\"\"/></div>
                                        <div style=\"display:inline;\" title=\"修正补丁 - 问题的发现与修正\">
                                            <div id=\"text_00\" style=\"display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;\"><a href='category-11.html'>修正补丁		(4)</a> <span class=\"rss\"><a href='rss.php?cateID=11'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id=\"category_category_2\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"模板专栏 - Skins for F2\">
                                        <div id=\"text_2\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-2.html'>模板专栏	  (12)</a> <span class=\"rss\"><a href='rss.php?cateID=2'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_3\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"转换程式 - 转换现有blog使用F2这套程序\">
                                        <div id=\"text_3\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-9.html'>转换程式	  (0)</a> <span class=\"rss\"><a href='rss.php?cateID=9'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_4\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"插件专栏 - Plugins for F2\">
                                        <div id=\"text_4\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-3.html'>插件专栏	  (8)</a> <span class=\"rss\"><a href='rss.php?cateID=3'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_5\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"程式增强 - 完善功能的各式Hack\">
                                        <div id=\"text_5\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-7.html'>程式增强	  (3)</a> <span class=\"rss\"><a href='rss.php?cateID=7'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_6\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"开发日志 - F2cont 小组程序开发notes\">
                                        <div id=\"text_6\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-5.html'>开发日志	  (3)</a> <span class=\"rss\"><a href='rss.php?cateID=5'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_7\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"疑问解答 - 官方解答及程序说明，帮助文档之类\">
                                        <div id=\"text_7\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-8.html'>疑问解答	  (1)</a> <span class=\"rss\"><a href='rss.php?cateID=8'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_8\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\" onClick=\"openCategory('8')\"><img src=\"images/tree/base/tab_opened.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"关于 - Free &amp; Freedom\">
                                        <div id=\"text_8\" style=\"display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;\"><a href='category-4.html'>关于F2		(14)</a> <span class=\"rss\"><a href='rss.php?cateID=4'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_8_children\" style=\"display:\">
                                    <div id=\"subcategory_70\" style=\"line-height: 100%\">
                                        <div style=\"display:inline;\"><img src=\"images/tree/base/navi_back_active.gif\" width=\"17\" align=\"top\" alt=\"\"/><img src=\"images/tree/base/tab_treed_end.gif\" width=\"22\" align=\"top\" alt=\"\"/></div>
                                        <div style=\"display:inline;\" title=\"F2cont团队 - 让F2的理念继续下去\">
                                            <div id=\"text_70\" style=\"display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;\"><a href='category-6.html'>f2cont		(5)</a> <span class=\"rss\"><a href='rss.php?cateID=6'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id=\"category_category_9\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive_end.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"网络资讯 - 了解前沿动态，不做井底之蛙\">
                                        <div id=\"text_9\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-10.html'>外部资讯	  (2)</a> <span class=\"rss\"><a href='rss.php?cateID=10'>[RSS]</a></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--日曆-->
                <div class=\"sidepanel\" id=\"Side_Calendar\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Calendar')\">日曆</h4>
                    <div class=\"Pcontent\" id=\"content_Calendar\" style=\"display:\">
                        <div class=\"CalendarTable\" id=\"Calendar_Body\">
                            <div id=\"Calendar_Top\">
                                <div><a href=\"calendar-200906.html\" id=\"LeftB\"></a></div>
                                <div><a href=\"calendar-200908.html\" id=\"RightB\"></a></div>
                                2009 年  7 月</div>
                            <div id=\"Calendar_week\">
                                <ul class=\"Week_UL\">
                                    <li><font color=\"#ff0000\">日</font></li>
                                    <li>一</li>
                                    <li>二</li>
                                    <li>三</li>
                                    <li>四</li>
                                    <li>五</li>
                                    <li>六</li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a class=\"otherday\">28<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"初六\">初六</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">29<span style=\"dipslay:none;font-size:9px;color:\" title=\"初七\"><br />
                                        初七</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">30<span style=\"dipslay:none;font-size:9px;color:\" title=\"初八\"><br />
                                        初八</span></a></li>
                                    <li class=\"DayA\"><a>1<span style=\"dipslay:none;font-size:9px;color:\" title=\"初九\"><br />
                                        初九</span></a></li>
                                    <li class=\"DayA\"><a class=\"today\">2<span style=\"dipslay:none;font-size:9px;color:\" title=\"初十\"><br />
                                        初十</span></a></li>
                                    <li class=\"DayA\"><a>3<span style=\"dipslay:none;font-size:9px;color:\" title=\"十一\"><br />
                                        十一</span></a></li>
                                    <li class=\"DayA\"><a>4<span style=\"dipslay:none;font-size:9px;color:\" title=\"十二\"><br />
                                        十二</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>5<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"十三\">十三</span></a></li>
                                    <li class=\"DayA\"><a>6<span style=\"dipslay:none;font-size:9px;color:\" title=\"十四\"><br />
                                        十四</span></a></li>
                                    <li class=\"DayA\"><a>7<span style=\"dipslay:none;font-size:9px;color:\" title=\"十五\"><br />
                                        十五</span></a></li>
                                    <li class=\"DayA\"><a>8<span style=\"dipslay:none;font-size:9px;color:\" title=\"十六\"><br />
                                        十六</span></a></li>
                                    <li class=\"DayA\"><a>9<span style=\"dipslay:none;font-size:9px;color:\" title=\"十七\"><br />
                                        十七</span></a></li>
                                    <li class=\"DayA\"><a>10<span style=\"dipslay:none;font-size:9px;color:\" title=\"十八\"><br />
                                        十八</span></a></li>
                                    <li class=\"DayA\"><a>11<span style=\"dipslay:none;font-size:9px;color:\" title=\"十九\"><br />
                                        十九</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>12<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"二十\">二十</span></a></li>
                                    <li class=\"DayA\"><a>13<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿一\"><br />
                                        廿一</span></a></li>
                                    <li class=\"DayA\"><a>14<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿二\"><br />
                                        廿二</span></a></li>
                                    <li class=\"DayA\"><a>15<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿三\"><br />
                                        廿三</span></a></li>
                                    <li class=\"DayA\"><a>16<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿四\"><br />
                                        廿四</span></a></li>
                                    <li class=\"DayA\"><a>17<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿五\"><br />
                                        廿五</span></a></li>
                                    <li class=\"DayA\"><a>18<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿六\"><br />
                                        廿六</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>19<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"廿七\">廿七</span></a></li>
                                    <li class=\"DayA\"><a>20<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿八\"><br />
                                        廿八</span></a></li>
                                    <li class=\"DayA\"><a>21<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿九\"><br />
                                        廿九</span></a></li>
                                    <li class=\"DayA\"><a>22<span style=\"dipslay:none;font-size:9px;color:\" title=\"六月\"><br />
                                        六月</span></a></li>
                                    <li class=\"DayA\"><a>23<span style=\"dipslay:none;font-size:9px;color:\" title=\"初二\"><br />
                                        初二</span></a></li>
                                    <li class=\"DayA\"><a>24<span style=\"dipslay:none;font-size:9px;color:\" title=\"初三\"><br />
                                        初三</span></a></li>
                                    <li class=\"DayA\"><a>25<span style=\"dipslay:none;font-size:9px;color:\" title=\"初四\"><br />
                                        初四</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>26<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"初五\">初五</span></a></li>
                                    <li class=\"DayA\"><a>27<span style=\"dipslay:none;font-size:9px;color:\" title=\"初六\"><br />
                                        初六</span></a></li>
                                    <li class=\"DayA\"><a>28<span style=\"dipslay:none;font-size:9px;color:\" title=\"初七\"><br />
                                        初七</span></a></li>
                                    <li class=\"DayA\"><a>29<span style=\"dipslay:none;font-size:9px;color:\" title=\"初八\"><br />
                                        初八</span></a></li>
                                    <li class=\"DayA\"><a>30<span style=\"dipslay:none;font-size:9px;color:\" title=\"初九\"><br />
                                        初九</span></a></li>
                                    <li class=\"DayA\"><a>31<span style=\"dipslay:none;font-size:9px;color:\" title=\"初十\"><br />
                                        初十</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">1<span style=\"dipslay:none;font-size:9px;color:\" title=\"十一\"><br />
                                        十一</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--熱門標籤-->
                <div class=\"sidepanel\" id=\"Side_HotTags\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_HotTags')\">熱門標籤</h4>
                    <div class=\"Pcontent\" id=\"content_HotTags\" style=\"display:\">
                        <div class=\"HotTagsTable\" id=\"HotTags_Body\"> <a href=\"tags-F2cont.html\" style=\"color:#f60\" title=\"網誌數量: 14\">F2cont</a> <a href=\"tags-Skin.html\" style=\"color:#069\" title=\"網誌數量: 12\">Skin</a> <a href=\"tags-%E6%A8%A1%E6%9D%BF.html\" style=\"color:#069\" title=\"網誌數量: 12\">模板</a> <a href=\"tags-%E7%A8%8B%E5%BA%8F.html\" style=\"color:#690\" title=\"網誌數量: 9\">程序</a> <a href=\"tags-%E6%8F%92%E4%BB%B6.html\" style=\"color:#690\" title=\"網誌數量: 6\">插件</a> <a href=\"tags-%E5%8F%91%E5%B1%95%E5%8E%86%E5%8F%B2.html\" style=\"color:#690\" title=\"網誌數量: 6\">发展历史</a> <a href=\"tags-Plugin.html\" style=\"color:#690\" title=\"網誌數量: 6\">Plugin</a> <a href=\"tags-%E7%BB%84%E5%91%98.html\" style=\"color:#09c\" title=\"網誌數量: 5\">组员</a> <a href=\"tags-%E5%9B%A2%E9%98%9F.html\" style=\"color:#09c\" title=\"網誌數量: 5\">团队</a> <a href=\"tags-%E5%8F%91%E5%B8%83.html\" style=\"color:#09c\" title=\"網誌數量: 5\">发布</a> <a href=\"tags-TinyMCE.html\" style=\"color:#09c\" title=\"網誌數量: 4\">TinyMCE</a> <a href=\"tags-%E5%A2%9E%E5%BC%BA.html\" style=\"color:#09c\" title=\"網誌數量: 3\">增强</a> <a href=\"tags-F2blog.html\" style=\"color:#09c\" title=\"網誌數量: 2\">F2blog</a> <a href=\"tags-%E7%BC%96%E8%BE%91%E5%99%A8.html\" style=\"color:#999\" title=\"網誌數量: 1\">编辑器</a> <a href=\"tags-%E6%B5%8B%E8%AF%95.html\" style=\"color:#999\" title=\"網誌數量: 1\">测试</a> <a href=\"tags-%E6%A0%87%E9%A2%98%E6%A0%8F.html\" style=\"color:#999\" title=\"網誌數量: 1\">标题栏</a> <a href=\"tags-%E5%B0%8F%E6%97%A5%E5%8E%86.html\" style=\"color:#999\" title=\"網誌數量: 1\">小日历</a> <a href=\"tags-%E8%AF%84%E8%AE%BA.html\" style=\"color:#999\" title=\"網誌數量: 1\">评论</a> <a href=\"tags-%E7%95%99%E8%A8%80.html\" style=\"color:#999\" title=\"網誌數量: 1\">留言</a> <a href=\"tags-ver1.0.html\" style=\"color:#999\" title=\"網誌數量: 1\">ver1.0</a> <a href=\"tags-Hack.html\" style=\"color:#999\" title=\"網誌數量: 1\">Hack</a> <a href=\"tags-%E5%88%86%E9%A1%B5.html\" style=\"color:#999\" title=\"網誌數量: 1\">分页</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新網誌-->
                <div class=\"sidepanel\" id=\"Side_NewLogForPJBlog\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_NewLogForPJBlog')\">最新網誌</h4>
                    <div class=\"Pcontent\" id=\"content_NewLogForPJBlog\" style=\"display:\">
                        <div class=\"NewLogForPJBlogTable\" id=\"NewLogForPJBlog_Body\"> <a class=\"sideA\" id=\"NewLog_Link_54\" title=\"堕落程式 於 2009-06-30 11:42 發表 09/06/30 G型主機異常\" href=\"read-54.html\">堕落程式: 09/06/30 G型主</a> <a class=\"sideA\" id=\"NewLog_Link_53\" title=\"堕落程式 於 2009-06-28 17:37 發表 F2Cont 1.0 Beta 090628 8盎司非力牛 版\" href=\"read-53.html\">堕落程式: F2Cont 1.0 B</a> <a class=\"sideA\" id=\"NewLog_Link_52\" title=\"堕落程式 於 2009-06-24 15:09 發表 F2Cont 1.0 Beta 090624 6盎司非力牛 版\" href=\"read-52.html\">堕落程式: F2Cont 1.0 B</a> <a class=\"sideA\" id=\"NewLog_Link_51\" title=\"堕落程式 於 2009-06-09 22:33 發表 關於這兩天 F2Cont 連不上的原因\" href=\"read-51.html\">堕落程式: 關於這兩天 F2Cont</a> <a class=\"sideA\" id=\"NewLog_Link_50\" title=\"堕落程式 於 2009-06-01 16:12 發表 F2Cont 安全行更新 20090601\" href=\"read-50.html\">堕落程式: F2Cont 安全行更新</a> <a class=\"sideA\" id=\"NewLog_Link_49\" title=\"堕落程式 於 2009-05-13 12:15 發表 F2 開發進度\" href=\"read-49.html\">堕落程式: F2 開發進度</a> <a class=\"sideA\" id=\"NewLog_Link_48\" title=\"堕落程式 於 2009-05-12 16:58 發表 感謝 SAW 部落格學院 提供資源\" href=\"read-48.html\">堕落程式: 感謝 SAW 部落格學院</a> <a class=\"sideA\" id=\"NewLog_Link_47\" title=\"堕落程式 於 2009-05-12 16:44 發表 F2 程序下載\" href=\"read-47.html\">堕落程式: F2 程序下載</a> <a class=\"sideA\" id=\"NewLog_Link_46\" title=\"堕落程式 於 2009-05-12 16:41 發表 請F2 團隊 人員 與我聯絡\" href=\"read-46.html\">堕落程式: 請F2 團隊 人員 與我</a> <a class=\"sideA\" id=\"NewLog_Link_45\" title=\"堕落程式 於 2009-05-12 16:34 發表 F2 招聘 推廣人員 數名\" href=\"read-45.html\">堕落程式: F2 招聘 推廣人員 數</a> <a class=\"sideA\" id=\"NewLog_Link_44\" title=\"堕落程式 於 2009-05-12 16:28 發表 F2 招聘 美工設計人員 數名\" href=\"read-44.html\">堕落程式: F2 招聘 美工設計人員</a> <a class=\"sideA\" id=\"NewLog_Link_43\" title=\"堕落程式 於 2009-05-12 16:20 發表 F2 招聘 測試人員 數名\" href=\"read-43.html\">堕落程式: F2 招聘 測試人員 數</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新評論-->
                <div class=\"sidepanel\" id=\"Side_Comment\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Comment')\">最新評論</h4>
                    <div class=\"Pcontent\" id=\"content_Comment\" style=\"display:\">
                        <div class=\"CommentTable\" id=\"Comment_Body\"> <a class=\"sideA\" id=\"comments_Link_278\" title=\"Fish 於 2009-06-28 10:11 發表評論 原木兄好, 小弟用的是synology\"  href=\"read-39-2.html#book278\">Fish: 原木兄好, 小弟用的是s</a> <a class=\"sideA\" id=\"comments_Link_261\" title=\"原木 於 2009-06-16 17:21 發表評論 原木也是用 buffalo 改機來架 Debian 的，但是原木有128 RAM，哈哈 ...\"  href=\"read-39-2.html#book261\">原木: 原木也是用 buffal</a> <a class=\"sideA\" id=\"comments_Link_258\" title=\"Fish 於 2009-06-14 08:32 發表評論 終於有人願意接手了~我一向用f2blog, 但我美工差, 程式也不懂, 但我可以做測試. 因為我是用NAS(Network Attachement Storage) 來host f2blog 的, 用NAS可以測試資源試用程度, 因為NAS只有32Mb ram. 當然, 我也不介意買一個空間來做hosting 測試如果接受小弟的話, 請email 聯絡啊\"  href=\"read-39-2.html#book258\">Fish: 終於有人願意接手了~</a> <a class=\"sideA\" id=\"comments_Link_256\" title=\"寶兒 於 2009-06-10 02:41 發表評論 這是一支超好用的程式&gt;&lt;真的希望能夠看到它慢慢進步~~非常支持大大們的努力~~~&gt;&lt;\"  href=\"read-39-2.html#book256\">寶兒: 這是一支超好用的程式&g</a> <a class=\"sideA\" id=\"comments_Link_255\" title=\"新2 於 2009-06-07 00:11 發表評論 (*^__^*)...不錯的博客程式。希望可以幫忙推廣推廣。新2\"  href=\"read-39-2.html#book255\">新2: (*^__^*)...不</a> <a class=\"sideA\" id=\"comments_Link_243\" title=\"boshing 於 2009-06-04 22:58 發表評論 支持推廣\"  href=\"read-45.html#book243\">boshing: 支持推廣</a> <a class=\"sideA\" id=\"comments_Link_233\" title=\"万古小兔兔 於 2009-05-29 12:36 發表評論 不知道能不能建议，部分内容隐藏（需要密码）呢？另外还有我觉得3个日志编辑器太多了也其实没什麽用，可以把功能直接合在一起变成一个编辑器吗？另外编辑器的速度也有点慢（tiny那个，php的那个还好但是有用到html代码的话会很不方便）\"  href=\"read-38.html#book233\">万古小兔兔: 不知道能不能建议，部分内</a> <a class=\"sideA\" id=\"comments_Link_232\" title=\"万古小兔兔 於 2009-05-29 12:31 發表評論 該內容只有管理員可見\"  href=\"read-44.html#book232\">万古小兔兔: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_231\" title=\"阿飞 於 2009-05-27 15:10 發表評論 建议数据库添加sqlite，能在mysql和sqlite之间选择\"  href=\"read-49.html#book231\">阿飞: 建议数据库添加sqlit</a> <a class=\"sideA\" id=\"comments_Link_224\" title=\"Renee 於 2009-05-25 10:54 發表評論 該內容只有管理員可見\"  href=\"read-31.html#book224\">Renee: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_223\" title=\"Ann 於 2009-05-25 10:36 發表評論 該內容只有管理員可見\"  href=\"read-15.html#book223\">Ann: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_222\" title=\"光光 於 2009-05-23 12:27 發表評論 可以的話...希望能有網誌密碼提示的功能^^\"  href=\"read-38.html#book222\">光光: 可以的話...希望能</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新留言-->
                <div class=\"sidepanel\" id=\"Side_GuestBookForPJBlogSubItem1\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_GuestBookForPJBlogSubItem1')\">最新留言</h4>
                    <div class=\"Pcontent\" id=\"content_GuestBookForPJBlogSubItem1\" style=\"display:\">
                        <div class=\"GuestBookForPJBlogSubItem1Table\" id=\"GuestBookForPJBlogSubItem1_Body\"> <a class=\"sideA\" id=\"GuestBook_Link382\" title=\"阿亂支持 於 2009-06-29 17:11 發表留言 謝謝! 已經在論壇上 得到答覆了 ~\"  href=\"guestbook.html#book382\">阿亂支持: 謝謝! 已經在論壇上 得</a> <a class=\"sideA\" id=\"GuestBook_Link380\" title=\"一片空白 於 2009-06-27 00:34 發表留言 請到官方論壇發問吧，那裏人多這裡不太好放語法http://bbs.f2cont.com/\"  href=\"guestbook.html#book380\">一片空白: 請到官方論壇發問吧，那裏</a> <a class=\"sideA\" id=\"GuestBook_Link379\" title=\"阿亂來了 於 2009-06-25 12:54 發表留言 感謝 空白兄~ 只是 圖還有後半段沒顯示~方便直接將 語法貼出嗎~ 感謝\"  href=\"guestbook.html#book379\">阿亂來了: 感謝 空白兄~ 只是 圖</a> <a class=\"sideA\" id=\"GuestBook_Link350\" title=\"一片空白 於 2009-06-24 22:25 發表留言 [img]http://wabis.biz/up0015/200906251124405651764097.GIF[/img]\"  href=\"guestbook.html#book350\">一片空白: [img]http://</a> <a class=\"sideA\" id=\"GuestBook_Link349\" title=\"阿亂支持f2 於 2009-06-24 18:00 發表留言 站長 你好 請問如何用f2 將新增的一張圖放置在左側邊欄後 做[b]連結[/b]到blog指定的內文中 謝謝~好用的f2blog\"  href=\"guestbook.html#book349\">阿亂支持f2: 站長 你好 請問如何用f</a> <a class=\"sideA\" id=\"GuestBook_Link346\" title=\"卓越网 於 2009-06-17 09:43 發表留言 站长好，很喜欢你的博客，想和贵站做个友情链接。本站正常收录，pr=3，希望能通过。博客名称：卓越网博客地址：www.di67.cn/\"  href=\"guestbook.html#book346\">卓越网: 站长好，很喜欢你的博客，</a> <a class=\"sideA\" id=\"GuestBook_Link345\" title=\"一片空白 於 2009-06-12 23:09 發表留言 到這看看吧http://bbs.f2cont.com/redirect.php?tid=121&amp;goto=lastpost#lastpost\"  href=\"guestbook.html#book345\">一片空白: 到這看看吧http:</a> <a class=\"sideA\" id=\"GuestBook_Link344\" title=\"非透明。 於 2009-06-12 14:37 發表留言 请问下有没有从PJBLOG转到F2BLOG的转换程式？？\"  href=\"guestbook.html#book344\">非透明。: 请问下有没有从PJBLO</a> <a class=\"sideA\" id=\"GuestBook_Link343\" title=\"寶兒 於 2009-06-10 02:46 發表留言 請大大確認一下是否將外掛的資料夾放入plugins之中\"  href=\"guestbook.html#book343\">寶兒: 請大大確認一下是否將外掛</a> <a class=\"sideA\" id=\"GuestBook_Link342\" title=\"寶兒 於 2009-06-10 02:37 發表留言 是原開發團隊的大大也&gt;&lt;真是好久不見了&gt;&lt;感謝你們開發出這麼好用的程式~~&gt;&lt;\"  href=\"guestbook.html#book342\">寶兒: 是原開發團隊的大大也&g</a> <a class=\"sideA\" id=\"GuestBook_Link309\" title=\"堕落程式 於 2009-06-01 15:00 發表留言 有備份就有機會就救回來，建議先將所有的資料備份後，在還原試看看\"  href=\"guestbook.html#book309\">堕落程式: 有備份就有機會就救回來，</a> <a class=\"sideA\" id=\"GuestBook_Link308\" title=\"堕落程式 於 2009-06-01 14:59 發表留言 已有內建google map了http://你的網址/googlesitemap.php\"  href=\"guestbook.html#book308\">堕落程式: 已有內建google m</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--歸檔-->
                <div class=\"sidepanel\" id=\"Side_Archive\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Archive')\">歸檔</h4>
                    <div class=\"Pcontent\" id=\"content_Archive\" style=\"display:\">
                        <div class=\"ArchiveTable\" id=\"Archive_Body\"> <a class=\"sideA\" id=\"Archive_Link_1\" href=\"archives-200906.html\">2009 年 06 月 [5]</a> <a class=\"sideA\" id=\"Archive_Link_2\" href=\"archives-200905.html\">2009 年 05 月 [12]</a> <a class=\"sideA\" id=\"Archive_Link_3\" href=\"archives-200903.html\">2009 年 03 月 [1]</a> <a class=\"sideA\" id=\"Archive_Link_4\" href=\"archives-200902.html\">2009 年 02 月 [1]</a> <a class=\"sideA\" id=\"Archive_Link_5\" href=\"archives-200812.html\">2008 年 12 月 [10]</a> <a class=\"sideA\" id=\"Archive_Link_6\" href=\"archives-200811.html\">2008 年 11 月 [24]</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--程式链接-->
                <div class=\"sidepanel\" id=\"Side_Links\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Links')\">程式链接</h4>
                    <div class=\"Pcontent\" id=\"content_Links\" style=\"display:\">
                        <div class=\"LinksTable\" id=\"Links_Body\"> <a class=\"sideA\" id=\"Link_1\" title=\"F2Cont\" href=\"http://www.f2cont.com/\" target=\"_blank\">F2Cont</a> <a class=\"sideA\" id=\"Link_2\" title=\"SaBlog\" href=\"http://www.sablog.net/\" target=\"_blank\">SaBlog</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--F2.Cont小组-->
                <div class=\"sidepanel\" id=\"Side_Links1\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Links1')\">F2.Cont小组</h4>
                    <div class=\"Pcontent\" id=\"content_Links1\" style=\"display:\">
                        <div class=\"Links1Table\" id=\"Links1_Body\"> <a class=\"sideA\" id=\"Link_4\" title=\"堕落程式\" href=\"http://blog.phptw.idv.tw/\" target=\"_blank\">堕落程式</a> <a class=\"sideA\" id=\"Link_5\" title=\"真空实验室\" href=\"http://blog.tgb.net.cn/\" target=\"_blank\">真空实验室</a> <a class=\"sideA\" id=\"Link_3\" title=\"逸林轩\" href=\"http://www.rainboww.net/\" target=\"_blank\">逸林轩</a> <a class=\"sideA\" id=\"Link_6\" title=\"九里河畔\" href=\"http://www.xpboy.com/\" target=\"_blank\">九里河畔</a> <a class=\"sideA\" id=\"Link_8\" title=\"伊氏部落格\" href=\"http://www.eqq.us/\" target=\"_blank\">伊氏部落格</a> <a class=\"sideA\" id=\"Link_9\" title=\"TinyLog\" href=\"http://www.tinylog.org/\" target=\"_blank\">TinyLog</a> <a class=\"sideA\" id=\"Link_7\" title=\"桔絲9\" href=\"http://jas9.com/\" target=\"_blank\">桔絲9</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--統計-->
                <div class=\"sidepanel\" id=\"Side_BlogInfo\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_BlogInfo')\">統計</h4>
                    <div class=\"Pcontent\" id=\"content_BlogInfo\" style=\"display:\">
                        <div class=\"BlogInfoTable\" id=\"BlogInfo_Body\">今日訪問: 36<br />
                            昨日訪問: 103<br />
                            總訪問量: 33372<br />
                            在線人數: 1<br />
                            日誌數量: 53<br />
                            評論數量: 234<br />
                            引用數量: 7<br />
                            註冊用戶: 791<br />
                            留言數量: 314 </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <div id=\"sidebar-bottomimg\"></div>
            </div>
        </div>	
	
	
	";
	
	
	
}

function doHtmlRightSidebar(){
	
	
	echo "
	
		<!--处理侧边栏-->
		<div id=\"sidebar\">
            <div id=\"innersidebar\">
                <div id=\"sidebar-topimg\">
                    <!--工具条顶部图象-->
                </div>
                <!--侧边栏显示内容-->
                <!--用戶面板-->
                <div class=\"sidepanel\" id=\"Side_User\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_User')\">用戶面板</h4>
                    <div class=\"Pcontent\" id=\"content_User\" style=\"display:\">
                        <div class=\"UserTable\" id=\"User_Body\"> <a href=\"login.php\" class=\"sideA\">登錄</a> <a href=\"register.php\" class=\"sideA\">用戶註冊</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--類別-->
                <div class=\"sidepanel\" id=\"Side_Category\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Category')\">類別</h4>
                    <div class=\"Pcontent\" id=\"content_Category\" style=\"display:\">
                        <div class=\"CategoryTable\" id=\"Category_Body\">
                            <script type=\"text/javascript\">
	function openCategory(category) {
		var oLevel1 = document.getElementById(\"category_\" + category);
		var oImg = oLevel1.getElementsByTagName(\"img\")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case \"isleaf\":
			return true;
			case \"closed\":
			oImg.src = \"images/tree/base/tab_opened.gif\";
			showLayer(\"category_\" + category + \"_children\");
			expanded = true;
			return true;
			case \"opened\":
			oImg.src = \"images/tree/base/tab_closed.gif\";
			hideLayer(\"category_\" + category + \"_children\");
			expanded = false;
			return true;
		}
		return false;
	}

	function showLayer(id) {
		document.getElementById(id).style.display = \"block\";
		return true;
	}

	function hideLayer(id) {
		document.getElementById(id).style.display = \"none\";
		return true;
	}
</script>
                            <div id=\"treeComponent\">
                                <div id=\"category_0\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;\"><img src=\"images/tree/base/tab_top.gif\" width=\"16\" align=\"top\" alt=\"\"/></div>
                                    <div style=\"display:inline; vertical-align:middle; margin-left:3px; padding-left:3px; cursor:pointer;\"> <a href='index.php'>所有分類 (53)</a> <span class=\"rss\"><a href='rss.php'>[RSS]</a></span></div>
                                </div>
                                <div id=\"category_1\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\" onClick=\"openCategory('1')\"><img src=\"images/tree/base/tab_opened.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"程序发布 - f2cont程序更新\">
                                        <div id=\"text_1\" style=\"display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;\"><a href='category-1.html'>程序发布		(10)</a> <span class=\"rss\"><a href='rss.php?cateID=1'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_1_children\" style=\"display:\">
                                    <div id=\"subcategory_00\" style=\"line-height: 100%\">
                                        <div style=\"display:inline;\"><img src=\"images/tree/base/navi_back_active.gif\" width=\"17\" align=\"top\" alt=\"\"/><img src=\"images/tree/base/tab_treed_end.gif\" width=\"22\" align=\"top\" alt=\"\"/></div>
                                        <div style=\"display:inline;\" title=\"修正补丁 - 问题的发现与修正\">
                                            <div id=\"text_00\" style=\"display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;\"><a href='category-11.html'>修正补丁		(4)</a> <span class=\"rss\"><a href='rss.php?cateID=11'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id=\"category_category_2\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"模板专栏 - Skins for F2\">
                                        <div id=\"text_2\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-2.html'>模板专栏	  (12)</a> <span class=\"rss\"><a href='rss.php?cateID=2'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_3\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"转换程式 - 转换现有blog使用F2这套程序\">
                                        <div id=\"text_3\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-9.html'>转换程式	  (0)</a> <span class=\"rss\"><a href='rss.php?cateID=9'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_4\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"插件专栏 - Plugins for F2\">
                                        <div id=\"text_4\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-3.html'>插件专栏	  (8)</a> <span class=\"rss\"><a href='rss.php?cateID=3'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_5\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"程式增强 - 完善功能的各式Hack\">
                                        <div id=\"text_5\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-7.html'>程式增强	  (3)</a> <span class=\"rss\"><a href='rss.php?cateID=7'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_6\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"开发日志 - F2cont 小组程序开发notes\">
                                        <div id=\"text_6\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-5.html'>开发日志	  (3)</a> <span class=\"rss\"><a href='rss.php?cateID=5'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_category_7\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"疑问解答 - 官方解答及程序说明，帮助文档之类\">
                                        <div id=\"text_7\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-8.html'>疑问解答	  (1)</a> <span class=\"rss\"><a href='rss.php?cateID=8'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_8\" style=\"line-height: 100%\">
                                    <div style=\"display:inline;background-image: url('images/tree/base/navi_back_noactive.gif')\"><a class=\"click\" onClick=\"openCategory('8')\"><img src=\"images/tree/base/tab_opened.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"关于 - Free &amp; Freedom\">
                                        <div id=\"text_8\" style=\"display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;\"><a href='category-4.html'>关于F2		(14)</a> <span class=\"rss\"><a href='rss.php?cateID=4'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id=\"category_8_children\" style=\"display:\">
                                    <div id=\"subcategory_70\" style=\"line-height: 100%\">
                                        <div style=\"display:inline;\"><img src=\"images/tree/base/navi_back_active.gif\" width=\"17\" align=\"top\" alt=\"\"/><img src=\"images/tree/base/tab_treed_end.gif\" width=\"22\" align=\"top\" alt=\"\"/></div>
                                        <div style=\"display:inline;\" title=\"F2cont团队 - 让F2的理念继续下去\">
                                            <div id=\"text_70\" style=\"display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;\"><a href='category-6.html'>f2cont		(5)</a> <span class=\"rss\"><a href='rss.php?cateID=6'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id=\"category_category_9\" style=\"line-height: 100%\">
                                    <div style=\"display:inline; background-image: url('images/tree/base/navi_back_noactive_end.gif')\"><a class=\"click\"><img src=\"images/tree/base/tab_isleaf.gif\" align=\"top\" alt=\"\"/></a></div>
                                    <div style=\"display:inline;\" title=\"网络资讯 - 了解前沿动态，不做井底之蛙\">
                                        <div id=\"text_9\" style=\"padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;\"><a href='category-10.html'>外部资讯	  (2)</a> <span class=\"rss\"><a href='rss.php?cateID=10'>[RSS]</a></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--日曆-->
                <div class=\"sidepanel\" id=\"Side_Calendar\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Calendar')\">日曆</h4>
                    <div class=\"Pcontent\" id=\"content_Calendar\" style=\"display:\">
                        <div class=\"CalendarTable\" id=\"Calendar_Body\">
                            <div id=\"Calendar_Top\">
                                <div><a href=\"calendar-200906.html\" id=\"LeftB\"></a></div>
                                <div><a href=\"calendar-200908.html\" id=\"RightB\"></a></div>
                                2009 年  7 月</div>
                            <div id=\"Calendar_week\">
                                <ul class=\"Week_UL\">
                                    <li><font color=\"#ff0000\">日</font></li>
                                    <li>一</li>
                                    <li>二</li>
                                    <li>三</li>
                                    <li>四</li>
                                    <li>五</li>
                                    <li>六</li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a class=\"otherday\">28<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"初六\">初六</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">29<span style=\"dipslay:none;font-size:9px;color:\" title=\"初七\"><br />
                                        初七</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">30<span style=\"dipslay:none;font-size:9px;color:\" title=\"初八\"><br />
                                        初八</span></a></li>
                                    <li class=\"DayA\"><a>1<span style=\"dipslay:none;font-size:9px;color:\" title=\"初九\"><br />
                                        初九</span></a></li>
                                    <li class=\"DayA\"><a class=\"today\">2<span style=\"dipslay:none;font-size:9px;color:\" title=\"初十\"><br />
                                        初十</span></a></li>
                                    <li class=\"DayA\"><a>3<span style=\"dipslay:none;font-size:9px;color:\" title=\"十一\"><br />
                                        十一</span></a></li>
                                    <li class=\"DayA\"><a>4<span style=\"dipslay:none;font-size:9px;color:\" title=\"十二\"><br />
                                        十二</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>5<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"十三\">十三</span></a></li>
                                    <li class=\"DayA\"><a>6<span style=\"dipslay:none;font-size:9px;color:\" title=\"十四\"><br />
                                        十四</span></a></li>
                                    <li class=\"DayA\"><a>7<span style=\"dipslay:none;font-size:9px;color:\" title=\"十五\"><br />
                                        十五</span></a></li>
                                    <li class=\"DayA\"><a>8<span style=\"dipslay:none;font-size:9px;color:\" title=\"十六\"><br />
                                        十六</span></a></li>
                                    <li class=\"DayA\"><a>9<span style=\"dipslay:none;font-size:9px;color:\" title=\"十七\"><br />
                                        十七</span></a></li>
                                    <li class=\"DayA\"><a>10<span style=\"dipslay:none;font-size:9px;color:\" title=\"十八\"><br />
                                        十八</span></a></li>
                                    <li class=\"DayA\"><a>11<span style=\"dipslay:none;font-size:9px;color:\" title=\"十九\"><br />
                                        十九</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>12<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"二十\">二十</span></a></li>
                                    <li class=\"DayA\"><a>13<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿一\"><br />
                                        廿一</span></a></li>
                                    <li class=\"DayA\"><a>14<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿二\"><br />
                                        廿二</span></a></li>
                                    <li class=\"DayA\"><a>15<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿三\"><br />
                                        廿三</span></a></li>
                                    <li class=\"DayA\"><a>16<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿四\"><br />
                                        廿四</span></a></li>
                                    <li class=\"DayA\"><a>17<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿五\"><br />
                                        廿五</span></a></li>
                                    <li class=\"DayA\"><a>18<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿六\"><br />
                                        廿六</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>19<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"廿七\">廿七</span></a></li>
                                    <li class=\"DayA\"><a>20<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿八\"><br />
                                        廿八</span></a></li>
                                    <li class=\"DayA\"><a>21<span style=\"dipslay:none;font-size:9px;color:\" title=\"廿九\"><br />
                                        廿九</span></a></li>
                                    <li class=\"DayA\"><a>22<span style=\"dipslay:none;font-size:9px;color:\" title=\"六月\"><br />
                                        六月</span></a></li>
                                    <li class=\"DayA\"><a>23<span style=\"dipslay:none;font-size:9px;color:\" title=\"初二\"><br />
                                        初二</span></a></li>
                                    <li class=\"DayA\"><a>24<span style=\"dipslay:none;font-size:9px;color:\" title=\"初三\"><br />
                                        初三</span></a></li>
                                    <li class=\"DayA\"><a>25<span style=\"dipslay:none;font-size:9px;color:\" title=\"初四\"><br />
                                        初四</span></a></li>
                                </ul>
                            </div>
                            <div class=\"Calendar_Day\" style=\"height:30px;\">
                                <ul class=\"Day_UL\">
                                    <li class=\"DayA\"><a>26<br />
                                        <span style=\"dipslay:none;font-size:9px;color:\" title=\"初五\">初五</span></a></li>
                                    <li class=\"DayA\"><a>27<span style=\"dipslay:none;font-size:9px;color:\" title=\"初六\"><br />
                                        初六</span></a></li>
                                    <li class=\"DayA\"><a>28<span style=\"dipslay:none;font-size:9px;color:\" title=\"初七\"><br />
                                        初七</span></a></li>
                                    <li class=\"DayA\"><a>29<span style=\"dipslay:none;font-size:9px;color:\" title=\"初八\"><br />
                                        初八</span></a></li>
                                    <li class=\"DayA\"><a>30<span style=\"dipslay:none;font-size:9px;color:\" title=\"初九\"><br />
                                        初九</span></a></li>
                                    <li class=\"DayA\"><a>31<span style=\"dipslay:none;font-size:9px;color:\" title=\"初十\"><br />
                                        初十</span></a></li>
                                    <li class=\"DayA\"><a class=\"otherday\">1<span style=\"dipslay:none;font-size:9px;color:\" title=\"十一\"><br />
                                        十一</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--熱門標籤-->
                <div class=\"sidepanel\" id=\"Side_HotTags\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_HotTags')\">熱門標籤</h4>
                    <div class=\"Pcontent\" id=\"content_HotTags\" style=\"display:\">
                        <div class=\"HotTagsTable\" id=\"HotTags_Body\"> <a href=\"tags-F2cont.html\" style=\"color:#f60\" title=\"網誌數量: 14\">F2cont</a> <a href=\"tags-Skin.html\" style=\"color:#069\" title=\"網誌數量: 12\">Skin</a> <a href=\"tags-%E6%A8%A1%E6%9D%BF.html\" style=\"color:#069\" title=\"網誌數量: 12\">模板</a> <a href=\"tags-%E7%A8%8B%E5%BA%8F.html\" style=\"color:#690\" title=\"網誌數量: 9\">程序</a> <a href=\"tags-%E6%8F%92%E4%BB%B6.html\" style=\"color:#690\" title=\"網誌數量: 6\">插件</a> <a href=\"tags-%E5%8F%91%E5%B1%95%E5%8E%86%E5%8F%B2.html\" style=\"color:#690\" title=\"網誌數量: 6\">发展历史</a> <a href=\"tags-Plugin.html\" style=\"color:#690\" title=\"網誌數量: 6\">Plugin</a> <a href=\"tags-%E7%BB%84%E5%91%98.html\" style=\"color:#09c\" title=\"網誌數量: 5\">组员</a> <a href=\"tags-%E5%9B%A2%E9%98%9F.html\" style=\"color:#09c\" title=\"網誌數量: 5\">团队</a> <a href=\"tags-%E5%8F%91%E5%B8%83.html\" style=\"color:#09c\" title=\"網誌數量: 5\">发布</a> <a href=\"tags-TinyMCE.html\" style=\"color:#09c\" title=\"網誌數量: 4\">TinyMCE</a> <a href=\"tags-%E5%A2%9E%E5%BC%BA.html\" style=\"color:#09c\" title=\"網誌數量: 3\">增强</a> <a href=\"tags-F2blog.html\" style=\"color:#09c\" title=\"網誌數量: 2\">F2blog</a> <a href=\"tags-%E7%BC%96%E8%BE%91%E5%99%A8.html\" style=\"color:#999\" title=\"網誌數量: 1\">编辑器</a> <a href=\"tags-%E6%B5%8B%E8%AF%95.html\" style=\"color:#999\" title=\"網誌數量: 1\">测试</a> <a href=\"tags-%E6%A0%87%E9%A2%98%E6%A0%8F.html\" style=\"color:#999\" title=\"網誌數量: 1\">标题栏</a> <a href=\"tags-%E5%B0%8F%E6%97%A5%E5%8E%86.html\" style=\"color:#999\" title=\"網誌數量: 1\">小日历</a> <a href=\"tags-%E8%AF%84%E8%AE%BA.html\" style=\"color:#999\" title=\"網誌數量: 1\">评论</a> <a href=\"tags-%E7%95%99%E8%A8%80.html\" style=\"color:#999\" title=\"網誌數量: 1\">留言</a> <a href=\"tags-ver1.0.html\" style=\"color:#999\" title=\"網誌數量: 1\">ver1.0</a> <a href=\"tags-Hack.html\" style=\"color:#999\" title=\"網誌數量: 1\">Hack</a> <a href=\"tags-%E5%88%86%E9%A1%B5.html\" style=\"color:#999\" title=\"網誌數量: 1\">分页</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新網誌-->
                <div class=\"sidepanel\" id=\"Side_NewLogForPJBlog\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_NewLogForPJBlog')\">最新網誌</h4>
                    <div class=\"Pcontent\" id=\"content_NewLogForPJBlog\" style=\"display:\">
                        <div class=\"NewLogForPJBlogTable\" id=\"NewLogForPJBlog_Body\"> <a class=\"sideA\" id=\"NewLog_Link_54\" title=\"堕落程式 於 2009-06-30 11:42 發表 09/06/30 G型主機異常\" href=\"read-54.html\">堕落程式: 09/06/30 G型主</a> <a class=\"sideA\" id=\"NewLog_Link_53\" title=\"堕落程式 於 2009-06-28 17:37 發表 F2Cont 1.0 Beta 090628 8盎司非力牛 版\" href=\"read-53.html\">堕落程式: F2Cont 1.0 B</a> <a class=\"sideA\" id=\"NewLog_Link_52\" title=\"堕落程式 於 2009-06-24 15:09 發表 F2Cont 1.0 Beta 090624 6盎司非力牛 版\" href=\"read-52.html\">堕落程式: F2Cont 1.0 B</a> <a class=\"sideA\" id=\"NewLog_Link_51\" title=\"堕落程式 於 2009-06-09 22:33 發表 關於這兩天 F2Cont 連不上的原因\" href=\"read-51.html\">堕落程式: 關於這兩天 F2Cont</a> <a class=\"sideA\" id=\"NewLog_Link_50\" title=\"堕落程式 於 2009-06-01 16:12 發表 F2Cont 安全行更新 20090601\" href=\"read-50.html\">堕落程式: F2Cont 安全行更新</a> <a class=\"sideA\" id=\"NewLog_Link_49\" title=\"堕落程式 於 2009-05-13 12:15 發表 F2 開發進度\" href=\"read-49.html\">堕落程式: F2 開發進度</a> <a class=\"sideA\" id=\"NewLog_Link_48\" title=\"堕落程式 於 2009-05-12 16:58 發表 感謝 SAW 部落格學院 提供資源\" href=\"read-48.html\">堕落程式: 感謝 SAW 部落格學院</a> <a class=\"sideA\" id=\"NewLog_Link_47\" title=\"堕落程式 於 2009-05-12 16:44 發表 F2 程序下載\" href=\"read-47.html\">堕落程式: F2 程序下載</a> <a class=\"sideA\" id=\"NewLog_Link_46\" title=\"堕落程式 於 2009-05-12 16:41 發表 請F2 團隊 人員 與我聯絡\" href=\"read-46.html\">堕落程式: 請F2 團隊 人員 與我</a> <a class=\"sideA\" id=\"NewLog_Link_45\" title=\"堕落程式 於 2009-05-12 16:34 發表 F2 招聘 推廣人員 數名\" href=\"read-45.html\">堕落程式: F2 招聘 推廣人員 數</a> <a class=\"sideA\" id=\"NewLog_Link_44\" title=\"堕落程式 於 2009-05-12 16:28 發表 F2 招聘 美工設計人員 數名\" href=\"read-44.html\">堕落程式: F2 招聘 美工設計人員</a> <a class=\"sideA\" id=\"NewLog_Link_43\" title=\"堕落程式 於 2009-05-12 16:20 發表 F2 招聘 測試人員 數名\" href=\"read-43.html\">堕落程式: F2 招聘 測試人員 數</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新評論-->
                <div class=\"sidepanel\" id=\"Side_Comment\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Comment')\">最新評論</h4>
                    <div class=\"Pcontent\" id=\"content_Comment\" style=\"display:\">
                        <div class=\"CommentTable\" id=\"Comment_Body\"> <a class=\"sideA\" id=\"comments_Link_278\" title=\"Fish 於 2009-06-28 10:11 發表評論 原木兄好, 小弟用的是synology\"  href=\"read-39-2.html#book278\">Fish: 原木兄好, 小弟用的是s</a> <a class=\"sideA\" id=\"comments_Link_261\" title=\"原木 於 2009-06-16 17:21 發表評論 原木也是用 buffalo 改機來架 Debian 的，但是原木有128 RAM，哈哈 ...\"  href=\"read-39-2.html#book261\">原木: 原木也是用 buffal</a> <a class=\"sideA\" id=\"comments_Link_258\" title=\"Fish 於 2009-06-14 08:32 發表評論 終於有人願意接手了~我一向用f2blog, 但我美工差, 程式也不懂, 但我可以做測試. 因為我是用NAS(Network Attachement Storage) 來host f2blog 的, 用NAS可以測試資源試用程度, 因為NAS只有32Mb ram. 當然, 我也不介意買一個空間來做hosting 測試如果接受小弟的話, 請email 聯絡啊\"  href=\"read-39-2.html#book258\">Fish: 終於有人願意接手了~</a> <a class=\"sideA\" id=\"comments_Link_256\" title=\"寶兒 於 2009-06-10 02:41 發表評論 這是一支超好用的程式&gt;&lt;真的希望能夠看到它慢慢進步~~非常支持大大們的努力~~~&gt;&lt;\"  href=\"read-39-2.html#book256\">寶兒: 這是一支超好用的程式&g</a> <a class=\"sideA\" id=\"comments_Link_255\" title=\"新2 於 2009-06-07 00:11 發表評論 (*^__^*)...不錯的博客程式。希望可以幫忙推廣推廣。新2\"  href=\"read-39-2.html#book255\">新2: (*^__^*)...不</a> <a class=\"sideA\" id=\"comments_Link_243\" title=\"boshing 於 2009-06-04 22:58 發表評論 支持推廣\"  href=\"read-45.html#book243\">boshing: 支持推廣</a> <a class=\"sideA\" id=\"comments_Link_233\" title=\"万古小兔兔 於 2009-05-29 12:36 發表評論 不知道能不能建议，部分内容隐藏（需要密码）呢？另外还有我觉得3个日志编辑器太多了也其实没什麽用，可以把功能直接合在一起变成一个编辑器吗？另外编辑器的速度也有点慢（tiny那个，php的那个还好但是有用到html代码的话会很不方便）\"  href=\"read-38.html#book233\">万古小兔兔: 不知道能不能建议，部分内</a> <a class=\"sideA\" id=\"comments_Link_232\" title=\"万古小兔兔 於 2009-05-29 12:31 發表評論 該內容只有管理員可見\"  href=\"read-44.html#book232\">万古小兔兔: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_231\" title=\"阿飞 於 2009-05-27 15:10 發表評論 建议数据库添加sqlite，能在mysql和sqlite之间选择\"  href=\"read-49.html#book231\">阿飞: 建议数据库添加sqlit</a> <a class=\"sideA\" id=\"comments_Link_224\" title=\"Renee 於 2009-05-25 10:54 發表評論 該內容只有管理員可見\"  href=\"read-31.html#book224\">Renee: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_223\" title=\"Ann 於 2009-05-25 10:36 發表評論 該內容只有管理員可見\"  href=\"read-15.html#book223\">Ann: 該內容只有管理員可見</a> <a class=\"sideA\" id=\"comments_Link_222\" title=\"光光 於 2009-05-23 12:27 發表評論 可以的話...希望能有網誌密碼提示的功能^^\"  href=\"read-38.html#book222\">光光: 可以的話...希望能</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--最新留言-->
                <div class=\"sidepanel\" id=\"Side_GuestBookForPJBlogSubItem1\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_GuestBookForPJBlogSubItem1')\">最新留言</h4>
                    <div class=\"Pcontent\" id=\"content_GuestBookForPJBlogSubItem1\" style=\"display:\">
                        <div class=\"GuestBookForPJBlogSubItem1Table\" id=\"GuestBookForPJBlogSubItem1_Body\"> <a class=\"sideA\" id=\"GuestBook_Link382\" title=\"阿亂支持 於 2009-06-29 17:11 發表留言 謝謝! 已經在論壇上 得到答覆了 ~\"  href=\"guestbook.html#book382\">阿亂支持: 謝謝! 已經在論壇上 得</a> <a class=\"sideA\" id=\"GuestBook_Link380\" title=\"一片空白 於 2009-06-27 00:34 發表留言 請到官方論壇發問吧，那裏人多這裡不太好放語法http://bbs.f2cont.com/\"  href=\"guestbook.html#book380\">一片空白: 請到官方論壇發問吧，那裏</a> <a class=\"sideA\" id=\"GuestBook_Link379\" title=\"阿亂來了 於 2009-06-25 12:54 發表留言 感謝 空白兄~ 只是 圖還有後半段沒顯示~方便直接將 語法貼出嗎~ 感謝\"  href=\"guestbook.html#book379\">阿亂來了: 感謝 空白兄~ 只是 圖</a> <a class=\"sideA\" id=\"GuestBook_Link350\" title=\"一片空白 於 2009-06-24 22:25 發表留言 [img]http://wabis.biz/up0015/200906251124405651764097.GIF[/img]\"  href=\"guestbook.html#book350\">一片空白: [img]http://</a> <a class=\"sideA\" id=\"GuestBook_Link349\" title=\"阿亂支持f2 於 2009-06-24 18:00 發表留言 站長 你好 請問如何用f2 將新增的一張圖放置在左側邊欄後 做[b]連結[/b]到blog指定的內文中 謝謝~好用的f2blog\"  href=\"guestbook.html#book349\">阿亂支持f2: 站長 你好 請問如何用f</a> <a class=\"sideA\" id=\"GuestBook_Link346\" title=\"卓越网 於 2009-06-17 09:43 發表留言 站长好，很喜欢你的博客，想和贵站做个友情链接。本站正常收录，pr=3，希望能通过。博客名称：卓越网博客地址：www.di67.cn/\"  href=\"guestbook.html#book346\">卓越网: 站长好，很喜欢你的博客，</a> <a class=\"sideA\" id=\"GuestBook_Link345\" title=\"一片空白 於 2009-06-12 23:09 發表留言 到這看看吧http://bbs.f2cont.com/redirect.php?tid=121&amp;goto=lastpost#lastpost\"  href=\"guestbook.html#book345\">一片空白: 到這看看吧http:</a> <a class=\"sideA\" id=\"GuestBook_Link344\" title=\"非透明。 於 2009-06-12 14:37 發表留言 请问下有没有从PJBLOG转到F2BLOG的转换程式？？\"  href=\"guestbook.html#book344\">非透明。: 请问下有没有从PJBLO</a> <a class=\"sideA\" id=\"GuestBook_Link343\" title=\"寶兒 於 2009-06-10 02:46 發表留言 請大大確認一下是否將外掛的資料夾放入plugins之中\"  href=\"guestbook.html#book343\">寶兒: 請大大確認一下是否將外掛</a> <a class=\"sideA\" id=\"GuestBook_Link342\" title=\"寶兒 於 2009-06-10 02:37 發表留言 是原開發團隊的大大也&gt;&lt;真是好久不見了&gt;&lt;感謝你們開發出這麼好用的程式~~&gt;&lt;\"  href=\"guestbook.html#book342\">寶兒: 是原開發團隊的大大也&g</a> <a class=\"sideA\" id=\"GuestBook_Link309\" title=\"堕落程式 於 2009-06-01 15:00 發表留言 有備份就有機會就救回來，建議先將所有的資料備份後，在還原試看看\"  href=\"guestbook.html#book309\">堕落程式: 有備份就有機會就救回來，</a> <a class=\"sideA\" id=\"GuestBook_Link308\" title=\"堕落程式 於 2009-06-01 14:59 發表留言 已有內建google map了http://你的網址/googlesitemap.php\"  href=\"guestbook.html#book308\">堕落程式: 已有內建google m</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--歸檔-->
                <div class=\"sidepanel\" id=\"Side_Archive\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Archive')\">歸檔</h4>
                    <div class=\"Pcontent\" id=\"content_Archive\" style=\"display:\">
                        <div class=\"ArchiveTable\" id=\"Archive_Body\"> <a class=\"sideA\" id=\"Archive_Link_1\" href=\"archives-200906.html\">2009 年 06 月 [5]</a> <a class=\"sideA\" id=\"Archive_Link_2\" href=\"archives-200905.html\">2009 年 05 月 [12]</a> <a class=\"sideA\" id=\"Archive_Link_3\" href=\"archives-200903.html\">2009 年 03 月 [1]</a> <a class=\"sideA\" id=\"Archive_Link_4\" href=\"archives-200902.html\">2009 年 02 月 [1]</a> <a class=\"sideA\" id=\"Archive_Link_5\" href=\"archives-200812.html\">2008 年 12 月 [10]</a> <a class=\"sideA\" id=\"Archive_Link_6\" href=\"archives-200811.html\">2008 年 11 月 [24]</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--程式链接-->
                <div class=\"sidepanel\" id=\"Side_Links\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Links')\">程式链接</h4>
                    <div class=\"Pcontent\" id=\"content_Links\" style=\"display:\">
                        <div class=\"LinksTable\" id=\"Links_Body\"> <a class=\"sideA\" id=\"Link_1\" title=\"F2Cont\" href=\"http://www.f2cont.com/\" target=\"_blank\">F2Cont</a> <a class=\"sideA\" id=\"Link_2\" title=\"SaBlog\" href=\"http://www.sablog.net/\" target=\"_blank\">SaBlog</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--F2.Cont小组-->
                <div class=\"sidepanel\" id=\"Side_Links1\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_Links1')\">F2.Cont小组</h4>
                    <div class=\"Pcontent\" id=\"content_Links1\" style=\"display:\">
                        <div class=\"Links1Table\" id=\"Links1_Body\"> <a class=\"sideA\" id=\"Link_4\" title=\"堕落程式\" href=\"http://blog.phptw.idv.tw/\" target=\"_blank\">堕落程式</a> <a class=\"sideA\" id=\"Link_5\" title=\"真空实验室\" href=\"http://blog.tgb.net.cn/\" target=\"_blank\">真空实验室</a> <a class=\"sideA\" id=\"Link_3\" title=\"逸林轩\" href=\"http://www.rainboww.net/\" target=\"_blank\">逸林轩</a> <a class=\"sideA\" id=\"Link_6\" title=\"九里河畔\" href=\"http://www.xpboy.com/\" target=\"_blank\">九里河畔</a> <a class=\"sideA\" id=\"Link_8\" title=\"伊氏部落格\" href=\"http://www.eqq.us/\" target=\"_blank\">伊氏部落格</a> <a class=\"sideA\" id=\"Link_9\" title=\"TinyLog\" href=\"http://www.tinylog.org/\" target=\"_blank\">TinyLog</a> <a class=\"sideA\" id=\"Link_7\" title=\"桔絲9\" href=\"http://jas9.com/\" target=\"_blank\">桔絲9</a> </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <!--統計-->
                <div class=\"sidepanel\" id=\"Side_BlogInfo\">
                    <h4 class=\"Ptitle\" style=\"cursor: pointer;\" onClick=\"sidebarTools('content_BlogInfo')\">統計</h4>
                    <div class=\"Pcontent\" id=\"content_BlogInfo\" style=\"display:\">
                        <div class=\"BlogInfoTable\" id=\"BlogInfo_Body\">今日訪問: 36<br />
                            昨日訪問: 103<br />
                            總訪問量: 33372<br />
                            在線人數: 1<br />
                            日誌數量: 53<br />
                            評論數量: 234<br />
                            引用數量: 7<br />
                            註冊用戶: 791<br />
                            留言數量: 314 </div>
                    </div>
                    <div class=\"Pfoot\"></div>
                </div>
                <div id=\"sidebar-bottomimg\"></div>
            </div>
        </div>	
	
	
	";
	
	
	
}

function doHtmlFoot(){
	
	
	
	echo "
	
	
	
	
	
    <!--底部-->
    <div id=\"foot\">
        <p> <strong><a href=\"mailto:info(at)f2cont.com\">自由誌</a></strong> 's blog
            Powered By <a href=\"http://www.f2cont.com\" target=\"_blank\"><strong>F2blog.cont 1.0 Beta 090628</strong></a> CopyRight 2006  - 2009 <a href=\"http://validator.w3.org/check/referer\" target=\"_blank\">XHTML</a> | <a href=\"http://jigsaw.w3.org/css-validator/validator-uri.html\" target=\"_blank\">CSS</a> | <a href=\"archives/index.php\" target=\"_blank\">Archiver</a> | <a href=\"googlesitemap.php\" target=\"_blank\">Sitemap</a> </p>
        <p style=\"font-size:11px;\"> <a href=\"http://www.f2cont.com\" target=\"_blank\"><strong>F2cont</strong></a> 程序維護 By <a href=\"http://blog.phptw.idv.tw\" target=\"_blank\"><strong>墮落程式</strong></a> Design by <a href=\"mailto:wind(at)rainboww.net\">Phileas|Joesen</a> Skin from f2blog		推薦 <a href=\"http://burning-g.net/\" target=\"_blank\">虛擬主機</a>；Tech Support <a href=\"http://bbs.saw.tw\" target=\"_blank\">SAW 部落格學院 論壇</a>
            <script type=\"text/javascript\" src=\"http://tw.js.webmaster.yahoo.com/249101/ystat.js\"></script>
        <noscript>
        <a href=\"http://tw.webmaster.yahoo.com\"><img src=http://tw.img.webmaster.yahoo.com/249101/ystats.gif></a>
        </noscript>
        <br />
        Processed in <b>0.013078</b> second(s), <b>3</b> queries, Gzip enabled<br />
        </p>
    </div>	
	
	
	
	
	
	
	
	";
	
	
	
}

function doFoot(){
	
	
	
	echo "
	
<link type=\"text/css\" rel=\"stylesheet\" href=\"SyntaxHighlighter/css/SyntaxHighlighter.css\">
</link>
<script language=\"javascript\" src=\"SyntaxHighlighter/js/shCore.js\"></script>
<script language=\"javascript\" src=\"SyntaxHighlighter/js/shBrushCSharp.js\"></script>
<script language=\"javascript\" src=\"SyntaxHighlighter/js/shBrushXml.js\"></script>
<script language=\"javascript\">
dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/js/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('code');
</script>	
	
	";
	
	
	
	
}


?>
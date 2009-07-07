<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

if (!defined(SKIN_ROOT)) define(SKIN_ROOT,dirname(__FILE__));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>

<?php include(F2BLOG_ROOT."/include/skin.header.php"); ?>

<!--全局样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/global.css" type="text/css" media="all" />
<!--层次样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/layout.css" type="text/css" media="all" />
<!--局部样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/typography.css" type="text/css" media="all" />
<!--超链接样式表-->
<link rel="stylesheet" rev="stylesheet" href="skins/<?php echo $blogSkins?>/link.css" type="text/css" media="all" />
<!--F2blog特有CSS-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "skins/$blogSkins/f2blog.css"?>" type="text/css" media="all" />
<!--F2blog共用CSS-->
<link rel="stylesheet" rev="stylesheet" href="include/common.css" type="text/css" media="all" />
<!--UBB样式-->
<link rel="stylesheet" rev="stylesheet" href="<?php echo "$ubb_path/editor.css"?>" type="text/css" media="all" />

<?php  do_action("f2_head"); ?>

<?php if ($settingInfo['headcode']!="") echo str_replace("<br />","",dencode($settingInfo['headcode']));?>


</head>

<body>
<div id="container">
    <!--顶部-->
    <div id="header">
        <div id="blogname"> 墮落程式
            <div id="blogTitle"> 騎著滑鼠、帶著鍵盤去甩尾～～ </div>
        </div>
        <!--顶部菜单-->
        <div id="menu">
            <div id="Left"></div>
            <div id="Right"></div>
            <ul>
                <li class="menuL"></li>
                <li><a class="menuA" id="home" title="首頁" href="index.php">首頁</a></li>
                <li><a class="menuA" id="tags" title="標籤" href="tags.html">標籤</a></li>
                <li><a class="menuA" id="作品集" title="作品集" href="category-23.html">作品集</a></li>
                <li><a class="menuA" id="F2Cont" title="F2Cont" href="category-37.html">F2Cont</a></li>
                <li><a class="menuA" id="guestbook" title="留言板" href="guestbook.html">留言板</a></li>
                <li><a class="menuA" id="links" title="連結" href="links.html">連結</a></li>
                <li><a class="menuA" id="archives" title="歸檔" href="archives.html">歸檔</a></li>
                <li><a class="menuA" id="rss" title="RSS" href="rss.php">RSS</a></li>
                <li><a class="menuA" id="imagebox" title="相册" href="index.php?load=imagebox">相册</a></li>
                <li class="menuR"></li>
            </ul>
        </div>
    </div>
    <!--内容-->
    <div id="Tbody">
        <!--正文-->
        <div id="mainContent">
            <div id="innermainContent">
                <div id="mainContent-topimg"></div>
                <!-- SATRT GoogleAdSenseSearch -->
                <div id="GoogleAdSenseSearch">
                    <!-- SiteSearch Google -->
                    <div style="bgcolor:#ffffff;margin-left:20px;margin-bottom:10px;">
                        <form method="get" action="http://www.google.com.tw/custom" target="_self">
                            <div style="padding-left:20px;margin:0px;" align="left"> <a href="http://www.google.com/"><img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></a>
                                <input type="hidden" name="domains" value="blog.phptw.idv.tw">
                                </input>
                                <input type="text" name="q" size="35" maxlength="255" value="搜尋本站內容，請輸入搜尋關鍵字" id="sbi" onfocus="this.value='';" style="width:240px;">
                                </input>
                                <input type="submit" name="sa" value="搜尋" id="sbb">
                                </input>
                                <input type="hidden" name="sitesearch" value="blog.phptw.idv.tw" id="ss1">
                                </input>
                                <input type="hidden" name="client" value="pub-6086038655950990">
                                </input>
                                <input type="hidden" name="forid" value="1">
                                </input>
                                <input type="hidden" name="ie" value="UTF-8">
                                </input>
                                <input type="hidden" name="oe" value="UTF-8">
                                </input>
                                <input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1">
                                </input>
                                <input type="hidden" name="hl" value="zh-TW">
                                </input>
                            </div>
                        </form>
                    </div>
                    <!-- SiteSearch Google -->
                </div>
                <!-- END GoogleAdSenseSearch -->
                <!--主体部分-->
                <!--工具栏-->
                <div id="Content_ContentList" class="content-width">
                    <div class="pageContent" style="OVERFLOW: hidden; LINE-HEIGHT: 140%; HEIGHT: 18px; TEXT-ALIGN: right">
                        <div class="page" style="FLOAT: left"> </div>
                        瀏覽模式: <a href="1-0.html">普通</a> | <a href="1-1.html">列表</a> </div>
                    <!--显示-->
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <div class="BttnE" onclick="OpenClose(this,'log_0')"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-145.html"> 程式設計接案服務</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2008-03-13 22:43 </h2>
                        </div>
                        <div id="log_0" >
                            <div class="Content-body" id="logcontent_145" style="table-layout: fixed;">
                                <div><strong>◆服務項目◆<br />
                                    網站程式設計</strong><br />
                                    以PHP、Java、javascript、ajax 開發，使用MySQL、MsSQL資料庫，不含美工(可先設計好，再由我開發)，若需使用其他資料庫皆可談。</div>
                                <div>&nbsp;</div>
                                <div><strong>應用系統開發</strong><br />
                                    資料庫的新增、修改、刪除、查詢等資訊管理系統皆可。
                                    <div>&nbsp;
                                        <div><strong>學生作業協助與指導</strong><br />
                                            有關程式撰寫方面皆可談，PHP皆可，依需求而定。
                                            <div>&nbsp;
                                                <div><strong>其他</strong><br />
                                                    若有其他特殊需求也可來信詳談(如系統整合、爬蟲程式、自動貼文&hellip;)。</div>
                                                <div><br />
                                                    資料庫可採用MySQL或MS SQL。</div>
                                                <div>&nbsp;</div>
                                                <div>Email:service@phptw.idv.tw</div>
                                                <div>Msn:service@phptw.idv.tw<br />
                                                    來信內容請註明下列事項：<br />
                                                    <strong>姓名</strong>：<br />
                                                    <strong>聯絡電話</strong>：<br />
                                                    <strong>地區</strong>：<br />
                                                    <strong>需求說明</strong>：(請敘述程式之需求，盡量詳細說明，以便報價)<br />
                                                    <strong>期限</strong>：(請盡量給一個大概的時程，勿填&quot;愈快愈好&quot;)<br />
                                                    <strong>預算</strong>：(最好能對開發費用有個底，不合理的預算請恕小弟無法承接)<br />
                                                    <strong>開發需求</strong>：(若有特定需求的話請註明)<br />
                                                    1. 系統平臺<br />
                                                    2. 程式語言需求<br />
                                                    3. 資料庫 <br />
                                                    4. 其他(若有其他需求請註明)</div>
                                                <div>&nbsp;</div>
                                                <div><font size="2">收到信後，會先評估需求後，再回信報價。<br />
                                                    需求請盡量清楚、明確，以便報價的準確性。</font>
                                                    <div><font color="#00008b" size="2"><strong>若需求須要面談的，僅限台北地區(確定能承接後才會面談)。<br />
                                                        若只需email或線上溝通的，全省皆可。</strong></font>
                                                        <div>&nbsp;</div>
                                                        <div><font size="2">另外有下列幾點必須先說明。</font><font size="2"> </font></div>
                                                        <ul>
                                                            <li><font size="2">付款方式：<br />
                                                                酬勞5000元內，請一次付清。<br />
                                                                酬勞10,000元內，可採50%/50%付款，或一次付清。<br />
                                                                酬勞10,000元以上可分階段<strong>(付款比例可談)</strong>：<br />
                                                                ◆確定接案後，<strong><font color="#0000cd">須先付50%之報酬</font></strong>，才會開始開發。<br />
                                                                ◆<font color="#0000cd">開發完畢並確認後須付<strong>30%之報酬</strong></font>。<br />
                                                                ◆<font color="#0000cd">交付程式時則付剩下的尾款(即<strong>20%報酬</strong>)</font>。 </font></li>
                                                            <li><font size="2">所有付款方式採ATM轉帳或現金面付<strong><font color="#0000bf">(恕不接受支票)</font></strong>。 </font></li>
                                                        </ul>
                                                        <p>&nbsp;</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-%E7%B6%B2%E8%B7%AF%E8%B3%BA%E9%8C%A2.html">網路賺錢</a>&nbsp;<a href="tags-PHP.html">PHP</a>&nbsp;<a href="tags-%E7%B6%B2%E9%9A%9B%E7%B6%B2%E8%B7%AF.html">網際網路</a>&nbsp;<a href="tags-%E7%B6%B2%E7%AB%99%E4%BD%9C%E5%93%81.html">網站作品</a>&nbsp;<a href="tags-%E5%B7%A5%E5%95%86%E6%9C%8D%E5%8B%99.html">工商服務</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-24.html">程式設計</a> | <a href="read-145.html#tb_top">引用: 0</a> | <a href="read-145.html#comm_top">評論: 1</a> | <a href="read-145.html">閱讀: 1289</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-205.html"> 09/06/30 G型主機異常</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-30 11:43 </h2>
                        </div>
                        <div id="log_1" >
                            <div class="Content-body" id="logcontent_205" style="table-layout: fixed;"> <br />
                                <span class="Apple-style-span" style="font-family: Verdana, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 19px; border-collapse: collapse; color: rgb(132, 131, 131); white-space: pre-wrap; ">09/06/30 台灣時間 上午 11：00～11：50 左右 F2Cont 託管 位址 G型主機異常  ， ping 有 瞬段 現象 或 網路斷線, 所有台灣主機和虛擬主機都受到影響  打電話過去是說 機房 異常查修中， 這段時間上不去是有一點正常的<br />
                                </span><br />
                                <br />
                                <br type="_moz" />
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-%E7%B6%B2%E9%9A%9B%E7%B6%B2%E8%B7%AF.html">網際網路</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-17.html">網際網路</a> | <a href="read-205.html#tb_top">引用: 0</a> | <a href="read-205.html#comm_top">評論: 0</a> | <a href="read-205.html">閱讀: 31</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/16.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-204.html"> F2Cont 1.0 Beta 090628 8盎司非力牛 版</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-28 17:39 </h2>
                        </div>
                        <div id="log_2" >
                            <div class="Content-body" id="logcontent_204" style="table-layout: fixed;"> &nbsp;<br />
                                <div>F2Cont&nbsp;1.0&nbsp;Beta&nbsp;090628&nbsp; &nbsp;8盎司非力牛 版 發布了<br />
                                    目前只是測試版。<br />
                                    <br />
                                    真對 F2Cont 做了修正如下<br />
                                    <br />
                                </div>
                                <p><a class="more" href="read-204.html">[閱讀全文]</a></p>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-%E7%B6%B2%E9%9A%9B%E7%B6%B2%E8%B7%AF.html">網際網路</a>&nbsp;<a href="tags-PHP.html">PHP</a>&nbsp;<a href="tags-F2cont.html">F2cont</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-37.html">F2cont</a> | <a href="read-204.html#tb_top">引用: 0</a> | <a href="read-204.html#comm_top">評論: 0</a> | <a href="read-204.html">閱讀: 43</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-203.html"> fsckvps 事件簿 後記</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-28 15:28 </h2>
                        </div>
                        <div id="log_3" >
                            <div class="Content-body" id="logcontent_203" style="table-layout: fixed;"> <a href="https://secure.fsckvps.com/aff.php?aff=135" target="_blank" title="https://secure.fsckvps.com/aff.php?aff=135" class="KeyWordStyle">fsckvps<img src="images/keyword.gif" border="0" alt=""/></a> 被駭後，已經一段時間了，目前幾乎看起來是已經落幕了。<br />
                                不過 所有資料遺失的&nbsp;客戶 可以免費得到 兩的月的 免費使用，不過&nbsp;資料 lost 了，免費使用兩的月似乎用意不大。<br />
                                不過我將 新的 VPS server 從&nbsp;Atlanta 移到LA ，據說LA的線路比較好，<br />
                                不過用起來似乎也才好一點點&nbsp;tracert 看起來是比較好，<br />
                                不過實際&nbsp;LA
                                <p><a class="more" href="read-203.html">[閱讀全文]</a></p>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-%E7%B6%B2%E8%B7%AF%E8%B3%BA%E9%8C%A2.html">網路賺錢</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-17.html">網際網路</a> | <a href="read-203.html#tb_top">引用: 0</a> | <a href="read-203.html#comm_top">評論: 0</a> | <a href="read-203.html">閱讀: 40</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-202.html"> fsckvps 事件簿</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-28 15:23 </h2>
                        </div>
                        <div id="log_4" >
                            <div class="Content-body" id="logcontent_202" style="table-layout: fixed;"> <br />
                                <div>關於這兩天 F2Cont 和 phptw 連不上的原因 ，是出在 f2cont.com 的DNS server 掛點了...</div>
                                <div>F2cont.com 的DNS server 是我自己在 亞特蘭大的 VPS server ，廠商為 <a href="https://secure.fsckvps.com/aff.php?aff=135" target="_blank" title="https://secure.fsckvps.com/aff.php?aff=135" class="KeyWordStyle">FsckVPS<img src="images/keyword.gif" border="0" alt=""/></a></div>
                                <div>這兩天 在全球hosting 發生了一件重大的事情，</div>
                                <div>就是 VPS sever 被hacked ，造成重大 災情產生，</div>
                                <div>&nbsp;</div>
                                <div>根據 FsckVPS 發給我的E</div>
                                <p><a class="more" href="read-202.html">[閱讀全文]</a></p>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-17.html">網際網路</a> | <a href="read-202.html#tb_top">引用: 0</a> | <a href="read-202.html#comm_top">評論: 0</a> | <a href="read-202.html">閱讀: 38</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-201.html"> F2Cont 1.0 Beta 060624 6盎司非力牛 版</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-24 15:10 </h2>
                        </div>
                        <div id="log_5" >
                            <div class="Content-body" id="logcontent_201" style="table-layout: fixed;"> 應&nbsp;&nbsp;white&nbsp;<span href="tag.php?name=F2Cont" onclick="tagshow(event)" class="t_tag" style="word-wrap: break-word; line-height: normal; cursor: pointer; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(255, 0, 0); white-space: nowrap; ">F2Cont</span>&nbsp;1.0 Beta 060624&nbsp; &nbsp;6盎司非力牛 版 發布了<br style="word-wrap: break-word; line-height: normal; " />
                                目前只是測試版。<br style="word-wrap: break-word; line-height: normal; " />
                                <br style="word-wrap: break-word; line-height: normal; " />
                                真對 F2Cont 做了修正如下<br style="word-wrap: break-word; line-height: normal; " />
                                1. 針對資料庫優化<br style="word-wrap: break-word; line-height: normal; " />
                                2. 針對&nbsp;<span href="tag.php?name=spam" onclick="tagshow(event)" class="t_tag" style="word-wrap: break-word; line-height: normal; cursor: pointer; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(255, 0, 0); white-space: nowrap; ">spam</span>&nbsp;內建預設的 filter 並更改規則<br style="word-wrap: break-word; line-height: normal; " />
                                3. 安全性更新&nbsp;<a target="_blank" style="word-wrap: break-word; text-decoration: none; color: rgb(64, 80, 96); line-height: normal; " href="http://bbs.f2cont.com/thread-107-1-1.html">http://bbs.f2cont.com/thread-107-1-1.</a>
                                <p><a class="more" href="read-201.html">[閱讀全文]</a></p>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-F2cont.html">F2cont</a>&nbsp;<a href="tags-PHP.html">PHP</a>&nbsp;<a href="tags-%E7%B6%B2%E9%9A%9B%E7%B6%B2%E8%B7%AF.html">網際網路</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-1.html">未分類文章</a> | <a href="read-201.html#tb_top">引用: 0</a> | <a href="read-201.html#comm_top">評論: 0</a> | <a href="read-201.html">閱讀: 45</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-200.html"> [轉載] 在 MySQL 中使用 index</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-14 23:39 </h2>
                        </div>
                        <div id="log_6" >
                            <div class="Content-body" id="logcontent_200" style="table-layout: fixed;"> &nbsp;<span class="Apple-style-span" style="font-family: Verdana; font-size: 12px; line-height: 18px; ">以下文章 來至於&nbsp;Edison home<br />
                                http://remindme.blogbus.com/logs/34133550.html<br />
                                <br />
                                <span class="Apple-style-span" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 19px; color: rgb(85, 85, 85); ">
                                <p style="margin-top: 8px; margin-right: 0px; margin-bottom: 8px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; ">在 MySQL 中使用 index 時，下列是一些該注意的事：</p>
                                <ul style="margin-top: 5px; margin-right: 0px; margin-bottom: 5px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; list-style-type: none; list-style-position: initial; list-style-image: initial; ">
                                    <li style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; list-style-type: disc; list-style-position: inside; list-style-image: initial; ">在 MySQL 裡，將欄位設為 Primary 或 Unique 時，都同時具有 index 的效果。</li>
                                    <li style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; list-style-type: disc; list-style-position: inside; list-style-image: initial; ">欲設為 index 的欄位長度是越短越好，這樣在維護 index table 時會</li>
                                </ul>
                                </span></span>
                                <p><a class="more" href="read-200.html">[閱讀全文]</a></p>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-MySQL.html">MySQL</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-3.html">MySQL</a> | <a href="read-200.html#tb_top">引用: 0</a> | <a href="read-200.html#comm_top">評論: 0</a> | <a href="read-200.html">閱讀: 162</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/16.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-199.html"> F2Cont 安全行更新 20090601</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-14 23:38 </h2>
                        </div>
                        <div id="log_7" >
                            <div class="Content-body" id="logcontent_199" style="table-layout: fixed;"> &nbsp;原始資料來自於&nbsp;<a href="http://blog.f2cont.com/guestbook.html#book307" target="_blank" style="text-decoration: none; color: rgb(0, 0, 204); ">http://blog.f2cont.com/guestbook.html#book307</a>
                                <div style="background-color: rgb(255, 255, 255); padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; font-family: Arial, Verdana, sans-serif; font-size: 11pt; line-height: 21px; "><br />
                                    請下載 path 檔案直接覆蓋&nbsp;</div>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-F2cont.html">F2cont</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-37.html">F2cont</a> | <a href="read-199.html#tb_top">引用: 0</a> | <a href="read-199.html#comm_top">評論: 0</a> | <a href="read-199.html">閱讀: 129</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/1.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-198.html"> [轉載] 軟體服務就像自來水 隨選即用</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-14 23:37 </h2>
                        </div>
                        <div id="log_8" >
                            <div class="Content-body" id="logcontent_198" style="table-layout: fixed;"> &nbsp;<span class="Apple-style-span" style="font-family: Verdana; font-size: 12px; line-height: 18px; ">&nbsp;<span class="Apple-style-span" style="font-size: 12px; line-height: 16px; border-collapse: collapse; color: rgb(59, 70, 74); "><span class="Apple-style-span" style="color: rgb(0, 0, 128); font-size: 13px; ">產業趨勢》軟體服務像自來水 隨選隨用 &nbsp;</span>
                                <p style="margin-top: 15px; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; "><span style="font-size: 10pt; color: rgb(0, 0, 128); ">經濟日報／陳珮馨 2007.03.16 04:29 am</span></p>
                                <p style="margin-top: 15px; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; "><span style="font-size: 10pt; color: rgb(128, 128, 128); "><span style="color: rgb(51, 102, 255); ">網路革命風起雲湧，開放與分享的時代潮流，逐步吞噬傳統的軟體概念，近幾年炙手可熱的軟體服務化（Software as a service，SaaS），強調貼近使用者、共創分享平台，形成一股銳不可擋的全球趨勢。</span>&nbsp;<br />
                                    <br />
                                    2005年末，美國半導體市調權威國際數據資訊公司（I</span></p>
                                </span></span>
                                <p><a class="more" href="read-198.html">[閱讀全文]</a></p>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-%E7%B6%B2%E9%9A%9B%E7%B6%B2%E8%B7%AF.html">網際網路</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-17.html">網際網路</a> | <a href="read-198.html#tb_top">引用: 0</a> | <a href="read-198.html#comm_top">評論: 0</a> | <a href="read-198.html">閱讀: 46</a> </div>
                        </div>
                    </div>
                    <div class="Content">
                        <div class="Content-top">
                            <div class="ContentLeft"></div>
                            <div class="ContentRight"></div>
                            <h1 class="ContentTitle"><img src="images/icons/16.gif" style="margin: 0px 2px -4px 0px" alt="" class="CateIcon"/><a class="titleA" href="read-197.html"> [F2Cont] F2 開發進度</a> </h1>
                            <h2 class="ContentAuthor"> 作者:phptw&nbsp;日期:2009-06-14 23:36 </h2>
                        </div>
                        <div id="log_9" >
                            <div class="Content-body" id="logcontent_197" style="table-layout: fixed;"> &nbsp;<span class="Apple-style-span" style="font-family: Verdana; font-size: 12px; line-height: 18px; "><span style="font-size: small; ">目前F2Cont 的程式發進度</span>
                                <ol>
                                    <li><span style="font-size: small; ">強化&nbsp;DB&nbsp;的效率</span></li>
                                    <li><span style="font-size: small; ">將google&nbsp;sitemap 那入 link 省去 登錄google 的動作</span></li>
                                    <li><span style="font-size: small; ">加入預設的驗證機制，用來抵擋 spam</span></li>
                                </ol>
                                <span style="font-size: small; "><br />
                                接下來預計進行的動作</span>
                                <ol>
                                    <li><span style="font-size: small; ">製作&nbsp;百度&nbsp;sitemap&nbsp;</span></li>
                                    <li><span style="font-size: small; ">修正「回覆留言」、評論 BUG</span></li>
                                </ol>
                                </span>
                                <div style="clear:both;margin-top:10px;"><strong>標籤: </strong> <a href="tags-F2cont.html">F2cont</a></div>
                            </div>
                            <div class="Content-bottom">
                                <div class="ContentBLeft"></div>
                                <div class="ContentBRight"></div>
                                類別: <a href="category-37.html">F2cont</a> | <a href="read-197.html#tb_top">引用: 0</a> | <a href="read-197.html#comm_top">評論: 0</a> | <a href="read-197.html">閱讀: 54</a> </div>
                        </div>
                    </div>
                    <div class="pageContent">
                        <div class="page" style="float:right">
                            <ul>
                                <li class="pageNumber"><strong>1</strong>&nbsp;<a href="2.html">2</a>&nbsp;<a href="3.html">3</a>&nbsp;<a href="4.html">4</a>&nbsp;<a href="5.html">5</a>&nbsp;<a href="6.html">6</a>&nbsp;<a href="7.html">7</a>&nbsp;<a href="8.html">8</a>&nbsp;<a href="9.html">9</a>&nbsp;<a href="10.html">10</a>&nbsp;<a href="11.html">11</a>&nbsp;<a href="12.html">12</a>&nbsp;<a href="13.html">13</a>&nbsp;<a href="14.html">14</a>&nbsp;<a href="15.html">15</a>&nbsp;<a href="16.html">16</a>&nbsp;<a href="17.html">17</a>&nbsp;<a href="2.html" title="下一頁">></a>&nbsp;<a href="17.html" title="尾頁">>|</a>&nbsp;</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="Content_fsckvps" class="content-width"> <a href="https://secure.fsckvps.com/aff.php?aff=135"><img src=http://fsckvps.com/images/468x60_fsckvps.gif border="0"></a></div>
                <div id="mainContent-bottomimg"></div>
            </div>
        </div>
        <!--处理侧边栏-->
        <div id="sidebar">
            <div id="innersidebar">
                <div id="sidebar-topimg">
                    <!--工具条顶部图象-->
                </div>
                <!--侧边栏显示内容-->
                <!--日曆-->
                <div class="sidepanel" id="Side_Calendar">
                    <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Calendar')">日曆</h4>
                    <div class="Pcontent" id="content_Calendar" style="display:">
                        <div class="CalendarTable" id="Calendar_Body">
                            <div id="Calendar_Top">
                                <div><a href="Javascript:void(0)" onclick="f2_ajax_calendar('f2blog_ajax.php?job=calendar&amp;seekname=200906&amp;ajax_display=calendar')" id="LeftB"></a></div>
                                <div><a href="Javascript:void(0)" onclick="f2_ajax_calendar('f2blog_ajax.php?job=calendar&amp;seekname=200908&amp;ajax_display=calendar')" id="RightB"></a></div>
                                2009 年  7 月</div>
                            <div id="Calendar_week">
                                <ul class="Week_UL">
                                    <li><font color="#ff0000">日</font></li>
                                    <li>一</li>
                                    <li>二</li>
                                    <li>三</li>
                                    <li>四</li>
                                    <li>五</li>
                                    <li>六</li>
                                </ul>
                            </div>
                            <div class="Calendar_Day" style="height:30px;">
                                <ul class="Day_UL">
                                    <li class="DayA"><a class="otherday">28<br />
                                        <span style="dipslay:none;font-size:9px;color:" title="初六">初六</span></a></li>
                                    <li class="DayA"><a class="otherday">29<span style="dipslay:none;font-size:9px;color:" title="初七"><br />
                                        初七</span></a></li>
                                    <li class="DayA"><a class="otherday">30<span style="dipslay:none;font-size:9px;color:" title="初八"><br />
                                        初八</span></a></li>
                                    <li class="DayA"><a>1<span style="dipslay:none;font-size:9px;color:" title="初九"><br />
                                        初九</span></a></li>
                                    <li class="DayA"><a>2<span style="dipslay:none;font-size:9px;color:" title="初十"><br />
                                        初十</span></a></li>
                                    <li class="DayA"><a>3<span style="dipslay:none;font-size:9px;color:" title="十一"><br />
                                        十一</span></a></li>
                                    <li class="DayA"><a>4<span style="dipslay:none;font-size:9px;color:" title="十二"><br />
                                        十二</span></a></li>
                                </ul>
                            </div>
                            <div class="Calendar_Day" style="height:30px;">
                                <ul class="Day_UL">
                                    <li class="DayA"><a>5<br />
                                        <span style="dipslay:none;font-size:9px;color:" title="十三">十三</span></a></li>
                                    <li class="DayA"><a>6<span style="dipslay:none;font-size:9px;color:" title="十四"><br />
                                        十四</span></a></li>
                                    <li class="DayA"><a class="today">7<span style="dipslay:none;font-size:9px;color:" title="十五"><br />
                                        十五</span></a></li>
                                    <li class="DayA"><a>8<span style="dipslay:none;font-size:9px;color:" title="十六"><br />
                                        十六</span></a></li>
                                    <li class="DayA"><a>9<span style="dipslay:none;font-size:9px;color:" title="十七"><br />
                                        十七</span></a></li>
                                    <li class="DayA"><a>10<span style="dipslay:none;font-size:9px;color:" title="十八"><br />
                                        十八</span></a></li>
                                    <li class="DayA"><a>11<span style="dipslay:none;font-size:9px;color:" title="十九"><br />
                                        十九</span></a></li>
                                </ul>
                            </div>
                            <div class="Calendar_Day" style="height:30px;">
                                <ul class="Day_UL">
                                    <li class="DayA"><a>12<br />
                                        <span style="dipslay:none;font-size:9px;color:" title="二十">二十</span></a></li>
                                    <li class="DayA"><a>13<span style="dipslay:none;font-size:9px;color:" title="廿一"><br />
                                        廿一</span></a></li>
                                    <li class="DayA"><a>14<span style="dipslay:none;font-size:9px;color:" title="廿二"><br />
                                        廿二</span></a></li>
                                    <li class="DayA"><a>15<span style="dipslay:none;font-size:9px;color:" title="廿三"><br />
                                        廿三</span></a></li>
                                    <li class="DayA"><a>16<span style="dipslay:none;font-size:9px;color:" title="廿四"><br />
                                        廿四</span></a></li>
                                    <li class="DayA"><a>17<span style="dipslay:none;font-size:9px;color:" title="廿五"><br />
                                        廿五</span></a></li>
                                    <li class="DayA"><a>18<span style="dipslay:none;font-size:9px;color:" title="廿六"><br />
                                        廿六</span></a></li>
                                </ul>
                            </div>
                            <div class="Calendar_Day" style="height:30px;">
                                <ul class="Day_UL">
                                    <li class="DayA"><a>19<br />
                                        <span style="dipslay:none;font-size:9px;color:" title="廿七">廿七</span></a></li>
                                    <li class="DayA"><a>20<span style="dipslay:none;font-size:9px;color:" title="廿八"><br />
                                        廿八</span></a></li>
                                    <li class="DayA"><a>21<span style="dipslay:none;font-size:9px;color:" title="廿九"><br />
                                        廿九</span></a></li>
                                    <li class="DayA"><a>22<span style="dipslay:none;font-size:9px;color:" title="六月"><br />
                                        六月</span></a></li>
                                    <li class="DayA"><a>23<span style="dipslay:none;font-size:9px;color:" title="初二"><br />
                                        初二</span></a></li>
                                    <li class="DayA"><a>24<span style="dipslay:none;font-size:9px;color:" title="初三"><br />
                                        初三</span></a></li>
                                    <li class="DayA"><a>25<span style="dipslay:none;font-size:9px;color:" title="初四"><br />
                                        初四</span></a></li>
                                </ul>
                            </div>
                            <div class="Calendar_Day" style="height:30px;">
                                <ul class="Day_UL">
                                    <li class="DayA"><a>26<br />
                                        <span style="dipslay:none;font-size:9px;color:" title="初五">初五</span></a></li>
                                    <li class="DayA"><a>27<span style="dipslay:none;font-size:9px;color:" title="初六"><br />
                                        初六</span></a></li>
                                    <li class="DayA"><a>28<span style="dipslay:none;font-size:9px;color:" title="初七"><br />
                                        初七</span></a></li>
                                    <li class="DayA"><a>29<span style="dipslay:none;font-size:9px;color:" title="初八"><br />
                                        初八</span></a></li>
                                    <li class="DayA"><a>30<span style="dipslay:none;font-size:9px;color:" title="初九"><br />
                                        初九</span></a></li>
                                    <li class="DayA"><a>31<span style="dipslay:none;font-size:9px;color:" title="初十"><br />
                                        初十</span></a></li>
                                    <li class="DayA"><a class="otherday">1<span style="dipslay:none;font-size:9px;color:" title="十一"><br />
                                        十一</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="Pfoot"></div>
                </div>
                <!--類別-->
                <div class="sidepanel" id="Side_Category">
                    <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Category')">類別</h4>
                    <div class="Pcontent" id="content_Category" style="display:">
                        <div class="CategoryTable" id="Category_Body">
                            <script type="text/javascript"> 
	function openCategory(category) {
		var oLevel1 = document.getElementById("category_" + category);
		var oImg = oLevel1.getElementsByTagName("img")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case "isleaf":
				return true;
			case "closed":
				oImg.src = "images/tree/folder_gray/tab_opened.gif";
				showLayer("category_" + category + "_children");
				expanded = true;
				return true;
			case "opened":
				oImg.src = "images/tree/folder_gray/tab_closed.gif";
				hideLayer("category_" + category + "_children");
				expanded = false;
				return true;
		}
		return false;
	}
	
	function showLayer(id) {
		document.getElementById(id).style.display = "block";
		return true;
	}
	
	function hideLayer(id) {
		document.getElementById(id).style.display = "none";
		return true;
	}	
</script>
                            <div id="treeComponent">
                                <div id="category_0" style="line-height: 100%">
                                    <div style="display:inline;"><img src="images/tree/folder_gray/tab_top.gif" width="16" align="top" alt=""/></div>
                                    <div style="display:inline; vertical-align:middle; margin-left:3px; padding-left:3px; cursor:pointer;"> <a href='index.php'>所有分類 (169)</a> <span class="rss"><a href='rss.php'>[RSS]</a></span></div>
                                </div>
                                <div id="category_category_1" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="yahoo 知識+">
                                        <div id="text_1" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-38.html'>yahoo 知識+	  (1)</a> <span class="rss"><a href='rss.php?cateID=38'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_2" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="最新消息">
                                        <div id="text_2" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-35.html'>最新消息	  (5)</a> <span class="rss"><a href='rss.php?cateID=35'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_3" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="MIS 筆記">
                                        <div id="text_3" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-27.html'>MIS 筆記	  (2)</a> <span class="rss"><a href='rss.php?cateID=27'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_4" style="line-height: 100%">
                                    <div style="display:inline;background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click" onclick="openCategory('4')"><img src="images/tree/folder_gray/tab_closed.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_4" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='category-2.html'>PHP		(69)</a> <span class="rss"><a href='rss.php?cateID=2'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_4_children" style="display:none">
                                    <div id="subcategory_30" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_30" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-28.html'>PHP 新聞		(4)</a> <span class="rss"><a href='rss.php?cateID=28'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_31" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_31" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-29.html'>PHP 教學		(33)</a> <span class="rss"><a href='rss.php?cateID=29'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_32" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_32" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-30.html'>PHP 實例		(0)</a> <span class="rss"><a href='rss.php?cateID=30'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_33" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_33" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-7.html'>PHP Class		(4)</a> <span class="rss"><a href='rss.php?cateID=7'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_34" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="PHP100 實例">
                                            <div id="text_34" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-32.html'>PHP100 實例		(21)</a> <span class="rss"><a href='rss.php?cateID=32'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_35" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed_end.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_35" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-31.html'>PHP 其他		(6)</a> <span class="rss"><a href='rss.php?cateID=31'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="category_category_5" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_5" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-39.html'>Extjs 學習手札	  (0)</a> <span class="rss"><a href='rss.php?cateID=39'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_6" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="php葵花寶典">
                                        <div id="text_6" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-34.html'>php葵花寶典	  (1)</a> <span class="rss"><a href='rss.php?cateID=34'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_7" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_7" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-3.html'>MySQL	  (3)</a> <span class="rss"><a href='rss.php?cateID=3'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_8" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_8" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-6.html'>CSS	  (1)</a> <span class="rss"><a href='rss.php?cateID=6'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_9" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="linux">
                                        <div id="text_9" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-36.html'>linux	  (5)</a> <span class="rss"><a href='rss.php?cateID=36'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_10" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_10" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-4.html'>JavaScripts	  (11)</a> <span class="rss"><a href='rss.php?cateID=4'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_11" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="F2cont">
                                        <div id="text_11" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-37.html'>F2cont	  (9)</a> <span class="rss"><a href='rss.php?cateID=37'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_12" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="F2Blog">
                                        <div id="text_12" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-21.html'>F2Blog	  (8)</a> <span class="rss"><a href='rss.php?cateID=21'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_13" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_13" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-26.html'>Java	  (0)</a> <span class="rss"><a href='rss.php?cateID=26'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_14" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="SEO">
                                        <div id="text_14" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-25.html'>SEO	  (2)</a> <span class="rss"><a href='rss.php?cateID=25'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_15" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="程式設計">
                                        <div id="text_15" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-24.html'>程式設計	  (1)</a> <span class="rss"><a href='rss.php?cateID=24'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_16" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_16" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-17.html'>網際網路	  (6)</a> <span class="rss"><a href='rss.php?cateID=17'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_category_17" style="line-height: 100%">
                                    <div style="display:inline; background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click"><img src="images/tree/folder_gray/tab_isleaf.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="理財投資">
                                        <div id="text_17" style="padding-left:3px; cursor:pointer;display:inline; vertical-align:middle;"><a href='category-18.html'>理財投資	  (0)</a> <span class="rss"><a href='rss.php?cateID=18'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_18" style="line-height: 100%">
                                    <div style="display:inline;background-image: url('images/tree/folder_gray/navi_back_noactive.gif')"><a class="click" onclick="openCategory('18')"><img src="images/tree/folder_gray/tab_closed.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_18" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='category-23.html'>網站作品		(14)</a> <span class="rss"><a href='rss.php?cateID=23'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_18_children" style="display:none">
                                    <div id="subcategory_170" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="入口網站">
                                            <div id="text_170" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-20.html'>入口網站		(2)</a> <span class="rss"><a href='rss.php?cateID=20'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_171" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_active.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed_end.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="形像網站">
                                            <div id="text_171" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-22.html'>形像網站		(12)</a> <span class="rss"><a href='rss.php?cateID=22'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="category_19" style="line-height: 100%">
                                    <div style="display:inline;background-image: url('images/tree/folder_gray/navi_back_noactive_end.gif')"><a class="click" onclick="openCategory('19')"><img src="images/tree/folder_gray/tab_closed.gif" align="top" alt=""/></a></div>
                                    <div style="display:inline;" title="">
                                        <div id="text_19" style="display:inline; vertical-align:middle; padding-left:3px; cursor:pointer;"><a href='category-5.html'>網路賺錢		(31)</a> <span class="rss"><a href='rss.php?cateID=5'>[RSS]</a></span></div>
                                    </div>
                                </div>
                                <div id="category_19_children" style="display:none">
                                    <div id="subcategory_180" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="Bux 站">
                                            <div id="text_180" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-14.html'>Bux 站		(13)</a> <span class="rss"><a href='rss.php?cateID=14'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_181" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="PayPal">
                                            <div id="text_181" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-16.html'>PayPal		(0)</a> <span class="rss"><a href='rss.php?cateID=16'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_182" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_182" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-8.html'>擊點廣告		(4)</a> <span class="rss"><a href='rss.php?cateID=8'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_183" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="填寫問卷">
                                            <div id="text_183" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-19.html'>填寫問卷		(1)</a> <span class="rss"><a href='rss.php?cateID=19'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_184" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_184" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-11.html'>網路衝浪		(0)</a> <span class="rss"><a href='rss.php?cateID=11'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_185" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_185" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-10.html'>賺錢工具		(7)</a> <span class="rss"><a href='rss.php?cateID=10'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                    <div id="subcategory_186" style="line-height: 100%">
                                        <div style="display:inline;"><img src="images/tree/folder_gray/navi_back_noactive_end.gif" width="17" align="top" alt=""/><img src="images/tree/folder_gray/tab_treed_end.gif" width="22" align="top" alt=""/></div>
                                        <div style="display:inline;" title="">
                                            <div id="text_186" style="display:inline; vertical-align:middle;padding-left:3px; cursor:pointer;"><a href='category-12.html'>收款紀錄		(5)</a> <span class="rss"><a href='rss.php?cateID=12'>[RSS]</a></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Pfoot"></div>
                </div>
                <!--最新網誌-->
                <div class="sidepanel" id="Side_NewLogForPJBlog">
                    <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_NewLogForPJBlog')">最新網誌</h4>
                    <div class="Pcontent" id="content_NewLogForPJBlog" style="display:">
                        <div class="NewLogForPJBlogTable" id="NewLogForPJBlog_Body"> <a class="sideA" id="NewLog_Link_205" title="phptw 於 2009-06-30 11:43 發表 09/06/30 G型主機異常" href="read-205.html">09/06/30 G型主機異常</a> <a class="sideA" id="NewLog_Link_204" title="phptw 於 2009-06-28 17:39 發表 F2Cont 1.0 Beta 090628 8盎司非力牛 版" href="read-204.html">F2Cont 1.0 Beta 090628 8盎司非力牛 版</a> <a class="sideA" id="NewLog_Link_203" title="phptw 於 2009-06-28 15:28 發表 fsckvps 事件簿 後記" href="read-203.html">fsckvps 事件簿 後記</a> <a class="sideA" id="NewLog_Link_202" title="phptw 於 2009-06-28 15:23 發表 fsckvps 事件簿" href="read-202.html">fsckvps 事件簿</a> <a class="sideA" id="NewLog_Link_201" title="phptw 於 2009-06-24 15:10 發表 F2Cont 1.0 Beta 060624 6盎司非力牛 版" href="read-201.html">F2Cont 1.0 Beta 060624 6盎司非力牛 版</a> <a class="sideA" id="NewLog_Link_200" title="phptw 於 2009-06-14 23:39 發表 [轉載] 在 MySQL 中使用 index" href="read-200.html">[轉載] 在 MySQL 中使用 index</a> <a class="sideA" id="NewLog_Link_199" title="phptw 於 2009-06-14 23:38 發表 F2Cont 安全行更新 20090601" href="read-199.html">F2Cont 安全行更新 20090601</a> <a class="sideA" id="NewLog_Link_198" title="phptw 於 2009-06-14 23:37 發表 [轉載] 軟體服務就像自來水 隨選即用" href="read-198.html">[轉載] 軟體服務就像自來水 隨選即用</a> <a class="sideA" id="NewLog_Link_197" title="phptw 於 2009-06-14 23:36 發表 [F2Cont] F2 開發進度" href="read-197.html">[F2Cont] F2 開發進度</a> <a class="sideA" id="NewLog_Link_196" title="phptw 於 2009-05-08 12:11 發表 感謝各位的支持" href="read-196.html">感謝各位的支持</a> </div>
                    </div>
                    <div class="Pfoot"></div>
                </div>
                <!-- SATRT BloggerAds -->
                <div class="sidepanel" id="BloggerAds">
                    <h4 class="Ptitle" style="cursor: pointer;">BloggerAds</h4>
                    <div class="Pcontent" id="content_statistics" style="display:">
                        <script type="text/javascript" src="http://ad2.bloggerads.net/showads.aspx?blogid=20080109000109&amp;charset=utf-8"></script>
                    </div>
                    <a href="http://author.bloggerads.net/01_join_read.aspx?refid=10001&amp;id=20080109000081" target="_blank">用 BloggerAds 替自已賺油錢</a></div>
            </div>
            <!-- END BloggerAds -->
            <!--最新留言-->
            <div class="sidepanel" id="Side_GuestBookForPJBlogSubItem1">
                <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_GuestBookForPJBlogSubItem1')">最新留言</h4>
                <div class="Pcontent" id="content_GuestBookForPJBlogSubItem1" style="display:">
                    <div class="GuestBookForPJBlogSubItem1Table" id="GuestBookForPJBlogSubItem1_Body"> <a class="sideA" id="GuestBook_Link112" title="phptw 於 2009-06-28 15:37 發表留言 請到官方論壇發問吧，那裏人多這裡不太好放語法http://bbs.f2cont.com/"  href="guestbook.html#book112">請到官方論壇發問吧，那裏人多這裡不太好放語法http://bbs.f2cont.com/</a> <a class="sideA" id="GuestBook_Link111" title="支持f2 於 2009-06-26 09:47 發表留言 請教站長 如何將側邊欄位 放一張圖檔及做可點選連結到指定的網誌文章內 謝謝"  href="guestbook.html#book111">請教站長 如何將側邊欄位 放一張圖檔及做可點選連結到指定的網誌文章內 謝謝</a> <a class="sideA" id="GuestBook_Link110" title="原木 於 2009-05-08 12:04 發表留言 已經修正原來是 footer.php 不需要再加額外的資訊因為 plugins  已有故會造成重複的高亮代碼已經修正好嚕謝謝喔"  href="guestbook.html#book110">已經修正原來是 footer.php 不需要再加額外的資訊因為 plugins  已有故</a> <a class="sideA" id="GuestBook_Link109" title="weilin 於 2009-05-07 23:56 發表留言 您好 ,請問站長可不可以交我一個 javascript 語法就是當滑鼠移到圖片時,圖片旁會直接顯示一個視窗而當滑鼠離開圖片時小視窗自動關閉就好像在您的網誌當我把滑鼠移至您上方的連結時會跑出一個小視窗(ex: 把滑鼠移至留言板時 , 會有個小視窗顯示留言板)"  href="guestbook.html#book109">您好 ,請問站長可不可以交我一個 javascript 語法就是當滑鼠移到圖片時,圖片旁</a> <a class="sideA" id="GuestBook_Link108" title="phptw 於 2009-05-04 17:06 發表留言 我是使用 自訂的plung 內容如下：&lt;div class=&quot;bookmark&quot; style=&quot;width:100%;&quot; align=&quot;right&quot;&gt;    &lt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;via=&#39;&#39;;if(document.referrer)via=document.referrer;if(typeof(_ref)!=&#39;undefined&#39;)via=_ref;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://www.hemidemi.com/user_bookmark/new?title=&#39;+encodeURIComponent(document.title)+&#39;&amp;url=&#39;+encodeURIComponent(location.href)+&#39;&amp;description=&#39;+encodeURIComponent(desc)+&#39;&amp;via=&#39;+encodeURIComponent(via)));&quot;&gt;&lt;img src=&quot;/imgs/tags/hemidemi.jpg&quot; title=&quot;HemiDemi&quot; alt=&quot;HemiDemi&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- HemiDemi //--&gt;span&gt;加入書籤:&lt;/span&gt;    &lt;a href=&quot;javascript:(function(){d=document;w=window;t=&#39;&#39;;if(d.selection){t=d.selection.createRange().text;}else{if(d.getSelection){t=d.getSelection();}else{if(w.getSelection){t=w.getSelection()}}}void(window.open(&#39;http://myshare.url.com.tw/index.php?func=newurl&amp;from=mysharepop&amp;url=&#39;+encodeURIComponent(location.href)+&#39;&amp;desc=&#39;+escape(document.title)+&#39;&amp;contents=&#39;+escape(t),&#39;newwin&#39;))})();&quot;&gt;&lt;img src=&quot;/imgs/tags/myshare.jpg&quot; title=&quot;MyShare&quot; alt=&quot;MyShare&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- MyShare //--&gt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://cang.baidu.com/do/add?iu=&#39;+encodeURIComponent(location.href)+&#39;&amp;it=&#39;+encodeURIComponent(document.title)+&#39;&amp;dc=&#39;+encodeURIComponent(desc)));&quot;&gt;&lt;img src=&quot;/imgs/tags/baidu.gif&quot; title=&quot;Baidu&quot; alt=&quot;Baidu&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Baidu //--&gt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://www.google.com/bookmarks/mark?op=add&amp;bkmk=&#39;+encodeURIComponent(location.href)+&#39;&amp;title=&#39;+encodeURIComponent(document.title)+&#39;&amp;annotation=&#39;+encodeURIComponent(desc)));&quot;&gt;&lt;img src=&quot;/imgs/tags/google.jpg&quot; title=&quot;Google Bookmarks&quot; alt=&quot;Google Bookmarks&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Google Bookmarks //--&gt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://tw.myweb2.search.yahoo.com/myresults/bookmarklet?t=&#39;+encodeURIComponent(document.title)+&#39;&amp;u=&#39;+encodeURIComponent(window.location.href)+&#39;&amp;d=&#39;+encodeURIComponent(desc)+&#39;&amp;ei=UTF-8&#39;));&quot;&gt;&lt;img src=&quot;/imgs/tags/yahoo.jpg&quot; title=&quot;Yahoo! My Web&quot; alt=&quot;Yahoo! My Web&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Y! MyWeb //--&gt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://del.icio.us/post?title=&#39;+encodeURIComponent(document.title)+&#39;&amp;url=&#39;+encodeURIComponent(location.href)+&#39;&amp;notes=&#39;+encodeURIComponent(desc)));&quot;&gt;&lt;img src=&quot;/imgs/tags/delicious.jpg&quot; title=&quot;Del.icio.us&quot; alt=&quot;Del.icio.us&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Del.icio.us //--&gt;    &lt;a href=&quot;javascript:desc=&#39;&#39;;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://digg.com/submit?phase=2&amp;url=&#39;+encodeURIComponent(location.href)+&#39;&amp;title=&#39;+encodeURIComponent(document.title)+&#39;&amp;bodytext=&#39;+encodeURIComponent(desc)));&quot;&gt;&lt;img src=&quot;/imgs/tags/digg.jpg&quot; title=&quot;Digg&quot; alt=&quot;Digg&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Digg //--&gt;    &lt;a href=&quot;javascript:void window.open(&#39;http://technorati.com/faves?add=&#39;+encodeURIComponent(location.href)+&#39;&amp;title=&#39;+encodeURIComponent(document.title))&quot;&gt;&lt;img src=&quot;/imgs/tags/technorati.jpg&quot; title=&quot;technorati&quot; alt=&quot;technorati&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- Technorati //--&gt;    &lt;a href=&quot;javascript:(function(){d=document;t=d.selection?(d.selection.type!=&#39;None&#39;?d.selection.createRange().text:&#39;&#39;):(d.getSelection?d.getSelection():&#39;&#39;);var%20furlit=window.open(&#39;http://www.furl.net/storeIt.jsp?t=&#39;+encodeURIComponent(d.title)+&#39;&amp;u=&#39;+escape(d.location.href)+&#39;&amp;r=&#39;+escape(d.referrer)+&#39;&amp;c=&#39;+encodeURIComponent(t)+&#39;&amp;p=1&#39;);})();&quot;&gt;&lt;img src=&quot;/imgs/tags/furl.jpg&quot; title=&quot;furl&quot; alt=&quot;furl&quot; border=&quot;0&quot;&gt;&lt;/a&gt; &lt;!-- furl //--&gt;    &lt;a href=&quot;javascript:q=(document.location.href);void(open(&#39;http://www.youpush.net/submit.php?url=&#39;+escape(q),&#39;&#39;,&#39;resizable,location,menubar,toolbar,scrollbars,status&#39;))&quot;&gt; &lt;img src=&quot;/imgs/tags/yp.gif&quot; alt=&quot;加入此網頁到:YouPush&quot; border=&quot;0&quot; valign=&quot;middle&quot;&gt;&lt;/a&gt; &lt;!-- YouPush.net //--&gt;    &lt;!-- Funp &lt;a href=&quot;http://funp.com/pages/submit/add.php?&amp;via=tools&quot; title=&quot;貼到funP&quot;&gt;&lt;img src=&quot;/imgs/tags/funp.gif&quot; border=&quot;0&quot;/&gt;&lt;/a&gt; --&gt;    &lt;!-- udn //--&gt; &lt;a href=&quot;javascript:desc=&#39;&#39;;via=&#39;&#39;;if(document.referrer)via=document.referrer;if(typeof(_ref)!=&#39;undefined&#39;)via=_ref;if(window.getSelection)desc=window.getSelection();if(document.getSelection)desc=document.getSelection();if(document.selection)desc=document.selection.createRange().text;void(open(&#39;http://bookmark.udn.com/add?f_TITLE=&#39;+encodeURIComponent(document.title)+&#39;&amp;f_URL=&#39;+encodeURIComponent(location.href)+&#39;&amp;f_DIGEST=&#39;+encodeURIComponent(desc)+&#39;&amp;via=&#39;+encodeURIComponent(via)));&quot;&gt;&lt;img src=&quot;/imgs/tags/udn.gif&quot; border=&quot;0&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;     &lt;!-- funp --&gt; &lt;script language=&quot;JavaScript&quot; src=&quot;http://funp.com/tools/button.php?&amp;s=8&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt;  &lt;/div&gt;"  href="guestbook.html#book108">我是使用 自訂的plung 內容如下：&lt;div class=&quot;bookm</a> <a class="sideA" id="GuestBook_Link107" title="phptw 於 2009-05-04 17:05 發表留言 初步判定 ，應該是使用了兩個不同名稱的 「dphighlighter」 plung"  href="guestbook.html#book107">初步判定 ，應該是使用了兩個不同名稱的 「dphighlighter」 plung</a> <a class="sideA" id="GuestBook_Link106" title="原木 於 2009-05-01 09:13 發表留言 請大大幫我看一下底下這篇文章http://beau.tw/read-87.html我是有用 dphighlighter 模組但為何會出現兩次勒?我是依照以下這篇文章 Hack 的http://www.f2cont.com/read-6.html 謝謝喔"  href="guestbook.html#book106">請大大幫我看一下底下這篇文章http://beau.tw/read-87.html我是有用 </a> <a class="sideA" id="GuestBook_Link94" title="榮哥 於 2009-04-19 21:24 發表留言 站長您好我也是使用F2blog.cont想請問一各問題,就是閱讀您得每一篇文章在文章的最下面有&quot;加入書籤&quot;  的功能不知道站長可以交我要修改哪裡才可以達到和您一樣的功能因為我想把一些加入書籤的功能加進去希望站長可交我ㄧ下"  href="guestbook.html#book94">站長您好我也是使用F2blog.cont想請問一各問題,就是閱讀您得每一篇文章在文章的最</a> <a class="sideA" id="GuestBook_Link86" title="phptw 於 2009-03-20 10:02 發表留言 你好～能否提供更多的資訊，才能資料問題出在哪"  href="guestbook.html#book86">你好～能否提供更多的資訊，才能資料問題出在哪</a> <a class="sideA" id="GuestBook_Link85" title="nono 於 2009-03-19 10:26 發表留言 該內容只有管理員可見"  href="guestbook.html#book85">該內容只有管理員可見</a> </div>
                </div>
                <div class="Pfoot"></div>
            </div>
            <!--最新評論-->
            <div class="sidepanel" id="Side_Comment">
                <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Comment')">最新評論</h4>
                <div class="Pcontent" id="content_Comment" style="display:">
                    <div class="CommentTable" id="Comment_Body"> <a class="sideA" id="comments_Link_340" title="Raylea 於 2009-07-07 00:20 發表評論 This site [url=http://www.freewebtown.com/dilkibat/viramune]viramune prescribing information[/url] is about viramune prescribing information."  href="read-143.html#book340">This site [url=http://www.freewebtown.com/dilkibat</a> <a class="sideA" id="comments_Link_339" title="Elizabeth 於 2009-07-07 00:16 發表評論 This site [url=http://www.freewebs.com/acuagulha/synthroid]synthroid for sale[/url] is about synthroid for sale."  href="read-46.html#book339">This site [url=http://www.freewebs.com/acuagulha/s</a> <a class="sideA" id="comments_Link_338" title="Dannielle 於 2009-07-07 00:08 發表評論 This site [url=http://www.freewebtown.com/dfqa/soma]soma[/url] is about soma prices."  href="read-69.html#book338">This site [url=http://www.freewebtown.com/dfqa/som</a> <a class="sideA" id="comments_Link_337" title="Austin 於 2009-07-06 23:37 發表評論 This site [url=http://heddan.2trom.com/accutane]accutane[/url] is about roaccutane."  href="read-115.html#book337">This site [url=http://heddan.2trom.com/accutane]ac</a> <a class="sideA" id="comments_Link_336" title="Dara 於 2009-07-06 23:37 發表評論 This site [url=http://eskuvoszerviz.extra.hu/norvasc]norvasc[/url] is about norvasc order."  href="read-114.html#book336">This site [url=http://eskuvoszerviz.extra.hu/norva</a> <a class="sideA" id="comments_Link_335" title="Venus 於 2009-07-06 23:15 發表評論 This site [url=http://zivotopisy.szm.sk/atenolol]life[/url] is about life."  href="read-174.html#book335">This site [url=http://zivotopisy.szm.sk/atenolol]l</a> <a class="sideA" id="comments_Link_334" title="Ilia 於 2009-07-06 23:13 發表評論 This site [url=http://juegosjava.110mb.com/dilantin]dilantin dosing[/url] is about dilantin dosing."  href="read-142.html#book334">This site [url=http://juegosjava.110mb.com/dilanti</a> <a class="sideA" id="comments_Link_307" title="原木 於 2009-05-08 16:08 發表評論 該內容只有管理員可見"  href="read-100.html#book307">該內容只有管理員可見</a> <a class="sideA" id="comments_Link_306" title="phptw 於 2009-05-08 12:10 發表評論 好像是要得，F2blog.cont 1.0 build 11.30 當初並非我發佈的，當初在發布 F2blog.cont 1.0 build 11.30 似乎沒有把它納入。"  href="read-114.html#book306">好像是要得，F2blog.cont 1.0 build 11.30 當初並非我發佈的，當初在發布</a> <a class="sideA" id="comments_Link_305" title="原木 於 2009-05-08 11:59 發表評論 請教站大若已經更新到 F2blog.cont 1.0 build 11.30還需要這個 plugin 嗎?草稿功能還修要 patch 嗎??Thanks .."  href="read-114.html#book305">請教站大若已經更新到 F2blog.cont 1.0 build 11.30還需要這個 pl</a> </div>
                </div>
                <div class="Pfoot"></div>
            </div>
            <!--連結-->
            <div class="sidepanel" id="Side_Links">
                <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_Links')">連結</h4>
                <div class="Pcontent" id="content_Links" style="display:">
                    <div class="LinksTable" id="Links_Body">
                        <div id="linksgroup_3">酷站連結</div>
                        <a class="sideA" id="Link_3" title="台灣PHP研發中心 php@tw" href="http://www.phptw.idv.tw" target="_blank">台灣PHP研發中心 php@tw</a> <a class="sideA" id="Link_15" title="iware" href="http://www.iware.com.tw" target="_blank">iware</a> <a class="sideA" id="Link_19" title="露天拍賣" href="http://class.ruten.com.tw/user/index.php?sid=tengji" target="_blank">露天拍賣</a> <a class="sideA" id="Link_20" title="Mr. 6" href="http://mr6.cc" target="_blank">Mr. 6</a> <a class="sideA" id="Link_42" title="Aug 9 - 創意。行銷。創業。成功學" href="http://blog.xuite.net/aug9/aug9" target="_blank">Aug 9 - 創意。行銷。創業。成功學</a> <a class="sideA" id="Link_43" title="Mr./Ms. Days (MMDays" href="http://mmdays.com/" target="_blank">Mr./Ms. Days (MMDays</a> <a class="sideA" id="Link_44" title="社交網站收集與研究" href="http://socialnetwork.mmdays.com/" target="_blank">社交網站收集與研究</a> <a class="sideA" id="Link_45" title="W3Schools" href="http://www.w3schools.com/" target="_blank">W3Schools</a> <a class="sideA" id="Link_46" title="google Code" href="http://code.google.com/" target="_blank">google Code</a> <a class="sideA" id="Link_47" title="Yahoo! Developer" href="http://developer.yahoo.com/" target="_blank">Yahoo! Developer</a> <a class="sideA" id="Link_48" title="YDN 開發者社群" href="http://tw.developer.yahoo.com/" target="_blank">YDN 開發者社群</a>
                        <div id="linksgroup_4">PHP</div>
                        <a class="sideA" id="Link_6" title="phpclasse" href="http://www.phpclasses.org" target="_blank">phpclasse</a> <a class="sideA" id="Link_14" title="php.net" href="http://www.php.net" target="_blank">php.net</a> <a class="sideA" id="Link_34" title="Free PHP Scripts" href="http://scripts.ringsworld.com/" target="_blank">Free PHP Scripts</a> <a class="sideA" id="Link_36" title="香港 PHP 用家社區" href="http://www.hkpug.net/" target="_blank">香港 PHP 用家社區</a> <a class="sideA" id="Link_38" title="PHP 網路雜誌" href="http://www.phparch.com/c/phpa/index" target="_blank">PHP 網路雜誌</a> <a class="sideA" id="Link_39" title="PHP同盟會" href="http://www.dophp.net/index.php" target="_blank">PHP同盟會</a> <a class="sideA" id="Link_40" title="PHP俱樂部" href="http://club.21php.com/index.php?" target="_blank">PHP俱樂部</a> <a class="sideA" id="Link_41" title="phpchina" href="http://www.phpchina.com" target="_blank">phpchina</a> <a class="sideA" id="Link_49" title="phpriot" href="http://www.phpriot.com/" target="_blank">phpriot</a>
                        <div id="linksgroup_5">網際網路</div>
                        <a class="sideA" id="Link_21" title="neo" href="http://www.neo.com.tw/" target="_blank">neo</a> <a class="sideA" id="Link_35" title="何飛鵬：社長的筆記本" href="http://blog.pixnet.net/feipengho" target="_blank">何飛鵬：社長的筆記本</a> <a class="sideA" id="Link_37" title="Search-This" href="http://www.search-this.com/" target="_blank">Search-This</a> <a class="sideA" id="Link_50" title="冰雨飘痕" href="HTTP://BLOG.EOOD.CN" target="_blank">冰雨飘痕</a>
                        <div id="linksgroup_6">技術手冊</div>
                        <a class="sideA" id="Link_22" title="PHP中文手冊" href="http://www.phptw.idv.tw/doc/php_manual_tw" target="_blank">PHP中文手冊</a> <a class="sideA" id="Link_23" title="MySQL 5.1 中文手冊" href="http://www.phptw.idv.tw/doc/mysql51cht/" target="_blank">MySQL 5.1 中文手冊</a> <a class="sideA" id="Link_24" title="Zend Framework API" href="http://www.phptw.idv.tw/doc/zend_api" target="_blank">Zend Framework API</a> <a class="sideA" id="Link_25" title="Zend Framework 手冊" href="http://www.phptw.idv.tw/doc/zend_manual" target="_blank">Zend Framework 手冊</a> <a class="sideA" id="Link_26" title="smarty 2.6.14 手冊" href="http://www.phptw.idv.tw/doc/smarty_2_6_14/manual/" target="_blank">smarty 2.6.14 手冊</a> <a class="sideA" id="Link_27" title="Cake Framework 手冊" href="http://www.phptw.idv.tw/doc/cake/" target="_blank">Cake Framework 手冊</a> <a class="sideA" id="Link_28" title="ADODB 手冊" href="http://www.phptw.idv.tw/doc/adodb" target="_blank">ADODB 手冊</a> <a class="sideA" id="Link_29" title="MySQL4.1 手冊" href="http://www.phptw.idv.tw/doc/mysql41" target="_blank">MySQL4.1 手冊</a> <a class="sideA" id="Link_30" title="MySQL5.0 手冊" href="http://www.phptw.idv.tw/doc/mysql50/" target="_blank">MySQL5.0 手冊</a> <a class="sideA" id="Link_31" title="MySQL5.1 手冊" href="http://www.phptw.idv.tw/doc/mysql51/" target="_blank">MySQL5.1 手冊</a> <a class="sideA" id="Link_32" title="MySQL6.0 手冊" href="http://www.phptw.idv.tw/doc/mysql60/" target="_blank">MySQL6.0 手冊</a> <a href="index.php?load=applylink">&gt;&gt;&gt; 申請連結 &lt;&lt;&lt;</a> </div>
                </div>
                <div class="Pfoot"></div>
            </div>
            <!--統計-->
            <div class="sidepanel" id="Side_BlogInfo">
                <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_BlogInfo')">統計</h4>
                <div class="Pcontent" id="content_BlogInfo" style="display:">
                    <div class="BlogInfoTable" id="BlogInfo_Body">總訪問量: 100760<br />
                        日誌數量: 170<br />
                        評論數量: 44<br />
                        標籤數量: 32<br />
                        留言數量: 41 </div>
                </div>
                <div class="Pfoot"></div>
            </div>
            <!--FlashDailyStatistics-->
            <div id="Side_flashDailyStatistics" class="sidepanel">
                <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('content_flashDaily')">Flash Daily Statistics</h4>
                <div class="Pcontent" id="content_flashDaily" style="display:">
                    <object style="border:0px solid;" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="165" height="93.5">
                        <param name="allowScriptAccess" value="sameDomain" />
                        <param name="movie" value="plugins/flashDaily/counter.swf?counter=7"/>
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#ffffff" />
                    </object>
                </div>
                <div class="Pfoot"></div>
            </div>
            <div id="sidebar-bottomimg"></div>
        </div>
    </div>
    <div style="clear: both;height:1px;overflow:hidden;margin-top:-1px;"></div>
</div>
<!--底部-->
<div id="foot">
    <p> <strong><a href="mailto:service@phptw.idv.tw">頭文字「豬」</a></strong> 's blog
        Powered By <a href="http://www.f2cont.com" target="_blank"><strong>F2blog.cont 1.0 Beta 090628</strong></a> CopyRight 2006  - 2009 <a href="http://validator.w3.org/check/referer" target="_blank">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/validator-uri.html" target="_blank">CSS</a> | <a href="archives/index.php" target="_blank">Archiver</a> | <a href="googlesitemap.php" target="_blank">Sitemap</a> </p>
    <p style="font-size:11px;"> <a href="http://www.8gov.com/blog/" target="_blank"><strong>Google Style</strong></a> 程序維護 By <a href="http://blog.phptw.idv.tw" target="_blank"><strong>墮落程式</strong></a> Design by <a href="mailto:info@8gov.com">8gov</a> Skin from pjblog
    <div style="display:none;">
        <script type="text/javascript" src="http://tw.js.webmaster.yahoo.com/35482/ystat.js"></script>
        <noscript>
        <a href="http://tw.webmaster.yahoo.com"><img src=http://tw.img.webmaster.yahoo.com/35482/ystats.gif></a>
        </noscript>
    </div>
    <br />
    Processed in <b>0.027357</b> second(s), <b>3</b> queries, Gzip enabled<br />
    </p>
</div>
</div>
<script class="javascript" src="plugins/dphighlighter/js/shCore.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushCSharp.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushPhp.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushJScript.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushJava.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushVb.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushSql.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushXml.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushDelphi.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushPython.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushRuby.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushCss.js"></script>
<script class="javascript" src="plugins/dphighlighter/js/shBrushCpp.js"></script>
<script class="javascript">dp.SyntaxHighlighter.HighlightAll('code');</script>
<link type="text/css" rel="stylesheet" href="SyntaxHighlighter/css/SyntaxHighlighter.css">
</link>
<script language="javascript" src="SyntaxHighlighter/js/shCore.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushCSharp.js"></script>
<script language="javascript" src="SyntaxHighlighter/js/shBrushXml.js"></script>
<script language="javascript"> 
dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/js/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('code');
</script>
</body>
</html>

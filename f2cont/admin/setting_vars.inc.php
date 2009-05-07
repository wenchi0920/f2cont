<?php  
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//博客设定
//@params:第一个为控件类型：sec表示每个分区的标题，t表示文本框(输入内容无限制)，r表示单选框，f表示文件框，ta表示文本区域，tn表示限输入数字的文本框sel表示选择框，c表示复选框。（必须）
//@params:第二个为控件的说明性文字。（必须）
//@params:第三个为控件的名称 （为sec类型是可选）
//@params:第四个参数为控件的默认参数 （可以为空）
//@params:第五个参数为控件的标识内容 （为sel类型时，该内容为选择的内容，用｜分隔，其它类型是为紧跟控件的文字。说明文字=>值

$default_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-17);

$SectionBlog[]=addSettingValue("sec", $strBasicInfo);
$SectionBlog[]=addSettingValue("t", $strName, "name", "My F2Blog");
$SectionBlog[]=addSettingValue("t", $strTitle, "blogTitle", "Free & Freedom Blog");
$SectionBlog[]=addSettingValue("t", $strBlogUrl, "blogUrl", $default_url);
$SectionBlog[]=addSettingValue("t", $strSettingKeywords, "blogKeyword");
$SectionBlog[]=addSettingValue("f", $strLogo, "logo");
$SectionBlog[]=addSettingValue("f", $strFaviconMemo, "favicon");
$SectionBlog[]=addSettingValue("t", $strMaster, "master");
$SectionBlog[]=addSettingValue("t", $strEmail, "email");
$SectionBlog[]=addSettingValue("t", $strForumUsername, "forum_user","","<br />$strForumDataHelp");

$SectionBlog[]=addSettingValue("sec", $strSettingBlogsOption);
$SectionBlog[]=addSettingValue("r", $strCloseBlog, "status", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionBlog[]=addSettingValue("ta", $strCloseReason, "closeReason","Sorry, the blog is temporarily closed for maintenance.");
$SectionBlog[]=addSettingValue("r", $strLoginStatus, "loginStatus", "1", "$strTbOpen=>0|$strTbClose=>1");
$SectionBlog[]=addSettingValue("r", $strSettingBlogsRegister, "isRegister", "1", "$strTbOpen=>0|$strTbClose=>1");
$SectionBlog[]=addSettingValue("ta", $strSettingBlogsRegisterReason, "registerClose","Sorry, registration has been disabled.");

$SectionBlog[]=addSettingValue("sec", $strSettingBlogsBases);
$SectionBlog[]=addSettingValue("t", $strSettingOnlineTime, "onlineTime", "3600", "$strSettingOnlineSecond<br />$strSettingOnlineHelp");
$SectionBlog[]=addSettingValue("sel", $strLanguage, "language", "zh_cn", "$strSChinese=>zh_cn|$strTChinese=>zh_tw|$strEnglish=>en");
$SectionBlog[]=addSettingValue("sel", $strTimezone, "timezone", "8", "$strTimeSet=>$strTimeNum");
$SectionBlog[]=addSettingValue("t", $strTimeSystemFormat, "timeSystemFormat", "Y-m-d H:i");
$SectionBlog[]=addSettingValue("r", $strSettingValidComment, "isValidateCode", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionBlog[]=addSettingValue("r", $strSettingValidLogin, "loginvalid", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionBlog[]=addSettingValue("r", $strSettingValidUser, "uservalid", "0", "$strTbOpen=>1|$strTbClose=>0");

//侧边栏设定
$SectionSidebar[]=addSettingValue("sec", $strSettingSideBar);
$SectionSidebar[]=addSettingValue("tn", $strSettingLogsPage, "sidelogsPage", "10", $strSettingUnitsRecord);
$SectionSidebar[]=addSettingValue("tn", $strSettingSidebarLogsLength, "sidelogslength", "12", "$strSettingUnitsWords");
$SectionSidebar[]=addSettingValue("t", $strSettingSidebarLogs, "sidelogsstyle", "{title}", "<br />$strSettingSidebarCommentHelp");
$SectionSidebar[]=addSettingValue("tn", $strSettingCommentPage, "commPage", "10", $strSettingUnitsRecord);
$SectionSidebar[]=addSettingValue("tn", $strSettingSidebarCommentLength, "sidecommentlength", "12", "$strSettingUnitsWords");
$SectionSidebar[]=addSettingValue("t", $strSettingSidebarComment, "sidecommentstyle", "{title}", "<br />$strSettingSidebarCommentHelp");
$SectionSidebar[]=addSettingValue("tn", $strSettingGbookPage, "gbookPage", "10", $strSettingUnitsRecord);
$SectionSidebar[]=addSettingValue("tn", $strSettingSidebarGbookLength, "sidegbooklength", "12", "$strSettingUnitsWords");
$SectionSidebar[]=addSettingValue("t", $strSettingSidebarGbook, "sidegbookstyle", "{title}", "<br />$strSettingSidebarCommentHelp");
$SectionSidebar[]=addSettingValue("tn", $strSettingTrackbackPage, "trackNums", "10", $strSettingUnitsRecord);
$SectionSidebar[]=addSettingValue("tn", $strSettingTagsMaxNumber, "tagNums", "50", $strSettingUnitsRecord);
$SectionSidebar[]=addSettingValue("tn", $strSettingArchivesMonth, "archivesmonth", "5", $strSettingUnitsMonths);
$SectionSidebar[]=addSettingValue("sel", $strSettingCategoryImgPath, "categoryImgPath", "images/tree/folder_gray", "{$strSettingSidebarMenuTree} - gray=>images/tree/folder_gray|{$strSettingSidebarMenuTree} - base=>images/tree/base|{$strSettingSidebarMenuTree} - yellow=>images/tree/folder_yellow|$strSettingSidebarMenuNews=>0");
$SectionSidebar[]=addSettingValue("r", $strSettingCategoryTree, "treeCategory", "0", "$strYes=>1|$strNo=>0");
$SectionSidebar[]=addSettingValue("r", $strSeachShowStatus, "disSearch", "0", "$strShow=>1|$strHidden=>0");
$SectionSidebar[]=addSettingValue("r", $strApplyLinkShow, "applylink", "1", "$strTbOpen=>1|$strTbClose=>0");

if (preg_match("/http:\/\//is",$settingInfo['linklogo'])){
	$logopath=$settingInfo['linklogo'];
}else{
	$logopath=$settingInfo['blogUrl'].$settingInfo['linklogo'];
}

$SectionSidebar[]=addSettingValue("t", $strSettingLinksLogo, "linklogo", "images/logo.gif", "<img src=\"{$logopath}\" id=\"linklogoimg\" alt=\"\"> $strSettingLinksLogoHelp");
$SectionSidebar[]=addSettingValue("r", $strSettingLinksShow, "linkshow", "0", "$strSettingLinksSame=>1|$strSettingLinksGroup=>0");
$SectionSidebar[]=addSettingValue("tn", $strLinksMarquee, "linkmarquee", "0", "$strLinksMarqueeHelp");

$SectionSidebar[]=addSettingValue("sec", $strSettingTotalOption);
$SectionSidebar[]=addSettingValue("r", $strStatisticsToday, "showtoday", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsYesterday, "showyester", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsTotal, "showtotal", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsOnline, "showonline", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsLogs, "showlogs", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsComments, "showcomment", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsGuestbook, "showguest", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsCategory, "showcate", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsTags, "showtags", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsUser, "showuser", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsAttachments, "showattach", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionSidebar[]=addSettingValue("r", $strStatisticsQuote, "showquote", "0", "$strTbOpen=>1|$strTbClose=>0");

//主体栏设定
$SectionContent[]=addSettingValue("sec", $strSettingContent);
$SectionContent[]=addSettingValue("r", $strDisType, "disType", "0", "$strNormal=>0|$strList=>1");
$SectionContent[]=addSettingValue("t", $strContentsCurrDateTime, "currFormatDate", "Y-m-d H:i", $strContentsDateTimeHelp);
$SectionContent[]=addSettingValue("t", $strContentsListDateTime, "listFormatDate", "m-d", $strContentsDateTimeHelp);
$SectionContent[]=addSettingValue("r", $strLogsTagsShow, "disTags", "1", "$strShow=>1|$strHidden=>0");
$SectionContent[]=addSettingValue("r", $strSettingKeyword, "showKeyword", "0", "$strShow=>1|$strHidden=>0|($strSettingKeywordHelp)");
$SectionContent[]=addSettingValue("r", $strSeetingAutoUrl, "autoUrl", "0", "$strTbOpen=>1|$strTbClose=>0|($strSettingKeywordHelp)");
$SectionContent[]=addSettingValue("ta", $strSettingHeaderOption, "headcode", "", "<br />$strSettingHeaderCodeHelp");
$SectionContent[]=addSettingValue("ta", $strFooterCode, "footcode", "", "<br />$strFooterCodeHelp$strCodeHelp");
$SectionContent[]=addSettingValue("tn", $strPerPageNormal, "perPageNormal", "8", $strSettingUnitsLogs);
$SectionContent[]=addSettingValue("tn", $strPerPageList, "perPageList", "20", $strSettingUnitsLogs);
$SectionContent[]=addSettingValue("sel", $strSettingLogsPageSet, "pagebar", "A", "$strSettingLogsPageTop=>T|$strSettingLogsPageButtom=>B|$strSettingLogsPageAll=>A");
$SectionContent[]=addSettingValue("tn", $strSettingLogsPerPage, "logspage", "18", $strSettingUnitsPage);
$SectionContent[]=addSettingValue("tn", $strSettingLogsCommentPage, "logscomment", "10", $strSettingUnitsLogs);
$SectionContent[]=addSettingValue("tn", $strSettingLogsGuestBookPage, "logsgbook", "10", $strSettingUnitsLogs);
$SectionContent[]=addSettingValue("r", $strSettingLogsReadPageBar, "readpagebar", "1", "$strHidden=>0|$strShow=>1");
$SectionContent[]=addSettingValue("r", $strIsLinkTagLog, "isLinkTagLog", "1", "$strHidden=>0|$strShow=>1");
$SectionContent[]=addSettingValue("tn", $strLinkTagLog, "linkTagLog", "10", $strSettingUnitsLogs);
$SectionContent[]=addSettingValue("r", $strSettingLogsCommentOrder, "commentOrder", "asc", "$strSettingLogsCommentDesc=>desc|$strSettingLogsCommentASC=>asc");
$SectionContent[]=addSettingValue("r", $strSettingLogsGbookOrder, "gbookOrder", "desc", "$strSettingLogsCommentDesc=>desc|$strSettingLogsCommentASC=>asc");
$SectionContent[]=addSettingValue("tn", $strImageWidthMax, "img_width", "400", "px");
$SectionContent[]=addSettingValue("r", $strWeather, "weatherStatus", "1", "$strShow=>1|$strHidden=>0");
$SectionContent[]=addSettingValue("r", $strGuestBookFaceStatus, "gbface", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionContent[]=addSettingValue("r", $strLogsPrinter, "showPrint", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionContent[]=addSettingValue("r", $strLogsDownload, "showDown", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionContent[]=addSettingValue("r", $strLogsSendMail, "showMail", "0", "$strTbOpen=>1|$strTbClose=>0|($strLogsSendMailHelp)");
$SectionContent[]=addSettingValue("r", $strSettingUBBTools, "showUBB", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionContent[]=addSettingValue("r", $strLinkAlertStyle, "showAlertStyle", "0", "$strTbOpen=>1|$strTbClose=>0");

//其它设定
$SectionOther[]=addSettingValue("sec", $strSettingFooterOption);
$SectionOther[]=addSettingValue("r", $strIsProgramRun, "isProgramRun", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionOther[]=addSettingValue("t", $strAboutGov, "about");

$SectionOther[]=addSettingValue("sec", $strSettingRSSOption);
$SectionOther[]=addSettingValue("tn", $strSettingRssOutNumber, "newRss", "10", $strSettingUnitsLogs);
$SectionOther[]=addSettingValue("r", $strRssContentType, "rssContentType", "1", "$strRssUnitPublic=>1|$strRssAllPublic=>0");
$SectionOther[]=addSettingValue("tn", $strSettingRssOutOption, "rssLength", "500", "$strSettingUnitsWords");

$SectionOther[]=addSettingValue("sec", $strSettingAttachOption);
$SectionOther[]=addSettingValue("tn", $strSettingBackSize, "backupSize", "1024", "K");
$SectionOther[]=addSettingValue("r", $strAttSaveDir, "attachDir", "1", "$strAttSaveType1=>1|$strAttSaveType2=>0");
$SectionOther[]=addSettingValue("r", $strAttachShowTitle, "disAttach", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionOther[]=addSettingValue("t", $strSettingAttachType, "attachType", "gif,jpg,rar,zip,doc,xls,pdf,ico,png,swf,wma,wav,mp3,wmv,rm", "<br />$strSettingUploadType");
$SectionOther[]=addSettingValue("r", $strSettingGenThumb, "genThumb", "0", "$strTbOpen=>1|$strTbClose=>0|<br />{$strSettingGenThumbInfo}");
$SectionOther[]=addSettingValue("t", $strSettingThumbSize, "thumbSize", "200x200", "<br />".$strSettingThumbSizeInfo);
$SectionOther[]=addSettingValue("r", $strLogsEditorZip, "fastEditor", "1", "$strTbOpen=>1|$strTbClose=>0| ({$strLogsEditorZipHelp})");

$SectionOther[]=addSettingValue("sec", $strSettingOtherSecurity);
$SectionOther[]=addSettingValue("r", $strSettingDownload, "downcode", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionOther[]=addSettingValue("tn", $strCommTimerout, "commTimerout", "30", $strSettingOnlineSecond);
$SectionOther[]=addSettingValue("tn", $strCommLength, "commLength", "800", $strSettingUnitsWords);
$SectionOther[]=addSettingValue("r", $strSettingComments, "allowComment", "1", "$strTbOpen=>0|$strTbClose=>1");
$SectionOther[]=addSettingValue("r", $strSettingTrackback, "allowTrackback", "1", "$strTbOpen=>0|$strTbClose=>1");
$SectionOther[]=addSettingValue("r", $strIsTbApp, "isTbApp", "0", "$strTbOpen=>0|$strTbClose=>1");
$SectionOther[]=addSettingValue("ta", $strTbSiteList, "tbSiteList", "", "<br />$strTbSiteListMemo");

$SectionOther[]=addSettingValue("sec", $strSettingOtherNews);
$SectionOther[]=addSettingValue("c", $strAjaxStatus, "ajaxstatus", "", "$strAjaxLogsPassword=>P|$strAjaxCommentBook=>G|$strAjaxTrackBack=>T|$strAjaxCalendar=>C|$strAjaxMediaStatus=>M");
$SectionOther[]=addSettingValue("r", $strSettingGzip, "gzipstatus", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionOther[]=addSettingValue("r", $strSettingRewrite, "rewrite", "0", "$strTbClose=>0|{$strSettingRewritePHP} [<a href=\"testrewrite.php?test=php\" target=\"_blank\">$strSettingRewriteTest</a>]=>1|{$strSettingRewriteApache}  [<a href=\"testrewrite.php?test=rewrite\" target=\"_blank\">$strSettingRewriteTest</a>]=>2|<br />{$strSettingRewriteHelp}");
$SectionOther[]=addSettingValue("r", $strSettingIsHtmlPage, "isHtmlPage", "0", "$strTbOpen=>1|$strTbClose=>0");

//读取编辑器
include_once(F2BLOG_ROOT."./include/xmlparse.inc.php");
$handle=opendir("../editor/"); 
$arr_editorName=array();
$arr_editorPath=array();
while ($file = readdir($handle)){ 
	if (preg_match("/editor_(.+)\.xml/is",$file)){
		$arr_editor=xmlArray(F2BLOG_ROOT."./editor/$file");
		$arr_editorName[]=$arr_editor['EditorDecription']."=>".$arr_editor['EditorName'];
	}	
} 
closedir($handle);

$SectionOther[]=addSettingValue("sec", $strSettingOtherOption);
$SectionOther[]=addSettingValue("tn", $strAutoSplitLogs, "autoSplit", "0", "$strSettingUnitsWords<br />$strAutoSplitLogsHelp");
$SectionOther[]=addSettingValue("r", $strAutoSaveStatus, "autoSave", "0", "$strTbOpen=>1|$strTbClose=>0");
$SectionOther[]=addSettingValue("sel", $strSettingEdits, "defaultedits", "tiny", implode("|",$arr_editorName));
$SectionOther[]=addSettingValue("t", $strSettingMouseOverColor, "mouseovercolor", "#FFFFCC");
$SectionOther[]=addSettingValue("tn", $strSettingAdminPageSize, "adminPageSize", "15", $strSettingUnitsLogs);
$SectionOther[]=addSettingValue("tn", $strSettingAdminLogsPerPage, "adminPerPage", "25", $strSettingUnitsPage);

//读取后台样式
$style_list=array();
$handle=opendir("themes/"); 
while ($themes = readdir($handle)){
	$themes_path="themes/$themes";
	if (is_dir($themes_path) && $themes!="." && $themes!=".."){
		$style_list[] = "$themes=>$themes";
	}	
} 
closedir($handle);
$SectionOther[]=addSettingValue("sel", $strSettingAdminStyle, "adminstyle", "default",@implode("|",$style_list));

//农历设定
$SectionCalendar[]=addSettingValue("sec", $strSettingSideCalendarSet);
$SectionCalendar[]=addSettingValue("r", $strSettingSideCalendar, "showcalendar", "1", "$strTbOpen=>1|$strTbClose=>0");
$SectionCalendar[]=addSettingValue("ta", $strSettingSideGCalendar, "gcalendar", $strGholidayDefault,  "<br />$strSettingSideCalendarHelp");
$SectionCalendar[]=addSettingValue("ta", $strSettingSideNCalendar, "ncalendar", $strNholidayDefault, "<br />$strSettingSideCalendarHelp");

?>
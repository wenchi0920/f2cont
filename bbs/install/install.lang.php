<?php
define('INSTALL_LANG', 'TC_UTF8');

$lang = array
(
	'SC_GBK' => '簡體中文版',
	'TC_BIG5' => '繁體中文版',
	'SC_UTF8' => '簡體中文 UTF8 版',
	'TC_UTF8' => '繁體中文 UTF8 版',
	'EN_ISO' => 'ENGLISH ISO8859',
	'EN_UTF8' => 'ENGLIST UTF-8',

	'title_install' => 'Discuz! Board 程序安裝',

	'username' => '管理員賬號:',
	'username_error' => '帳號不能為空，不能包含特殊字符',
	'password' => '管理員密碼:',
	'password_error' => '管理員密碼不能為空',
	'repeat_password' => '重複密碼',
	'repeat_password_error' => '重複密碼應當和管理員密碼一樣',
	'admin_email' => '管理員 Email:',

	'succeed' => '成功',
	'enabled' => '允許',
	'writeable' => '可寫',
	'readable' => '可讀',
	'unwriteable' => '不可寫',
	'yes' => '可',
	'no' => '不可',
	'unlimited' => '不限',
	'support' => '支持',
	'unsupport' => '<span class="redfont">不支持</span>',
	'old_step' => '上一步',
	'new_step' => '填寫完畢，進行下一步',
	'tips_message' => '提示信息',
	'return' => '返回',
	'error_message' => '錯誤信息',
	'message_return' => '點擊這裡返回',
	'message_forward' => '點擊這裡繼續',
	'error_quit_msg' => '您必須解決以上問題，安裝才可以繼續',
	'check_pass_next_step' => '檢測通過，跳轉到下一步',
	'reg_app_to_ucenter_fail' => '將應用註冊到用戶中心失敗。',

	'env_os' => '操作系統',
	'env_php' => 'PHP 版本',
	'env_mysql' => 'MySQL 支持',
	'env_attach' => '附件上傳',
	'env_diskspace' => '磁盤空間',
	'env_dir_writeable' => '目錄寫入',
	'env_comment' => '提示信息',
	'env' => '提示信息',

	'error_env' => '運行環境檢測失敗',
	'error_unknow_type ' => '程序運行遇到未知性錯誤，請到 Discuz! 支持論壇尋求支持',
	'error_env_require_comment' => '您當前的服務器環境無法安裝或者運行 Discuz!, 請根據以下提示進行修正，然後重新檢測',
	'error_db_config' => '數據庫信息設置錯誤',
	'error_admin_config' => '管理員信息設置錯誤',
	'error_uc_install' => 'UCenter 設置錯誤',
	'error_config_vars' => 'config.inc.php 錯誤',
	'error_config_vars_comment' => '<ul><li>config.inc.php 文件是 Discuz! 論壇運行的必須文件<li>您必須將 config.inc.php 放在論壇的根目錄，且設置為可讀寫狀態(777)<li>每個版本的 config.inc.php 文件都可能會不同，請保證您上傳的版本和論壇的版本是一致的</ul><p><strong>請正確上傳後重新刷新本頁</strong>',

	'init_log' => '初始化記錄',
	'clear_dir' => '清空目錄',
	'select_db' => '選擇數據庫',
	'create_table' => '建立數據表',

	'install_wizard' => '安裝嚮導',
	'current_process' => '當前狀態:',
	'show_license' => 'Discuz! 用戶許可協議',
	'agreement_yes' => '我同意',
	'agreement_no' => '我不同意',
	'check_config' => '檢查配置文件狀態',
	'check_catalog_file_name' => '目錄文件名稱',
	'check_need_status' => '所需狀態',
	'check_currently_status' => '當前狀態',
	'edit_config' => '瀏覽/編輯當前配置',
	'variable' => '設置選項',
	'value' => '當前值',
	'comment' => '註釋',
	'dbhost' => '數據庫服務器:',
	'dbhost_comment' => '數據庫服務器地址, 一般為 localhost',
	'dbuser' => '數據庫用戶名:',
	'dbuser_error' => '連接數據庫失敗，請檢查用戶名',
	'dbpw' => '數據庫密碼:',
	'dbpw_error' => '密碼是否正確',
	'dbname' => '數據庫名:',
	//'dbname_comment' => '數據庫名稱',
	'email' => '信箱 Email:',
	'adminemail' => '系統信箱 Email:',
	'adminemail_comment' => '用於發送程序錯誤報告',
	'tablepre' => '表名前綴:',
	'tablepre_comment' => '同一數據庫運行多個論壇時，請修改前綴',
	'tablepre_prompt' => '除非您需要在同一數據庫安裝多個 Discuz! \n論壇,否則,強烈建議您不要修改表名前綴。',

	'forceinstall' => '發現舊論壇數據:',
	'forceinstall_error' => '當前數據庫當中已經安裝過 Discuz! 論壇，您可以修改「表名前綴」來避免刪除舊的數據，或者選擇強制安裝。強制安裝會刪除舊數據，且無法恢復',
	'agree_forceinstall' => '<span class="red"><b>我要刪除數據，強制安裝 !!!</b></span>',
	'config_nonexistence' => '您的 config.inc.php 不存在, 無法繼續安裝, 請用 FTP 將該文件上傳後再試。',
	'error_discuz_data_exist' => '當前數據庫中已經安裝過 Discuz! 論壇，繼續安裝會清除原有論壇數據。如果您確認要這麼做，請選擇強制安裝。否則請您更換"數據庫"或者修改"表名前綴"',

	'recheck_config' => '重新檢查設置',
	'check_env' => '檢查當前服務器環境',
	'env_required' => 'Discuz! 所需配置',
	'env_best' => 'Discuz! 最佳配置',
	'env_current' => '當前服務器',
	'install_note' => '安裝嚮導提示',
	'add_admin' => '設置管理員賬號',
	'start_install' => '開始安裝 Discuz!',
	'dbname_invalid' => '數據庫名為空，請填寫數據庫名稱',
	'admin_username_invalid' => '非法用戶名，用戶名長度不應當超過 15 個英文字符，且不能包含特殊字符，一般是中文，字母或者數字',
	'admin_password_invalid' => '密碼和上面不一致，請重新輸入',
	'admin_email_invalid' => 'Email 地址錯誤，此郵件地址已經被使用或者格式無效，請更換為其他地址',
	'admin_invalid' => '您的信息管理員信息沒有填寫完整，請仔細填寫每個項目',
	'admin_exist_password_error' => '該用戶已經存在，如果您要設置此用戶為論壇的管理員，請正確輸入該用戶的密碼，或者請更換論壇管理員的名字',

	'config_comment' => '請在下面填寫您的數據庫賬號信息, 通常情況下不需要修改紅色選項內容。',
	'config_unwriteable' => '安裝嚮導無法寫入配置文件, 請設置 config.inc.php 程序屬性為可寫狀態(777)',
	'threadcache_unwriteable' => '主題緩存目錄 <strong>./forumdata/threadcaches 屬性非 777 或無法寫入，在線編輯模板功能將無法使用',
	'ucclientcache_unwriteable' => '緩存目錄 <strong>./uc_client/data/cache</strong> 無法寫入, 請設置屬性為可寫狀態 (777)',

	'database_errno_2003' => '無法連接數據庫，請檢查數據庫是否啟動，數據庫服務器地址是否正確',
	'database_errno_1044' => '無法創建新的數據庫，請檢查數據庫名稱填寫是否正確',
	'database_errno_1045' => '無法連接數據庫，請檢查數據庫用戶名或者密碼是否正確',

	'dbpriv_createtable' => '沒有CREATE TABLE權限，無法安裝論壇',
	'dbpriv_insert' => '沒有INSERT權限，無法安裝論壇',
	'dbpriv_select' => '沒有SELECT權限，無法安裝論壇',
	'dbpriv_update' => '沒有UPDATE權限，無法安裝論壇',
	'dbpriv_delete' => '沒有DELETE權限，無法安裝論壇',
	'dbpriv_droptable' => '沒有DROP TABLE權限，無法安裝論壇',

	'php_version_406' => '服務器 PHP 版本小於 4.0.6, 無法使用 Discuz!',
	'php_version_430' => '服務器 PHP 版本小於 4.3.0, 無法使用 Discuz!。',
	'attach_enabled' => '允許/最大尺寸 ',
	'attach_enabled_info' => '您可以上傳附件的最大尺寸: ',
	'attach_disabled' => '不允許上傳附件',
	'attach_disabled_info' => '附件上傳或相關操作被服務器禁止。',
	'mysql_version_323' => '服務器 MySQL 版本低於 3.23，安裝無法繼續進行',
	'mysql_unsupport' => '服務器不支持 MySql 數據庫，無法安裝論壇程序',
	'template_unwriteable' => '模板目錄 <strong>./templates 屬性非 777 或無法寫入，在線編輯模板功能將無法使用',
	'attach_unwriteable' => '附件目錄 <strong>默認是 ./attachments</strong> 無法寫入，附件上傳功能將無法使用',
	'avatar_unwriteable' => '自定義頭像目錄 <strong>./customavatars</strong> 或無法寫入，上傳頭像功能將無法使用',
	'forumdata_unwriteable' => '數據目錄 <strong>./forumdata</strong> 無法寫入，請設置屬性為可寫狀態 (777)',
	'ftemplates_unwriteable' => '數據目錄 <strong>./forumdata/templates</strong> 無法寫入，請設置屬性為可寫狀態 (777)',
	'cache_unwriteable' => '緩存目錄 <strong>./forumdata/cache</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'uccache_unwriteable' => '緩存目錄 <strong>./uc_server/data/cache</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	//'ucconfig_unwriteable' => '緩存目錄 <strong>./uc_server/data/config.inc.php</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdata_unwriteable' => '緩存目錄 <strong>./uc_server/data</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdatacache_unwriteable' => '緩存目錄 <strong>./uc_server/data/cache</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdataview_unwriteable' => '緩存目錄 <strong>./uc_server/data/view</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdataavatar_unwriteable' => '緩存目錄 <strong>./uc_server/data/avatar</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdatabackup_unwriteable' => '緩存目錄 <strong>./uc_server/data/backup</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdatatmp_unwriteable' => '緩存目錄 <strong>./uc_server/data/tmp</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'ucdatalogs_unwriteable' => '緩存目錄 <strong>./uc_server/data/logs</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'log_unwriteable' => '緩存目錄 <strong>./forumdata/logs</strong> 無法寫入，請設置屬性為可寫狀態 (777)。',
	'tablepre_invalid' => '表名前綴不能包含字符".",不能以數字開頭',
	'db_invalid' => '指定的數據庫不存在, 系統也無法自動建立, 無法安裝 Discuz!。',
	'db_auto_created' => '指定的數據庫不存在, 但系統已成功建立, 可以繼續安裝。',
	'db_not_null' => '數據庫中已經安裝過 Discuz!, 繼續安裝會清空原有數據。',
	'db_drop_table_confirm' => '繼續安裝會清空全部原有數據，您確定要繼續嗎?',
	'install_in_processed' => '正在安裝論壇數據，此過程需要較長時間，請您稍後 ...',
	'install_succeed' => '您現在可以點擊這裡進入論壇',

	'install_finished' => '論壇安裝成功，請按照提示執行最後的安全工作',
	'install_finished_comment' => '<li>恭喜您成功安裝了 Discuz! Board.</li>
		<li>為了保障您的論壇數據安全，建議您立即刪除 <b>install</b> 目錄中的所有文件，以免此文件被他人利用。</li>
		<li>論壇目錄中的 <b>config.inc.php</b> 文件記錄著您的重要信息，建議您將它做好備份。另外此文件中還有些更高級的設置，您可以參考當中的說明進行配置，從而使您的論壇更加安全。</li>
		<li>Discuz! 為您提供很多新功能，這些新功能的使用可能需要您花費一點時間去學習和使用它，在開始使用之前，我們建議您閱讀論壇的使用手冊。</li>
		</ul><ul><br><b>最後，感謝您選用 Comsenz 公司的產品，我們會竭誠為您提供更多更好的程序和服務</b>',

	'uc_appname' => '論壇',
	'uc_appreg' => '註冊',
	'uc_appreg_succeed' => ' UCenter 信息設置成功，下面將開始下一步',
	'uc_continue' => '點擊這裡繼續',
	'uc_title_ucenter' => '請填寫 UCenter 的相關信息',
	'uc_url' => 'UCenter 的 URL',
	'uc_ip' => 'UCenter 的 IP',
	'uc_ip_comment' => '當前服務器無法解析 UCenter 的 IP 地址，請您手工填寫',
	'uc_ip_error' => '連接的過程中出了點問題，請您填寫服務器 IP 地址，如果您的 UC 與論壇裝在同一服務器上，我們建議您嘗試填寫 127.0.0.1',
	'uc_adminpw' => 'UCenter 創始人密碼',
	'uc_app_name' => '論壇的名稱',
	'uc_app_name_comment' => '安裝完畢後可在後台修改',
	'uc_app_url' => '論壇的安裝地址',
	'uc_app_url_comment' => '可手工填寫或使用程序檢測到的',
	'uc_app_ip' => '的 IP',
	'uc_app_ip_comment' => '當主機 DNS 有問題時需要設置，默認請保留為空',
	'uc_connent_invalid' => '連接服務器失敗，請檢查 URL ',
	'uc_version_incorrect' => '您的 UCenter 服務端版本 ('.@$ucversion.') 過低，請升級 UCenter 服務端到最新版本，並且升級，下載地址：http://www.comsenz.com/ 。',
	'uc_dbcharset_incorrect' => 'UCenter 服務端字符集與當前應用的字符集不同，請下載 '.@$ucdbcharset.' 編碼的 Discuz! 論壇進行安裝。',

	'error_config_write' => '讀寫 config.inc.php 失敗，無法將設置寫入 config.inc.php，請將論壇 config.inc.php 的屬性設置為可讀寫狀態(777)',

	'uc_url_empty' => '您沒有填寫 UCenter 的 URL，請返回填寫。',
	'uc_url_invalid' => 'URL 格式錯誤',
	'uc_url_unreachable' => 'UCenter 的 URL 地址可能填寫錯誤，請檢查',
	'uc_ip_invalid' => '無法解析該域名，請填寫站點的 IP</font>',
	'uc_admin_invalid' => '密碼錯誤，請重新填寫',
	'uc_data_invalid' => '通信失敗，請檢查 URL 地址是否正確 ',

	'tagtemplates_subject' => '標題',
	'tagtemplates_uid' => '用戶 ID',
	'tagtemplates_username' => '發帖者',
	'tagtemplates_dateline' => '日期',
	'tagtemplates_url' => '主題地址',

	'tips_uc_install' => '本安裝程序會檢測服務器環境是否達到論壇所需的最低標準',
	'tips_uc_install_comment' => '程序會檢測PHP版本號、所依賴的庫函數、目錄是否可寫等信息',

	'tips_env_check' => '本安裝程序會檢測服務器環境是否達到論壇所需的最低標準',
	'tips_env_check_comment' => '程序會檢測PHP版本號、所依賴的庫函數、目錄是否可寫等信息',
	
	'tips_db_config' => '填寫論壇數據庫信息',
	'tips_db_config_comment' => '',
	'tips_admin_config' => '填寫論壇管理員信息',
	'tips_admin_config_comment' => '',

	'tips_install_process' => '安裝論壇並設置初始數據',

	'step_1' => '檢測運行環境',
	'step_1_comment' => '檢測服務器環境是否達到論壇所需的最低標準。',

	'step_2' => '設置基本信息',
	'step_2_comment' => '請填寫論壇 數據庫 和 管理員 信息',

	'step_3' => '安裝初始數據',
	'step_3_comment' => '安裝論壇並設置初始數據',

	'step_4' => '安裝完成',
	'step_4_comment' => '論壇安裝成功，請按照提示執行最後的安全工作',

	'init_credits_karma' => '威望',
	'init_credits_money' => '金錢',

	'init_group_0' => '會員',
	'init_group_1' => '管理員',
	'init_group_2' => '超級版主',
	'init_group_3' => '版主',
	'init_group_4' => '禁止發言',
	'init_group_5' => '禁止訪問',
	'init_group_6' => '禁止 IP',
	'init_group_7' => '遊客',
	'init_group_8' => '等待驗證會員',
	'init_group_9' => '乞丐',
	'init_group_10' => '新手上路',
	'init_group_11' => '註冊會員',
	'init_group_12' => '中級會員',
	'init_group_13' => '高級會員',
	'init_group_14' => '金牌會員',
	'init_group_15' => '論壇元老',

	'init_rank_1' => '新生入學',
	'init_rank_2' => '小試牛刀',
	'init_rank_3' => '實習記者',
	'init_rank_4' => '自由撰稿人',
	'init_rank_5' => '特聘作家',

	'init_cron_1' => '清空今日發帖數',
	'init_cron_2' => '清空本月在線時間',
	'init_cron_3' => '每日數據清理',
	'init_cron_4' => '生日統計與郵件祝福',
	'init_cron_5' => '主題回復通知',
	'init_cron_6' => '每日公告清理',
	'init_cron_7' => '限時操作清理',
	'init_cron_8' => '論壇推廣清理',
	'init_cron_9' => '每月主題清理',
	'init_cron_10' => '每日 X-Space更新用戶',
	'init_cron_11' => '每週主題更新',

	'init_bbcode_1' => '使內容橫向滾動，這個效果類似 HTML 的 marquee 標籤，注意：這個效果只在 Internet Explorer 瀏覽器下有效。',
	'init_bbcode_2' => '嵌入 Flash 動畫',
	'init_bbcode_3' => '顯示 QQ 在線狀態，點這個圖標可以和他（她）聊天',
	'init_bbcode_4' => '上標',
	'init_bbcode_5' => '下標',
	'init_bbcode_6' => '嵌入 Windows media 音頻',
	'init_bbcode_7' => '嵌入 Windows media 音頻或視頻',

	'init_qihoo_searchboxtxt' =>'輸入關鍵詞,快速搜索本論壇',
	'init_threadsticky' =>'全局置頂,分類置頂,本版置頂',

	'init_default_style' => '默認風格',
	'init_default_forum' => '默認版塊',
	'init_default_template' => '默認模板套系',
	'init_default_template_copyright' => '康盛創想（北京）科技有限公司',

	'init_dataformat' => 'Y-n-j',
	'init_modreasons' => '廣告/SPAM\r\n惡意灌水\r\n違規內容\r\n文不對題\r\n重複發帖\r\n\r\n我很贊同\r\n精品文章\r\n原創內容',
	'init_link' => 'Discuz! 官方論壇',
	'init_link_note' => '提供最新 Discuz! 產品新聞、軟件下載與技術交流',

	'license' => '<div class="license"><h1>中文版授權協議 適用於中文用戶</h1>

<p>版權所有 (c) 2001-2009，康盛創想（北京）科技有限公司保留所有權利。</p>

<p>感謝您選擇 Discuz! 論壇產品。希望我們的努力能為您提供一個高效快速和強大的社區論壇解決方案。</p>

<p>Discuz! 英文全稱為 Crossday Discuz! Board，中文全稱為 Discuz! 論壇，以下簡稱 Discuz!。</p>

<p>康盛創想（北京）科技有限公司為 Discuz! 產品的開發商，依法獨立擁有 Discuz! 產品著作權（中國國家版權局著作權登記號 2006SR11895）。康盛創想（北京）科技有限公司網址為 http://www.comsenz.com，Discuz! 官方網站網址為 http://www.discuz.com，Discuz! 官方討論區網址為 http://www.discuz.net。</p>

<p>Discuz! 著作權已在中華人民共和國國家版權局註冊，著作權受到法律和國際公約保護。使用者：無論個人或組織、盈利與否、用途如何（包括以學習和研究為目的），均需仔細閱讀本協議，在理解、同意、並遵守本協議的全部條款後，方可開始使用 Discuz! 軟件。</p>

<p>本授權協議適用且僅適用於 Discuz! 7.x.x 版本，康盛創想（北京）科技有限公司擁有對本授權協議的最終解釋權。</p>

<h3>I. 協議許可的權利</h3>
<ol>
<li>您可以在完全遵守本最終用戶授權協議的基礎上，將本軟件應用於非商業用途，而不必支付軟件版權授權費用。</li>
<li>您可以在協議規定的約束和限制範圍內修改 Discuz! 源代碼(如果被提供的話)或界面風格以適應您的網站要求。</li>
<li>您擁有使用本軟件構建的論壇中全部會員資料、文章及相關信息的所有權，並獨立承擔與文章內容的相關法律義務。</li>
<li>獲得商業授權之後，您可以將本軟件應用於商業用途，同時依據所購買的授權類型中確定的技術支持期限、技術支持方式和技術支持內容，自購買時刻起，在技術支持期限內擁有通過指定的方式獲得指定範圍內的技術支持服務。商業授權用戶享有反映和提出意見的權力，相關意見將被作為首要考慮，但沒有一定被採納的承諾或保證。</li>
</ol>

<h3>II. 協議規定的約束和限制</h3>
<ol>
<li>未獲商業授權之前，不得將本軟件用於商業用途（包括但不限於企業網站、經營性網站、以營利為目或實現盈利的網站）。購買商業授權請登陸http://www.discuz.com參考相關說明，也可以致電8610-51657885瞭解詳情。</li>
<li>不得對本軟件或與之關聯的商業授權進行出租、出售、抵押或發放子許可證。</li>
<li>無論如何，即無論用途如何、是否經過修改或美化、修改程度如何，只要使用 Discuz! 的整體或任何部分，未經書面許可，論壇頁面頁腳處的 Discuz! 名稱和康盛創想（北京）科技有限公司下屬網站（http://www.comsenz.com、http://www.discuz.com 或 http://www.discuz.net） 的鏈接都必須保留，而不能清除或修改。</li>
<li>禁止在 Discuz! 的整體或任何部分基礎上以發展任何派生版本、修改版本或第三方版本用於重新分發。</li>
<li>如果您未能遵守本協議的條款，您的授權將被終止，所被許可的權利將被收回，並承擔相應法律責任。</li>
</ol>

<h3>III. 有限擔保和免責聲明</h3>
<ol>
<li>本軟件及所附帶的文件是作為不提供任何明確的或隱含的賠償或擔保的形式提供的。</li>
<li>用戶出於自願而使用本軟件，您必須瞭解使用本軟件的風險，在尚未購買產品技術服務之前，我們不承諾提供任何形式的技術支持、使用擔保，也不承擔任何因使用本軟件而產生問題的相關責任。</li>
<li>康盛創想（北京）科技有限公司不對使用本軟件構建的論壇中的文章或信息承擔責任。</li>
</ol>

<p>有關 Discuz! 最終用戶授權協議、商業授權與技術服務的詳細內容，均由 Discuz! 官方網站獨家提供。康盛創想（北京）科技有限公司擁有在不事先通知的情況下，修改授權協議和服務價目表的權力，修改後的協議或價目表對自改變之日起的新授權用戶生效。</p>

<p>電子文本形式的授權協議如同雙方書面簽署的協議一樣，具有完全的和等同的法律效力。您一旦開始安裝 Discuz!，即被視為完全理解並接受本協議的各項條款，在享有上述條款授予的權力的同時，受到相關的約束和限制。協議許可範圍以外的行為，將直接違反本授權協議並構成侵權，我們有權隨時終止授權，責令停止損害，並保留追究相關責任的權力。</p></div>',


	'preparation' => '<li>將壓縮包中 Discuz! 目錄下全部文件和目錄上傳到服務器。</li><li>如果您使用非 WINNT 系統請修改以下屬性：<br />&nbsp; &nbsp; <b>./attachments</b> 目錄 777;&nbsp; &nbsp; <b>./forumdata</b> 目錄 777;<br /><b>&nbsp; &nbsp; ./forumdata/cache</b> 目錄 777;&nbsp; &nbsp; <b>./forumdata/templates</b> 目錄 777;&nbsp; &nbsp; <b>./forumdata/threadcaches</b> 目錄 777;<br />&nbsp; &nbsp; <b>./forumdata/logs</b> 目錄 777;&nbsp; &nbsp; <br /></li><li>確認 URL 中 /attachments 可以訪問服務器目錄 ./attachments 內容。</li><li>如果config.inc.php文件不可寫，請自行修改該文件上傳到論壇根目錄下。</li>',


	'install_locked' => '安裝程序已經被鎖定',
	'install_locked_comment' => '您已經成功安裝過論壇，如果想要重新安裝，請刪除 forumdata 目錄下的 install.lock 文件，然後刷新重試',

	'short_open_tag_invalid' => 'PHP 環境問題',
	'short_open_tag_invalid_comment' => '對不起，請將 php.ini 中的 short_open_tag 設置為 On，否則程序無法正常運行',

	'database_nonexistence' => '程序文件丟失',
	'database_nonexistence_comment' => '您的 ./include/db_mysql.class.php 不存在, 無法繼續安裝, 請用 FTP 將該文件上傳後再試。',

	);

$msglang = array(

	'config_nonexistence' => '您的 config.inc.php 不存在, 無法繼續安裝, 請用 FTP 將該文件上傳後再試。',
);

$videoinfo = array(
	'open' => 0,
	'vtype' => "新聞\t軍事\t音樂\t影視\t動漫",
	'bbname' => '',
	'url' => '',
	'email' => '',
	'logo' => '',
	'sitetype' => "新聞\t軍事\t音樂\t影視\t動漫\t遊戲\t美女\t娛樂\t交友\t教育\t藝術\t學術\t技術\t動物\t旅遊\t生活\t時尚\t電腦\t汽車\t手機\t攝影\t戲曲\t外語\t公益\t校園\t數碼\t電腦\t歷史\t天文\t地理\t財經\t地區\t人物\t體育\t健康\t綜合",
	'vsiteid' => '',
	'vpassword' => '',
	'vkey' => '',
	'vclasses' => array (
		22 => '新聞',
		15 => '體育',
		27 => '教育',
		28 => '明星',
		26 => '美色',
		1 => '搞笑',
		29 => '另類',
		18 => '影視',
		12 => '音樂',
		8 => '動漫',
		7 => '遊戲',
		24 => '綜藝',
		11 => '廣告',
		19 => '藝術',
		5 => '時尚',
		21 => '居家',
		23 => '旅遊',
		25 => '動物',
		14 => '汽車',
		30 => '軍事',
		16 => '科技',
		31 => '其他'
	),
	'vclassesable' => array (22, 15, 27, 28, 26, 1, 29, 18, 12, 8, 7, 24, 11, 19, 5, 21, 23, 25, 14, 30, 16, 31)
);



$optionlist = array (
	8 => array (
		'classid' => '1',
		'displayorder' => '2',
		'title' => '性別',
		'identifier' => 'gender',
		'type' => 'radio',
		'rules' => array (
			      'required' => '0',
			      'unchangeable' => '0',
			      'choices' => "1=男\r\n2=女",
			   ),
		),
	16 => array (
		'classid' => '2',
		'displayorder' => '0',
		'title' => '房屋類型',
		'identifier' => 'property',
		'type' => 'select',
		'rules' => array (
			      'choices' => "1=寫字樓\r\n2=公寓\r\n3=小區\r\n4=平房\r\n5=別墅\r\n6=地下室",
			   ),
		),
	17 => array (
		'classid' => '2',
		'displayorder' => '0',
		'title' => '座向',
		'identifier' => 'face',
		'type' => 'radio',
	    	'rules' => array (
	      			'required' => '0',
	      			'unchangeable' => '0',
	      			'choices' => "1=南向\r\n2=北向\r\n3=西向\r\n4=東向",
	    		),
	  	),
      18 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '裝修情況',
        	'identifier' => 'makes',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=無裝修\r\n2=簡單裝修\r\n3=精裝修",
        		),
      	),
      19 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '居室',
        	'identifier' => 'mode',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=獨居\r\n2=兩居室\r\n3=三居室\r\n4=四居室\r\n5=別墅",
        		),
      	),
      23 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '屋內設施',
        	'identifier' => 'equipment',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=水電\r\n2=寬帶\r\n3=管道氣\r\n4=有線電視\r\n5=電梯\r\n6=電話\r\n7=冰箱\r\n8=洗衣機\r\n9=熱水器\r\n10=空調\r\n11=暖氣\r\n12=微波爐\r\n13=油煙機\r\n14=飲水機",
       		),
      	),
      25 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '是否中介',
        	'identifier' => 'bool',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=是\r\n2=否",
        		),
      	),
      27 => array (
        	'classid' => '3',
       	'displayorder' => '0',
        	'title' => '星座',
        	'identifier' => 'Horoscope',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=白羊座\r\n2=金牛座\r\n3=雙子座\r\n4=巨蟹座\r\n5=獅子座\r\n6=處女座\r\n7=天秤座\r\n8=天蠍座\r\n9=射手座\r\n10=摩羯座\r\n11=水瓶座\r\n12=雙魚座",
        		),
      	),
      30 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '婚姻狀況',
        	'identifier' => 'marrige',
        	'type' => 'radio',
        	'rules' => array (
          			'choices' => "1=已婚\r\n2=未婚",
        		),
      	),
      31 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '愛好',
        	'identifier' => 'hobby',
        	'type' => 'checkbox',
        	'rules' => array (
          			'choices' => "1=美食\r\n2=唱歌\r\n3=跳舞\r\n4=電影\r\n5=音樂\r\n6=戲劇\r\n7=聊天\r\n8=拍托\r\n9=電腦\r\n10=網絡\r\n11=遊戲\r\n12=繪畫\r\n13=書法\r\n14=雕塑\r\n15=異性\r\n16=閱讀\r\n17=運動\r\n18=旅遊\r\n19=八卦\r\n20=購物\r\n21=賺錢\r\n22=汽車\r\n23=攝影",
        		),
      	),
      32 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '收入範圍',
        	'identifier' => 'salary',
        	'type' => 'select',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=保密\r\n2=800元以上\r\n3=1500元以上\r\n4=2000元以上\r\n5=3000元以上\r\n6=5000元以上\r\n7=8000元以上",
        		),
      	),
      34 => array (
        	'classid' => '1',
        	'displayorder' => '0',
        	'title' => '學歷',
        	'identifier' => 'education',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=文盲\r\n2=小學\r\n3=初中\r\n4=高中\r\n5=中專\r\n6=大專\r\n7=本科\r\n8=研究生\r\n9=博士",
        		),
      	),
      38 => array (
        	'classid' => '5',
        	'displayorder' => '0',
        	'title' => '席別',
        	'identifier' => 'seats',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=站票\r\n2=硬座\r\n3=軟座\r\n4=硬臥\r\n5=軟臥",
        		),
      	),
      44 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '是否應屆',
        	'identifier' => 'recr_term',
        	'type' => 'radio',
        	'rules' => array (
    		      	'required' => '0',
    		      	'unchangeable' => '0',
    		      	'choices' => "1=應屆\r\n2=非應屆",
        		),
      	),
      48 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '薪金',
        	'identifier' => 'recr_salary',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=面議\r\n2=1000以下\r\n3=1000~1500\r\n4=1500~2000\r\n5=2000~3000\r\n6=3000~4000\r\n7=4000~6000\r\n8=6000~8000\r\n9=8000以上",
        		),
      	),
      50 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '工作性質',
        	'identifier' => 'recr_work',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=全職\r\n2=兼職",
        		),
      	),
      53 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '性別要求',
        	'identifier' => 'recr_sex',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=男\r\n2=女",
        		),
      	),
      62 => array (
        	'classid' => '5',
        	'displayorder' => '0',
        	'title' => '付款方式',
        	'identifier' => 'pay_type',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=電匯\r\n2=支付寶\r\n3=現金\r\n4=其他",
        		),
      	),
);

$request_data = array (
  '邊欄模塊_版塊樹形列表' => 
  array (
    'url' => 'function=module&module=forumtree.inc.php&settings=N%3B&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'forumtree.inc.php',
      'cachelife' => '864000',
      'jscharset' => '0',
    ),
    'comment' => '邊欄版塊樹形列表模塊',
    'type' => '5',
  ),
  '邊欄模塊_版主排行' => 
  array (
    'url' => 'function=module&module=modlist.inc.php&settings=N%3B&jscharset=0&cachelife=3600',
    'parameter' => 
    array (
      'module' => 'modlist.inc.php',
      'cachelife' => '3600',
      'jscharset' => '0',
    ),
    'comment' => '邊欄版主排行模塊',
    'type' => '5',
  ),
  '聚合模塊_版塊列表' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A58%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%C5%C5%D0%D0%2C%B0%E6%BF%E9%C5%C5%D0%D0%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%CA%F7%D0%CE%C1%D0%B1%ED%2C%B0%E6%BF%E9%C1%D0%B1%ED%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'data' => '邊欄模塊_版塊排行,版塊排行
邊欄模塊_版塊樹形列表,版塊列表',
      ),
      'jscharset' => '0',
    ),
    'comment' => '熱門版塊、版塊樹形聚合模塊',
    'type' => '5',
  ),
  '邊欄模塊_版塊排行' => 
  array (
    'url' => 'function=forums&startrow=0&items=0&newwindow=1&orderby=posts&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B0%E6%BF%E9%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%3Cimg%20style%3D%5C%22vertical-align%3Amiddle%5C%22%20src%3D%5C%22images%2Fdefault%2Ftree_file.gif%5C%22%20%2F%3E%20%7Bforumname%7D%28%7Bposts%7D%29%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>版塊排行</h4>
<ul class=\\"textinfolist\\">
[node]<li><img style=\\"vertical-align:middle\\" src=\\"images/default/tree_file.gif\\" /> {forumname}({posts})</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '0',
      'newwindow' => 1,
      'orderby' => 'posts',
      'jscharset' => '0',
    ),
    'comment' => '邊欄版塊排行模塊',
    'type' => '1',
  ),
  '聚合模塊_熱門主題' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A89%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%BD%F1%C8%D5%2C%BD%F1%C8%D5%C8%C8%C3%C5%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%D6%DC%2C%B1%BE%D6%DC%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%D4%C2%2C%B1%BE%D4%C2%22%3B%7D&jscharset=0&cachelife=1800',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '1800',
      'settings' => 
      array (
        'data' => '邊欄模塊_熱門主題_今日,今日熱門
邊欄模塊_熱門主題_本周,本周
邊欄模塊_熱門主題_本月,本月',
      ),
      'jscharset' => '0',
    ),
    'comment' => '今日、本周、本月熱門主題聚合模塊',
    'type' => '5',
  ),
  '邊欄模塊_熱門主題_本月' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=720&jscharset=0&cachelife=86400&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D4%C2%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本月熱門</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '86400',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '720',
      'jscharset' => '0',
    ),
    'comment' => '邊欄本月熱門主題模塊',
    'type' => '0',
  ),
  '聚合模塊_會員排行' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A89%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%BD%F1%C8%D5%2C%BD%F1%C8%D5%C5%C5%D0%D0%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%B1%BE%D6%DC%2C%B1%BE%D6%DC%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%B1%BE%D4%C2%2C%B1%BE%D4%C2%22%3B%7D&jscharset=0&cachelife=3600',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '3600',
      'settings' => 
      array (
        'data' => '邊欄模塊_會員排行_今日,今日排行
邊欄模塊_會員排行_本周,本周
邊欄模塊_會員排行_本月,本月',
      ),
      'jscharset' => '0',
    ),
    'comment' => '今日、本周、本月會員排行聚合模塊',
    'type' => '5',
  ),
  '邊欄模塊_推薦主題' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=1&newwindow=1&threadtype=0&highlight=0&orderby=lastpost&hours=48&jscharset=0&cachelife=3600&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%CD%C6%BC%F6%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>推薦主題</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '3600',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '1',
      'newwindow' => 1,
      'orderby' => 'lastpost',
      'hours' => '48',
      'jscharset' => '0',
    ),
    'comment' => '邊欄推薦主題模塊',
    'type' => '0',
  ),
  '邊欄模塊_最新圖片' => 
  array (
    'url' => 'function=images&sidestatus=0&isimage=1&threadmethod=1&maxwidth=140&maxheight=140&startrow=0&items=5&orderby=dateline&hours=0&digest=0&newwindow=1&jscharset=0&jstemplate=%3Cdiv%20%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%CD%BC%C6%AC%3C%2Fh4%3E%0D%0A%3Cscript%20type%3D%5C%22text%2Fjavascript%5C%22%3E%0D%0Avar%20slideSpeed%20%3D%202500%3B%0D%0Avar%20slideImgsize%20%3D%20%5B140%2C140%5D%3B%0D%0Avar%20slideTextBar%20%3D%200%3B%0D%0Avar%20slideBorderColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0Avar%20slideBgColor%20%3D%20%5C%27%23FFF%5C%27%3B%0D%0Avar%20slideImgs%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgLinks%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgTexts%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideSwitchBar%20%3D%201%3B%0D%0Avar%20slideSwitchColor%20%3D%20%5C%27black%5C%27%3B%0D%0Avar%20slideSwitchbgColor%20%3D%20%5C%27white%5C%27%3B%0D%0Avar%20slideSwitchHiColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0A%5Bnode%5D%0D%0AslideImgs%5B%7Border%7D%5D%20%3D%20%5C%22%7Bimgfile%7D%5C%22%3B%0D%0AslideImgLinks%5B%7Border%7D%5D%20%3D%20%5C%22%7Blink%7D%5C%22%3B%0D%0AslideImgTexts%5B%7Border%7D%5D%20%3D%20%5C%22%7Bsubject%7D%5C%22%3B%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fscript%3E%0D%0A%3Cscript%20language%3D%5C%22javascript%5C%22%20type%3D%5C%22text%2Fjavascript%5C%22%20src%3D%5C%22include%2Fjs%2Fslide.js%5C%22%3E%3C%2Fscript%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div  class=\\"sidebox\\">
<h4>最新圖片</h4>
<script type=\\"text/javascript\\">
var slideSpeed = 2500;
var slideImgsize = [140,140];
var slideTextBar = 0;
var slideBorderColor = \\\'#C8DCEC\\\';
var slideBgColor = \\\'#FFF\\\';
var slideImgs = new Array();
var slideImgLinks = new Array();
var slideImgTexts = new Array();
var slideSwitchBar = 1;
var slideSwitchColor = \\\'black\\\';
var slideSwitchbgColor = \\\'white\\\';
var slideSwitchHiColor = \\\'#C8DCEC\\\';
[node]
slideImgs[{order}] = \\"{imgfile}\\";
slideImgLinks[{order}] = \\"{link}\\";
slideImgTexts[{order}] = \\"{subject}\\";
[/node]
</script>
<script language=\\"javascript\\" type=\\"text/javascript\\" src=\\"include/js/slide.js\\"></script>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'isimage' => '1',
      'maxwidth' => '140',
      'maxheight' => '140',
      'threadmethod' => '1',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄最新圖片展示模塊',
    'type' => '4',
  ),
  '邊欄模塊_最新主題' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=dateline&hours=0&jscharset=0&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>最新主題</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄最新主題模塊',
    'type' => '0',
  ),
  '邊欄模塊_活躍會員' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=12&newwindow=1&extcredit=1&orderby=posts&hours=0&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%BB%EE%D4%BE%BB%E1%D4%B1%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22avt_list%20s_clear%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bavatarsmall%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>活躍會員</h4>
<ul class=\\"avt_list s_clear\\">
[node]<li>{avatarsmall}</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '12',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'posts',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄活躍會員模塊',
    'type' => '2',
  ),
  '邊欄模塊_熱門主題_本版' => 
  array (
    'url' => 'function=threads&sidestatus=1&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=replies&hours=0&jscharset=0&cachelife=1800&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%B0%E6%C8%C8%C3%C5%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本版熱門主題</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '1800',
      'sidestatus' => '1',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'replies',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄本版熱門主題模塊',
    'type' => '0',
  ),
  '邊欄模塊_熱門主題_今日' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=24&jscharset=0&cachelife=1800&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%BD%F1%C8%D5%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>今日熱門</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '1800',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '24',
      'jscharset' => '0',
    ),
    'comment' => '邊欄今日熱門主題模塊',
    'type' => '0',
  ),
  '邊欄模塊_最新回復' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=lastpost&hours=0&jscharset=0&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%BB%D8%B8%B4%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>最新回復</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'lastpost',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄最新回復模塊',
    'type' => '0',
  ),
  '邊欄模塊_最新圖片_本版' => 
  array (
    'url' => 'function=images&sidestatus=1&isimage=1&threadmethod=1&maxwidth=140&maxheight=140&startrow=0&items=5&orderby=dateline&hours=0&digest=0&newwindow=1&jscharset=0&jstemplate=%3Cdiv%20%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%CD%BC%C6%AC%3C%2Fh4%3E%0D%0A%3Cscript%20type%3D%5C%22text%2Fjavascript%5C%22%3E%0D%0Avar%20slideSpeed%20%3D%202500%3B%0D%0Avar%20slideImgsize%20%3D%20%5B140%2C140%5D%3B%0D%0Avar%20slideTextBar%20%3D%200%3B%0D%0Avar%20slideBorderColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0Avar%20slideBgColor%20%3D%20%5C%27%23FFF%5C%27%3B%0D%0Avar%20slideImgs%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgLinks%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgTexts%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideSwitchBar%20%3D%201%3B%0D%0Avar%20slideSwitchColor%20%3D%20%5C%27black%5C%27%3B%0D%0Avar%20slideSwitchbgColor%20%3D%20%5C%27white%5C%27%3B%0D%0Avar%20slideSwitchHiColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0A%5Bnode%5D%0D%0AslideImgs%5B%7Border%7D%5D%20%3D%20%5C%22%7Bimgfile%7D%5C%22%3B%0D%0AslideImgLinks%5B%7Border%7D%5D%20%3D%20%5C%22%7Blink%7D%5C%22%3B%0D%0AslideImgTexts%5B%7Border%7D%5D%20%3D%20%5C%22%7Bsubject%7D%5C%22%3B%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fscript%3E%0D%0A%3Cscript%20language%3D%5C%22javascript%5C%22%20type%3D%5C%22text%2Fjavascript%5C%22%20src%3D%5C%22include%2Fjs%2Fslide.js%5C%22%3E%3C%2Fscript%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div  class=\\"sidebox\\">
<h4>最新圖片</h4>
<script type=\\"text/javascript\\">
var slideSpeed = 2500;
var slideImgsize = [140,140];
var slideTextBar = 0;
var slideBorderColor = \\\'#C8DCEC\\\';
var slideBgColor = \\\'#FFF\\\';
var slideImgs = new Array();
var slideImgLinks = new Array();
var slideImgTexts = new Array();
var slideSwitchBar = 1;
var slideSwitchColor = \\\'black\\\';
var slideSwitchbgColor = \\\'white\\\';
var slideSwitchHiColor = \\\'#C8DCEC\\\';
[node]
slideImgs[{order}] = \\"{imgfile}\\";
slideImgLinks[{order}] = \\"{link}\\";
slideImgTexts[{order}] = \\"{subject}\\";
[/node]
</script>
<script language=\\"javascript\\" type=\\"text/javascript\\" src=\\"include/js/slide.js\\"></script>
</div>',
      'cachelife' => '',
      'sidestatus' => '1',
      'startrow' => '0',
      'items' => '5',
      'isimage' => '1',
      'maxwidth' => '140',
      'maxheight' => '140',
      'threadmethod' => '1',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '邊欄本版最新圖片展示模塊',
    'type' => '4',
  ),
  '邊欄模塊_標籤' => 
  array (
    'url' => 'function=module&module=tag.inc.php&settings=a%3A1%3A%7Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2220%22%3B%7D&jscharset=0&cachelife=900',
    'parameter' => 
    array (
      'module' => 'tag.inc.php',
      'cachelife' => '900',
      'settings' => 
      array (
        'limit' => '20',
      ),
      'jscharset' => '0',
    ),
    'comment' => '邊欄標籤模塊',
    'type' => '5',
  ),
  '邊欄模塊_會員排行_本月' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=720&jscharset=0&cachelife=86400&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D4%C2%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本月排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />發帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '86400',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '720',
      'jscharset' => '0',
    ),
    'comment' => '邊欄會員本月發帖排行模塊',
    'type' => '2',
  ),
  '邊欄模塊_會員排行_本周' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本周排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />發帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '邊欄會員本周發帖排行模塊',
    'type' => '2',
  ),
  '邊欄方案_主題列表頁默認' => 
  array (
    'url' => 'function=side&jscharset=&jstemplate=%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%CE%D2%B5%C4%D6%FA%CA%D6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%B0%E6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%C5%C5%D0%D0%5B%2Fmodule%5D',
    'parameter' => 
    array (
      'selectmodule' => 
      array (
        1 => '邊欄模塊_我的助手',
        2 => '邊欄模塊_熱門主題_本版',
        3 => '邊欄模塊_版塊排行',
      ),
      'cachelife' => 0,
      'jstemplate' => '[module]邊欄模塊_我的助手[/module]<hr class="shadowline"/>[module]邊欄模塊_熱門主題_本版[/module]<hr class="shadowline"/>[module]邊欄模塊_版塊排行[/module]',
    ),
    'comment' => NULL,
    'type' => '-2',
  ),
  '邊欄方案_首頁默認' => 
  array (
    'url' => 'function=side&jscharset=&jstemplate=%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%CE%D2%B5%C4%D6%FA%CA%D6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%BE%DB%BA%CF%C4%A3%BF%E9_%D0%C2%CC%FB%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%BE%DB%BA%CF%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%BB%EE%D4%BE%BB%E1%D4%B1%5B%2Fmodule%5D',
    'parameter' => 
    array (
      'selectmodule' => 
      array (
        1 => '邊欄模塊_我的助手',
        2 => '聚合模塊_新帖',
        3 => '聚合模塊_熱門主題',
        4 => '邊欄模塊_活躍會員',
      ),
      'cachelife' => 0,
      'jstemplate' => '[module]邊欄模塊_我的助手[/module]<hr class="shadowline"/>[module]聚合模塊_新帖[/module]<hr class="shadowline"/>[module]聚合模塊_熱門主題[/module]<hr class="shadowline"/>[module]邊欄模塊_活躍會員[/module]',
    ),
    'comment' => NULL,
    'type' => '-2',
  ),
  '聚合模塊_新帖' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A50%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%D7%EE%D0%C2%D6%F7%CC%E2%2C%D7%EE%D0%C2%D6%F7%CC%E2%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%D7%EE%D0%C2%BB%D8%B8%B4%2C%BB%D8%B8%B4%22%3B%7D&jscharset=0',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '',
      'settings' => 
      array (
        'data' => '邊欄模塊_最新主題,最新主題
邊欄模塊_最新回復,回復',
      ),
      'jscharset' => '0',
    ),
    'comment' => '最新主題、最新回復聚合模塊',
    'type' => '5',
  ),
  '邊欄模塊_熱門主題_本周' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本周熱門</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '邊欄本周熱門主題模塊',
    'type' => '0',
  ),
  '邊欄模塊_會員排行_今日' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=24&jscharset=0&cachelife=3600&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%BD%F1%C8%D5%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>今日排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />發帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '3600',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '24',
      'jscharset' => '0',
    ),
    'comment' => '邊欄會員今日發帖排行模塊',
    'type' => '2',
  ),
  '邊欄模塊_論壇之星' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=3&newwindow=1&extcredit=1&orderby=hourposts&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%D6%AE%D0%C7%3C%2Fh4%3E%0D%0A%5Bnode%5D%0D%0A%5Bshow%3D1%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3B%20margin-right%3A%2016px%3B%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%5B%2Fshow%5D%7Bmember%7D%20%5Bshow%3D1%5D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%3Cdiv%20style%3D%5C%22clear%3Aboth%3Bmargin-top%3A2px%5C%22%20%2F%3E%3C%2Fdiv%3E%5B%2Fshow%5D%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本周之星</h4>
[node]
[show=1]<div style=\\"clear:both\\"><div style=\\"float:left; margin-right: 16px;\\">{avatarsmall}</div>[/show]{member} [show=1]<br />發帖 {value} 篇</div><div style=\\"clear:both;margin-top:2px\\" /></div>[/show]
[/node]
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '3',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '邊欄論壇之星模塊',
    'type' => '2',
  ),
  '邊欄模塊_我的助手' => 
  array (
    'url' => 'function=module&module=assistant.inc.php&settings=N%3B&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'assistant.inc.php',
      'cachelife' => '0',
      'jscharset' => '0',
    ),
    'comment' => '邊欄我的助手模塊',
    'type' => '5',
  ),
  '邊欄模塊_Google搜索' => 
  array (
    'url' => 'function=module&module=google.inc.php&settings=a%3A2%3A%7Bs%3A4%3A%22lang%22%3Bs%3A0%3A%22%22%3Bs%3A7%3A%22default%22%3Bs%3A1%3A%221%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'google.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'lang' => '',
        'default' => '1',
      ),
      'jscharset' => '0',
    ),
    'comment' => '邊欄 Google 搜索模塊',
    'type' => '5',
  ),
  'UCHome_最新動態' => 
  array (
    'url' => 'function=module&module=feed.inc.php&settings=a%3A6%3A%7Bs%3A5%3A%22title%22%3Bs%3A8%3A%22%D7%EE%D0%C2%B6%AF%CC%AC%22%3Bs%3A4%3A%22uids%22%3Bs%3A0%3A%22%22%3Bs%3A6%3A%22friend%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A54%3A%22%3Cdiv%20style%3D%5C%22padding-left%3A2px%5C%22%3E%7Btitle_template%7D%3C%2Fdiv%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'feed.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新動態',
        'uids' => '',
        'friend' => '0',
        'start' => '0',
        'limit' => '10',
        'template' => '<div style=\\"padding-left:2px\\">{title_template}</div>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '獲取UCHome的最新動態',
    'type' => '5',
  ),
  'UCHome_最新更新空間' => 
  array (
    'url' => 'function=module&module=space.inc.php&settings=a%3A17%3A%7Bs%3A5%3A%22title%22%3Bs%3A12%3A%22%D7%EE%D0%C2%B8%FC%D0%C2%BF%D5%BC%E4%22%3Bs%3A3%3A%22uid%22%3Bs%3A0%3A%22%22%3Bs%3A14%3A%22startfriendnum%22%3Bs%3A0%3A%22%22%3Bs%3A12%3A%22endfriendnum%22%3Bs%3A0%3A%22%22%3Bs%3A12%3A%22startviewnum%22%3Bs%3A0%3A%22%22%3Bs%3A10%3A%22endviewnum%22%3Bs%3A0%3A%22%22%3Bs%3A11%3A%22startcredit%22%3Bs%3A0%3A%22%22%3Bs%3A9%3A%22endcredit%22%3Bs%3A0%3A%22%22%3Bs%3A6%3A%22avatar%22%3Bs%3A2%3A%22-1%22%3Bs%3A10%3A%22namestatus%22%3Bs%3A2%3A%22-1%22%3Bs%3A8%3A%22dateline%22%3Bs%3A1%3A%220%22%3Bs%3A10%3A%22updatetime%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22order%22%3Bs%3A10%3A%22updatetime%22%3Bs%3A2%3A%22sc%22%3Bs%3A4%3A%22DESC%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A267%3A%22%3Ctable%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%20width%3D%5C%2250%5C%22%20rowspan%3D%5C%222%5C%22%3E%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20target%3D%5C%22_blank%5C%22%3E%3Cimg%20src%3D%5C%22%7Bphoto%7D%5C%22%20%2F%3E%3C%2Fa%3E%3C%2Ftd%3E%0D%0A%3Ctd%3E%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20%20target%3D%5C%22_blank%5C%22%20style%3D%5C%22text-decoration%3Anone%3B%5C%22%3E%7Busername%7D%3C%2Fa%3E%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%3Ctd%3E%7Bupdatetime%7D%3C%2Ftd%3E%3C%2Ftr%3E%0D%0A%3C%2Ftable%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'space.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新更新空間',
        'uid' => '',
        'startfriendnum' => '',
        'endfriendnum' => '',
        'startviewnum' => '',
        'endviewnum' => '',
        'startcredit' => '',
        'endcredit' => '',
        'avatar' => '-1',
        'namestatus' => '-1',
        'dateline' => '0',
        'updatetime' => '0',
        'order' => 'updatetime',
        'sc' => 'DESC',
        'start' => '0',
        'limit' => '10',
        'template' => '<table>
<tr>
<td width=\\"50\\" rowspan=\\"2\\"><a href=\\"{userlink}\\" target=\\"_blank\\"><img src=\\"{photo}\\" /></a></td>
<td><a href=\\"{userlink}\\"  target=\\"_blank\\" style=\\"text-decoration:none;\\">{username}</a></td>
</tr>
<tr><td>{updatetime}</td></tr>
</table>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '獲取UCHome最新更新會員空間',
    'type' => '5',
  ),
  'UCHome_最新記錄' => 
  array (
    'url' => 'function=module&module=doing.inc.php&settings=a%3A6%3A%7Bs%3A5%3A%22title%22%3Bs%3A8%3A%22%D7%EE%D0%C2%BC%C7%C2%BC%22%3Bs%3A3%3A%22uid%22%3Bs%3A0%3A%22%22%3Bs%3A4%3A%22mood%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A360%3A%22%0D%0A%3Cdiv%20style%3D%5C%22padding%3A0%200%205px%200%3B%5C%22%3E%0D%0A%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20target%3D%5C%22_blank%5C%22%3E%3Cimg%20src%3D%5C%22%7Bphoto%7D%5C%22%20width%3D%5C%2218%5C%22%20height%3D%5C%2218%5C%22%20align%3D%5C%22absmiddle%5C%22%3E%3C%2Fa%3E%20%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20%20target%3D%5C%22_blank%5C%22%3E%7Busername%7D%3C%2Fa%3E%A3%BA%0D%0A%3C%2Fdiv%3E%0D%0A%3Cdiv%20style%3D%5C%22padding%3A0%200%205px%2020px%3B%5C%22%3E%0D%0A%3Ca%20href%3D%5C%22%7Blink%7D%5C%22%20style%3D%5C%22color%3A%23333%3Btext-decoration%3Anone%3B%5C%22%20target%3D%5C%22_blank%5C%22%3E%7Bmessage%7D%3C%2Fa%3E%0D%0A%3C%2Fdiv%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'doing.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新記錄',
        'uid' => '',
        'mood' => '0',
        'start' => '0',
        'limit' => '10',
        'template' => '
<div style=\\"padding:0 0 5px 0;\\">
<a href=\\"{userlink}\\" target=\\"_blank\\"><img src=\\"{photo}\\" width=\\"18\\" height=\\"18\\" align=\\"absmiddle\\"></a> <a href=\\"{userlink}\\"  target=\\"_blank\\">{username}</a>：
</div>
<div style=\\"padding:0 0 5px 20px;\\">
<a href=\\"{link}\\" style=\\"color:#333;text-decoration:none;\\" target=\\"_blank\\">{message}</a>
</div>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '獲取UCHome的最新記錄',
    'type' => '5',
  ),
  'UCHome_競價排名' => 
  array (
    'url' => 'function=module&module=html.inc.php&settings=a%3A3%3A%7Bs%3A4%3A%22type%22%3Bs%3A1%3A%220%22%3Bs%3A4%3A%22code%22%3Bs%3A27%3A%22%3Cdiv%20id%3D%5C%22sidefeed%5C%22%3E%3C%2Fdiv%3E%22%3Bs%3A4%3A%22side%22%3Bs%3A1%3A%220%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'html.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'type' => '0',
        'code' => '<div id=\\"sidefeed\\"></div>',
        'side' => '0',
      ),
      'jscharset' => '0',
    ),
    'comment' => '獲取UCHome的競價排名信息',
    'type' => '5',
  ),
);

$tasktypes = array(
  'promotion' => 
  array (
    'name' => '論壇推廣任務',
    'version' => '1.0',
  ),
  'gift' => 
  array (
    'name' => '紅包類任務',
    'version' => '1.0',
  ),
  'avatar' => 
  array (
    'name' => '頭像類任務',
    'version' => '1.0',
  )
);

?>
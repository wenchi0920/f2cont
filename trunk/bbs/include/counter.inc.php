<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: counter.inc.php 17367 2008-12-16 06:13:26Z monkey $
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

$visitor = array();

$visitor['agent'] = $_SERVER['HTTP_USER_AGENT'];
list($visitor['month'], $visitor['week'], $visitor['hour']) = explode("\t", gmdate("Ym\tw\tH", $timestamp + $_DCACHE['settings']['timeoffset'] * 3600));

if (!$sessionexists || $discuz_uid) {
	if (ereg("MAXTHON ([a-z0-9.]+)", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
                $visitor_browser = "Maxthon " . $browser_ver;
                $visitor['browser'] = 'Maxthon';
	} elseif (ereg("MSIE ([a-z0-9.]+)", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
                $visitor_browser = "Internet Explorer " . $browser_ver;
                $visitor['browser'] = 'MSIE';
        } elseif (ereg("Firefox/([a-z0-9.]+)", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
                $visitor_browser = "Mozilla Firefox " . $browser_ver;
                $visitor['browser'] = 'Mozilla';
	} elseif (ereg("Chrome/([a-z0-9.]+) Safari/", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
		$visitor_browser = "Google Chrome " . $browser_ver;
                $visitor['browser'] = 'Chrome';
	} elseif (ereg("Version/([a-z0-9.]+)([a-zA-Z0-9/ ]*) Safari/", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
		$visitor_browser = "Apple Safari " . $browser_ver;
                $visitor['browser'] = 'Safari';
        } elseif (strpos($visitor['agent'], "PSP")) {
                $visitor_browser = "PSP Browser";
                $visitor['browser'] = 'PSP Browser';
        } elseif (ereg("Opera/([a-z0-9.]+)", $visitor['agent'], $browser)) {
		$browser_ver = $browser[1];
		$visitor_browser = "Opera " . $browser_ver;
                $visitor['browser'] = 'Opera';
        } elseif (strpos($visitor['agent'], "Netscape")) {
                $visitor_browser = "Netscape";
                $visitor['browser'] = 'Netscape';
        } elseif (strpos($visitor['agent'], "Lynx")) {
                $visitor_browser = "Lynx";
                $visitor['browser'] = 'Lynx';
        } elseif (strpos($visitor['agent'], "Konqueror")) {
                $visitor_browser = "Konqueror";
                $visitor['browser'] = 'Konqueror';
        } elseif (strpos($visitor['agent'], "Mozilla/5.0")) {
                $visitor_browser = "Mozilla";
                $visitor['browser'] = 'Mozilla';
        } else {
                $visitor_browser = "其它";
                $visitor['browser'] = 'Other';
        }
	if (strpos($visitor['agent'], "NT 6.1")) {
		$visitor_os = "Windows 7";
		$visitor['os'] = 'Windows';
	} elseif (strpos($visitor['agent'], "NT 6.0")) {
		$visitor_os = "Windows Vista";
		$visitor['os'] = 'Windows';
	} elseif (strpos($visitor['agent'], "NT 5.1")) {
		$visitor_os = "Windows XP";
		$visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "PSP")) {
		if (ereg("system=(.*)\)", $_SERVER["HTTP_X_PSP_BROWSER"], $os)) {
	                $visitor_os = "PSP $os[1]";
        	        $visitor['os'] = "PSP $os[1]";
		}
        } elseif (strpos($visitor['agent'], "NT 5.2")) {
                $visitor_os = "Windows Server 2003";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "NT 5")) {
                $visitor_os = "Windows 2000";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "4.9")) {
                $visitor_os = "Windows ME";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "NT 4")) {
                $visitor_os = "Windows NT 4.0";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "98")) {
                $visitor_os = "Windows 98";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "95")) {
                $visitor_os = "Windows 95";
                $visitor['os'] = 'Windows';
        } elseif (strpos($visitor['agent'], "Mac")) {
                $visitor_os = "Macintosh";
                $visitor['os'] = 'Macintosh';
        } elseif (strpos($visitor['agent'], "Linux")) {
                $visitor_os = "Linux";
                $visitor['os'] = 'Linux';
        } elseif (strpos($visitor['agent'], "Unix")) {
                $visitor_os = "Unix";
                $visitor['os'] = 'Other';
        } elseif (strpos($visitor['agent'], "FreeBSD")) {
                $visitor_os = "FreeBSD";
                $visitor['os'] = 'FreeBSD';
        } elseif (strpos($visitor['agent'], "SunOS")) {
                $visitor_os = "SunOS";
                $visitor['os'] = 'SunOS';
        } elseif (strpos($visitor['agent'], "OS/2")) {
                $visitor_os = "OS/2";
                $visitor['os'] = 'OS/2';
        } elseif (strpos($visitor['agent'], "AIX")) {
                $visitor_os = "AIX";
                $visitor['os'] = 'AIX';
        } elseif (preg_match("/(Bot|Crawl|Spider)/i", $visitor['agent'])) {
                $visitor_os = "Spiders";
                $visitor['os'] = 'Spiders';
	} else {
                $visitor_os = "其它";
                $visitor['os'] = 'Other';
        }
	$visitorsadd = "OR (type='browser' AND variable='$visitor[browser]') OR (type='os' AND variable='$visitor[os]')".
		($discuz_user ? " OR (type='total' AND variable='members')" : " OR (type='total' AND variable='guests')");
	$updatedrows = 7;
} else {
	$visitorsadd = '';
	$updatedrows = 4;
}

$db->query("UPDATE {$tablepre}stats SET count=count+1 WHERE (type='total' AND variable='hits') $visitorsadd OR (type='month' AND variable='$visitor[month]') OR (type='week' AND variable='$visitor[week]') OR (type='hour' AND variable='$visitor[hour]')");

if($updatedrows > $db->affected_rows()) {
	$db->query("INSERT INTO {$tablepre}stats (type, variable, count)
			VALUES ('month', '$visitor[month]', '1')", 'SILENT');
}

?>
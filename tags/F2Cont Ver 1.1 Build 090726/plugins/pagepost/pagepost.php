<?php
/*
Plugin Name: pagepost
Plugin URI: http://joesen.f2blog.com
Description: AJAX分页显示 for F2Blog V1.1 or later.
Author: Joesen & andot
Version: 1.1
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function pagepost_install() {
	$arrPlugin['Name']="pagepost";  //Plugin name
	$arrPlugin['Desc']="Ajax分页";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function pagepost_unstall() {
	$ActionMessage=unstall_plugins("pagepost");
	return $ActionMessage;
}

function pagepost($content,$post_id) {
	if (strpos(";$content","<!--nextpage-->")<1) return $content;

    $pages = preg_split("/(<p>)?\s*<!--\s*nextpage\s*-->\s*(<\/p>)?/is", $content, -1, PREG_SPLIT_NO_EMPTY);
    $totalpage = count($pages);
    if ($totalpage == 1) {
        return $content;
    }
    $curpage = (isset($_GET['pp'])
        && is_numeric($_GET['pp'])
        && ($_GET['pp'] >= 0)
        && ($_GET['pp'] <= $totalpage)) ? $_GET['pp'] : 1;
    //$post_id = 352;
    $id = rand();
    if ($curpage > 0) {
        $content = $pages[$curpage - 1];
        $pagebar = "<a href=\"###pp=0\" onclick=\"pagepost($post_id, 0, $id);\">&#23436;&#25972;&#26174;&#31034;</a> &nbsp; ";
    } else {
        $pagebar = '<strong style="color: green">&#23436;&#25972;&#26174;&#31034;</strong> &nbsp; ';
    }
    for ($i = 1; $i <= $totalpage; $i++) {
    	if ($i != $curpage) {
            $pagebar .= "<a href=\"###pp=$i\" onclick=\"pagepost($post_id, $i, $id);\">$i</a> ";
        }
        else {
            $pagebar .= '<strong style="color: green">' . $i . '</strong> ';
        }
    }
    $pagebar_top = "<div id=\"pagebar_top_$id\" class=\"pagebar\">" . $pagebar . "</div>";
    $pagebar_bottom = "<div id=\"pagebar_bottom_$id\" class=\"pagebar\">" . $pagebar . "</div>";
    return "<div id=\"pagepost_$id\" " .
        "class=\"pagepost\">" .
        $pagebar_top . $content . $pagebar_bottom . '</div>';
}

function pagepost_js() {
    if ((!defined('PHPRPC_JS_CLIENT_LOADED')) || (PHPRPC_JS_CLIENT_LOADED == false)) {
        echo "<script type=\"text/javascript\" src=\"plugins/pagepost/phprpc_client.js\"></script>\n";
        define('PHPRPC_JS_CLIENT_LOADED', true);
    }
    if ((!defined('INNERHTML_JS_LOADED')) || (INNERHTML_JS_LOADED == false)) {
    echo "<script type=\"text/javascript\" src=\"plugins/pagepost/innerhtml.js\"></script>\n";
        define('INNERHTML_JS_LOADED', true);
    }
    echo "<script type=\"text/javascript\" src=\"plugins/pagepost/pagepost.js.php\"></script>\n";
}

function pagepost_css() {
    echo "<link rel=\"stylesheet\" href=\"plugins/pagepost/pagepost.css\" />\n";
}

add_filter('f2_content','pagepost',2);
add_action('f2_head', 'pagepost_js');
add_action('f2_head', 'pagepost_css');

?>
<?php
require_once('phprpc_server.php');
$plugins_path="../../";
require_once('../../include/function.php');

function get_pagepost($post_id, $curpage, $id) {
	global $DBPrefix,$strContentAll;
    //$post = getRecordValue($post_id);
	$post=getRecordValue($DBPrefix."logs"," id='$post_id'");
    $content = $post['logContent'];
	$pages = preg_split("/(<p>)?\s*<!--\s*nextpage\s*-->\s*(<\/p>)?/is", $content, -1, PREG_SPLIT_NO_EMPTY);
    $totalpage = count($pages);
    if ($curpage > 0) {
        $content = formatBlogContent($pages[$curpage - 1],0);
    }
    else {
        for ($i = 0; $i < count($pages); $i++) {
			$pages[$i] = formatBlogContent($pages[$i],0);
        }
        $content = join('', $pages);
    }
    if ($curpage > 0) {
        $pagebar = "<a href=\"###pp=0\" onclick=\"pagepost($post_id, 0, $id);\">&#23436;&#25972;&#26174;&#31034;</a> &nbsp; ";
    }
    else {
        $pagebar = '<strong style="color: green">&#23436;&#25972;&#26174;&#31034;</strong> &nbsp; ';
    }
    for ($i = 1; $i <= $totalpage; $i++) {
    	if ($i != $curpage) {
            $pagebar .= "<a href=\"###pp=$i\" onclick=\"pagepost($post_id, $i, $id);\">$i</a> ";
        }
        else {
            $pagebar .= "<strong style=\"color: green\">$i</strong> ";
        }
    }
    $pagebar_top = "<div id=\"pagebar_top_$id\" class=\"pagebar\">" . $pagebar . "</div>";
    $pagebar_bottom = "<div id=\"pagebar_bottom_$id\" class=\"pagebar\">" . $pagebar . "</div>";
	//$content=substr($content,0,strpos($content,"<!--more-->"));
	//$content.="<p><a class=\"more\" href=\"index.php?load=read&id=".$post_id."\">[$strContentAll]</a></p> \n";
    echo $pagebar_top . $content . $pagebar_bottom;
}

//get_pagepost(352, 2, 20256);

new phprpc_server(array('get_pagepost'), true);
?>
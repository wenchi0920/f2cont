<?php
/*
Plugin Name: CoolCode
Plugin URI: http://joesen.f2blog.com/index.php?load=read&id=150
Description: 高亮格式化显示代码 for F2Blog V1.1 or later.
Version: 1.1
Author: andot & Joesen
Author URI: http://joesen.f2blog.com
*/

// Install Plugin
function CoolCode_install() {
	$arrPlugin['Name']="CoolCode";  //Plugin name
	$arrPlugin['Desc']="高亮显示代码";  //Plugin title
	$arrPlugin['Type']="Func";      //Plugin type
	$arrPlugin['Code']="";          //Plugin htmlcode
	$arrPlugin['Path']="";          //Plugin Path

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function CoolCode_unstall() {
	$ActionMessage=unstall_plugins("CoolCode");
	return $ActionMessage;
}

//================================================================================
// Download the code
//================================================================================
if (!empty($_GET['download'])) {
    $post = $_GET['p'];
    $download = $_GET['download'];

	$plugins_path=substr(dirname(__FILE__), 0, -16);
	include_once($plugins_path."include/function.php");
    $dataInfo = getLogs($post);
    $content = $dataInfo['logContent'];

	$content=str_replace("&amp;","&",$content);
	$content=str_replace("&lt;","<",$content);
	$content=str_replace("&gt;",">",$content);
	$content=str_replace("<br />","\n",$content);
	$content=str_replace("&#39;","'",$content);
	$content=str_replace("&nbsp;"," ",$content);
	$content=str_replace("&quot;","\"",$content);
	$content=str_replace("<p><coolcode","<coolcode",$content);
	$content=str_replace("</coolcode></p>","</coolcode>",$content);
	$content=str_replace("</p> <p>","\r\n\r\n",$content);
    $search = strtolower($content);

    $pos = 0;
    while (true) {
        $count = 0;
		$pos1 = strpos($search, "<coolcode", $pos);
        $pos2 = strpos($search, "[coolcode", $pos);
        if ($pos1 === false) {
            if ($pos2 === false) {
                exit();
            }
            else {
                $pos = $pos2;
                $bracket = array('[', ']');
            }
        }
        else {
            if ($pos2 === false) {
                $pos = $pos1;
                $bracket = array('<', '>');
            }
            else if ($pos1 < $pos2) {
                $pos = $pos1;
                $bracket = array('<', '>');
            }
            else {
                $pos = $pos2;
                $bracket = array('[', ']');
            }
        }
        $start = $pos++;
        $count = 1;
        while ($count > 0) {
            $pos1 = strpos($search, $bracket[0] . "coolcode", $pos);
            $pos2 = strpos($search, $bracket[0] . "/coolcode" . $bracket[1], $pos);
            if ($pos1 === false) {
                if ($pos2 === false) {
                    exit();
                }
                else {
                    $pos = $pos2;
                    $count--;
                }
            }
            else {
                if ($pos2 === false) {
                    $pos = $pos1;
                    $count++;
                }
                else if ($pos1 < $pos2) {
                    $pos = $pos1;
                    $count++;
                }
                else {
                    $pos = $pos2;
                    $count--;
                }
            }
            $pos++;
        }
        $end = $pos + 10;
        $code = substr($content, $start, $end - $start);
        if (preg_match('#^\<coolcode(.*?)download="' . $download . '"(.*?)\>(.*)\</coolcode\>$#si', $code, $match) ||
            preg_match('#^\[coolcode(.*?)download="' . $download . '"(.*?)\](.*)\[/coolcode\]$#si', $code, $match)) {
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$download\"");
            echo trim($match[3]);
            exit();
        }
    }
}

//================================================================================
// Get the PEAR path, and include the CoolCode
//================================================================================
$pear_dir = F2BLOG_ROOT . 'plugins/CoolCode/PEAR';

if(is_dir($pear_dir))
    ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . $pear_dir);
require_once 'Text/Highlighter.php';

//================================================================================
// Create our custom highlighter, then add the filters
// We have sets - before and after, which are performed before and
// after all of the other filters. This is to bypass any filters
// that do crazy text replacements. It's easier this way, instead of
// trying to undo what the other filters did.
//================================================================================
$CoolCode = new CoolCode();

// Showing
add_filter('f2_content', array(&$CoolCode, 'part_one'), -1000);
add_filter('f2_content', array(&$CoolCode, 'part_two'),  1000);

add_action('f2_head', array(&$CoolCode, 'add_css'));
add_action('f2_head', array(&$CoolCode, 'add_js'));

unset($CoolCode);

class CoolCode
{
    // The languages the Text_Highlighter can accept
    var $acceptable_lang = array('php', 'cpp', 'css', 'diff', 'dtd', 'javascript', 'html',
                                 'mysql', 'perl', 'python', 'ruby', 'sql', 'xml', 'java', 'actionscript');

    var $hl_class = array('class="hl-default"', 'class="hl-code"', 'class="hl-brackets"',
                          'class="hl-comment"', 'class="hl-quotes"', 'class="hl-string"',
                          'class="hl-identifier"', 'class="hl-builtin"', 'class="hl-reserved"',
                          'class="hl-inlinedoc"', 'class="hl-var"', 'class="hl-url"',
                          'class="hl-special"', 'class="hl-number"', 'class="hl-inlinetags"');

    var $hl_style = array('style="color: Black;"', 'style="color: Gray;"', 'style="color: Olive;"',
                          'style="color: #ffa500;"', 'style="color: #8b0000;"', 'style="color: Red;"',
                          'style="color: Blue;"', 'style="color: Teal;"' ,'style="color: Green;"',
                          'style="color: Blue;"', 'style="color: #00008b;"', 'style="color: Blue;"',
                          'style="color: Navy;"', 'style="color: Maroon;"', 'style="color: Blue;"');

    // The blocks array that holds the block ID's and their real code blocks
    var $blocks = array();

    /****************************************************************************
     * add_css
     *    > Add the coolcode.css to the head
     ****************************************************************************/
    function add_css() {
        echo "<link rel=\"stylesheet\" href=\"plugins/CoolCode/coolcode.css\" />\n";
    }
    
    /****************************************************************************
     * add_js
     *    > Add the coolcode.js to the footer
     ****************************************************************************/
    function add_js() {
        echo "<script type=\"text/javascript\" src=\"plugins/CoolCode/coolcode.js\"></script>\n";
    }

    /****************************************************************************
     * part_one
     *    > Replace the code blocks with the block IDs
     ****************************************************************************/
    function part_one($search,$post_id)
    {
		$search=preg_replace('/&lt;coolcode (.+?)&lt;\/coolcode&gt;/ie', '$this->makeConvert($post_id,\'\\1\')', $search);

        return $search;
    }

    function makeConvert($post_id,$code)
    {        
		$code="&lt;coolcode ".$code."&lt;/coolcode&gt;";

		$code=str_replace("&amp;","&",$code);
		$code=str_replace("&lt;","<",$code);
		$code=str_replace("&gt;",">",$code);
		$code=str_replace("<br />","\n",$code);
		$code=str_replace("&#39;","'",$code);
		$code=str_replace("&nbsp;"," ",$code);
		$code=str_replace("&quot;","\"",$code);
		$code=str_replace("<p><coolcode","<coolcode",$code);
		$code=str_replace("</coolcode></p>","</coolcode>",$code);
		$code=str_replace("</p> <p>","\r\n\r\n",$code);

        $code = preg_replace('#^\<coolcode(.*?)\>(.*)\</coolcode\>$#sie', '$this->do_CoolCode($code,$post_id, \'\\2\', \'\\1\');', $code);
        $code = preg_replace('#^\[coolcode(.*?)\](.*)\[/coolcode\]$#sie', '$this->do_CoolCode($code,$post_id, \'\\2\', \'\\1\');', $code);

        return $code;
    }

    /****************************************************************************
     * part_two
     *    > Replace the block ID's from part one with the actual code blocks
     ****************************************************************************/
    function part_two($content,$post_id)
    {
        if (count($this->blocks)) {
            $content = str_replace(array_keys($this->blocks), array_values($this->blocks), $content);
            $this->blocks = array();
        }

        return $content;
    }

    /****************************************************************************
     * do_CoolCode
     *    > Perform the code highlighting that is to be replaced with a block ID
     ****************************************************************************/
    function do_CoolCode($content, $post_id,$txt, $options)
    {
        $options = str_replace(array("\\\"", "\\\'"), array("\"", "\'"), $options);
        if (preg_match('/lang="(\w*?)"/i', $options, $match)) {
            $lang = $match[1];
        }
        else {
            $lang = "";
        }
        if (preg_match('/linenum="(\w*?)"/i', $options, $match)) {
            $linenum = $match[1];
        }
        else {
            $linenum = "on";
        }
        if (preg_match('/download="(.*?)"/i', $options, $match)) {
            $download = $match[1];
        }
        else {
            $download = "";
        }
        $txt = str_replace("\\\"", "\"", $txt);
        $txt = trim($txt);
        $txt = str_replace("\r\n", "\n", $txt);
        $txt = str_replace("\r", "\n", $txt);
        $blockID = $this->getBlockID($content);
        if ($download == "") {
            $this->blocks[$blockID] = '';
        }
        else {
            $this->blocks[$blockID] = '<div class="hl-title">&#19979;&#36733;: <a href="'
                . 'plugins/CoolCode/CoolCode.php?p=' . $post_id
                . '&amp;download=' . htmlspecialchars($download)
                . '">' . htmlspecialchars($download) . '</a></div>';
        }

        $hackphp = false;

        if (strtolower($lang) == 'php') {
            if (strpos($txt, '<' . '?') === false) {
                $txt = '<' . "?php\n" . $txt . "\n?" . '>';
                $hackphp = true;
            }
        }

        if ((strtolower($linenum) == 'on') or (strtolower($linenum) == 'open')) {
            if(!in_array(strtolower($lang), $this->acceptable_lang)) {
                $this->blocks[$blockID] .= '<div class="hl-surround"><ol class="hl-main ln-show" '
                    . 'title="Double click to hide line number." '
                    . 'ondblclick = "linenumber(this)"><li class="hl-firstline">'
                    . str_replace("\n", "</li>\n<li>", htmlspecialchars($txt))
                    . '</li></ol></div>';
                $this->blocks[$blockID] = str_replace("<li></li>", "<li>&nbsp;</li>", $this->blocks[$blockID]);
                $this->blocks[$blockID] = str_replace('<li> ', '<li>&nbsp;', $this->blocks[$blockID]);
            }
            else
            {
                $options = array(
                    'numbers' => HL_NUMBERS_LI,
                );
                $hl =& Text_Highlighter::factory($lang, $options);
                $this->blocks[$blockID] .= '<div class="hl-surround">' . str_replace($this->hl_class, $this->hl_style, $hl->highlight($txt)) . '</div>';
                $this->blocks[$blockID] = preg_replace('/<span style=\"[^\"]*?\"><\/span>/', '', $this->blocks[$blockID]);
                $this->blocks[$blockID] = str_replace('<ol class="hl-main">',
                    '<ol class="hl-main ln-show" title="Double click to hide line number." ondblclick = "linenumber(this)">',
                    $this->blocks[$blockID]);
                $this->blocks[$blockID] = str_replace("\"> </span></li>", "\">&nbsp;</span></li>", $this->blocks[$blockID]);
                $this->blocks[$blockID] = preg_replace('/<li><span style=(.*?)> </si', '<li><span style=\\1>&nbsp;<', $this->blocks[$blockID]);

                if ($hackphp) {
                    $this->blocks[$blockID] = str_replace("<span style=\"color: Blue;\">&lt;?php</span></li>\n<li>", '', $this->blocks[$blockID]);
                    $this->blocks[$blockID] = str_replace('<li><span style="color: Blue;">?&gt;</span></li>', '', $this->blocks[$blockID]);
                }
            }
        }
        else {
            if(!in_array(strtolower($lang), $this->acceptable_lang)) {
                $this->blocks[$blockID] .= '<div class="hl-surround"><div class="hl-main">'
                    . str_replace("\n", "<br />", htmlspecialchars($txt))
                    . '</div></div>';
            }
            else
            {
                $hl =& Text_Highlighter::factory($lang);
                $this->blocks[$blockID] .= '<div class="hl-surround">' . str_replace("\n", "<br />", str_replace("</pre>", "", str_replace("<pre>", "", str_replace($this->hl_class, $this->hl_style, $hl->highlight($txt))))) . '</div>';
                
                if ($hackphp) {
                    $this->blocks[$blockID] = str_replace('<span style="color: Blue;">&lt;?php</span><span style="color: Gray;"><br /></span>', '', $this->blocks[$blockID]);
                    $this->blocks[$blockID] = str_replace('<br /></span><span style="color: Blue;">?&gt;</span>', '</span>', $this->blocks[$blockID]);
                }
            }
            $this->blocks[$blockID] = str_replace('<br /> ', '<br />&nbsp;', $this->blocks[$blockID]);
        }

        // correct the indent
        $this->blocks[$blockID] = str_replace("  ", '&nbsp; ', $this->blocks[$blockID]);
        $this->blocks[$blockID] = str_replace("  ", ' &nbsp;', $this->blocks[$blockID]);

        return $blockID;
    }

    /****************************************************************************
     * getBlockID
     *    > Generate a block ID that will be replaced at the end (after all that
     *      crazy WP text work!) with the right code
     ****************************************************************************/
    function getBlockID($content)
    {
        static $num = 0;

        do
        {
            ++$num;
            $blockID = "<p>++CoolCode_BLOCK_$num++</p>";
        }
        while(strpos($content, $blockID) !== false);

        return $blockID;
    }
}
?>
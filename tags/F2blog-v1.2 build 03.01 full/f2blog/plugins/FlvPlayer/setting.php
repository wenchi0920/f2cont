<?php
function editor_flvplayer($editorcode){
	echo <<<HTML
	<a href="../plugins/FlvPlayer/FlvEditor.php?TB_iframe=true&height=110&width=500" title="插入FLV" class="thickbox"><img src="../plugins/FlvPlayer/flv.gif" title="插入FLV" alt="插入FLV" border="0" /></a>
HTML;
}

add_filter('f2_editor','editor_flvplayer');
?>
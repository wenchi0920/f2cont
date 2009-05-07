<?php
function editor_coolcode($editorcode){
	echo <<<HTML
	<a href="../plugins/CoolCode/selCoolcode.php?TB_iframe=true&height=110&width=500" title="插入Cool Code" class="thickbox"><img src="../images/code.gif" title="插入Cool Code" alt="插入Cool Code" border="0" /></a>
HTML;
}

add_filter('f2_editor','editor_coolcode');
?>
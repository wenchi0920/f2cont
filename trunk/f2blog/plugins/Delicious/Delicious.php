<?php
/*
Plugin Name: Delicious
Plugin URI: http://forum.f2blog.com/forum-21-1.html
Description: Add Del.ico.us bookmark to your blog.
Version: 0.1
Author: zach14c
Author URI: http://forum.f2blog.com/profile-uid-22.html
*/


// Install Plugin
function Delicious_install() {
	global $Delicious_plugin_config;
	$arrPlugin['Name'] = "Delicious";
	$arrPlugin['Desc'] = "Delicious"; 
	$arrPlugin['Type'] = "Side";     
	$arrPlugin['Code'] = "";         
	$arrPlugin['Path'] = "";         
	$arrPlugin['DefaultField'] = array("user"); 
	$arrPlugin['DefaultValue'] = array("zach14c"); 

	$ActionMessage = install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function Delicious_unstall() {
	global $Delicious_plugin_config;
	$ActionMessage = unstall_plugins("Delicious");
	return $ActionMessage;
}
    
function Delicious($sidename, $sidetitle, $htmlcode, $isInstall) {
	//$arr = getModSet($sidename);
	global $plugins_Delicious;
	$user_name = $plugins_Delicious["user"];
	if(!$user_name) $user_name = 'zach14c';
?>	
<!-- Del.icio.us plugin -->
<div class="sidepanel" id="Side_Delicious">
  <h4 class="Ptitle" style="cursor: pointer;">Del.icio.us - <?php echo $user_name?></h4>
  <div class="Pcontent" id="content_Delicious" style="white-space:nowrap; height:150px; overflow:auto; ">
  </div> 
</div> 
<script type="text/javascript">
var dispPanel;
function loadBookmarks(){
	var url = "plugins/Delicious/proxy.php?user_name=" + "<?php echo $user_name?>";
	var request = YAHOO.util.Connect.asyncRequest('GET', url, { success:successHandler, failure:failureHandler });
	dispPanel.innerHTML = "<img src=\"plugins/Delicious/loading.gif\">";
}

function sortTitles(aa, ab){
	var a = aa.split("|");
	var b = ab.split("|");
	if(a[0].charAt(0).toUpperCase() > b[0].charAt(0).toUpperCase()) return 1;
	if(a[0].charAt(0).toUpperCase() < b[0].charAt(0).toUpperCase()) return -1;	
	return 0;
}

function successHandler(resp){
	var root = resp.responseXML.documentElement;	
	var titles = root.getElementsByTagName('title');
	var urlLinks = root.getElementsByTagName('link');
	var title_urls = new Array();
	
	for(var i = 0 ; i < titles.length ; i++){
		title_urls[i] = titles[i].firstChild.nodeValue + "|" + urlLinks[i].firstChild.nodeValue;
	}
	title_urls.sort(sortTitles);
	var j = 0;
	
	var contentBuffer = "";
	for(var i = 0 ; i < title_urls.length ; i++){
		var urlLinkArr = title_urls[i].split("|");
		contentBuffer += "<a href=\"" + urlLinkArr[1] + "\" target=\"blank\">" + urlLinkArr[0] + "</a><br>";
	}
	dispPanel.innerHTML = contentBuffer;
}

function failureHandler(resp){
	dispPanel.innerHTML = "Can not connect.";
}

dispPanel = document.getElementById('content_Delicious');
loadBookmarks();
</script>
<!-- end Del.icio.us plugin -->
<?php		
}

function add_js() {
    echo "<script type=\"text/javascript\" src=\"plugins/Delicious/js/yahoo.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"plugins/Delicious/js/event.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"plugins/Delicious/js/connection.js\"></script>\n";
}

add_action('f2_head','add_js');
add_action('Delicious','Delicious',4);
?>
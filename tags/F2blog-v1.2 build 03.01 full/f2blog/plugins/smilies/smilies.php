<?
/*
Plugin Name: smilies
Plugin URI: http://korsen.f2blog.com
Description: 表情符号
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function smilies_install() {
	$arrPlugin['Name']="smilies";
	$arrPlugin['Desc']="表情符号";  
	$arrPlugin['Type']="Func";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array("smile","lol","wink","eek","razz","cool","angry","redface","muteness","sad","cry","stun","Belial","angel","heart","breakheart","MSN","cat","dog","month","star","film","music","email","flower","flower","clock","kiss","gift","birthday","photo","idea","tea","phone","Rhug","Lhug","stopper","teeth","bookworm","confused","handclap","askance","loo","rolleyes","sleepy","GoodLuck","Party","illness"); //Default Filed
	$arrPlugin['DefaultValue']=array("Face_01.gif","Face_02.gif","Face_03.gif","Face_04.gif","Face_05.gif","Face_06.gif","Face_07.gif","Face_08.gif","Face_09.gif","Face_10.gif","Face_11.gif","Face_12.gif","Face_13.gif","Face_14.gif","Face_15.gif","Face_16.gif","Face_17.gif","Face_18.gif","Face_19.gif","Face_20.gif","Face_21.gif","Face_22.gif","Face_23.gif","Face_24.gif","Face_25.gif","Face_26.gif","Face_27.gif","Face_28.gif","Face_29.gif","Face_30.gif","Face_31.gif","Face_32.gif","Face_33.gif","Face_34.gif","Face_35.gif","Face_36.gif","Face_48.gif","Face_49.gif","Face_50.gif","Face_51.gif","Face_69.gif","Face_72.gif","Face_73.gif","Face_76.gif","Face_78.gif","Face_68.gif","Face_75.gif","Face_53.gif"); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function smilies_unstall() {
	$ActionMessage=unstall_plugins("smilies");
	return $ActionMessage;
}

function do_smilies($Text,$post_id=""){
	include(F2BLOG_ROOT."./cache/cache_modulesSetting.php");
	if (!empty($plugins_smilies) && is_array($plugins_smilies)){
		foreach($plugins_smilies as $key=>$value){
			$Text=preg_replace("/\[".$key."\]/","<img src=plugins/smilies/smilies/".$value." border=0>", $Text); 
			$Text=preg_replace("/\[emot\]".$key."\[\/emot\]/","<img src=plugins/smilies/smilies/".$value." border=0>", $Text); 
		}
	}

	return $Text;
}

add_filter('f2_ubbcode', 'do_smilies', 1);
add_filter('f2_content', 'do_smilies', 2);
?>
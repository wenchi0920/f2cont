<?php
/*
Plugin Name: SiteFocus
Plugin URI: http://korsen.f2blog.com
Description: 博客焦点
Author: korsen & PuterJam
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

function SiteFocus_install() {
	$arrPlugin['Name']="SiteFocus";
	$arrPlugin['Desc']="博客焦点";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array(
		"imgtext1","imgurl1","imglink1",
		"imgtext2","imgurl2","imglink2",
		"imgtext3","imgurl3","imglink3",
	); //Default Filed
	$arrPlugin['DefaultValue']=array(
		"图片1","plugins/SiteFocus/1.jpg","http://www.f2blog.com",
		"图片2","plugins/SiteFocus/2.jpg","http://www.f2blog.com",
		"图片3","plugins/SiteFocus/3.jpg","http://www.f2blog.com",
	); //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function SiteFocus_unstall() {
	$ActionMessage=unstall_plugins("SiteFocus");
	return $ActionMessage;
}

#欢迎介面
function SiteFocus($sidename,$sidetitle,$htmlcode,$isInstall){
	//解析XML文件
	include_once("include/xmlparse.inc.php");
	$xmlArray=xmlArray("plugins/SiteFocus/site.xml");
	foreach ($xmlArray['item'] as $value){
		$site_name[]=$value['imgtext'];
		$site_url[]=$value['imgurl'];
		$site_link[]=$value['imglink'];
	}

	$imgText=@implode("|",$site_name);
	$imgUrl=@implode("|",$site_url);
	$imgLink=@implode("|",$site_link);

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<!--Site Focus-->
<div class="sidepanel" id="Side_Site_Focus">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
    <script type="text/javascript">
	 var focus_width=160;
	 var focus_height=120;
	 var text_height=18;

	 var swf_height = focus_height+text_height;
	 
	 var pics="<?php echo $imgUrl?>";
	 var links=encodeURIComponent("<?php echo $imgLink?>");
	 var texts="<?php echo $imgText?>";
	 
	 document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
	 document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="plugins/SiteFocus/focus.swf"><param name="quality" value="high"><param name="bgcolor" value="#F0F0F0">');
	 document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
	 document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'"></object>');
	</script>
    </span> </a> <span id=focustext class=f14b></span></div>
  <div class="Pfoot"></div>
</div>
<?php
} #END欢迎介面

add_filter("SiteFocus",'SiteFocus',4);
?>
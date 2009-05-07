<?php
/*
Plugin Name: Subseribe
Plugin URI: http://joesen.f2blog.com/read-485.html
Description: 订阅本站
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function Subseribe_install() {
	$arrPlugin['Name']="Subseribe";
	$arrPlugin['Desc']="订阅本站";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="&lt;a title=&quot;通过Google Reader订阅&quot; href=&quot;http://fusion.google.com/add?feedurl=http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/google.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />&lt;a title=&quot;通过抓虾订阅&quot; href=&quot;http://www.zhuaxia.com/add_channel.php?url=http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/zhuaxia.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />&lt;a title=&quot;通过bloglines订阅&quot; href=&quot;http://www.bloglines.com/sub/http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/bloglines.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />&lt;a title=&quot;通过飞鸽订阅&quot; href=&quot;http://www.pageflakes.com/subscribe.aspx?url=http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/pageflakes.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />&lt;a title=&quot;通过GouGou订阅&quot; href=&quot;http://www.gougou.com/find_rss.jsp?url=http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/gougou.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />&lt;a title=&quot;通过My Yahoo!订阅&quot; href=&quot;http://e.my.yahoo.com/config/cstore?.opt=content&amp;#038;.url=http://feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;plugins/Subseribe/yahoo.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;span style=&quot;height:5px&quot;&gt; &lt;/span&gt;<br />如果您希望使用邮件订阅，请点击这里：&lt;a href=&quot;http://www.emailrss.cn/?rss=http%3A//feeds.feedburner.com/joesen&quot; target=&quot;_blank&quot;&gt;&lt;img alt=&quot;使用Email订阅&quot; src=&quot;plugins/Subseribe/sub_emailrss.gif&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=""; //Default Filed
	$arrPlugin['DefaultValue']=""; //Default value

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function Subseribe_unstall() {
	$ActionMessage=unstall_plugins("Subseribe");
	return $ActionMessage;
}

function Subseribe($sidename,$sidetitle,$htmlcode,$isInstall){
	global $settingInfo;

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_Subseribe">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
		<?php
		if ($htmlcode!=""){
			echo dencode($htmlcode);
		}
		?>
   </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_filter("Subseribe",'Subseribe',4);
?>
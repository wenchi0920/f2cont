<?php
/*
Plugin Name: CallBoard
Plugin URI: http://joesen.f2blog.com/read-468.html
Description: 滚动的公告板
Author: joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function CallBoard_install() {
	$arrPlugin['Name']="CallBoard";
	$arrPlugin['Desc']="滚动的公告板";  
	$arrPlugin['Type']="Main";
	$arrPlugin['Code']="&lt;font color=&quot;#999999&quot; style=&quot;font-weight:bold;&quot;&gt;[&lt;font color=&quot;#acc414&quot;&gt;2006-12-15&lt;/font&gt;]&lt;/font&gt;欢迎选用F2Blog。<br />
&lt;font color=&quot;#999999&quot; style=&quot;font-weight:bold;&quot;&gt;[&lt;font color=&quot;#acc414&quot;&gt;2006-12-15&lt;/font&gt;]&lt;/font&gt;&lt;a href=&quot;http://forum.f2blog.com&quot; target=&quot;_blank&quot;&gt;有任何问题，请到F2Blog论坛中寻求支持！&lt;/a&gt;<br />
&lt;font color=&quot;#999999&quot; style=&quot;font-weight:bold;&quot;&gt;[&lt;font color=&quot;#acc414&quot;&gt;2006-12-15&lt;/font&gt;]&lt;/font&gt;&lt;a href=&quot;http://joesen.f2blog.com&quot;  target=&quot;_blank&quot;&gt;给自己做个广告，有兴趣给我做个友情链接吧。&lt;/a&gt;";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="2";//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function CallBoard_unstall() {
	$ActionMessage=unstall_plugins("CallBoard");
	return $ActionMessage;
}

#滚动的公告板
function CallBoardYou($mainname,$maintitle,$htmlcode){
?>
<!--滚动的公告板-->
<div id="MainContent_<?php echo $mainname?>" class="content-width">
		<?php
		if ($htmlcode!=""){
			$arrCode=explode("<br />",$htmlcode);
		?>
		<table width="100%" height="40" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#f5f9f4;border:2px solid #eaeee8;font-size:12px;text-align:left;">
		<tr>
		<td width="36" align="center"><img src="plugins/CallBoard/callBoard.gif" alt="公告板" /></td>
		<td width="95%" align="right" >
		<div id="icefable1" valign="top">
			<TABLE style="COLOR:#000" height="40" cellSpacing="0" cellPadding="0" width="100%" border="0">
			<?php foreach($arrCode as $key=>$value) { ?>
			<TR><TD align=left height=20>&nbsp;<?php echo dencode(trim($value))?></TD></TR>
			<?php } ?>
			</TABLE>
		</div>

		<script language=javascript> 
			if (navigator.product != 'Gecko'){
				marqueesHeight=20//滚动的高度 
				delaytime = 140 //停留时间 
				scrollupRadio = 18 //每段显示中的文字向上滚动速度... 
				stopscroll=false; 
				document.getElementById('icefable1').scrollTop=0; 
				//设置层的属性 
				with(document.getElementById('icefable1')){  
					//宽度0 
					//style.width=330; 
					//高度为设定的滚动高度 
					style.height=40; 
					//溢出不显示.. 
					style.overflowX="visible"; 
					style.overflowY="hidden"; 
					//不允许换行.. 
					noWrap= false //true; 
					onmouseover=new Function("stopscroll_=true"); 
					onmouseout=new Function("stopscroll_=false"); 
				}

				//将层中的数据输出两次，由于限制了高度，所以不会显示出来； 
				document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML; 
				function init_srolltext(){  
					document.getElementById('icefable1').scrollTop= 0; 
					setInterval("scrollUp()",scrollupRadio); //滚动速度...100 
				} 

				init_srolltext(); 
				preTop=0; currentTop=0; stoptime=0; 
				function scrollUp(){  
					if(stopscroll==true) return; 
					currentTop+=1; 
					if(currentTop==21){  
						stoptime+=1; 
						currentTop-=1; 
						if(stoptime==delaytime){  
							currentTop=0; 
							stoptime=0; 
						} 
					} else {     
						preTop=document.getElementById('icefable1').scrollTop; 
						document.getElementById('icefable1').scrollTop+=1; 
						if(preTop==document.getElementById('icefable1').scrollTop){  
							document.getElementById('icefable1').scrollTop=20
							document.getElementById('icefable1').scrollTop+=1; 
						} 
					} 
				} 
			}
		</script> 				
		</td>
		</tr>
		</table>
<?php }?>
</div>
<?php
} #END滚动的公告板

add_filter("CallBoard",'CallBoardYou',4);
?>
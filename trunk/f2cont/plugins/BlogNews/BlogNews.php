<?php
/*
Plugin Name: BlogNews
Plugin URI: http://blog.phptw.idv.tw/read-100.html
Description: 跑馬燈
Author: wenchi
Version: 1.0
Author URI: http://blog.phptw.idv.tw
*/

function BlogNews_install() {
	$arrPlugin['Name']="BlogNews";
	$arrPlugin['Desc']="跑馬燈";  
	$arrPlugin['Type']="Main";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['indexOnly']="0";//$strModuleContentShow=array("0所有内容头部","1所有内容尾部","2首页内容头部","3首页内容尾部","4首页日志尾部","5读取日志尾部");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function BlogNews_unstall() {
	$ActionMessage=unstall_plugins("BlogNews");
	return $ActionMessage;
}

#滚动的公告板
function ShowBlogNews($mainname,$maintitle,$htmlcode){
	global $DBPrefix,$DMC,$settingInfo;
?>
<!--滚动的公告板-->
<div id="MainContent_<?php echo $mainname?>" class="content-width">
		<?php
		$SQL="select id,logTitle,DATE_FORMAT(FROM_UNIXTIME(postTime),'%Y-%m-%d') as DATE from ".$DBPrefix."logs where isTopNews='1' and isComment='1' and isTrackback='1' and saveType='1' order by id desc";
		$result=$DMC->query($SQL);
		if ($DMC->numRows($result)>0) {
			while ($aryDetail=$DMC->fetchArray($result)) {
				if ($settingInfo['rewrite']==0) $gourl="index.php?load=read&id=".$aryDetail[id];
				if ($settingInfo['rewrite']==1) $gourl="rewrite.php/read-".$aryDetail[id].".html";	
				if ($settingInfo['rewrite']==2) $gourl="read-".$aryDetail[id].".html";				
				$arrCode[]="&nbsp;<font color=\"#999999\" style=\"font-weight:bold;\">[<font color=\"#acc414\">".$aryDetail['DATE']."</font>]</font>&nbsp;<a href=\"".$gourl."\" target=\"_self\">".$aryDetail[logTitle]."</a>";
			}
		}
		elseif ($htmlcode!=""){
			$arrCode=explode("<br />",$htmlcode);
		}
		?>

		<table width="100%" height="40" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#f5f9f4;border:2px solid #eaeee8;font-size:12px;text-align:left;">
		<tr>
		<td width="36" align="center"><img src="plugins/BlogNews/callBoard.gif" alt="公告板" /></td>
		<td width="95%" align="right" >
		<div id="icefable1" valign="top" style="overflow:hidden; height:40px; float:left;">
			<TABLE style="COLOR:#000" height="40" cellSpacing="0" cellPadding="0" width="100%" border="0">
			<?php if (is_array($arrCode)){foreach($arrCode as $key=>$value) { ?>
			<TR><TD align=left height=20>&nbsp;<?php echo dencode(trim($value))?></TD></TR>
			<?php }} ?>
			</TABLE>
		</div>

		<script language=javascript> 
			//if (navigator.product != 'Gecko'){
				marqueesHeight=20//滚动的高度 
				delaytime = 140 //停留时间 
				scrollupRadio = 18 //每段显示中的文字向上滚动速度... 
				stopscroll=false; 
				document.getElementById('icefable1').scrollTop=0;
				
				var m = document.getElementById('icefable1');
				m.style.width=330; 
				m.style.height=40; 
				m.noWrap= false //true; 
				m.onmouseover = function(){
				//	alert('test1');
					scrollUp(true);
				}
				m.onmouseout = function(){
				//	alert('test2');
					scrollUp(false);
				}
				//m.setAttribute('onmouseover', 'scrollUp(true)'); 
				//m.setAttribute('onmouseout', 'scrollUp(false)'); 
				//m.onmouseover=new Function("stopscroll_=true"); 
				//m.onmouseout=new Function("stopscroll_=false"); 
				//	    a_upLink.setAttribute('onclick', 'fun2(document.getElementById("upLink"))'); 

				
				
				//设置层的属性 
				/*
				with(document.getElementById('icefable1')){  
					//宽度0 
					//style.width=330; 
					//高度为设定的滚动高度 
					style.height=40; 
					//溢出不显示.. 
					//style.overflowX="visible"; 
					//style.overflow="hidden"; 
					//不允许换行.. 
					noWrap= false //true; 
					onmouseover=new Function("stopscroll_=true"); 
					onmouseout=new Function("stopscroll_=false"); 
					
					alert('test0');
					
				}
				*/
				//将层中的数据输出两次，由于限制了高度，所以不会显示出来； 
				document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML+=document.getElementById('icefable1').innerHTML; 
				function init_srolltext(){  
					//alert('test1');
					document.getElementById('icefable1').scrollTop= 0; 
				//	alert('test2');
					setInterval("scrollUp(false)",scrollupRadio); //滚动速度...100 
					//alert('test3');
				} 

				init_srolltext(); 
				preTop=0; currentTop=0; stoptime=0; 
					//alert('test4');
				function scrollUp(stopscroll){  
					if (stopscroll==true) return ;
				
				//	if(stopscroll==true) return; 
				//	alert('test5');
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
			//}
		</script> 				
		</td>
		</tr>
		</table>
</div>
<?php
}

add_filter("BlogNews",'ShowBlogNews',4);
?>
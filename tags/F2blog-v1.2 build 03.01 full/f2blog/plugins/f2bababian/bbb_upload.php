<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")>0){
	$browser="ie";
}else{
	$browser="firefox";
}

//取得cache值
if (file_exists("../../cache/cache_modulesSetting.php")){
	include("../../cache/cache_modulesSetting.php");
	if (count($plugins_f2bababian)>0){
		$bbb_api_key=$plugins_f2bababian['bbb_api_key'];
		$bbb_user_key=$plugins_f2bababian['bbb_user_key'];
	}else{
		die("插件没有安装好！");
	}
}

if ($bbb_api_key!="" && $bbb_user_key!=""){
	include ('BabaBian.php');
	$GoBind=new BabaBian();	
	$GoBind->api_key=$bbb_api_key;

	if ($GoBind->photouploadSession($bbb_user_key)){
		list($bbb_session_key,$bbb_cap_total,$bbb_cap_used)=$GoBind->upload_Session_info;
		//echo "$bbb_session_key == $bbb_cap_total == $bbb_cap_used <br />";
	}else{
		$ActionMessage.=$GoBind->fault[0]."(".$GoBind->fault[1].")<br />"; //显示错误相关信息
	}
}else{
	$ActionMessage="你的巴巴变账没有设定正确！";
}
?>
<HEAD>
<TITLE>巴巴变文件上载</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<SCRIPT>
 <?php if ($browser=="ie"){?>
 function mCreateFile(obj){
  var eF
  var mName
  mFileName.innerHTML=""
    if (obj.id=="File") {
    for (i=0;i<mFile.children.length-1;i++)
    {
      if (mFile.children[i].value=="") {
              mFile.removeChild(mFile.children[i])
      }
      else
      {
        mName=mFile.children[i].value.split("\\")
        mFileName.innerHTML+="<div id=NameDetail title='"+mName[mName.length-1]+"'>"+mName[mName.length-1]+"</div>"
      }
    }
    mstatus.innerHTML="总共有 <b>"+(mFile.children.length-1)+"</b> 个文件等待上传"
  }
 
  if (obj.id=="File_New") {
    if(mFile.children.length>=6){
	  mstatus.innerHTML="总共有 <b>"+(mFile.children.length)+"</b> 个文件等待上传"
	  return;
	}
    eF=document.createElement('<input type="file" name="File" size="30" id=File_New onpropertychange="mCreateFile(this)">')
    mFile.appendChild(eF)
    obj.id="File"
  }
 }
 <?php }else{?>
	 function mViewStatus(obj) {
		var num=0
		mFileName.innerHTML="";
		for(i=0;i<document.photoform.File.length;i++){
			if (document.photoform.File[i].value!="") {
				num++
				mName=document.photoform.File[i].value.split("\\")
				mFileName.innerHTML+="<div id=NameDetail title='"+mName[mName.length-1]+"'>"+mName[mName.length-1]+"</div>"
			}
		}
		mstatus.innerHTML="总共有 <b>"+num+"</b> 个文件等待上传"
	 }
 <?php }?>
</SCRIPT>
<STYLE>
#mTD {
	LINE-HEIGHT: 24px
}
#mFile {
	FLOAT: left; WIDTH: 203px;
}
#mFile2 {
	FLOAT: left; WIDTH: 203px;
}
#mFileName {
	FLOAT: right; WIDTH: 180px!important; WIDTH: 100px;clear: left!important;clear: none;
}
#mFileName2 {
	FLOAT: right; WIDTH: 100px;
}
#NameDetail {
	FONT-SIZE: 12px; OVERFLOW: hidden; WIDTH: 176px; CURSOR: default; COLOR: #000000; FONT-FAMILY: Verdana,Arial,SimSun; HEIGHT: 22px
}
#NameDetail2 {
	FONT-SIZE: 12px; OVERFLOW: hidden; WIDTH: 176px; CURSOR: default; COLOR: #000000; FONT-FAMILY: Verdana,Arial,SimSun; HEIGHT: 22px
}
#mstatus {
	FONT-SIZE: 12px; COLOR: #ff0000;clear: left!important;clear: none;
}
#mstatus2 {
	FONT-SIZE: 12px; COLOR: #ff0000;clear: left!important;clear: none;
}

.alertTxt {
	PADDING-RIGHT: 6px; PADDING-LEFT: 6px; FONT-WEIGHT: bold; FONT-SIZE: 12px; BACKGROUND: url(images/aM.gif) #ffcc00; PADDING-BOTTOM: 8px; PADDING-TOP: 8px;margin-top:10px;
}
</STYLE>
<link href="../../admin/images/css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
if (!empty($ActionMessage)){
	echo "<DIV style=\"FONT-SIZE: 12px; MARGIN-BOTTOM: 8px\" align=center><SPAN class=alertTxt>$ActionMessage</SPAN></DIV> \n";
	exit;
}
?>
<!--Creat By wbc -->
<!--Http://wbc.bkkss.com -->
<!--MSN : wbc@bkkss.com -->
<DIV style="FONT-SIZE: 12px; MARGIN-BOTTOM: 8px" align=center><SPAN class=alertTxt>每月最大使用量：<?php echo $bbb_cap_total?>MB 已用空间：<?php echo $bbb_cap_used?>MB</SPAN></DIV>
<FORM id=photoform name=photoform action=http://www.bababian.com/upload method=post encType=multipart/form-data>
  <INPUT type=hidden value=pho name=file_type>
  <INPUT type=hidden value=<?php echo $bbb_session_key?> name=session_key>
  <TABLE id=table1 style="BORDER-COLLAPSE: collapse" borderColor=#0066cc cellPadding=4 width="100%" bgColor=#d0f0ff border=1 align="center">
    <TBODY>
      <TR>
        <TD align=middle bgColor=#000033 height=26><FONT color=#ffffff><B>单个图片添加</B></FONT></TD>
      </TR>
      <TR>
        <TD id=mTD>
          <DIV id=mFile>
			<?php if ($browser=="ie"){?>
            <INPUT id="File_New" onpropertychange="mCreateFile(this)" type="file" size="30" name="File">
			<?php }else{?>
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
            <INPUT id="File_New" onchange="mViewStatus(this)" type="file" size="30" name="File">
			<?php }?>
          </DIV>
          <DIV id=mFileName></DIV>
          <DIV id=mstatus>总共有 <B>0</B> 个文件等待上传</DIV>
        </TD>
      </TR>
    </TBODY>
  </TABLE>
  <DIV align=center>
    <INPUT type=submit value=提交 name=Submit class="btn">
    </LABEL>
  </DIV>
</FORM>
<FORM id=fileform name=fileform action=http://www.bababian.com/upload method=post encType=multipart/form-data>
  <INPUT type=hidden value=zip name=file_type>
  <INPUT type=hidden value=<?php echo $bbb_session_key?> name=session_key>
  <TABLE id=table2 style="BORDER-COLLAPSE: collapse" borderColor=#0066cc cellPadding=4 width="100%" bgColor=#d0f0ff border=1>
    <TBODY>
      <TR>
        <TD align=middle bgColor=#660000 height=26><FONT color=#ffffff><B>图片打包，RAR或Zip添加</B></FONT></TD>
      </TR>
      <TR>
        <TD id=mTD>
          <DIV id=mFile2>
            <INPUT type=file size=30 name=File id=File>
          </DIV>
          <DIV id=mFileName2></DIV>
          <DIV id=mstatus2>一次只可上载一个RAR或ZIP文件</DIV>
        </TD>
      </TR>
    </TBODY>
  </TABLE>
  <DIV align=center>
    <INPUT id=Submit2 type=submit value=提交 name=Submit2 class="btn">
  </DIV>
</FORM>
</BODY>
</HTML>

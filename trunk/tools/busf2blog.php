<?php
/*
Tool Name: BlogBus
Tool URI: http://korsen.f2bLog.com
Description: blogbus格式汇入f2bLog
Author: korsen
Version: 1.0
Author URI: http://korsen.f2bLog.com
*/

$plugins_path="../";
include("../include/function.php");

echo "<h2>BLOGBUS汇入F2blog程序</h2>";

$blogbus_xml="blogbus.xml";

if (!file_exists($blogbus_xml)){
	echo "注意事项：汇入的xml名称必须是blogbus.xml，并且通过ftp上传到tools目录下面。";
}else{
	//解析XML文件
	include_once("../include/xmlparse.inc.php");
	$xmlArray=xmlArray($blogbus_xml);
	foreach ($xmlArray['Log'] as $key=>$value){
		$logTitle[]=encode($value['Title']);
		$postTime[]=strtotime($value['LogDate']);
		$logContent[]=str_replace("'","&#39;",$value['Content']);
		$saveType[]=1;
		$isComment[]=1;
		$isTrackback[]=1;
		$author[]=$value['Writer'];
		$quoteUrl[]=$value['TrackBack'];
		$tags[]=$value['Tags'];		

		$arr_comments[$key]=empty($value['Comments'][0]['Comment'])?array():$value['Comments'][0]['Comment'];
	}

	//删除类别和删除日志
	$DMC->query("TRUNCATE TABLE ".$DBPrefix."categories");
	$DMC->query("TRUNCATE TABLE ".$DBPrefix."logs");
	$DMC->query("TRUNCATE TABLE ".$DBPrefix."comments");

	//新增一个类别
	$sql="select id from ".$DBPrefix."categories";
	if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
		$cateId=$arr_result['id'];
	}else{
		$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('0','BlogBus','1','BlogBus','','".count($logTitle)."','0')";
		$DMC->query($sql);
		$cateId=$DMC->insertId();
	}

	$error=0;
	$success=0;
	$i=0;
	foreach($logTitle as $key=>$value){
		$i=$key+1;
		echo "正在汇入第$i 条日志 ... ";
		$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter) VALUES ('$cateId','$logTitle[$key]','$logContent[$key]','$author[$key]','$quoteUrl[$key]','$postTime[$key]','$isComment[$key]','$isTrackback[$key]','0','sunny','$saveType[$key]','$tags[$key]','','tiny')";
		$DMC->query($sql);
		if ($DMC->error()){
			$error++;
			echo $DMC->error()."<br>";
		}else{
			$logsId=$DMC->insertId();
			//增加评论
			foreach($arr_comments[$key] as $subkey=>$subvalue){
				$subvalue['CommentText']=encode(strip_tags($subvalue['CommentText']),"<br>");
				$comm_sql="insert into ".$DBPrefix."comments(author,password,logId,ip,content,postTime,isSecret,parent) values('".$subvalue['NiceName']."','','".$logsId."','".$subvalue['PostIP']."','".$subvalue['CommentText']."','".str_format_time($subvalue['CreateTime'])."','0','0')";
				$DMC->query($comm_sql);
			}

			$success++;
			echo "<font color=red>OK</font><br>";
		}
	}

	echo "<br>共有$i 条日志，成功汇入$success 条，失败$error 条";
}
?>
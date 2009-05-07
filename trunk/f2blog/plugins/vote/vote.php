<?php
/*
Plugin Name: vote
Plugin URI: http://joesen.f2blog.com/read-462.html
Description: 投票插件
Author: Joesen
Version: 1.0
Author URI: http://joesen.f2blog.com
*/

function vote_install() {
	global $DMC,$DBPrefix;

	$arrPlugin['Name']="vote";
	$arrPlugin['Desc']="投票";  
	$arrPlugin['Type']="Side";
	$arrPlugin['Code']="";
	$arrPlugin['Path']="";
	$arrPlugin['DefaultField']=array("voteWidth","voteHeight","voteTM");
	$arrPlugin['DefaultValue']=array("175","250","0");

	//建表及插入默认数据
	if ($DMC->getServerInfo() > '4.1') {
		$default_charset=" DEFAULT CHARACTER SET utf8";
	}else{
		$default_charset="";
	}

	if (!$DMC->query("SELECT id FROM ".$DBPrefix."vote",'T')){
		$sql="CREATE TABLE `{$DBPrefix}vote` (`id` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`voteco` VARCHAR( 200 ) NOT NULL ,`cs_1` VARCHAR( 50 ) NOT NULL ,`cs_2` VARCHAR( 50 ) NOT NULL ,`cs_3` VARCHAR( 50 ) NOT NULL ,`cs_4` VARCHAR( 50 ) NOT NULL ,`cs_5` VARCHAR( 50 ) NOT NULL ,`cs_1_num` INT( 5 ) NOT NULL DEFAULT '0',`cs_2_num` INT( 5 ) NOT NULL DEFAULT '0' ,`cs_3_num` INT( 5 ) NOT NULL DEFAULT '0' ,`cs_4_num` INT( 5 ) NOT NULL DEFAULT '0' ,`cs_5_num` INT( 5 ) NOT NULL DEFAULT '0' ,`oorc` VARCHAR( 5 ) NOT NULL ,`bg_color` VARCHAR( 10 ) NOT NULL,`word_color` VARCHAR( 10 ) NOT NULL,`word_size` VARCHAR( 2 ) NOT NULL,`vote_time` VARCHAR( 20 ) NOT NULL ,`votevi` INT( 5 ) NOT NULL DEFAULT '0' ,`sorm` VARCHAR( 5 ) NOT NULL ,INDEX ( `voteco` ) ) ENGINE = MYISAM $default_charset;";
		$DMC->query($sql);
	}
	$curTime=time();
	$DMC->query("INSERT INTO {$DBPrefix}vote VALUES (1, '您认为F2Blog那些需要加强？', '功能', '皮肤', '插件', '速度', '服务', '10', '5', '8', '13', '2', 'True', 'EEEEEE', '333399', '11', '$curTime', '0', 'True')");

	$ActionMessage=install_plugins($arrPlugin);
	return $ActionMessage;
}

//Unstall Plugin
function vote_unstall() {
	global $DMC,$DBPrefix;
	$DMC->query("drop table {$DBPrefix}vote");
	$ActionMessage=unstall_plugins("vote");
	return $ActionMessage;
}

function vote($sidename,$sidetitle,$htmlcode,$isInstall){
	global $DMC;

	$settingValue=readfromfile("./plugins/vote/vote.txt");
	$arrSet= explode(',', $settingValue);
    $WS = $arrSet[0];
    $HS = $arrSet[1];
    $TP = $arrSet[2];         

	if (isset($_COOKIE["content_$sidename"])){
		$display=$_COOKIE["content_$sidename"];
	}else{
		$display=($isInstall>0)?"none":"";
	}
?>
<div class="sidepanel" id="Side_Site_vote">
  <h4 class="Ptitle" style="cursor: pointer;" onclick="sidebarTools('<?php echo "content_$sidename"?>')"><?php echo $sidetitle?></h4>
  <div class="Pcontent" id="<?php echo "content_$sidename"?>" style="display:<?php echo $display?>">
	<?
	if ($TP==0){
		$vote_code = "<embed id='vote' src='./plugins/vote/flashvote.swf' width='$WS' height='$HS' type='application/x-shockwave-flash' scale='exactfit' wmode='transparent' menu='false'></embed>";
	} else {
		$vote_code = "<embed id='vote' src='./plugins/vote/flashvote.swf' width='$WS' height='$HS' type='application/x-shockwave-flash' scale='exactfit' menu='false'></embed>";
	}
	echo $vote_code;
	?>
  </div>
  <div class="Pfoot"></div>
</div>
<?php
}

add_filter("vote",'vote',4);
?>
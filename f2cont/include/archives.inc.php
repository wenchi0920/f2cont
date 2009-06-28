<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>
<div id="Content_ContentList" class="content-width">
	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><b><?php echo $strArchives?></b></h1>
		<h2 class="ContentAuthor"><a href="archives/" target="_blank"><?php echo $strArchivesList?></a></h2>
	  </div>
	  <div class="Content-body">
		<?php 
		$saveType=($_SESSION['rights']=="admin")?"saveType=1 or saveType=3":"saveType=1";

		$archives = $DMC->query("SELECT postTime FROM ".$DBPrefix."logs where $saveType ORDER BY postTime DESC");
		$articledb = array();
		while ($article = $DMC->fetchArray($archives)) {
			$article['dateline'] = format_time("Y,m",$article['postTime']);
			$articledb[]=$article['dateline'];
		}
		unset($article);
		$archivedb = array_count_values($articledb);
		unset($articledb);

		$i=0;
		$lastyear="";
		foreach($archivedb as $key => $val){
			list($logYear,$logMonth)=explode(",",$key);
			$logName="$logMonth $strMonth";

			if ($lastyear!=$logYear){
				if ($settingInfo['rewrite']==0) $gourl="index.php?job=archives&amp;seekname=$logYear";
				if ($settingInfo['rewrite']==1) $gourl="rewrite.php/archives-$logYear";
				if ($settingInfo['rewrite']==2) $gourl="archives-$logYear";
				
				$lastyear=$logYear;
				if ($i>0) echo "</div></div>";
		?>
		<div class="linkover">
			<div class="linkgroup"><span class="linktitle"><a href="<?php echo $gourl.$settingInfo['stype']?>"><?php echo "$logYear"?></a></span></div>
			<div class="linkgroupcontent">
		<?php 
			}
			$i++;
			if ($settingInfo['rewrite']==0) $gourl="index.php?job=archives&amp;seekname=$logYear$logMonth";
			if ($settingInfo['rewrite']==1) $gourl="rewrite.php/archives-$logYear$logMonth";
			if ($settingInfo['rewrite']==2) $gourl="archives-$logYear$logMonth";
		?>
				<div class="archivesbody">
					<div class="linkimg"><span class="linktitle"><a href="<?php echo $gourl.$settingInfo['stype']?>"><?php echo $logName?></a></span></div>
					<div class="linktxt">(<?php echo $val?>)</div>
				</div>
		<?php } if ($i>0) echo "</div></div>";?>
	  </div>
	</div>
</div>
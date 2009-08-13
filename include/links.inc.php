<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>

<div id="Content_ContentList" class="content-width">
	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><b><?php echo $strLinks?></b></h1>
		<h2 class="ContentAuthor"><?php if ($settingInfo['applylink']==1){?><a href='index.php?load=applylink'><?php echo $strApplyLink?></a><?php }else{echo "Links";}?></h2>
	  </div>
	  <div class="Content-body">
		<?php 
		$linkgroup = $DMC->fetchQueryAll($DMC->query("select * from {$DBPrefix}linkgroup order by id"));
		foreach($linkgroup as $key=>$value){
		?>
		<div class="linkover">
			<div class="linkgroup"><?php echo $value['name']?></div>
			<div class="linkgroupcontent">
				<?php 
				$res=$DMC->query("select * from ".$DBPrefix."links where lnkGrpId='".$value['id']."' and isApp='1' order by blogLogo desc,orderNo desc");
				while ($my=$DMC->fetchArray($res)) {
					$blogLogo=($my['blogLogo']!="")?"<img src=\"".$my['blogLogo']."\" alt=\"".$my['name']."\" border=\"0\" />":"";
				?>
				<div class="linkbody">
					<div class="linkimg"><?php echo $blogLogo?></div>
					<div class="linktxt">
						<span class="linktitle"><a href="<?php echo $my['blogUrl']?>" target="_blank"><?php echo $my['name']?></a></span><br/>
					</div>
				</div>
				<?php  } ?>
			</div>
		</div>
		<?php  } ?>
	  </div>
	</div>
</div>
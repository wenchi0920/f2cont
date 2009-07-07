<div id="Content_ContentList" class="content-width">
	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><b><?=$strHotTags?></b></h1>
		<h2 class="ContentAuthor">Tags Cloud</h2>
	  </div>
	  <div class="Content-body">
		<?
			list($maxTag,$minTag)=getTagRange();
			//require("cache/cache_hottags.php");
			$tagscache = $DMF->fetchQueryAll($DMF->query("select * from ".$DBPrefix."tags order by logNums desc"));
			for ($i=0;$i<count($tagscache);$i++){
				$curColor=getTagHot($tagscache[$i]['logNums'],$maxTag,$minTag);
				$strDayLog=$strTagsCount.": ".$tagscache[$i]['logNums'];
				
				echo "<a href=\"$PHP_SELF?job=tag&seekname=".$tagscache[$i]['name']."\" style=\"color:$curColor\" title=\"$strDayLog\"><span style=\"font-size:13px\">".$tagscache[$i]['name']."</span></a>&nbsp;&nbsp; \n";
			}
		?>
	  </div>
	</div>
</div>
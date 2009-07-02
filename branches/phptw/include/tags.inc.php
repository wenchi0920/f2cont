<?php if (!defined('IN_F2BLOG')) die ('Access Denied.');?>

<div id="Content_ContentList" class="content-width">
	<div class="Content">
	  <div class="Content-top">
		<div class="ContentLeft"></div>
		<div class="ContentRight"></div>
		<h1 class="ContentTitle"><b><?php echo $strHotTags?></b></h1>
		<h2 class="ContentAuthor">Tags Cloud</h2>
	  </div>
	  <div class="Content-body">
		<?php 
			function getTagSize($countk,$maxk,$mink){
				$distk=$maxk/3;
				if($countk==$mink)
					return "12px";
				elseif($countk==$maxk)
					return "30px";
				elseif($countk>=$mink+($distk*2))
					return "26px";
				elseif($countk>=$mink+$distk)
					return "22px";
				else
					return "16px";
			}

			if ($settingInfo['rewrite']==0) $gourl="index.php?job=tags&amp;seekname=";
			if ($settingInfo['rewrite']==1) $gourl="rewrite.php/tags-";
			if ($settingInfo['rewrite']==2) $gourl="tags-";

			$arr_result=$DMC->fetchArray($DMC->query("select max(logNums) as max,min(logNums) as min from ".$DBPrefix."tags"));
			if ($arr_result['max']=="") $arr_result['max']=0;
			if ($arr_result['min']=="") $arr_result['min']=0;
			
			$tagsmaxnumber=$arr_result['max'];
			$tagsminnumber=$arr_result['min'];

			$tagscache = $DMC->fetchQueryAll($DMC->query("select * from ".$DBPrefix."tags order by logNums desc"));
			foreach($tagscache as $key=>$value){
				$curColor=getTagHot($value['logNums'],$tagsmaxnumber,$tagsminnumber);
				$curSize=getTagSize($value['logNums'],$tagsmaxnumber,$tagsminnumber);
				$strDayLog=$strTagsCount.": ".$value['logNums'];
				
				echo "<a href=\"$gourl".urlencode($value['name']).$settingInfo['stype']."\" style=\"color:$curColor;font-size:$curSize;line-height:35px;\" title=\"$strDayLog\">".$value['name']."(".$value['logNums'].")</a>&nbsp;&nbsp;&nbsp; \n";
			}
		?>
	  </div>
	</div>
</div>
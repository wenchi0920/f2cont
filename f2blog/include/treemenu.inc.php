<?
/** 禁止直接访问该页面 */
if (basename($_SERVER['PHP_SELF']) == "treemenu.inc.php") {
    header("HTTP/1.0 404 Not Found");
    exit;
}

//如果Cache不存在，就调用include/cache.php来生成。
if (file_exists("cache/cache_categories.php")){
	//调用Cache文件。
	include_once("cache/cache_categories.php");
}

/*测试代码
$PATH=".";
$PHP_SELF=$_SERVER['PHP_SELF'];
require_once("$PATH/include/config.php");
require_once("$PATH/include/db.php");

//Connect Database
$DMC = new DummyMySQLClass($DBHost, $DBUser, $DBPass, $DBName, $DBNewlink);
//print_r($DMC);

//get sum category
$sum_sql="select sum(cateCount) as sum_total from ".$DBPrefix."categories where parent='0' and isHidden='0'";
$sum_result=$DMC->query($sum_sql);
if ($arr_result=$DMC->fetchArray($sum_result)){
	$sum_total=$arr_result['sum_total'];
}else{
	$sum_total=0;
}

//get main category
$query_sql="select id,name,cateTitle,outLinkUrl,cateCount from ".$DBPrefix."categories where parent='0' and isHidden='0' order by orderNo";
$query_result=$DMC->query($query_sql);
$arr_parent = $DMC->fetchQueryAll($query_result);
for ($i=0;$i<count($arr_parent);$i++){
	//get sub category
	$sub_sql="select id,name,cateTitle,outLinkUrl,cateCount from ".$DBPrefix."categories where parent='".$arr_parent[$i]['id']."' and isHidden='0' order by orderNo";
	$sub_result=$DMC->query($sub_sql);
	$arr_sub[$i] = $DMC->fetchQueryAll($sub_result);
//	print_r($arr_sub);
}

//print_r($arr_parent);
//echo "<br><br>";
//print_r($arr_sub);
*/
?>
<script type="text/javascript">
//<![CDATA[
	function openCategory(category) {
		var oLevel1 = document.getElementById("category_" + category);
		var oImg = oLevel1.getElementsByTagName("img")[0];
		switch (oImg.src.substr(oImg.src.length - 10, 6)) {
			case "isleaf":
				return true;
			case "closed":
				oImg.src = "<?=$cfg_category_path?>/tab_opened.gif";
				showLayer("category_" + category + "_children");
				expanded = true;
				return true;
			case "opened":
				oImg.src = "<?=$cfg_category_path?>/tab_closed.gif";
				hideLayer("category_" + category + "_children");
				expanded = false;
				return true;
		}
		return false;
	}
	
	function showLayer(id) {
		document.getElementById(id).style.display = "block";
		return true;
	}
	
	function hideLayer(id) {
		document.getElementById(id).style.display = "none";
		return true;
	}	
//]]>
</script>

<table id="treeComponent" cellpadding="0" cellspacing="0" style="width: 100%; font: 12px/1.5 Dotum;font-family:;">
  <tr>
    <td>
	  <table id="category_0" name="treeNode" cellpadding="0" cellspacing="0">
        <tr>
          <td width="18"><img src="<?=$cfg_category_path?>/tab_top.gif" width="16" alt=""/></td>
          <td valign="top" onclick="window.location.href='<?="index.php"?>'"><div id="text_0" style="cursor:pointer; color:"><?=$strAllCategory?> <span class="c_cnt">(<?=$sum_total?>)</span>
		  &nbsp;<span class="rss"><a href='rss.php'>[RSS]</a></span></div></td>
        </tr>
      </table>
	  <?
	  for ($i=0;$i<count($arr_parent);$i++){
		if ($arr_sub[$i][0]!=""){		
	  ?>
      <table name="treeNode" id="category_<?=$i+1?>" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ib" style="width:40px; font-size: 1px; background-image: url('<?=($i<count($arr_parent)-1)?"$cfg_category_path/navi_back_noactive.gif":"$cfg_category_path/navi_back_noactive_end.gif"?>')"><a class="click" onclick="openCategory('<?=$i+1?>')"><img src="<?=$cfg_category_path?>/tab_closed.gif" width="39" alt=""/></a></td>
          <td><table cellpadding="0" cellspacing="0" style="background-color:;">
              <tr>
                <td class="branch3" onclick="window.location.href='<?=($arr_parent[$i]['outLinkUrl'])?$arr_parent[$i]['outLinkUrl']:"index.php?job=category&seekname=".$arr_parent[$i]['id']?>'" title="<?=$arr_parent[$i]['cateTitle']?>"><div id="text_<?=$i+1?>" style="cursor:pointer;color:"><?=$arr_parent[$i]['name']?> <span class="c_cnt">(<?=$arr_parent[$i]['cateCount']?>)</span>
				&nbsp;<span class="rss"><a href='rss.php?cateID=<?=$arr_parent[$i]['id']?>'>[RSS]</a></span></div>
				</td>
              </tr>
            </table></td>
        </tr>
      </table>
	  <?}else{?>
      <table name="treeNode"  id="category_category_<?=$i+1?>" cellpadding="0" cellspacing="0">
        <tr>
          <td class="ib" style="width:40px; font-size: 1px; background-image: url('<?=($i<count($arr_parent)-1)?"$cfg_category_path/navi_back_noactive.gif":"$cfg_category_path/navi_back_noactive_end.gif"?>')"><a class="click"><img src="<?=$cfg_category_path?>/tab_isleaf.gif" width="39" alt=""/></a></td>
          <td><table cellpadding="0" cellspacing="0" style="background-color:;">
              <tr>
                <td class="branch3" onclick="window.location.href='<?=($arr_parent[$i]['outLinkUrl'])?$arr_parent[$i]['outLinkUrl']:"index.php?job=category&seekname=".$arr_parent[$i]['id']?>'" title="<?=$arr_parent[$i]['cateTitle']?>"><div id="text_<?=$i+1?>" style="cursor:pointer;color:" ><?=$arr_parent[$i]['name']?> <span class="c_cnt">(<?=$arr_parent[$i]['cateCount']?>)</span>
				&nbsp;<span class="rss"><a href='rss.php?cateID=<?=$arr_parent[$i]['id']?>'>[RSS]</a></span></div></td>
              </tr>
            </table></td>
        </tr>
      </table>
	  <?}?>

      <div id="category_<?=$i+1?>_children" style="display:none">
		<?
			for ($j=0;$j<count($arr_sub[$i]);$j++){
		?>
        <table id="category_<?=$i.$j?>" name="treeNode" cellpadding="0" cellspacing="0">
          <tr>
            <td style="width:40px; font-size: 1px"><img src="<?=($i<count($arr_parent)-1)?"$cfg_category_path/navi_back_active.gif":"$cfg_category_path/navi_back_noactive_end.gif"?>" width="17" alt=""/><?if ($j==count($arr_sub[$i])-1){?><img src="<?=$cfg_category_path?>/tab_treed_end.gif" width="22" alt=""/>
				<?}else{?><img src="<?=$cfg_category_path?>/tab_treed.gif" width="22" alt=""/><?}?>
			</td>
            <td>
			  <table onclick="window.location.href='<?=($arr_sub[$i][$j]['outLinkUrl'])?$arr_sub[$i][$j]['outLinkUrl']:"index.php?job=category&seekname=".$arr_sub[$i][$j]['id']?>'" cellpadding="0" cellspacing="0" style="background-color:;" title="<?=$arr_sub[$i][$j]['cateTitle']?>">
                <tr>
                  <td class="branch3"><div id="text_<?=$i.$j?>" style="cursor:pointer;color:"><?=$arr_sub[$i][$j]['name']?> <span class="c_cnt">(<?=$arr_sub[$i][$j]['cateCount']?>)</span>
				  &nbsp;<span class="rss"><a href='rss.php?cateID=<?=$arr_sub[$i][$j]['id']?>'>[RSS]</a></span></div></td>
                </tr>
              </table>
			</td>
          </tr>
        </table>
		<?  }//end sub ?>
	  </div>
	 <?}//end parent?>
	</td>
  </tr>
</table>
<?php 
/*
Plugin Name: sitemap
Plugin URI: http://korsen.f2blog.com
Description: google sitemap
Author: korsen
Version: 1.0
Author URI: http://korsen.f2blog.com
*/

include_once("include/function.php");

$home_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"googlesitemap.php"));

if ($settingInfo['rewrite']==0) $gourl=$home_url."index.php?load=read&amp;id=";
if ($settingInfo['rewrite']==1) $gourl=$home_url."rewrite.php/read-";
if ($settingInfo['rewrite']==2) $gourl=$home_url."read-";

$sql="select postTime,id from ".$DBPrefix."logs where saveType=1 order by postTime desc";

//echo $sql;
$query_result=$DMC->query($sql);
$arr_array=$DMC->fetchQueryAll($query_result);

ob_end_clean();
header('Content-Type: text/xml; charset=utf-8');
echo "<?php xml version=\"1.0\" encoding=\"UTF-8\"?> \n";
?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
      <url>
          <loc><?php echo $home_url?></loc>
          <lastmod><?php echo format_time("Y-m-d\TH:i:s\Z",time())?></lastmod>
          <changefreq>always</changefreq>
          <priority>1.0</priority>
      </url>
	<?php 
	foreach($arr_array as $key=>$value){
	?>
      <url>
          <loc><?php echo $gourl.$value['id'].$settingInfo['stype']?></loc>
          <lastmod><?php echo format_time("Y-m-d\TH:i:s\Z",$value['postTime'])?></lastmod>
          <changefreq>daily</changefreq>
          <priority>0.8</priority>
      </url>
	<?php }?>
</urlset>
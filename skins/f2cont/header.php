<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="robots" content="all" />
<meta name="author" content="<?php echo $settingInfo['email']?>" />
<meta name="Copyright" content="<?php echo blogCopyright?>" />
<meta name="keywords" content="<?php echo blogKeywords.",".$settingInfo['blogKeyword'].$logTags?>" />
<meta name="description" content="<?php echo $settingInfo['name']?> - <?php echo $settingInfo['blogTitle']?>" />
<title><?php echo (!empty($borwseTitle))?$borwseTitle." - ":""?><?php echo $settingInfo['name']?></title>
<?php if (!empty($load) && $load=="read"){?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php?cateID=<?php echo $arr_array['cateId']?>" title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php?cateID=<?php echo $arr_array['cateId']?>"  title="<?php echo $settingInfo['name']?> - <?php echo $arr_array['name']?>(Atom)"  />
<?php }else{?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $settingInfo['blogUrl']?>rss.php" title="<?php echo $settingInfo['name']?>(Rss2)" />
<link rel="alternate" type="application/atom+xml" href="<?php echo $settingInfo['blogUrl']?>atom.php" title="<?php echo $settingInfo['name']?>(Atom)" />
<?php }?>


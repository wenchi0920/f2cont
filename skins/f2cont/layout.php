<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>

<?php echo doHtmlMetaHeader();?>

</head>

<body>
<div id="container">

	<?php echo doHtmlHeader();?>
	
    <!--内容-->
    <div id="Tbody">
        
		<?php echo doHtmlLeftSidebar();?>
        
		<?php echo doHtmlMainContent();?>
		
		<?php echo doHtmlRightSidebar();?>
        
        <div style="clear: both;height:1px;overflow:hidden;margin-top:-1px;"></div>
    </div>
    
    <?php echo doHtmlFoot();?>
    
</div>

<?php echo doFoot();?>

</body>
</html>

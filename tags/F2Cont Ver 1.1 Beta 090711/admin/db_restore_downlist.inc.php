<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">
	<div class="contenttitle"><?php echo $title?></div>
		<div class="subcontent">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr class="subcontent-title">
			  <td><?php echo $strAttachmentsName?></td>
			</tr>
			<?php for ($j=1;$j<=$filecount;$j++){?>
			<tr>
			  <td class="subcontent-td">
			  <?php 
				$downname=str_replace(".sql","_v".$j.".sql",$filename);
				echo "&nbsp;&nbsp;<a href=\"$data_path/$downname\">$downname</a>";
			  ?>
			  </td>
			</tr>
			<?php }?>
		  </table>
	</div>
	<br />
	<div class="bottombar-onebtn">
	  <input name="reback" class="btn" type="button" id="return" value="<?php echo $strReturn?>" onclick="location.href='db_restore.php'">
	</div>

  </div>
</form>
<?php  dofoot(); ?>

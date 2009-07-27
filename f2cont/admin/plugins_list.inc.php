<?php 
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//输出头部信息
dohead($title,"");
require('admin_menu.php');
?>

<form action="" method="post" name="seekform">
  <div id="content">

	<div class="contenttitle"><?php echo $title?></div>
	<br>
	<?php  if ($ActionMessage!="") { ?>
	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
	  <tr>
		<td>
		  <fieldset>
		  <legend>
		  <?php echo $strErrorInfo?>
		  </legend>
		  <div align="center">
			<table border="0" cellpadding="2" cellspacing="1">
			  <tr>
				<td><span class="alertinfo">
				  <?php echo $ActionMessage?>
				  </span></td>
			  </tr>
			</table>
		  </div>
		  </fieldset>
		  <br>
		</td>
	  </tr>
	</table>
	<?php  } ?>
	<div class="subcontent">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr class="subcontent-title">
		  <td width="10%" nowrap class="whitefont">
			<?php echo $strPluginName?>
		  </td>
		  <td width="7%" nowrap class="whitefont">
			<?php echo $strPluginVersion?>
		  </td>
		  <td width="56%" nowrap class="whitefont">
			<?php echo $strPluginDesc?>
		  </td>
		  <td width="7%" nowrap class="whitefont">
			<?php echo $strAuthor?>
		  </td>
		  <td width="7%" nowrap align="center" class="whitefont">
			<?php echo $strPluginSettingEdit?>
		  </td>
		  <td width="8%" nowrap align="center" class="whitefont">
			<?php echo $strPluginAction?>
		  </td>
		</tr>
		<?php 	foreach($plugins as $plugin_file => $plugin_data) { 
			$plugin=trim($plugin_data['Name']);
			$pfile=trim($plugin_data['Pfile']);

			if(strpos(",,".$actPlugins, ",$plugin,")>0) {
				$active="unActive";
				$class="subcontent-td2";
				if (file_exists(F2BLOG_ROOT."./plugins/$plugin/setting.php")) include_once(F2BLOG_ROOT."./plugins/$plugin/setting.php");
			} else {
				$active="active";
				$class="subcontent-td";
			}
		?>
		<tr class="subcontent-input" onMouseOver="this.style.backgroundColor='<?php echo $settingInfo['mouseovercolor']?>'" onMouseOut="this.style.backgroundColor=''">
		  <td nowrap class="<?php echo $class?>"><?php echo $plugin_data['Title']?></td>
		  <td nowrap class="<?php echo $class?>"><?php echo $plugin_data['Version']?></td>
		  <td class="<?php echo $class?>"><?php echo $plugin_data['Description']?></td>
		  <td nowrap class="<?php echo $class?>"><?php echo $plugin_data['Author']?></td>
		  <td nowrap class="<?php echo $class?>" align="center">
			<?php echo (function_exists($plugin."_setCode") and $active=="unActive")?"<a href='$PHP_SELF?action=set&plugin=$plugin'><img src='themes/{$settingInfo['adminstyle']}/icon_modif.gif' border='0' title='$strPluginSettingEdit' alt='$strPluginSettingEdit'></a>":"&nbsp;"?>
			<?php echo ($plugin_data['Advanced']!="" and $active=="unActive")?"&nbsp;<a href='$PHP_SELF?action=advset&plugin=$plugin'><img src='themes/{$settingInfo['adminstyle']}/icon_advanced.gif' border='0' title='$strPluginSettingAdvEdit' alt='$strPluginSettingAdvEdit'></a>":""?>
		  </td>
		  <td nowrap class="<?php echo $class?>" align="center">
			<a href='<?php echo "$PHP_SELF?action=save&operation=$active&plugin=$plugin&pfile=$pfile"?>'><?php echo ($active=="active")?$strActive:$strUnActive?></a>
		  </td>
		</tr>
		<?php }//end for?>
	  </table>
	</div>
  </div>
</form>
<?php  dofoot(); ?>

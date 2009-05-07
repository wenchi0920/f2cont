<?php
# 禁止直接访问该页面
if (!defined('IN_F2BLOG')) die ('Access Denied.');

//转换UBB标签
if (strpos(";".$logContent,"<!--hideBegin-->")>0) $logContent=preg_replace("/<!--hideBegin-->(.+?)<!--hideEnd-->/is","[hideBegin]\\1[hideEnd]",$logContent);
if (strpos(";".$logContent,"<!--fileBegin-->")>0) $logContent=preg_replace("/<!--fileBegin-->(.+?)<!--fileEnd-->/is","[fileBegin]\\1[fileEnd]",$logContent);
if (strpos(";".$logContent,"<!--flvBegin-->")>0) $logContent=preg_replace("/<!--flvBegin-->(.+?)<!--flvEnd-->/is","[flvBegin]\\1[flvEnd]",$logContent);
if (strpos(";".$logContent,"<!--musicBegin-->")>0) $logContent=preg_replace("/<!--musicBegin-->(.+?)<!--musicEnd-->/is","[musicBegin]\\1[musicEnd]",$logContent);
if (strpos(";".$logContent,"<!--mfileBegin-->")>0) $logContent=preg_replace("/<!--mfileBegin-->(.+?)<!--mfileEnd-->/is","[mfileBegin]\\1[mfileEnd]",$logContent);
if (strpos(";".$logContent,"<!--galleryBegin-->")>0) $logContent=preg_replace("/<!--galleryBegin-->(.+?)<!--galleryEnd-->/is","[galleryBegin]\\1[galleryEnd]",$logContent);
if (strpos(";".$logContent,"<!--more-->")>0) $logContent=str_replace("<!--more-->","[more]",$logContent);
if (strpos(";".$logContent,"<!--nextpage-->")>0) $logContent=str_replace("<!--nextpage-->","[nextpage]",$logContent);
if (strpos(";".$logContent,"../attachments")>0) $logContent=str_replace("../attachments",$settingInfo['blogUrl']."attachments",$logContent);
?>

<script type="text/javascript">
	function AddText(codetext) {
		var oEditor = FCKeditorAPI.GetInstance('logContent') ;
		if ( oEditor.EditMode == FCK_EDITMODE_WYSIWYG ) {
			oEditor.InsertHtml( codetext) ;
		} else {
			alert( 'You must be on WYSIWYG mode!' ) ;
		}
	}

	function onclick_update(form,act) {	
		if (act=="preview"){
			if (isNull(form.logTitle, '<?php echo $strErrNull?>')) return false;
			if (isNull(form.cateId, '<?php echo $strErrNull?>')) return false;
			if (isNull(FCKeditorAPI.GetInstance('logContent').GetXHTML(true), '<?php echo $strErrNull?>')) return false;
			form.target = "_blank";
			form.action = "preview.php";
			form.submit();
		}else if (act=="save"){
			if (isNull(form.logTitle, '<?php echo $strErrNull?>')) return false;
			if (isNull(form.cateId, '<?php echo $strErrNull?>')) return false;
			if (isNull(FCKeditorAPI.GetInstance('logContent').GetXHTML(true), '<?php echo $strErrNull?>')) return false;
			form.preview.disabled = true;
			form.save.disabled = true;
			form.reback.disabled = true;
			form.target = window.name;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=\""?>+act;
			form.submit();
		}else{
			form.target = window.name;
			form.action = "<?php echo "$edit_url&mark_id=$mark_id&action=\""?>+act;
			form.submit();
		}
	}		
</script>
<tr>
  <td colspan="4">
	<?php
	if ($settingInfo['language']=="en"){
		$editor_language="en";
	}elseif ($settingInfo['language']=="zh_tw"){
		$editor_language="zh";
	}else{
		$editor_language="zh-cn";
	}

	include("../editor/fckeditor/fckeditor.php") ;

	$sBasePath = "../editor/fckeditor/" ;

	$oFCKeditor = new FCKeditor('logContent') ;
	$oFCKeditor->Config['DefaultLanguage']		= $editor_language ;
	$oFCKeditor->BasePath	= $sBasePath ;
	$oFCKeditor->Value		= empty($logContent)?"":$logContent ;
	$oFCKeditor->Width		= '100%' ;

	$oFCKeditor->Create() ;
	?>
  </td>
</tr>
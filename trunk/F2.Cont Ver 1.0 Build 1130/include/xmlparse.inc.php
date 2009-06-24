<?php
/**
解析XML文件为数组，结果类似下
Array
(
    [item] => Array
        (
            [0] => Array
                (
                    [imgtext] => 测试1
                    [imgurl] => plugins/SiteFocus/1.jpg
                    [imglink] => http://www.f2blog.com
                )
            [1] => Array
                (
                    [imgtext] => 测试2
                    [imgurl] => plugins/SiteFocus/2.jpg
                    [imglink] => http://www.f2blog.com
                )
        )
)

可以通过来遍历数组内容
foreach($xml_array['item'] as $value){
	echo $value['imgtext']."<br>";
}
*/

function xmlArray($xml){
	if (!file_exists("$xml")){
		return array();
	}else{
		$xmldata=_readxmlfile($xml);
		$parser = xml_parser_create("utf-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $xmldata, $values);
		xml_parser_free($parser);
	   
	   // tracking used keys
	   $usedKeys = array();
	   $xml_array = array();
	   $deepLevel = -1;

	   // start a php array string (evaluated later)
	   $forEvalPrefix = '$xml_array';
	   
	   // loop throught the value array
	   foreach ($values as $key => $val) {
		   $tagName = $val['tag']; // pass the key tag into a more friendly looking variable
		   $level = $val['level']; // idem
		   if($level<2) continue;
		   if($val['type'] == 'open') {
			   $deepLevel++; // increase deep level
			   $forEvalPrefix .= '[\''. $tagName .'\']';
			   
			   // begin used keys checks to allow multidimensionatity under the same tag
			   (isset($usedKeys[$level][$tagName])) ? $usedKeys[$level][$tagName]++ : $usedKeys[$level][$tagName] = 0;
			   $forEvalPrefix .= '['. $usedKeys[$level][$tagName] .']';
		   } 
		   if($val['type'] == 'complete') {
			   ($level > $deepLevel) ? $deepLevel++ : ''; // increase $deepLevel only if current level is bigger
			   $tagValue = (!empty($val['value']))?addslashes($val['value']):""; // format the value for evaluation as a string
			   $forEvalSuffix = '[\''. $tagName .'\'] = \''. $tagValue .'\';'; // create a string to append to the current prefix
			   $forEval = $forEvalPrefix . $forEvalSuffix; // (without "$php_used_prefix"...)
			   eval($forEval); // write the string to the array structure
		   }
		   if($val['type'] == 'close') {
			   unset($usedKeys[$deepLevel]); // Suppress tagname's keys useless
			   $deepLevel--;
			   $forEvalPrefix = substr($forEvalPrefix, 0, strrpos($forEvalPrefix, '[')); // cut off the used keys node
			   $forEvalPrefix = substr($forEvalPrefix, 0, strrpos($forEvalPrefix, '[')); // cut off the end level of the array string prefix
		   }
	   }
	   return $xml_array;
   }
}

//阅读XML文件
function _readxmlfile($xml_name) {
	if (file_exists($xml_name)) {
		if (PHP_VERSION >= "4.3.0" && function_exists('file_get_contents')) {
			return file_get_contents($xml_name);
		} else {
			$filenum=fopen($xml_name,"rb");
			$sizeofit=filesize($xml_name);
			if ($sizeofit<=0) return '';
			flock($filenum,LOCK_EX);
			$file_data=fread($filenum, $sizeofit);
			fclose($filenum);
			return $file_data;
		}
	} else {
		return '';
	}
}
?>
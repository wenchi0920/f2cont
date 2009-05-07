<?php
@error_reporting(E_ERROR | E_WARNING | E_PARSE);

header("Content-Type: text/xml");	
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<querys>\n";

if (file_exists('sentence.xml')) {
	if (function_exists(simplexml_load_file)){
		$xml = simplexml_load_file('sentence.xml');
		
		$random=rand(1,3748);
		$english=$xml->sentence[$random]->English;
		$chinese=$xml->sentence[$random]->Chinese;
	}else{
		//including khalid xml parser
		include_once "../../include/kxparse.php";
		//create the object
		$xmlnav=new kxparse("sentence.xml");

		$random=rand(1,3748);
		$english=$xmlnav->get_tag_text("root:sentence:English","1:".$random.":1");
		$chinese=$xmlnav->get_tag_text("root:sentence:Chinese","1:".$random.":1");
	}

	echo "<English><![CDATA[$english]]></English><Chinese><![CDATA[$chinese]]></Chinese>\n";
} else {
    exit('Failed to open sentence.xml.');
}

echo "</querys>\n";
?> 

<?php
	/**
	 * 巴巴变 xml-rpc php 版
	 * 作者：Korsen Zhang
	 * 日期：2006-08-14
	 * 网址：http://korsen.f2blog.com
	 * 说明：
	 * １、在原有“风中独火”的基础，进行重新封装，并且保留了他原来所有的调用方法，所以过去使用BabaBian.class包的，可以直接覆盖使用。
	 * ２、本包封装了巴巴变截止到2006-08-12公布的所有API接口。
	 * ３、具体使用方法，请参考本文最后面的测试代码，有些返回值是数组，有些是单个值，请注意参考。
	 * ４、对XML的解析是用xml_parse_into_struct把XML转入到数组，然后再解析出相应数据，所以该解析方法具有专有性，仅对巴巴变有效。
	 * ５、该类可以支持php4，php５，在php５以后版本对xml有更为高效的解析方法，但是为了向下兼容，故采用直接解析方法，因为数据量并不
	 *     很大，所以直接解析对速度影响不大。
	 * ６、如果您对该类进行了修改，或发现有什么错误，请联系我(korsenzhang@yahoo.com.cn)，谢谢。
	 */
	
	//类开始
	class BabaBian
	{
		
		//变量定义
		var $host="www.bababian.com";
		var $port="80";
		var $server="/xmlrpc";
		var $output_options="utf-8";

		//公菜变量
		var $api_key="F5AF66122B693DADF77D9D94AC4E97FEAK";
		
		//错误信息
		var $fault=array();
		
		//返回数据
		var $GetXML="";						//返回原始XML数据		
		var $val_url="";						//验证码
		var $user_key_id=array();			//user key && user id
		var $upload_Session_info=array();	//上传图片时取得session值
		var $photo_list=array();				//个人照片列表
		var $photo_info=array();				//照片信息
		var $photo_info_reply=array();		//某照片评论信息
		var $photo_info_keyword=array();		//某照片关键字
		var $photo_recommend=array();		//最新的精彩照片
		var $set_list=array();				//个人专辑列表
		var $setphoto_list_info=array();		//某专辑信息
		var $setphoto_list=array();			//某专辑照片列表
		var $usertag_list=array();			//个人关键字列表

		//分页输出时
		var $photo_total;					//照片总数，专辑总数
		var $page_total;						//总页数
		var $page_cur;						//当前页数
		var $reply_total;					//评论总数

		//公共方法开始=================================================================================================
		function __do_remote_call($request) //调用远程过程
		{
			$content="";
			$this->fault=array();//清空错误
			$fp=@fsockopen($this->host,$this->port,$errno,$errstr,30);
			if (!$fp) {
				$this->fault[]=$errstr;
				$this->fault[]=$errno;
				return false;
			} else {
				$out = "POST ".$this->server." / HTTP/1.1\r\n";
				$out .= "User_Agent:XML-RPC\r\n";
				$out .= "Host: ".$this->host."\r\n";
				$out .= "Content-Type:text/xml\r\n";
				$out .= "Content-Length:".strlen($request)."\n\n".$request."\n";
				if(!fwrite($fp, $out)){
					$this->fault[]="Connect to www.bababian.com Error!";
					$this->fault[]="50000";
					return false;
				}
				while (!feof($fp)) {
					$content.=fgets($fp, 128);
				}
				fclose($fp);
			}
			return $content;
		}

		function _get_remote_xml($method,$params) //返回xml
		{
			$request="<?xml version=\"1.0\" encoding=\"".$this->output_options."\"?> \n";
			$request.="	<methodCall> \n";
			$request.="	  <methodName>$method</methodName>\n";
			$request.="	  <params>\n";
			for ($i=0;$i<count($params);$i++){
				$request.="		<param>\n";
				$request.="		  <value><string>".$params[$i]."</string></value>\n";
				$request.="		</param>\n";
			}
			$request.="	  </params>\n";
			$request.="	</methodCall>\n";

			//向巴巴变发送请求
			$this->fault=array();//清空错误
			$response=$this->__do_remote_call($request);
			if ($response!=""){
				$xml=$this->_getXML($response);
			}else{
				$this->fault[]="Connect to www.bababian.com Error!";
				$this->fault[]="50000";
				return false;
			}

			return $xml;
		}

		function _IsFalut($xml)
		{	
			$this->fault=array();//清空错误
			$falut=false;
			if ($xml!=""){
				if (preg_match("/<name>faultString<\/name>/i",$xml)){
					$arr_result=$this->_xmlToArray($xml,3);
					$arrerror=$arr_result[0];
					foreach($arrerror as $key=>$value){
						$this->fault[]=$value;
						$this->fault[$key]=$value;
					}
					$falut=true;
					//print_r($this->fault);
				}
			}

			return $falut;
		}

		function _getXML($response)//拆分xml文件
		{
			//echo $response;
			$split="Content-Type: text/xml;charset=utf-8";
			$xml=explode($split,$response);
			$xml=trim($xml[1]);
			$xml=str_replace("&#32;"," ",$xml);	//处理日期时，日期与时间间的空格去掉了，故需要转换。
			return $xml;
		}
		
		//拆分xml文件为数组
		function _xmlToArray($xmldata,$parserlevel="7",$parserfield="value")
		{
			//echo $xmldata;
			//分析巴巴变数据
			$xmldata=str_replace("<int>","<string>",$xmldata);
			$xmldata=str_replace("</int>","</string>",$xmldata);
			$parser = xml_parser_create("utf-8");
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $xmldata, $values, $tags);
			xml_parser_free($parser);

			$arr_name=!empty($tags['name'])?$tags['name']:array();
			$arr_value=!empty($tags['string'])?$tags['string']:array();
			$last_key="";

			$i=0;
			$bbb_data=array();
			//列数据，找到相应的value
			foreach ($values as $key=>$value){
				if ($value['tag']==$parserfield && $value['level']==$parserlevel && $value['type']=="close"){
					if (count($arr_name)<1){$keyname=0;}
					
					foreach ($arr_value as $key1=>$value1){
						//echo "当前值：".$value1."，当前键值".$key."，上一键值".$last_key ."<br>";

						if ($value1<$key && $value1>$last_key){
							if (count($arr_name)>1){
								$keyname=empty($values[$arr_name[$key1]]['value'])?"":$values[$arr_name[$key1]]['value'];
							}
							$keyvalue=empty($values[$arr_value[$key1]]['value'])?"":$values[$arr_value[$key1]]['value'];
							
							$bbb_data[$i][$keyname]=$keyvalue;
							$last_key=$value1;
							if (count($arr_name)<1){$keyname++;}
						}
					}
					$i++;
				}
			}
			//print_r($bbb_data);
			return $bbb_data;
		}
		//公共方法结束==================================================================


		/*==============================================================================
		用户登录
		================================================================================*/
		function userbind($email,$password)
		{
			$this->GetXML="";//清空返回的XML
			$this->user_key_id=array();//清空可能存在的登录信息
			
			$method="bababian.user.bind";
			$params[]=$this->api_key;
			$params[]=$email;
			$params[]=$password;

			if ($xml=$this->_get_remote_xml($method,$params)){
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetUserKeyID($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetUserKeyID($xml)
		{
			$arr_result=$this->_xmlToArray($xml,4);
			$this->user_key_id=$arr_result[0];
		}

		/*==============================================================================
		用户登录结束
		================================================================================*/
		
		/*==============================================================================
		用户注册
		================================================================================*/
		function userregister($email,$screenname,$password,$val_num)
		{
			$this->GetXML="";//清空返回的XML
			$this->user_key_id=array();//清空可能存在的注册后信息
			
			$method="bababian.user.register";
			$params[]=$this->api_key;
			$params[]=$email;
			$params[]=$screenname;
			$params[]=$password;
			$params[]=$val_num;

			if ($xml=$this->_get_remote_xml($method,$params)){			
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetUserKeyID($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}
		/*==============================================================================
		用户注册结束
		================================================================================*/


		/*==============================================================================
		获取验证码
		================================================================================*/
		function uservalidate()
		{
			$this->GetXML="";//清空返回的XML
			$this->val_url="";//清空验证码url
			
			$method="bababian.user.validate";
			$params[]=$this->api_key;
			
			if ($xml=$this->_get_remote_xml($method,$params)){		
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetValidateUrl($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetValidateUrl($xml)
		{
			$arr_result=$this->_xmlToArray($xml,4);
			$this->val_url=$arr_result[0];
		}
		/*==============================================================================
		获取验证码结束
		================================================================================*/

		/*==============================================================================
		申请上传会话
		================================================================================*/
		function photouploadSession($user_key)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.photo.uploadSession";
			$params[]=$this->api_key;
			$params[]=$user_key;
			
			if ($xml=$this->_get_remote_xml($method,$params)){			
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetuploadSession($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetuploadSession($xml)
		{
			$arr_result=$this->_xmlToArray($xml,4);
			$this->upload_Session_info=$arr_result[0];
		}
		/*==============================================================================
		申请上传会话结束
		================================================================================*/

		/*==============================================================================
		获取照片列表
		================================================================================*/
		function photogetPhotoList($user_key,$page_cur,$page_size,$photo_size)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.photo.getPhotoList";
			$params[]=$this->api_key;
			$params[]=$user_key;
			$params[]=$page_cur;
			$params[]=$page_size;
			$params[]=$photo_size;
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetphotoList($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetphotoList($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){				
				if ($key==0){
					$this->photo_total=$value['photo_total'];
					$this->page_total=$value['page_total'];
					$this->page_cur=$value['page_cur'];
				}else{
					$return_result[]=$value;
				}
			}
			$this->photo_list=$return_result;
		}
		/*==============================================================================
		获取照片列表结束
		================================================================================*/

		/*==============================================================================
		获取巴巴变最新的精彩照片
		================================================================================*/
		function photogetRecommendPhoto($page_cur,$page_size,$photo_size)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.photo.getRecommendPhoto";
			$params[]=$this->api_key;
			$params[]=$page_cur;
			$params[]=$page_size;
			$params[]=$photo_size;
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetgetRecommendPhoto($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetgetRecommendPhoto($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){				
				if ($key==0){
					$this->photo_total=$value['photo_total'];
					$this->page_total=$value['page_total'];
					$this->page_cur=$value['page_cur'];
				}else{
					$return_result[]=$value;
				}
			}
			$this->photo_recommend=$return_result;
		}
		/*==============================================================================
		获取巴巴变最新的精彩照片结束
		================================================================================*/

		/*==============================================================================
		获取一张照片的详细信息，包括照片，标题，描述，关键字，评论，Exif信息等
		================================================================================*/
		function photogetPhotoInfo($did,$page_cur,$page_size)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.photo.getPhotoInfo";
			$params[]=$this->api_key;
			$params[]=$did;
			$params[]=$page_cur;
			$params[]=$page_size;			
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetgetPhotoInfo($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetgetPhotoInfo($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){
				//取得照片信息
				if ($key==0){
					//转换exif信息为数组
					$value['exif']=$this->_exifToArray($value['exif']);

					$this->photo_info=$value;
				}else{
					//取得关键字
					if ($key==1){
						if ($value['tag1']!="") {
							$this->photo_info_keyword=$value;
						}else{
							$this->reply_total=$value['photo_total'];
							$this->page_total=$value['page_total'];
							$this->page_cur=$value['page_cur'];
						}
					}else{
						//取得评论统计信息
						if ($key==2 && $value['photo_total']>0){
							$this->reply_total=$value['photo_total'];
							$this->page_total=$value['page_total'];
							$this->page_cur=$value['page_cur'];
						}else{
							$this->photo_info_reply[]=$value;
						}
					}					
				}
			}
		}

		//拆分照片Exif信息为数组
		function _exifToArray($xmldata)
		{
			//echo $xmldata;
			$bbb_exif=array();
			if ($xmldata!=""){
				$parser = xml_parser_create("utf-8");
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
				xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
				xml_parse_into_struct($parser, $xmldata, $values, $tags);
				xml_parser_free($parser);
					
				//列数据
				foreach ($values as $key=>$value){
					if ($value['type']=="complete"){
						//转换日期的冒号:为-号。
						if (in_array($value['tag'],array("dateAndTime","dateAndTimeOriginal","dateAndTimeDigitized"))){
							$value['value']=$this->_covert_time($value['value']);
						}
						$bbb_exif[$value['tag']]=$value['value'];
					}
				}
			}
			return $bbb_exif;
		}

		//转换时间
		function _covert_time($datetime){
			if ($datetime!=""){
				list($date,$time)=explode(" ",$datetime);
				$date=str_replace(":","-",$date);
				return "$date $time";
			}else{
				return "";
			}
		}
		/*==============================================================================
		获取一张照片的详细信息结束
		================================================================================*/

		/*==============================================================================
		取得一个用户专辑列表
		================================================================================*/
		function photogetSetList($user_id,$page_cur,$page_size)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.set.getSetList";
			$params[]=$this->api_key;
			$params[]=$user_id;
			$params[]=$page_cur;
			$params[]=$page_size;			
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetgetSetList($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetgetSetList($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){				
				if ($key==0){
					$this->photo_total=$value['photo_total'];
					$this->page_total=$value['page_total'];
					$this->page_cur=$value['page_cur'];
				}else{
					$return_result[]=$value;
				}
			}
			$this->set_list=$return_result;
		}
		/*==============================================================================
		取得一个用户专辑列表结束
		================================================================================*/

		/*==============================================================================
		取得一个专辑的所有公开照片的列表
		================================================================================*/
		function photogetSetPhotoList($did,$page_cur,$page_size)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.set.getSetPhotoList";
			$params[]=$this->api_key;
			$params[]=$did;
			$params[]=$page_cur;
			$params[]=$page_size;			
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetgetSetPhotoList($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetgetSetPhotoList($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){			
				if ($key==0){
					$this->setphoto_list_info=$value;
				}else if ($key==1){
					$this->photo_total=$value['photo_total'];
					$this->page_total=$value['page_total'];
					$this->page_cur=$value['page_cur'];
				}else{
					$return_result[]=$value;
				}
			}
			$this->setphoto_list=$return_result;
		}
		/*==============================================================================
		取得一个专辑的所有公开照片的列表结束
		================================================================================*/

		/*==============================================================================
		取得一个用户的关键字和关键字对应的照片数
		================================================================================*/
		function photogetUserTagList($user_id)
		{
			$this->GetXML="";//清空返回的XML
			
			$method="bababian.tag.getUserTagList";
			$params[]=$this->api_key;
			$params[]=$user_id;
			
			if ($xml=$this->_get_remote_xml($method,$params)){	
				if ($this->_IsFalut($xml)){
					return false;
				}else{
					$this->_GetgetUserTagList($xml);
					$this->GetXML=$xml;
					return true;
				}
			}
		}

		function _GetgetUserTagList($xml)
		{
			$arr_result=$this->_xmlToArray($xml,7);
			foreach ($arr_result as $key=>$value){				
				$return_result[]=$value;
			}
			$this->usertag_list=$return_result;
		}
		/*==============================================================================
		取得一个用户的关键字和关键字对应的照片数结束
		================================================================================*/
	}

	//主类结束!

	//测试样例，以后的代码可以删除，仅仅供参考。
	//声明一个类的实例以后，api key一定要传入。以后就可以直接调用函数进行相应的操作。
	//$GoBind=new BabaBian();
	//$GoBind->api_key="";

	/*
	 * 取得user key
	 * 传入参数分别为email:登入巴巴变的邮箱，password:登入巴巴变的密码
	 */
	/*if($GoBind->userbind("your bababian email","password")) {
		list($user_key, $user_id)=$GoBind->user_key_id;
		echo "User Key:".$user_key."<br>";
		echo "User ID:".$user_id."<br>";
	}else{
		echo("取得User Key失败!<hr />");
		echo("错误代号: ".$GoBind->fault[1]."<br>");
		echo("错误说明: ".$GoBind->fault[0]."<br />");
	}*/

	/*
	 * 取得用户图片列表
	 * 传入参数分别为user_key:可用userbind函数取得，page_cur:当前页，page_size:每页显示照片数，photo_size:所要显示图片大小75,100,240
	 * 取得内容有两种取法，另一种参见获得最新的精彩照片
	 */
	/*if($GoBind->photogetPhotoList($user_key,"1","10","75")) {
		echo ("总的照片数量：".$GoBind->photo_total."<br />");
		echo ("总页数：".$GoBind->page_total."<br />");
		echo ("当前页数：".$GoBind->page_cur."<br />");

		echo ("当前显示数量：".count($GoBind->photo_list)."<hr />");
		foreach($GoBind->photo_list as $key=>$value){
			echo ("did is:".$value["did"]."<br />");
			echo ("date is:".$value["date"]."<br />");
			echo ("title is:".$value["title"]."<br />");
			echo ("src is:<img src='".$value["src"]."'><hr />");
		}
	}else{		
		echo("获取用户图片列表!<hr />");
		echo("错误代号: ".$GoBind->fault[1]."<br>");
		echo("错误说明: ".$GoBind->fault[0]."<br />");		
	}*/

	/*
	 * 获得最新的精彩照片
	 * 传入参数分别page_cur:当前页，page_size:每页显示照片数，photo_size:所要显示图片大小75,100,240
	 */
	/*if($GoBind->photogetRecommendPhoto("1","10","75")) {
		echo ("总的照片数量：".$GoBind->photo_total."<br />");
		echo ("总页数：".$GoBind->page_total."<br />");
		echo ("当前页数：".$GoBind->page_cur."<br />");

		echo ("当前显示数量：".count($GoBind->photo_recommend)."<hr />");
		foreach($GoBind->photo_recommend as $key=>$value){
			foreach($value as $subkey=>$subvalue){
				if (preg_match("/http:\/\//i",$subvalue)){//图片
					echo ("$subkey is: <img src='".$subvalue."'><br />");
				}else{
					echo ("$subkey is: ".$subvalue."<br />");
				}
			}
		}
	}else{		
		echo("获取最新的精彩照片!<hr />");
		echo("错误代号: ".$GoBind->fault[1]."<br>");
		echo("错误说明: ".$GoBind->fault[0]."<br />");		
	}*/

	/*
	 * 获取一张照片的详细信息，包括照片，标题，描述，关键字，评论，Exif信息等
	 * 传入参数分别为did:照片的ID,page_cur:当前要显示的评论页数,page_size:分页时，每页显示的评论数（目前每页显示1-100个评论）
	 * 测试图片，有评论：AF84A4FA341D014B2935A88F69B842EADT，有exif：AF84A4FA341D014B2935A88F69B842EADT
	 * 照片exif信息，返回来的是数组
	 */
	/*if($GoBind->photogetPhotoInfo("AF84A4FA341D014B2935A88F69B842EADT","1","10")) {
		//取得照片信息
		echo "图片信息：<hr>";
		echo "发表日期：".$GoBind->photo_info[date]."<br>";
		echo "Exif（数组）：";
		print_r($GoBind->photo_info[exif]);
		echo "<br>";
		echo "照片描述description：".$GoBind->photo_info[description]."<br>";
		echo "图片ID did：".$GoBind->photo_info[did]."<br>";
		echo "标题title：".$GoBind->photo_info[title]."<br>";
		echo "图片路径src：".$GoBind->photo_info[src]."<br>";

		//取得关键字
		echo "<br><br>关键字：<hr>";
		foreach($GoBind->photo_info_keyword as $key=>$value){
			echo ("$key is: ".$value."<br />");
		}

		//取得评论信息
		echo "<br><br>评论信息：<hr>";
		echo ("总的评论数量：".$GoBind->reply_total."<br />");
		echo ("总页数：".$GoBind->page_total."<br />");
		echo ("当前页数：".$GoBind->page_cur."<br />");
		$i=0;
		foreach($GoBind->photo_info_reply as $key=>$value){
			$i++;
			echo "第 $i 条评论信息<br>";
			foreach($value as $subkey=>$subvalue){
				echo ("$subkey is: ".$subvalue."<br />");
			}			
		}
	}else{		
		echo("获取一张照片的详细信息!<hr />");
		echo("错误代号: ".$GoBind->fault[1]."<br>");
		echo("错误说明: ".$GoBind->fault[0]."<br />");	
	}*/

	/*
	 * 取得一个用户专辑列表
	 * 传入参数分别为userid:用户id,page_cur:当前要显示的页,page_size:分页显示时，每页显示的照片数（目前每页显示1-100张照片）
	 */
	/*if($GoBind->photogetSetList($user_id,"1","10")) {
		foreach($GoBind->set_list as $key=>$value){
			foreach($value as $subkey=>$subvalue){
				if (preg_match("/http:\/\//i",$subvalue)){//图片
					echo ("$subkey is: <img src='".$subvalue."'><br />");
				}else{
					echo ("$subkey is: ".$subvalue."<br />");
				}
			}
		}
	}else{	
		//显示错误相关信息			
		echo("获取一个用户专辑列表!<hr />");
		echo("错误代号: ".$GoBind->fault[1]."<br>");
		echo("错误说明: ".$GoBind->fault[0]."<br />");
	}*/

	/*
	 * 取得一个专辑所有公开照片的列表
	 * 传入参数分别为setid:专辑id,page_cur:当前要显示的页,page_size:分页显示时，每页显示的照片数（目前每页显示1-100张照片）
	 * 测试专辑id：3EC67F9B05341EC65200A7E63D191918DS
	 */
	/*if($GoBind->photogetSetPhotoList("3EC67F9B05341EC65200A7E63D191918DS","1","10")) {
		foreach($GoBind->setphoto_list as $key=>$value){
			foreach($value as $subkey=>$subvalue){
				if (preg_match("/http:\/\//i",$subvalue)){//图片
					echo ("$subkey is: <img src='".$subvalue."'><br />");
				}else{
					echo ("$subkey is: ".$subvalue."<br />");
				}
			}
		}
	}else{		
		echo("获取一个用户专辑列表!<hr />");
		echo($GoBind->fault[0]."<br />");
		echo($GoBind->fault[1]."<br />"); //显示错误相关信息		
	}*/
	
	/*
	 * 取得一个用户的关键字和关键字对应的照片数
	 * 传入参数分别为user id
	 */
	/*if($GoBind->photogetUserTagList($user_id)) {
		foreach($GoBind->usertag_list as $key=>$value){
			foreach($value as $subkey=>$subvalue){
				echo ("$subkey is: ".$subvalue."<br />");
			}
		}
	}else{		
		echo("取得一个用户的关键字和关键字对应的照片数!<hr />");
		echo($GoBind->fault[0]."<br />");
		echo($GoBind->fault[1]."<br />"); //显示错误相关信息		
	}*/

	/*
	 * 上传图片，先要取得session id，HTML代码参考如下：以下已经是最精简的代码了。
	 * //以下代码为上载rar图片包
	 * <FORM id=photoform name=photoform action=http://www.bababian.com/upload method=post encType=multipart/form-data>
	 *  <INPUT type=hidden value=pho name=file_type>
     *  <INPUT type=hidden value=$bbb_session_key name=session_key>
     *  <INPUT type=file size=30 name=File1>
     *  <INPUT type=submit value=提交 name=Submit class="btn">
	 *	</FORM>
	 * //以下代码为上载rar图片包
	 * <FORM id=fileform name=fileform action=http://www.bababian.com/upload method=post encType=multipart/form-data>
	 *	<INPUT type=hidden value=zip name=file_type>
     *  <INPUT type=hidden value=$bbb_session_key name=session_key>
	 *  <INPUT type=file size=30 name=File>
     *  <INPUT id=Submit2 type=submit value=提交 name=Submit2 class="btn">
     * </FORM>
	 * 取session key的调用方法。
	 */
	/*if($GoBind->photouploadSession($bbb_user_key)) {
		//$bbb_session_key：为session key,$bbb_cap_total为你的每月最大使用量,$bbb_cap_used为你的已用空间。
		list($bbb_session_key,$bbb_cap_total,$bbb_cap_used)=$GoBind->upload_Session_info;
	}else{		
		echo("获取session key<hr />");
		echo($GoBind->fault[0]."<br />");
		echo($GoBind->fault[1]."<br />"); //显示错误相关信息		
	}*/
?>
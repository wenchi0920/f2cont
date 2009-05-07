<?php
/*
	F2blog (http://www.f2blog.com)

	Date:2006.10.26
	Author:Harry
*/

class F2MysqlClass {
	var $querycount = 0;

	function F2MysqlClass($DBHost, $DBUser, $DBPswd, $DBName,$DBNewlink="false") {
		if($DBNewlink=="true") {
			if(!mysql_pconnect($DBHost, $DBUser, $DBPswd)) {
				$this->halt("Don't connect to database!");
			}
		} else {
			if(!mysql_connect($DBHost, $DBUser, $DBPswd)) {
				$this->halt("Don't connect to database!");
			}
		}

		if($this->getServerInfo() > '4.1') {
			mysql_query("SET NAMES 'utf8'");
		}

		if($this->getServerInfo() > '5.0.1') {
			mysql_query("SET sql_mode=''");
		}
		
		if($DBName) {
			$this->selectDB($DBName);
		}
	}

	function fetchArray($query, $resultType = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $resultType);
	}

	function fetchQueryAll($query,$resultType = MYSQL_BOTH){
		$rows=array();
		while($row=mysql_fetch_array($query, $resultType)){
			array_push($rows,$row);
		}
		return $rows;
	} 

	function fetchOneArray($sql) {
		$result = $this->query($sql);
		$record = $this->fetchArray($result);
		return $record;
	}

    function fetchAssoc($query){
        return mysql_fetch_assoc($query);
    }
	
	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql)) && $type != 'T') { //如果type=T，表示有错误也继续运行。
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querycount++;
		//echo "<font color=red>".$this->querycount."</font>$sql<br>";
		return $query;
	}
	
	function unQuery($sql) {
		return $this->query($sql, 'UNBUFFERED');
	}

	function selectDB($DBName) {
		return mysql_select_db($DBName);
	}

	function listTable($DBName) {
		return mysql_list_tables($DBName);
	}

	function numRows($query) {
		return mysql_num_rows($query);
	}

	function numFields($query) {
		return mysql_num_fields($query);
	}
	
	function free_result($query) {
		return mysql_free_result($query);
	}

	function getServerInfo() {
		return mysql_get_server_info();
	}

	function close() {
		return mysql_close();
	}

	function error() {
		return mysql_error();
	}

	function errno() {
		return intval(mysql_errno());
	}

	function insertId() {
		return mysql_insert_id();
	}

	function affectedRows(){
		return mysql_affected_rows();
	}

	function halt($msg,$sql=''){
		global $settingInfo;
		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
		$message .= "<STYLE TYPE=\"text/css\">\n";
		$message .=  "body,td,p,pre {\n";
		$message .=  "font-family : Verdana, sans-serif;font-size : 12px;\n";
		$message .=  "}\n";
		$message .=  "</STYLE>\n";
		$message .= "</head>\n";
		$message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
		$message .= "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
		$message .= "<b>Mysql error description</b>: ".$this->error()."\n<br />";
		$message .= "<b>Mysql error number</b>: ".$this->errno()."\n<br />";
		$message .= "<b>Date</b>: ".gmdate("Y-m-d H:i",time()+3600*$settingInfo['timezone'])."\n<br />";
		$message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].$_SERVER["SCRIPT_NAME"]."\n<br />";
		if ($sql!=""){
			$message .= "<b>SQL code</b>: ".htmlspecialchars($sql)."\n<br />";
		}
		$message .= "</body>\n</html>";
		echo $message;
		exit;
	}
}
?>
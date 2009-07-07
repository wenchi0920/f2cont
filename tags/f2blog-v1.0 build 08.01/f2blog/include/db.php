<?php
/**
   Author : Dummy
   Revised History: 2006/05/26 Joesen
*/

class DummyMySQLClass{
    var $DBHost = ''; /* 数据库主机名称 */
    var $DBUser = ''; /* 数据库用户名称 */
    var $DBPswd = ''; /* 数据库密码     */
    var $DBName = ''; /* 数据库名称     */

    var $Result = NULL; /* private */
    var $LinkId = NULL; /* private */
    var $Record = NULL; /* 包含一条记录的内容 */
    var $Rows   = 0;

    var $MustBeHalt = true; /* 有了错误立即停止 */
    var $IsTest     = true; /* 是否是测试       */
    var $RecordCase = NULL; /* 只在 nextRecord() 和 f()方法里出现 */

	var $querycount = 0; /* 统计查询次数 */

    function DummyMySQLClass($DBHost = '', $DBUser = '', $DBPswd = '', $DBName = '', $newLink = false){
        if(is_array($DBHost)){
            $DBHost  = $DBHost[0];
            $DBUser  = $DBHost[1];
            $DBPswd  = $DBHost[2];
            $DBName  = $DBHost[3];
            $newLink = empty($DBHost[4])?false:$DBHost[4];
        }elseif(str_replace(array("/", "@"), array("", ""), $DBHost) != $DBHost){
            $dsn = $this->splitDSN($DBHost);

            $DBTpye  = $dsn[0];
            $DBHost  = $dsn[1].":".$dsn[2];
            $DBUser  = $dsn[3];
            $DBPswd  = $dsn[4];
            $DBName  = $dsn[5];
            $newLink  = $dsn[6] == ''?$newLink:$dsn[6];
        }
        $this->DBHost = empty($DBHost)?$this->DBHost:$DBHost;
        $this->DBUser = empty($DBUser)?$this->DBUser:$DBUser;
        $this->DBPswd = empty($DBPswd)?$this->DBPswd:$DBPswd;
        $this->DBName = empty($DBName)?$this->DBName:$DBName;

        if(!empty($DBHost)){
            $this->connect($this->DBHost, $this->DBUser, $this->DBPswd, $newLink);
        }
        if(!empty($DBName)){
            $this->selectDB($DBName);
        }
    }

    function splitDSN($dsn){
        //$dsn = "数据库类型名称://入口帐号:入口密码@数据库主机名:端口号/数据库名/是否总是打开新的连接";
        //$dsn = "MySQL://Dummy:123@localhost:3306/tetx/false";
        $dsn = preg_split("/[:/@]/", $dsn);

        $DBTpye  = '';
        $DBHost  = '';
        $DBPort  = '';
        $DBUser  = '';
        $DBPswd  = '';
        $DBName  = '';
        $DBNewLink  = false;

        $DBTpye  = $dsn[0];
        $DBHost  = $dsn[5];
        $DBPort  = $dsn[6];
        $DBUser  = $dsn[3];
        $DBPswd  = $dsn[4];
        $DBName  = $dsn[7];
        $DBNewLink  = $dsn[8];

        return array($DBTpye, $DBHost, $DBPort, $DBUser, $DBPswd, $DBName, $DBNewLink);
    }

    function affectedRows(){ /* 取得前一次 MySQL 操作所影响的记录行数 */
        return mysql_affected_rows($this->LinkId);
    }

    function changeUser($user, $password){ /* 改变活动连接中登录的用户 */
        return mysql_change_user($user, $password, $this->DBName, $this->LinkId);
    }

    function clientEncoding(){ /* 返回字符集的名称 */
        return mysql_client_encoding($this->LinkId);
    }

    function close(){ /* 关闭 MySQL 连接 */
        $close = mysql_close($this->LinkId);
        $this->LinkId = NULL;
        $this->Result = NULL;
        $this->Record = NULL;
        return $close;
    }

    function connect($DBHost = '', $DBUser = '', $DBPswd = '', $newLink = false){//, int client_flags){ /* 打开一个到 MySQL 服务器的连接 */
        $connect = @mysql_connect(empty($DBHost)?$this->DBHost:$DBHost, empty($DBUser)?$this->DBUser:$DBUser, empty($DBPswd)?$this->DBPswd:$DBPswd, $newLink);
        if(!is_resource($connect)){
            $this->halt("连接数据库失败！<BR/>", 1);
            return false;
        }
		@mysql_query("set names 'utf8'");;
        $this->LinkId = $connect;
        return true;
    }

    function createDB($DBName){ /* 新建一个 MySQL 数据库 */
        return @mysql_create_db($DBName, $this->LinkId) or die($this->halt("创建数据库 ".$DBName." 失败！"));
    }

    function dataSeek($rowNumber){ /* 移动内部结果的指针 */
        return mysql_data_seek($this->Result, $rowNumber);
    }

    function dbName($row, $field = NULL){ /* 取得结果数据 */
        if(empty($field)){
            return mysql_db_name($this->Result, $row);
        }
        return mysql_db_name($this->Result, $row, $field);
    }

    function dbQuery($DBName, $queryString){ /* 发送一条 MySQL 查询 */
        $this->Result = mysql_db_query($DBName, $queryString, $this->LinkId);
        return $this->Result?true:false;
    }

    function dropDB($DBName){ /* 丢弃（删除）一个 MySQL 数据库 */
        return mysql_drop_db($DBName, $this->LinkId);
    }

    function errno(){ /* 返回上一个 MySQL 操作中的错误信息的数字编码 */
        return mysql_errno($this->LinkId);
    }

    function error(){ /* 返回上一个 MySQL 操作产生的文本错误信息 */
        return mysql_error($this->LinkId);
    }

    function escapeString($unescapedString){ /* 转义一个字符串用于 mysql_query */
        return mysql_escape_string($unescapedString);
    }

    function fetchArray($Rows = 0, $resultType = MYSQL_BOTH){ /* 从结果集中取得一行作为关联数组，或数字数组，或二者兼有 */
        if(!is_resource($this->Result)){
            return false;
        }
        $fetchArray = mysql_fetch_array($this->Result, $resultType);
        if($fetchArray && $Rows){$this->Rows++;}
        return $fetchArray;
    }

	function fetchQueryAll($Rows = 0,$resultType = MYSQL_BOTH){
		if(!is_resource($this->Result)){
            return false;
        }
		$rows=array();
		while($row=mysql_fetch_array($this->Result, $resultType)){
			array_push($rows,$row);
		}
		return $rows;
	}

    function fetchAssoc($Rows = 0){ /* 从结果集中取得一行作为关联数组 */
        if(!is_resource($this->Result)){
            return false;
        }
        $fetchAssoc = mysql_fetch_assoc($this->Result);
        if($fetchAssoc && $Rows){$this->Rows++;}
        return $fetchAssoc;
    }

    function fetchField($fieldOffset = NULL){ /* 从结果集中取得列信息并作为对象返回 */
        if(empty($fieldOffset)){
            return mysql_fetch_field($this->Result, $fieldOffset);
        }
        return mysql_fetch_field($this->Result);
    }

    function fetchLengths(){ /* 取得结果集中每个输出的长度 */
        return @mysql_fetch_lengths($this->Result);
    }

    function fetchObject($Rows = 0){ /* 从结果集中取得一行作为对象 */
        if(!is_resource($this->Result)){
            return false;
        }
        $fetchObject = mysql_fetch_object($this->Result);
        if(is_object($fetchObject) && $Rows){$this->Rows++;}
        return is_object($fetchObject)?$fetchObject:false;
    }

    function fetchRow($Rows = 0){ /* 从结果集中取得一行作为枚举数组 */
        if(!is_resource($this->Result)){
            return false;
        }
        $fetchRow = mysql_fetch_row($this->Result);
        if($fetchRow && $Rows){$this->Rows++;}
        return $fetchRow;
    }

    function fieldFlags($fieldOffset){ /* 从结果中取得和指定字段关联的标志 */
        return mysql_field_flags($this->Result, $fieldOffset);
    }

    function fieldLen($fieldOffset){ /* 返回指定字段的长度 */
        return mysql_field_len($this->Result, $fieldOffset);
    }

    function fieldName($fieldIndex){ /* 取得结果中指定字段的字段名 */
        return mysql_field_name($this->Result, $fieldIndex);
    }

    function fieldSeek($fieldOffset){ /* 将结果集中的指针设定为制定的字段偏移量 */
        return mysql_field_seek($this->Result, $fieldOffset);
    }

    function fieldTable($fieldOffset){ /* 取得指定字段所在的表名 */
        return mysql_field_table($this->Result, $fieldOffset);
    }

    function fieldType($fieldOffset){ /* 取得结果集中指定字段的类型 */
        return mysql_field_type($this->Result, $fieldOffset);
    }

    function freeResult(){ /* 释放结果内存 */
        return mysql_free_result($this->Result);
    }

    function getClientInfo(){ /* 取得 MySQL 客户端信息 */
        return mysql_get_client_info();
    }

    function getHostInfo(){ /* 取得 MySQL 主机信息 */
        return mysql_get_host_info($this->LinkId);
    }

    function getProtoInfo(){ /* 取得 MySQL 协议信息 */
        return mysql_get_proto_info($this->LinkId);
    }

    function getServerInfo(){ /* 取得 MySQL 服务器信息 */
        return mysql_get_server_info($this->LinkId);
    }

    function info(){ /* 取得最近一条查询的信息 */
        return mysql_info($this->LinkId);
    }

    function insertId(){ /* 取得上一步 INSERT 操作产生的 ID */
        return @mysql_insert_id($this->LinkId);
    }

    function listDBs(){ /* 列出 MySQL 服务器中所有的数据库 */
        $this->Result = mysql_list_dbs($this->LinkId);
        return $this->Result?true:false;
    }

    function listFields($DBName, $tableName){ /* 列出 MySQL 结果中的字段 */
        $this->Result = mysql_list_fields($DBName, $tableName, $this->LinkId);
        return $this->Result?true:false;
    }

    function listProcesses(){ /* 列出 MySQL 进程 */
        $this->Result = mysql_list_processes($this->LinkId);
        return $this->Result?true:false;
    }

    function listTables($DBName = ''){ /* 列出 MySQL 数据库中的表 */
        $DBName = empty($DBName)?$this->DBName:$DBName;
        $this->Result = mysql_list_tables($DBName, $this->LinkId);
        return $this->Result?true:false;
    }

    function numFields(){ /* 取得结果集中字段的数目 */
        return @mysql_num_fields($this->Result);
    }

    function numRows(){ /* 取得结果集中行的数目 */
        return @mysql_num_rows($this->Result);
    }

    function pconnect($DBHost = '', $DBUser = '', $DBPswd = ''){ /* 打开一个到 MySQL 服务器的持久连接 */
        $connect = @mysql_pconnect(empty($DBHost)?$this->DBHost:$DBHost, empty($DBUser)?$this->DBUser:$DBUser, empty($DBPswd)?$this->DBPswd:$DBPswd);
        if(!is_resource($connect)){
            $this->halt("连接数据库失败！",1);
            return false;
        }
        $this->LinkId = $connect;
        return true;
    }

    function ping(){ /* Ping 一个服务器连接，如果没有连接则重新连接 */
        return mysql_ping($this->LinkId);
    }

    function query($queryString){ /* 发送一条 MySQL 查询 */
        if(empty($queryString)){
            $this->halt("SQL 语句为空！", 1);
            return false;
        }
        if(!is_resource($this->LinkId)){
            $this->halt("请先确保数据库已经连接上！", 1);
            return false;
        }
        //$this->Result = mysql_query($queryString, $this->LinkId) or die(mysql_error());//print_r($this);
        $this->Result = mysql_query($queryString, $this->LinkId);//print_r($this);
		$this->querycount++;
        return $this->Result?true:false;
    }

    function realEscapeString($unescapedString){ /* 转义 SQL 语句中使用的字符串中的特殊字符，并考虑到连接的当前字符集 */
        return mysql_real_escape_string($unescapedString, $this->LinkId);
    }

    function result($row, $field = NULL){ /* 取得结果数据 */
        if(empty($field)){
            return @mysql_result($this->Result, $row, $field);
        }
        return @mysql_result($this->Result, $row);
    }

    function selectDB($DBName = 'test'){ /* 选择 MySQL 数据库 */
        return mysql_select_db(empty($DBName)?$this->$DBName:$DBName, $this->LinkId);
    }

    function stat(){ /* 取得当前系统状态 */
        return mysql_stat($this->LinkId);
    }

    function tablename($index){ /* 取得表名 */
        return mysql_tablename($this->Result, $index);
    }

    function threadId(){ /* 返回当前线程的 ID */
        return mysql_thread_id($this->LinkId);
    }

    function unbufferedQuery($queryString){ /* 向 MySQL 发送一条 SQL 查询，并不获取和缓存结果的行 */
        $this->Result = mysql_unbuffered_query($queryString,$this->LinkId);
        return $this->Result?true:false;
    }

	/*-- 上面的方法名跟手册上 MySQL 的那些函数名是一对一的，除了“splitDSN”和“构造函数” --*/
	/*-- 下面是扩展，也就是说下面的这些方法在手册上是找不到影子的                         --*/
    function free(){ /* 释放结果内存，效果 freeResult 一样，只是这样简单些，少写几个字母，算是别名吧~ ^_^ */
        return $this->freeResult();
    }

    function setMustBeHalt($MustBeHalt = false){
        $this->MustBeHalt = $MustBeHalt;
    }

    function getMustBeHalt(){
        return $this->MustBeHalt;
    }

	/* 以下是支持事物扩展 */
    function setAutoCommit($AutoCommit = 1){ /* 默认为不支持事务 */
        mysql_query("SET AUTOCOMMIT = ".$AutoCommit);
    }

    function begin(){ /* 事务开始 */
        @mysql_query("LOCK TABLES");
        @mysql_query("UNLOCK TABLES");
        $this->setAutoCommit(0);
        mysql_query("BEGIN");
    }

    function rollback(){ /* 回滚，继续默认的不支持事务 */
        mysql_query("ROLLBACK");
        @mysql_query("UNLOCK TABLES");
        $this->setAutoCommit(1);
    }

    function commit(){ /* 事务结束，继续默认的不支持事务 */
        mysql_query("COMMIT");
        @mysql_query("UNLOCK TABLES");
        $this->setAutoCommit(1);
    }

	/* 以上是支持事物扩展 */
    function getRows(){ /* 取得已经读出的数据记录数 */
        return $this->Rows;
    }

    function getDBName(){
        return $this->DBName;
    }

    function nextRecord($resultType = 0){ /* 跟 phplib 接轨，同 phplib 的 next_record */
        $record = NULL;
        switch($resultType){
            case 1:
                $record = @mysql_fetch_assoc($this->Result);
                $this->RecordCase = 1;
            break;
            case 2:
                $record = @mysql_fetch_row($this->Result);
                $this->RecordCase = 2;
            break;
            case 3:
                $record = @mysql_fetch_object($this->Result);
                $this->RecordCase = 3;
            break;
            default:
                $record = @mysql_fetch_array($this->Result);
                $this->RecordCase = 0;
            break;
        }
        if(is_resource($record)){
            $this->Record = $record;
        }else{
            return false;
        }
    }

    function f($fieldName){ /* 跟 phplib 接轨 */
        switch($this->RecordCase){
            case 3:
                return is_object($this->Record)?$this->Record->$fieldName:NULL;
            break;
            default:
                return $this->Record[$fieldName];
            break;
        }
    }

    function setTest($IsTest){
        if(is_bool($IsTest)){
            $this->IsTest = $IsTest;
        }else{
            die('$IsTest 值不对！');
        }
    }

    function getTest(){
        return $this->IsTest;
    }

	/* 下面的自己可以定义格式 */
    function halt($msg = "未知错误！", $MustBeHalt = 0){
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
		echo "<title>$msg</title>\n";
        echo "<br><font size=7 color='red'><b>$msg</b></font>";
        if($MustBeHalt !== 0 || $this->getMustBeHalt()){
            if($this->getTest()){
                echo "<pre>";
                //print_r($this);
                echo "</pre>";
            }
            die();
        }
    }

	/* 下面的可要可不要，因为得PHP5才行 */
    function __get($nm){
        if(isset($this->$nm)){
            //
        }else{
            $this->halt("没有的成员变量 ：$nmn",1);
        }
    }

    function __set($nm, $val){
        //
    }

    function __call($m, $a){
        print "<hr>调用不存在的方法——".$m."(".join(",",$a).")！n";
        echo '<pre>';
        var_dump($a);
        echo '</pre>';
        $this->halt("<hr>");
    }

} // End DummyMySQLClass
?>
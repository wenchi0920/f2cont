<?php
	require_once("db.php");

	//数据库类型名称://入口帐号:入口密码@数据库主机名:端口号/数据库名/是否总是打开新的连接
	$dsn = "MySQL://Dummy:admin@localhost:3306/tetx/false";

	$DMC = new DummyMySQLClass($dsn);
	//$DMC    = new DummyMySQLClass("localhost","root","","test");
	//print_r($DMC);
	$DMC->query("use mysql");
	$queryResult = $DMC->query("SHOW TABLE STATUS FROM user");
	if(false === $queryResult){
		$DMC->halt('查询失败：'.$sql_Get_SelectedDB_Tables);
	}else{
		// 处理~
		while($fo = $DMC->fetchObject()){
			$tableName   = $fo->Name;
			$DataRecord  = $fo->Rows;
			$DataSize    = $fo->Data_length + $fo->Index_length;
			$Surplus     = $fo->Data_free;
			$TableType   = $fo->Type;
			$TableEncode = $fo->Collation;

			$TotalRecord += $DataRecord;
			$DBEncode     = $TableEncode;
			$TotalSize   += $DataSize;
			$TotalSurplus = $Surplus;
		}
		// ……
	}

	// drop table if exists 
	$md5 = md5('admin');
	$sql_Drop = "DROP TABLE IF EXISTS dmc_demo;";
	$DropResult = $DMC->query($sql_Drop);
	if(false === $DropResult){
		echo '删除表失败。<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// create a test table 
	$sql_Create = "CREATE TABLE dmc_demo(
	  id int(9) not null auto_increment,
	  name varchar(32) not null default '',
	  pswd varchar(32) not null default '',
	  sex enum('1', '0', '-1') not null default '0',
	  age smallint(3) not null default 0,
	  primary key (id)
	);";
	$CreateResult = $DMC->query($sql_Create);
	if(false === $CreateResult){
		echo '建表失败。<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// insert one line
	$sql_Insert = "insert into dmc_demo values('', 'Dummy', '".$md5."', '1', '11');";
	$InsertResult = $DMC->query($sql_Insert);
	if(false === $InsertResult || !$DMC->affectedRows()){
		echo '插入失败。<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// select 
	$sql_Select = "select * from dmc_demo where name = 'Dummy' ";
	$SelectResult = $DMC->query($sql_Select);
	if(false === $SelectResult){
		echo '查询失败。<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// 下面分别显示处理结果的四种方法。
	echo '<PRE>';
	// 1
	while($fa = $DMC->fetchArray()){
		print_r($fa);
	}

	// 2
	$DMC->dataSeek(0); // 重置内部结果的指针
	while($fa = $DMC->fetchAssoc()){
		print_r($fa);
	}

	// 3
	$DMC->dataSeek(0); // 重置内部结果的指针
	while($fa = $DMC->fetchRow()){
		print_r($fa);
	}

	// 4
	$DMC->dataSeek(0); // 重置内部结果的指针
	while($fo = $DMC->fetchObject()){
		echo $fo->id;
		echo ' : ';
		echo $fo->name;
		echo ' : ';
		echo $fo->pswd;
		echo ' : ';
		echo $fo->sex;
		echo ' : ';
		echo $fo->age;
	}
	echo '</PRE>';

	//---------------------------------------------------------
	/**4
	$DMC->query("SELECT    * FROM `purchase_product` LIMIT 1");
	while($fetchObject = $DMC->fetchObject(3)){
		echo $DMC->fieldName(0).'<br>'; // 效果如：ID
		echo $DMC->fieldType(0).'<br>';
		echo $DMC->fieldLen(0).'<br>'; // 效果如：9
		print_r($DMC->fieldFlags(0)); // 效果如：not_null primary_key auto_increment
		echo '<hr>合起来的效果：<br>';
		echo '`'.$DMC->fieldName(0).'` '.$DMC->fieldType(0).'('.$DMC->fieldLen(0).') '.str_replace(array("NOT_NULL","PRIMARY_KEY"),array("NOT NULL","PRIMARY KEY"),strtoupper($DMC->fieldFlags(0))).",n<br>";
	}
	//下面这是秘籍！！^_^
	*/
	//---------------------------------------------------------
	/**4
	echo "getHostInfo() --- ".$DMC->getHostInfo().'<br>';
	echo "getClientInfo() - ".$DMC->getClientInfo().'<br>';
	echo "getProtoInfo() -- ".$DMC->getProtoInfo().'<br>';
	echo "getServerInfo() - ".$DMC->getServerInfo().'<br>';
	echo "info() ---------- ".$DMC->info().'<br>';
	*/
	//---------------------------------------------------------
	/**3
	//取得指定库的所有表
	$DMC->listTables("test");
	while($row = $DMC->fetchRow()){
		print "Table: ".$row[0]."n";
	}
	//下面for的效果跟上面while的一样
	for ($i = 0; $i < $DMC->numRows(); $i++){
		printf ("Table: %sn", $DMC->tablename($i));
	}
	*/
	//---------------------------------------------------------
	/**2
	//数据查询
	$DMC->query("SELECT    * FROM `purchase_product` LIMIT 1");
	while($fetchObject = $DMC->fetchObject(3)){
		print_r($fetchObject);
		print_r($DMC->fetchLengths()); // 是记录的长度，不是字段的长度
		//print_r($DMC);
	}
	*/
	//---------------------------------------------------------
	/**1
	// 取得指定库指定表名称的字段
	$DMC->listFields("test","purchase_product");
	$a = $DMC->numFields();
	for($i=0; $i<$a; $i++){
		echo $DMC->fieldName($i).' ';
	}
	*/
?>
<?php
	require_once("db.php");

	//���ݿ���������://����ʺ�:�������@���ݿ�������:�˿ں�/���ݿ���/�Ƿ����Ǵ��µ�����
	$dsn = "MySQL://Dummy:admin@localhost:3306/tetx/false";

	$DMC = new DummyMySQLClass($dsn);
	//$DMC    = new DummyMySQLClass("localhost","root","","test");
	//print_r($DMC);
	$DMC->query("use mysql");
	$queryResult = $DMC->query("SHOW TABLE STATUS FROM user");
	if(false === $queryResult){
		$DMC->halt('��ѯʧ�ܣ�'.$sql_Get_SelectedDB_Tables);
	}else{
		// ����~
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
		// ����
	}

	// drop table if exists 
	$md5 = md5('admin');
	$sql_Drop = "DROP TABLE IF EXISTS dmc_demo;";
	$DropResult = $DMC->query($sql_Drop);
	if(false === $DropResult){
		echo 'ɾ����ʧ�ܡ�<BR>';
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
		echo '����ʧ�ܡ�<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// insert one line
	$sql_Insert = "insert into dmc_demo values('', 'Dummy', '".$md5."', '1', '11');";
	$InsertResult = $DMC->query($sql_Insert);
	if(false === $InsertResult || !$DMC->affectedRows()){
		echo '����ʧ�ܡ�<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// select 
	$sql_Select = "select * from dmc_demo where name = 'Dummy' ";
	$SelectResult = $DMC->query($sql_Select);
	if(false === $SelectResult){
		echo '��ѯʧ�ܡ�<BR>';
		echo $DMC->errno()." : ";
		echo $DMC->error();
		die();
	}

	// ����ֱ���ʾ�����������ַ�����
	echo '<PRE>';
	// 1
	while($fa = $DMC->fetchArray()){
		print_r($fa);
	}

	// 2
	$DMC->dataSeek(0); // �����ڲ������ָ��
	while($fa = $DMC->fetchAssoc()){
		print_r($fa);
	}

	// 3
	$DMC->dataSeek(0); // �����ڲ������ָ��
	while($fa = $DMC->fetchRow()){
		print_r($fa);
	}

	// 4
	$DMC->dataSeek(0); // �����ڲ������ָ��
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
		echo $DMC->fieldName(0).'<br>'; // Ч���磺ID
		echo $DMC->fieldType(0).'<br>';
		echo $DMC->fieldLen(0).'<br>'; // Ч���磺9
		print_r($DMC->fieldFlags(0)); // Ч���磺not_null primary_key auto_increment
		echo '<hr>��������Ч����<br>';
		echo '`'.$DMC->fieldName(0).'` '.$DMC->fieldType(0).'('.$DMC->fieldLen(0).') '.str_replace(array("NOT_NULL","PRIMARY_KEY"),array("NOT NULL","PRIMARY KEY"),strtoupper($DMC->fieldFlags(0))).",n<br>";
	}
	//���������ؼ�����^_^
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
	//ȡ��ָ��������б�
	$DMC->listTables("test");
	while($row = $DMC->fetchRow()){
		print "Table: ".$row[0]."n";
	}
	//����for��Ч��������while��һ��
	for ($i = 0; $i < $DMC->numRows(); $i++){
		printf ("Table: %sn", $DMC->tablename($i));
	}
	*/
	//---------------------------------------------------------
	/**2
	//���ݲ�ѯ
	$DMC->query("SELECT    * FROM `purchase_product` LIMIT 1");
	while($fetchObject = $DMC->fetchObject(3)){
		print_r($fetchObject);
		print_r($DMC->fetchLengths()); // �Ǽ�¼�ĳ��ȣ������ֶεĳ���
		//print_r($DMC);
	}
	*/
	//---------------------------------------------------------
	/**1
	// ȡ��ָ����ָ�������Ƶ��ֶ�
	$DMC->listFields("test","purchase_product");
	$a = $DMC->numFields();
	for($i=0; $i<$a; $i++){
		echo $DMC->fieldName($i).' ';
	}
	*/
?>
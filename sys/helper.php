<!-- booting up the helper -->
<?php
	/*
	*	Owner: Frank Lou A. UBay
	*	Version: 1.0.1
	*	Date: June 15, 2017, September 23, 2017
	*/

	/*
	*	Table of Contents
	*
	*	F1, Line 27 = execQuery($query)
	*	F2, Line 48 = queryData([SQL query])
	*	F3, Line 72 = getData([identifier of the data in associative array], [table name])
	*	F4, Line 113 = getResultSet($SQL query)
	*	F5, Line 138 = insertData([table name], [data in associative array])
	*	F6, Line 173 = db_init()
	*	F7, Line 196 = validate()
	*	F8, Line 214 = logging()
	*	
	*/

	/* F1
	*	execQuery([modified SQL query])
	*		- function that executes any query.
	*/
	function execQuery($query){
		try {
			
			$pdo = db_init();

			$pdo->exec($query);

		} catch (PDOException $e) {
			logging($_SERVER['PHP_SELF'] . ", 22, Error Executing Query:". $e->getMessage() ." ," . date("Y-m-d") . " " . date("h:i:sa"));
		}
	}

	/* F2
	*	queryData([SQL query])
	*	
	*		- a method that will be supplied a SQL query
	*	and then return the data using an associative 
	*	array.
	*
	*		- returns a single associative array (beware for errors ;) )
	*/
	function queryData($query){
		try {
			
			$pdo = db_init();

			$rs = $pdo->query($query);

			return $rs->fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			logging($_SERVER['PHP_SELF'] . ", 44, Error Pulling Data:". $e->getMessage() ." ," . date("Y-m-d") . " " . date("h:i:sa"));
			return;
		}
	}
	/* F3
	*	getData([identifier of the data in associative array], [table name])
	*		- This method will return an associative array with a single row 


		Example ...

			getData(Array('ID' => '1', ...), [table name]);
	*
	*/
	function getData($ident, $table){

		try {
			
			$pdo = db_init();

			$field = $data = "";

			foreach ($ident as $key => $value) {
				$field = $key;
				$data = $value;
			}

			$query = "SELECT * FROM $table WHERE $field LIKE $data";
			
			$rs = $pdo->query($query); 

			return $rs;

		} catch (PDOException $e) {
			logging($_SERVER['PHP_SELF'] . ", 73, Error Pulling Data:". $e->getMessage() ." ," . date("Y-m-d") . " " . date("h:i:sa"));
			return;
		}
	}

	/* F4
	*
	*	getResultSet($SQL query)
	*
	*	- This is the bug fix that you made to fetch multiple rows in a data
	*	- Don't forget to add a while loop to fetch the data
	*
	*	Example : 
	*
	*	$rs = getResultSet([sql data]);
	*
	*	while ($rows = $rs->fetch()){
	*		// your code
	*	}
	*	
	*/
	function getResultSet($SQL){

		try {
			$pdo = db_init();

			return $pdo->query($SQL);
		} catch (PDOException $e){
			logging($_SERVER['PHP_SELF'] . ", 44, Error Pulling Data:". $e->getMessage() ." ," . date("Y-m-d") . " " . date("h:i:sa"));
			return;
		}
	}



	/* F5
	*	insertData([table name], [data in associative array])
	*
	*		- associative array which uses the KEY as the column 
	*   names of the database.table and the value is the data
	*	that will be punched into the database.table.
	*
	* Example ... 
	*
	*	insertData('table_name' , Array('col_name' => 'value', ...));
	*/
	function insertData($table, $data){
		
		validate();

		$values = $cols = "";

		foreach ($data as $key => $value) {
			$cols .= " " . $key . ",";
			$values .= " " . $value . ",";
		}

		$cols = substr($cols, 0, strlen($cols) - 1);
		$values = substr($values, 0, strlen($values) - 1);

		$query = "INSERT INTO $table ($cols) VALUES ($values)";

		
		try {

			$pdo = db_init();

			$pdo->exec($query);

			return 1;
		} catch (PDOException $e) {
			logging($_SERVER['PHP_SELF'] . ", 112, Error Insert Data:". $e->getMessage() ." ," . date("Y-m-d") . " " . date("h:i:sa"));
			return;
		}

	}

	/*	F6
	*	db_init()
	*		- This function will initializa the connectivity of the database.
	*/
	function db_init(){
		$SOURCE = $GLOBALS['SOURCE'];
		try {
			$pdo = new PDO('mysql:host=' . 
				$SOURCE['host'] . ';dbname=' .
				$SOURCE['dbname'],
				$SOURCE['username'] ,
				$SOURCE['password']
				);


			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->exec('SET NAMES "utf8"');

			return $pdo;

		} catch (PDOException $e){
			logging($_SERVER['PHP_SELF'] . ", 138" . ", " . $e->getMessage() . ", " . date("Y-m-d") . " " . date("h:i:sa"));
			return;
		}
	}

	// F7
	function validate(){
		if (get_magic_quotes_gpc()){
			$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
			while(list($key, $val) = each($process)){
				foreach ($val as $k => $v) {
					unset($process[$key][$k]);
					if (is_array($v)){
						$process[$key][stripslashes($k)] = $v;
						$process[] = &$process[$key][stripslashes($k)];
					} else {
						$process[$key][stripslashes($k)] = stripslashes($v);
					}
				}
			} unset($process);
		}
	}

	// F8
	function logging($str = 'EMPTY STRING'){
		$file = fopen("log.txt", "a") or die("ERROR in Logging");
		fwrite($file, $str . "\n");
		fclose($file);
	}
?>
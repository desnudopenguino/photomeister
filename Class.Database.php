<?php
	
class DatabaseManager {
	
	/* Properties */
	private $_host;
	
	private $_user;
	
	private $_password;
	
	private $_database;
	
	private $_port;
	
	private $_databaseType;
	
	private $_dbConn;
	
	private $_counter = 0;
	

	/* Constructor */
	public function __construct($host, $user, $password, $database, $port) {
		$this->setHost($host);
		$this->setUser($user);
		$this->setPassword($password);
		$this->setDatabase($database);
		$this->setPort($port);
	}
	
	/* Accessors & Mutators */
	
	//Host
	public function setHost($value) {
		$this->_host = $value;
	}
	
	public function getHost() {
		return $this->_host;
	}
	
	//User
	public function setUser($value) {
		$this->_user = $value;
	}
	
	public function getUser() {
		return $this->_user;
	}
	
	//Password
	public function setPassword($value) {
		$this->_password = $value;
	}
	
	public function getPassword() {
		return $this->_password;
	}
	
	//Database
	public function setDatabase($value) {
		$this->_database = $value;
	}
	
	public function getDatabase() {
		return $this->_database;
	}
	
	//Port
	public function setPort($value) {
		$this->_port = $value;
	}
	
	public function getPort() {
		return $this->_port;
	}
	
	//Database Type
	public function setDatabaseType($value) {
		$this->_databaseType = $value;
	}
	
	public function getDatabaseType() {
		return $this->_databaseType;
	}
	
	//Database Connection
	public function setDBConn($value){
		$this->_dbConn = $value;
	}
	
	public function getDBConn() {
		return $this->_dbConn;
	}
	
	
	/* Functions */
	
	//Make connection
	public function connectDB() {
		switch($this->getDatabaseType()) {
		
			//mysqli
			case "mysqli":
				$this->_dbConn = new mysqli($this->getHost(),
					$this->getUser(),
					$this->getPassword(),
					$this->getDatabase()
				);
				if(mysqli_connect_errno()) {
					print('connection failed: '. mysqli_connect_error());
					exit();
				}
				break;
	
			//mysql
			case "mysql":
				 $this->_dbconn = mysql_connect(getHost(),
					$this->getUser(),
					$this->getPassword())
					or die('Could not connect: ' . mysql_error());
				mysql_select_db($this->getDatabase()) 
					or die('Could not select database');
				break;
			
			//postgres
			case "postgres":
				$postgresConnString = "host=".$this->getHost().
					" dbname=".$this->getDatabase().
					" user=".$this->getUser().
					" password=".$this->getPassword().
					"";
				$this->setDBConn(pg_connect($postgresConnString));
				break;
		}
	}
	
	public function closeDB() {
		pg_close($this->getDBConn());
	}
	
	//Query the db with prepared queries
	public function query($query,$array) {
		$objectList = array();
		switch($this->getDatabaseType()) {
			
			//mysqli
			case "mysqli":
			
				break;
			
			//mysql
			case "mysql":
			
				break;
			
			//postgres
			case"postgres":
				$this->connectDB();
				if($array != null) {
					$result = pg_prepare($this->getDBConn(),"query".$this->_counter,$query);
					$result = pg_execute($this->getDBConn(),"query".$this->_counter,$array);
				}
				else {
					$result = pg_query($this->getDBConn(), $query);
				}
				
				if(!$result) {
					throw new Exception('ERROR: Query not valid');
				}
				else {
					while($row = pg_fetch_object($result)) {
						$objectList[] = $row;
					}
				}		
				$this->_counter++;
				$this->closeDB();
				break;
		}
		return $objectList;
	}
	
	
	//Query the db with prepared queries
	public function insert($table,$array) {
		$result = null;
		switch($this->getDatabaseType()) {
			
			//mysqli
			case "mysqli":
				break;
				
			//mysql
			case "mysql":
				break;
				
			//postgres
			case"postgres":
				$this->connectDB();
				pg_insert($this->getDBConn(), $table, $array);
				$this->closeDB();
				break;
		}
	}
	
	
	//create a large object into the database
	public function createLargeObject($object) {
		$this->connectDB();
		//start transaction
		pg_query($this->getDBConn(), "begin");
		//create large object
		$oid = pg_lo_create($this->getDBConn());
		//open the large object & give it a handle
		$handle = pg_lo_open($this->getDBConn(),$oid, "w");
		//write to the handle
		pg_lo_write($handle, $object);
		//close the handle
		pg_lo_close($handle);
		//commit the transaction
		pg_query($this->getDBConn(), "commit");
		$this->closeDB();
		return $oid;
	}
	
	
	//insert a large object
	public function insertLargeObject($object) {
		$this->connectDB();
		pg_query($this->getDBConn(), "begin");
		$oid = pg_lo_import($this->getDBConn(), $object);
		pg_query($this->getDBConn(), "commit");
		$this->closeDB();
		return $oid;
	}
	
	//get a large object from the database
	public function getLargeObject($oid, $contentType) {
		header("Content-Type: ".$contentType);
		$this->connectDB();
		pg_query($this->getDBConn(), "begin");
		$handle = pg_lo_open($this->getDBConn(), $oid, "r");
		pg_lo_read_all($handle);
		pg_query($this->getDBConn(), "commit");
		$this->closeDB();
	}
	
	//delete a row from a table
	public function deleteRow($table, $args) {
		$this->connectDB();
		pg_delete($this->getDBConn(), $table, $args);
		$this->closeDB();
	}
	
	//update
	public function update($table, $data, $condition) {
		$this->connectDB();
		pg_update($this->getDBConn(), $table, $data, $condition);
		$this->closeDB();
	}
	
	//delete object
	public function deleteLargeObject($objectID) {
		$this->connectDB();
		pg_lo_unlink($this->getDBConn(), $objectID);
		$this->closeDB();
	}
}
?>

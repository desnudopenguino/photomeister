<?php 

include_once("Class.Database.php");

class User {
	
	/*Properties */
	private $_name;
	
	private $_id;
	
	private $_password;
	
	private $_SALT;
	
	private $_dbConn;
	
	
	/*Constructor*/
	public function __construct() {

	}

	
	/*Accessors & Mutators*/
	
	//Name
	public function setName($value) {
		$this->_name = strtolower($value);	
	}
	
	public function getName() {
		return $this->_name;
	}
	
	//ID
	public function setId($value) {
		$this->_id = $value;	
	}
	
	public function getId() {
		return $this->_id;
	}
	
	//Password
	public function setPassword($value) {
		$this->_password = crypt($value,$this->_SALT);	
	}
	
	public function getPassword() {
		return $this->_password;
	}
	
	//SALT
	public function setSALT($value) {
		$this->_SALT = $value;
	}
	
	public function getSALT() {
		return $this->_SALT;
	}
	
	//DBConn
	public function setDBConn($value) {
		$this->_dbConn = $value;
	}
	
	public function getDBConn() {
		return $this->_dbConn;
	}
	
	
	
	/*Login Functions */
	
	//login
	public function login() {
	
		$query = "SELECT id 
			FROM test_user
			WHERE username = $1
			AND password = $2
			";
		$array = array($this->getName(), $this->getPassword());
		
		$result = null;
		
		try{
			$result = $this->getDBConn()->query($query, $array);
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
		}
		
		
		if(!$result) {
			throw new Exception('ERROR: Invalid Username/Password');
		}
		else {
			$row = $result[0];
			$id = $row->id;
			$this->setID($id);
			$_SESSION["user"] = serialize($this);
		}
	}
	
	//logout
	public function logout() {
		$_SESSION["user"] = null; 	
		header("Location: index.php");
	}
	
	
	//register
	public function register() {
		
		//create check query
		$query = "SELECT id
			FROM test_user
			WHERE username = $1";
		
		$array = array($this->getName());
		
		//execute check query
		$result = $this->_dbConn->query($query, $array);
		
		//if check passes, write insert query
		if(!$result) {
			//register query
			$table = "test";
			$user["name"] = $this->getName();
			$user["password"] = $this->getPassword();
			
			$this->_dbConn->insert($table,$user);
		}
		
		else {
			throw new Exception('ERROR: Username already exists');
		}
		
	}
	
	//delete user from db
	public function delete() {
		$args = array('id' => $this->getID());
		
		$this->getDBConn()->deleteRow("test_user",$args);
	}
	
	//change the password
	public function changePassword() {
		$table = "test_user";
			
		$user["password"] = $this->getPassword();
		
		$condition = array('id' => $this->getID());
		
		$this->getDBConn()->update($table, $user, $condition);
	}
}
?>
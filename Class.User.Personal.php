<?php
	include_once("Class.User.php");
	include_once("Class.Portfolio.php");
	
	class PersonalUser extends User {
		
		/* Properties */

		//First name
		private $_firstName;

		//Last Name
		private $_lastName;

		//City
		private $_city;

		//State
		private $_state;

		//Zip code
		private $_zip;

		//Phone number
		private $_phone;
		
		//portfolios
		private $_portfolios = array();
		
		//email
		private $_email;

		
		/* Accessors & Mutators */
		
		//First Name
		public function setFirstName($value) {
			$this->_firstName = ucWords($value);
		}
		
		public function getFirstName() {
			return $this->_firstName;
		}
		
		//Last Name
		public function setLastName($value) {
			$this->_lastName = ucWords($value);
		}
		
		public function getLastName() {
			return $this->_lastName;
		}
		
		//City
		public function setCity($value) {
			$this->_city = ucWords($value);
		}
		
		public function getCity() {
			return $this->_city;
		}
		
		//State
		public function setState($value) {
			$this->_state = $value;
		}
		
		public function getState() {
			return $this->_state;
		}
		
		//Zip Code
		public function setZip($value) {
			$this->_zip = $value;
		}
		
		public function getZip() {
			return $this->_zip;
		}
		
		//Phone Number
		public function setPhone($value) {
		
			$this->_phone = $value;
		}
		
		public function getPhone() {
			return $this->_phone;
		}
		
		//Email
		public function setEmail($value) {
			$this->_email = strtolower($value);
		}
		
		public function getEmail() {
			return $this->_email;
		}
		
		//Portfolios
		public function setPortfolios($value) {
			$this->_portfolios = $value;
		}
		
		public function getPortfolios() {
			return $this->_portfolios;
		}
		
		public function addPortfolio($value) {
			$this->_portfolios[] = $value;
		}
		
		
		/* Functions */
		
		public function register() {
		
			//create check query
			$query = "SELECT id
				FROM test_user
				WHERE username = $1";
			
			$array = array($this->getName());
			
			//execute check query
			$result = $this->getDBConn()->query($query, $array);
			
			//if check passes, write insert query
			if(!$result) {
				//register query
				$table = "test_user";
				$user["username"] = $this->getName();
				$user["password"] = $this->getPassword();
				$user["firstname"] = $this->getFirstName();
				$user["lastname"] = $this->getLastName();
				$user["city"] = $this->getCity();
				$user["state"] = $this->getState();
				$user["zip"] = $this->getZip();
				$user["phone"] = $this->getPhone();
				$user["email"] = $this->getEmail();
				
				
				$this->getDBConn()->insert($table,$user);
			}
			
			else {
				throw new Exception('ERROR: Username already exists');
			}
		}
		
		
		public function login() {
	
			$query = "SELECT id, username, firstname, lastname, email, city, state, zip, phone
				FROM test_user
				WHERE username = $1
				AND password = $2";
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
				$user = $result[0];
				
				$this->setID($user->id);
				$this->setName($user->username);
				$this->setFirstName($user->firstname);
				$this->setLastName($user->lastname);
				$this->setEmail($user->email);
				$this->setCity($user->city);
				$this->setState($user->state);
				$this->setZip($user->zip);
				$this->setPhone($user->phone);
				
				$_SESSION["user"] = serialize($this);
			}
		}


		//get user's portfolios
		public function loadPortfolios() {

			$query = "SELECT name, id
				FROM test_portfolio
				WHERE userid = $1";

			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$row) {
				$portfolio = new Portfolio();
				$portfolio->setDBConn($this->getDBConn());
				$portfolio->setID($row->id);
				$portfolio->setName($row->name);
				
				$this->addPortfolio($portfolio);
			}
		}
		
		//update the user information
		public function update() {
			$table = "test_user";
			
			$user["username"] = $this->getName();
			$user["firstname"] = $this->getFirstName();
			$user["lastname"] = $this->getLastName();
			$user["city"] = $this->getCity();
			$user["state"] = $this->getState();
			$user["zip"] = $this->getZip();
			$user["phone"] = $this->getPhone();
			$user["email"] = $this->getEmail();
			
			$condition = array('id' => $this->getID());
			
			$this->getDBConn()->update($table, $user, $condition);
		}
		
		
		//load user from database
		public function load() {
			$query = "SELECT username, firstname, lastname, email, city, state, zip, phone, email
				FROM test_user
				WHERE id= $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			if(!$result) {
				throw new Exception("ERROR: User doesn't exist");
			}
			else {
				$user = $result[0];
				
				$this->setName($user->username);
				$this->setFirstName($user->firstname);
				$this->setLastName($user->lastname);
				$this->setEmail($user->email);
				$this->setCity($user->city);
				$this->setState($user->state);
				$this->setZip($user->zip);
				$this->setPhone($user->phone);
				$this->setEmail($user->email);
				
				
				if(isset($_SESSION["user"])) {
					$user = unserialize($_SESSION["user"]);
					
					if($user->getID() == $this->getID()) {
						$_SESSION["user"] = serialize($this);
					}
				}
			}
		}
	}

?>

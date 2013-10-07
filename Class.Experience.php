<?php
	
	include_once("Class.Database.php");
	
	class Experience {
	
		/* Properties */
		
		//start month
		private $_startMonth;
		
		//start year
		private $_startYear;
		
		//end month
		private $_endMonth;
		
		//end year
		private $_endYear;
		
		//experience
		private $_experience;
		
		//database connection
		private $_dbConn;
		
		//owner
		private $_owner;
		
		//id
		private $_id;
		
		/* Accessors & Mutators */
		
		//database connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		//start month
		public function setStartMonth($value) {
			$this->_startMonth = $value;
		}
		
		public function getStartMonth() {
			return $this->_startMonth;
		}
		
		//start year
		public function setStartYear($value) {
			$this->_startYear = $value;
		}
		
		public function getStartYear() {
			return $this->_startYear;
		}
		
		//database connection
		public function setEndMonth($value) {
			$this->_endMonth = $value;
		}
		
		public function getEndMonth() {
			return $this->_endMonth;
		}
		
		//database connection
		public function setExperience($value) {
			$this->_experience = $value;
		}
		
		public function getExperience() {
			return $this->_experience;
		}
		
		//database connection
		public function setEndYear($value) {
			$this->_endYear = $value;
		}
		
		public function getEndYear() {
			return $this->_endYear;
		}
		
		//database connection
		public function setOwner($value) {
			$this->_owner = $value;
		}
		
		public function getOwner() {
			return $this->_owner;
		}
		
		//id
		public function setID($value) {
			$this->_id = $value;
		}
		
		public function getID() {
			return $this->_id;
		}
		
		
		/* Functions */
		
		public function save() {
			$table = "test_experience";
			
			$exp["startmonth"] = $this->getStartMonth();
			$exp["endmonth"] = $this->getEndMonth();
			$exp["startyear"] = $this->getStartYear();
			$exp["endyear"] = $this->getEndYear();
			$exp["experience"] = $this->getExperience();
			$exp["userid"] = $this->getOwner();

			$this->getDBConn()->insert($table, $exp);
		
		}
		
		public function load() {
			$query = "SELECT startmonth, endmonth,
				startyear, endyear,
				experience,
				userid
				FROM test_experience
				WHERE id = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			$row = $result[0];
			
			$this->setOwner($row->userid);
			$this->setExperience($row->experience);
			$this->setStartMonth($row->startmonth);
			$this->setEndMonth($row->endmonth);
			$this->setStartYear($row->startyear);
			$this->setEndYear($row->endyear);
		}
		
		public function update() {
			$table = "test_experience";
			
			$exp["id"] = $this->getID();
			$exp["startmonth"] = $this->getStartMonth();
			$exp["endmonth"] = $this->getEndMonth();
			$exp["startyear"] = $this->getStartYear();
			$exp["endyear"] = $this->getEndYear();
			$exp["experience"] = $this->getExperience();
			$exp["userid"] = $this->getOwner();
			
			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $exp, $condition);	
		}
		
		public function delete() {
			$table = "test_experience";
			
			$condition = array('id' => $this->getID());
			
			$this->getDBConn()->deleteRow($table, $condition);
		}
	}
?>
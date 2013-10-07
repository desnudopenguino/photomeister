<?php
	include_once("Class.Database.php");
	
	class YearList {
		/* Properties */
		
		//Database Connection Object
		private $_dbConn;
		
		//State list
		private $_years;
		
		/* Constructor */
		public function __construct() {
		
		}
		
		/* Accessors & Mutators */
		
		//Database Connection Object
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		
		//State List
		public function setYears($value) {
			$this->_years = $value;
		}
		
		public function getYears() {
			return $this->_years;
		}
		
		public function addYear($key, $value) {
			$this->_years[$key] = $value;
		}
		
		/* Functions */
		
		//load the states from the database
		public function load() {
			$query = "SELECT id
				FROM year
				ORDER BY id DESC";
				
			$args = null;
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$year) {
				$this->addYear($year->id, $year->id);
			}
		}
	}
?> 
<?php
	
	include_once("Class.Database.php");
	
	class MonthList {
	
		/* Properties */
		
		//database connection
		private $_dbConn;
		
		//monthlist
		private $_months;
		
		/* Accessors & Mutators */
		
		//database connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		//Month list
		public function setMonths($value) {
			$this->_months = $value;
		}
		
		public function getMonths() {
			return $this->_months;
		}
		
		public function addMonth($key, $value) {
			$this->_months[$key] = $value;
		}
		
		
		/* Functions */
		
		public function load() {
			$query = "SELECT id, name
				FROM month
				ORDER BY monthorder ASC";
				
			$args = null;
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$month) {
				$this->addMonth($month->id, $month->name);
			}
		}
	}
?>
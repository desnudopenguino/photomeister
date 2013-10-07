<?php
	include_once("Class.Database.php");
	
	class StateList {
		/* Properties */
		
		//Database Connection Object
		private $_dbConn;
		
		//State list
		private $_states;
		
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
		public function setStates($value) {
			$this->_states = $value;
		}
		
		public function getStates() {
			return $this->_states;
		}
		
		public function addState($key, $value) {
			$this->_states[$key] = $value;
		}
		
		/* Functions */
		
		//load the states from the database
		public function load() {
			$query = "SELECT id, name
				FROM state
				ORDER BY name ASC";
				
			$args = null;
			
			$result = $this->getDBConn()->query($query, $args);
			
			var_dump($result);
			foreach($result as &$state) {
				$this->addState($state->id, $state->name);
			}
		}
	}
?> 
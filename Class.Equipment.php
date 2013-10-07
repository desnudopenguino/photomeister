<?php
	
	include_once("Class.Database.php");
	
	class Equipment {
	
		/* Properties */
		
		//skill
		private $_equipment;
				
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
		
		//equipment
		public function setEquipment($value) {
			$this->_equipment = $value;
		}
		
		public function getEquipment() {
			return $this->_equipment;
		}
		
		//Owner
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
			$table = "test_equipment";
			
			$equipment["equipmentname"] = $this->getEquipment();
			$equipment["userid"] = $this->getOwner();

			$this->getDBConn()->insert($table, $equipment);
		}
		
		public function load() {
			$query = "SELECT equipmentname,
				userid
				FROM test_equipment
				WHERE id = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			$row = $result[0];
			
			$this->setOwner($row->userid);
			$this->setEquipment($row->equipmentname);
		}
		
		public function update() {
			$table = "test_equipment";
			
			$equipment["equipmentname"] = $this->getEquipment();
			
			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $equipment, $condition);
		}
		
		public function delete() {
			$table = "test_equipment";
			
			$condition = array('id' => $this->getID());
			
			$this->getDBConn()->deleteRow($table, $condition);
		}	
	}
?>
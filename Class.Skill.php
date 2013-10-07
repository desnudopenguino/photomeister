<?php
	
	include_once("Class.Database.php");
	
	class Skill {
	
		/* Properties */
		
		//skill
		private $_skill;
		
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
		
		//skill
		public function setSkill($value) {
			$this->_skill = $value;
		}
		
		public function getSkill() {
			return $this->_skill;
		}
		
		//experience
		public function setExperience($value) {
			$this->_experience = $value;
		}
		
		public function getExperience() {
			return $this->_experience;
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
			$table = "test_skill";
			
			$skill["skill"] = $this->getSkill();
			$skill["experience"] = $this->getExperience();
			$skill["userid"] = $this->getOwner();

			$this->getDBConn()->insert($table, $skill);
		}
		
		public function load() {
			$query = "SELECT skill,
				experience,
				userid
				FROM test_skill
				WHERE id = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			$row = $result[0];
			
			$this->setOwner($row->userid);
			$this->setExperience($row->experience);
			$this->setSkill($row->skill);
		}
		
		public function update() {
			$table = "test_skill";
			
			$skill["skill"] = $this->getSkill();
			$skill["experience"] = $this->getExperience();
			
			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $skill, $condition);
		}
		
		public function delete() {
			$table = "test_skill";
			
			$condition = array('id' => $this->getID());
			
			$this->getDBConn()->deleteRow($table, $condition);
		}
		
		
		
	}

?>
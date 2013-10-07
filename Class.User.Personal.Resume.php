<?php
	include_once("Class.User.Personal.php");
	include_once("Class.Experience.php");
	include_once("Class.Skill.php");
	include_once("Class.Equipment.php");
	
	class ResumeUser extends PersonalUser {
	
		/* Properties */
		
		//Experiences
		private $_experiences;
		
		//skills
		private $_skills;
		
		//equipment
		private $_equipment;
		
		
		/* Accessors & Mutators */
		
		//experiences
		public function setExperiences($value) {
			$this->_experiences = $value;
		}
		
		public function getExperiences() {
			return $this->_experiences;
		}
		
		public function addExperience($key, $exp) {
			$this->_experiences[$key] = $exp;
		}
		
		//skills
		public function setSkills($value) {
			$this->_skills = $value;
		}
		
		public function getSkills() {
			return $this->_skills;
		}
		
		public function addSkill($key, $skill) {
			$this->_skills[$key] = $skill;
		}
		
		//equipment
		public function setEquipment($value) {
			$this->_equipment = $value;
		}
		
		public function getEquipment() {
			return $this->_equipment;
		}
		
		public function addEquipment($key, $equip) {
			$this->_equipment[$key] = $equip;
		}
		
		/* Functions */
		
		//load the experiences
		public function loadExperiences() {
			$query = "SELECT id
				FROM test_experience
				WHERE userid = $1
				ORDER BY startyear ASC, endyear ASC";
				
			$args = array('id' => $this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$row) {
				$exp = new Experience();
				$exp->setDBConn($this->getDBConn());
				$exp->setID($row->id);
				$this->addExperience($row->id, $exp);
			}
			
		}
		
		//load the skills
		public function loadSkills() {
			$query = "SELECT id
				FROM test_skill
				WHERE userid = $1";
				
			$args = array('id' => $this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$row) {
				$skill = new Skill();
				$skill->setDBConn($this->getDBConn());
				$skill->setID($row->id);
				$this->addSkill($row->id, $skill);
			}
		}
		
		//load the equipments
		public function loadEquipment() {
			$query = "SELECT id
				FROM test_equipment
				WHERE userid = $1";
				
			$args = array('id' => $this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as &$row) {
				$equip = new Equipment();
				$equip->setDBConn($this->getDBConn());
				$equip->setID($row->id);
				$this->addEquipment($row->id, $equip);
			}
		}
	}
?>
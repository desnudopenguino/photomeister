<?php

	include_once("Class.Database.php");
	Include_once("Class.PhotoAlbum.Private.php");

	class Portfolio {
	
		/* Propertios */
		
		private $_id;
		
		private $_name;
		
		private $_description;
		
		private $_owner;
		
		private $_dbConn;
		
		private $_albums = array();
		
		/* Constructor */
		
		public function __construct() {
		
		}
		
		
		/* Accessors & Mutators */
		
		public function setID($value) {
			$this->_id = $value;
		}
		
		public function getID() {
			return $this->_id;
		}
		
		
		public function setName($value) {
			$this->_name = $value;
		}
		
		public function getName() {
			return $this->_name;
		}
		
		
		public function setDescription($value) {
			$this->_description = $value;
		}
		
		public function getDescription() {
			return $this->_description;
		}
		
		
		public function setOwner($value) {
			$this->_owner = $value;
		}
		
		public function getOwner() {
			return $this->_owner;
		}
		
		//Database Connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		
		//Album list
		public function setAlbums($value) {
			$this->_albums = $value;
		}
		
		public function getAlbums() {
			return $this->_albums;
		}
		
		public function addAlbum($value) {
			$this->_albums[] = $value;
		}
		
		/* Functions */
		
		//save portfolio
		public function save() {
			
			$table = "test_portfolio";
			
			$portfolio["id"] = $this->getID();
			$portfolio["name"] = $this->getName();
			$portfolio["description"] = $this ->getDescription();
			$portfolio["userid"] = $this->getOwner();
			
			$this->getDBConn()->insert($table,$portfolio);
		}
		
		//load portfolio by id
		public function load() {
			$query = "SELECT userid, name, description
				FROM test_portfolio
				WHERE id = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			if($result) {
				$portfolio = $result[0];
				
				$this->setName($portfolio->name);
				$this->setDescription($portfolio->description);
				$this->setOwner($portfolio->userid);
			}
			
			else {
				throw new exception("ERROR: Invalid Portfolio ID");
			}
		}
		
		//get photo albums
		public function loadPhotoAlbums() {
			$query = "SELECT id, name
				FROM test_album
				WHERE portfolioid = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			foreach($result as $row) {
				$album = new PrivatePhotoAlbum();
				
				$album->setID($row->id);
				$album->setName($row->name);
				$album->setDBConn($this->getDBConn());
				
				$this->addAlbum($album);
			}
		}
		
		//delete portfolio from db
		public function delete() {
			$args = array('id' => $this->getID());
			
			$this->getDBConn()->deleteRow("test_portfolio",$args);
		}


		//update portfolio
		public function update() {
			$table = "test_portfolio";

			$portfolio["name"] = $this->getName();
			$portfolio["description"] = $this->getDescription();

			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $portfolio, $condition);	
		}
	}
?>

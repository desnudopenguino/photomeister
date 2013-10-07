<?php

	include_once("Class.Database.php");
	include_once("Class.Image.php");

	class PhotoAlbum {
		
		/* Properties */
		
		//Gallery ID
		private $_id;
		
		//Database Connection
		private $_dbConn;
		
		//image array
		private $_images = array();
		
		//Gallery Name
		private $_name;
		
		//Description
		private $_description;
		
		//Columns
		private $_columns;
		
		//Portfolio ID
		private $_portfolioID;
		
		//image height 
		private $_imgHeight;

		//image width
		private $_imgWidth;
		
		
		/* Constructor */
		public function __construct() {
		
		}
		
		/* Accessors & Mutators */
	
		//GalleryID
		public function setID($value) {
			$this->_id = $value;
		}
		
		public function getID() {
			return $this->_id;
		}
		
		//Database Connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		//Image array
		public function setImages($value) {
			$this->_images = $value;
		}
		
		public function getImages() {
			return $this->_images;
		}
		
		public function addImage($value) {
			$this->_images[] = $value;
		}
		
		//Name
		public function setName($value) {
			$this->_name = $value;
		}
		
		public function getName() {
			return $this->_name;
		}
		
		//Description
		public function setDescription($value) {
			$this->_description = $value;
		}
		
		public function getDescription() {
			return $this->_description;
		}
		
		//Columns
		Public function setColumns($value) {
			$this->_columns = $value;
		}
		
		public function getColumns() {
			return $this->_columns;
		}
		
		//Portfolio ID
		Public function setPortfolioID($value) {
			$this->_portfolioID = $value;
		}
		
		public function getPortfolioID() {
			return $this->_portfolioID;
		}
		
		
		/* Functions */
		
		//Save gallery to database
		public function save() {
			
			$table = "test_album";
				
			$args["name"] = $this->getName();
			$args["description"] =  $this->getDescription();
			$args["portfolioid"] = $this->getPortfolioID();
			
			
			$this->getDBConn()->insert($table, $args);
			
		}
		
		//load album info from the db
		public function load() {
			$query = "SELECT name, description, portfolioid
				FROM test_album
				WHERE id = $1";
				
			$args = array($this->getID());
			
			$result = $this->getDBConn()->query($query, $args);
			
			if(!$result) {
				throw new Exception("ERROR: Photo Album doesn't exist");
			}
			
			else {
				$album = $result[0];
				
				$this->setName($album->name);
				$this->setDescription($album->description);
				$this->setPortfolioID($album->portfolioid);
			}
		}
		
		//Get gallery info & images from database
		public function loadImages() {
			$query = "SELECT id, objectID
				FROM test_image
				WHERE albumid = $1
				ORDER BY imgorder ASC";
				
			$args = array($this->getID());
			
			$results = $this->getDBConn()->query($query, $args);
			
			foreach($results as &$row) {
				$image = new Image();
				
				$image->setID($row->id);
				$image->setObjectID($row->objectid);
				$image->setDBConn($this->getDBConn());
				
				$this->addImage($image);
			}
		}
		
		//delete album from db
		public function delete() {
			$args = array('id' => $this->getID());
			
			$this->getDBConn()->deleteRow("test_album",$args);
		}
	

		//get the owner of the album	
		public function getOwner() {
			$query = "SELECT test_user.id
				FROM test_user
				JOIN test_portfolio
				ON(test_user.id = test_portfolio.userid)
				JOIN test_album
				on(test_portfolio.id = test_album.portfolioid)
				WHERE test_album.id = $1";
			$args = array($this->getID());
			$result = $this->getDBConn()->query($query,$args);
			
			$owner;
			
			if(!$result) {
				throw new Exception("ERROR: There is no owner of this album!");
			}
			else {
				$row = $result[0];
				$owner = $row->id;
			}
			return $owner;
		}
		
		//update album info on the DB
		public function update() {
			$table = "test_album";

			$portfolio["name"] = $this->getName();
			$portfolio["description"] = $this->getDescription();

			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $portfolio, $condition);	
		}
	}
?>
<?php
	include_once("Class.Database.php");
	
	class Image {
	
		//Database Connection
		private $_dbConn;
		
		//ID
		private $_id;
		
		//Object ID
		private $_objectID;
		
		//Location
		private $_location;
		
		//Album ID
		private $_albumID;
		
		//Image Keywords
		private $_keywords;
		
		//Image Caption
		private $_caption;
		
		//Thumb ID
		private $_thumbID;
		
		//Image Order
		private $_order;

		/* Constructor */
		public function __construct() {
			
		}
	
		/*Accessors & Mutators */
		
		//Database Connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
		
		public function getDBConn() {
			return $this->_dbConn;
		}
		
		//ID
		public function setID($value) {
			$this->_id = $value;
		}
		
		public function getID() {
			return $this->_id;
		}
		
		//Object ID
		public function setObjectID($value) {
			$this->_objectID = $value;
		}
		
		public function getObjectID() {
			return $this->_objectID;
		}
		
		//File location
		public function setLocation($value) {
			$this->_location = $value;
		}
		
		public function getLocation() {
			return $this->_location;
		}
		
		//Album ID
		public function setAlbumID($value) {
			$this->_albumID = $value;
		}
		
		public function getAlbumID() {
			return $this->_albumID;
		}
		
		//Keywords
		public function setKeywords($value) {
			$this->_keywords = $value;
		}
		
		public function getKeywords() {
			return $this->_keywords;
		}
		
		//Caption
		public function setCaption($value) {
			$this->_caption = $value;
		}
		
		public function getCaption() {
			return $this->_caption;
		}
		
		//Thumb ID
		public function setThumbID($value) {
			$this->_thumbID = $value;
		}
		
		public function getThumbID() {
			return $this->_thumbID;
		}
		
		//Image Order
		public function setOrder($value) {
			$this->_order = $value;
		}
		
		public function getOrder() {
			return $this->_order;
		}
		
		
		/*Functions*/
		
		//Save image to Database
		public function saveImage() {
			$oid = $this->getDBConn()->insertLargeObject($this->getLocation());
			
			$this->setObjectID($oid);
			
			if($this->getObjectID() != false) {
				$table = "test_image";
				$image["objectid"] = $this->getObjectID();
				$image["albumid"] = $this->getAlbumID();
				$image["thumbid"] = $this->getThumbID();
				$this->getDBConn()->insert($table,$image);	
			}
			else {
				throw new exception("ERROR: Could not save image to database");
			}
		}
		
		//get image from database
		public function show() {
			$contentType = "image/jpeg";
			$this->getDBConn()->getLargeObject($this->getObjectID(), $contentType);
		}
		
		//get thumb from database
		public function showThumb() {
			$contentType = "image/jpeg";
			$this->getDBConn()->getLargeObject($this->getThumbID(), $contentType);
		}
		
		//delete image from db
		public function delete() {
			//delete object
			$this->getDBConn()->deleteLargeObject($this->getObjectID());
			$this->getDBConn()->deleteLargeObject($this->getThumbID());
			
			//delete row
			$args = array('id' => $this->getID());
			$this->getDBConn()->deleteRow("test_image",$args);
		}
		
		//load image 
		public function load() {
			$query = "SELECT objectid, albumid, keywords, caption, thumbid, imgorder
				FROM test_image
				WHERE id= $1";
			$args = array($this->getID());
			$result = $this->getDBConn()->query($query,$args);
			$row = $result[0];	
			$this->setObjectID($row->objectid);
			$this->setAlbumID($row->albumid);
			$this->setKeywords($row->keywords);
			$this->setCaption($row->caption);
			$this->setThumbID($row->thumbid);
			$this->setOrder($row->imgorder);
		}
		
		//get the owner of the image	
		public function getOwner() {
			$query = "SELECT test_user.id
				FROM test_user
				JOIN test_portfolio
				ON(test_user.id = test_portfolio.userid)
				JOIN test_album
				ON(test_portfolio.id = test_album.portfolioid)
				JOIN test_image
				ON(test_album.id = test_image.albumid)
				WHERE test_image.id = $1";
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
		
		//update image
		public function update() {
			$table = "test_image";

			$image["keywords"] = $this->getKeywords();
			$image["caption"] = $this->getCaption();

			$condition = array('id' => $this->getID());

			$this->getDBConn()->update($table, $image, $condition);

		}
		
		//update position in database
		public function updateOrder() {
			$table = "test_image";
			
			$image["imgorder"] = $this->getOrder();
			
			$condition = array('id' => $this->getID());
			
			$this->getDBConn()->update($table, $image, $condition);
		}
		
		//creates a thumb of the original image
		public function createThumb() {
			$src_img = imagecreatefromjpeg($this->getLocation());
			
			$src_width = imageSX($src_img);
			$src_height = imageSY($src_img);
		
			$dst_width = 100;
			$dst_height = 80;
			
			$src_x = 0;
			$src_y = 0;
			
			$int_width = 0;
			$int_height = 0;
			
			$int_x = 0;
			$int_y = 0;

			if($src_width*0.8 > $src_height) {
				$int_width = $src_height/0.8;
				$int_height = $src_height;
				$src_x = $src_width - $int_width;
				$int_x = $src_x/2;
			}

			elseif($src_width*0.8 < $src_height) {
				$int_height = $src_width*0.8;
				$int_width = $src_width;
				$src_y = $src_height - $int_height;
				$int_y = $src_y/2;
			}
			
			$dst_img= ImageCreateTrueColor($dst_width, $dst_height);
			
			imagecopyresampled($dst_img, $src_img, 
				0,0, $int_x, $int_y, 
				$dst_width, $dst_height, 
				$int_width, $int_height);
			
			$name = $this->getLocation()."thumb";
			
			imagejpeg($dst_img, $name);
			
			$thumbID = $this->getDBConn()->insertLargeObject($name);
			
			$this->setThumbID($thumbID);
		}
	}
?>
<?php
	class Search {

		/* Properties */
	
		//dbconn
		private $_dbConn;
	
		//search string
		private $_search;
	
		//search results
		private $_result;
	
	
		/* Constructor */
		public function __construct() {
	
		}
	
	
		/* Accessors & Mutators */
		
		//database connection
		public function setDBConn($value) {
			$this->_dbConn = $value;
		}
	
		public function getDBConn() {
			return $this->_dbConn;
		}
	
		
		//search string
		public function setSearch($value) {
			$this->_search = $value;
		}
	
		public function getSearch() {
			return $this->_search;
		}
	
		
		//results
		public function setResult($value) {
			$this->_result = $value;
	
		}
	
		public function getResult() {
			return $this->_result;
		}
	
		/* Functions */
	
		//get keyword results
		public function searchImages() {
			$query = "SELECT test_image.id
				FROM test_image
				INNER JOIN test_album
				ON (test_image.albumid = test_album.id)
				
				WHERE (keywords LIKE ($1)
				OR caption LIKE ($1))
				AND test_album.private = false";
	
			$args = array("%".$this->getSearch()."%");
			
			$this->setResult($this->getDBConn()->query($query, $args));
		}	
		
		//get location results
		public function searchUser() {
			$query = "SELECT DISTINCT id, username
				FROM test_user
				WHERE city LIKE ($1)
				OR state LIKE ($1)
				OR zip LIKE ($1)
				OR email LIKE ($1)
				OR firstname LIKE ($1)
				OR lastname LIKE ($1)
				OR username LIKE ($1)
				OR phone LIKE($1)";
	
			$args = array("%".$this->getSearch()."%");
			
			$this->setResult($this->getDBConn()->query($query, $args));
		}	
	
		//get portfolio results
		public function searchPortfolio() {
			$query = "SELECT id, name
				FROM test_portfolio
				WHERE name LIKE ($1)
				OR description LIKE ($1)";
	
			$args = array("%".$this->getSearch()."%");
			
			$this->setResult($this->getDBConn()->query($query, $args));
		}	
		
		//get album results
		public function searchAlbum() {
			$query = "SELECT id, name
				FROM test_album
				WHERE (name LIKE ($1)
				OR description LIKE ($1))
				AND test_album.private = false";
	
	
			$args = array("%".$this->getSearch()."%");
			
			$this->setResult($this->getDBConn()->query($query, $args));
		}	
	}
?>

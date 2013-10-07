<?php
	
	include_once("Class.PhotoAlbum.php");

	class PrivatePhotoAlbum extends PhotoAlbum {

		/* Properties */

		//Is Private
		private $_private;

		//Password
		private $_password;

	
		/* Accessors & Mutators */

		//Private
		public function setPrivate($value) {
			$this->_private = $value;
		}

		public function getPrivate() {
			return $this->_private;
		}

		//Password
		public function setPassword($value) {
			$this->_password = $value;
		}

		public function getPassword() {
			return $this->_password;
		}

		
		/* Functions */

		//load album
		public function load() {
			$query = "SELECT name, description, private, password, portfolioid
				FROM test_album
				WHERE id = $1";
			$args = array($this->getID());

			$result = $this->getDBConn()->query($query, $args);

			if(!$result) {
				throw new Exception("ERROR: album does not exist");
			}
			else {
				$album = $result[0];
				
				$this->setName($album->name);
				$this->setDescription($album->description);
				$this->setPortfolioID($album->portfolioid);
				$this->setPrivate($album->private);
				$this->setPassword($album->password);
			}
		}

		//update album
		public function update() {
			
			$table = "test_album";

			$album["name"] = $this->getName();
			$album["description"] = $this->getDescription();
			$album["private"] = $this->getPrivate();
			
			$constraint = array("id" => $this->getID());
			
            $this->getDBConn()->update($table, $album, $constraint);
		}

		//save album info
		public function save() {

			$table = "test_album";

			$album["name"] = $this->getName(); 
			$album["description"] = $this->getDescription(); 
			$album["portfolioid"] = $this->getPortfolioID(); 
			$album["private"] = $this->getPrivate(); 
			$album["password"] = $this->getPassword();

			$this->getDBConn()->insert($table, $album);
		}
	
		//check the password entered
		public function checkPassword($value) {
			if($this->getPassword() == $value) {
				$_SESSION["private"] = $this->getID();
			}
			else {
				throw new Exception("ERROR: invalid password entered");
			}
		}
		
		//clear the private variable
		public function clearPassword() {
			$_SESSION["private"] = null;
		}
		
		//update password
		public function updatePassword() {
			$table = "test_album";
			
			$album["password"] = $this->getPassword();
			
			$constraint = array("id" => $this->getID());
			
			$this->getDBConn()->update($table, $album, $constraint);
		}
	}
?>

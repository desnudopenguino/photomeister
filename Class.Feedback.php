<?php
	class Feedback {
		
		/* Properties */
		
		//Errors
		private $_errors;
		
		//Success Message
		private $_successMessage;
	
		/* Accessors and Mutators */
		
		//Errors
		public function setErrors($value) {
			$this->_errors = $value;
		}
		
		public function getErrors() {
			return $this->_errors;
		}
		
		public function addError($value) {
			$this->_errors[] = $value;
		}
		
		//Success Message
		public function setSuccessMessage($value) {
			$this->_successMessage = $value;
		}
		
		public function getSuccessMessage() {
			return $this->_successMessage;
		}
	}
?>
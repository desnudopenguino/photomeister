<?php
	//Form tools to validate stuff easily
	
	function checkGet($name, $value) {
		$isset = false;
		if(isset($_GET[$name])) {
			if($_GET[$name] == $value) {
				$isset = true;
			}
		}
		
		return $isset;
	}
	
	function checkPost($name, $value) {
		$isset = false;
		if(isset($_POST[$name])) {
			if($_POST[$name] == $value) {
				$isset = true;
			}
		}
		
		return $isset;
	}
	
	function checkEmptyGetField($name) {
		$isset = false;
		
		if(isset($_GET[$name])) {
			$isset = $_GET[$name];
		}
		
		else {
			throw new exception("ERROR: Field ".$name." is empty");
		}
		
		return $isset;
	}
	
	function checkEmptyPostField($name) {
		$isset = false;
		
		if(isset($_POST[$name])) {
			if($_POST[$name] != null) {
			
				$isset = $_POST[$name];
			}
			
			else {
				throw new exception("ERROR: Field ".$name." is empty");
			}
		}
		return $isset;
	}
	
	function checkGetPassword($password1, $password2) {
		$same = false;
		if(isset($_GET[$password1]) && isset($_GET[$password2])) {
			if($_GET[$password1] == $_GET[$password2]) {
				$same = $_GET[$password1];
			}
			
			else {
				throw new exception("ERROR: passwords do not match");
			}
		}
		
		return $same;
	}
	
	function checkPostPassword($password1, $password2) {
		$same = false;
		if(isset($_POST[$password1]) && isset($_POST[$password2])) {
			if($_POST[$password1] == $_POST[$password2]) {
				$same = $_POST[$password1];
			}
			
			else {
				throw new exception("ERROR: passwords do not match");
			}
		}
		
		return $same;
	}
	
	
	function checkPostEmail($email) {
		$isEmail = false;
		if(isset($_POST[$email])) {
			$pattern = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
			if(preg_match($pattern, $_POST[$email])) {
				$isEmail = $_POST[$email];
			}
			
			else {
				throw new exception("ERROR: invalid email entered");
			}
		}
		
		return $isEmail;
	}
?>

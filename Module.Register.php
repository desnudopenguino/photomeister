<?php
	include_once("Class.User.Personal.php");
	include_once("Tool.Form.php");
	include_once("Class.StateList.php");
	
	if(checkPost("action","register")) {		
		$user = new ResumeUser();
		$user->setDBConn($DBCONN);
		$user->setSALT("blah");
		
		$noError = true;
		
		//Required fields
		try {
			$user->setName(checkEmptyPostField("user_name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			$user->setFirstName(checkEmptyPostField("first_name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			$user->setLastName(checkEmptyPostField("last_name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			checkEmptyPostField("password");
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			checkEmptyPostField("verify_password");
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		//password verification
		
		try {
			$user->setPassword(checkPostPassword("password","verify_password"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		if($_POST["email"] != null) {
			try {
				$user->setEmail(checkPostEmail("email"));
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
				$noError = false;
			}
		}
		$user->setCity($_POST["city"]);
		$user->setState($_POST["state"]);
		$user->setZip($_POST["zip"]);
		$user->setPhone($_POST["phone"]);
		
		if($noError) {
			try {
				$user->register();
				$FB->setSuccessMessage("You have successfully registered! now Login!");
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
			}
		}
	}
	
	//update for current user
	else if(checkPost("action","update")) {
		$noError = true;
		//Required fields
		$user = unserialize($_SESSION["user"]);
		try {
			$user->setFirstName(checkEmptyPostField("first_name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			$user->setLastName(checkEmptyPostField("last_name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			checkEmptyPostField("password");
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		try {
			checkEmptyPostField("verify_password");
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
	
		if($_POST["email"] != null) {
			try {
				$user->setEmail(checkPostEmail("email"));
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
				$noError = false;
			}
		}
		
		$user->setCity($_POST["city"]);
		$user->setState($_POST["state"]);
		$user->setZip($_POST["zip"]);
		$user->setPhone($_POST["phone"]);
		
		if($noError) {
			try {
				$user->update();
				$user->load();
				$FB->setSuccessMessage("You have successfully updated your personal information!");
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
			}
		}
	}
	
	//update password
	elseif(checkPost("action","change_password")) {

		$user = unserialize($_SESSION["user"]);

		$noError = true;
		try {
			$user->setPassword(checkPostPassword("password","verify_password"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		if($noError) {
			try {
				$user->changePassword();
				$user->load();
				$FB->setSuccessMessage("You have successfully updated your password!");
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
			}
		}
	}
	
	//delete user
	elseif(checkPost("action","delete")) {
		$user = unserialize($_SESSION["user"]);
		$user->delete();
		$user->logout();
	}
	
	//update logged in user
	if(isset($_SESSION["user"])) {
		$user = unserialize($_SESSION["user"]);		
?>
<h1>Update Personal Information:</h1>
<form method="post">
	<input type="hidden" name="action" value="update" />
	<ul>
		<li>
			<lable class="">User Name: </lable>
			<span><?php echo $user->getName(); ?></span>
		</li>
		
		<li>
			<lable class="">First Name:</lable>
			<input type="text" name="first_name" value=<?php echo $user->getFirstName(); ?> />
		</li>
		<li>
			<lable class="">Last Name:</lable>
			<input type="text" name="last_name" value=<?php echo $user->getLastName(); ?> />
		</li>
		<li>
			<lable class="">City:</lable>
			<input type="text" name="city" value=<?php echo $user->getCity(); ?> />
		</li>
		<li>
			<lable class="">State:</lable>
			<select name="state" >
				<option value="--">Select your state:</option>
<?php
		$stateList = new StateList();
		$stateList->setDBConn($DBCONN);
		$stateList->load();
		
		$states = $stateList->getStates();
		
		foreach($states as  $key => $state) {
			$selected = "";
			if($user->getState() == $key) {
				$selected = "selected";
			}
?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?>  >
					<?php echo $state; ?>
				</option>
<?php
		}

?>
			</select>
		</li>
		<li>
			<lable class="">Zip Code:</lable>
			<input type="text" name="zip" maxchar="9" size="9" value=<?php echo $user->getZip(); ?> />
		</li>
		<li>
			<lable class="">Phone #:</lable>
			<input type="text" name="phone" maxlength="13" size="13" value=<?php echo $user->getPhone(); ?> />
		</li>
		<li>
			<lable class="">E-Mail:</lable>
			<input type="text" name="email" value=<?php echo $user->getEmail(); ?> />
		</li>
		<li>
			<input class="txt" type="submit" value="Update" />
		</li>
	</ul>

</form>

<h2>Change Password</h2>
<form method="post">
	<input type="hidden" name="action" value="change_password" />
	<ul>
		<li>
			<lable class="">New Password:</lable>
			<input type="password" name="password" />
		</li>
		<li>
			<lable class="">Verify Password:</lable>
			<input type="password" name="verify_password" />
		</li>
		<li>
			<input class="txt" type="submit" value="Change Password" />
		</li>
	</ul>
</form>

<h2>Delete Account</h2>
<form method="post">
	<input type="hidden" name="action" value="delete" />
	<ul>
		<li><input type="submit" value="Delete Account" />
		</li>
	</ul>
</form>
<?php	
	}
	//create new user
	else {
?>
<h1>Register:</h1>
<form method="post">
	<input type="hidden" name="action" value="register" />
	<ul>
		<li>
			<lable class="">User Name:</lable>
			<input type="text" name="user_name" />
		</li>
		<li>
			<lable class="">Password:</lable>
			<input type="password" name="password" />
		</li>
		<li>
			<lable class="">Verify Password:</lable>
			<input type="password" name="verify_password" />
		</li>
		<li>
			<lable class="">First Name:</lable>
			<input type="text" name="first_name" />
		</li>
		<li>
			<lable class="">Last Name:</lable>
			<input type="text" name="last_name" />
		</li>
		<li>
			<lable class="">City:</lable>
			<input type="text" name="city" />
		</li>
		<li>
			<lable class="">State:</lable>
			<select name="state" >
				<option value="--">Select your state:</option>
<?php
		$stateList = new StateList();
		$stateList->setDBConn($DBCONN);
		$stateList->load();
		
		$states = $stateList->getStates();
		
		foreach($states as $key => $state) {
?>
				<option value=<?php echo $key; ?>  >
					<?php echo $state; ?>
				</option>
<?php
		}

?>
			</select>
		</li>
		<li>
			<lable class="">Zip Code:</lable>
			<input type="text" name="zip" maxlength="9" size="9"/>
		</li>
		<li>
			<lable class="">Phone #:</lable>
			<input type="text" name="phone" maxlength="13" size="13"/>
		</li>
		<li>
			<lable class="">E-Mail:</lable>
			<input type="text" name="email" />
		</li>
		<li>
			<input type="submit" value="Register" />
		</li>
	</ul>
</form>
<?php
	}
?>

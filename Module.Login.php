<?php
	session_start();
	
	include_once("Class.User.Personal.Resume.php");
	
	if(isset($_POST["action"])) {
		if($_POST["action"] == "login") {
			$user = new ResumeUser();
			$user->setName($_POST["user_name"]);
			$user->setSALT("blah");
			$user->setPassword($_POST["password"]);
			$user->setDBConn($DBCONN);
			try {
				$user->login();
				header("Location: index.php");
			}
			catch(Exception $e) {
				$FB->addError($e->getMessage());
			}
		}
		else if($_POST["action"] == "logout") {
			$user = unserialize($_SESSION["user"]); 
			$user->logout();
		}
	}
?>
<?php
	//login
	if(!isset($_SESSION["user"]) || $_SESSION["user"] == null) {
?>
<form method="post">
	<input type="hidden" name="action" value="login" />
	<input type="hidden" name="page" value="home" />
	<ul>
		<li>
			<lable>Username:</lable>
			<input type="text" name="user_name" />
		</li>
		<li>
				<lable>Password:</lable>
				<input type="password" name="password" />
		</li>
		<li>
				<input type="submit" value="Login" class="txt" />
				<span class="small">or <a href="?page=register">Register</a></span>
		</li>
	</ul>
</form>
<?php
	}
	//logout
	elseif(isset($_SESSION["user"])){
		$user = unserialize($_SESSION["user"]);
		
?>
<p>Welcome, <?php echo $user->getName(); ?>!</p> 
<form method="post">
	<input type="hidden" name="action" value="logout" />
	<input type="hidden" name="page" value="home" />
	<input class="txt" type="submit" value="Logout" />
</form>
<?php
	}
?>
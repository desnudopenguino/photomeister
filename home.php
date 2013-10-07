<?php
	//includes
	include_once("Class.User.Personal.php");
	include_once("Class.Portfolio.php");
	include_once("Tool.Form.php");
	
	$loggedIn = false;
	
	if(isset($_SESSION["user"])) {
		$loggedIn = true;
	}
?>
<h1>Welcome, 
<?php 
	if($loggedIn) {
		
		$user = unserialize($_SESSION["user"]);
		
		$name = $user->getFirstName(). " " .$user->getLastName();
		
		echo $name;
?>
.</h1>
<h2>My Portfolios: </h2>
<?php
	}
	
	else {
		echo "Guest";
?>
.</h1>
<?php
	}
		if($loggedIn) {
			$user->loadPortfolios();
		
			$portfolios = $user->getPortfolios();
			foreach($portfolios as &$portfolio) {
				$url = "?page=portfolio&id=".$portfolio->getID();
		
?>
<div class="item">
<p><a href=<?php echo $url ?> >
		<?php echo $portfolio->getName(); ?>
	</a>
</p>
</div>
<?php
			}
		}
		else {
?>
<p>Welcome to PhotoMeister! This is a new web baset portfolio site designed for photography
	professionals.  if you have some photos you want to share, please
	<a href="?page=register">register</a> and upload some!  If you're looking for certain
	photos or someone in your area to take some photos for you, search for them!</p>
<?php
		}
?>

<?php
	//Includes
	include_once("Class.User.Personal.Resume.php");
	include_once("Class.Portfolio.php");
	include_once("Tool.Form.php");
	include_once("Class.PhotoAlbum.php");
	
	/* Page Actions */
	
	//create a new portfolio
	if(checkPost("action","create")) {
		$noError = true;
		
		$portfolio = new Portfolio();
		$portfolio->setDBConn($DBCONN);
		$portfolio->setOwner($user->getID());
		
		try {
			$portfolio->setName(checkEmptyPostField("name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		$portfolio->setDescription($_POST["description"]);
		if($noError) {
			$portfolio->save();
			header("Location: index.php");
		}
		
	}
	
	//update a portfolio
	elseif(checkPost("action","update")) {
		$noError = true;
		
		$portfolio = new Portfolio();
		$portfolio->setID($_GET["id"]);
		$portfolio->setDBConn($DBCONN);
		$portfolio->setOwner($user->getID());

		try {
				$portfolio->setName(checkEmptyPostField("name"));
		}
		catch(Exception $e) {
				$FB->addError($e->getMessage());
				$noError = false;
		}

		$portfolio->setDescription($_POST["description"]);

		if($noError) {
				$FB->setSuccessMessage("Portfolio successfully updated!");
				$portfolio->update();
		}

	}
	
	//delete a portfolio
	elseif(checkPost("action","delete")) {
		$portfolio = new Portfolio();
		$portfolio->setID($_GET["id"]);
		$portfolio->setDBConn($DBCONN);
		$portfolio->load();
		$userID = $portfolio->getOwner();
		$portfolio->delete();
		
		header("Location: index.php");
	}
	
	
	/* Load Views */
	
	/*If user logged in & (portfolio)id is set
	 *Update Portfoio
	 */
	if(isset($_GET["id"]) && isset($_SESSION["user"])) {
		$user = unserialize($_SESSION["user"]);
		
		$portfolio = new Portfolio();
		$portfolio->setDBConn($DBCONN);
		$portfolio->setID($_GET["id"]);
		try {
			$portfolio->load();
		}
		catch(Exception $e){
			$FB->addError($e->getMessage());
			exit;
		}

		if($portfolio->getOwner() == $user->getID()) {
?>
<h1>Update Portfolio</h1>
<form method="post">
	<input type="hidden" name="action" value="update" />
	<ul>
			<li>
					<lable>Name:</lable>
					<input type="text" name="name" value="<?php echo $portfolio->getName(); ?>" />
			</li>
			<li>
					<lable>Description:</lable>
					<textarea name="description" ><?php echo $portfolio->getDescription(); ?></textarea>
			</li>
			<li>
					<input class="txt" type="submit" value="Update" />
			</li>
	</ul>
</form>
<form method="post">
	<input type="hidden" name="action" value="delete" />
	<input type="submit" value="Delete" />
</form>
<form method="get">
	<input type="hidden" name="page" value="album" />
    <input type="hidden" name="portfolio" value="<?php echo $portfolio->getID(); ?>" />
	<input class="txt" type="submit" value="Create A New Photo Album" />
</form>
<?php
		}
	}
	
	/*If user logged in
	 *Create new portfolio
	 */
	else if(isset($_SESSION["user"])) {
?>
<h1>New Portfolio</h1>
<form method="post">
	<input type="hidden" name="action" value="create" />
	<ul>
		<li>
			<lable>Name:</lable>
			<input type="text" name="name" />
		</li>
		<li>
			<lable>Description:</lable>
			<textarea name="description" ></textarea>
		</li>
		<li>
			<input class="txt" type="submit" value="Save" />
		</li>
	</ul>
</form>
<?php

	}
	
	/*If (portfolio)id is set
	 *Show portfolio
	 */
	if(isset($_GET["id"])) {
		$portfolio = new Portfolio();
		$portfolio->setDBConn($DBCONN);
		$portfolio->setID($_GET["id"]);

		try {
			$portfolio->load();
?>
<h1><?php echo $portfolio->getName(); ?></h1>
<p><?php echo $portfolio->getDescription(); ?></p>
<h3>Photo Albums:</h3>
<ul>
<?php
			$portfolio->loadPhotoAlbums();

			$albums = $portfolio->getAlbums();
			
			$visitor;
			if(isset($_SESSION["user"])) {
				$visitor = unserialize($_SESSION["user"]);
			}
			else {
				$visitor = new User();
				$visitor->setID(null);
			}
			foreach($albums as &$album) {
				$album->load();
				
				if(($album->getPrivate() == "f") ||
			 		($album->getPrivate() == "t" && $visitor->getID() == $album->getOwner())
				) {
					$url = "?page=album&id=".$album->getID();
?>
<div class="item">
	<p><a href=<?php echo $url; ?> >
			<?php echo $album->getName(); ?>
		</a>
	</p>
<?php
				}
			}
?>
</ul>
<?php
		}
		catch(Exception $e) {
				$FB->addError($e->getMessage());
		}
	}
?>
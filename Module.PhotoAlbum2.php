<?php
	include_once("Class.PhotoAlbum.Private.php");
	include_once("Tool.Form.php");
	
	/* Page Actions */
	
	//Save new album
	if(checkPost("action","save")) {
		$noError = true; 
		$album = new PrivatePhotoAlbum();
		$album->setDBConn($DBCONN);
		$album->setPortfolioID($_GET["portfolio"]);
		try {
			$album->setName(checkEmptyPostField("name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		$album->setDescription($_POST["description"]);
	 	if(checkPost("private","on")) {
				$album->setPrivate(true);
				if(checkEmptyPostField("password") && checkEmptyPostField("verify_password")) {
						$album->setPassword(checkPostPassword("password","verify_password"));
				}
		}
		else {
			$album->setPrivate(false);
		}

		if($noError) {
			$album->save();
			header("Location: ?page=portfolio&id=".$album->getPortfolioID());
		}
	}	
	
	//Update existing album
	else if(checkPost("action","update")) {
		$album = new PrivatePhotoAlbum();
		$album->setDBConn($DBCONN);
		$album->setID($_GET["id"]);
		
		$noError = true;
		try {
			$album->setName(checkEmptyPostField("name"));
		}
		catch(Exception $e) {
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		$album->setDescription($_POST["description"]);
		
		if(checkPost("private","on")) {
			$album->setPrivate(true);
		}	
		else {
			$album->setPrivate(false);
		}

		if($noError) {
			$album->update();
			$FB->setSuccessMessage("Photo album updated!");
		}
	}
	
	//change password
	else if(checkPost("action","password")) {
		$noError = true;
		$album = new PrivatePhotoAlbum();
		$album->setDBConn($DBCONN);
		$album->setID($_GET["id"]);
		
		try {
			if(checkEmptyPostField("password") && checkEmptyPostField("verify_password")) {
				$album->setPassword(checkPostPassword("password","verify_password"));
			}
		}
		catch (Exception $e){
			$FB->addError($e->getMessage());
			$noError = false;
		}
		
		if($noError) {
			$album->updatePassword();
			$FB->setSuccessMessage("Private password updated");
		}
		
	}
	
	//Delete album
	else if(checkPost("action","delete")) {
		$album = new PrivatePhotoAlbum();
		$album->setDBConn($DBCONN);
		$album->setID($_GET["id"]);
		$album->load();
		$portfolioID = $album->getPortfolioID();
		$album->delete();
		
		header("Location: ?page=portfolio&id=".$portfolioID);
	}
	

	//Enter private album
	else if(checkPost("action","enter_private")) {
		$album = new PrivatePhotoAlbum();
		$album->setDBConn($DBCONN);
		$album->setID($_GET["id"]);
		$album->load();
		$album->checkPassword($_POST["album_password"]); 
	}
	
	
	/* Load Views*/
	
	/*User logged in & (album)id is set
	 *Update album
	 */
	if(isset($_SESSION["user"]) && isset($_GET["id"])) {
		$user = unserialize($_SESSION["user"]);
		
		$album = new PrivatePhotoAlbum();
		$album->setID($_GET["id"]);
		$album->setDBConn($DBCONN);
		$album->load();
		
		if($user->getID() == $album->getOwner()) {
		
		$selected = "";
		
		if($album->getPrivate() == "t") {
			$selected = "checked";
		}
?>
<link rel="stylesheet" type="text/css" href="admin.css" />
<script type="text/javascript" src="ui.core.js">
</script>
<script type="text/javascript" src="ui.sortable.js">
</script>
<script type="text/javascript" src="photomeister.js">
</script>
<script type="text/javascript">
	$(function() {
		$("#imageList").sortable();
		$("#imageList").disableSelection();
	});
</script>
<h1>Update Photo Album: </h1>
<form method="post">
	<input type="hidden" name="action" value="update" />
	<ul>
		<li>
			<lable>Name:</lable/><input type="text" name="name" value="<?php echo $album->getName(); ?>" />
		</li>
		<li>
			<lable>Description</lable/><textarea name="description"><?php echo $album->getDescription(); ?></textarea>
		</li>
		<li>
			<lable>Private?</lable><input type="checkbox" name="private" <?php echo $selected; ?> />
		</li>
		<li>
			<input type="submit" value="Update" />
		</li>
	</ul>
</form>
<?php
	if($selected == "checked") {
?>
<form method="post">
	<input type="hidden" name="action" value="password">
	<ul>
		<li>
			<lable>Password:</lable><input type="password" name="password" />
		</li>
		<li>
			<lable>Verify Password:</lable><input type="password" name="verify_password" />
		</li>
		<li>
			<input type="submit" value="Update Password" />
		</li>
	</ul>
</form>
<?php
	}
?>
<form method="post">
	<input type="hidden" name="action" value="delete" />
	<input type="submit" value="Delete" />
</form>
<form method="get">
	<input type="hidden" name="page" value="image" />
	<input type="hidden" name="album" value="<?php echo $album->getID(); ?>" />
	<input class="txt" type="submit" value="Upload A photograph!" />
</form>
<button onclick="saveAlbumOrder()" >Save Album Order</button>
<?php
		}
	}
		
	/*User logged in & portfolio(id) is set
	 *Create New Album
	 */
	elseif(isset($_SESSION["user"]) && isset($_GET["portfolio"])) {
		$user = unserialize($_SESSION["user"]);
	
		$portfolio = new Portfolio();
		$portfolio->setID($_GET["portfolio"]);
		$portfolio->setDBConn($DBCONN);
		$portfolio->load();
		if($user->getID() == $portfolio->getOwner()) {		
?>
<h1>New Photo Album: </h1>
<form method="POST">
	<input type="hidden" name="action" value="save" />
	<ul>
		<li>
			<lable>Name:</lable/><input type="text" name="name" />
		</li>
		<li>
			<lable>Description:</lable/><textarea name="description"></textarea>
		</li>
 		<li>
			<lable>Private?</lable><input type="checkbox" name="private" />
		</li>
		<li>
			<lable>If private:</li><br />
		</li>
		<li>
			<lable>Password:</lable><input type="password" name="password" />
		</li>
		<li>
			<lable>Verify Password:</lable><input type="password" name="verify_password" />
		</li>

		<li>
			<input class="txt" type="submit" value="Save" />
		</li>

	</ul>
</form>
<?php
		}
	}
	
	//Load images in album
	if(isset($_GET["id"])) {
		$album = new PrivatePhotoAlbum();
		$album->setID($_GET["id"]);
		$album->setDBConn($DBCONN);
		$album->load();
		$album->setColumns(3);
	
		if(isset($_SESSION["private"]) && $_SESSION["private"] != $album->getID()) {
			$album->clearPassword();
		}
	
	
		$visitor;	
		if(isset($_SESSION["user"])) {
			$visitor = unserialize($_SESSION["user"]);
		}
		else {
			$visitor = new User();
			$visitor->setID(null);
		}

		if(($album->getPrivate() == "f") ||
			 ($album->getPrivate() == "t" && $visitor->getID() == $album->getOwner()) ||
			 ($album->getPrivate() == "t" && isset($_SESSION["private"]) && $_SESSION["private"] == $album->getID())
		) {
			$album->loadImages();
			$images = $album->getImages(); 		
?>
<div id="album">
	<h1><?php echo $album->getName(); ?></h1>
	<p><?php echo $album->getDescription(); ?></p>
	<div class="images">
		<ul id="imageList" >
<?php	
			foreach($images as &$image) {
				$image->load();
?>
			<li id="<?php echo $image->getID(); ?>">
				<a href="?page=image&id=<?php echo $image->getID(); ?>"><img src="thumb.php?id=<?php echo $image->getID(); ?>" /></a>
			</li>
<?php
			}//end foreach
?>
		</ul>
	</div>
</div>
<?php
		}
		else {
?>
<form method="post">
	<input type="hidden" name="action" value="enter_private" />
	<ul>
		<li>
			<lable>Album Password:</lable><input type="password" name="album_password" />
		</li>
		<li>
			<input type="submit" value="Submit" />
		</li>
	</ul>
</form>
<?php
		} 
	}//end isset id
?>		

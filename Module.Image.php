<?php
	include_once("Class.Image.php");
	include_once("Class.User.Personal.php");
	include_once("Class.PhotoAlbum.php");
	include_once("Tool.Form.php");
	
	//update image data
	if(checkPost("action","update")) {
		$image = new Image();
		$image->setID($_GET["id"]);
		$image->setDBConn($DBCONN);
		$image->load();
		$image->setKeywords($_POST["keywords"]);
		$image->setCaption($_POST["caption"]);
		$image->update();
	}
	
	if(checkPost("action","delete")) {
		$image = new Image();
		$image->setID($_GET["id"]);
		$image->setDBConn($DBCONN);
		$image->load();
		$albumID = $image->getAlbumID();
		$image->Delete();
		
		header("Location: ?page=album&id=".$albumID);
	}
	if(isset($_SESSION["user"])) {
		$user = unserialize($_SESSION["user"]);
		if(isset($_GET["album"])) {
			$album = new PrivatePhotoAlbum();
			$album->setID($_GET["album"]);
			$album->setDBConn($DBCONN);
			$album->load();
			if($user->getID() == $album->getOwner()) {
				try{
					include_once("Module.FileUpload.php");
				}
				catch(Exception $e) {
					$FB->addError($e->getMessage());
				}
			}
		}
		elseif(isset($_GET["id"])) {
			$image = new Image();
			$image->setID($_GET["id"]);
			$image->setDBConn($DBCONN);
			$image->load();

			if($user->getID() == $image->getOwner()) {
?>
<h1>Update Image:</h1>
<form method="post">
	<input type="hidden" name="action" value="update" />
	<ul>
		<li>
			<lable>Caption:</lable><textarea name="caption"><?php echo $image->getCaption(); ?></textarea>
		</li>
		<li>
			<lable>Keywords:</lable><textarea name="keywords"><?php echo $image->getKeywords(); ?></textarea>
		</li>
		<li>
			<lable></lable><input type="submit" value="Update" />
		</li>
	</ul>
</form>
<form method="post">
	<input type="hidden" name="action" value="delete" />
	<lable></lable><input type="submit" value="Delete" />
</form>
<?php
			}
		}
	}
	if(isset($_GET["id"])) {
		$image = new Image();	
		$image->setID($_GET["id"]);
		$image->setDBConn($DBCONN);
		$image->load();
		$url = "image.php?id=".$image->getID();
?>
<div class="image">
	<img src=<?php echo $url ?> width="600" />
	<p class="caption"><?php echo $image->getCaption(); ?></p>
</div>
<?php
	}
	elseif(isset($_GET["album"]) && !isset($_SESSION["user"])) {
		throw new Exception("ERROR: You are not logged in");
	}
?>
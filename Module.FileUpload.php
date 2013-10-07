<script language="javascript" src="photomeister.js">
</script>
<?php
	include_once("Class.Image.php");
	include_once("Class.Feedback.php");
	$id = 0;
	
	/* Action */
	if(checkPost("action","upload")) {
		foreach($_FILES as &$file) {
			$imageName = $file["tmp_name"];
			if(is_uploaded_file($imageName)) {
				$image = new Image();
				$image->setDBConn($DBCONN);
				$image->setAlbumID($_GET["album"]);
				$image->setLocation($imageName);
				$image->createThumb();
				$image->saveImage();
				
			}
			else {
				throw new Exception("ERROR: Image not uploaded");
			}
		}
		
		header("Location: ?page=album&id=".$image->getAlbumID());
	}
	
	if(isset($_GET["album"])) {
		$id = $_GET["album"];
	}
?>
<h1>Upload Images</h1>
<form enctype="multipart/form-data" method="post">
	<input type="hidden" name="action" value="upload" />
	<input type="hidden" name="albumid" value="<?php echo $id; ?>" />
	<ul id="files">
		<li>
			<input type="file" name="image1" />
		</li>
	</ul>
	<input type="button" onclick="javascript:loadUpload()" value="Add" />
	<input class="txt" type="submit" value="Upload" />
</form>
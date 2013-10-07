<?php
	include_once("db.inc");
	include_once("Class.Image.php");
	
	if(isset($_GET["id"])) {
		$id = $_GET["id"];
		$image = new Image();
		$image->setDBConn($DBCONN);
		$image->setID($id);
		$image->load();	
		$image->showThumb();
	}
?>
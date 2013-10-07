<?php
	include_once("Class.Image.php");
	include_once("db.inc");
	
	if(isset($_POST["images"])) {
		$images = split(',',$_POST["images"]);
		
		for ($i = 0; $i < count($images); $i++) {
			$j = $i + 1;
			$image = new Image();
			$image->setID($images[$i]);
			$image->setOrder($j);
			$image->setDBConn($DBCONN);
			$image->updateOrder();
		}
		
		var_dump($images);
	}
?>
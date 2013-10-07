<?php
	include_once("Class.Database.php");

	global $DBCONN;

	$DBCONN= new DatabaseManager("localhost", 
			"photomeisteruser",
			"BuckyTownsend",
			"photomeister",
			"5432");
			
	$DBCONN->setDatabaseType("postgres");
?>
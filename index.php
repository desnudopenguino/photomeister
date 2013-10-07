<?php
	include_once("db.inc"); 
	include_once("feedback.inc");
?>

<html>
	<head>
		<title>.:PhotoMeister:.</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<script language="javascript" src="jquery.js">
		
		</script>
	</head>
	<body>
		<div id="top">
			<img src="banner.jpg" />
		</div>
		<div id="login">
			<?php include_once("Module.Login.php"); ?>
		</div>
		<div id="navigation">
			<span class="left">
				<a href="index.php">home</a> |
<?php
	if(isset($_SESSION["user"])) {
?>
<a href="?page=register">personal</a> |
<a href="?page=resume">resume</a> |
<a href="?page=portfolio">new portfolio</a> |
<?php	
	}
?>
				<a href="?page=faq">faq</a> 
			</span>
			<span class="right"><?php include_once("Module.SearchBar.php"); ?></span>
		</div>
		<div id="breadcrumbs">
			<span class="small"><?php include_once("Tool.Trail.php"); ?></span>
		</div>
		<div id="content">
			<?php	
				if(isset($_GET["page"])) {
					switch($_GET["page"]) {

						case "register":
							include_once("Module.Register.php");
							break;
						
						case "portfolio":
							include_once("Module.Portfolio.php");
							break;
						
						case "album":
							try {
								include_once("Module.PhotoAlbum2.php");
							}
							catch(Exception $e) {
								$FB->addError($e->getMessage());
							}
							break;
							
						case "image":
							try {
								include_once("Module.Image.php");
							}
							catch(Exception $e) {
								$FB->addError($e->getMessage());
							}
							break; 
							
						case "search":
							include_once("Module.Search.php");
							break;
							
						case "user":
							try {
								include_once("Module.User.php");
							}
							catch(Exception $e) {
								$FB->addError($e->getMessage());
							}
							break;
							
						case "faq":
							include_once("Module.FAQ.php");
							break;
							
						case "login":
							include_once("Module.Login.php");
							break;
							
						case "resume":
							try {
								include_once("Module.Resume.php");
							}
							catch(Exception $e) {
								$FB->addError($e->getMessage());
							}
							break;
					}
				}
				else {
					include_once("home.php");
				}
			?>
			<div id="footer">
				<p>[ Photomeister is &copy; Adam Townsend.  All Images are &copy; their respective
					owners. ]</p>
			</div>
		</div>
		<?php include_once("Module.Feedback.php"); ?>
	</body>
</html>

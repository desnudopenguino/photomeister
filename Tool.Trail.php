<?php
	
	$crumbs = array();
	
	$crumbs[0] = array("index.php","Home");
	
	//if image page, get image id
	if(isset($_GET["page"])) {
		switch($_GET["page"]) {

			case "register":
				$crumbs[1] = array("?page=register","Register");
				break;
				
			case "faq":
				$crumbs[1] = array("?page=faq","FAQ");
				break;
				
			case "login":
				$crumbs[1] = array("?page=login","Login");
				break;
				
			case "search":
				$crumbs[1] = array("?page=search","Search");
				break;
			
			case "resume":
				$crumbs[1] = array("?page=resume","Resume");
				
			case "user":
				if(isset($_GET["id"])) {
					try {
						$user = new ResumeUser();
						$user->setID($_GET["id"]);
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
					
				}
				break;
			
			case "portfolio":
				if(isset($_GET["id"])) {
					try {
						$portfolio = new Portfolio();
						$portfolio->setID($_GET["id"]);
						$portfolio->setDBConn($DBCONN);
						$portfolio->load();
						
						$crumbs[2] = array("?page=portfolio&id=".$portfolio->getID(),$portfolio->getName());
						
						$user = new ResumeUser();
						$user->setID($portfolio->getOwner());
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				else {
					try {
						$user;
						if(isset($_SESSION["user"])) {
							$user = unserialize($_SESSION["user"]);
						}
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				break;
			
			case "album":
				if(isset($_GET["id"])) {
					try {
						$album = new PhotoAlbum();
						$album->setID($_GET["id"]);
						$album->setDBConn($DBCONN);
						$album->load();
						
						$crumbs[3] = array("?page=album&id=".$album->getID(),$album->getName());
						
						$portfolio = new Portfolio();
						$portfolio->setID($album->getPortfolioID());
						$portfolio->setDBConn($DBCONN);
						$portfolio->load();
						
						$crumbs[2] = array("?page=portfolio&id=".$portfolio->getID(),$portfolio->getName());
						
						$user = new ResumeUser();
						$user->setID($portfolio->getOwner());
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				
				else if(isset($_GET["portfolio"])) {
					try {
						$portfolio = new Portfolio();
						$portfolio->setID($_GET["portfolio"]);
						$portfolio->setDBConn($DBCONN);
						$portfolio->load();
						
						$crumbs[2] = array("?page=portfolio&id=".$portfolio->getID(),$portfolio->getName());
						
						$user = new ResumeUser();
						$user->setID($portfolio->getOwner());
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				break;
				
			case "image":
				if(isset($_GET["id"])) {
					try {
						$image = new Image();
						$image->setID($_GET["id"]);
						$image->setDBConn($DBCONN);
						$image->load();
						
						$crumbs[4] = array("?page=image&id=".$image->getID(), $image->getOrder());
						
						$album = new PhotoAlbum();
						$album->setID($image->getAlbumID());
						$album->setDBConn($DBCONN);
						$album->load();
						
						$crumbs[3] = array("?page=album&id=".$album->getID(),$album->getName());
						
						$portfolio = new Portfolio();
						$portfolio->setID($album->getPortfolioID());
						$portfolio->setDBConn($DBCONN);
						$portfolio->load();
						
						$crumbs[2] = array("?page=portfolio&id=".$portfolio->getID(),$portfolio->getName());
						
						$user = new ResumeUser();
						$user->setID($portfolio->getOwner());
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				elseif(isset($_GET["album"])) {
					try {
						$album = new PhotoAlbum();
						$album->setID($_GET["album"]);
						$album->setDBConn($DBCONN);
						$album->load();
						
						$crumbs[3] = array("?page=album&id=".$album->getID(),$album->getName());
						
						$portfolio = new Portfolio();
						$portfolio->setID($album->getPortfolioID());
						$portfolio->setDBConn($DBCONN);
						$portfolio->load();
						
						$crumbs[2] = array("?page=portfolio&id=".$portfolio->getID(),$portfolio->getName());
						
						$user = new ResumeUser();
						$user->setID($portfolio->getOwner());
						$user->setDBConn($DBCONN);
						$user->load();
						
						$crumbs[1] = array("?page=user&id=".$user->getID(), $user->getName());
					}
					catch(Exception $e) {
					
					}
				}
				break;
		}
	}
	
	//get 
	for($i = 0; $i < 5; $i++) {
		if(isset($crumbs[$i])) {
?>
<a href=<?php echo $crumbs[$i][0]; ?> ><?php echo $crumbs[$i][1]; ?></a> &gt; 
<?php
		}
	}
?>
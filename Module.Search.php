<?php
	include_once("Class.Search.php");
	include_once("Tool.Form.php");

	$search = new Search();	

	$search->setDBConn($DBCONN);

	if(isset($_GET["search"])) {
		$search->setSearch($_GET["search"]);
		
		$search->searchImages();
		
		$result = $search->getResult();
?>		
<h1>Results for <?php echo $search->getSearch(); ?></h1>
<?php

?>
<h3>Images: </h3>
<?php
		foreach($result as &$row) {
?>
<div class="item">
	<a href="?page=image&id=<?php echo $row->id; ?>" >
		<img src="image.php?id=<?php echo $row->id;  ?>"  width="200"/>		
	</a>
</div>
<?php
		}
		
		$search->searchAlbum();
		
		$result = $search->getResult();
		
?>
<h3>Albums: </h3>
<?php
		foreach($result as &$row) {
?>
<div class="item">
	<a href="?page=album&id=<?php echo $row->id; ?>" >
		<?php echo $row->name; ?>
	</a>
</div>
<?php
		}
?>
<h3>Portfolios: </h3>
<?php		
		$search->searchPortfolio();
		
		$result = $search->getResult();
		
		foreach($result as &$row) {
?>
<div class="item">
	<a href="?page=portfolio&id=<?php echo $row->id; ?>" >
		<?php echo $row->name; ?>
	</a>
</div>
<?php
		}
?>
<h3>Users: </h3>
<?php
		$search->searchUser();
		
		$result = $search->getResult();
		
		foreach($result as &$row) {
?>
<div class="item">
	<a href="?page=user&id=<?php echo $row->id; ?>" >
		<?php echo $row->username; ?>
	</a>
</div>
<?php
		}
	}
?>
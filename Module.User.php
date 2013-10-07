<?php
	include_once("Class.User.Personal.php");
	
	if(isset($_GET["id"])) {
		$user = new ResumeUser();
		$user->setID($_GET["id"]);
		$user->setDBConn($DBCONN);
		$user->load();
?>
<div id="portfolios">
<h3>Portfolios:</h3>
<ul>
<?php	
	$user->loadPortfolios();
	
	$portfolios = $user->getPortfolios();
	
	foreach($portfolios as &$portfolio) {
?>
<li>
	<a href="?page=portfolio&id=<?php echo $portfolio->getID(); ?>" ><?php echo $portfolio->getName(); ?></a>
</li>
<?php
	}

?>
</ul>
</div>
<h1>
	<?php echo $user->getName()."'s Information"; ?>
</h1>
<p>
	Name: <?php echo $user->getLastName().", ".$user->getFirstName() ?>
</p>
<p>
	Email: <?php echo $user->getEmail(); ?>
</p>
<p>
	Location: <?php echo $user->getCity(). ", ".$user->getState()." ".$user->getZip(); ?>
</p>
<p>
	Phone #: <?php echo $user->getPhone(); ?>
</p>

<?php
		$user->loadExperiences();
		
		$experiences = $user->getExperiences();
		if(!is_null($experiences)) {
?>
<h2>Experience:</h2>
<ol>
<?php
			$i = 1;
			foreach($experiences as $key => $exp) {
				$exp->load();
?>
<li>
	<p><?php echo $exp->getStartMonth(); ?>,<?php echo $exp->getStartYear(); ?> -
		<?php echo $exp->getEndMonth(); ?>,<?php echo $exp->getEndYear(); ?>
	</p>
	<p><?php echo $exp->getExperience(); ?></p>
</li>
<?php				
				$i++;
			}
?>
</ol>
<?php
		}
?>

<?php
		$user->loadSkills();
		
		$skills = $user->getSkills();
		if(!is_null($skills)) {
?>
<h2>Skills:</h2>
<ol>
<?php
			$i = 1;
			foreach($skills as &$skill) {
				$skill->load();
?>
	<li>
		<span class="skill"><?php echo $skill->getSkill(); ?>:</span>
		<span class="skill_time"><?php echo $skill->getExperience(); ?> years</span>
	</li>
<?php
				$i++;
			}
?>
</ol>
<?php
		}
		
		$user->loadEquipment();
		
		$equipment = $user->getEquipment();
		if(!is_null($equipment)) {
?>
<h2>Equipment:</h2>
<ol>
<?php
			$i = 1;
			foreach($equipment as $equip) {
				$equip->load();
?>
<li>
	<?php echo $equip->getEquipment(); ?>
</li>
<?php
				$i++;
			}
?>
</ol>
<?php
		}
	}
	else {
		throw new Exception("ERROR: User does not exist");
	}
?>
<?php
	include_once("Class.MonthList.php");
	include_once("Tool.Form.php");
	include_once("Class.Experience.php");
	include_once("Class.YearList.php");
	include_once("Class.Skill.php");
	include_once("Class.Equipment.php");

	//experience
	if(checkPost("action","experience")) {
		$user = unserialize($_SESSION["user"]);
		
		$exp = new Experience();
		$exp->setDBConn($DBCONN);
		$exp->setOwner($user->getID());
		$exp->setStartMonth($_POST["start_month"]);
		$exp->setStartYear($_POST["start_year"]);
		$exp->setEndMonth($_POST["end_month"]);
		$exp->setEndYear($_POST["end_year"]);
		$exp->setExperience($_POST["experience"]);
		
		$exp->save();
		$FB->setSuccessMessage("You have successfully added a new experience.");
	}

	if(checkPost("action","update_experience")) {
		$user = unserialize($_SESSION["user"]);
		
		$exp = new Experience();
		$exp->setDBConn($DBCONN);
		$exp->setStartMonth($_POST["start_month"]);
		$exp->setStartYear($_POST["start_year"]);
		$exp->setEndMonth($_POST["end_month"]);
		$exp->setEndYear($_POST["end_year"]);
		$exp->setExperience($_POST["experience"]);
		$exp->setID($_POST["experience_id"]);
		$exp->setOwner($user->getID());
		
		$exp->update();
		$FB->setSuccessMessage("You have successfully updated an experience.");
	}
	
	if(checkPost("action","delete_experience")) {
		
		$exp = new Experience();
		$exp->setDBConn($DBCONN);
		$exp->setID($_POST["experience_id"]);
				
		$exp->delete();
		$FB->setSuccessMessage("You have successfully deleted an experience.");
	}
	
	//skill
	if(checkPost("action","skill")) {
		$user = unserialize($_SESSION["user"]);
		
		$skill = new Skill();
		$skill->setDBConn($DBCONN);
		$skill->setOwner($user->getID());
		$skill->setSkill($_POST["skill"]);
		$skill->setExperience($_POST["skill_time"]);
		
		$skill->save();
		$FB->setSuccessMessage("You have successfully added a new skill.");
	}

	if(checkPost("action","update_skill")) {
		$user = unserialize($_SESSION["user"]);
		
		$skill = new Skill();
		$skill->setDBConn($DBCONN);
		$skill->setExperience($_POST["skill_time"]);
		$skill->setID($_POST["skill_id"]);
		$skill->setSkill($_POST["skill"]);
		
		$skill->update();
		$FB->setSuccessMessage("You have successfully updated a skill.");
	}
	
	if(checkPost("action","delete_skill")) {
		
		$skill = new Skill();
		$skill->setDBConn($DBCONN);
		$skill->setID($_POST["skill_id"]);
				
		$skill->delete();
		$FB->setSuccessMessage("You have successfully deleted a skill.");
	}
	
	//equipment
	if(checkPost("action","equipment")) {
		$user = unserialize($_SESSION["user"]);
		
		$equip = new Equipment();
		$equip->setDBConn($DBCONN);
		$equip->setOwner($user->getID());
		$equip->setEquipment($_POST["equipment"]);
		
		$equip->save();
		$FB->setSuccessMessage("You have successfully added new equipment.");
	}

	if(checkPost("action","update_equipment")) {
		$user = unserialize($_SESSION["user"]);
		
		$equip = new Equipment();
		$equip->setDBConn($DBCONN);
		$equip->setEquipment($_POST["equipment"]);
		$equip->setID($_POST["equipment_id"]);
		
		$equip->update();
		$FB->setSuccessMessage("You have successfully updated equipment");
	}
	
	if(checkPost("action","delete_equipment")) {
		
		$equip = new Equipment();
		$equip->setDBConn($DBCONN);
		$equip->setID($_POST["equipment_id"]);
				
		$equip->delete();
		$FB->setSuccessMessage("You have successfully deleted equipment.");
	}


	if(isset($_SESSION["user"])) {
		$user = unserialize($_SESSION["user"]);
?>
<h1>Resume</h1>
<h2>Experiences</h2>
<form method="post">
	<input type="hidden" name="action" value="experience" />
	<ul>
		<li>
			<lable>Start:</lable>
			<select name="start_month">
				<option value="---">Month:</option>
<?php
		$monthList = new MonthList();
		$monthList->setDBConn($DBCONN);
		$monthList->load();
		
		$months = $monthList->getMonths();
		
		foreach($months as $key => $month) {
?>
				<option value="<?php echo $key; ?>"><?php echo $month; ?></option>
<?php

		}

?>
			</select>
			<select name="start_year">
				<option value="0000">Year:</option>
<?php
		$yearList = new YearList();
		$yearList->setDBConn($DBCONN);
		$yearList->load();
		
		$years = $yearList->getYears();
		
		foreach($years as $key => $year) {
?>
				<option value="<?php echo $key; ?>"><?php echo $year; ?></option>
<?php
		}
?>			
			</select>
		</li>
		<li>
			<lable>End:</lable>
			<select name="end_month">
				<option value="---">Month:</option>
<?php
		$monthList = new MonthList();
		$monthList->setDBConn($DBCONN);
		$monthList->load();
		
		$months = $monthList->getMonths();
		
		foreach($months as $key => $month) {
?>
				<option value="<?php echo $key; ?>"><?php echo $month; ?></option>
<?php
		}
?>
			</select>
			<select name="end_year">
				<option value="0000">Year:</option>
<?php
		$yearList = new YearList();
		$yearList->setDBConn($DBCONN);
		$yearList->load();		
		$years = $yearList->getYears();
		
		foreach($years as $key => $year) {
?>
				<option value="<?php echo $key; ?>"><?php echo $year; ?></option>
<?php
		}
?>
			</select>
		</li>
		<li>
			<lable>Experience:</lable>
			<textarea name="experience"></textarea>
		</li>
		<li>
			<input type="submit" value="Save" />
		</li>
	</ul>
</form>
<?php
	//Update Experiences
	
	$user->loadExperiences();
	
	$experiences = $user->getExperiences();
	if(!is_null($experiences)) {
		foreach($experiences as $key => $exp) {
			$exp->load();
?>
<form method="post">
	<input type="hidden" name="action" value="update_experience" />
	<input type="hidden" name="experience_id" value="<?php echo $exp->getID(); ?>" />
	<ul>
		<li>
			<lable>Start:</lable>
			<select name="start_month">
				<option value="---">Month:</option>
<?php
			$monthList = new MonthList();
			$monthList->setDBConn($DBCONN);
			$monthList->load();
			
			$months = $monthList->getMonths();
			
			foreach($months as $key => $month) {
				$selected = "";
				if($exp->getStartMonth() == $key) {
					$selected = "selected";
				}
?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $month; ?></option>
<?php
			}
?>
			</select>
			<select name="start_year">
				<option value="0000">Year:</option>
<?php
			$yearList = new YearList();
			$yearList->setDBConn($DBCONN);
			$yearList->load();
			
			$years = $yearList->getYears();
			
			
			foreach($years as $key => $year) {
				$selected = "";
				if($exp->getStartYear() == $key) {
					$selected = "selected";
				}
?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
<?php
			}
?>
			</select>
		</li>
		<li>
			<lable>End:</lable>
			<select name="end_month">
				<option value="---">Month:</option>
<?php
			$monthList = new MonthList();
			$monthList->setDBConn($DBCONN);
			$monthList->load();
			
			$months = $monthList->getMonths();		
			
			foreach($months as $key => $month) {
				$selected = "";
				if($exp->getEndMonth() == $key) {
					$selected = "selected";
				}
?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $month; ?></option>
<?php
			}
?>
			</select>
			<select name="end_year">
				<option value="0000">Year:</option>
<?php
			$yearList = new YearList();
			$yearList->setDBConn($DBCONN);
			$yearList->load();
			
			$years = $yearList->getYears();
			
			foreach($years as $key => $year) {
				$selected = "";
				if($exp->getEndYear() == $key) {
					$selected = "selected";
				}
?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $year; ?></option>
<?php
			}
?>
			</select>
		</li>
		<li>
			<lable>Experience:</lable>
			<textarea name="experience"><?php echo $exp->getExperience(); ?></textarea>
		</li>
		<li>
			<input type="submit" value="Update" />
		</li>
	</ul>
</form>

<form method="post">
	<input type="hidden" name="action" value="delete_experience" />
	<input type="hidden" name="experience_id" value="<?php $exp->getID(); ?>" />
	<input type="submit" value="Delete" />
</form>
<?php
		}
	}
?>
<hr />
<h2>Skills</h2>
<form method="post">
	<input type="hidden" name="action" value="skill" />
	<ul>
		<li>
			<lable>Skill:</lable><input type="text" name="skill" />
		</li>
		<li>
			<lable>Time (years):</lable><input type="text" name="skill_time" />
		</li>
		<li>
			<input type="submit" value="Save" />
		</li>
	</ul>
</form>
<?php
		//Update skills
		
		$user->loadSkills();
		
		$skills = $user->getSkills();
		if(!is_null($skills)) {
			foreach($skills as &$skill) {
				$skill->load();		
?>
<form method="post">
	<input type="hidden" name="action" value="update_skill" />
	<input type="hidden" name="skill_id" value="<?php echo $skill->getID(); ?>" />
	<ul>
		<li>
			<lable>Skill:</lable><input type="text" name="skill" value="<?php echo $skill->getSkill(); ?>" />
		</li>
		<li>
			<lable>Time (years):</lable><input type="text" name="skill_time" value="<?php echo $skill->getExperience(); ?>" />
		</li>
		<li>
			<input type="submit" value="Update" />
		</li>
	</ul>
</form>
<form method="post">
	<input type="hidden" name="action" value="delete_skill" />
	<input type="hidden" name="skill_id" value="<?php echo $skill->getID(); ?>" />
	<input type="submit" value="Delete" />
</form>
<?php
			}
		}
?>
<hr />
<h2>Equipment</h2>
<form method="post">
	<input type="hidden" name="action" value="equipment" />
	<ul>
		<li>
			<lable>Name:</lable><input type="text" name="equipment" />
		</li>
		<li>
			<input type="submit" value="Save" />
		</li>
	</ul>
</form>
<?php
		//Update equipment
		
		$user->loadEquipment();
		
		$equipment = $user->getEquipment();
		if(!is_null($equipment)) {
			foreach($equipment as $equip) {
				$equip->load();
?>
<form method="post">
	<input type="hidden" name="action" value="update_equipment" />
	<input type="hidden" name="equipment_id" value="<?php echo $equip->getID(); ?>" />
	<ul>
		<li>
			<lable>Name:</lable><input type="text" name="equipment" value="<?php echo $equip->getEquipment(); ?>" />
		</li>
		<li>
			<input type="submit" value="Update" />
		</li>
	</ul>
</form>	
<form method="post">
	<input type="hidden" name="action" value="delete_equipment" />
	<input type="hidden" name="equipment_id" value="<?php echo $equip->getID(); ?>" />
	<input type="submit" value="Delete" />
</form>
<?php		
			}
		}
	}
	else {
		throw new Exception("ERROR: You are not logged in.");
	}
?>
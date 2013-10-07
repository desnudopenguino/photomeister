<?php

?>
<div id="feedback">
<?php
	if(!is_null($FB->getErrors())) {
		$errors = $FB->getErrors();
		foreach($errors as $error) {
?>
	<p class="error">
		<?php echo $error; ?>
	</p>
<?php
		}
	}
	else {
?>
<p class="success">
	<?php echo $FB->getSuccessMessage(); ?>
</p>
<?php
	}
	$FB->setErrors(null);
	$FB->setSuccessMessage(null);
?>
</div>
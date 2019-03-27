<div class="x_panel">
	<div class="x_title">
		<h2>Applicant Info</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="edit_application.php" method="post" class="form-horizontal form-label-left">

		<?php if($applicant) { ?>
		<p>Applicant: <strong><?php echo $applicant['name'] ?></strong><br />
			ID: <strong><?php echo $applicant['id'] ?></strong><br />
			Email: <strong><?php echo $applicant['email'] ?></strong><br />
			Phone: <strong><?php echo $applicant['phone'] ?></strong><br />
		</p>
		<input id="applicant_id" name="applicant_id" value="<?php echo $applicant['id'] ?>" type="hidden" />
		<?php } ?>
		</form>
	</div>
</div>

<div class="x_panel">
	<?php if($applicant) { ?>
	<div class="x_title">
		<h2>Edit Application</h2>
		<div class="clearfix"></div>
	</div>

	<form action="edit_application.php" method="post" class="form-area">
	<div class="x_content">
		<input id="applicant_id" name="applicant_id" value="<?php echo $applicant['id'] ?>" type="hidden" />
		<?php 
		$html->buildInput("preference[1]", "First Preference", 'select', $applicant['preference_1'], ['options' => $verticals]); 
		$html->buildInput("preference[2]", "Second Preference", 'select', $applicant['preference_2'], ['options' => $verticals]); 
		$html->buildInput("preference[3]", "Third Preference", 'select', $applicant['preference_3'], ['options' => $verticals]); 
		$html->buildInput("city_id", "Moving?", 'select', $applicant['moving_city_id'], ['options' => $all_cities]);
		$html->buildInput("action", "&nbsp", 'submit', 'Update', ['class' => 'btn btn-primary']);
		?>
	</div>
	<?php } ?>
	</form>
</div>

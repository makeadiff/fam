<div class="x_panel">
	<div class="x_title">
		<h2>Step 1. Find the Applicant</h2>
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
		<?php } elseif(!count($found_applicants)) { ?>
		<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="email" class="form-control has-feedback-left" placeholder="Email" />
			<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
        </div>

        OR <br />

		<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="phone" class="form-control has-feedback-left" placeholder="Phone" />
			<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
        </div>

		<?php $html->buildInput("action", "&nbsp", 'submit', 'Find Applicant', ['class' => 'btn btn-primary']); ?>
		<?php } elseif(count($found_applicants) > 1) { ?>
		<table class="table table-striped">
			<tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th></tr>
			<?php foreach($found_applicants as $user) { ?>
			<tr><td><?php echo $user['id']; ?></td><td><a href="edit_application.php?applicant_id=<?php echo $user['id'] ?>"><?php echo $user['name']; ?></a></td>
				<td><?php echo $user['phone']; ?></td><td><?php echo $user['email']; ?></td></tr>
			<?php } ?>
		</table>
		<?php } ?>
		</form>
	</div>
</div>

<div class="x_panel">
	<?php if($applicant) { ?>
	<div class="x_title">
		<h2>Step 2. Edit Application</h2>
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

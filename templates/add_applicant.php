<div class="x_panel">
	<div class="x_title">
		<h2>Add Applicant</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="add_applicant.php" method="post" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
		<h3>Step 1. Find the Applicant</h3>

		<?php if(!count($applicants)) { ?>
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
		<?php } elseif(count($applicants) > 1) { ?>
		<table class="table table-striped">
			<tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th></tr>
			<?php foreach($applicants as $user) { ?>
			<tr><td><?php echo $user['id']; ?></td><td><a href="add_applicant.php?applicant_id=<?php echo $user['id'] ?>"><?php echo $user['name']; ?></a></td>
				<td><?php echo $user['phone']; ?></td><td><?php echo $user['email']; ?></td></tr>
			<?php } ?>
		</table>
		<?php } elseif(count($applicants) == 1) { ?>
		<p>Applicant: <?php echo $applicant['name'] ?><br />
			ID: <?php echo $applicant['id'] ?><br />
			Email: <?php echo $applicant['email'] ?><br />
			Phone: <?php echo $applicant['phone'] ?><br />
		</p>
		<input id="applicant_id" name="applicant_id" value="<?php echo $applicant['id'] ?>" />
		<?php } ?>
		</form>
	</div>
</div>

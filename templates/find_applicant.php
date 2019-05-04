<div class="x_panel">

	<?php
		if(!$is_director) die("You don't have access to this view");
	?>
	
	<div class="x_title">
		<h2>Search Applicant</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="find_applicant.php" method="get" class="form-horizontal form-label-left">

		<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="name" class="form-control has-feedback-left" placeholder="Name" value="<?php echo i($QUERY, 'name'); ?>" />
			<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="id" class="form-control has-feedback-left" placeholder="User ID" value="<?php echo i($QUERY, 'id'); ?>" />
			<span class="fa fa-male form-control-feedback left" aria-hidden="true"></span>
        </div>

		<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="email" class="form-control has-feedback-left" placeholder="Email" value="<?php echo i($QUERY, 'email'); ?>" />
			<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
        </div>

		<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
			<input type="text" name="phone" class="form-control has-feedback-left" placeholder="Phone" value="<?php echo i($QUERY, 'phone'); ?>" />
			<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
        </div>

		<?php $html->buildInput("action", "&nbsp", 'submit', 'Find Applicant', ['class' => 'btn btn-primary']); ?>
		</form>
	</div>
</div>

<?php if($applicants) { ?>
<div class="x_panel">
	<div class="x_title">
		<h2>Results</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<?php
	$count = 0;
	require 'templates/partials/applicants_table.php';
	?>
	</div>
</div>
<?php } ?>

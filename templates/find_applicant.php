<div class="x_panel">
	<div class="x_title">
		<h2>Search Applicant</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="find_applicant.php" method="post" class="form-horizontal form-label-left">

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
		
	<table class="table table-striped">
	<tr><th>Count</th><th>Applicant</th><th>City</th><th>Current Roles</th><th>Applied For</th>
		<th>Evaluator</th>
		<?php if($is_director) { ?><th width="250">Evaluations</th><th>Action</th><?php } ?>
	</tr>
	<?php
	$count = 0;
	foreach($applicants as $u) {
		$count++; ?>
	<tr><td><?php echo $count ?></td>
		<!-- <td><?php echo $u['ugp'] ?></td> -->
		<td><?php echo $u['name'] ?><br />
			<?php echo $u['email'] ?><br />
		<?php echo $u['phone'] ?></td>
		<td><?php echo $u['city'] ?></td>
		<td><?php $groups = $common->getUserGroups($u['id']);
					$names = [];
					foreach($groups as $g) $names[] = $g['name'];
					echo implode(", ", $names); ?></td>
		<td><?php 
		$applied_groups_split = explode(",", $u['applied_groups']);
		echo "<ol><li>" . implode("</li><li>", $applied_groups_split) . "</li></ol>";
		?></td>
		<td><?php echo i($u, 'evaluator'); ?></td>
		<?php if($is_director) { ?>
			<td><!-- <a href="evaluate.php?stage_id=1&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-primary">Kindness Challenge</a> <?php showApplicantStatus($u['id'], 1); ?><br /> -->
				<a href="evaluate.php?stage_id=2&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-success">Applicant Feedback</a> <?php showApplicantStatus($u['id'], 2); ?><br />
				<a href="evaluate.php?stage_id=3&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-warning">Common Tasks</a> <?php showApplicantStatus($u['id'], 3); ?><br />
				<a href="evaluate_vertical.php?stage_id=5&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-default">Vertical Tasks</a> <?php showApplicantStatus($u['id'], 5); ?><br />
				<a href="evaluate_vertical.php?stage_id=4&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-info">Personal Interview</a> <?php showApplicantStatus($u['id'], 4); ?>
			</td>
			<td><a href="<?php echo getLink('applicants.php',[
												'action'		=>	'delete',
												'applicant_id'	=>	$u['id'],
												'city_id'		=>	$u['city_id']]); ?>" class="delete confirm icon">Delete</a><br /><br />
			</td>
		<?php } ?>
	</tr>
	<?php } ?>
	</table>
	</div>
</div>
<?php } ?>
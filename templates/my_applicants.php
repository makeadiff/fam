<div class="x_panel">

<div class="x_title">
<h2>Applicants Assigned to <?php echo $user['name'] ?></h2>
<div class="clearfix"></div>

</div>

<div class="x_content">
<p class="text-muted font-13 m-b-30">
  These are the applicants that you have to evaluate. If any more people are assigned to you, they'll automatically appear here.
</p>

<table class="table table-striped">
<tr><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Applied for Role...</th><th>Evaluate...</th></tr>
<?php foreach($applicants as $u) { ?>
<tr>
	<!-- <td><?php echo $u['id'] ?></td> -->
	<td><?php echo $u['name'] ?></td>
	<td><?php echo $u['email'] ?></td>
	<td><?php echo $u['phone'] ?></td>
	<td><?php echo $u['city'] ?></td>
	<td><?php
		$grps = explode(",", $u['groups']);
		$groups = [];
		foreach($grps as $group_id) $groups[] = $all_groups[$group_id];
		echo implode(',', $groups); ?></td>
	<td><!-- <a href="evaluate.php?stage_id=1&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-primary">Kindness Challenge</a> <?php showApplicantStatus($u['id'], 1); ?><br /> -->
	<a href="evaluate.php?stage_id=2&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-success">Applicant Feedback</a> <?php showApplicantStatus($u['id'], 2); ?><br />
	<a href="evaluate.php?stage_id=3&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-warning">Common Tasks</a> <?php showApplicantStatus($u['id'], 3); ?><br />
	<a href="evaluate_vertical.php?stage_id=5&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-default">Vertical Tasks</a> <?php showApplicantStatus($u['id'], 5); ?><br />
	<a href="evaluate.php?stage_id=4&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-info">Personal Interview</a> <?php showApplicantStatus($u['id'], 4); ?></td>
</tr>
<?php } ?>
</table>
</div>

</div>

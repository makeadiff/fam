<div class="x_panel">

<div class="x_title">
<h2>Evaluators</h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<table class="table table-striped">
<tr><th>Count</th><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>Applicants Assigned</th></tr>
<?php
$count = 0;
foreach($evaluators as $u) {
	$count++; ?>
<tr><td><?php echo $count ?></td>
	<td><?php echo $u['name'] ?></td>
	<td><?php echo $u['email'] ?></td>
	<td><?php echo $u['phone'] ?></td>
	<td><?php echo count($fam->getApplicants(['evaluator_id' => $u['id']])); ?></td>
</tr>
<?php } ?>
</table>

</div>
</div>
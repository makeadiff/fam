<div class="x_panel">

<div class="x_title">
<h2>All Applicants</h2>
<div class="clearfix"></div>
</div>


<div class="x_content">
<form action="applicants.php" method="post">
<?php $html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $all_groups, 'no_br' => 1]); ?> &nbsp;
<?php $html->buildInput("city_id", 'City ', 'select', $city_id, ['options' => $all_cities, 'no_br' => 1]); ?> &nbsp;
<?php if($is_director) { $html->buildInput("stage_id", 'Stage ', 'select', $stage_id, ['options' => $all_stages_input, 'no_br' => 1]); } ?> &nbsp;
<?php if($is_director) { echo $fam->statusSelectOption('status','Status ',$status); } ?> &nbsp;
<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>
</form>

<table class="table table-striped">
<tr><th>Count</th><th>Applicant</th><th>City</th><th>Current Roles</th><th>Applied For</th>p
	<?php if($group_id) { ?><th>Preference</th><?php } ?>
	<th>Evaluator</th>
	<?php if($is_director) { ?><th>Evaluations</th><th>Action</th><?php } ?>
</tr>
<?php
$count = ($applicants_pager->page - 1) * $applicants_pager->items_per_page;
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
	<td><?php echo $u['applied_groups']; ?></td>
	<?php if($group_id) { ?><td><?php echo $u['preference'] ?></td><?php } ?>
	<td><?php echo i($u, 'evaluator'); ?></td>
	<?php if($is_director) { ?>
		<td><a href="evaluate.php?stage_id=1&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-primary">Kindness Challenge</a> <?php showApplicantStatus($u['id'], 1); ?><br />
			<a href="evaluate.php?stage_id=2&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-success">Applicant Feedback</a> <?php showApplicantStatus($u['id'], 2); ?><br />
			<a href="evaluate.php?stage_id=3&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-warning">Common Tasks</a> <?php showApplicantStatus($u['id'], 3); ?><br />
			<a href="evaluate_vertical.php?stage_id=5&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-default">Vertical Tasks</a> <?php showApplicantStatus($u['id'], 5); ?><br />
			<a href="evaluate_vertical.php?stage_id=4&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-info">Personal Interview</a> <?php showApplicantStatus($u['id'], 4); ?></td>

		<td><a href="applicants.php?action=delete&ugp_id=<?php echo $u['ugp_id'] ?>&city_id=<?php echo $city_id ?>&group_id=<?php echo $group_id ?>"
									 class="delete confirm icon">Delete</a></td><?php } ?>
</tr>
<?php } ?>
</table>

<?php
if(isset($QUERY['city_id'])) $applicants_pager->opt['parameters']['city_id'] = $QUERY['city_id'];
if(isset($QUERY['group_id'])) $applicants_pager->opt['parameters']['group_id'] = $QUERY['group_id'];
$applicants_pager->link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%">%%TEXT%%</a>';
if($applicants_pager->total_pages > 1) {
	print $applicants_pager->getLink("first") . $applicants_pager->getLink("back");
	$applicants_pager->printPager();
	print $applicants_pager->getLink("next") . $applicants_pager->getLink("last") . '<br />';
}
if($applicants_pager->total_items) print $applicants_pager->getStatus();

?>
</div>
</div>

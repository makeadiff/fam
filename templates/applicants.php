<div class="x_panel">

<div class="x_title">
<h2>All Applicants</h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<form action="applicants.php" method="post">
<?php $html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $all_groups, 'no_br' => 1]); ?> &nbsp;
<?php $html->buildInput("city_id", 'City ', 'select', $city_id, ['options' => $all_cities, 'no_br' => 1]); ?> &nbsp;
<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>
</form>

<table class="table table-striped">
<tr><th>Count</th><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Current Roles</th><th>Applied For</th><th>Priority</th><th>Evaluator</th></tr>
<?php 
$count = 0;
foreach($applicants as $u) {
	$count++; ?>
<tr><!-- <td><?php echo $count ?></td> -->
	<td><?php echo $u['ugp'] ?></td>
	<td><?php echo $u['name'] ?></td>
	<td><?php echo $u['email'] ?></td>
	<td><?php echo $u['phone'] ?></td>
	<td><?php echo $u['city'] ?></td>
	<td><?php $groups = $common->getUserGroups($u['id']); 
				$names = [];
				foreach($groups as $g) $names[] = $g['name'];
				echo implode(", ", $names); ?></td>
	<td><?php echo i($all_groups, $u['group_id'], ''); ?></td>
	<td><?php echo $u['preference'] ?></td>
	<td><?php $evaluator = $fam->getEvaluator($u['id'], $group_id); 
			  if($evaluator) echo $evaluator['name'];
			  else echo 'None'; ?></td>
</tr>
<?php } ?>
</table>

<?php
$applicants_pager->opt['parameters']['city_id'] = $QUERY['city_id'];
$applicants_pager->opt['parameters']['group_id'] = $QUERY['group_id'];
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
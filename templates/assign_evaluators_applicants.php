<div class="x_panel">

<form action="assign_evaluators_applicants.php" method="post" class="form-area">

<div class="x_title">
<h2>Assign Evaluator to Applicants</h2>
<div class="clearfix"></div>

<?php $html->buildInput("evaluator_id", 'Evaluator', 'select', $evaluator_id, ['options' => $all_evaluators]); ?>
</div>

<div class="x_content">
<p class="text-muted font-13 m-b-30">
  Assign multiple applicants for the selected evaluator from the below list.
</p>

<?php $html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $all_groups, 'no_br' => 1]); ?> &nbsp;
<?php $html->buildInput("city_id", 'City ', 'select', $city_id, ['options' => $all_cities, 'no_br' => 1]); ?> &nbsp;
<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>

<table class="table table-striped">
<tr><th></th><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Current Roles</th><th>Applied For</th><th>Priority</th><th>Evaluator</th></tr>
<?php foreach($applicants as $u) { ?>
<tr><td><input type="checkbox" name="selected[]" value='<?php echo $u['id'] ?>' <?php
	if(in_array($u['id'], $existing_applicants)) echo 'checked';
?> /><input type="hidden" name="all_user_ids[]" value="<?php echo $u['id'] ?>" /></td>
	<!-- <td><?php echo $u['id'] ?></td> -->
	<td><?php echo $u['name'] ?></td>
	<td><?php echo $u['email'] ?></td>
	<td><?php echo $u['phone'] ?></td>
	<td><?php echo $u['city'] ?></td>
	<td><?php $groups = $common->getUserGroups($u['id']); 
				$names = [];
				foreach($groups as $g) $names[] = $g['name'];
				echo implode(", ", $names); ?></td>
	<td><?php 
		$grps = explode(",", $u['groups']); 
		$groups = [];
		foreach($grps as $group_id) $groups[] = $all_groups[$group_id];
		echo implode(',', $groups); ?></td>
	<td><?php echo $u['preference'] ?></td>
	<td><?php $evaluator = keyFormat($fam->getEvaluators($u['id'], $group_id), ['id', 'name']); 
			  if($evaluator) echo implode(", ", array_values($evaluator));
			  else echo 'None'; ?></td>
</tr>
<?php } ?>
</table>
<button class="btn btn-primary" value="Assign" name="action">Assign</button>
</div>
</form>

<?php if(!i($QUERY, 'show_unassigned')) { ?>
<a href="?show_unassigned=1">Show all Applicants who are not assigned to any Evaluator</a>
<?php } ?>
</div>
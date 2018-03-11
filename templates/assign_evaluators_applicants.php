<div class="x_panel">

<form action="" method="post" class="form-area">

<div class="x_title">
<h2>Assign Evaluator to Applicants</h2>
<div class="clearfix"></div>

<?php $html->buildInput("evaluator_id", 'Evaluator', 'select', $evaluator_id, ['options' => $all_evaluators]); ?>
</div>

<div class="x_content">
<p class="text-muted font-13 m-b-30">
  Assign multiple applicants for the selected evaluator from the below list. If you want to add evaluators who are not in the above list, talk to Binny.
</p>

<?php $html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $all_groups, 'no_br' => 1]); ?> &nbsp;
<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>

<table class="table table-striped">
<tr><th></th><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>Priority</th></tr>
<?php foreach($applicants as $u) { ?>
<tr><td><input type="checkbox" name="selected[]" value='<?php echo $u['id'] ?>' <?php
	if(in_array($u['id'], $existing_applicants)) echo 'checked';
?> /></td>
	<!-- <td><?php echo $u['id'] ?></td> -->
	<td><?php echo $u['name'] ?></td>
	<td><?php echo $u['email'] ?></td>
	<td><?php echo $u['phone'] ?></td>
	<td><?php echo $u['preference'] ?></td>
</tr>
<?php } ?>
</table>
<button class="btn btn-primary" value="Assign" name="action">Assign</button>
</div>
</form>

</div>
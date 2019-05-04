<div class="x_panel">

	<?php
		if(!$is_director) die("You don't have access to this view");
	?>

	<form action="" method="post" class="form-area">

		<div class="x_title">
			<h2>Assign Evaluators for <?php echo $vertical_name ?></h2>
			<div class="clearfix"></div>

		<?php $html->buildInput("group_id", 'Role', 'select', $group_id, ['options' => $all_groups]); ?>
		</div>

		<div class="x_content">
			<p class="text-muted font-13 m-b-30">
			  Select evaluators for your vertical from the below list. It has all the current strats and directors. If you want to add evaluators who are not in the list, talk to Binny.
			</p>

			<button class="btn btn-primary" value="Assign" name="action">Assign</button>
			<table class="table table-striped">
				<tr><th></th><!-- <th>ID</th> --><th>Name</th><th>Email</th><th>Phone</th><th>Groups</th></tr>
				<?php foreach($users as $u) { ?>
				<tr><td><input type="checkbox" name="selected[]" value='<?php echo $u['id'] ?>' <?php
					if(in_array($u['id'], $existing_evaluators)) echo 'checked';
				?> /></td>
					<!-- <td><?php echo $u['id'] ?></td> -->
					<td><?php echo $u['name'] ?></td>
					<td><?php echo $u['mad_email'] ?></td>
					<td><?php echo $u['phone'] ?></td>
					<td></td>
				</tr>
				<?php } ?>
			</table>
			<button class="btn btn-primary" value="Assign" name="action">Assign</button>
		</div>
	</form>
	
</div>

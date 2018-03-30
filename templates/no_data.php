<div class="x_panel">
	<div class="x_title">
		<h2>Select Stage Active Today...</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="" method="post" class="form-area">
			<label for="stage">Stage</label>
			<select name="stage">
			<?php foreach ($all_stages as $stage) { ?>
				<optgroup label="<?php echo $stage['name']; ?>">
					<?php
					$all_categories = $fam->getCategories($stage['id']);
					if(!$all_categories) {
						?><option value="<?php echo $stage['id'] ?>-0" <?php if($active_stage_id == $stage['id'] . '-0') echo "selected='selected'"; ?>>Comment</option>
					<?php } else {
						foreach ($all_categories as $category) { ?>
						<option value="<?php echo $stage['id'] ?>-<?php echo $category['id'] ?>" <?php if($active_stage_id == $stage['id'] . '-' . $category['id']) echo "selected='selected'"; ?>
							><?php echo $category['name'] ?></option>
					<?php }
					} ?>
				</optgroup>
			<?php } ?>
			</select><br />

			<?php $html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $verticals]); ?>
			<input type="submit" value="Show" name="action" class="btn btn-primary" />
		</form>
	</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Data not entered for <strong><?php echo $category_name['name'] ?></strong></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<table class="table table-striped">
			<tr><th>Evaluator</th><th>Assigned</th><th>Data Entered For</th><th>Not Entered</th></tr>
			<?php foreach ($all_evaluators as $evaluator_id => $info) { ?>
			<tr>
				<td><?php echo $all_users[$evaluator_id]['name'] ?></td>
				<td><?php echo $info['assigned'] ?></td>
				<td><?php echo $info['data_entered'] ?></td>
				<td><?php if($info['assigned'] - $info['data_entered']) { ?>
					<a href="no_data_by_evaluator.php?evaluator_id=<?php echo $evaluator_id ?>&stage=<?php echo $active_stage_id . '-' . $active_category_id?>"
						style="text-decoration: underline;"><?php echo $info['assigned'] - $info['data_entered'] ?></a>
					<?php } else echo 'None'; ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>

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
						?><option value="<?php echo $stage['id'] ?>-0" <?php if(i($QUERY, 'stage') == $stage['id'] . '-0') echo "selected='selected'"; ?>>Comment</option>
					<?php } else {
						foreach ($all_categories as $category) { ?>
						<option value="<?php echo $stage['id'] ?>-<?php echo $category['id'] ?>" <?php if(i($QUERY, 'stage') == $stage['id'] . '-' . $category['id']) echo "selected='selected'"; ?>
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
			<tr><th>Evaluator</th><th>Applicant</th><th>City</th><th>Vertical</th></tr>
			<?php foreach ($applicants_whos_data_is_not_entered as $applicant_id => $applicant) { ?>
			<tr>
				<td><?php echo $all_users[$applicant['evaluator_id']]['name'] ?></td>
				<td><?php echo $all_users[$applicant_id]['name'] ?></td>
				<td><?php echo $all_users[$applicant_id]['city'] ?></td>
				<td><?php echo $verticals[$all_applicants[$applicant_id]['group_id']] ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>

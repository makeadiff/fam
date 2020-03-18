<?php if(!empty($applicants)) { ?>
	<table class="table table-striped">
	<tr>
		<th>Count</th>
		<th>User</th>
		<th>City</th>
		<th>Status</th>
	<?php if(isset($applicants[0]['groups'])) { ?>
		<th>Applied Role (Evaluator)</th>
	<?php } ?>
	<?php if(isset($group_id) and $group_id)  { ?>
		<th>Preference</th>
	<?php } ?>
		<th>Action</th>
	</tr>
<?php } else { ?>
	<p><strong>No Applicants Assigned to You.</strong></p>
<?php }

if(!isset($count)) $count = 0;
foreach($applicants as $u) {
	$count++; ?>
	<tr class="<?php echo $u['status']; ?>">
		<!-- Serial Number -->
		<td><?php echo $count ?></td>
		<!-- Applicant Details -->
		<td>
				<?php echo $u['name'] ?><br />
				<?php echo $u['email'] ?><br />
				<?php echo $u['phone'] ?>
		</td>
		<!-- Applicant City -->
		<td><?php echo $u['city']; ?></td>
		<!-- User Status -->
		<td><?php echo ucwords($u['user_type']); ?></td>
		<!-- Applicant Applied Profiles -->
	<?php if(isset($u['groups'])) { ?>
		<td>
			<?php
				$applied_groups_split = explode(",", $u['groups']);
				$evaluators = keyFormat($fam->getEvaluatorsByGroup($u['id']), ['group_id', 'name']);
				$application_info = keyFormat($fam->getApplicationInfo($u['id']), 'group_id');

				echo "<ol>";
				foreach($applied_groups_split as $this_group_id) {
					echo "<li>" . $verticals[$this_group_id];
					if(isset($evaluators[$this_group_id])) echo " (" . $evaluators[$this_group_id] . ")";
					if(isset($application_info[$this_group_id]) && isset($stage_id) && $stage_id==0 && $stage_id==3 && $stage_id==2 && $stage_id==1 && $stage_id==6){
					// DO Nothing
					}
					elseif(isset($application_info[$this_group_id]) && isset($stage_id)){
						echo " "; showApplicantStatus($u['id'],$stage_id,$this_group_id);
					}
					echo "</li>";
				}
				echo "</ol>";
			?>
		</td>
	<?php } ?>
	<?php if(isset($group_id) and $group_id) { ?>
		<!-- Preference Number for Selected Group -->
		<td><?php echo $u['preference'] ?></td>
	<?php } ?>
		<td>
			<a href="add_applicant.php?user_id=<?php echo $u['id']; ?>&action=Add"><button class="btn btn-primary">Add Applicant</button></a>
		</td>
	</tr>
<?php } ?>
</table>

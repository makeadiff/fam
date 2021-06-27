<?php
	if(!isset($overall_status)){
		$overall_status = '';
	}
?>


<?php if(!empty($applicants)) { ?>
	<table class="table table-striped">
	<tr>
		<th>Count</th>
		<th>Applicant</th>
		<th>City</th>
		<th>Current Roles</th>
	<?php if(isset($applicants[0]['groups'])) { ?>
		<th>Applied Role (Evaluator)</th>
	<?php } ?>
	<?php if(isset($group_id) and $group_id)  { ?>
		<th>Preference</th>
	<?php } ?>
	<?php if($is_director) { ?>
		<th width="250">Evaluations</th>
		<th>Action</th>
	<?php } ?>
	</tr>
<?php } else { ?>
	<p><strong>No Applicants Assigned to You.</strong></p>
<?php }

if(!isset($count)) $count = 0;
foreach($applicants as $u) {
	$count++; ?>
	<tr>
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
		<!-- Applicant Current Roles -->
		<td>
			<?php
				$groups = $common->getUserGroups($u['id']);
				$names = [];
				foreach($groups as $g) $names[] = $g['name'];
					echo implode(", ", $names);
			?>
		</td>
		<!-- Applicant Applied Profiles -->
	<?php if(isset($u['groups'])) { ?>
		<td>
			<?php
				if($overall_status!='rejected'){
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
				}

				if($fam->isRejected($u['id'])){
					echo "<span class='badge shortlisted'>Mentor Shortlist</span><br/>";
					$rejected_applications = $fam->getApplications($u['id'],'rejected');
					if($rejected_applications){
						echo "<br/><strong>Rejected Applications:</strong>";
						echo "<ol>";
						foreach ($rejected_applications as $key => $value) {
							echo "<li>".$verticals[$value['group_id']]."</li>";
						}
						echo "</ol>";
					}
				}
			?>
		</td>
	<?php } ?>
	<?php if(isset($group_id) and $group_id) { ?>
		<!-- Preference Number for Selected Group -->
		<td><?php echo $u['preference'] ?></td>
	<?php } ?>
		<!-- Application Stages -->
		<td>
			<a href="evaluate.php?stage_id=1&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-dark">3 Day Challenge</a> <?php showApplicantStatus($u['id'], 1); ?><br />
			<a href="evaluate.php?stage_id=6&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-primary">Volunteer Participation</a> <?php showApplicantStatus($u['id'], 6); ?><br />
			<a href="evaluate.php?stage_id=2&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-success">Applicant Feedback</a> <?php showApplicantStatus($u['id'], 2); ?><br />
			<a href="evaluate.php?stage_id=3&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-warning">Common Tasks</a> <?php showApplicantStatus($u['id'], 3); ?><br />
			<a href="evaluate_vertical.php?stage_id=5&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-default">Vertical Tasks</a> <?php showApplicantStatus($u['id'], 5); ?><br />
			<a href="evaluate_vertical.php?stage_id=4&applicant_id=<?php echo $u['id'] ?>" class="btn btn-xs btn-info">Personal Interview</a> <?php showApplicantStatus($u['id'], 4); ?><br />
			<?php if(!empty($u['achivement_record'])) { ?>
				<a href="<?php echo $u['achivement_record'] ?>" target="_blank" class="btn btn-xs btn-primary">Achivement Record</a>
			<?php } ?>
		</td>
		<?php if($is_director) { ?>
		<td><a href="<?php echo getLink('applicants.php',[
											'action'		=>	'delete',
											'applicant_id'	=>	$u['id'],
											'ugp_id'		=>	$u['ugp_id'],
											'city_id'		=>	$city_id,
											'group_id'		=>	$group_id]); ?>" class="delete confirm icon" title="Delete this application">Delete</a><br />
			<a href="<?php echo getLink('edit_application.php',[
											'action'		=>	'edit_application',
											'applicant_id'	=>	$u['id']]); ?>" class="edit icon" title="Edit Application">Edit</a><br /><br />
		</td>
		<?php } ?>
	</tr>
<?php } ?>
</table>

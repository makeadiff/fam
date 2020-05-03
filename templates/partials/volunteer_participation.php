<div class="x_panel">
	<div class="x_title">
		<h2>Parameters</h2>
		<div class="clearfix"></div>
	</div>

 	<div class="x_content">
 		<table class="table table-striped">
			<tr><th>Parameter</th><th>Value in Database</th><th>Volunteer Input</th><th>Updated Value</th></tr>
	<?php
$participation_content = load($config['site_url'] . 'apps/participation-profile-update/?user_id=' . $applicant_id);

// $participation_content = '{"credit":"-2","user_credit":"-2","vertical":"Ed Support","training":"","cpp":"signed","city_circle":"0\/0","shelter_sensitisation":"1\/1"}';
$participation = json_decode($participation_content, true);

if($participation)
foreach ($participation as $key => $data) {
	if($key == 'name' or $key == 'vertical' or $key == 'participation_additional_consideration') continue;

	$db_value = i($data, 'madapp', false);
	$correct  = i($data, 'is_correct');
	$user_value=i($data, 'user_updated');

	if($key == 'cpp') {
		$title = 'Child Protection Policy Signed';
	} else $title = ucfirst(format($key));
	if($db_value === false) continue;

	echo "<tr>";
	echo "<td>" . $title . "</td>";
	echo "<td>" . $db_value . "</td>";
	echo "<td>" . $correct . "</td>";
	echo "<td>" . $user_value . "</td>";
	echo "</tr>";
}
?>
		</table>

		<p><strong>Volunteer Comments...</strong></p>
		<p><?php echo nl2br(i($participation, 'participation_additional_consideration')); ?></p>
	</div>
</div>

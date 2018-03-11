<?php
require 'common.php';

// :TODO: 
// Validation - make sure at least one applicant is chooser, and evaluator is chosen.
// Some way to show that some people have been assigned to other evaluators already

$strats = $common->getUsers(['group_id' => 350]);
$directors = $common->getUsers(['group_type' => 'national']);
$evaluators = array_merge($directors, $strats);
$all_evaluators = keyFormat($evaluators, ['id', 'name']);
$all_evaluators[0] = 'Select...';

$vertical_id = 0; // If 0, show all groups. :TODO: Get the vertical Id of the current user. 
$groups_in_vertical = $fam->getGroups($vertical_id);
$all_groups = keyFormat($groups_in_vertical, ['id', 'name']);

$group_id = i($QUERY, 'group_id', $groups_in_vertical[0]['id']);
$evaluator_id = i($QUERY, 'evaluator_id', 0);

$applicants = $fam->getApplicants(['group_id' => $group_id]);

if(i($QUERY, 'action') == 'Assign' and $evaluator_id) {
	$assignments = $QUERY['selected'];

	// Clear existing evaluator assignemts.
	$sql->remove("FAM_Evaluator", [
		'evaluator_id' => $evaluator_id
	]);

	// Add the newly marked ones.
	foreach ($assignments as $user_id) {
		$sql->insert("FAM_Evaluator", [
			'evaluator_id' => $evaluator_id,
			'group_id'	=> $group_id,
			'user_id'	=> $user_id,
			'added_on'	=> 'NOW()'
		]);
	}
}

$existing_applicants = colFormat($fam->getApplicants(['evaluator_id' => $evaluator_id]));

render();

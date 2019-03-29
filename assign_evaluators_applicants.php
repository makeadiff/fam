<?php
require 'common.php';

// :TODO:
// Validation - make sure at least one applicant is chooser, and evaluator is chosen.

$evaluators = $common->getUsers(['group_id' => 382]);
$all_evaluators = keyFormat($evaluators, ['id', 'name']);
$all_evaluators[0] = 'Select...';

$all_groups = $verticals;
$all_groups[0] = 'Any';

$all_stages = $fam->getStages();
$all_stages_input = [];
foreach ($all_stages as $key => $stages) {
	$all_stages_input[$stages['id']] = $stages['name'];
}
$all_stages_input[0] = 'Any';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$group_id = i($QUERY, 'group_id', 2);
$city_id = i($QUERY, 'city_id');
$evaluator_id = i($QUERY, 'evaluator_id', 0);
$stage_id = i($QUERY, 'stage_id', 0);
$status = i($QUERY, 'status', '0');
$preference = i($QUERY, 'preference', 0);

if(i($QUERY, 'show_unassigned')) {
	$applicants = $fam->getUnassignedApplicants();
	$group_id = 0;
} else {
	$applicants = $fam->getApplicants(['group_id' => $group_id, 'preference'=> $preference, 'city_id' => $city_id,'stage_id'=>$stage_id,'status'=>$status]);
}

if(i($QUERY, 'action') == 'Assign' and $evaluator_id) {
	$assignments = $QUERY['selected'];

	// Remove earlier assignments.
	$amoung_users = $QUERY['all_user_ids'];
	$fam->resetAssignments($evaluator_id, $amoung_users);

	// Add the newly marked ones.
	foreach ($assignments as $user_id) {
		$fam->assignEvaluators($user_id, $evaluator_id, $group_id);
	}
}

$existing_applicants = colFormat($fam->getApplicants(['evaluator_id' => $evaluator_id]));

render();

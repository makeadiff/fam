<?php
require 'common.php';

// :TODO: 
// Validation - make sure at least one applicant is chooser, and evaluator is chosen.

$strats = $common->getUsers(['group_id' => 381]);
$directors = $common->getUsers(['group_type' => 'national']);
$evaluators = array_merge($directors, $strats);
$all_evaluators = keyFormat($evaluators, ['id', 'name']);
$all_evaluators[0] = 'Select...';

$vertical_id = 0; // If 0, show all groups. :TODO: Get the vertical Id of the current user. 
$groups_in_vertical = $fam->getGroups($vertical_id);
$all_groups = keyFormat($groups_in_vertical, ['id', 'name']);
$all_groups[0] = 'Any';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$group_id = i($QUERY, 'group_id', $groups_in_vertical[0]['id']);
$city_id = i($QUERY, 'city_id');
$evaluator_id = i($QUERY, 'evaluator_id', 0);

if(i($QUERY, 'show_unassigned')) {
	$applicants = $fam->getUnassignedApplicants();	
	$group_id = 0;
} else {
	$applicants = $fam->getApplicants(['group_id' => $group_id, 'city_id' => $city_id]);
}

if(i($QUERY, 'action') == 'Assign' and $evaluator_id) {
	$assignments = $QUERY['selected'];

	// Add the newly marked ones.
	foreach ($assignments as $user_id) {
		$sql->update("FAM_UserGroupPreference", [
				'evaluator_id' => $evaluator_id,
			], [
				'group_id'	=> $group_id,
				'user_id'	=> $user_id,
			]);
	}
}

$existing_applicants = colFormat($fam->getApplicants(['evaluator_id' => $evaluator_id]));

render();

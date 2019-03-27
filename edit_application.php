<?php
require 'common.php';

$action = i($QUERY, 'action');
$applicant_id = i($QUERY, 'applicant_id');
$found_applicants = [];
$applicant = false;

$verticals[0] = 'None';
$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Not Moving';

if($action == 'edit_application' and $applicant_id) {
	$found_applicants = $fam->findUser(['id' => $applicant_id], ' AND ', true);
	if(count($found_applicants) == 1) $applicant = $found_applicants[0];

} elseif($action == 'Update') {
	$sql->update("FAM_UserGroupPreference", ['status' => 'withdrawn'], ['user_id' => $applicant_id, 'year' => $year]);

	$preferences = $QUERY['preference'];

	foreach ($preferences as $preference => $group_id) {
		if(!$group_id) continue;
		
		$sql->insert("FAM_UserGroupPreference", [
			'user_id'	=> $applicant_id,
			'group_id'	=> $group_id,
			'preference'=> $preference,
			'city_id'	=> $QUERY['city_id'],
			'added_on'	=> 'NOW()',
			'year'		=> $year
		]);
	}

	$QUERY['success'] = "Updated Application";
}

if($applicant) {
	$preferences = $fam->getApplications($applicant['id']);

	// Init
	$applicant['preference_1'] = 0;
	$applicant['preference_2'] = 0;
	$applicant['preference_3'] = 0;
	$applicant['moving_city_id'] = 0;

	if($preferences) {
		
		foreach ($preferences as $pref) {
			$applicant['preference_' . $pref['preference']] = $pref['group_id'];
		}

		$applicant['moving_city_id'] = $preferences[0]['city_id'];
	}
}

render();

<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer'");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE preference=1");

$verticals = [
	'2'		=> "City Team Lead",
	'19'	=> "Ed Support",
	'378'	=> "Aftercare",
	'272'	=> "Transition",
	'370'	=> "Fundraising",
	'269'	=> "Shelter Ops",
	'4'		=> "Shelter Support",
	'5'		=> "Human Capital",
	'15'	=> "Finance",
	'11'	=> "Campaigns",
	'375'	=> "Foundational",
];

// Data source - https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629
$requirements = getRequirementFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vTf7uqEdn1CWZjwG8YALAS52jGVMABAfo1Xpb6YR3g69jSHir_govSZvFz_F_J_ACX1W50byaNE0ibS/pub?output=csv");

$applications = [];
$selected = [];

foreach ($all_cities as $city_id => $city_name) {
	// $applications[$city_id] = array_combine(array_keys($verticals), array_fill(0, count($verticals), 0)); // Create init values.

	$applications[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
		INNER JOIN User U ON UGP.user_id=U.id 
		WHERE preference=1 AND U.city_id=$city_id
		GROUP BY UGP.group_id");
	
	// $selected[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	// 	INNER JOIN User U ON UGP.user_id=U.id 
	// 	WHERE U.city_id=$city_id AND preference=1 AND UGP.status='selected'
	// 	GROUP BY UGP.group_id");
}

// $template->addResource("js/library/DataTables/datatables.min.css", 'css');
// $template->addResource("js/library/DataTables/datatables.min.js", 'js');

$multiplication_factor = 3;
render();

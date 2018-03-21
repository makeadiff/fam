<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'All';
$city_id = i($QUERY, 'city_id', 0);

$city_check = '';
if($city_id) {
	$city_check = "U.city_id=$city_id AND";
}

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer' AND city_id=$city_id");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE $city_check preference=1");
$fellowship_applications = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE $city_check  preference=1 AND UGP.group_id IN (SELECT id FROM `Group` WHERE type='fellow' OR type='strat')");

$verticals = [
	'2'		=> "City Team Lead",
	'19'	=> "Ed Support",
	'378'	=> "Aftercare",
	'272'	=> "Transition Readiness",
	'370'	=> "Fundraising",
	'269'	=> "Shelter Operations",
	'4'		=> "Shelter Support",
	'5'		=> "Human Capital",
	'15'	=> "Finance",
	'11'	=> "Campaigns and Communications",
	'375'	=> "Foundational Programme",
];

// Data source - https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629
$requirements = getRequirementFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vTf7uqEdn1CWZjwG8YALAS52jGVMABAfo1Xpb6YR3g69jSHir_govSZvFz_F_J_ACX1W50byaNE0ibS/pub?output=csv");

$applicants = [];
foreach ($verticals as $group_id => $name) {
	$applicants[$group_id] = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
		INNER JOIN User U ON UGP.user_id=U.id 
		WHERE $city_check preference=1 AND group_id=$group_id");
}

$selected = [];
foreach ($verticals as $group_id => $name) {
	$selected[$group_id] = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
		INNER JOIN User U ON UGP.user_id=U.id 
		WHERE $city_check preference=1 AND group_id=$group_id AND UGP.status='selected'");
}

$multiplication_factor = 3;

render();

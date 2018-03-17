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
	WHERE $city_check 1=1");
$fellowship_applications = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE $city_check UGP.group_id IN (SELECT id FROM `Group` WHERE type='fellow' OR type='strat')");
$mentor_applications =  $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE $city_check UGP.group_id = 8");
$wingman_applications = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
	INNER JOIN User U ON UGP.user_id=U.id 
	WHERE $city_check (UGP.group_id = 348 OR UGP.group_id = 365)");

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
	'375'	=> "Foundation",	
];
$requirements = [
	'2'		=> 25,
	'19'	=> 40,
	'378'	=> 50,
	'272'	=> 50,
	'370'	=> 25,
	'269'	=> 70,
	'4'		=> 40,
	'5'		=> 25,
	'15'	=> 25,
	'11'	=> 15,
	'375'	=> 6,	
];
$applicants = [];
foreach ($verticals as $group_id => $name) {
	$applicants[$group_id] = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
		INNER JOIN User U ON UGP.user_id=U.id 
		WHERE $city_check group_id=$group_id");
}

$selected = [];
foreach ($verticals as $group_id => $name) {
	$selected[$group_id] = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP 
		INNER JOIN User U ON UGP.user_id=U.id 
		WHERE $city_check group_id=$group_id AND UGP.status='selected'");
}

render();

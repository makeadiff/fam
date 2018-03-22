<?php
require 'common.php';

$all_groups = $verticals = [
	'0'		=> 'Any',
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

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$group_id = i($QUERY, 'group_id', 0);
$city_id = i($QUERY, 'city_id');
$evaluator_id = i($QUERY, 'evaluator_id', 0);

$applicants_pager = $fam->getApplicants(['group_id' => $group_id, 'city_id' => $city_id], true);

$applicants = $applicants_pager->getPage();

render();

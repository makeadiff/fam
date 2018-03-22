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

$checks = ['1=1'];
if($group_id) $checks[] = "group_id=" . $group_id;
if($city_id) $checks[] = "city_id=" . $city_id;
if($evaluator_id) $checks[] = "evaluator_id=" . $evaluator_id;

$query = "SELECT UGP.id AS ugp, U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(G.name ORDER BY UGP.preference SEPARATOR ',') AS applied_groups, C.name AS city, UGP.preference
	FROM User U
	INNER JOIN City C ON C.id=U.city_id
	INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
	INNER JOIN `Group` G ON UGP.group_id=G.id
	WHERE " . implode(" AND ", $checks) . "
	GROUP BY UGP.user_id";
if($group_id) $query .= " ORDER BY UGP.preference";
else $query .= " ORDER BY C.name, U.name";

$applicants_pager = new SqlPager($query, 25);

$applicants = $applicants_pager->getPage();

render();

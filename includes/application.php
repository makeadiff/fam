<?php
$user_info = check_user();

$user_id = $user_info['user_id'];
$fam = new FAM;
$year = 2017;

// require dirname(__FILE__) . '/../../driller/models/Common.php';
$common = new Common;
$html = new HTML;

$user = $user_info['current_user'];
$user['groups'] = $common->getUserGroups($user['id']);

$user_groups_ids = array_keys(keyFormat($user['groups']));
$evaluators_group_id = 382;

if(!in_array('national', $user_info['groups']) and !in_array($evaluators_group_id, $user_groups_ids)) {
	die("Only directors and evaluators can access this app.");
}

$is_director = false;
foreach($user['groups'] as $grp) {
	if($grp['type'] == 'national') {
	  $is_director = true;
	  break;
	}
}

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


function showApplicantStatus($user_id, $stage_id) {
	global $fam;

	$status = $fam->getStageStatus($user_id, $stage_id);
	if($status['status'] == 'selected') echo '<span class="fa fa-check-circle success-message">Selected</span>';
	else if($status['status'] == 'rejected') echo '<span class="fa fa-times-circle error-message">Rejected</span>';
	else if($status['status'] == 'free-pool') echo '<span class="fa fa-info-circle" style="color: #397eb9;">Free Pool</span>';
	else if($status['status'] == 'maybe') echo '<span class="fa fa-question-circle" style="color: #a62c37;">Maybe</span>';
}

/// This will fetch the google spreadsheet PUBLISHED csv and convert it into a array.
function getRequirementFromSheet($sheet_url) {
	global $common;
	require 'includes/classes/ParseCSV.php';
	$sheet = new ParseCSV($sheet_url);

	$all_cities = keyFormat($common->getCities(), ['name', 'id']);

	// Transilation table for group_id => index in the spreadsheet.
	$keys = [
		'city_name'	=> 'A',
		'2'		=> 'G',	// City Team Lead
		'19'	=> 'I',	// Ed Support
		'378'	=> 'K',	// Aftercare
		'272'	=> 'J',	// Transition Readiness
		'370'	=> 'O',	// Fundraising
		'269'	=> 'L',	// Shelter Operations
		'4'		=> 'M',	// Shelter Support
		'5'		=> 'N',	// Human Capital
		'15'	=> 'P',	// Finance
		'11'	=> 'Q',	// Campaigns and Communications
		'375'	=> 'H',	// Foundational Programme
	];

	$requirements = [];
	$total_by_group = [];
	$total_by_city = [];
	foreach($sheet as $row_index => $row) {
		$city_name = $row[$keys['city_name']];
		if(!isset($all_cities[$city_name])) continue;
		$city_id = $all_cities[$city_name];

		foreach($keys as $group_id => $column_name) {
			if($group_id == 'city_name') continue;

			$requirements[$city_id][$group_id] = $row[$column_name];

			if(!isset($total_by_group[$group_id])) $total_by_group[$group_id] = 0;
			$total_by_group[$group_id] += $row[$column_name];
		}

		$total_by_city[$city_id] = $row['R'];
	}

	$requirements['total_group'] = $total_by_group;
	$requirements['total_city'] = $total_by_city;
	$requirements['total_group'][0] = $requirements['total_city'][0] = $sheet->getCell('R25');

	$requirements[0] = $total_by_group;
	return $requirements;
}

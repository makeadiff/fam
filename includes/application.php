<?php
global $user_info, $year, $user_id, $fam, $common, $user, $html, $user_groups_ids, $evaluators_group_id, $is_director, $verticals, $colors, $overall_statuses;

$user_info = check_user();

$year = get_year();
$user_id = $user_info['user_id'];

require __DIR__ . '/../models/FAM.php';
$fam = new FAM;
$common = new Common;
$html = new iframe\HTML\HTML;

$user = $user_info['user'];
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

define('GROUP_ID_CTL', 2);
define('GROUP_ID_ED', 19);
define('GROUP_ID_AFTERCARE', 378);
define('GROUP_ID_TR', 272);
define('GROUP_ID_FR', 370);
define('GROUP_ID_OPS', 269);
define('GROUP_ID_SOF', 4);
define('GROUP_ID_HC', 5);
define('GROUP_ID_FINANCE', 15);
define('GROUP_ID_PR', 11);
define('GROUP_ID_FP', 375);
define('GROUP_ID_MENTOR', 8);

$verticals = [
	GROUP_ID_CTL		=> "City Team Lead",
	GROUP_ID_ED			=> "Ed Support",
	GROUP_ID_AFTERCARE	=> "Aftercare",
	GROUP_ID_TR			=> "Transition Readiness",
	GROUP_ID_FR			=> "Fundraising",
	GROUP_ID_OPS		=> "Shelter Operations",
	GROUP_ID_SOF		=> "Shelter Support",
	GROUP_ID_HC			=> "Human Capital",
	GROUP_ID_FINANCE	=> "Finance",
	GROUP_ID_PR			=> "Campaigns and Communications",
	GROUP_ID_FP			=> "Foundational Programme",
	GROUP_ID_MENTOR		=> 'Mentors'
];

$colors = [
  'green'   => '#26B99A',
  'orange'  => '#f6b26b',
  'red'     => '#a62c37'
];

$overall_statuses = [
	''			=> 'All',
	'rejected' 	=> 'Shortlisted for Mentor',
	'pending'	=> 'In Progress',
	'withdrawn'	=> 'Withdrawn Application'
];

function showApplicantStatus($user_id, $stage_id,$group_id=0) {
	global $fam;

	$status = $fam->getStageStatus($user_id, $stage_id, $group_id);
	if($status['status'] == 'selected') echo '<span class="fa fa-check-circle success-message">Selected</span>';
	else if($status['status'] == 'rejected') echo '<span class="fa fa-times-circle error-message">Rejected</span>';
	else if($status['status'] == 'free-pool') echo '<span class="fa fa-info-circle" style="color: #397eb9;">Free Pool</span>';
	else if($status['status'] == 'maybe') echo '<span class="fa fa-question-circle" style="color: #a62c37;">Maybe</span>';
}

/// This will fetch the google spreadsheet PUBLISHED csv and convert it into a array.
function getRequirementFromSheet($sheet_url = '') {
	global $common;
	// Data source
	// 2018-19 Requirement Sheet - https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629
	// 2018-19 CSV - https://docs.google.com/spreadsheets/d/e/2PACX-1vTf7uqEdn1CWZjwG8YALAS52jGVMABAfo1Xpb6YR3g69jSHir_govSZvFz_F_J_ACX1W50byaNE0ibS/pub?output=csv
	// 2019-20 Requirement Sheet - https://docs.google.com/spreadsheets/d/1FsypDbY5KDpTwD5696Hz0ZSd1UZpMyrFNauoDWvLBGQ/edit?ts=5c90f9b4#gid=675197629
	$sheet_url = "https://docs.google.com/spreadsheets/d/e/2PACX-1vRzSwv2Yr5vT9YCjqRpraem2ZBpVKy2VT_UU9L2iA3364MIBiN1zhdVCX2bIq_3CIg7owI2yQx86q1q/pub?gid=675197629&single=true&output=csv";

	require 'includes/classes/ParseCSV.php';
	$sheet = new ParseCSV($sheet_url);
	// dump($sheet);

	$all_cities = keyFormat($common->getCities(), ['name', 'id']);

	// Transilation table for group_id => index in the spreadsheet.
	$keys = [
		'city_name'	=> ['A'],
		'2'		=> ['H','I','J'],		// City Team Lead
		'375'	=> ['K','L','M'],		// Foundation
		'19'	=> ['N','O','P'],		// Ed Support
		'272'	=> ['Q','R','S'],		// Transition Readiness
		'378'	=> ['T','U','V'],		// Aftercare
		'269'	=> ['W','X','Y'],		// Shelter Operations
		'4'		=> ['Z','AA','AB'],		// Shelter Support
		'5'		=> ['AC','AD','AE'],	// Human Capital
		'370'	=> ['AF','AG','AH'],	// Fundraising
		'15'	=> ['AI','AJ','AK'],	// Finance
		'11'	=> ['AL','AM','AN'],	// Campaigns and Communications
		'8'		=> ['AP','AQ','AR'],	// Mentors
	];

	$requirements = [];
	$total_by_group = [];
	$total_by_city = [];
	foreach($sheet as $row_index => $row) {
		$city_name = $row[$keys['city_name'][0]];
		if(!isset($all_cities[$city_name])) continue;
		$city_id = $all_cities[$city_name];

		foreach($keys as $group_id => $columns) {
			if($group_id == 'city_name') continue;

			$requirements[$city_id][$group_id] = 0;
			foreach ($columns as $column_index) {
				@$requirements[$city_id][$group_id] += $row[$column_index];
			}

			if(!isset($total_by_group[$group_id])) $total_by_group[$group_id] = 0;
			$total_by_group[$group_id] += $requirements[$city_id][$group_id];
		}

		$total_by_city[$city_id] = $row['AO'];
	}

	$requirements['total_group'] = $total_by_group;
	$requirements['total_city'] = $total_by_city;
	$requirements['total_group'][0] = $requirements['total_city'][0] = $sheet->getCell('R25');

	$requirements[0] = $total_by_group;
	return $requirements;
}

function getEmailFromSheet($sheet_url) {
	global $common;
	require 'includes/classes/ParseCSV.php';
	$sheet = new ParseCSV($sheet_url);
	$data = [];
	foreach($sheet as $row_index => $row) {
		// dump($row);
		$data[$row['A']] = strtolower($row['I']);
	}
	unset($data['id']); //Unset Header Row
	return $data;
}

function getCity($city_id,$sql){
  return $sql->getOne('SELECT name from City where id='.$city_id);
}

function generateCSV($array){
	if(!empty($array)){
		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv");
		header("Content-Disposition:attachment;filename=FAM_Applicant_Export.csv");
		$header = [];
		$first = $array[0];
		foreach ($first as $key => $value) {
			$header[] = strtoupper(str_replace('_',' ',$key));
		}
		fputcsv($output, $header);
		foreach($array as $values) {
		    fputcsv($output, $values);
		}
		fclose($output) or die("Can't close php://output");
		return $output;
	
	} else {
		return false;
	}
}

function clear_current_email($sql){
	return $sql->update('User',[
		'mad_email' => '',
	],'city_id < 26');
}

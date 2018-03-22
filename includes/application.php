<?php
$user_info = check_user();

$user_id = $user_info['user_id'];
$fam = new FAM;
$year = 2017;

require dirname(__FILE__) . '/../../driller/models/Common.php';
$common = new Common;
$html = new HTML;

$user = $user_info['current_user'];
$user['groups'] = $common->getUserGroups($user['id']);

$user_groups_ids = array_keys(keyFormat($user['groups']));
$evaluators_group_id = 382;

if(!in_array('national', $user_info['groups']) and !in_array($evaluators_group_id, $user_groups_ids)) {
	die("Only directors and evaluators can access this app.");
}

function showApplicantStatus($user_id, $stage_id) {
	global $fam;

	$status = $fam->getStageStatus($user_id, $stage_id);
	if($status['status'] == 'selected') echo '<span class="fa fa-check-circle success-message">Selected</span>';
	else if($status['status'] == 'rejected') echo '<span class="fa fa-times-circle error-message">Rejected</span>';
}

/// This will fetch the google spreadsheet PUBLISHED csv and convert it into a array.
function getRequirementFromSheet($sheet_url) {
	global $common;
	$contents = load($sheet_url);
	$lines = explode("\n", $contents);

	$all_cities = keyFormat($common->getCities(), ['name', 'id']);

	// Transilation table for group_id => index in the spreadsheet.
	$keys = [
		'city_name'	=> 0,
		'2'		=> 6,
		'19'	=> 8,
		'378'	=> 10,
		'272'	=> 9,
		'370'	=> 14,
		'269'	=> 11,
		'4'		=> 12,
		'5'		=> 13,
		'15'	=> 15,
		'11'	=> 16,
		'375'	=> 7,
	];

	$requirements = [];
	$line_index = 0;
	$total = [];
	foreach($lines as $l) {
		$data = str_getcsv($l);

		$city_name = $data[$keys['city_name']];
		if(!isset($all_cities[$city_name])) continue;
		$city_id = $all_cities[$city_name];

		foreach($keys as $group_id => $sheet_index) {
			if($group_id == 'city_name') continue;

			$requirements[$city_id][$group_id] = $data[$sheet_index];

			if(!isset($total[$group_id])) $total[$group_id] = 0;
			$total[$group_id] += $data[$sheet_index];
		}

		$line_index++;
		if($line_index > 25) break;
	}

	$requirements[0] = $total;
	return $requirements;
}

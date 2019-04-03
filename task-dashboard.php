<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_verticals = $verticals; unset($all_verticals[8]);

$all_tasks = array(
	'common_video' 	 		=> 'Common Task (Video)',
	'common_written' 	 	=> 'Common Task (Written)',
	'vertical'	 				=> 'Vertical Task',
	'all'				 					=> 'All'
);

$evaluation_statuses = array(
	'evaluated'					=> 'Evaluated',
	'all'								=> 'All'
);



$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.group_id <> 8 AND year=$year AND UGP.status <> 'withdrawn'");

$task_type = i($QUERY, 'task_type', 'all');
$evaluation_status = i($QUERY, 'evaluation_status', 'all');

if($task_type && $task_type=='common_video'){
	$task_check = " AND UT.common_task_url<>'' AND UT.year=$year";
}
else if($task_type && $task_type=='common_written'){
	$task_check = " AND UT.common_task_files<>'' AND UT.year=$year";
}
else if($task_type && $task_type=='vertical'){
	$task_check = " AND UT.preference_1_task_files<>'' AND UT.year=$year";
}
else{
	$task_check = " AND UT.common_task_url<>'' AND UT.year=$year";
}

$no_mentor_check = " AND UGP.group_id <> 8 ";

$requirements = getRequirementFromSheet();

$applications = [];
$submitted = [];
$evaluated = [];
$nonctl_fellow_applicants = [];
$ctl_fellow_applicants = [];
$ctl_ctl_applicants = [];

$tables = 'SELECT UGP.group_id, COUNT(DISTINCT UGP.user_id)
					 FROM FAM_UserGroupPreference UGP
					 INNER JOIN User U ON UGP.user_id=U.id';

foreach ($all_cities as $city => $city_name) {
	// $applications[$city_id] = array_combine(array_keys($verticals), array_fill(0, count($verticals), 0)); // Create init values.

	$applications[$city] = $sql->getById("$tables
		WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year
		AND UGP.status <> 'withdrawn' $no_mentor_check
		GROUP BY UGP.group_id");

	$nonctl_fellow_applicants[$city] = $sql->getById("$tables
		INNER JOIN UserGroup UG ON UG.user_id = U.id
		INNER JOIN `Group` G ON G.id = UG.group_id
		WHERE UGP.preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0
			AND U.city_id=$city)) AND UGP.year=$year AND UG.year=$year AND G.type = 'fellow' AND G.id <> 2 AND UGP.group_id <> 2
			AND UGP.status <> 'withdrawn' $no_mentor_check GROUP BY UGP.group_id");

	$ctl_fellow_applicants[$city] = $sql->getById("$tables
		INNER JOIN UserGroup UG ON UG.user_id = U.id
		INNER JOIN `Group` G ON G.id = UG.group_id
		WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year AND UG.year=$year AND G.type = 'fellow' AND UGP.group_id = 2
		AND UGP.status <> 'withdrawn' $no_mentor_check GROUP BY UGP.group_id");

	$ctl_ctl_applicants[$city] = $sql->getById("$tables
		INNER JOIN UserGroup UG ON UG.user_id = U.id
		INNER JOIN `Group` G ON G.id = UG.group_id
		WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year AND UG.year=$year AND G.type = 'fellow' AND G.id=2 AND UGP.group_id = 2
		AND UGP.status <> 'withdrawn' $no_mentor_check GROUP BY UGP.group_id");

	$submitted[$city] = $sql->getById("$tables
		INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
		WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year
		AND UGP.status <> 'withdrawn' $task_check $no_mentor_check
		GROUP BY UGP.group_id");

	$evaluated[$city] = $sql->getById("$tables
		INNER JOIN FAM_UserStage US ON US.user_id = U.id
		INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
		WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year AND US.year=$year
		AND US.stage_id=5 AND US.status<>'' AND US.status<>'pending' $task_check $no_mentor_check
		GROUP BY UGP.group_id");

}

$multiplication_factor = 3;

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'All';
$all_verticals[0] = 'All';
$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);


$city_check = '';
$city_check_ugp = '';
if($city_id) {
	$city_check = "U.city_id=$city_id AND ";
	$city_check_ugp = "((UGP.city_id != 0 AND UGP.city_id=$city_id) OR (UGP.city_id = 0 AND U.city_id=$city_id)) AND ";
}

$shortlisted = [];
$total_submitted = [];
$total_evaluated = [];
foreach ($verticals as $id => $name) {
	if($id == 8) continue;
	$shortlisted[$id] = $sql->getOne("SELECT COUNT(DISTINCT UGP.user_id)
																					FROM FAM_UserGroupPreference UGP
																					INNER JOIN User U ON UGP.user_id=U.id
																					WHERE $city_check_ugp preference=1 AND UGP.group_id=$id AND UGP.year=$year AND UGP.status <> 'withdrawn'");

	$total_submitted[$id] = $sql->getOne("SELECT COUNT(DISTINCT UGP.user_id)
																							FROM FAM_UserGroupPreference UGP
																							INNER JOIN User U ON UGP.user_id=U.id
																							INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
																							WHERE $city_check_ugp preference=1 AND UGP.group_id=$id AND UGP.year=$year AND UGP.status <> 'withdrawn' $task_check");

	$total_evaluated[$id] = $sql->getOne("SELECT COUNT(DISTINCT UGP.user_id)
																							FROM FAM_UserGroupPreference UGP
																							INNER JOIN User U ON UGP.user_id=U.id
																							INNER JOIN FAM_UserStage US ON US.user_id = U.id
																							INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
																							WHERE $city_check_ugp preference=1 AND UGP.group_id=$id AND UGP.year=$year AND US.year=$year AND UGP.status <> 'withdrawn'
																							AND US.stage_id=5 AND US.status<>'' AND US.status<>'pending' $task_check");
}

$selected = [];
foreach ($verticals as $id => $name) {
	$selected[$id] = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
		INNER JOIN User U ON UGP.user_id=U.id
		WHERE $city_check preference=1 AND group_id=$id AND UGP.status='selected' AND UGP.year=$year");
}


$all_applied = 0;
$all_submitted = 0;
$all_evaluated = 0;
$all_not_required = 0;

$total_verticals = [];
$total_cities = [];

foreach($all_cities as $city => $city_name) {
	if($city==0) continue;
	foreach($verticals as $id => $group_name) {
		if(!isset($total_verticals[$id]['applications'])) $total_verticals[$id]['applications'] = 0;
		if(!isset($total_verticals[$id]['submitted'])) $total_verticals[$id]['submitted'] = 0;
		if(!isset($total_verticals[$id]['evaluated'])) $total_verticals[$id]['evaluated'] = 0;
		if(!isset($total_verticals[$id]['not_required'])) $total_verticals[$id]['not_required'] = 0;

		if(isset($applications[$city][$id])) {
			$total_verticals[$id]['applications'] += i($applications[$city], $id, 0);
			$total_verticals[$id]['submitted'] += i($submitted[$city], $id, 0);
			$total_verticals[$id]['evaluated'] += i($evaluated[$city], $id, 0);
			$total_verticals[$id]['not_required'] += i($ctl_ctl_applicants[$city], $id, 0);
			$total_verticals[$id]['not_required'] += i($nonctl_fellow_applicants[$city], $id, 0);
		}

		if(!isset($total_cities[$city]['applications'])) $total_cities[$city]['applications'] = 0;
		if(!isset($total_cities[$city]['submitted'])) $total_cities[$city]['submitted'] = 0;
		if(!isset($total_cities[$city]['evaluated'])) $total_cities[$city]['evaluated'] = 0;
		if(!isset($total_cities[$city]['not_required'])) $total_cities[$city]['not_required'] = 0;

		if(isset($applications[$city][$id])){
			$total_cities[$city]['applications'] += i($applications[$city], $id, 0);
			$total_cities[$city]['submitted'] += i($submitted[$city], $id, 0);
			$total_cities[$city]['evaluated'] += i($evaluated[$city], $id, 0);
			$total_cities[$city]['not_required'] += i($ctl_ctl_applicants[$city], $id, 0);
			$total_cities[$city]['not_required'] += i($nonctl_fellow_applicants[$city], $id, 0);
		}
	}
	$all_applied += $total_cities[$city]['applications'];
	$all_submitted += $total_cities[$city]['submitted'];
	$all_evaluated += $total_cities[$city]['evaluated'];
	$all_not_required += $total_cities[$city]['not_required'];
}

render();

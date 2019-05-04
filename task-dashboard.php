<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_verticals = $verticals;

$all_tasks = array(
	'common_video' 	=> 'Common Task (Video)',
	'common_written'=> 'Common Task (Written)',
	'vertical'		=> 'Vertical Task',
	'all'			=> 'All'
);

$evaluation_statuses = array(
	'evaluated'	=> 'Evaluated',
	'all'		=> 'All'
);


$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.group_id <> 8 AND year=$year AND UGP.status <> 'withdrawn'");

$task_type = i($QUERY, 'task_type', 'all');
$evaluation_status = i($QUERY, 'evaluation_status', 'all');
$stage_clear_check = '';


$selects 	= "SELECT UGP.group_id, COUNT(DISTINCT UGP.user_id) FROM FAM_UserGroupPreference UGP";
$joins 		= "INNER JOIN User U ON UGP.user_id=U.id INNER JOIN UserGroup UG ON UG.user_id = U.id INNER JOIN `Group` G ON G.id = UG.group_id";

if($task_type && $task_type=='common_video'){
	$task_check = " AND UT.common_task_url<>'' AND UT.year=$year";
	$evaluation_stage = 3;
}
else if($task_type && $task_type=='common_written'){
	$task_check = " AND UT.common_task_files<>'' AND UT.year=$year";
	$evaluation_stage = 3;
}
else if($task_type && $task_type=='vertical'){
	$task_check = " AND UT.preference_1_task_files<>'' AND UT.year=$year";
	$joins .= ' INNER JOIN FAM_UserStage US ON US.user_id = U.id';
	$evaluation_stage = 5;
}
else{
	$task_check = " AND (UT.common_task_url<>'' OR UT.common_task_files<>'' OR UT.preference_1_task_files <> '') AND UT.year=$year";
	$evaluation_stage = 3;
}

$requirements = getRequirementFromSheet();
$requirements['total_group'][0] = array_sum($requirements['total_group']);
$requirements['total_city'][0] = array_sum($requirements['total_city']);

$applications = [];
$submitted = [];
$evaluated = [];
$nonctl_fellow_applicants = [];
$ctl_fellow_applicants = [];
$ctl_ctl_applicants = [];

$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);


// if($group_id!=8){
// 	$no_mentor_check = " AND UGP.group_id <> 8 ";
// }
// else{
	$no_mentor_check = "";
// }

foreach ($all_cities as $city => $city_name) {

	$conditions = "WHERE preference=1 AND ((UGP.city_id != 0 AND UGP.city_id=$city) OR (UGP.city_id = 0 AND U.city_id=$city)) AND UGP.year=$year AND UGP.status <> 'withdrawn'";

	if($task_type && $task_type=='vertical'){
		$conditions .= " AND US.stage_id = 3 AND US.status ='selected' AND US.year=$year";
	}

	$applications[$city] = $sql->getById("$selects $joins $conditions $no_mentor_check GROUP BY UGP.group_id");

	$nonctl_fellow_applicants[$city] = $sql->getById("$selects $joins $conditions AND UG.year=$year
		AND G.type = 'fellow' AND G.id <> 2 AND UGP.group_id <> 2
		$no_mentor_check GROUP BY UGP.group_id");

	$ctl_fellow_applicants[$city] = $sql->getById("$selects $joins $conditions AND UG.year=$year AND G.type = 'fellow' AND UGP.group_id = 2
		$no_mentor_check GROUP BY UGP.group_id");

	$ctl_ctl_applicants[$city] = $sql->getById("$selects $joins $conditions
		AND UG.year=$year AND G.type = 'fellow' AND G.id=2 AND UGP.group_id = 2
		$no_mentor_check GROUP BY UGP.group_id");

	$same_vertical_applicants[$city] = $sql->getById("$selects $joins $conditions
		AND UG.year=$year AND G.type = 'fellow' AND G.id=UGP.group_id
		$no_mentor_check GROUP BY UGP.group_id");

	$submitted[$city] = $sql->getById("$selects $joins
		INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
		$conditions $task_check $no_mentor_check
		GROUP BY UGP.group_id");

	$evaluated[$city] = $sql->getById("$selects $joins
		INNER JOIN FAM_UserStage US2 ON US2.user_id = U.id
		INNER JOIN FAM_UserTask UT ON UT.user_id = U.id
		$conditions AND US2.year=$year
		AND US2.stage_id=$evaluation_stage AND US2.status<>'' AND US2.status<>'pending' $task_check $no_mentor_check
		GROUP BY UGP.group_id");
}

$multiplication_factor = 3;

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'All';
$all_verticals[0] = 'All';

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

	if(!isset($shortlisted[$id])) $shortlisted[$id] = 0;
	if(!isset($total_submitted[$id])) $total_submitted[$id] = 0;
	if(!isset($total_evaluated[$id])) $total_evaluated[$id] = 0;

	foreach ($all_cities as $city => $city_name) {
		// if($id == 8) continue;
		if(isset($applications[$city][$id])) $shortlisted[$id] += $applications[$city][$id];
		if(isset($submitted[$city][$id])) $total_submitted[$id] += $submitted[$city][$id];
		if(isset($evaluated[$city][$id])) $total_evaluated[$id] += $evaluated[$city][$id];
	}

	$applications[0][$id] = $shortlisted[$id];
	$submitted[0][$id] = $total_submitted[$id];
	$evaluated[0][$id] = $total_evaluated[$id];
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
			if($task_type!='vertical'){
				$total_verticals[$id]['not_required'] += i($ctl_ctl_applicants[$city], $id, 0);
				$total_verticals[$id]['not_required'] += i($nonctl_fellow_applicants[$city], $id, 0);
			}else{
				$total_verticals[$id]['not_required'] += i($same_vertical_applicants[$city], $id, 0);
			}
		}

		if(!isset($total_cities[$city]['applications'])) $total_cities[$city]['applications'] = 0;
		if(!isset($total_cities[$city]['submitted'])) $total_cities[$city]['submitted'] = 0;
		if(!isset($total_cities[$city]['evaluated'])) $total_cities[$city]['evaluated'] = 0;
		if(!isset($total_cities[$city]['not_required'])) $total_cities[$city]['not_required'] = 0;

		if(isset($applications[$city][$id])){
			$total_cities[$city]['applications'] += i($applications[$city], $id, 0);
			$total_cities[$city]['submitted'] += i($submitted[$city], $id, 0);
			$total_cities[$city]['evaluated'] += i($evaluated[$city], $id, 0);
			if($task_type!='vertical'){
				$total_cities[$city]['not_required'] += i($ctl_ctl_applicants[$city], $id, 0);
				$total_cities[$city]['not_required'] += i($nonctl_fellow_applicants[$city], $id, 0);
			}else{
				$total_cities[$city]['not_required'] += i($same_vertical_applicants[$city], $id, 0);
			}
		}
	}
	$all_applied += $total_cities[$city]['applications'];
	$all_submitted += $total_cities[$city]['submitted'];
	$all_evaluated += $total_cities[$city]['evaluated'];
	$all_not_required += $total_cities[$city]['not_required'];
}

render();

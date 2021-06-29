<?php
require 'common.php';

$all_groups = $verticals;
$all_groups[0] = 'Any';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$group_id = i($QUERY, 'group_id', 0);
$city_id = i($QUERY, 'city_id', 0);
$task_status = i($QUERY, 'task-status', '');
$stage_id = i($QUERY, 'stage_id', 0);
$common_video_task= i($QUERY, 'common_video_task', 'any');
$common_written_task= i($QUERY, 'common_written_task', 'any');
$vertical_task= i($QUERY, 'vertical_task', 'any');

$all_stages = $fam->getStages();
$all_stages_input = [];
foreach ($all_stages as $key => $stages) {
	$all_stages_input[$stages['id']] = $stages['name'];
}
$all_stages_input[0] = 'Any';

$all_tasks_status = [
	'submitted'		=> 'Submitted',
	'not-submitted'	=> 'Not Submitted',
	'any'			=> 'Any'
];

$checks = ['1=1'];
$join = '';
$selects = '';
$join_condition = '';
if($group_id) {
	$checks[] = "UGP.group_id=$group_id";
	$join_condition = "AND UE.group_id=$group_id AND UE.year=$year";
}
if($city_id) {
	$checks[] = "((UGP.city_id != 0 AND UGP.city_id={$city_id}) OR (UGP.city_id = 0 AND U.city_id={$city_id}))";
}
if($stage_id) {
	$selects .= ', US.status, US.stage_id';
	$join .= 'INNER JOIN FAM_UserStage US ON US.user_id = U.id';
	$checks[] = 'US.stage_id='.$stage_id;
	$checks[] = 'US.year='.$year;
	if($task_status) {
		$checks[] = 'US.status="'.$status.'"';
	}
}

$selects .= ', UT.common_task_url, UT.common_task_files, UT.preference_1_task_files';
$join .= ' LEFT JOIN FAM_UserTask UT ON UGP.user_id = UT.user_id AND UT.year='.$year;

if($common_video_task == 'not-submitted') {
	$checks[] = "(UT.common_task_url IS NULL OR UT.common_task_url='')";
} elseif($common_video_task == 'submitted') {
	$checks[] = "UT.common_task_url != ''";
}
if($common_written_task == 'not-submitted') {
	$checks[] = "(UT.common_task_files IS NULL OR UT.common_task_files='')";
} elseif($common_written_task == 'submitted') {
	$checks[] = "UT.common_task_files != ''";
}
if($vertical_task == 'not-submitted') {
	$checks[] = "(UT.preference_1_task_files IS NULL OR UT.preference_1_task_files='')";
} elseif($vertical_task == 'submitted') {
	$checks[] = "UT.preference_1_task_files != ''";
}

$query = "SELECT U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(DISTINCT UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS groups,
					C.name AS city, UGP.preference, UGP.id AS ugp_id, E.name AS evaluator $selects
			FROM User U
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			LEFT JOIN FAM_UserEvaluator UE ON U.id=UE.user_id $join_condition
			LEFT JOIN User E ON E.id=UE.evaluator_id
			$join
			WHERE " . implode(" AND ", $checks) . " AND UGP.status != 'withdrawn' AND UGP.status != 'rejected' AND UGP.year=$year AND (UT.year=$year OR UT.year IS NULL)
			GROUP BY UGP.user_id";
if($group_id) $query .= " ORDER BY UGP.preference, C.name, U.name";
else $query .= " ORDER BY C.name, U.name";

$applicants_pager = new SqlPager($query, 25);
$applicants = $applicants_pager->getPage();

render();

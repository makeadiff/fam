<?php
require 'common.php';

$all_stages = $fam->getStages();
list($active_stage_id, $active_category_id) = explode("-", i($QUERY, 'stage', '1-1'));
$category_name = $fam->getCategory($active_category_id);
$group_id = i($QUERY, 'group_id', 2);

$all_connections = $sql->getById("SELECT user_id, evaluator_id FROM FAM_UserEvaluator WHERE evaluator_id != 0 AND group_id=$group_id");

// Remove applicants rejected during the last stage.
if($active_stage_id > 1) {
	$last_stage_id = $active_stage_id - 1;
	$rejected_applicants = $sql->getCol("SELECT user_id FROM FAM_UserStage WHERE stage_id<=$last_stage_id AND status='rejected'");

	foreach ($all_connections as $user_id => $applicant) {
		if(in_array($user_id, $rejected_applicants))
			unset($all_connections[$user_id]);
	}
}

$all_evaluators = [];

foreach($all_connections as $user_id => $evaluator_id) {
	if(!isset($all_evaluators[$evaluator_id])) {
		$all_evaluators[$evaluator_id] = [
			'assigned' => 0,
			'data_entered' => 0
		];
	}

	$all_evaluators[$evaluator_id]['assigned']++;
}

$cache_user_ids = array_keys($all_evaluators);

if($active_category_id) {
	$parameters_in_category = array_keys(keyFormat($fam->getParameters($active_stage_id, $active_category_id), ['id', 'name']));

	if(count($parameters_in_category)) {
		$data_entered = $sql->getAll("SELECT evaluator_id, COUNT(DISTINCT user_id) AS users 
			FROM FAM_Evaluation 
			WHERE parameter_id IN (" . implode(",", $parameters_in_category) . ") AND evaluator_id IN (" . implode(',', $cache_user_ids) . ")
			GROUP BY evaluator_id");

		foreach ($data_entered as $eval) {
			$all_evaluators[$eval['evaluator_id']]['data_entered'] = $eval['users'];
		}
	}
}

$all_users = [];
if($cache_user_ids) {
	$all_users = $sql->getById("SELECT U.id, U.name , C.name as city 
									FROM User U 
									INNER JOIN City C ON C.id=U.city_id 
									WHERE U.id IN (" . implode(",", $cache_user_ids) . ")");
}

render();

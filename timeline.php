<?php
require 'common.php';

$all_stages = $fam->getStages();
list($active_stage_id, $active_category_id) = explode("-", i($QUERY, 'stage', '1-1'));
$category_name = $fam->getCategory($active_category_id);

$all_applicants = $sql->getById("SELECT DISTINCT user_id, evaluator_id FROM FAM_UserGroupPreference WHERE evaluator_id != 0");

// Remove applicants rejected during the last stage.
if($active_stage_id > 1) {
	$last_stage_id = $active_stage_id - 1;
	$rejected_applicants = $sql->getCol("SELECT user_id FROM FAM_UserStage WHERE stage_id<=$last_stage_id AND status='rejected'");

	foreach ($all_applicants as $user_id => $evaluator_id) {
		if(in_array($user_id, $rejected_applicants))
			unset($all_applicants[$user_id]);
	}
}

$applicants_whos_data_is_not_entered = $all_applicants;

if($active_category_id) {
	$parameters_in_category = array_keys(keyFormat($fam->getParameters($active_stage_id, $active_category_id), ['id', 'name']));

	if(count($parameters_in_category)) {
		$data_entered_for = $sql->getCol("SELECT user_id FROM FAM_Evaluation WHERE parameter_id IN (" . implode(",", $parameters_in_category) . ")");

		foreach ($applicants_whos_data_is_not_entered as $user_id => $evaluator_id) {
			if(in_array($user_id, $data_entered_for))
				unset($applicants_whos_data_is_not_entered[$user_id]);
		}
	}
}

$cache_user_ids = array_keys($applicants_whos_data_is_not_entered);
$cache_user_ids = array_merge($cache_user_ids, array_unique(array_values($applicants_whos_data_is_not_entered)));
$all_users = [];
if($cache_user_ids) {
	$all_users = $sql->getById("SELECT id, name FROM User WHERE id IN (" . implode(",", $cache_user_ids) . ")");
}

render();
